<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Category;
use App\Models\Inventory;
use App\Models\Warehouse;
use App\Http\Requests\ItemRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    /**
     * Display a listing of items with search & category filter.
     */
    public function index(Request $request)
    {
        $query = Item::with(['category', 'inventories']);

        // Search Filter (Search by Name or Barcode)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        // Category Filter
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $items = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::orderBy('name', 'asc')->get();

        return view('admin.items.index', compact('items', 'categories'));
    }

    /**
     * Show the form for creating a new item.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.items.create', compact('categories'));
    }

    /**
     * Store a newly created item in storage with initial inventory batch.
     */
    public function store(ItemRequest $request)
    {
        DB::transaction(function () use ($request) {
            // 1. Create New Item Record
            $item = Item::create($request->validated());

            // 2. Create Initial Inventory Record for Default Warehouse if available
            $defaultWarehouse = Warehouse::first();

            if ($defaultWarehouse) {
                Inventory::create([
                    'warehouse_id' => $defaultWarehouse->id,
                    'item_id'      => $item->id,
                    'quantity'     => $request->input('quantity', 0),
                    'expiry_date'  => $request->input('expiry_date'),
                ]);
            }
        });

        return redirect()
            ->route('backend.items.index')
            ->with('success', 'Item and initial inventory batch created successfully.');
    }

    /**
     * Display the specified item details.
     */
    public function show(string $id)
    {
        $item = Item::with(['category', 'inventories.warehouse'])->findOrFail($id);

        return view('admin.items.show', compact('item'));
    }

    /**
     * Show the form for editing the specified item.
     */
    public function edit(string $id)
    {
        $item = Item::with('inventories')->findOrFail($id);
        $categories = Category::orderBy('name')->get();

        return view('admin.items.edit', compact('item', 'categories'));
    }

    /**
     * Update the specified item in storage.
     */
    public function update(ItemRequest $request, string $id)
    {
        DB::transaction(function () use ($request, $id) {
            $item = Item::findOrFail($id);
            $item->update($request->validated());

            // Update initial batch expiry date if provided
            if ($request->filled('expiry_date')) {
                Inventory::where('item_id', $item->id)
                    ->orderBy('id', 'asc')
                    ->limit(1)
                    ->update([
                        'expiry_date' => $request->input('expiry_date'),
                    ]);
            }
        });

        return redirect()
            ->route('backend.items.index')
            ->with('success', 'Item updated successfully.');
    }

    /**
     * Remove the specified item from storage.
     */
    public function destroy(string $id)
    {
        $item = Item::findOrFail($id);
        $item->delete();

        return redirect()
            ->route('backend.items.index')
            ->with('success', 'Item deleted successfully.');
    }

    /**
     * Get item details by Barcode or ID for AJAX / Barcode Scanner operations.
     * Route Name: backend.items.getByBarcode
     */
    public function getByBarcode(string $barcode)
    {
        $item = Item::where('barcode', $barcode)
            ->orWhere('id', $barcode)
            ->first();

        if ($item) {
            return response()->json([
                'success' => true,
                'item'    => $item // Fixed response key to match scan.blade.php
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Item not found'
        ], 404);
    }
}
