<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\DonationItem;
use App\Models\Donor;
use App\Models\Warehouse;
use App\Models\Item;
use App\Models\Inventory;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DonationController extends Controller
{
    /**
     * Display a listing of the donations.
     */
    public function index()
    {
        $donations = Donation::with(['donor', 'warehouse', 'donationItems.item'])
            ->latest('id')
            ->paginate(15);

        return view('admin.donations.index', compact('donations'));
    }

    /**
     * Show the form for creating a new donation.
     */
    public function create()
    {
        $donors = Donor::all();
        $warehouses = Warehouse::all();
        $items = Item::all();

        return view('admin.donations.create', compact('donors', 'warehouses', 'items'));
    }

    /**
     * Store a newly created donation in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'donor_id'     => 'required|exists:donors,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'donation_date'=> 'required|date',
            'status'       => 'required|in:Pending,Received,Cancelled',
            'items'        => 'required|array|min:1',
            'items.*.item_id'  => 'required|exists:items,id',
            'items.*.quantity' => 'required|numeric|min:1',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // 1. Save Donation Record
                $donation = Donation::create([
                    'donor_id'      => $request->donor_id,
                    'warehouse_id'  => $request->warehouse_id,
                    'donation_date' => $request->donation_date,
                    'status'        => 'Pending', // Default status Pending
                    'note'          => $request->note,
                ]);

                // 2. Save Donation Items
                foreach ($request->items as $itemData) {
                    DonationItem::create([
                        'donation_id' => $donation->id,
                        'item_id'     => $itemData['item_id'],
                        'quantity'    => $itemData['quantity'],
                    ]);
                }
            });

            return redirect()->route('backend.donations.index')
                ->with('success', 'Donation request created successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create donation: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified donation.
     */
    public function show($id)
    {
        $donation = Donation::with(['donor', 'warehouse', 'donationItems.item'])->findOrFail($id);

        return view('admin.donations.show', compact('donation'));
    }

    /**
     * Show the form for editing the specified donation.
     */
    public function edit($id)
    {
        $donation = Donation::with('donationItems')->findOrFail($id);
        $donors = Donor::all();
        $warehouses = Warehouse::all();
        $items = Item::all();

        return view('admin.donations.edit', compact('donation', 'donors', 'warehouses', 'items'));
    }

    /**
     * Update the specified donation in storage.
     */
    public function update(Request $request, $id)
    {
        $donation = Donation::findOrFail($id);

        if ($donation->status === 'Received') {
            return redirect()->back()->with('error', 'Cannot edit a received donation.');
        }

        $request->validate([
            'donor_id'     => 'required|exists:donors,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'donation_date'=> 'required|date',
            'note'          => 'nullable|string',
        ]);

        $donation->update([
            'donor_id'      => $request->donor_id,
            'warehouse_id'  => $request->warehouse_id,
            'donation_date' => $request->donation_date,
            'note'          => $request->note,
        ]);

        return redirect()->route('backend.donations.index')
            ->with('success', 'Donation updated successfully!');
    }

    /**
     * Remove the specified donation from storage.
     */
    public function destroy($id)
    {
        $donation = Donation::findOrFail($id);

        if ($donation->status === 'Received') {
            return redirect()->back()->with('error', 'Cannot delete a received donation.');
        }

        $donation->delete();

        return redirect()->route('backend.donations.index')
            ->with('success', 'Donation deleted successfully!');
    }

    /**
     * Receive Donation Action (Increase Inventory Stock & Log Stock Movement)
     */
    public function receive($id)
    {
        $donation = Donation::with('donationItems')->findOrFail($id);

        if ($donation->status === 'Received') {
            return redirect()->back()->with('error', 'Donation is already received.');
        }

        if ($donation->donationItems->isEmpty()) {
            return redirect()->back()->with('error', 'Cannot receive donation without any items.');
        }

        try {
            DB::transaction(function () use ($donation) {
                // A. Status ပြောင်းလဲခြင်း
                $donation->update(['status' => 'Received']);

                // B. Inventory Stock (+) တိုးပေးခြင်းနှင့် Stock Movement မှတ်ခြင်း
                foreach ($donation->donationItems as $dItem) {

                    // 1. Inventory Stock ရှာမည်/ မရှိပါက အသစ်တည်ဆောက်မည်
                    $inventory = Inventory::firstOrCreate(
                        [
                            'warehouse_id' => $donation->warehouse_id,
                            'item_id'      => $dItem->item_id,
                        ],
                        [
                            'quantity' => 0,
                        ]
                    );

                    // 2. Stock Quantity ပေါင်းပေးခြင်း (Stock +)
                    $inventory->increment('quantity', $dItem->quantity);

                    // 3. Stock Movement History မှတ်တမ်းတင်ခြင်း (Model Structure နှင့် ကိုက်ညီအောင် ပြင်ဆင်ထားပါသည်)
                    StockMovement::create([
                        'warehouse_id'  => $donation->warehouse_id,
                        'item_id'       => $dItem->item_id,
                        'type'          => 'in',
                        'quantity'      => $dItem->quantity,
                        'balance_after' => $inventory->quantity, // increment လုပ်ပြီးနောက် ကျန်ရှိသော လက်ကျန်စတော့
                        'reference'     => 'DONATION-' . $donation->id,
                        'note'          => 'Donation received (ID: ' . $donation->id . ')',
                        'created_by'    => auth()->id() ?? 1, // Login ဝင်ထားသူ ID (သို့မဟုတ် Default 1)
                    ]);
                }
            });

            return redirect()->back()->with('success', 'Donation received successfully and inventory stock updated!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to receive donation: ' . $e->getMessage());
        }
    }
}
