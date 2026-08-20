<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\DonationPayment;
use App\Models\Distribution;
use App\Models\Inventory;
use App\Models\ReliefRequest;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    /**
     * Admin / Manager Dashboard
     */
    public function index()
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Dashboard Title
        |--------------------------------------------------------------------------
        */

        if ($user?->role === 'warehouse_manager') {
            $dashboardTitle = 'ဂိုဒေါင် စီမံခန့်ခွဲမှု Dashboard';
        } elseif ($user?->role === 'manager') {
            $dashboardTitle = 'စီမံခန့်ခွဲမှု Dashboard';
        } else {
            $dashboardTitle = 'Smart Disaster Relief Warehouse Dashboard';
        }


        /*
        |--------------------------------------------------------------------------
        | Warehouse Scope
        |--------------------------------------------------------------------------
        */

        $warehouseId = null;

        if (
            $user &&
            $user->role === 'warehouse_manager' &&
            !empty($user->warehouse_id)
        ) {
            $warehouseId = $user->warehouse_id;
        }


        /*
        |--------------------------------------------------------------------------
        | 1. Total Inventory
        |--------------------------------------------------------------------------
        */

        $totalInventory = 0;

        if (Schema::hasTable('inventories')) {

            $query = Inventory::query();

            if ($warehouseId) {
                $query->where('warehouse_id', $warehouseId);
            }

            $totalInventory = $query->sum('quantity');
        }


        /*
        |--------------------------------------------------------------------------
        | 2. Pending Relief Requests
        |--------------------------------------------------------------------------
        */

        $pendingRequests = 0;

        if (Schema::hasTable('relief_requests')) {

            $query = ReliefRequest::where('status', 'Pending');

            /*
             * If relief_requests has warehouse_id,
             * filter warehouse manager.
             */
            if (
                $warehouseId &&
                Schema::hasColumn('relief_requests', 'warehouse_id')
            ) {
                $query->where('warehouse_id', $warehouseId);
            }

            $pendingRequests = $query->count();
        }


        /*
        |--------------------------------------------------------------------------
        | 3. Active Distributions
        |--------------------------------------------------------------------------
        */

        $activeDistributions = 0;

        if (Schema::hasTable('distributions')) {

            $query = Distribution::whereIn('status', [
                'Pending',
                'Approved',
                'Processing',
                'In Transit',
            ]);

            if (
                $warehouseId &&
                Schema::hasColumn('distributions', 'warehouse_id')
            ) {
                $query->where('warehouse_id', $warehouseId);
            }

            $activeDistributions = $query->count();
        }


        /*
        |--------------------------------------------------------------------------
        | 4. Low Stock Items
        |--------------------------------------------------------------------------
        */

        $lowStockItems = collect();

        $lowStockCount = 0;

        if (
            Schema::hasTable('inventories') &&
            Schema::hasTable('items')
        ) {

            $query = Inventory::with([
                'item',
                'warehouse'
            ])
            ->where('quantity', '>=', 0)
            ->whereHas('item', function ($q) {

                $q->whereColumn(
                    'inventories.quantity',
                    '<=',
                    'items.minimum_stock'
                );

            })
            ->orderBy('quantity', 'asc');

            if ($warehouseId) {
                $query->where(
                    'warehouse_id',
                    $warehouseId
                );
            }

            $lowStockItems = $query
                ->take(10)
                ->get();

            $lowStockCount = $lowStockItems->count();
        }


        /*
        |--------------------------------------------------------------------------
        | 5. Expiring Items - Next 30 Days
        |--------------------------------------------------------------------------
        */

        $expiringItems = collect();

        if (
            Schema::hasTable('inventories') &&
            Schema::hasColumn('inventories', 'expiry_date')
        ) {

            $query = Inventory::with([
                'item',
                'warehouse'
            ])
            ->whereNotNull('expiry_date')
            ->where('quantity', '>', 0)
            ->whereDate(
                'expiry_date',
                '>=',
                Carbon::today()
            )
            ->whereDate(
                'expiry_date',
                '<=',
                Carbon::today()->addDays(30)
            )
            ->orderBy('expiry_date', 'asc');

            if ($warehouseId) {
                $query->where(
                    'warehouse_id',
                    $warehouseId
                );
            }

            $expiringItems = $query
                ->take(10)
                ->get()
                ->map(function ($inventory) {

                    $expiry = Carbon::parse(
                        $inventory->expiry_date
                    );

                    $inventory->days_left =
                        Carbon::today()->diffInDays(
                            $expiry,
                            false
                        );

                    return $inventory;
                });
        }


        /*
        |--------------------------------------------------------------------------
        | 6. Total Donation Amount
        |--------------------------------------------------------------------------
        */

        $totalDonationAmount = 0;

        if (Schema::hasTable('donation_payments')) {

            $query = DonationPayment::where(
                'status',
                'Completed'
            );

            if (
                $warehouseId &&
                Schema::hasTable('donations') &&
                Schema::hasColumn('donations', 'warehouse_id')
            ) {

                $query->whereHas('donation', function ($q) use ($warehouseId) {

                    $q->where(
                        'warehouse_id',
                        $warehouseId
                    );

                });
            }

            $totalDonationAmount = $query->sum('amount');
        }


        /*
        |--------------------------------------------------------------------------
        | 7. Warehouse Count
        |--------------------------------------------------------------------------
        */

        $warehouseCount = 0;

        if (Schema::hasTable('warehouses')) {

            if ($warehouseId) {

                $warehouseCount = Warehouse::where(
                    'id',
                    $warehouseId
                )->count();

            } else {

                $warehouseCount = Warehouse::count();
            }
        }


        /*
        |--------------------------------------------------------------------------
        | 8. Recent Activities
        |--------------------------------------------------------------------------
        */

        $recentActivities = collect();

        if (Schema::hasTable('activity_logs')) {

            $recentActivities = ActivityLog::latest(
                'created_at'
            )
            ->take(8)
            ->get();
        }


        /*
        |--------------------------------------------------------------------------
        | 9. Today's Distributions
        |--------------------------------------------------------------------------
        */

        $todayDistributions = 0;

        if (Schema::hasTable('distributions')) {

            $query = Distribution::whereDate(
                'created_at',
                Carbon::today()
            );

            if (
                $warehouseId &&
                Schema::hasColumn('distributions', 'warehouse_id')
            ) {
                $query->where(
                    'warehouse_id',
                    $warehouseId
                );
            }

            $todayDistributions = $query->count();
        }


        /*
        |--------------------------------------------------------------------------
        | 10. Today's Donation Amount
        |--------------------------------------------------------------------------
        */

        $todayDonationAmount = 0;

        if (Schema::hasTable('donation_payments')) {

            $query = DonationPayment::whereDate(
                'payment_date',
                Carbon::today()
            )
            ->where(
                'status',
                'Completed'
            );

            if (
                $warehouseId &&
                Schema::hasTable('donations') &&
                Schema::hasColumn('donations', 'warehouse_id')
            ) {

                $query->whereHas('donation', function ($q) use ($warehouseId) {

                    $q->where(
                        'warehouse_id',
                        $warehouseId
                    );

                });
            }

            $todayDonationAmount = $query->sum('amount');
        }


        /*
        |--------------------------------------------------------------------------
        | Return Dashboard
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.dashboard',
            compact(
                'dashboardTitle',
                'totalInventory',
                'pendingRequests',
                'activeDistributions',
                'totalDonationAmount',
                'warehouseCount',
                'lowStockCount',
                'lowStockItems',
                'expiringItems',
                'recentActivities',
                'todayDistributions',
                'todayDonationAmount'
            )
        );
    }


    /**
     * QR / Barcode Scan Page
     */
    public function scan()
    {
        $user = Auth::user();

        if (
            $user &&
            $user->role === 'warehouse_manager' &&
            $user->warehouse_id
        ) {

            $warehouses = Warehouse::where(
                'id',
                $user->warehouse_id
            )->get();

        } else {

            $warehouses = Warehouse::orderBy(
                'name',
                'asc'
            )->get();
        }

        return view(
            'admin.scan',
            compact('warehouses')
        );
    }
}
