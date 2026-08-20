<!DOCTYPE html>
<html lang="my">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Smart Relief ')</title>

    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Vite Assets Loading (Bootstrap & App JS/CSS) -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('front-assets/css/styles.css') }}">
    @stack('styles')
</head>
<body>

    <!-- DEMO NAVIGATION BAR SWITCHER -->
    <div class="panel-switcher">
        <span>
            <strong>Smart သဘာဝဘေး ကူညီကယ်ဆယ်ရေးစနစ်</strong>
        </span>
        <div class="switcher-btns">
            <button class="active" onclick="window.location.href='{{ route('home') }}'">🌐 Public / အသုံးပြုသူ စာမျက်နှာ</button>

            @auth
    @if(in_array(Auth::user()->role, ['admin', 'manager', 'warehouse_manager']))
        <button onclick="window.location.href='{{ route('backend.dashboard') }}'">🔐 စီမံခန့်ခွဲသူ / ဝန်ထမ်း စာမျက်နှာ</button>
    @endif
@endauth
        </div>
    </div>

    <!-- PUBLIC / USER PANEL -->
    <div id="public-panel">
        <!-- Public Navigation -->
        <nav class="public-navbar">
            <div class="container nav-wrap d-flex justify-content-between align-items-center py-2">
                <a href="{{ route('home') }}" class="brand-logo text-decoration-none fw-bold fs-4">
                    <i class="fa-solid fa-hand-holding-hand"></i> Diaster RELIEF
                </a>

                <button class="mobile-toggle d-md-none" aria-label="Toggle navigation">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <ul class="public-nav-links d-flex list-unstyled m-0 gap-3" id="nav-links">
                    <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active fw-bold' : '' }}">ပင်မစာမျက်နှာ</a></li>
                    <li><a href="{{ route('public.about') }}" class="{{ request()->routeIs('public.about') ? 'active fw-bold' : '' }}">ကျွန်ုပ်တို့အကြောင်း / ကူညီဆောင်ရွက်မှုများ</a></li>
                    <li><a href="{{ route('public.campaigns') }}" class="{{ request()->routeIs('public.campaigns') ? 'active fw-bold' : '' }}">သဘာဝဘေး ဖြစ်စဉ်များ</a></li>

                    {{-- @auth --}}
                        <li><a href="{{ route('public.request.create') }}" class="{{ request()->routeIs('public.request.create') ? 'active fw-bold' : '' }}">အကူအညီ တောင်းခံရန်</a></li>
                        <li><a href="{{ route('public.my-requests') }}" class="{{ request()->routeIs('public.my-requests') ? 'active fw-bold' : '' }}">ကျွန်ုပ်၏ တောင်းခံလွှာများ</a></li>
                        <li><a href="{{ route('public.donate.create') }}" class="{{ request()->routeIs('public.donate.create') ? 'active fw-bold' : '' }}">လှူဒါန်းမှု ပြုလုပ်ရန်</a></li>
                        <li><a href="{{ route('public.don-history') }}" class="{{ request()->routeIs('public.don-history') ? 'active fw-bold' : '' }}">လှူဒါန်းမှု မှတ်တမ်း</a></li>
                    {{-- @endauth --}}
                </ul>

                <div class="nav-actions d-flex align-items-center gap-2">
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fa-solid fa-right-to-bracket"></i> အကောင့်ဝင်မည်
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-user-plus"></i> အကောင့်သစ်ဖွင့်မည်
                        </a>
                    @else
                        <div class="dropdown">
                            <button class="btn btn-light dropdown-toggle btn-sm border" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-user-circle"></i> {{ Auth::user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                <li>
                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fa-solid fa-power-off me-2"></i> အကောင့်မှ ထွက်မည်
                                    </a>
                                </li>
                            </ul>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    @endguest
                </div>
            </div>
        </nav>

        <div class="container public-content-wrap my-4">
            @yield('content')
        </div>
    </div>

    <!-- Custom JS -->
    <script src="{{ asset('js/user-panel.js') }}"></script>
    @stack('scripts')
</body>
</html>
