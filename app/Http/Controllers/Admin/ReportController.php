<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Inventory;
use App\Models\Distribution;
use App\Models\Donation;
use App\Models\StockMovement;
use App\Models\Warehouse;
use App\Models\ReliefRequest;

class ReportController extends Controller
{
    // Helper function: User ၏ Role နှင့် Warehouse ကို စစ်ဆေးရန်
    private function applyWarehouseFilter($query)
    {
        $user = Auth::user();
        if (in_array($user->role, ['warehouse_manager', 'manager']) || $user->warehouse_id) {
            return $query->where('warehouse_id', $user->warehouse_id);
        }
        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | 1. Inventory Report
    |--------------------------------------------------------------------------
    */
    public function inventory(Request $request)
    {
        $query = Inventory::with(['item.category', 'warehouse']);

        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        } else {
            $this->applyWarehouseFilter($query);
        }

        $inventories = $query->latest()->get();
        $warehouses = Warehouse::all();

        return view('admin.reports.inventory', compact('inventories', 'warehouses'));
    }

    /*
    |--------------------------------------------------------------------------
    | 2. Distribution Report
    |--------------------------------------------------------------------------
    */
    public function distribution(Request $request)
    {
        $query = Distribution::with(['distributionItems.item', 'warehouse', 'reliefRequest.requestedBy', 'handledBy']);

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [$request->from_date . ' 00:00:00', $request->to_date . ' 23:59:59']);
        }

        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        } else {
            $this->applyWarehouseFilter($query);
        }

        $distributions = $query->latest('created_at')->get();
        $warehouses = Warehouse::all();

        return view('admin.reports.distribution', compact('distributions', 'warehouses'));
    }

    /*
    |--------------------------------------------------------------------------
    | 3. Stock Movement Report
    |--------------------------------------------------------------------------
    */
    public function stockMovement(Request $request)
    {
        $query = StockMovement::with(['item', 'warehouse', 'creator']);

        $this->applyWarehouseFilter($query);

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [$request->from_date . ' 00:00:00', $request->to_date . ' 23:59:59']);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $movements = $query->latest('created_at')->get();

        return view('admin.reports.stock_movement', compact('movements'));
    }

    /*
    |--------------------------------------------------------------------------
    | 4. Donation Report (ပြန်လည်ထည့်သွင်းပေးလိုက်သည်)
    |--------------------------------------------------------------------------
    */
    public function donation(Request $request)
{
    // scopeForCurrentWarehouse ကို သုံး၍ အလိုအလျောက် စစ်ထုတ်သည်
    $query = Donation::query()->forCurrentWarehouse()->with(['donor', 'donationItems.item']);

    // Date Filter
    if ($request->filled('from_date') && $request->filled('to_date')) {
        $query->whereBetween('created_at', [$request->from_date . ' 00:00:00', $request->to_date . ' 23:59:59']);
    }

    // Status Filter
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $donations = $query->latest()->get();

    return view('admin.reports.donation', compact('donations'));
}

   /*
    |--------------------------------------------------------------------------
    | 5. Relief Request Report
    |--------------------------------------------------------------------------
    */
    public function reliefRequest(Request $request)
    {
        $query = ReliefRequest::with(['requestedBy', 'requestItems.item', 'disaster']);

        // Apply warehouse permissions/filters
        $this->applyWarehouseFilter($query);

        // Date range filter
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [$request->from_date . ' 00:00:00', $request->to_date . ' 23:59:59']);
        }

        // Status filter (case-insensitive check if needed or direct match)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reliefRequests = $query->latest('created_at')->get();

        return view('admin.reports.relief_request', compact('reliefRequests'));
    }

    /*
    |--------------------------------------------------------------------------
    | 6. Low Stock Report
    |--------------------------------------------------------------------------
    */
    public function lowStock(Request $request)
    {
        $threshold = $request->input('threshold', 10);

        $query = Inventory::with(['item.category', 'warehouse'])
                            ->where('quantity', '<=', $threshold);

        $this->applyWarehouseFilter($query);

        $lowStocks = $query->orderBy('quantity', 'asc')->get();

        return view('admin.reports.low_stock', compact('lowStocks', 'threshold'));
    }
}
