<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Disaster;
use App\Models\Distribution;
use App\Models\Donation;
use App\Models\DonationItem;
use App\Models\DonationPayment;
use App\Models\Donor;
use App\Models\Item;
use App\Models\ReliefRequest;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FrontController extends Controller
{
    public function index()
    {
        // 1. Total Items Stocked
        $totalItems = Inventory::sum('quantity');

        // 2. Active Warehouses Count (ဖြည့်စွက်ထားပါသည်)
        $totalWarehouses = Warehouse::count();

        // 3. Active Disaster Zones Count
        $activeDisastersCount = Disaster::where('status', 'Active')->count();

        // 4. Families Helped Count (Approved သို့မဟုတ် Completed ဖြစ်ပြီးသား Relief Requests များ)
        $familiesHelped = ReliefRequest::whereIn('status', ['Approved', 'Completed', 'Distributed'])->count();

        // 5. Active Disasters & Distributions Lists
        $activeDisasters = Disaster::where('status', 'Active')->latest()->take(3)->get();
        $recentDistributions = Distribution::with('warehouse')->latest()->take(5)->get();

        return view('front.home', compact(
            'totalItems',
            'totalWarehouses',
            'activeDisastersCount',
            'familiesHelped',
            'activeDisasters',
            'recentDistributions'
        ));
    }

    public function about()
    {
        return view('front.about');
    }

    public function campaigns()
    {
        $campaigns = Disaster::latest()->paginate(9);
        return view('front.campaigns', compact('campaigns'));
    }

    public function myRequests()
    {
        $userId = auth()->id() ?? 1;

        $requests = ReliefRequest::with(['disaster', 'requestItems.item'])
            ->where('requested_by', $userId)
            ->latest()
            ->paginate(10);

        return view('front.my-requests', compact('requests'));
    }

    public function donationHistory()
    {
        $donations = Donation::with(['payment', 'items.item', 'warehouse'])
            ->when(auth()->check(), function($query) {
                $user = auth()->user();
                $query->whereHas('donor', function($q) use ($user) {
                    $q->where('email', $user->email)
                      ->orWhere('phone', $user->phone);
                });
            })
            ->latest()
            ->paginate(10);

        return view('front.donation-history', compact('donations'));
    }

    // Request Form Pages
    public function createRequest()
    {
        $disasters = Disaster::where('status', 'Active')->get();
        return view('front.request', compact('disasters'));
    }

    public function storeRequest(Request $request)
    {
        $request->validate([
            'disaster_id' => 'required|exists:disasters,id',
            'location'    => 'required|string|max:255',
            'note'        => 'nullable|string',
        ]);

        ReliefRequest::create([
            'disaster_id'  => $request->disaster_id,
            'requested_by' => auth()->id() ?? 1,
            'location'     => $request->location,
            'request_date' => now(),
            'status'       => 'Pending',
            'note'         => $request->note,
        ]);

        return redirect()->back()->with('success', 'Aid request submitted successfully!');
    }

    // Donation Form Pages
    public function createDonation()
    {
        $items = Item::orderBy('name', 'asc')->get();
        return view('front.donate', compact('items'));
    }

    public function storeDonation(Request $request)
    {
        $request->validate([
            'donor_name'    => 'required|string|max:255',
            'phone'         => 'required|string|max:50',
            'email'         => 'nullable|email',
            'donation_type' => 'required|in:Cash,Food,Water,Clothing,Medical,Shelter,Hygiene,Rescue Equipment,Other',

            // Cash Validations
            'amount'        => 'required_if:donation_type,Cash|nullable|numeric|min:1',
            'proof'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            // Item Validations
            'quantity'      => 'required_unless:donation_type,Cash|nullable|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {
            // ၁။ Donor ဖန်တီးခြင်း / ရှာဖွေခြင်း
            $donor = Donor::firstOrCreate(
                ['phone' => $request->phone],
                ['name' => $request->donor_name, 'email' => $request->email]
            );

            // Default Warehouse ID
            $defaultWarehouse = Warehouse::first();
            $warehouseId = $defaultWarehouse ? $defaultWarehouse->id : 1;

            // ၂။ Master Donation Record
            $donation = Donation::create([
                'donor_id'      => $donor->id,
                'warehouse_id'  => $warehouseId,
                'donation_type' => $request->donation_type,
                'donation_date' => now(),
                'status'        => 'Pending',
                'note'          => $request->note,
            ]);

            // ၃။ Cash Donation
            if ($request->donation_type === 'Cash') {
                $proofPath = null;
                if ($request->hasFile('proof')) {
                    $proofPath = $request->file('proof')->store('payments', 'public');
                }

                DonationPayment::create([
                    'donation_id'           => $donation->id,
                    'payment_method'        => $request->payment_method ?? 'Cash In Hand',
                    'transaction_reference' => $request->transaction_reference,
                    'payment_date'          => now(),
                    'amount'                => $request->amount,
                    'currency'              => 'MMK',
                    'proof'                 => $proofPath,
                    'status'                => 'Completed',
                    'note'                  => $request->note,
                ]);
            }
            // ၄။ Item Donation
            else {
                $itemId = $request->item_id;

                if (!$itemId && $request->filled('new_item_name')) {
                    $item = Item::firstOrCreate(
                        ['name' => trim($request->new_item_name)],
                        [
                            'unit'          => $request->unit ?? 'Pcs',
                            'status'        => 'Active',
                            'minimum_stock' => 0,
                        ]
                    );
                    $itemId = $item->id;
                }

                if ($itemId) {
                    DonationItem::create([
                        'donation_id' => $donation->id,
                        'item_id'     => $itemId,
                        'quantity'    => $request->quantity,
                    ]);

                    $inventory = Inventory::firstOrCreate(
                        [
                            'warehouse_id' => $warehouseId,
                            'item_id'      => $itemId,
                        ],
                        [
                            'quantity'     => 0,
                        ]
                    );

                    $inventory->increment('quantity', $request->quantity);
                }
            }
        });

        return redirect()->back()->with('success', 'Thank you! Your donation has been recorded and stock updated.');
    }
}
