<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Inventory;
use App\Models\Distribution;
use App\Models\Donation;
use App\Models\StockMovement;
use App\Models\Warehouse;
use App\Models\ReliefRequest;
use App\Models\Disaster;

class ReportController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 1. Inventory Report
    |--------------------------------------------------------------------------
    */

    public function inventory(Request $request)
    {
        $query = Inventory::with([
            'item.category',
            'warehouse',
        ]);

        if ($request->filled('warehouse_id')) {
            $query->where(
                'warehouse_id',
                $request->warehouse_id
            );
        }

        $inventories = $query
            ->latest()
            ->get();

        $warehouses = Warehouse::all();

        return view(
            'admin.reports.inventory',
            compact(
                'inventories',
                'warehouses'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | 2. Distribution Report
    |--------------------------------------------------------------------------
    */

    public function distribution(Request $request)
    {
        $query = Distribution::with([
            'distributionItems.item',
            'warehouse',
            'reliefRequest.requestedBy',
            'handledBy',
        ]);

        if (
            $request->filled('from_date') &&
            $request->filled('to_date')
        ) {
            $query->whereBetween('created_at', [
                $request->from_date . ' 00:00:00',
                $request->to_date . ' 23:59:59',
            ]);
        }

        if ($request->filled('warehouse_id')) {
            $query->where(
                'warehouse_id',
                $request->warehouse_id
            );
        }

        $distributions = $query
            ->latest('created_at')
            ->get();

        $warehouses = Warehouse::all();

        return view(
            'admin.reports.distribution',
            compact(
                'distributions',
                'warehouses'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | 3. Stock Movement Report
    |--------------------------------------------------------------------------
    */

    public function stockMovement(Request $request)
    {
        $query = StockMovement::with([
            'item',
            'warehouse',
            'creator',
        ]);

        if (
            $request->filled('from_date') &&
            $request->filled('to_date')
        ) {
            $query->whereBetween('created_at', [
                $request->from_date . ' 00:00:00',
                $request->to_date . ' 23:59:59',
            ]);
        }

        if ($request->filled('type')) {
            $query->where(
                'type',
                $request->type
            );
        }

        $movements = $query
            ->latest('created_at')
            ->get();

        return view(
            'admin.reports.stock_movement',
            compact('movements')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | 4. Donation Report
    |--------------------------------------------------------------------------
    */

    public function donation(Request $request)
    {
        $query = Donation::with([
            'donor',
            'donationItems.item',
        ]);

        if (
            $request->filled('from_date') &&
            $request->filled('to_date')
        ) {
            $query->whereBetween('created_at', [
                $request->from_date . ' 00:00:00',
                $request->to_date . ' 23:59:59',
            ]);
        }

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        $donations = $query
            ->latest('created_at')
            ->get();

        return view(
            'admin.reports.donation',
            compact('donations')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | 5. Relief Request Report
    |--------------------------------------------------------------------------
    */

    public function reliefRequest(Request $request)
    {
        $query = ReliefRequest::with([
            'requestedBy',
            'requestItems.item',
            'disaster',
        ]);

        if (
            $request->filled('from_date') &&
            $request->filled('to_date')
        ) {
            $query->whereBetween('created_at', [
                $request->from_date . ' 00:00:00',
                $request->to_date . ' 23:59:59',
            ]);
        }

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        $reliefRequests = $query
            ->latest('created_at')
            ->get();

        return view(
            'admin.reports.relief_request',
            compact('reliefRequests')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | 6. Low Stock Report
    |--------------------------------------------------------------------------
    */

    public function lowStock(Request $request)
    {
        $threshold = $request->input(
            'threshold',
            10
        );

        $lowStocks = Inventory::with([
            'item.category',
            'warehouse',
        ])
            ->where(
                'quantity',
                '<=',
                $threshold
            )
            ->orderBy(
                'quantity',
                'asc'
            )
            ->get();

        return view(
            'admin.reports.low_stock',
            compact(
                'lowStocks',
                'threshold'
            )
        );
    }
}
