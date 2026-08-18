<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

class StockService
{
    /**
     * Stock IN logic (Adds quantity per batch expiry date)
     */
    public function stockIn($itemId, $warehouseId, $quantity, $reference = null, $expiryDate = null)
    {
        return DB::transaction(function () use ($itemId, $warehouseId, $quantity, $reference, $expiryDate) {
            // Find existing batch or create new batch for specific expiry date
            $inventory = Inventory::firstOrNew([
                'warehouse_id' => $warehouseId,
                'item_id'      => $itemId,
                'expiry_date'  => $expiryDate,
            ]);

            $inventory->quantity = ($inventory->quantity ?? 0) + $quantity;
            $inventory->save();

            // Total balance in this warehouse for audit
            $totalWarehouseBalance = Inventory::where('warehouse_id', $warehouseId)
                ->where('item_id', $itemId)
                ->sum('quantity');

            // Record Movement
            StockMovement::create([
                'item_id'       => $itemId,
                'warehouse_id'  => $warehouseId,
                'type'          => 'IN',
                'quantity'      => $quantity,
                'balance_after' => $totalWarehouseBalance,
                'expiry_date'   => $expiryDate,
                'reference'     => $reference,
                'created_by'    => Auth::id() ?? 1,
            ]);

            return $totalWarehouseBalance;
        });
    }

    /**
     * Stock OUT logic using FEFO (First Expired, First Out)
     */
    public function stockOut($itemId, $warehouseId, $quantity, $reference = null)
    {
        return DB::transaction(function () use ($itemId, $warehouseId, $quantity, $reference) {
            $totalAvailable = Inventory::where('warehouse_id', $warehouseId)
                ->where('item_id', $itemId)
                ->sum('quantity');

            if ($totalAvailable < $quantity) {
                throw new Exception("လက်ကျန်ပစ္စည်း မလုံလောက်ပါ။ (လက်ရှိလက်ကျန်: {$totalAvailable})");
            }

            // Get active batches ordered by Expiry Date (FEFO)
            $batches = Inventory::where('warehouse_id', $warehouseId)
                ->where('item_id', $itemId)
                ->where('quantity', '>', 0)
                ->orderByRaw('expiry_date IS NULL ASC, expiry_date ASC')
                ->get();

            $remainingToDeduct = $quantity;

            foreach ($batches as $batch) {
                if ($remainingToDeduct <= 0) break;

                if ($batch->quantity <= $remainingToDeduct) {
                    $remainingToDeduct -= $batch->quantity;
                    $batch->quantity = 0;
                    $batch->save(); // Or $batch->delete() if soft clean needed
                } else {
                    $batch->quantity -= $remainingToDeduct;
                    $batch->save();
                    $remainingToDeduct = 0;
                }
            }

            $totalWarehouseBalance = Inventory::where('warehouse_id', $warehouseId)
                ->where('item_id', $itemId)
                ->sum('quantity');

            // Record Movement
            StockMovement::create([
                'item_id'       => $itemId,
                'warehouse_id'  => $warehouseId,
                'type'          => 'OUT',
                'quantity'      => $quantity,
                'balance_after' => $totalWarehouseBalance,
                'reference'     => $reference,
                'created_by'    => Auth::id() ?? 1,
            ]);

            return $totalWarehouseBalance;
        });
    }
}
