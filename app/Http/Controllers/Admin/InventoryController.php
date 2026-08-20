<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\InventoryRequest;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\Warehouse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class InventoryController extends Controller
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

    /**
     * Display inventory list.
     */
    public function index(Request $request): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $query = Inventory::with([
            'warehouse',
            'item.category',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Warehouse Manager / Manager
        |--------------------------------------------------------------------------
        | Only show assigned warehouse inventory.
        |--------------------------------------------------------------------------
        */
        if (
            $user &&
            in_array($user->role, ['warehouse_manager', 'manager'])
        ) {
            if (!$user->warehouse_id) {
                abort(
                    403,
                    'သင့်ထံတွင် Warehouse Assign လုပ်ထားခြင်းမရှိပါ။'
                );
            }

            $query->where(
                'warehouse_id',
                $user->warehouse_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        | Search by item name or barcode.
        |--------------------------------------------------------------------------
        */
        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->whereHas('item', function ($q) use ($search) {

                $q->where(
                    'name',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'barcode',
                    'like',
                    "%{$search}%"
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Warehouse Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('warehouse_id')) {

            /*
            |--------------------------------------------------------------------------
            | Manager cannot filter another warehouse.
            |--------------------------------------------------------------------------
            */
            if (
                in_array(
                    $user->role,
                    ['warehouse_manager', 'manager']
                )
            ) {

                if (
                    (int) $request->warehouse_id
                    !==
                    (int) $user->warehouse_id
                ) {
                    abort(
                        403,
                        'အခြား Warehouse ၏ Inventory ကို ကြည့်ရှုခွင့်မရှိပါ။'
                    );
                }
            }

            $query->where(
                'warehouse_id',
                $request->warehouse_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Expiry Status Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('status')) {

            if ($request->status === 'expired') {

                $query->whereNotNull('expiry_date')
                    ->whereDate(
                        'expiry_date',
                        '<',
                        today()
                    );

            } elseif ($request->status === 'expiring_soon') {

                $query->whereNotNull('expiry_date')
                    ->whereDate(
                        'expiry_date',
                        '>=',
                        today()
                    )
                    ->whereDate(
                        'expiry_date',
                        '<=',
                        today()->addDays(30)
                    );

            } elseif ($request->status === 'available') {

                $query->where(function ($q) {

                    $q->whereNull('expiry_date')
                        ->orWhereDate(
                            'expiry_date',
                            '>=',
                            today()
                        );
                });
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */
        $inventories = $query
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Available Warehouses
        |--------------------------------------------------------------------------
        */
        $warehouses = $this->getAvailableWarehouses();

        return view(
            'admin.inventories.index',
            compact(
                'inventories',
                'warehouses'
            )
        );
    }

    /**
     * Show create form.
     */
    public function create(): View
    {
        $warehouses = $this->getAvailableWarehouses();

        $items = Item::where(
                'status',
                'Active'
            )
            ->orderBy('name')
            ->get();

        return view(
            'admin.inventories.create',
            compact(
                'warehouses',
                'items'
            )
        );
    }

    /**
     * Store inventory.
     */
    public function store(
        InventoryRequest $request
    ): RedirectResponse {

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $data = $request->validated();

        /*
        |--------------------------------------------------------------------------
        | Warehouse Manager / Manager
        |--------------------------------------------------------------------------
        | Force assigned warehouse.
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
                        'သင့်ထံတွင် Assign လုပ်ထားသော Warehouse မရှိပါ။'
                    );
            }

            $data['warehouse_id'] =
                $user->warehouse_id;
        }

        /*
        |--------------------------------------------------------------------------
        | Check existing inventory
        |--------------------------------------------------------------------------
        */
        $existing = Inventory::where(
                'warehouse_id',
                $data['warehouse_id']
            )
            ->where(
                'item_id',
                $data['item_id']
            )
            ->first();

        if ($existing) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'ဤ Warehouse တွင် ဤပစ္စည်း ရှိပြီးသားဖြစ်ပါသည်။ Update လုပ်ပါ။'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Create Inventory
        |--------------------------------------------------------------------------
        */
        Inventory::create([
            'warehouse_id' => $data['warehouse_id'],
            'item_id'      => $data['item_id'],
            'quantity'     => $data['quantity'],
            'expiry_date'  => $data['expiry_date'] ?? null,
        ]);

        return redirect()
            ->route(
                'backend.inventories.index'
            )
            ->with(
                'success',
                'Inventory ကို အောင်မြင်စွာ ထည့်သွင်းပြီးပါပြီ။'
            );
    }

    /**
     * Show inventory details.
     */
    public function show(
        Inventory $inventory
    ): View {

        $this->checkWarehouseAccess($inventory);

        $inventory->load([
            'warehouse',
            'item.category',
        ]);

        return view(
            'admin.inventories.show',
            compact('inventory')
        );
    }

    /**
     * Show edit form.
     */
    public function edit(
        Inventory $inventory
    ): View {

        $this->checkWarehouseAccess($inventory);

        $warehouses =
            $this->getAvailableWarehouses();

        $items = Item::where(
                'status',
                'Active'
            )
            ->orderBy('name')
            ->get();

        return view(
            'admin.inventories.edit',
            compact(
                'inventory',
                'warehouses',
                'items'
            )
        );
    }

    /**
     * Update inventory.
     */
    public function update(
        InventoryRequest $request,
        Inventory $inventory
    ): RedirectResponse {

        $this->checkWarehouseAccess($inventory);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $data = $request->validated();

        /*
        |--------------------------------------------------------------------------
        | Manager cannot change warehouse.
        |--------------------------------------------------------------------------
        */
        if (
            in_array(
                $user->role,
                ['warehouse_manager', 'manager']
            )
        ) {

            $data['warehouse_id'] =
                $inventory->warehouse_id;
        }

        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */
        $inventory->update([
            'warehouse_id' =>
                $data['warehouse_id'],

            'item_id' =>
                $data['item_id'],

            'quantity' =>
                $data['quantity'],

            'expiry_date' =>
                $data['expiry_date'] ?? null,
        ]);

        return redirect()
            ->route(
                'backend.inventories.index'
            )
            ->with(
                'success',
                'Inventory ကို အောင်မြင်စွာ ပြင်ဆင်ပြီးပါပြီ။'
            );
    }

    /**
     * Delete inventory.
     */
    public function destroy(
        Inventory $inventory
    ): RedirectResponse {

        $this->checkWarehouseAccess($inventory);

        $inventory->delete();

        return redirect()
            ->route(
                'backend.inventories.index'
            )
            ->with(
                'success',
                'Inventory ကို အောင်မြင်စွာ ဖျက်ပြီးပါပြီ။'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Helper: Available Warehouses
    |--------------------------------------------------------------------------
    */
    private function getAvailableWarehouses()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Warehouse Manager / Manager
        |--------------------------------------------------------------------------
        */
        if (
            $user &&
            in_array(
                $user->role,
                ['warehouse_manager', 'manager']
            )
        ) {

            return Warehouse::where(
                    'id',
                    $user->warehouse_id
                )
                ->where(
                    'status',
                    'Active'
                )
                ->orderBy('name')
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | Admin
        |--------------------------------------------------------------------------
        */
        return Warehouse::where(
                'status',
                'Active'
            )
            ->orderBy('name')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Helper: Warehouse Access
    |--------------------------------------------------------------------------
    */
    private function checkWarehouseAccess(
        Inventory $inventory
    ): void {

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (
            $user &&
            in_array(
                $user->role,
                ['warehouse_manager', 'manager']
            )
        ) {

            if (
                !$user->warehouse_id ||
                (int) $inventory->warehouse_id
                !==
                (int) $user->warehouse_id
            ) {

                abort(
                    403,
                    'ဤ Inventory ကို အသုံးပြုခွင့်မရှိပါ။'
                );
            }
        }
    }
}
