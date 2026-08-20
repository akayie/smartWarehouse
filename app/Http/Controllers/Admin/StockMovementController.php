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
     * Display Resources / Movement History (Warehouse အလိုက် စစ်ထုတ်ပေးခြင်း)
     */
    public function index(Request $request)
    {
        $movements = StockMovement::with(['item', 'warehouse', 'creator'])
            ->forCurrentWarehouse() // <--- Warehouse Manager / Manager များအတွက် အလိုအလျောက် စစ်ထုတ်ပေးသည်
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

                    // 3. Stock In ဝင်လာသည့် Warehouse နှင့် သက်ဆိုင်သော DonationPayment များကိုသာ
                    // အရင်ဆုံး စစ်ထုတ်ပြီးမှ အဟောင်းဆုံးရက်စွဲအလိုက် amount များကို လျော့ချရန် (FIFO)
                    $remainingAmountToDeduct = $amount;

                    $payments = DonationPayment::where('amount', '>', 0)
                        ->whereHas('donation', function ($query) use ($warehouseId) {
                            $query->where('warehouse_id', $warehouseId);
                        })
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

    /**
     * QR / Barcode Scanner မျက်နှာပြင်ကို ပြသရန်
     */
    public function qrScannerIndex()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if (in_array($user->role, ['admin', 'super_admin'])) {
            $warehouses = Warehouse::all();
        } else {
            // Warehouse Manager များအတွက် ၎င်းတို့ပိုင်ဆိုင်သော Warehouse များသာ
            $warehouses = method_exists($user, 'warehouses')
                ? $user->warehouses
                : Warehouse::where('id', $user->warehouse_id)->get();
        }

        return view('admin.stock_movements.scanner', compact('warehouses'));
    }
}
