<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Front Controller
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
    DonationMoneyController,
    DonationPaymentController,
    ReliefRequestController,
    RequestItemController,
    DistributionController,
    DistributionItemController,
    ReportController,
};

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Auth::routes();


/*
|--------------------------------------------------------------------------
| Public / Front Routes
|--------------------------------------------------------------------------
*/

// Home Page
Route::get('/', [FrontController::class, 'index'])
    ->name('home');

Route::get('/home', [HomeController::class, 'index'])
    ->name('user.home');


/*
|--------------------------------------------------------------------------
| General Public Pages
|--------------------------------------------------------------------------
*/

Route::get('/about', [FrontController::class, 'about'])
    ->name('public.about');

Route::get('/campaigns', [FrontController::class, 'campaigns'])
    ->name('public.campaigns');


/*
|--------------------------------------------------------------------------
| Protected User Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // My Relief Requests
    Route::get('/my-requests', [FrontController::class, 'myRequests'])
        ->name('public.my-requests');

    // Donation History
    Route::get('/donation-history', [FrontController::class, 'donationHistory'])
        ->name('public.don-history');


    /*
    |--------------------------------------------------------------------------
    | Public Relief Requests
    |--------------------------------------------------------------------------
    */

    Route::get('/request-help', [FrontController::class, 'createRequest'])
        ->name('public.request.create');

    Route::post('/request-help', [FrontController::class, 'storeRequest'])
        ->name('public.request.store');


    /*
    |--------------------------------------------------------------------------
    | Public Donations
    |--------------------------------------------------------------------------
    */

    Route::get('/donate', [FrontController::class, 'createDonation'])
        ->name('public.donate.create');

    Route::post('/donate', [FrontController::class, 'storeDonation'])
        ->name('public.donate.store');
});


/*
|--------------------------------------------------------------------------
| Admin / Backend Routes
|--------------------------------------------------------------------------
|
| Protected with auth + checkrole middleware
|
*/

Route::prefix('backend')
    ->name('backend.')
    ->middleware(['auth', 'checkrole'])
    ->group(function () {


    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/scan', [DashboardController::class, 'scan'])
        ->name('scan');


    /*
    |--------------------------------------------------------------------------
    | QR / Barcode
    |--------------------------------------------------------------------------
    */

    Route::post('/qr-process', [StockMovementController::class, 'processQrScan'])
        ->name('qr.process');


    /*
    |--------------------------------------------------------------------------
    | Master Data
    |--------------------------------------------------------------------------
    */

    Route::resource('categories', CategoryController::class);

    Route::resource('users', UserController::class);

    Route::resource('warehouses', WarehouseController::class);


    /*
    |--------------------------------------------------------------------------
    | Items
    |--------------------------------------------------------------------------
    */

    // Must be before items/{item}
    Route::get(
        'items/get-by-barcode/{barcode}',
        [ItemController::class, 'getByBarcode']
    )->name('items.getByBarcode');

    Route::resource('items', ItemController::class);


    /*
    |--------------------------------------------------------------------------
    | Inventory
    |--------------------------------------------------------------------------
    */

    Route::resource('inventories', InventoryController::class);


    /*
    |--------------------------------------------------------------------------
    | Stock Movements
    |--------------------------------------------------------------------------
    */

    Route::resource('stock-movements', StockMovementController::class);


    /*
    |--------------------------------------------------------------------------
    | Disaster Management
    |--------------------------------------------------------------------------
    */

    Route::resource('disasters', DisasterController::class);


    /*
    |--------------------------------------------------------------------------
    | Donor Management
    |--------------------------------------------------------------------------
    */

    Route::resource('donors', DonorController::class);


    /*
    |--------------------------------------------------------------------------
    | Donation Management
    |--------------------------------------------------------------------------
    */

    Route::post(
        'donations/{id}/receive',
        [DonationController::class, 'receive']
    )->name('donations.receive');

    Route::resource('donations', DonationController::class);

    Route::resource(
        'donation_items',
        DonationItemController::class
    );

    Route::resource(
        'donation_payments',
        DonationPaymentController::class
    );


    /*
    |--------------------------------------------------------------------------
    | Relief Request Management
    |--------------------------------------------------------------------------
    */

    // Approve Relief Request
    Route::patch(
        'relief_requests/{reliefRequest}/approve',
        [ReliefRequestController::class, 'approve']
    )->name('relief_requests.approve');


    // Reject Relief Request
    Route::patch(
        'relief_requests/{reliefRequest}/reject',
        [ReliefRequestController::class, 'reject']
    )->name('relief_requests.reject');


    // Resource Routes
    Route::resource(
        'relief_requests',
        ReliefRequestController::class
    );


    /*
    |--------------------------------------------------------------------------
    | Request Items
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'request_items',
        RequestItemController::class
    );


    /*
    |--------------------------------------------------------------------------
    | Distribution
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'distributions',
        DistributionController::class
    );

    Route::resource(
        'distribution_items',
        DistributionItemController::class
    );


    /*
    |--------------------------------------------------------------------------
    | Reports
    |--------------------------------------------------------------------------
    */

    Route::prefix('reports')
        ->name('reports.')
        ->group(function () {

            Route::get(
                '/inventory',
                [ReportController::class, 'inventory']
            )->name('inventory');

            Route::get(
                '/distribution',
                [ReportController::class, 'distribution']
            )->name('distribution');

            Route::get(
                '/stock-movement',
                [ReportController::class, 'stockMovement']
            )->name('stock-movement');

            Route::get(
                '/donation',
                [ReportController::class, 'donation']
            )->name('donation');

            Route::get(
                '/relief-request',
                [ReportController::class, 'reliefRequest']
            )->name('relief-request');

            Route::get(
                '/low-stock',
                [ReportController::class, 'lowStock']
            )->name('low-stock');
        });
});
