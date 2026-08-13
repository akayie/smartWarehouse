<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StockMovementRequest;
use App\Models\Item;
use App\Models\StockMovement;
use App\Models\User;
use App\Models\Warehouse;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class StockMovementController extends Controller
{
    protected $stockService;

    /**
     * Inject StockService into the controller.
     */
    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * Display stock movements history with search & filters.
     */
    public function index(Request $request)
    {
        $query = StockMovement::with([
            'item',
            'warehouse',
            'creator'
        ]);

        // Filter by Item
        if ($request->filled('item_id')) {
            $query->where('item_id', $request->item_id);
        }

        // Filter by Warehouse
        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        // Filter by Type (IN, OUT, TRANSFER)
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Date Range Filter
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $stockMovements = $query->orderBy('id', 'DESC')
            ->paginate(15)
            ->withQueryString();

        $items = Item::where('status', 'Active')->orderBy('name')->get();
        $warehouses = Warehouse::where('status', 'Active')->orderBy('name')->get();

        return view('admin.stock_movements.index', compact('stockMovements', 'items', 'warehouses'));
    }

    /**
     * Show create form for manual stock movements.
     */
    public function create()
    {
        $items = Item::where('status', 'Active')
            ->orderBy('name')
            ->get();

        $warehouses = Warehouse::where('status', 'Active')
            ->orderBy('name')
            ->get();

        $users = User::orderBy('name')->get();

        return view('admin.stock_movements.create', compact('items', 'warehouses', 'users'));
    }

    /**
     * Store stock movement using StockService.
     */
    public function store(StockMovementRequest $request)
    {
        try {
            $qty = (int) $request->quantity;

            if ($request->type === 'IN') {
                $this->stockService->stockIn(
                    $request->item_id,
                    $request->warehouse_id,
                    $qty,
                    $request->reference,
                    $request->expiry_date ?? null
                );
            } elseif ($request->type === 'OUT') {
                $this->stockService->stockOut(
                    $request->item_id,
                    $request->warehouse_id,
                    $qty,
                    $request->reference
                );
            } elseif ($request->type === 'TRANSFER') {
                $refOut = $request->reference ? "Transfer Out ({$request->reference})" : "Transfer Out";
                $refIn  = $request->reference ? "Transfer In ({$request->reference})" : "Transfer In";

                // Source Warehouse Out
                $this->stockService->stockOut(
                    $request->item_id,
                    $request->warehouse_id,
                    $qty,
                    $refOut
                );

                // Target Warehouse In
                $this->stockService->stockIn(
                    $request->item_id,
                    $request->target_warehouse_id,
                    $qty,
                    $refIn,
                    $request->expiry_date ?? null
                );
            }

            return redirect()
                ->route('backend.stock-movements.index')
                ->with('success', 'Stock movement created successfully.');

        } catch (Exception $e) {
            Log::error('Stock Movement Store Error: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Display single stock movement detail.
     */
    public function show(StockMovement $stockMovement)
    {
        $stockMovement->load(['item', 'warehouse', 'creator']);

        return view('admin.stock_movements.show', compact('stockMovement'));
    }

    /**
     * Revert inventory and delete stock movement record.
     */
    public function destroy(StockMovement $stockMovement)
    {
        try {
            // Revert inventory balances upon movement deletion
            if ($stockMovement->type === 'IN') {
                $this->stockService->stockOut(
                    $stockMovement->item_id,
                    $stockMovement->warehouse_id,
                    $stockMovement->quantity,
                    'Rollback due to IN record deletion'
                );
            } elseif ($stockMovement->type === 'OUT') {
                $this->stockService->stockIn(
                    $stockMovement->item_id,
                    $stockMovement->warehouse_id,
                    $stockMovement->quantity,
                    'Rollback due to OUT record deletion'
                );
            }

            $stockMovement->delete();

            return redirect()
                ->route('backend.stock-movements.index')
                ->with('success', 'Stock movement deleted and inventory reverted successfully.');

        } catch (Exception $e) {
            Log::error('Stock Movement Delete Error: ' . $e->getMessage());

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Process QR Code / Barcode Scan via AJAX Request.
     */
    public function processQrScan(Request $request)
    {
        $request->validate([
            'item_code'   => 'required|string',
            'quantity'    => 'required|integer|min:1',
            'type'        => 'required|in:IN,OUT',
            'expiry_date' => 'nullable|date',
        ]);

        try {
            // Search Item by barcode or ID
            $item = Item::where('barcode', $request->item_code)
                ->orWhere('id', $request->item_code)
                ->first();

            if (!$item) {
                return response()->json([
                    'success' => false,
                    'message' => 'Scanned Item မတွေ့ရှိပါ။ ကျေးဇူးပြု၍ Barcode မှန်မမှန် ပြန်စစ်ပါ။'
                ], 404);
            }

            $warehouseId = Auth::user()->warehouse_id ?? 1;
            $qty = (int) $request->quantity;

            if ($request->type === 'IN') {
                $this->stockService->stockIn(
                    $item->id,
                    $warehouseId,
                    $qty,
                    'QR/Barcode Scan Operation',
                    $request->expiry_date
                );
            } else {
                $this->stockService->stockOut(
                    $item->id,
                    $warehouseId,
                    $qty,
                    'QR/Barcode Scan Operation'
                );
            }

            // Calculate current balance for display response
            $newBalance = \App\Models\Inventory::where('item_id', $item->id)
                ->where('warehouse_id', $warehouseId)
                ->value('quantity') ?? 0;

            return response()->json([
                'success'     => true,
                'message'     => "{$item->name} - Stock {$request->type} စာရင်းသွင်းပြီးပါပြီ။",
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
