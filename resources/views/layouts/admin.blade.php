<!DOCTYPE html>
<html lang="my">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @yield('title', 'Smart သဘာဝဘေး ကူညီကယ်ဆယ်ရေး ကုန်လှောင်ရုံ စီမံခန့်ခွဲမှုစနစ်')
    </title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">

    <!-- Custom Admin CSS -->
    <link rel="stylesheet" href="{{ asset('admin-assets/css/styles.css') }}">

    @yield('css')
</head>

<body>

<!-- =========================================================
     TOP SYSTEM BAR
========================================================= -->
<div class="top-switcher-bar d-flex justify-content-between align-items-center px-3 py-2 bg-dark text-white">
    <div>
        <strong>သဘာဝဘေး ကူညီကယ်ဆယ်ရေး လုပ်ငန်းစီမံခန့်ခွဲမှု</strong>
    </div>

    <div class="switcher-btns d-flex align-items-center gap-2">
        <a href="{{ route('backend.dashboard') }}" class="btn btn-sm btn-primary active">
            🔐 စီမံခန့်ခွဲသူ / ဝန်ထမ်း စာမျက်နှာ
        </a>

        <a href="{{ url('/') }}" class="btn btn-sm btn-outline-light" target="_blank">
            🌐 ပြည်သူ့ပေါ်တယ်
        </a>

        <!-- User Dropdown & Logout -->
        @auth
            <div class="dropdown ms-3">
                <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-user-circle me-1"></i> {{ Auth::user()->name }} ({{ ucfirst(Auth::user()->role) }})
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userMenu">
                    <li>
                        <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa-solid fa-right-from-bracket me-2"></i> ထွက်မည် (Logout)
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        @endauth
    </div>
</div>

<!-- =========================================================
     ADMIN PANEL
========================================================= -->
<div id="admin-panel" class="panel-view active">
    <div class="admin-layout">

        <!-- =====================================================
             SIDEBAR
        ====================================================== -->
        <aside class="sidebar">

            <!-- SYSTEM BRAND -->
            <div class="sidebar-brand">
                <i class="fa-solid fa-hands-holding-circle" style="color:#38bdf8;"></i>
                <span>သဘာဝဘေး ကူညီကယ်ဆယ်ရေး</span>
            </div>

            <!-- SIDEBAR MENU -->
            <ul class="sidebar-menu list-unstyled">

                <!-- Dashboard -->
                <li>
                    <a id="nav-adm-dashboard"
                       class="{{ request()->routeIs('backend.dashboard') ? 'active' : '' }}"
                       href="{{ route('backend.dashboard') }}">
                        <i class="fa-solid fa-chart-line"></i>
                        <span>ပင်မစာမျက်နှာ</span>
                    </a>
                </li>

                <!-- System Admin Only: Warehouses & Users -->
                @if(Auth::check() && Auth::user()->role === 'admin')
                    <li>
                        <a id="nav-adm-warehouses"
                           class="{{ request()->routeIs('backend.warehouses.*') ? 'active' : '' }}"
                           href="{{ route('backend.warehouses.index') }}">
                            <i class="fa-solid fa-warehouse"></i>
                            <span>ကုန်လှောင်ရုံ စီမံခန့်ခွဲမှု</span>
                        </a>
                    </li>

                    <li>
                        <a id="nav-adm-users"
                           class="{{ request()->routeIs('backend.users.*') ? 'active' : '' }}"
                           href="{{ route('backend.users.index') }}">
                            <i class="fa-solid fa-users-gear"></i>
                            <span>အသုံးပြုသူ စီမံခန့်ခွဲမှု</span>
                        </a>
                    </li>
                @endif

                <!-- Categories -->
                <li>
                    <a id="nav-adm-categories"
                       class="{{ request()->routeIs('backend.categories.*') ? 'active' : '' }}"
                       href="{{ route('backend.categories.index') }}">
                        <i class="fa-solid fa-tags"></i>
                        <span>ပစ္စည်းအမျိုးအစားများ</span>
                    </a>
                </li>

                <!-- Items -->
                <li>
                    <a id="nav-adm-items"
                       class="{{ request()->routeIs('backend.items.*') ? 'active' : '' }}"
                       href="{{ route('backend.items.index') }}">
                        <i class="fa-solid fa-boxes-stacked"></i>
                        <span>ကူညီကယ်ဆယ်ရေး ပစ္စည်းများ</span>
                    </a>
                </li>

                <!-- Inventory -->
                <li>
                    <a id="nav-adm-inventory"
                       class="{{ request()->routeIs('backend.inventories.*') ? 'active' : '' }}"
                       href="{{ route('backend.inventories.index') }}">
                        <i class="fa-solid fa-cubes"></i>
                        <span>ပစ္စည်းလက်ကျန်စာရင်း</span>
                    </a>
                </li>

                <!-- QR Scanner -->
                {{-- <li>
                    <a id="nav-adm-qr"
                       class="{{ request()->routeIs('backend.scan') ? 'active' : '' }}"
                       href="{{ route('backend.scan') }}">
                        <i class="fa-solid fa-qrcode"></i>
                        <span>QR Code Scan ဖတ်ရန်</span>
                    </a>
                </li> --}}

                <!-- Disaster Events -->
                <li>
                    <a id="nav-adm-events"
                       class="{{ request()->routeIs('backend.disasters.*') ? 'active' : '' }}"
                       href="{{ route('backend.disasters.index') }}">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <span>သဘာဝဘေးဖြစ်စဉ်များ</span>
                    </a>
                </li>

                <!-- Donors -->
                <li>
                    <a id="nav-adm-donors"
                       class="{{ request()->routeIs('backend.donors.*') ? 'active' : '' }}"
                       href="{{ route('backend.donors.index') }}">
                        <i class="fa-solid fa-user-group"></i>
                        <span>အလှူရှင်များ စာရင်း</span>
                    </a>
                </li>

                <!-- Item Donations -->
                <li>
                    <a id="nav-adm-donations"
                       class="{{ request()->routeIs('backend.donations.*') ? 'active' : '' }}"
                       href="{{ route('backend.donations.index') }}">
                        <i class="fa-solid fa-hand-holding-heart"></i>
                        <span>ပစ္စည်း လှူဒါန်းမှုများ</span>
                    </a>
                </li>

                <!-- Donation Payments -->
                <li>
                    <a id="nav-adm-donation_payments"
                       class="{{ request()->routeIs('backend.donation_payments.*') ? 'active' : '' }}"
                       href="{{ route('backend.donation_payments.index') }}">
                        <i class="fa-solid fa-money-bill-wave"></i>
                        <span>ငွေကြေး လှူဒါန်းမှုများ</span>
                    </a>
                </li>
                {{-- Donation Funds --}}
                <li>
                    <a id="nav-adm-donation_funds"
                    class="{{ request()->routeIs('backend.donation-funds') ? 'active' : '' }}"
                    href="{{ route('backend.donation-funds') }}">

                        <i class="fa-solid fa-money-bill-wave"></i>

                        <span>လှူဒါန်းငွေ လက်ကျန်</span>

                    </a>
                </li>
                <!-- Relief Requests -->
                <li>
                    <a id="nav-adm-requests"
                       class="{{ request()->routeIs('backend.relief_requests.*') ? 'active' : '' }}"
                       href="{{ route('backend.relief_requests.index') }}">
                        <i class="fa-solid fa-clipboard-list"></i>
                        <span>အကူအညီ တောင်းခံမှုများ</span>
                    </a>
                </li>

                <!-- Distribution -->
                <li>
                    <a id="nav-adm-distribution"
                       class="{{ request()->routeIs('backend.distributions.*') ? 'active' : '' }}"
                       href="{{ route('backend.distributions.index') }}">
                        <i class="fa-solid fa-truck-fast"></i>
                        <span>ကူညီပစ္စည်း ဖြန့်ဝေမှုများ</span>
                    </a>
                </li>

                <!-- REPORTS MENU -->
                <li class="nav-item">
                    <a id="nav-adm-reports"
                       class="d-flex justify-content-between align-items-center {{ request()->routeIs('backend.reports.*') ? 'active' : '' }}"
                       data-bs-toggle="collapse"
                       href="#reportsMenu"
                       role="button"
                       aria-expanded="{{ request()->routeIs('backend.reports.*') ? 'true' : 'false' }}">
                        <div>
                            <i class="fa-solid fa-file-invoice"></i>
                            <span>အစီရင်ခံစာများ</span>
                        </div>
                        <i class="fa-solid fa-chevron-down small"></i>
                    </a>

                    <div class="collapse {{ request()->routeIs('backend.reports.*') ? 'show' : '' }} ps-3" id="reportsMenu">
                        <ul class="nav flex-column my-1">
                            <li class="nav-item">
                                <a class="nav-link py-1 {{ request()->routeIs('backend.reports.inventory') ? 'fw-bold text-primary' : '' }}"
                                   href="{{ route('backend.reports.inventory') }}">
                                    <i class="fa-solid fa-angle-right me-1"></i> ပစ္စည်းလက်ကျန်
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link py-1 {{ request()->routeIs('backend.reports.distribution') ? 'fw-bold text-primary' : '' }}"
                                   href="{{ route('backend.reports.distribution') }}">
                                    <i class="fa-solid fa-angle-right me-1"></i> ပစ္စည်းဖြန့်ဝေမှု
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link py-1 {{ request()->routeIs('backend.reports.stock-movement') ? 'fw-bold text-primary' : '' }}"
                                   href="{{ route('backend.reports.stock-movement') }}">
                                    <i class="fa-solid fa-angle-right me-1"></i> အဝင်အထွက် မှတ်တမ်း
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link py-1 {{ request()->routeIs('backend.reports.donation') ? 'fw-bold text-primary' : '' }}"
                                   href="{{ route('backend.reports.donation') }}">
                                    <i class="fa-solid fa-angle-right me-1"></i> လှူဒါန်းမှု အစီရင်ခံစာ
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link py-1 {{ request()->routeIs('backend.reports.relief-request') ? 'fw-bold text-primary' : '' }}"
                                   href="{{ route('backend.reports.relief-request') }}">
                                    <i class="fa-solid fa-angle-right me-1"></i> အကူအညီတောင်းခံမှု
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link py-1 {{ request()->routeIs('backend.reports.low-stock') ? 'fw-bold text-primary' : '' }}"
                                   href="{{ route('backend.reports.low-stock') }}">
                                    <i class="fa-solid fa-angle-right me-1"></i> လက်ကျန်နည်းသော ပစ္စည်းများ
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

            </ul>
        </aside>

        <!-- =====================================================
             MAIN CONTENT
        ====================================================== -->
        <main class="main admin-main">

            <!-- TOP HEADER -->
            <header class="header admin-header d-flex justify-content-between align-items-center p-3 bg-white border-bottom shadow-sm">
                <h1 class="h4 m-0 font-weight-bold">
                    @yield('title', 'ပင်မစာမျက်နှာ')
                </h1>

                <div class="header-actions">
                    @yield('button')
                </div>
            </header>

            <!-- FLASH MESSAGES -->
            <div class="container-fluid px-4 pt-3">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                        <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert">
                        <i class="fa-solid fa-triangle-exclamation me-2"></i> အချက်အလက်များ ဖြည့်စွက်ရာတွင် အမှားအယွင်းရှိနေပါသည်။
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>

            <!-- CONTENT AREA -->
            <div class="admin-content p-4">
                @yield('content')
            </div>

            <!-- FOOTER -->
            <footer class="footer text-center py-3 border-top bg-light mt-auto">
                <small class="text-muted">
                    © {{ date('Y') }} Smart သဘာဝဘေး ကူညီကယ်ဆယ်ရေး ကုန်လှောင်ရုံ စီမံခန့်ခွဲမှုစနစ်။
                </small>
            </footer>

        </main>
    </div>
</div>

<!-- =========================================================
     JAVASCRIPT
========================================================= -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('admin-assets/js/scripts.js') }}"></script>

@yield('script')

</body>
</html>
