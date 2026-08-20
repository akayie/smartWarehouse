<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Front Controllers
use App\Http\Controllers\Front\FrontController;
use App\Http\Controllers\HomeController;

// Admin Controllers
use App\Http\Controllers\Admin\{
    DashboardController,
    CategoryController,
    UserController,
    WarehouseController,
    ItemController,
    InventoryController,
    StockMovementController,
    DisasterController,
    DonorController,
    DonationController,
    DonationItemController,
    DonationPaymentController,
    ReliefRequestController,
    RequestItemController,
    DistributionController,
    DistributionItemController,
    ReportController,
    DonationFundController
};

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Auth::routes();

/*
|--------------------------------------------------------------------------
| Public / Front Routes (Guest တွေပါ ဝင်ရောက်နိုင်သော Routes များ)
|--------------------------------------------------------------------------
*/

Route::get('/', [FrontController::class, 'index'])->name('home');
Route::get('/about', [FrontController::class, 'about'])->name('public.about');
Route::get('/campaigns', [FrontController::class, 'campaigns'])->name('public.campaigns');

// လှူဒါန်းရန်နှင့် အကူအညီတောင်းခံရန် Form များ
Route::get('/donate', [FrontController::class, 'createDonation'])->name('public.donate.create');
Route::post('/donate', [FrontController::class, 'storeDonation'])->name('public.donate.store');

Route::get('/request-help', [FrontController::class, 'createRequest'])->name('public.request.create');
Route::post('/request-help', [FrontController::class, 'storeRequest'])->name('public.request.store');

Route::get('/get-warehouse-items/{warehouseId}', [FrontController::class, 'getWarehouseItems'])->name('public.warehouse.items');

// Public History & Tracking Pages (Login မလိုဘဲ အားလုံးကြည့်နိုင်ရန် Public အောက်တွင် ထည့်ထားသည်)
Route::get('/my-requests', [FrontController::class, 'myRequests'])->name('public.my-requests');
Route::get('/donation-history', [FrontController::class, 'donationHistory'])->name('public.don-history');

// Authenticated User Home Route
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('user.home');
});

/*
|--------------------------------------------------------------------------
| Admin & Warehouse Manager Shared Backend Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin,warehouse_manager,manager'])
    ->prefix('backend')
    ->name('backend.')
    ->group(function () {
Route::get(
            '/donation-funds',
            [DonationFundController::class, 'index']
        )->name('donation-funds');
        Route::get(
    'donation_payments/{id}/pdf',
    [DonationPaymentController::class, 'pdf']
)->name('donation_payments.pdf');
        // Dashboard & Scanner
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/scan', [DashboardController::class, 'scan'])->name('scan');

        // QR Scanner Routes
        Route::get('/qr-scanner', [StockMovementController::class, 'qrScannerIndex'])->name('qr.scanner');
        Route::post('/qr-process', [StockMovementController::class, 'processQrScan'])->name('qr.process');

        // Master Data
        Route::get('items/get-by-barcode/{barcode}', [ItemController::class, 'getByBarcode'])->name('items.getByBarcode');
        Route::resource('items', ItemController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('disasters', DisasterController::class);

        // Inventory & Operations Data
        Route::resource('inventories', InventoryController::class);
        Route::resource('stock-movements', StockMovementController::class);
        Route::resource('donors', DonorController::class);

        Route::get('/stockin', [StockMovementController::class, 'stockInView'])->name('stockin');

        // Donation Management
        Route::post('donations/{id}/receive', [DonationController::class, 'receive'])->name('donations.receive');
        Route::resource('donations', DonationController::class);
        Route::resource('donation_items', DonationItemController::class);
        Route::resource('donation_payments', DonationPaymentController::class);

        // Relief Request & Approval Operations
        Route::patch('relief_requests/{reliefRequest}/approve', [ReliefRequestController::class, 'approve'])->name('relief_requests.approve');
        Route::patch('relief_requests/{reliefRequest}/reject', [ReliefRequestController::class, 'reject'])->name('relief_requests.reject');
        Route::resource('relief_requests', ReliefRequestController::class);
        Route::resource('request_items', RequestItemController::class);

        // Distribution Operations
        Route::resource('distributions', DistributionController::class);
        Route::resource('distribution_items', DistributionItemController::class);

        // Operational Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/inventory', [ReportController::class, 'inventory'])->name('inventory');
            Route::get('/distribution', [ReportController::class, 'distribution'])->name('distribution');
            Route::get('/stock-movement', [ReportController::class, 'stockMovement'])->name('stock-movement');
            Route::get('/donation', [ReportController::class, 'donation'])->name('donation');
            Route::get('/relief-request', [ReportController::class, 'reliefRequest'])->name('relief-request');
            Route::get('/low-stock', [ReportController::class, 'lowStock'])->name('low-stock');
        });

        /*
        |--------------------------------------------------------------------------
        | Super Admin Only Routes
        |--------------------------------------------------------------------------
        */
        Route::middleware(['role:admin'])->group(function () {
            Route::resource('users', UserController::class);
            Route::resource('warehouses', WarehouseController::class);
        });
    });
