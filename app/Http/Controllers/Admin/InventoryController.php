<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Warehouse;
use App\Models\Item;
use App\Http\Requests\InventoryRequest;

class InventoryController extends Controller
{
    /**
     * Display a listing of the inventories.
     */
    public function index()
    {
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
        $warehouses = Warehouse::where('status', 'Active')
            ->orderBy('name')
            ->get();

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
        // updateOrCreate prevents duplicate warehouse + item entries
        Inventory::updateOrCreate(
            [
                'warehouse_id' => $request->warehouse_id,
                'item_id'      => $request->item_id,
            ],
            [
                'quantity'     => $request->quantity,
            ]
        );

        return redirect()
            ->route('backend.inventories.index')
            ->with('success', 'Inventory saved successfully.');
    }

    /**
     * Display the specified inventory item.
     */
    public function show(string $id)
    {
        $inventory = Inventory::with([
                'warehouse',
                'item.category'
            ])
            ->findOrFail($id);

        return view(
            'admin.inventories.show',
            compact('inventory')
        );
    }

    /**
     * Show the form for editing the specified inventory entry.
     */
    public function edit(string $id)
    {
        $inventory = Inventory::findOrFail($id);

        $warehouses = Warehouse::where('status', 'Active')
            ->orderBy('name')
            ->get();

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
    public function update(InventoryRequest $request, string $id)
    {
        $inventory = Inventory::findOrFail($id);

        $inventory->update($request->validated());

        return redirect()
            ->route('backend.inventories.index')
            ->with('success', 'Inventory updated successfully.');
    }

    /**
     * Remove the specified inventory entry from storage.
     */
    public function destroy(string $id)
    {
        $inventory = Inventory::findOrFail($id);

        $inventory->delete();

        return redirect()
            ->route('backend.inventories.index')
            ->with('success', 'Inventory deleted successfully.');
    }
}
