<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\ReliefRequest;
use App\Models\Dispatch;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Total Inventory Stock Count
        $totalInventory = Schema::hasTable('inventories') ? Inventory::sum('quantity') : 0;

        // 2. Pending Requests Count
        $pendingRequests = 0;
        if (class_exists(ReliefRequest::class) && Schema::hasTable('relief_requests')) {
            $pendingRequests = ReliefRequest::where('status', 'Pending')->count();
        }

        // 3. Active Dispatches Count (In Transit)
        $activeDispatches = 0;
        if (class_exists(Dispatch::class) && Schema::hasTable('dispatches')) {
            if (Schema::hasColumn('dispatches', 'status')) {
                $activeDispatches = Dispatch::where('status', 'In Transit')->count();
            }
        }

        // 4. Low Stock Alerts Count (Stock <= Minimum Stock Threshold)
        $lowStockItems = collect([]);
        if (Schema::hasTable('inventories') && Schema::hasTable('items')) {
            $lowStockItems = Inventory::with(['item'])
                ->whereHas('item')
                ->get()
                ->filter(function ($inventory) {
                    return $inventory->item && $inventory->quantity <= $inventory->item->minimum_stock;
                });
        }
        $lowStockCount = $lowStockItems->count();

        // 5. Expiry Tracking (Next 30 Days) - Safe check if column exists
        $expiringItems = collect([]);
        if (Schema::hasTable('inventories') && Schema::hasColumn('inventories', 'expiry_date')) {
            $expiringItems = Inventory::with(['item'])
                ->whereNotNull('expiry_date')
                ->where('quantity', '>', 0)
                ->whereDate('expiry_date', '<=', Carbon::now()->addDays(30))
                ->orderBy('expiry_date', 'asc')
                ->get()
                ->map(function ($item) {
                    $expiry = Carbon::parse($item->expiry_date);
                    $item->days_left = (int) Carbon::now()->diffInDays($expiry, false);
                    return $item;
                });
        }

        // 6. Recent Activities (Safe fallback if ActivityLog table/columns don't match)
        $recentActivities = collect([]);
        if (class_exists(ActivityLog::class) && Schema::hasTable('activity_logs')) {
            $recentActivities = ActivityLog::latest()->take(5)->get();
        }

        return view('admin.dashboard', compact(
            'totalInventory',
            'pendingRequests',
            'activeDispatches',
            'lowStockCount',
            'lowStockItems',
            'expiringItems',
            'recentActivities'
        ));
    }

    public function scan()
    {
        return view('admin.scan');
    }
}
