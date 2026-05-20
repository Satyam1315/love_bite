<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @isset($pageDescription)
        <meta name="description" content="{{ $pageDescription }}">
    @else
        <meta name="description" content="Love Bite — Fresh Indian food, delivered fast. Order tandoor, biryani, curries & more.">
    @endisset

    <title>@isset($pageTitle){{ $pageTitle }} | @endisset{{ config('app.name', 'Love Bite') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
    <style>
        /* Modern unified sidebar styling */
        body { background: radial-gradient(circle at top left, #ffedd5 0%, #fff7ed 35%, #fff 100%); }
        .app-sidebar {
            background: radial-gradient(circle at top left, #ffedd5 0%, #fff7ed 35%, #fff 100%);
            border-right: 1px solid #ffe0b2;
        }
        .app-nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 14px;
            font-size: 0.875rem;
            font-weight: 700;
            color: #9a3412;
            transition: all 0.18s;
            text-decoration: none;
        }
        .app-nav-item:hover {
            background: #fed7aa;
            color: #7c2d12;
        }
        .app-nav-item.active {
            background: linear-gradient(110deg, #ea580c 0%, #f97316 60%, #fb923c 100%);
            color: #fff;
            box-shadow: 0 4px 18px rgba(234,88,12,0.22);
        }
        .app-nav-item svg {
            flex-shrink: 0;
            width: 18px;
            height: 18px;
            opacity: 0.8;
        }
        .app-nav-item.active svg { opacity: 1; }
        .app-topbar {
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid #fed7aa;
        }
        .app-main { background: transparent; }
    </style>
</head>
<body class="antialiased overflow-x-hidden">

<div class="flex h-screen overflow-hidden">

    {{-- ===== SIDEBAR ===== --}}
    <aside id="app-sidebar"
           class="app-sidebar flex flex-col w-60 flex-shrink-0 z-30
                  fixed inset-y-0 left-0 -translate-x-full md:translate-x-0 md:static transition-transform duration-300">

        {{-- Logo --}}
        <div class="flex items-center gap-1.5 h-16 px-5 border-b border-orange-200 flex-shrink-0">
            <a href="{{ route('home') }}"
               class="display-font text-3xl text-transparent bg-clip-text bg-gradient-to-r from-orange-600 to-amber-500 leading-none tracking-wider">
                Love Bite
            </a>
            <span class="text-[9px] font-black text-orange-400 uppercase tracking-widest pt-1.5">fresh</span>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
            @php
                $navItems = [
                    [
                        'route' => 'home',
                        'label' => 'Home',
                        'match' => 'home',
                        'auth_only' => false,
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>',
                    ],
                    [
                        'route' => 'menu.index',
                        'label' => 'Explore Menu',
                        'match' => 'menu.*',
                        'auth_only' => false,
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>',
                    ],
                    [
                        'route' => 'cart.index',
                        'label' => 'My Cart',
                        'match' => 'cart.*',
                        'auth_only' => false,
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>',
                    ],
                    [
                        'route' => 'about',
                        'label' => 'About Us',
                        'match' => 'about',
                        'auth_only' => false,
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 11.518 1.397l-.518.26a1.5 1.5 0 00-.897 1.32V15m.218-11.142A9 9 0 1112 21.75c-4.968 0-9-4.032-9-9 0-1.405.324-2.734.907-3.924M12 7.5h.008v.008H12V7.5z"/>',
                    ],
                    [
                        'route' => 'orders.index',
                        'label' => 'Order History',
                        'match' => 'orders.*',
                        'auth_only' => true,
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>',
                    ],
                    [
                        'route' => 'profile.edit',
                        'label' => 'My Profile & Address',
                        'match' => 'profile.edit',
                        'auth_only' => true,
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25s-7.5-4.108-7.5-11.25a7.5 7.5 0 1115 0z"/>',
                    ],
                    [
                        'route' => 'notifications.index',
                        'label' => 'Notifications',
                        'match' => 'notifications.*',
                        'auth_only' => true,
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>',
                    ],
                ];
            @endphp

            @foreach($navItems as $item)
                @if(!$item['auth_only'] || Auth::check())
                    @php($isActive = request()->routeIs($item['match']))
                    <a href="{{ route($item['route']) }}" class="app-nav-item {{ $isActive ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            {!! $item['icon'] !!}
                        </svg>
                        {{ $item['label'] }}
                        @if($item['route'] === 'notifications.index')
                            @php($unread = auth()->user()->notifications()->whereNull('read_at')->count())
                            @if($unread > 0)
                                <span class="ml-auto rounded-full bg-red-100 px-2 py-0.5 text-xs font-black text-red-700">{{ $unread }}</span>
                            @endif
                        @elseif($item['route'] === 'cart.index')
                            @php($cartCount = count(session('cart', [])))
                            @if($cartCount > 0)
                                <span class="ml-auto rounded-full bg-orange-100 px-2 py-0.5 text-xs font-black text-orange-700">{{ $cartCount }}</span>
                            @endif
                        @endif
                    </a>
                @endif
            @endforeach
        </nav>

        {{-- Footer --}}
        <div class="flex-shrink-0 px-3 pb-4 pt-3 border-t border-orange-200 space-y-1">
            @php($__isOpen = \App\Models\Setting::isOpen())
            <div class="flex items-center gap-2 px-3 py-2 text-xs font-bold {{ $__isOpen ? 'text-green-700' : 'text-red-700' }}">
                <span class="w-2 h-2 rounded-full flex-shrink-0 {{ $__isOpen ? 'bg-green-500 animate-pulse' : 'bg-red-500' }}"></span>
                {{ $__isOpen ? 'Restaurant Open' : 'Restaurant Closed' }}
            </div>

            @auth
                @php($isAdmin = auth()->user()->isAdmin())
                <a href="{{ $isAdmin ? route('admin.dashboard') : route('dashboard') }}"
                   class="app-nav-item text-orange-700 bg-orange-100/50 hover:bg-orange-100" style="font-weight:700">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
                    </svg>
                    Dashboard
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="app-nav-item w-full text-left text-red-500 hover:!text-red-700 hover:!bg-red-50"
                        style="border:none;background:none;cursor:pointer;">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>
                        </svg>
                        Logout
                    </button>
                </form>
            @endauth
        </div>
    </aside>

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="flex flex-col flex-1 min-w-0 overflow-hidden">

        {{-- Top bar --}}
        <header class="app-topbar flex items-center justify-between h-16 px-6 flex-shrink-0 z-10">
            <div class="flex items-center gap-3">
                {{-- Mobile toggle --}}
                <button id="app-sidebar-toggle" class="md:hidden p-2 rounded-xl hover:bg-orange-100 text-orange-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                {{-- Page title using site's display-font --}}
                <h1 class="display-font text-4xl text-gray-900 leading-none">
                    @isset($pageTitle)
                        {{ $pageTitle }}
                    @else
                        Love Bite
                    @endisset
                </h1>
            </div>
            <div class="flex items-center gap-3">
                {{-- Notification bell for logged-in --}}
                @auth
                    @php($unread = auth()->user()->notifications()->whereNull('read_at')->count())
                    <a href="{{ route('notifications.index') }}"
                       class="relative p-2 rounded-xl hover:bg-orange-100 text-orange-700 transition"
                       title="Notifications">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                        </svg>
                        @if($unread > 0)
                            <span class="absolute -top-0.5 -right-0.5 flex items-center justify-center min-w-[18px] h-[18px] px-1 text-[10px] font-black text-white bg-red-500 rounded-full shadow">
                                {{ $unread > 99 ? '99+' : $unread }}
                            </span>
                        @endif
                    </a>
                    <span class="text-sm font-semibold text-gray-600 hidden sm:block">{{ auth()->user()->name }}</span>
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-orange-400 to-amber-500 flex items-center justify-center text-white font-black text-sm shadow-md shadow-orange-200">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                @else
                    <div class="flex items-center gap-2">
                        <a href="{{ route('login') }}" class="rounded-xl border border-orange-200 px-4 py-2 text-sm font-bold text-orange-700 hover:bg-orange-50 transition duration-150">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="rounded-xl bg-orange-600 px-4 py-2 text-sm font-bold text-white hover:bg-orange-700 transition duration-150">
                            Register
                        </a>
                    </div>
                @endauth
            </div>
        </header>

        {{-- ===== RESTAURANT STATUS BANNER ===== --}}
        @if(!$__isOpen)
        <div id="restaurant-closed-banner" class="relative z-40 bg-gradient-to-r from-red-600 to-rose-600 text-white text-center px-4 py-2.5">
            <div class="mx-auto max-w-7xl flex flex-wrap items-center justify-center gap-x-3 gap-y-1">
                <span class="text-lg">🔴</span>
                <p class="text-sm font-semibold">
                    <span class="font-black">We're closed right now.</span>
                    {{ \App\Models\Setting::closedMessage() }}
                </p>
                <button onclick="document.getElementById('restaurant-closed-banner').remove()" class="ml-3 text-white/70 hover:text-white transition text-xs underline">Dismiss</button>
            </div>
        </div>
        @endif

        {{-- Top layout header if supplied --}}
        @isset($header)
            <header class="bg-white/90 shadow-sm backdrop-blur border-b border-orange-100 py-6 px-6">
                {{ $header }}
            </header>
        @endisset

        {{-- Main viewport --}}
        <main class="app-main flex-1 overflow-y-auto p-6 space-y-6">
            {{ $slot }}

            {{-- Shared footer --}}
            <footer class="border-t border-orange-100 bg-gradient-to-b from-white to-orange-50/50 pt-12 mt-12 pb-6">
                <div class="grid gap-8 md:grid-cols-3">
                    <div>
                        <a href="{{ route('home') }}" class="text-3xl text-transparent bg-clip-text bg-gradient-to-r from-orange-600 to-amber-500 display-font">Love Bite</a>
                        <p class="mt-3 text-sm leading-relaxed text-gray-500 font-semibold">Fresh food, crafted with care. From our kitchen to your doorstep — every order is a promise of flavor.</p>
                    </div>
                    <div>
                        <h4 class="mb-3 text-sm font-bold tracking-wider text-gray-900 uppercase">Quick Links</h4>
                        <ul class="space-y-2 text-sm text-gray-600 font-semibold">
                            <li><a href="{{ route('menu.index') }}" class="transition hover:text-orange-600">Full Menu</a></li>
                            <li><a href="{{ route('cart.index') }}" class="transition hover:text-orange-600">My Cart</a></li>
                            <li><a href="{{ route('about') }}" class="transition hover:text-orange-600">About Us</a></li>
                            @auth
                            <li><a href="{{ route('orders.index') }}" class="transition hover:text-orange-600">Track Order</a></li>
                            @endauth
                        </ul>
                    </div>
                    <div>
                        <h4 class="mb-3 text-sm font-bold tracking-wider text-gray-900 uppercase">Contact</h4>
                        <ul class="space-y-2 text-sm text-gray-600 font-semibold">
                            <li>📞 +91 98765 43210</li>
                            <li>📧 hello@lovebite.in</li>
                            <li>📍 Your City, India</li>
                        </ul>
                    </div>
                </div>
                <div class="pt-8 mt-8 text-sm text-center text-gray-400 border-t border-orange-100 font-semibold">
                    <p>© {{ date('Y') }} Love Bite. All rights reserved. Made with ❤️</p>
                </div>
            </footer>
        </main>
    </div>
</div>

{{-- Mobile overlay --}}
<div id="app-sidebar-overlay" class="fixed inset-0 z-20 bg-black/30 backdrop-blur-sm hidden md:hidden"></div>

<script>
    const appSidebar  = document.getElementById('app-sidebar');
    const appOverlay  = document.getElementById('app-sidebar-overlay');
    const appToggle   = document.getElementById('app-sidebar-toggle');
    function openAppSb()  { appSidebar.classList.remove('-translate-x-full'); appOverlay.classList.remove('hidden'); }
    function closeAppSb() { appSidebar.classList.add('-translate-x-full'); appOverlay.classList.add('hidden'); }
    if (appToggle)  appToggle.addEventListener('click', openAppSb);
    if (appOverlay) appOverlay.addEventListener('click', closeAppSb);
</script>
@stack('scripts')
</body>
</html>
