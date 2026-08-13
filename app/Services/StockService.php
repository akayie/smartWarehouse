<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\StockMovement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockService
{
    /**
     * Stock In Process
     */
    public function stockIn($itemId, $warehouseId, int $quantity, $reference = null, $expiryDate = null)
    {
        return DB::transaction(function () use ($itemId, $warehouseId, $quantity, $reference, $expiryDate) {
            // ၁။ လက်ရှိ Inventory Record ကို ရှာပါ သို့မဟုတ် Create လုပ်ပါ
            $inventory = Inventory::firstOrCreate(
                ['item_id' => $itemId, 'warehouse_id' => $warehouseId],
                ['quantity' => 0]
            );

            // ၂။ Quantity တိုးပေးပြီး Balance After ကို တွက်ပါ
            $inventory->increment('quantity', $quantity);

            if ($expiryDate) {
                $inventory->update(['expiry_date' => $expiryDate]);
            }

            $balanceAfter = $inventory->quantity;

            // ၃။ Stock Movement တွင် balance_after ထည့်သွင်းပါ
            return StockMovement::create([
                'item_id'       => $itemId,
                'warehouse_id'  => $warehouseId,
                'type'          => 'IN',
                'quantity'      => $quantity,
                'balance_after' => $balanceAfter, // <-- balance_after ထည့်သွင်းပေးလိုက်ပါပြီ
                'reference'     => $reference,
                'created_by'    => Auth::id() ?? 1,
            ]);
        });
    }

    /**
     * Stock Out Process
     */
    public function stockOut($itemId, $warehouseId, int $quantity, $reference = null)
    {
        return DB::transaction(function () use ($itemId, $warehouseId, $quantity, $reference) {
            $inventory = Inventory::where('item_id', $itemId)
                ->where('warehouse_id', $warehouseId)
                ->first();

            if (!$inventory || $inventory->quantity < $quantity) {
                throw new \Exception('လက်ကျန် Stock မလုံလောက်ပါ။');
            }

            // Quantity လျှော့ပေးပြီး Balance After တွက်ပါ
            $inventory->decrement('quantity', $quantity);
            $balanceAfter = $inventory->quantity;

            // Stock Movement တွင် balance_after ထည့်သွင်းပါ
            return StockMovement::create([
                'item_id'       => $itemId,
                'warehouse_id'  => $warehouseId,
                'type'          => 'OUT',
                'quantity'      => $quantity,
                'balance_after' => $balanceAfter, // <-- balance_after ထည့်သွင်းပေးလိုက်ပါပြီ
                'reference'     => $reference,
                'created_by'    => Auth::id() ?? 1,
            ]);
        });
    }
}
