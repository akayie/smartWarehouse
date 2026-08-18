<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Warehouse;
use App\Models\Item;
use App\Http\Requests\InventoryRequest;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    /**
     * Display a listing of the inventories.
     */
    public function index()
    {
        // Global Scope ကြောင့် Manager ဖြစ်ပါက သူ၏ Warehouse Data သာ ထွက်လာမည်။
        $inventories = Inventory::with([
                'warehouse',
                'item'
            ])
            ->orderBy('id', 'DESC')
            ->paginate(15);

        return view(
            'admin.inventories.index',
            compact('inventories')
        );
    }

    /**
     * Show the form for creating a new inventory entry.
     */
    public function create()
    {
        $user = Auth::user();

        // Warehouse Manager ဖြစ်ပါက ၎င်းနှင့် သက်ဆိုင်သော Warehouse များကိုသာ Dropdown တွင် ပြမည်။
        if ($user->role === 'warehouse_manager') {
            $warehouses = $user->warehouses()
                ->where('status', 'Active')
                ->orderBy('name')
                ->get();
        } else {
            // Admin ဖြစ်ပါက Active ဖြစ်သော Warehouse အားလုံးကို ပြမည်။
            $warehouses = Warehouse::where('status', 'Active')
                ->orderBy('name')
                ->get();
        }

        $items = Item::where('status', 'Active')
            ->orderBy('name')
            ->get();

        return view(
            'admin.inventories.create',
            compact('warehouses', 'items')
        );
    }

    /**
     * Store a newly created or updated inventory in storage.
     */
    public function store(InventoryRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();

        // Warehouse Manager ဖြစ်ပါက Form မှ warehouse_id မပါခဲ့လျှင် မိမိ၏ ပထမဆုံး Warehouse ID ကို အလိုအလျောက် Assign လုပ်မည်။
        if ($user->role === 'warehouse_manager') {
            $warehouseId = $request->warehouse_id ?? $user->warehouses()->first()?->id;
        } else {
            $warehouseId = $request->warehouse_id;
        }

        // updateOrCreate ဖြင့် Duplicate Entry မဖြစ်အောင် ထိန်းသိမ်းမည်။
        Inventory::updateOrCreate(
            [
                'warehouse_id' => $warehouseId,
                'item_id'      => $data['item_id'],
            ],
            [
                'quantity'     => $data['quantity'],
            ]
        );

        return redirect()
            ->route('backend.inventories.index')
            ->with('success', 'Inventory saved successfully.');
    }

    /**
     * Display the specified inventory item.
     */
    public function show(Inventory $inventory)
    {
        // Policy ဖြင့် အခြား Warehouse ၏ Data ကို Direct URL မှ ကြည့်ရှုခြင်းအား တားဆီးမည်။
        $this->authorize('view', $inventory);

        $inventory->load([
            'warehouse',
            'item.category'
        ]);

        return view(
            'admin.inventories.show',
            compact('inventory')
        );
    }

    /**
     * Show the form for editing the specified inventory entry.
     */
    public function edit(Inventory $inventory)
    {
        // Policy ဖြင့် စစ်ဆေးမည်
        $this->authorize('update', $inventory);

        $user = Auth::user();

        if ($user->role === 'warehouse_manager') {
            $warehouses = $user->warehouses()
                ->where('status', 'Active')
                ->orderBy('name')
                ->get();
        } else {
            $warehouses = Warehouse::where('status', 'Active')
                ->orderBy('name')
                ->get();
        }

        $items = Item::where('status', 'Active')
            ->orderBy('name')
            ->get();

        return view(
            'admin.inventories.edit',
            compact('inventory', 'warehouses', 'items')
        );
    }

    /**
     * Update the specified inventory in storage.
     */
    public function update(InventoryRequest $request, Inventory $inventory)
    {
        // Policy Check
        $this->authorize('update', $inventory);

        $inventory->update($request->validated());

        return redirect()
            ->route('backend.inventories.index')
            ->with('success', 'Inventory updated successfully.');
    }

    /**
     * Remove the specified inventory entry from storage.
     */
    public function destroy(Inventory $inventory)
    {
        // Policy Check
        $this->authorize('delete', $inventory);

        $inventory->delete();

        return redirect()
            ->route('backend.inventories.index')
            ->with('success', 'Inventory deleted successfully.');
    }
}
