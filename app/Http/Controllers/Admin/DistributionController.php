<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DistributionRequest;
use App\Models\Distribution;
use App\Models\DistributionItem;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\ReliefRequest;
use App\Models\StockMovement;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\DonationPayment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DistributionController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->middleware([
            'auth',
            'role:admin,warehouse_manager,manager'
        ]);
    }


    /* =========================================================
       INDEX
    ========================================================= */

    public function index(Request $request)
    {
        $query = Distribution::with([
            'reliefRequest',
            'warehouse',
            'handledBy',
            'items.item',
        ]);

        /* -----------------------------------------------------
           SEARCH
        ----------------------------------------------------- */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where(
                    'distribution_no',
                    'like',
                    "%{$search}%"
                )

                ->orWhereHas(
                    'reliefRequest',
                    function ($rq) use ($search) {

                        $rq->where(
                            'location',
                            'like',
                            "%{$search}%"
                        );
                    }
                )

                ->orWhereHas(
                    'warehouse',
                    function ($wq) use ($search) {

                        $wq->where(
                            'name',
                            'like',
                            "%{$search}%"
                        );
                    }
                );
            });
        }


        /* -----------------------------------------------------
           STATUS
        ----------------------------------------------------- */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }


        /* -----------------------------------------------------
           DATE FROM
        ----------------------------------------------------- */

        if ($request->filled('date_from')) {

            $query->whereDate(
                'distribution_date',
                '>=',
                $request->date_from
            );
        }


        /* -----------------------------------------------------
           DATE TO
        ----------------------------------------------------- */

        if ($request->filled('date_to')) {

            $query->whereDate(
                'distribution_date',
                '<=',
                $request->date_to
            );
        }


        /* -----------------------------------------------------
           PAGINATION
        ----------------------------------------------------- */

        $distributions = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();


        /* -----------------------------------------------------
           ITEM TOTAL

           Item Total = quantity × unit price

           IMPORTANT:
           ဒီဟာကို Funding Amount အဖြစ် မသုံးပါ။
        ----------------------------------------------------- */

        foreach ($distributions as $distribution) {

            $itemTotal = 0;

            foreach ($distribution->items as $distributionItem) {

                $quantity = (float) $distributionItem->quantity;

                $unitPrice = (float) (
                    $distributionItem->unit_price
                    ?? optional($distributionItem->item)->unit_price
                    ?? 0
                );

                $itemTotal +=
                    $quantity * $unitPrice;
            }

            $distribution->calculated_item_total =
                $itemTotal;


            /*
            |--------------------------------------------------------------------------
            | FUNDING AMOUNT
            |--------------------------------------------------------------------------
            |
            | Donation Funding မှ တကယ်အသုံးပြုထားသောငွေ
            |
            */

            $distribution->calculated_funding_amount =
                (float) ($distribution->funding_amount ?? 0);
        }


        return view(
            'admin.distributions.index',
            compact('distributions')
        );
    }


    /* =========================================================
       CREATE
    ========================================================= */

    public function create()
    {
        /* -----------------------------------------------------
           ACTIVE WAREHOUSES
        ----------------------------------------------------- */

        $warehouses = Warehouse::where(
            'status',
            'Active'
        )
        ->orderBy('name')
        ->get();


        /* -----------------------------------------------------
           USERS
        ----------------------------------------------------- */

        $users = User::orderBy('name')->get();


        /* -----------------------------------------------------
           ITEMS
        ----------------------------------------------------- */

        $items = Item::orderBy('name')->get();


        /* -----------------------------------------------------
           APPROVED REQUESTS
        ----------------------------------------------------- */

        $requests = ReliefRequest::with([
            'requestItems.item',
            'requestedBy',
            'disaster',
        ])
        ->where(
            'status',
            'Approved'
        )
        ->orderByDesc('id')
        ->get();


        /* -----------------------------------------------------
           INVENTORY
        ----------------------------------------------------- */

        $inventory = Inventory::get();


        /* -----------------------------------------------------
           INVENTORY DATA FOR JAVASCRIPT
        ----------------------------------------------------- */

        $inventoryData = $inventory
            ->map(function ($inventory) {

                return [
                    'id' => $inventory->id,

                    'warehouse_id' =>
                        (int) $inventory->warehouse_id,

                    'item_id' =>
                        (int) $inventory->item_id,

                    'quantity' =>
                        (int) $inventory->quantity,

                    'expiry_date' =>
                        $inventory->expiry_date
                            ? $inventory->expiry_date->format('Y-m-d')
                            : null,

                    'alert_quantity' =>
                        (int) (
                            $inventory->alert_quantity ?? 0
                        ),
                ];
            })
            ->values();


        /* =====================================================
           DONATION FUNDING
        ===================================================== */

        $user = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | COMPLETED DONATION PAYMENTS
        |--------------------------------------------------------------------------
        */

        $donationQuery = DonationPayment::query()
            ->where(
                'status',
                'Completed'
            )
            ->whereHas(
                'donation',
                function ($query) use ($user) {

                    if (
                        in_array(
                            $user->role,
                            [
                                'warehouse_manager',
                                'manager'
                            ]
                        )
                    ) {

                        $query->where(
                            'warehouse_id',
                            $user->warehouse_id
                        );
                    }
                }
            );


        /*
        |--------------------------------------------------------------------------
        | TOTAL DONATION
        |--------------------------------------------------------------------------
        */

        $totalDonationAmount =
            (float) $donationQuery->sum('amount');


        /*
        |--------------------------------------------------------------------------
        | USED FUNDING
        |--------------------------------------------------------------------------
        */

        $usedFundingQuery =
            Distribution::query()
                ->whereNotNull('funding_amount');


        if (
            in_array(
                $user->role,
                [
                    'warehouse_manager',
                    'manager'
                ]
            )
        ) {

            $usedFundingQuery->where(
                'warehouse_id',
                $user->warehouse_id
            );
        }


        $usedFundingAmount =
            (float) $usedFundingQuery->sum(
                'funding_amount'
            );


        /*
        |--------------------------------------------------------------------------
        | AVAILABLE FUNDING
        |--------------------------------------------------------------------------
        */

        $availableFundingAmount = max(
            0,
            $totalDonationAmount
            -
            $usedFundingAmount
        );


        return view(
            'admin.distributions.create',
            compact(
                'warehouses',
                'users',
                'items',
                'requests',
                'inventory',
                'inventoryData',
                'totalDonationAmount',
                'usedFundingAmount',
                'availableFundingAmount'
            )
        );
    }


    /* =========================================================
       STORE
    ========================================================= */

    public function store(
    DistributionRequest $request
): RedirectResponse {

    $user = Auth::user();

    /*
    |--------------------------------------------------------------------------
    | WAREHOUSE PERMISSION
    |--------------------------------------------------------------------------
    */

    if (
        in_array(
            $user->role,
            ['warehouse_manager', 'manager']
        )
    ) {

        if (!$user->warehouse_id) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'သင့်ထံတွင် Warehouse Assign လုပ်ထားခြင်းမရှိပါ။'
                );
        }

        if (
            (int) $request->warehouse_id
            !==
            (int) $user->warehouse_id
        ) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'သင့်အား သတ်မှတ်ထားသော Warehouse အတွက်သာ Distribution ဖန်တီးခွင့်ရှိပါသည်။'
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | FUNDING AMOUNT
    |--------------------------------------------------------------------------
    */

    $fundingAmount = (float) (
        $request->funding_amount ?? 0
    );


    /*
    |--------------------------------------------------------------------------
    | DONATION FUNDING BALANCE
    |--------------------------------------------------------------------------
    */

    $donationQuery = DonationPayment::query()
        ->where('status', 'Completed')
        ->whereHas('donation', function ($query) use ($user) {

            if (
                in_array(
                    $user->role,
                    ['warehouse_manager', 'manager']
                )
            ) {

                $query->where(
                    'warehouse_id',
                    $user->warehouse_id
                );
            }
        });


    $totalDonationAmount = (float)
        $donationQuery->sum('amount');


    /*
    |--------------------------------------------------------------------------
    | USED FUNDING
    |--------------------------------------------------------------------------
    */

    $usedFundingAmount = (float)
        Distribution::query()
            ->when(
                in_array(
                    $user->role,
                    ['warehouse_manager', 'manager']
                ),
                function ($query) use ($user) {

                    $query->where(
                        'warehouse_id',
                        $user->warehouse_id
                    );

                }
            )
            ->sum('funding_amount');


    /*
    |--------------------------------------------------------------------------
    | AVAILABLE FUNDING
    |--------------------------------------------------------------------------
    */

    $availableFundingAmount = max(
        0,
        $totalDonationAmount
        -
        $usedFundingAmount
    );


    /*
    |--------------------------------------------------------------------------
    | VALIDATE FUNDING
    |--------------------------------------------------------------------------
    */

    if ($fundingAmount < 0) {

        return back()
            ->withInput()
            ->with(
                'error',
                'Funding Amount သည် 0 ထက် မနည်းရပါ။'
            );
    }


    if ($fundingAmount > $availableFundingAmount) {

        return back()
            ->withInput()
            ->with(
                'error',
                'Donation Funding လက်ကျန်ထက် ပိုမိုအသုံးပြု၍ မရပါ။'
            );
    }


    try {

        DB::transaction(function () use (
            $request,
            $fundingAmount
        ) {

            /*
            |--------------------------------------------------------------------------
            | CREATE DISTRIBUTION
            |--------------------------------------------------------------------------
            */

            $distribution = Distribution::create([

                'request_id' =>
                    $request->request_id,

                'warehouse_id' =>
                    $request->warehouse_id,

                'handled_by' =>
                    $request->handled_by
                    ?? Auth::id(),

                'distribution_date' =>
                    $request->distribution_date,

                'status' =>
                    $request->status
                    ?? 'Completed',

                /*
                |--------------------------------------------------------------------------
                | IMPORTANT
                |--------------------------------------------------------------------------
                | Donation မှ အသုံးပြုတဲ့ Funding Amount
                | Item Total Payment မဟုတ်ပါ
                |--------------------------------------------------------------------------
                */

                'funding_amount' =>
                    $fundingAmount,

                'note' =>
                    $request->note,
            ]);


            /*
            |--------------------------------------------------------------------------
            | DISTRIBUTION ITEMS
            |--------------------------------------------------------------------------
            */

            foreach (
                $request->items
                as $itemData
            ) {

                $itemId =
                    (int) $itemData['item_id'];

                $qty =
                    (int) $itemData['quantity'];

                $expiryDate =
                    $itemData['expiry_date']
                    ?? null;


                /*
                |--------------------------------------------------------------------------
                | VALIDATE QUANTITY
                |--------------------------------------------------------------------------
                */

                if ($qty <= 0) {

                    throw new \Exception(
                        "Quantity သည် 0 ထက်ကြီးရပါမည်။ Item ID: {$itemId}"
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | FIND INVENTORY
                |--------------------------------------------------------------------------
                */

                $inventoryQuery =
                    Inventory::where(
                        'warehouse_id',
                        $request->warehouse_id
                    )
                    ->where(
                        'item_id',
                        $itemId
                    );


                /*
                |--------------------------------------------------------------------------
                | EXPIRY DATE
                |--------------------------------------------------------------------------
                */

                if ($expiryDate) {

                    $inventoryQuery->whereDate(
                        'expiry_date',
                        $expiryDate
                    );

                } else {

                    $inventoryQuery->whereNull(
                        'expiry_date'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | LOCK INVENTORY
                |--------------------------------------------------------------------------
                */

                $inventory =
                    $inventoryQuery
                        ->lockForUpdate()
                        ->first();


                if (!$inventory) {

                    throw new \Exception(
                        "ဤ Item အတွက် Inventory စာရင်း မတွေ့ပါ။ Item ID: {$itemId}"
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | STOCK CHECK
                |--------------------------------------------------------------------------
                */

                if (
                    $inventory->quantity
                    <
                    $qty
                ) {

                    throw new \Exception(
                        "Stock မလုံလောက်ပါ။ Item ID: {$itemId}၊ " .
                        "လက်ကျန်: {$inventory->quantity}၊ " .
                        "ဖြန့်ဝေမည့်ပမာဏ: {$qty}"
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | CREATE DISTRIBUTION ITEM
                |--------------------------------------------------------------------------
                */

                DistributionItem::create([

                    'distribution_id' =>
                        $distribution->id,

                    'item_id' =>
                        $itemId,

                    'quantity' =>
                        $qty,
                ]);


                /*
                |--------------------------------------------------------------------------
                | DEDUCT INVENTORY
                |--------------------------------------------------------------------------
                */

                $inventory->decrement(
                    'quantity',
                    $qty
                );


                $inventory->refresh();


                /*
                |--------------------------------------------------------------------------
                | STOCK MOVEMENT
                |--------------------------------------------------------------------------
                */

                StockMovement::create([

                    'item_id' =>
                        $itemId,

                    'warehouse_id' =>
                        $request->warehouse_id,

                    'type' =>
                        'OUT',

                    'quantity' =>
                        $qty,

                    'balance_after' =>
                        $inventory->quantity,

                    'expiry_date' =>
                        $expiryDate,

                    'reference' =>
                        'DIST-' .
                        $distribution->id,

                    'note' =>
                        'Distributed via Request #' .
                        (
                            $request->request_id
                            ??
                            'Direct'
                        ),

                    'created_by' =>
                        $request->handled_by
                        ??
                        Auth::id(),
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | UPDATE RELIEF REQUEST
            |--------------------------------------------------------------------------
            */

            if ($request->request_id) {

                ReliefRequest::where(
                    'id',
                    $request->request_id
                )
                ->update([
                    'status' =>
                        'Distributed',
                ]);
            }
        });


        return redirect()
            ->route(
                'backend.distributions.index'
            )
            ->with(
                'success',
                'ကယ်ဆယ်ရေးပစ္စည်း ဖြန့်ဝေမှုကို အောင်မြင်စွာ သိမ်းဆည်းပြီး Donation Funding Amount ကိုလည်း မှတ်တမ်းတင်ပြီးပါပြီ။'
            );


    } catch (\Throwable $e) {

        return back()
            ->withInput()
            ->with(
                'error',
                $e->getMessage()
            );
    }
}

    /* =========================================================
       SHOW
    ========================================================= */

    public function show(
        Distribution $distribution
    ): View {

        $this->checkDistributionAccess(
            $distribution
        );


        $distribution->load([
            'reliefRequest',
            'reliefRequest.disaster',
            'reliefRequest.requestedBy',
            'warehouse',
            'handledBy',
            'items.item',
        ]);


        /*
        |--------------------------------------------------------------------------
        | ITEM TOTAL
        |--------------------------------------------------------------------------
        */

        $itemTotal = 0;


        foreach (
            $distribution->items
            as $distributionItem
        ) {

            $quantity =
                (float) $distributionItem->quantity;

            $unitPrice =
                (float) (
                    $distributionItem->unit_price
                    ??
                    optional(
                        $distributionItem->item
                    )->unit_price
                    ??
                    0
                );


            $itemTotal +=
                $quantity * $unitPrice;
        }


        $distribution->calculated_item_total =
            $itemTotal;


        /*
        |--------------------------------------------------------------------------
        | FUNDING
        |--------------------------------------------------------------------------
        */

        $distribution->calculated_funding_amount =
            (float) (
                $distribution->funding_amount
                ?? 0
            );


        return view(
            'admin.distributions.show',
            compact('distribution')
        );
    }


    /* =========================================================
       EDIT
    ========================================================= */

    public function edit(
        Distribution $distribution
    ): View {

        $this->checkDistributionAccess(
            $distribution
        );


        $user = Auth::user();


        $distribution->load([
            'distributionItems.item'
        ]);


        $requestsQuery =
            ReliefRequest::with([
                'disaster',
                'requestItems.item',
            ]);


        $warehousesQuery =
            Warehouse::where(
                'status',
                'Active'
            );


        if (
            in_array(
                $user->role,
                [
                    'warehouse_manager',
                    'manager'
                ]
            )
        ) {

            $requestsQuery->where(
                'warehouse_id',
                $user->warehouse_id
            );


            $warehousesQuery->where(
                'id',
                $user->warehouse_id
            );
        }


        $requests =
            $requestsQuery
                ->latest('id')
                ->get();


        $warehouses =
            $warehousesQuery
                ->orderBy('name')
                ->get();


        $users =
            User::orderBy('name')
                ->get();


        $items =
            Item::orderBy('name')
                ->get();


        return view(
            'admin.distributions.edit',
            compact(
                'distribution',
                'requests',
                'warehouses',
                'users',
                'items'
            )
        );
    }


    /* =========================================================
       UPDATE
    ========================================================= */

    /* =========================================================
       UPDATE
    ========================================================= */

    public function update(
        DistributionRequest $request,
        Distribution $distribution
    ): RedirectResponse {

        $user = Auth::user();

        $this->checkDistributionAccess(
            $distribution
        );

        /*
        |--------------------------------------------------------------------------
        | WAREHOUSE
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $user->role,
                [
                    'warehouse_manager',
                    'manager'
                ]
            )
        ) {

            if (
                (int) $request->warehouse_id
                !==
                (int) $user->warehouse_id
            ) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'သတ်မှတ်ထားသော Warehouse အတွက်သာ ပြင်ဆင်ခွင့်ရှိပါသည်။'
                    );
            }

            $warehouseId =
                $distribution->warehouse_id;

        } else {

            $warehouseId =
                $request->warehouse_id;
        }

        try {

            DB::transaction(function () use (
                $request,
                $distribution,
                $warehouseId
            ) {

                /*
                |--------------------------------------------------------------------------
                | NEW FUNDING
                |--------------------------------------------------------------------------
                */

                $newFunding =
                    (float) (
                        $request->funding_amount
                        ?? 0
                    );

                if ($newFunding < 0) {

                    throw new \Exception(
                        'Funding Amount သည် အနုတ်မဖြစ်ရပါ။'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | DONATION TOTAL
                |--------------------------------------------------------------------------
                */

                $totalDonationAmount =
                    (float) DonationPayment::query()
                        ->where(
                            'status',
                            'Completed'
                        )
                        ->whereHas(
                            'donation',
                            function ($query) use (
                                $warehouseId
                            ) {

                                $query->where(
                                    'warehouse_id',
                                    $warehouseId
                                );
                            }
                        )
                        ->sum('amount');

                /*
                |--------------------------------------------------------------------------
                | USED FUNDING
                |
                | Current distribution ကို မထည့်ပါ။
                |--------------------------------------------------------------------------
                */

                $usedFundingAmount =
                    (float) Distribution::where(
                        'warehouse_id',
                        $warehouseId
                    )
                    ->where(
                        'id',
                        '!=',
                        $distribution->id
                    )
                    ->sum(
                        'funding_amount'
                    );

                /*
                |--------------------------------------------------------------------------
                | AVAILABLE
                |--------------------------------------------------------------------------
                */

                $availableFundingAmount =
                    max(
                        0,
                        $totalDonationAmount
                        -
                        $usedFundingAmount
                    );

                /*
                |--------------------------------------------------------------------------
                | FUNDING CHECK
                |--------------------------------------------------------------------------
                */

                if (
                    $newFunding
                    >
                    $availableFundingAmount
                ) {
                    throw new \Exception(
                        'Donation Funding လက်ကျန်ထက် ပိုမိုအသုံးပြု၍ မရပါ။'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | REVERSE OLD INVENTORY & STOCK MOVEMENTS
                |--------------------------------------------------------------------------
                */
                foreach ($distribution->items as $oldItem) {
                    $inventory = Inventory::where('warehouse_id', $distribution->warehouse_id)
                        ->where('item_id', $oldItem->item_id)
                        ->first();

                    if ($inventory) {
                        $inventory->increment('quantity', $oldItem->quantity);
                        $inventory->refresh();

                        StockMovement::create([
                            'item_id' => $oldItem->item_id,
                            'warehouse_id' => $distribution->warehouse_id,
                            'type' => 'IN',
                            'quantity' => $oldItem->quantity,
                            'balance_after' => $inventory->quantity,
                            'reference' => 'DIST-REV-' . $distribution->id,
                            'note' => 'Reversed due to Distribution Update #' . $distribution->id,
                            'created_by' => Auth::id(),
                        ]);
                    }
                }

                // Delete old distribution items
                $distribution->items()->delete();

                /*
                |--------------------------------------------------------------------------
                | PROCESS NEW ITEMS & DEDUCT INVENTORY
                |--------------------------------------------------------------------------
                */
                foreach ($request->items as $itemData) {
                    $itemId = (int) $itemData['item_id'];
                    $qty = (int) $itemData['quantity'];
                    $expiryDate = $itemData['expiry_date'] ?? null;

                    if ($qty <= 0) {
                        throw new \Exception("Quantity သည် 0 ထက်ကြီးရပါမည်။ Item ID: {$itemId}");
                    }

                    $inventoryQuery = Inventory::where('warehouse_id', $warehouseId)
                        ->where('item_id', $itemId);

                    if ($expiryDate) {
                        $inventoryQuery->whereDate('expiry_date', $expiryDate);
                    } else {
                        $inventoryQuery->whereNull('expiry_date');
                    }

                    $inventory = $inventoryQuery->lockForUpdate()->first();

                    if (!$inventory) {
                        throw new \Exception("ဤ Item အတွက် Inventory စာရင်း မတွေ့ပါ။ Item ID: {$itemId}");
                    }

                    if ($inventory->quantity < $qty) {
                        throw new \Exception("Stock မလုံလောက်ပါ။ Item ID: {$itemId}၊ လက်ကျန်: {$inventory->quantity}၊ ဖြန့်ဝေမည့်ပမာဏ: {$qty}");
                    }

                    DistributionItem::create([
                        'distribution_id' => $distribution->id,
                        'item_id' => $itemId,
                        'quantity' => $qty,
                    ]);

                    $inventory->decrement('quantity', $qty);
                    $inventory->refresh();

                    StockMovement::create([
                        'item_id' => $itemId,
                        'warehouse_id' => $warehouseId,
                        'type' => 'OUT',
                        'quantity' => $qty,
                        'balance_after' => $inventory->quantity,
                        'expiry_date' => $expiryDate,
                        'reference' => 'DIST-' . $distribution->id,
                        'note' => 'Distributed via Request #' . ($request->request_id ?? 'Direct'),
                        'created_by' => $request->handled_by ?? Auth::id(),
                    ]);
                }

                /*
                |--------------------------------------------------------------------------
                | UPDATE DISTRIBUTION DETAILS
                |--------------------------------------------------------------------------
                */

                $distribution->update([
                    'request_id' => $request->request_id,
                    'warehouse_id' => $warehouseId,
                    'handled_by' => $request->handled_by ?? Auth::id(),
                    'distribution_date' => $request->distribution_date,
                    'status' => $request->status ?? $distribution->status,
                    'funding_amount' => $newFunding,
                    'note' => $request->note,
                ]);

                /*
                |--------------------------------------------------------------------------
                | UPDATE RELIEF REQUEST STATUS
                |--------------------------------------------------------------------------
                */
                if ($request->request_id) {
                    ReliefRequest::where('id', $request->request_id)->update([
                        'status' => 'Distributed',
                    ]);
                }
            });

            return redirect()
                ->route('backend.distributions.index')
                ->with('success', 'Distribution အချက်အလက်များကို အောင်မြင်စွာ ပြင်ဆင်ပြီးပါပြီ။');

        } catch (\Throwable $e) {

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }


    /* =========================================================
       DESTROY
    ========================================================= */

    public function destroy(
        Distribution $distribution
    ): RedirectResponse {

        $this->checkDistributionAccess(
            $distribution
        );


        $distribution->delete();


        return redirect()
            ->route(
                'backend.distributions.index'
            )
            ->with(
                'success',
                'Distribution ကို အောင်မြင်စွာ ဖျက်ပြီးပါပြီ။'
            );
    }


    /* =========================================================
       ACCESS CHECK
    ========================================================= */

    private function checkDistributionAccess(
        Distribution $distribution
    ): void {

        $user = Auth::user();


        if (
            in_array(
                $user->role,
                [
                    'warehouse_manager',
                    'manager'
                ]
            )
        ) {

            if (
                !$user->warehouse_id
                ||
                (int) $distribution->warehouse_id
                !==
                (int) $user->warehouse_id
            ) {

                abort(
                    403,
                    'ဤ Distribution ကို အသုံးပြုခွင့်မရှိပါ။'
                );
            }
        }
    }
}
