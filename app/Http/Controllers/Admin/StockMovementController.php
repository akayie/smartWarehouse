<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Warehouse;
use App\Models\Inventory;
use App\Models\StockMovement;
use App\Models\DonationPayment;
use App\Services\StockService;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class StockMovementController extends Controller
{
    protected $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * Display Resources / Movement History
     */
    public function index()
    {
        $movements = StockMovement::with(['item', 'warehouse', 'creator'])
            ->latest()
            ->paginate(15);

        return view('admin.stock_movements.index', compact('movements'));
    }

    /**
     * QR Process Action (Matched with web.php route: backend.qr.process)
     */
    public function processQrScan(Request $request)
    {
        $request->validate([
            'item_code'    => 'required|string',
            'warehouse_id' => 'required|exists:warehouses,id',
            'quantity'     => 'required|integer|min:1',
            'price'        => 'nullable|numeric|min:0',
            'type'         => 'required|in:IN,OUT',
            'expiry_date'  => 'nullable|date',
        ]);

        try {
            // Find item by Barcode or ID
            $item = Item::where('barcode', $request->item_code)
                ->orWhere('id', $request->item_code)
                ->first();

            if (!$item) {
                return response()->json([
                    'success' => false,
                    'message' => 'Scanned Item မတွေ့ရှိပါ။ Barcode ကို ပြန်လည်စစ်ဆေးပါ။'
                ], 404);
            }

            $warehouseId = $request->warehouse_id;
            $qty = (int) $request->quantity;
            $price = $request->price ?? 0;
            $amount = $qty * $price; // Amount = Price * Qty

            // Database Transaction ဖြင့် အားလုံးကို တစ်ပါတည်း မှန်ကန်အောင် လုပ်ဆောင်ပါမည်
            $newBalance = DB::transaction(function () use ($item, $warehouseId, $qty, $price, $amount, $request) {
                if ($request->type === 'IN') {
                    // 1. Stock In လုပ်ဆောင်ခြင်း
                    $newBalance = $this->stockService->stockIn(
                        $item->id,
                        $warehouseId,
                        $qty,
                        'QR/Barcode Scan Operation',
                        $request->expiry_date
                    );

                    // 2. နောက်ဆုံးထည့်လိုက်သော StockMovement တွင် price နှင့် amount ကို Update လုပ်ရန်
                    $latestMovement = StockMovement::where('item_id', $item->id)
                        ->where('warehouse_id', $warehouseId)
                        ->latest()
                        ->first();

                    if ($latestMovement) {
                        $latestMovement->update([
                            'price'  => $price,
                            'amount' => $amount,
                        ]);
                    }

                    // 3. DonationPayment ၏ amount ဇယားထဲမှ စုစုပေါင်း amount ကို နှုတ်ရန်
                    $remainingAmountToDeduct = $amount;
                    $payments = DonationPayment::where('amount', '>', 0)
                        ->orderBy('payment_date', 'asc')
                        ->get();

                    foreach ($payments as $payment) {
                        if ($remainingAmountToDeduct <= 0) {
                            break;
                        }

                        if ($payment->amount >= $remainingAmountToDeduct) {
                            $payment->amount -= $remainingAmountToDeduct;
                            $payment->save();
                            $remainingAmountToDeduct = 0;
                        } else {
                            $remainingAmountToDeduct -= $payment->amount;
                            $payment->amount = 0;
                            $payment->save();
                        }
                    }

                    return $newBalance;

                } else {
                    // Stock Out လုပ်ဆောင်ခြင်း
                    $newBalance = $this->stockService->stockOut(
                        $item->id,
                        $warehouseId,
                        $qty,
                        'QR/Barcode Scan Operation (FEFO Auto Deduction)'
                    );

                    // Stock Out အတွက်လည်း price နှင့် amount သိမ်းလိုပါက
                    $latestMovement = StockMovement::where('item_id', $item->id)
                        ->where('warehouse_id', $warehouseId)
                        ->latest()
                        ->first();

                    if ($latestMovement) {
                        $latestMovement->update([
                            'price'  => $price,
                            'amount' => $amount,
                        ]);
                    }

                    return $newBalance;
                }
            });

            return response()->json([
                'success'     => true,
                'message'     => "{$item->name} - Stock {$request->type} လုပ်ဆောင်ပြီးပါပြီ။",
                'item_name'   => $item->name,
                'new_balance' => $newBalance,
            ], 200);

        } catch (Exception $e) {
            Log::error('QR Process Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}


