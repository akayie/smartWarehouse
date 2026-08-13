<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Smart Disaster Relief Warehouse System')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">

    <!-- Custom WMS Admin CSS -->
    <link rel="stylesheet" href="{{ asset('admin-assets/css/styles.css') }}">

    @yield('css')
</head>

<body>

    <!-- TOP SYSTEM SWITCHER BANNER -->
    <div class="top-switcher-bar">
        <div><strong>Disaster Relief Operations</strong> | Internal Portal</div>
        <div class="switcher-btns">
            <button class="active" onclick="window.location.href='{{ route('backend.dashboard') }}'">🔐 Admin / Staff Panel</button>
            <button onclick="window.location.href='{{ url('/') }}'">🌐 Public / User Panel</button>
        </div>
    </div>

    <!-- MAIN ADMIN PANEL CONTAINER -->
    <div id="admin-panel" class="panel-view active">
        <div class="admin-layout">

            <!-- SIDEBAR NAVIGATION -->
            <aside class="sidebar">
                <div class="sidebar-brand">
                    <i class="fa-solid fa-hands-holding-circle" style="color: #38bdf8;"></i>
                    <span>Relief WMS</span>
                </div>
                <ul class="sidebar-menu list-unstyled">
                    <li>
                        <a id="nav-adm-dashboard" class="{{ request()->routeIs('backend.dashboard') ? 'active' : '' }}" href="{{ route('backend.dashboard') }}">
                            <i class="fa-solid fa-chart-line"></i> <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a id="nav-adm-warehouses" class="{{ request()->routeIs('backend.warehouses.*') ? 'active' : '' }}" href="{{ route('backend.warehouses.index') }}">
                            <i class="fa-solid fa-warehouse"></i> <span>Warehouses</span>
                        </a>
                    </li>
                    <li>
                        <a id="nav-adm-categories" class="{{ request()->routeIs('backend.categories.*') ? 'active' : '' }}" href="{{ route('backend.categories.index') }}">
                            <i class="fa-solid fa-tags"></i> <span>Categories</span>
                        </a>
                    </li>
                    <li>
                        <a id="nav-adm-items" class="{{ request()->routeIs('backend.items.*') ? 'active' : '' }}" href="{{ route('backend.items.index') }}">
                            <i class="fa-solid fa-boxes-stacked"></i> <span>Item Catalog</span>
                        </a>
                    </li>
                    <li>
                        <a id="nav-adm-inventory" class="{{ request()->routeIs('backend.inventories.*') ? 'active' : '' }}" href="{{ route('backend.inventories.index') }}">
                            <i class="fa-solid fa-cubes"></i> <span>Inventory</span>
                        </a>
                    </li>
                    <li>
                        <a id="nav-adm-qr" class="{{ request()->routeIs('backend.scan') ? 'active' : '' }}" href="{{ route('backend.scan') }}">
                            <i class="fa-solid fa-qrcode"></i> <span>QR Scanner</span>
                        </a>
                    </li>
                    <li>
                        <a id="nav-adm-events" class="{{ request()->routeIs('backend.disasters.*') ? 'active' : '' }}" href="{{ route('backend.disasters.index') }}">
                            <i class="fa-solid fa-triangle-exclamation"></i> <span>Campaigns / Disasters</span>
                        </a>
                    </li>
                    <li>
                        <a id="nav-adm-donations" class="{{ request()->routeIs('backend.donations.*') ? 'active' : '' }}" href="{{ route('backend.donations.index') }}">
                            <i class="fa-solid fa-hand-holding-heart"></i> <span>Donations</span>
                        </a>
                    </li>
                    <li>
                        <a id="nav-adm-requests" class="{{ request()->routeIs('backend.relief_requests.*') ? 'active' : '' }}" href="{{ route('backend.relief_requests.index') }}">
                            <i class="fa-solid fa-clipboard-list"></i> <span>Relief Requests</span>
                        </a>
                    </li>
                    <li>
                        <a id="nav-adm-distribution" class="{{ request()->routeIs('backend.distributions.*') ? 'active' : '' }}" href="{{ route('backend.distributions.index') }}">
                            <i class="fa-solid fa-truck-fast"></i> <span>Distributions</span>
                        </a>
                    </li>
                    <li>
                        <a id="nav-adm-distribution-items" class="{{ request()->routeIs('backend.distribution_items.*') ? 'active' : '' }}" href="{{ route('backend.distribution_items.index') }}">
                            <i class="fa-solid fa-list-check"></i> <span>Distribution Items</span>
                        </a>
                    </li>
                    <li>
                        <a id="nav-adm-users" class="{{ request()->routeIs('backend.users.*') ? 'active' : '' }}" href="{{ route('backend.users.index') }}">
                            <i class="fa-solid fa-users-gear"></i> <span>Users & Roles</span>
                        </a>
                    </li>

                    <!-- REPORTS DROPDOWN MENU -->
                    <li class="nav-item">
                        <a id="nav-adm-reports" class="d-flex justify-content-between align-items-center {{ request()->routeIs('backend.reports.*') ? 'active' : '' }}"
                           data-bs-toggle="collapse" href="#reportsMenu" role="button" aria-expanded="{{ request()->routeIs('backend.reports.*') ? 'true' : 'false' }}">
                            <div>
                                <i class="fa-solid fa-file-invoice"></i> <span>Reports</span>
                            </div>
                            <i class="fa-solid fa-chevron-down small"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('backend.reports.*') ? 'show' : '' }} ps-3" id="reportsMenu">
                            <ul class="nav flex-column my-1">
                                <li class="nav-item">
                                    <a class="nav-link py-1 {{ request()->routeIs('backend.reports.inventory') ? 'fw-bold text-primary' : '' }}" href="{{ route('backend.reports.inventory') }}">
                                        <i class="fa-solid fa-angle-right me-1"></i> Inventory Report
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link py-1 {{ request()->routeIs('backend.reports.distribution') ? 'fw-bold text-primary' : '' }}" href="{{ route('backend.reports.distribution') }}">
                                        <i class="fa-solid fa-angle-right me-1"></i> Distribution Report
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link py-1 {{ request()->routeIs('backend.reports.stock-movement') ? 'fw-bold text-primary' : '' }}" href="{{ route('backend.reports.stock-movement') }}">
                                        <i class="fa-solid fa-angle-right me-1"></i> Stock Movement
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link py-1 {{ request()->routeIs('backend.reports.donation') ? 'fw-bold text-primary' : '' }}" href="{{ route('backend.reports.donation') }}">
                                        <i class="fa-solid fa-angle-right me-1"></i> Donation Report
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link py-1 {{ request()->routeIs('backend.reports.relief-request') ? 'fw-bold text-primary' : '' }}" href="{{ route('backend.reports.relief-request') }}">
                                        <i class="fa-solid fa-angle-right me-1"></i> Relief Request Report
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link py-1 {{ request()->routeIs('backend.reports.low-stock') ? 'fw-bold text-primary' : '' }}" href="{{ route('backend.reports.low-stock') }}">
                                        <i class="fa-solid fa-angle-right me-1"></i> Low Stock Alert
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </aside>

            <!-- MAIN CONTENT AREA -->
            <main class="main admin-main">
                <!-- TOP HEADER -->
                <header class="header admin-header d-flex justify-content-between align-items-center">
                    <h1 class="h4 m-0 font-weight-bold">@yield('title', 'Dashboard')</h1>
                    <div class="header-actions">
                        @yield('button')
                    </div>
                </header>

                <!-- DYNAMIC CONTENT BODY -->
                <div class="admin-content p-4">
                    @yield('content')
                </div>

                <!-- FOOTER -->
                <footer class="footer text-center py-3 border-top">
                    <small class="text-muted">© 2026 Smart Disaster Relief Warehouse System. All rights reserved.</small>
                </footer>
            </main>

        </div>
    </div>

    <!-- SCRIPTS -->
    <script src="{{ asset('admin-assets/js/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('admin-assets/js/scripts.js') }}"></script>
    @yield('script')

</body>
</html>
