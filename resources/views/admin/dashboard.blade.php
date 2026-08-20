@extends('layouts.admin')

@section('title', $dashboardTitle)

@section('content')

<style>

    .dashboard-wrapper {
        background: #f8fafc;
        min-height: calc(100vh - 70px);
        padding: 24px;
    }

    /* =========================================================
       HEADER
    ========================================================== */

    .dashboard-header {
        background: linear-gradient(
            135deg,
            #0f4c81 0%,
            #2563eb 55%,
            #0284c7 100%
        );
        color: #fff;
        border-radius: 18px;
        padding: 26px 30px;
        margin-bottom: 24px;
        box-shadow: 0 10px 30px rgba(15, 76, 129, .18);
        position: relative;
        overflow: hidden;
    }

    .dashboard-header::after {
        content: "";
        position: absolute;
        width: 220px;
        height: 220px;
        right: -70px;
        top: -90px;
        border-radius: 50%;
        background: rgba(255,255,255,.08);
    }

    .dashboard-header h3 {
        font-weight: 800;
        margin-bottom: 7px;
    }

    .dashboard-header p {
        margin: 0;
        color: rgba(255,255,255,.82);
    }

    .dashboard-date {
        background: rgba(255,255,255,.14);
        border: 1px solid rgba(255,255,255,.18);
        border-radius: 12px;
        padding: 10px 16px;
        font-size: 14px;
    }


    /* =========================================================
       STAT CARDS
    ========================================================== */

    .dashboard-stat {
        border: 0;
        border-radius: 17px;
        background: #fff;
        padding: 22px;
        height: 100%;
        box-shadow: 0 5px 20px rgba(15,23,42,.06);
        transition: .25s ease;
        position: relative;
        overflow: hidden;
    }

    .dashboard-stat:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(15,23,42,.11);
    }

    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 21px;
        margin-bottom: 17px;
    }

    .stat-label {
        color: #64748b;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .stat-value {
        font-size: 26px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
    }

    .stat-footer {
        margin-top: 13px;
        font-size: 12px;
        color: #64748b;
    }


    /* =========================================================
       COLOR ICONS
    ========================================================== */

    .icon-blue {
        background: #e0f2fe;
        color: #0284c7;
    }

    .icon-orange {
        background: #ffedd5;
        color: #ea580c;
    }

    .icon-green {
        background: #dcfce7;
        color: #16a34a;
    }

    .icon-red {
        background: #fee2e2;
        color: #dc2626;
    }

    .icon-purple {
        background: #f3e8ff;
        color: #9333ea;
    }

    .icon-cyan {
        background: #cffafe;
        color: #0891b2;
    }

    .icon-indigo {
        background: #e0e7ff;
        color: #4f46e5;
    }

    .icon-yellow {
        background: #fef3c7;
        color: #d97706;
    }


    /* =========================================================
       CONTENT CARDS
    ========================================================== */

    .dashboard-card {
        border: 0;
        border-radius: 17px;
        background: #fff;
        box-shadow: 0 5px 20px rgba(15,23,42,.06);
        overflow: hidden;
    }

    .dashboard-card-header {
        padding: 18px 22px;
        border-bottom: 1px solid #eef2f7;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .dashboard-card-header h5 {
        margin: 0;
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
    }

    .dashboard-card-body {
        padding: 20px 22px;
    }


    /* =========================================================
       TABLE
    ========================================================== */

    .dashboard-table {
        width: 100%;
        border-collapse: collapse;
    }

    .dashboard-table th {
        background: #f8fafc;
        color: #64748b;
        font-size: 12px;
        font-weight: 700;
        padding: 13px 12px;
        border-bottom: 1px solid #e2e8f0;
    }

    .dashboard-table td {
        padding: 14px 12px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 13px;
        color: #334155;
    }

    .dashboard-table tbody tr:hover {
        background: #f8fafc;
    }


    /* =========================================================
       BADGES
    ========================================================== */

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 9px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 700;
    }

    .status-danger {
        background: #fee2e2;
        color: #b91c1c;
    }

    .status-warning {
        background: #fef3c7;
        color: #92400e;
    }

    .status-success {
        background: #dcfce7;
        color: #166534;
    }

    .status-info {
        background: #e0f2fe;
        color: #075985;
    }

    .status-secondary {
        background: #f1f5f9;
        color: #475569;
    }


    /* =========================================================
       EMPTY STATE
    ========================================================== */

    .empty-state {
        text-align: center;
        padding: 35px 20px;
        color: #94a3b8;
    }

    .empty-state i {
        font-size: 35px;
        margin-bottom: 12px;
    }


    /* =========================================================
       QUICK ACTIONS
    ========================================================== */

    .quick-action {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 13px;
        border-radius: 12px;
        background: #f8fafc;
        color: #334155;
        text-decoration: none;
        margin-bottom: 10px;
        transition: .2s ease;
    }

    .quick-action:hover {
        background: #eff6ff;
        color: #2563eb;
        transform: translateX(3px);
    }

    .quick-action-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #e0f2fe;
        color: #0284c7;
    }

    .quick-action strong {
        display: block;
        font-size: 13px;
    }

    .quick-action small {
        color: #94a3b8;
        font-size: 11px;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================== */

    @media (max-width: 768px) {

        .dashboard-wrapper {
            padding: 14px;
        }

        .dashboard-header {
            padding: 22px;
        }

        .dashboard-date {
            margin-top: 15px;
        }

        .stat-value {
            font-size: 22px;
        }
    }

</style>


<div class="dashboard-wrapper">

    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="dashboard-header">

        <div class="row align-items-center">

            <div class="col-lg-8">

                <h3>
                    <i class="fa-solid fa-chart-line me-2"></i>

                    {{ $dashboardTitle }}
                </h3>

                <p>
                    ဘေးအန္တရာယ်ကယ်ဆယ်ရေးပစ္စည်းများ၏
                    လက်ကျန်၊ တောင်းခံမှု၊ လှူဒါန်းမှုနှင့်
                    ဖြန့်ဝေမှုများကို တစ်နေရာတည်းမှ စောင့်ကြည့်နိုင်ပါသည်။
                </p>

            </div>

            <div class="col-lg-4 text-lg-end">

                <div class="dashboard-date d-inline-block">

                    <i class="fa-solid fa-calendar-day me-1"></i>

                    {{ now()->format('d M Y') }}

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         STATISTICS
    ====================================================== --}}

    <div class="row g-4 mb-4">

        {{-- Total Inventory --}}

        <div class="col-xl-3 col-md-6">

            <div class="dashboard-stat">

                <div class="stat-icon icon-blue">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>

                <div class="stat-label">
                    စုစုပေါင်း လက်ကျန်ပစ္စည်း
                </div>

                <div class="stat-value">
                    {{ number_format($totalInventory) }}
                </div>

                <div class="stat-footer">
                    <i class="fa-solid fa-box me-1"></i>
                    ပစ္စည်းအရေအတွက်
                </div>

            </div>

        </div>


        {{-- Pending Requests --}}

        <div class="col-xl-3 col-md-6">

            <div class="dashboard-stat">

                <div class="stat-icon icon-orange">
                    <i class="fa-solid fa-clock"></i>
                </div>

                <div class="stat-label">
                    စောင့်ဆိုင်းဆဲ တောင်းခံလွှာ
                </div>

                <div class="stat-value text-warning">
                    {{ number_format($pendingRequests) }}
                </div>

                <div class="stat-footer">
                    <i class="fa-solid fa-triangle-exclamation me-1"></i>
                    ဆောင်ရွက်ရန် လိုအပ်သည်
                </div>

            </div>

        </div>


        {{-- Active Distributions --}}

        <div class="col-xl-3 col-md-6">

            <div class="dashboard-stat">

                <div class="stat-icon icon-green">
                    <i class="fa-solid fa-truck-fast"></i>
                </div>

                <div class="stat-label">
                    လက်ရှိ ဖြန့်ဝေမှုများ
                </div>

                <div class="stat-value text-success">
                    {{ number_format($activeDistributions) }}
                </div>

                <div class="stat-footer">
                    <i class="fa-solid fa-route me-1"></i>
                    ဆောင်ရွက်နေဆဲ
                </div>

            </div>

        </div>


        {{-- Low Stock --}}

        <div class="col-xl-3 col-md-6">

            <div class="dashboard-stat">

                <div class="stat-icon icon-red">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>

                <div class="stat-label">
                    လက်ကျန်နည်းပစ္စည်း
                </div>

                <div class="stat-value text-danger">
                    {{ number_format($lowStockCount) }}
                </div>

                <div class="stat-footer">
                    <i class="fa-solid fa-bell me-1"></i>
                    အာရုံစိုက်ရန် လိုအပ်သည်
                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         FINANCIAL / OPERATIONAL STATS
    ====================================================== --}}

    <div class="row g-4 mb-4">

        {{-- Total Donation --}}

        <div class="col-xl-3 col-md-6">

            <div class="dashboard-stat">

                <div class="stat-icon icon-purple">
                    <i class="fa-solid fa-hand-holding-dollar"></i>
                </div>

                <div class="stat-label">
                    စုစုပေါင်း လှူဒါန်းငွေ
                </div>

                <div class="stat-value">
                    {{ number_format((float)$totalDonationAmount, 0) }}
                </div>

                <div class="stat-footer">
                    MMK
                </div>

            </div>

        </div>


        {{-- Today's Donation --}}

        <div class="col-xl-3 col-md-6">

            <div class="dashboard-stat">

                <div class="stat-icon icon-yellow">
                    <i class="fa-solid fa-money-bill-wave"></i>
                </div>

                <div class="stat-label">
                    ယနေ့ လှူဒါန်းငွေ
                </div>

                <div class="stat-value">
                    {{ number_format((float)$todayDonationAmount, 0) }}
                </div>

                <div class="stat-footer">
                    MMK · ယနေ့
                </div>

            </div>

        </div>


        {{-- Today's Distribution --}}

        <div class="col-xl-3 col-md-6">

            <div class="dashboard-stat">

                <div class="stat-icon icon-cyan">
                    <i class="fa-solid fa-truck"></i>
                </div>

                <div class="stat-label">
                    ယနေ့ ဖြန့်ဝေမှု
                </div>

                <div class="stat-value">
                    {{ number_format($todayDistributions) }}
                </div>

                <div class="stat-footer">
                    ယနေ့ ပြုလုပ်ထားသော ဖြန့်ဝေမှု
                </div>

            </div>

        </div>


        {{-- Warehouses --}}

        <div class="col-xl-3 col-md-6">

            <div class="dashboard-stat">

                <div class="stat-icon icon-indigo">
                    <i class="fa-solid fa-warehouse"></i>
                </div>

                <div class="stat-label">
                    ဂိုဒေါင်များ
                </div>

                <div class="stat-value">
                    {{ number_format($warehouseCount) }}
                </div>

                <div class="stat-footer">
                    လက်ရှိ စီမံခန့်ခွဲနေသော ဂိုဒေါင်
                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         LOW STOCK + QUICK ACTION
    ====================================================== --}}

    <div class="row g-4 mb-4">

        {{-- Low Stock Table --}}

        <div class="col-xl-8">

            <div class="dashboard-card">

                <div class="dashboard-card-header">

                    <h5>
                        <i class="fa-solid fa-triangle-exclamation text-danger me-2"></i>
                        လက်ကျန်နည်းပစ္စည်းများ
                    </h5>

                    @if(Route::has('backend.inventory.index'))

                        <a href="{{ route('backend.inventory.index') }}"
                           class="btn btn-sm btn-outline-primary">

                            အားလုံးကြည့်ရန်

                            <i class="fa-solid fa-arrow-right ms-1"></i>

                        </a>

                    @endif

                </div>


                <div class="dashboard-card-body p-0">

                    @if($lowStockItems->count())

                        <div class="table-responsive">

                            <table class="dashboard-table">

                                <thead>

                                    <tr>

                                        <th>ပစ္စည်း</th>

                                        <th>ဂိုဒေါင်</th>

                                        <th class="text-end">
                                            လက်ကျန်
                                        </th>

                                        <th class="text-end">
                                            အနည်းဆုံး
                                        </th>

                                        <th>အခြေအနေ</th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @foreach($lowStockItems as $inventory)

                                        <tr>

                                            <td>

                                                <strong>
                                                    {{ $inventory->item?->name ?? '-' }}
                                                </strong>

                                            </td>

                                            <td>
                                                {{ $inventory->warehouse?->name ?? '-' }}
                                            </td>

                                            <td class="text-end fw-bold text-danger">

                                                {{ number_format(
                                                    $inventory->quantity ?? 0
                                                ) }}

                                                {{ $inventory->item?->unit ?? '' }}

                                            </td>

                                            <td class="text-end">

                                                {{ number_format(
                                                    $inventory->item?->minimum_stock ?? 0
                                                ) }}

                                            </td>

                                            <td>

                                                @if(($inventory->quantity ?? 0) <= 0)

                                                    <span class="status-badge status-danger">
                                                        <i class="fa-solid fa-circle-xmark"></i>
                                                        ကုန်နေပြီ
                                                    </span>

                                                @else

                                                    <span class="status-badge status-warning">
                                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                                        လက်ကျန်နည်း
                                                    </span>

                                                @endif

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="empty-state">

                            <i class="fa-solid fa-circle-check text-success"></i>

                            <div>
                                လက်ကျန်နည်းပစ္စည်း မရှိပါ။
                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- Quick Actions --}}

        <div class="col-xl-4">

            <div class="dashboard-card">

                <div class="dashboard-card-header">

                    <h5>
                        <i class="fa-solid fa-bolt text-warning me-2"></i>
                        အမြန်လုပ်ဆောင်ရန်
                    </h5>

                </div>

                <div class="dashboard-card-body">

                    @if(Route::has('backend.distributions.create'))

                        <a href="{{ route('backend.distributions.create') }}"
                           class="quick-action">

                            <div class="quick-action-icon">
                                <i class="fa-solid fa-truck-fast"></i>
                            </div>

                            <div>

                                <strong>
                                    ဖြန့်ဝေမှုအသစ်
                                </strong>

                                <small>
                                    ပစ္စည်းများ ဖြန့်ဝေရန်
                                </small>

                            </div>

                        </a>

                    @endif


                    @if(Route::has('backend.inventory.index'))

                        <a href="{{ route('backend.inventory.index') }}"
                           class="quick-action">

                            <div class="quick-action-icon">
                                <i class="fa-solid fa-boxes-stacked"></i>
                            </div>

                            <div>

                                <strong>
                                    Inventory
                                </strong>

                                <small>
                                    လက်ကျန်ပစ္စည်းများ စစ်ဆေးရန်
                                </small>

                            </div>

                        </a>

                    @endif


                    @if(Route::has('backend.donation_payments.index'))

                        <a href="{{ route('backend.donation_payments.index') }}"
                           class="quick-action">

                            <div class="quick-action-icon">
                                <i class="fa-solid fa-money-bill-wave"></i>
                            </div>

                            <div>

                                <strong>
                                    လှူဒါန်းငွေများ
                                </strong>

                                <small>
                                    Donation Payments စစ်ဆေးရန်
                                </small>

                            </div>

                        </a>

                    @endif


                    @if(Route::has('backend.relief_requests.index'))

                        <a href="{{ route('backend.relief_requests.index') }}"
                           class="quick-action">

                            <div class="quick-action-icon">
                                <i class="fa-solid fa-hand-holding-heart"></i>
                            </div>

                            <div>

                                <strong>
                                    ကူညီပေးရန် တောင်းခံမှုများ
                                </strong>

                                <small>
                                    Relief Requests စစ်ဆေးရန်
                                </small>

                            </div>

                        </a>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         EXPIRY + RECENT ACTIVITIES
    ====================================================== --}}

    <div class="row g-4">

        {{-- Expiry Monitoring --}}

        <div class="col-xl-6">

            <div class="dashboard-card">

                <div class="dashboard-card-header">

                    <h5>

                        <i class="fa-solid fa-calendar-xmark text-warning me-2"></i>

                        သက်တမ်းကုန်ဆုံးရန် နီးကပ်နေသော ပစ္စည်းများ

                    </h5>

                    <span class="status-badge status-warning">
                        30 ရက်
                    </span>

                </div>


                <div class="dashboard-card-body p-0">

                    @if($expiringItems->count())

                        <div class="table-responsive">

                            <table class="dashboard-table">

                                <thead>

                                    <tr>

                                        <th>ပစ္စည်း</th>

                                        <th>သက်တမ်းကုန်မည့်နေ့</th>

                                        <th>ကျန်ရက်</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach($expiringItems as $inventory)

                                        <tr>

                                            <td>

                                                <strong>
                                                    {{ $inventory->item?->name ?? '-' }}
                                                </strong>

                                            </td>

                                            <td>

                                                {{ \Carbon\Carbon::parse(
                                                    $inventory->expiry_date
                                                )->format('d-m-Y') }}

                                            </td>

                                            <td>

                                                @if($inventory->days_left <= 7)

                                                    <span class="status-badge status-danger">

                                                        {{ $inventory->days_left }} ရက်

                                                    </span>

                                                @elseif($inventory->days_left <= 15)

                                                    <span class="status-badge status-warning">

                                                        {{ $inventory->days_left }} ရက်

                                                    </span>

                                                @else

                                                    <span class="status-badge status-success">

                                                        {{ $inventory->days_left }} ရက်

                                                    </span>

                                                @endif

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="empty-state">

                            <i class="fa-solid fa-calendar-check text-success"></i>

                            <div>
                                လာမည့် 30 ရက်အတွင်း
                                သက်တမ်းကုန်မည့်ပစ္စည်း မရှိပါ။
                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- Recent Activities --}}

        <div class="col-xl-6">

            <div class="dashboard-card">

                <div class="dashboard-card-header">

                    <h5>

                        <i class="fa-solid fa-clock-rotate-left text-primary me-2"></i>

                        လတ်တလော လုပ်ဆောင်ချက်များ

                    </h5>

                </div>


                <div class="dashboard-card-body p-0">

                    @if($recentActivities->count())

                        <div class="table-responsive">

                            <table class="dashboard-table">

                                <thead>

                                    <tr>

                                        <th>လုပ်ဆောင်ချက်</th>

                                        <th>တည်နေရာ</th>

                                        <th>အခြေအနေ</th>

                                        <th>အချိန်</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach($recentActivities as $activity)

                                        <tr>

                                            <td>

                                                <strong>
                                                    {{ $activity->title ?? '-' }}
                                                </strong>

                                            </td>

                                            <td>
                                                {{ $activity->location ?? '-' }}
                                            </td>

                                            <td>

                                                @if(($activity->status ?? '') === 'In Transit')

                                                    <span class="status-badge status-info">
                                                        ပို့ဆောင်ဆဲ
                                                    </span>

                                                @elseif(($activity->status ?? '') === 'Completed')

                                                    <span class="status-badge status-success">
                                                        ပြီးစီး
                                                    </span>

                                                @elseif(($activity->status ?? '') === 'Pending')

                                                    <span class="status-badge status-warning">
                                                        စောင့်ဆိုင်း
                                                    </span>

                                                @elseif(($activity->status ?? '') === 'Verified')

                                                    <span class="status-badge status-success">
                                                        စစ်ဆေးပြီး
                                                    </span>

                                                @else

                                                    <span class="status-badge status-secondary">
                                                        {{ $activity->status ?? '-' }}
                                                    </span>

                                                @endif

                                            </td>

                                            <td>

                                                <small class="text-muted">

                                                    {{ $activity->created_at
                                                        ? $activity->created_at->diffForHumans()
                                                        : '-'
                                                    }}

                                                </small>

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="empty-state">

                            <i class="fa-solid fa-clock-rotate-left"></i>

                            <div>
                                လတ်တလော လုပ်ဆောင်ချက်မှတ်တမ်း မရှိသေးပါ။
                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
