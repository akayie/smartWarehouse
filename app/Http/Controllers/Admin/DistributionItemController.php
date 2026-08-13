<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DistributionItemRequest;
use App\Models\Distribution;
use App\Models\DistributionItem;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\ReliefRequest;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DistributionItemController extends Controller
{
    /**
     * Display a listing of distribution items with pagination and search.
     */
    public function index(Request $request)
    {
        $query = DistributionItem::with([
            'distribution',
            'distribution.request',
            'distribution.warehouse',
            'item',
        ]);

        // Search Filter (By Distribution ID or Item Name)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('distribution_id', 'LIKE', "%{$search}%")
                  ->orWhereHas('item', function ($q) use ($search) {
                      $q->where('name', 'LIKE', "%{$search}%");
                  });
        }

        $distributionItems = $query->orderBy('id', 'DESC')->paginate(15);

        return view('admin.distribution_items.index', compact('distributionItems'));
    }

    /**
     * Show the form for creating a new distribution item.
     */
    public function create(Request $request)
    {
        $distributions = Distribution::with(['request', 'warehouse'])
            ->where('status', '!=', 'Cancelled')
            ->orderBy('id', 'DESC')
            ->get();

        $items = Item::where('status', 'Active')
            ->orderBy('name')
            ->get();

        // Optional: Specific Distribution ID ပါလာပါက Select လုပ်ရလွယ်ကူစေရန်
        $selectedDistribution = null;
        if ($request->filled('distribution_id')) {
            $selectedDistribution = Distribution::find($request->distribution_id);
        }

        return view('admin.distribution_items.create', compact('distributions', 'items', 'selectedDistribution'));
    }

    /**
     * Store a newly created distribution item in storage with Stock Out execution.
     */
    public function store(DistributionItemRequest $request)
    {
        $distribution = Distribution::findOrFail($request->distribution_id);
        $warehouseId = $distribution->warehouse_id;

        DB::beginTransaction();

        try {
            // 1. Check Available Stock in Selected Warehouse
            $inventory = Inventory::where('warehouse_id', $warehouseId)
                ->where('item_id', $request->item_id)
                ->first();

            if (!$inventory || $inventory->quantity < $request->quantity) {
                DB::rollBack();
                $available = $inventory ? $inventory->quantity : 0;

                return back()
                    ->withInput()
                    ->withErrors([
                        'error' => "Insufficient stock in warehouse! Available: {$available}, Requested: {$request->quantity}"
                    ]);
            }

            // 2. Create Distribution Item Record
            $distributionItem = DistributionItem::create([
                'distribution_id' => $request->distribution_id,
                'item_id'         => $request->item_id,
                'quantity'        => $request->quantity,
            ]);

            // 3. Decrement Inventory Stock (Stock OUT)
            $inventory->decrement('quantity', $request->quantity);

            // 4. Record Stock Movement Audit Log
            StockMovement::create([
                'warehouse_id'   => $warehouseId,
                'item_id'        => $request->item_id,
                'quantity'       => $request->quantity,
                'type'           => 'OUT',
                'reference_type' => 'Distribution',
                'reference_id'   => $distribution->id,
                'user_id'        => auth()->id(),
                'remarks'        => "Dispatched for Distribution #DSP-{$distribution->id}",
            ]);

            // 5. Update Statuses
            if ($distribution->status !== 'Completed') {
                $distribution->update(['status' => 'En Route']);
            }

            if ($distribution->relief_request_id) {
                ReliefRequest::where('id', $distribution->relief_request_id)
                    ->update(['status' => 'Distributed']);
            }

            DB::commit();

            return redirect()
                ->route('backend.distribution_items.index')
                ->with('success', 'Distribution item recorded and warehouse inventory updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to record distribution item: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified distribution item.
     */
    public function show(DistributionItem $distributionItem)
    {
        $distributionItem->load([
            'distribution',
            'distribution.request',
            'distribution.warehouse',
            'distribution.handledBy',
            'item',
        ]);

        return view('admin.distribution_items.show', compact('distributionItem'));
    }

    /**
     * Show the form for editing the specified distribution item.
     */
    public function edit(DistributionItem $distributionItem)
    {
        $distributions = Distribution::with(['request', 'warehouse'])
            ->orderBy('id', 'DESC')
            ->get();

        $items = Item::where('status', 'Active')
            ->orderBy('name')
            ->get();

        return view('admin.distribution_items.edit', compact('distributionItem', 'distributions', 'items'));
    }

    /**
     * Update the specified distribution item in storage with automatic stock adjustment.
     */
    public function update(DistributionItemRequest $request, DistributionItem $distributionItem)
    {
        $oldQuantity = $distributionItem->quantity;
        $newQuantity = $request->quantity;
        $quantityDiff = $newQuantity - $oldQuantity;

        $distribution = Distribution::findOrFail($request->distribution_id);
        $warehouseId = $distribution->warehouse_id;

        DB::beginTransaction();

        try {
            // Check & Adjust Inventory Stock if Quantity Changed
            if ($quantityDiff != 0) {
                $inventory = Inventory::where('warehouse_id', $warehouseId)
                    ->where('item_id', $request->item_id)
                    ->first();

                // Quantity တိုးလာပြီး စတော့ မလောက်ပါက
                if ($quantityDiff > 0 && (!$inventory || $inventory->quantity < $quantityDiff)) {
                    DB::rollBack();
                    return back()->withInput()->withErrors(['error' => 'Insufficient stock to increase distribution quantity.']);
                }

                if ($inventory) {
                    if ($quantityDiff > 0) {
                        $inventory->decrement('quantity', abs($quantityDiff));
                    } else {
                        $inventory->increment('quantity', abs($quantityDiff));
                    }
                }
            }

            // Update Distribution Item Record
            $distributionItem->update([
                'distribution_id' => $request->distribution_id,
                'item_id'         => $request->item_id,
                'quantity'        => $request->quantity,
            ]);

            DB::commit();

            return redirect()
                ->route('backend.distribution_items.index')
                ->with('success', 'Distribution item updated and inventory stock recalculated.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Update failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified distribution item and restore stock to inventory.
     */
    public function destroy(DistributionItem $distributionItem)
    {
        DB::beginTransaction();

        try {
            $distribution = $distributionItem->distribution;

            // Delete မလုပ်မီ လျှော့ထားခဲ့သော Stock ကို မူလ Warehouse Inventory ထဲ ပြန်ပေါင်းထည့်ပေးခြင်း (Restore Stock)
            if ($distribution) {
                $inventory = Inventory::where('warehouse_id', $distribution->warehouse_id)
                    ->where('item_id', $distributionItem->item_id)
                    ->first();

                if ($inventory) {
                    $inventory->increment('quantity', $distributionItem->quantity);
                }
            }

            // Delete Record
            $distributionItem->delete();

            DB::commit();

            return redirect()
                ->route('backend.distribution_items.index')
                ->with('success', 'Distribution item deleted and stock successfully restored to inventory.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Deletion failed: ' . $e->getMessage()]);
        }
    }
}
