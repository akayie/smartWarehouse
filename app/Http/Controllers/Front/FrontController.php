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
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FrontController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HOME & ABOUT
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        return view('front.home');
    }

    public function about()
    {
        return view('front.about');
    }

    /*
    |--------------------------------------------------------------------------
    | CAMPAIGNS / DISASTERS
    |--------------------------------------------------------------------------
    */

    public function campaigns()
    {
        $campaigns = Disaster::where('status', 'Active')
            ->latest()
            ->paginate(6);

        return view('front.campaigns', compact('campaigns'));
    }

    /*
    |--------------------------------------------------------------------------
    | MY RELIEF REQUESTS
    |--------------------------------------------------------------------------
    */

    public function myRequests()
    {
        $requests = ReliefRequest::with([
            'disaster',
            'warehouse',
            'requestItems.item',
        ])
            ->where('requested_by', Auth::id())
            ->latest()
            ->paginate(10);

        return view('front.my-requests', compact('requests'));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE RELIEF REQUEST PAGE
    |--------------------------------------------------------------------------
    */

    public function createRequest()
    {
        $disasters = Disaster::where('status', 'Active')
            ->latest()
            ->get();

        $warehouses = Warehouse::orderBy('name')->get();

        return view('front.request_relief', compact('disasters', 'warehouses'));
    }

    /*
    |--------------------------------------------------------------------------
    | GET WAREHOUSE ITEMS - AJAX
    |--------------------------------------------------------------------------
    */

    public function getWarehouseItems($warehouseId)
    {
        $inventories = Inventory::with(['item:id,name,unit'])
            ->where('warehouse_id', $warehouseId)
            ->where('quantity', '>', 0)
            ->get();

        return response()->json($inventories);
    }

    /*
    |--------------------------------------------------------------------------
    | STORE RELIEF REQUEST
    |--------------------------------------------------------------------------
    */

    public function storeRequest(StoreReliefRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
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

            $reliefRequest = ReliefRequest::create([
                'disaster_id'  => $disasterId,
                'warehouse_id' => $validated['warehouse_id'],
                'requested_by' => Auth::id(),
                'location'     => $validated['location'],
                'latitude'     => $validated['latitude'] ?? null,
                'longitude'    => $validated['longitude'] ?? null,
                'request_date' => now(),
                'status'       => 'Pending',
                'note'         => $validated['note'] ?? null,
            ]);

            foreach ($validated['items'] as $itemData) {
                RequestItem::create([
                    'request_id' => $reliefRequest->id,
                    'item_id'    => $itemData['item_id'],
                    'quantity'   => $itemData['quantity'],
                ]);
            }

            DB::commit();

            return redirect()
                ->route('public.my-requests')
                ->with('success', 'ကယ်ဆယ်ရေးအကူအညီတောင်းခံလွှာ ပေးပို့မှု အောင်မြင်ပါသည်။');

        } catch (Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'အမှားအယွင်း ဖြစ်ပေါ်နေပါသည်: ' . $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE DONATION PAGE
    |--------------------------------------------------------------------------
    */

    public function createDonation()
    {
        $categories = Category::orderBy('name')->get();
        $items      = Item::where('status', 'Active')->orderBy('name')->get();
        $warehouses = Warehouse::orderBy('name')->get();

        return view('front.donate', compact('categories', 'items', 'warehouses'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE DONATION
    |--------------------------------------------------------------------------
    */

    public function storeDonation(StoreDonationRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $user = Auth::user();

            if (!$user) {
                throw new Exception('အသုံးပြုသူအကောင့်သို့ Login ဝင်ထားရန် လိုအပ်ပါသည်။');
            }

            // Find or Create Donor
            $donor = Donor::firstOrCreate(
                ['email' => $user->email],
                [
                    'name'  => $user->name,
                    'phone' => $user->phone ?? null,
                ]
            );

            // Create Donation
            $donation = Donation::create([
                'donor_id'      => $donor->id,
                'warehouse_id'  => $validated['warehouse_id'],
                'donation_type' => $validated['donation_type'],
                'donation_date' => $validated['donation_date'],
                'status'        => 'Pending',
                'note'          => $validated['note'] ?? null,
            ]);

            // Save Cash Payment Details (if Cash or Both)
            if (in_array($validated['donation_type'], ['Cash', 'Both'])) {
                $proofPath = null;
                if ($request->hasFile('proof')) {
                    $proofPath = $request->file('proof')->store('donation-proofs', 'public');
                }

                DonationPayment::create([
                    'donation_id'           => $donation->id,
                    'payment_method'        => $validated['payment_method'] ?? null,
                    'transaction_reference' => $validated['transaction_reference'] ?? null,
                    'payment_date'          => $validated['donation_date'],
                    'account_name'          => $validated['account_name'] ?? null,
                    'account_number'        => $validated['account_number'] ?? null,
                    'amount'                => $validated['amount'] ?? 0,
                    'currency'              => 'MMK',
                    'proof'                 => $proofPath,
                    'status'                => 'Pending',
                    'note'                  => $validated['note'] ?? null,
                ]);
            }

            // Save Item Details (if Item or Both)
            if (in_array($validated['donation_type'], ['Item', 'Both'])) {
                if (!empty($validated['items'])) {
                    foreach ($validated['items'] as $itemData) {
                        if (empty($itemData['item_id']) || empty($itemData['quantity'])) {
                            continue;
                        }

                        DonationItem::create([
                            'donation_id' => $donation->id,
                            'item_id'     => $itemData['item_id'],
                            'quantity'    => $itemData['quantity'],
                            'unit'        => $itemData['unit'] ?? null,
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

    /*
    |--------------------------------------------------------------------------
    | DONATION HISTORY
    |--------------------------------------------------------------------------
    */

    public function donationHistory()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $donor = Donor::where('email', $user->email)->first();

        $donations = $donor
            ? Donation::with(['donor', 'warehouse', 'donationItems.item', 'payment'])
                ->where('donor_id', $donor->id)
                ->latest()
                ->paginate(10)
            : new LengthAwarePaginator([], 0, 10);

        return view('front.donation-history', compact('donations'));
    }
}
