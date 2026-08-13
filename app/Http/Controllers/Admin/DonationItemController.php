<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DonationItem;
use App\Models\Donation;
use App\Models\Item;
use Illuminate\Http\Request;

class DonationItemController extends Controller
{
    /**
     * Display a listing of donation items with search & relationships.
     */
    public function index(Request $request)
    {
        $query = DonationItem::with([
            'donation.donor',
            'item'
        ]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('donation_id', 'like', "%{$search}%")
                  ->orWhereHas('donation.donor', function ($qDonor) use ($search) {
                      $qDonor->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('item', function ($qItem) use ($search) {
                      $qItem->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $donationItems = $query->latest('id')
            ->paginate(15)
            ->appends($request->all());

        // admin.donation_items.index သို့ ပြောင်းလဲထားပါသည်
        return view('admin.donation_items.index', compact('donationItems'));
    }

    /**
     * Show the form for creating a new donation item.
     */
    public function create()
    {
        $donations = Donation::with('donor')->latest()->get();
        $items = Item::all();

        return view('admin.donation_items.create', compact('donations', 'items'));
    }

    /**
     * Store a newly created donation item in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'donation_id' => 'required|exists:donations,id',
            'item_id'     => 'required|exists:items,id',
            'quantity'    => 'required|integer|min:1',
        ]);

        DonationItem::create([
            'donation_id' => $request->donation_id,
            'item_id'     => $request->item_id,
            'quantity'    => $request->quantity,
        ]);

        return redirect()->route('backend.donation_items.index')
            ->with('success', 'Donation item created successfully.');
    }

    /**
     * Display the specified donation item.
     */
    public function show($id)
    {
        $donationItem = DonationItem::with(['donation.donor', 'item'])->findOrFail($id);

        return view('admin.donation_items.show', compact('donationItem'));
    }

    /**
     * Show the form for editing the specified donation item.
     */
    public function edit($id)
    {
        $donationItem = DonationItem::findOrFail($id);
        $donations = Donation::with('donor')->latest()->get();
        $items = Item::all();

        return view('admin.donation_items.edit', compact('donationItem', 'donations', 'items'));
    }

    /**
     * Update the specified donation item in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'donation_id' => 'required|exists:donations,id',
            'item_id'     => 'required|exists:items,id',
            'quantity'    => 'required|integer|min:1',
        ]);

        $donationItem = DonationItem::findOrFail($id);
        $donationItem->update([
            'donation_id' => $request->donation_id,
            'item_id'     => $request->item_id,
            'quantity'    => $request->quantity,
        ]);

        return redirect()->route('backend.donation_items.index')
            ->with('success', 'Donation item updated successfully.');
    }

    /**
     * Remove the specified donation item from storage.
     */
    public function destroy($id)
    {
        $donationItem = DonationItem::findOrFail($id);
        $donationItem->delete();

        return redirect()->route('backend.donation_items.index')
            ->with('success', 'Donation item deleted successfully.');
    }
}
