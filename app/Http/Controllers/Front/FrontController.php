<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDonationRequest;
use App\Http\Requests\StoreReliefRequest;
use App\Models\Category;
use App\Models\Disaster;
use App\Models\Donation;
use App\Models\DonationItem;
use App\Models\DonationPayment;
use App\Models\Donor;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\ReliefRequest;
use App\Models\RequestItem;
use App\Models\Warehouse;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FrontController extends Controller
{
    public function index()
    {
        $totalItems = Inventory::sum('quantity');
        $totalWarehouses = Warehouse::count();
        $activeDisastersCount = Disaster::where('status', 'Active')->count();
        $familiesHelped = ReliefRequest::where('status', 'Approved')->count();

        return view('front.home', compact(
            'totalItems',
            'totalWarehouses',
            'activeDisastersCount',
            'familiesHelped'
        ));
    }

    public function about()
    {
        return view('front.about');
    }

    public function campaigns()
    {
        $campaigns = Disaster::where('status', 'Active')
            ->latest()
            ->paginate(6);

        return view('front.campaigns', compact('campaigns'));
    }

    public function myRequests()
    {
        $requests = ReliefRequest::withoutGlobalScopes()
            ->with([
                'disaster',
                'warehouse',
                'requestItems.item',
            ])
            ->latest('id')
            ->paginate(10);

        return view(
            'front.my-requests',
            compact('requests')
        );
    }

    public function createRequest()
    {
        $disasters = Disaster::where('status', 'Active')
            ->latest()
            ->get();

        $warehouses = Warehouse::orderBy('name')->get();

        return view('front.request_relief', compact('disasters', 'warehouses'));
    }

    public function getWarehouseItems($warehouseId)
    {
        $inventories = Inventory::with([
            'item:id,name,unit'
        ])
            ->where('warehouse_id', $warehouseId)
            ->where('quantity', '>', 0)
            ->get([
                'id',
                'warehouse_id',
                'item_id',
                'quantity',
            ]);

        return response()->json($inventories);
    }

public function storeRequest(StoreReliefRequest $request)
{
    $validated = $request->validated();

    DB::beginTransaction();

    try {

        /*
        |--------------------------------------------------------------------------
        | 1. Disaster
        |--------------------------------------------------------------------------
        */

        if ($validated['disaster_option'] === 'new') {

            $disaster = Disaster::create([
                'name'       => $validated['new_disaster_name'],
                'type'       => $validated['new_disaster_type'],
                'start_date' => $validated['start_date'],
                'end_date'   => $validated['end_date'] ?? null,
                'location'   => $validated['location'],
                'status'     => 'Active',
            ]);

            $disasterId = $disaster->id;

        } else {

            $disasterId = $validated['disaster_id'];
        }


        /*
        |--------------------------------------------------------------------------
        | 2. Medical Proof Upload
        |--------------------------------------------------------------------------
        */

        $medicalProofPath = null;

        if (
            isset($validated['is_health_related']) &&
            (int) $validated['is_health_related'] === 1
        ) {

            if ($request->hasFile('medical_proof')) {

                $medicalProofPath = $request
                    ->file('medical_proof')
                    ->store('medical_proofs', 'public');
            }
        }


        /*
        |--------------------------------------------------------------------------
        | 3. Create Relief Request
        |--------------------------------------------------------------------------
        */

        $reliefRequest = ReliefRequest::withoutGlobalScopes()->create([

            'disaster_id' => $disasterId,

            'warehouse_id' => $validated['warehouse_id'],

            'requested_by' => Auth::check()
                ? Auth::id()
                : null,

            // Requester Information
            'name' => $validated['name'],

            'phone_number' => $validated['phone_number'],

            // Health / Medical Information
            'is_health_related' =>
                (bool) ($validated['is_health_related'] ?? false),

            'medical_proof' => $medicalProofPath,

            // Location Information
            'location' => $validated['location'],

            'latitude' => $validated['latitude'] ?? null,

            'longitude' => $validated['longitude'] ?? null,

            // Request Information
            'request_date' => now(),

            'status' => 'Pending',

            'note' => $validated['note'] ?? null,
        ]);


        /*
        |--------------------------------------------------------------------------
        | 4. Save Requested Items
        |--------------------------------------------------------------------------
        */

        foreach ($validated['items'] as $itemData) {

            $quantity = (int) ($itemData['quantity'] ?? 0);

            if ($quantity <= 0) {
                continue;
            }

            RequestItem::create([
                'request_id' => $reliefRequest->id,
                'item_id' => $itemData['item_id'],
                'quantity' => $quantity,
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | 5. Commit
        |--------------------------------------------------------------------------
        */

        DB::commit();


        /*
        |--------------------------------------------------------------------------
        | 6. Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('public.my-requests')
            ->with(
                'success',
                'ကယ်ဆယ်ရေးအကူအညီ တောင်းခံလွှာကို အောင်မြင်စွာ ပေးပို့ပြီးပါပြီ။'
            );

    } catch (\Throwable $e) {

        DB::rollBack();

        return redirect()
            ->back()
            ->withInput()
            ->with(
                'error',
                'တောင်းခံလွှာ မအောင်မြင်ပါ။ Error: ' .
                $e->getMessage()
            );
    }
}

    public function createDonation()
    {
        $categories = Category::orderBy('name')->get();
        $items      = Item::where('status', 'Active')->orderBy('name')->get();
        $warehouses = Warehouse::orderBy('name')->get();

        return view('front.donate', compact('categories', 'items', 'warehouses'));
    }

    public function storeDonation(StoreDonationRequest $request)
    {
        DB::beginTransaction();

        try {
            $user = Auth::user();

            $donorName  = $request->donor_name;
            $donorPhone = $request->phone;
            $donorEmail = $request->email;

            $donor = Donor::firstOrCreate(
                ['email' => $donorEmail],
                [
                    'name'  => $donorName,
                    'phone' => $donorPhone,
                ]
            );

            $donation = Donation::create([
                'user_id'       => $user ? $user->id : null,
                'donor_id'      => $donor->id,
                'donor_name'    => $donorName,
                'phone'         => $donorPhone,
                'email'         => $donorEmail,
                'warehouse_id'  => $request->warehouse_id,
                'donation_type' => $request->donation_type,
                'donation_date' => $request->donation_date,
                'status'        => 'Pending',
                'note'          => $request->note ?? null,
            ]);

            if (in_array($request->donation_type, ['Cash', 'Both'])) {
                if (!empty($request->amount) && $request->amount > 0) {
                    DonationPayment::create([
                        'donation_id'           => $donation->id,
                        'payment_method'        => $request->payment_method ?? 'Cash',
                        'amount'                => $request->amount,
                        'payment_date'          => $request->donation_date,
                        'transaction_reference' => $request->transaction_reference ?? null,
                        'account_name'          => $request->account_name ?? null,
                        'account_number'        => $request->account_number ?? null,
                        'currency'              => $request->currency ?? 'MMK',
                        'status'                => 'Pending',
                        'note'                  => $request->payment_note ?? null,
                        'proof'                 => $request->hasFile('proof') ? $request->file('proof')->store('slips', 'public') : null,
                    ]);
                }
            }

            if (in_array($request->donation_type, ['Item', 'Both'])) {
                if (!empty($request->items)) {
                    foreach ($request->items as $itemData) {
                        $itemId   = $itemData['item_id'] ?? null;
                        $quantity = $itemData['quantity'] ?? 0;

                        if (empty($quantity)) {
                            continue;
                        }

                        if (empty($itemId) && !empty($itemData['new_item_name'])) {
                            $newItem = Item::create([
                                'name'        => $itemData['new_item_name'],
                                'category_id' => $itemData['category_id'] ?? null,
                                'unit'        => $itemData['unit'] ?? null,
                                'status'      => 'Active',
                            ]);
                            $itemId = $newItem->id;
                        }

                        if (empty($itemId)) {
                            continue;
                        }

                        DonationItem::create([
                            'donation_id'  => $donation->id,
                            'item_id'      => $itemId,
                            'quantity'     => $quantity,
                            'unit'         => $itemData['unit'] ?? null,
                            'expired_date' => $itemData['expired_date'] ?? null,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()
                ->route('public.don-history')
                ->with('success', 'လှူဒါန်းမှု အောင်မြင်စွာ တင်သွင်းပြီးပါပြီ။');

        } catch (Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'လှူဒါန်းမှု မအောင်မြင်ပါ။ ' . $e->getMessage());
        }
    }

    public function donationHistory()
    {
        $donations = Donation::with(['donor', 'warehouse', 'donationItems.item', 'payment'])
            ->latest()
            ->paginate(10);

        return view('front.donation-history', compact('donations'));
    }
}
