@props(['pageTitle' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle ? $pageTitle . ' | ' : '' }}Admin · {{ config('app.name', 'Love Bite') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
    <style>
        /* Admin sidebar uses the site's own warm background */
        body { background: radial-gradient(circle at top left, #ffedd5 0%, #fff7ed 35%, #fff 100%); }
        .admin-sidebar {
            background: radial-gradient(circle at top left, #ffedd5 0%, #fff7ed 35%, #fff 100%);
            border-right: 1px solid #ffe0b2;
        }
        .admin-nav-item {
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
        .admin-nav-item:hover {
            background: #fed7aa;
            color: #7c2d12;
        }
        .admin-nav-item.active {
            background: linear-gradient(110deg, #ea580c 0%, #f97316 60%, #fb923c 100%);
            color: #fff;
            box-shadow: 0 4px 18px rgba(234,88,12,0.22);
        }
        .admin-nav-item svg {
            flex-shrink: 0;
            width: 18px;
            height: 18px;
            opacity: 0.8;
        }
        .admin-nav-item.active svg { opacity: 1; }
        .admin-topbar {
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid #fed7aa;
        }
        .admin-main { background: transparent; }
    </style>
</head>
<body class="antialiased overflow-x-hidden">

<div class="flex h-screen overflow-hidden">

    {{-- ===== SIDEBAR ===== --}}
    <aside id="admin-sidebar"
           class="admin-sidebar flex flex-col w-60 flex-shrink-0 z-30
                  fixed inset-y-0 left-0 -translate-x-full md:translate-x-0 md:static transition-transform duration-300">

        {{-- Logo --}}
        <div class="flex items-center gap-1.5 h-16 px-5 border-b border-orange-200 flex-shrink-0">
            <a href="{{ route('home') }}"
               class="display-font text-3xl text-transparent bg-clip-text bg-gradient-to-r from-orange-600 to-amber-500 leading-none tracking-wider">
                Love Bite
            </a>
            <span class="text-[9px] font-black text-orange-400 uppercase tracking-widest pt-1.5">admin</span>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
            @php
                $navItems = [
                    [
                        'route' => 'admin.dashboard',
                        'label' => 'Home',
                        'match' => 'admin.dashboard',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>',
                    ],
                    [
                        'route' => 'admin.analytics.revenue',
                        'label' => 'Revenue',
                        'match' => 'admin.analytics.*',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/>',
                    ],
                    [
                        'route' => 'admin.orders.index',
                        'label' => 'Orders',
                        'match' => 'admin.orders.*',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>',
                    ],
                    [
                        'route' => 'admin.categories.index',
                        'label' => 'Categories',
                        'match' => 'admin.categories.*',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/>',
                    ],
                    [
                        'route' => 'admin.foods.index',
                        'label' => 'Food Items',
                        'match' => 'admin.foods.*',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8.25v-1.5m0 1.5c-1.355 0-2.697.056-4.024.166C6.845 8.51 6 9.473 6 10.608v2.513m6-4.87c1.355 0 2.697.055 4.024.165C17.155 8.51 18 9.473 18 10.608v2.513m-3-4.87v-1.5m-6 1.5v-1.5m12 9.75l-1.5.75a3.354 3.354 0 01-3 0 3.354 3.354 0 00-3 0 3.354 3.354 0 01-3 0 3.354 3.354 0 00-3 0 3.354 3.354 0 01-3 0L3 16.5m15-3.38a48.474 48.474 0 00-6-.37c-2.032 0-4.034.125-6 .37m12 0c.39.049.777.102 1.163.16 1.07.16 1.837 1.094 1.837 2.175v5.17c0 .62-.504 1.124-1.125 1.124H4.125A1.125 1.125 0 013 20.625v-5.17c0-1.08.768-2.014 1.837-2.174A47.78 47.78 0 015 13.12M12.265 3.11a.375.375 0 11-.53 0L12 2.845l.265.265z"/>',
                    ],
                    [
                        'route' => 'admin.coupons.index',
                        'label' => 'Coupons',
                        'match' => 'admin.coupons.*',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m3-12h-15c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h15c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V3.375c0-.621-.504-1.125-1.125-1.125zm-12 5.25h6m-6 3h6m-6 3h6"/>',
                    ],
                    [
                        'route' => 'admin.settings',
                        'label' => 'Settings',
                        'match' => 'admin.settings',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>',
                    ],
                ];
            @endphp

            @foreach($navItems as $item)
                @php($isActive = request()->routeIs($item['match']))
                <a href="{{ route($item['route']) }}" class="admin-nav-item {{ $isActive ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        {!! $item['icon'] !!}
                    </svg>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        {{-- Footer --}}
        <div class="flex-shrink-0 px-3 pb-4 pt-3 border-t border-orange-200 space-y-1">
            @php($__isOpen = \App\Models\Setting::isOpen())
            <div class="flex items-center gap-2 px-3 py-2 text-xs font-bold {{ $__isOpen ? 'text-green-700' : 'text-red-700' }}">
                <span class="w-2 h-2 rounded-full flex-shrink-0 {{ $__isOpen ? 'bg-green-500 animate-pulse' : 'bg-red-500' }}"></span>
                {{ $__isOpen ? 'Restaurant Open' : 'Restaurant Closed' }}
            </div>
            <a href="{{ route('home') }}"
               class="admin-nav-item text-orange-700/60 hover:text-orange-900" style="font-weight:600">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Site
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="admin-nav-item w-full text-left text-red-500 hover:!text-red-700 hover:!bg-red-50"
                    style="border:none;background:none;cursor:pointer;">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="flex flex-col flex-1 min-w-0 overflow-hidden">

        {{-- Top bar --}}
        <header class="admin-topbar flex items-center justify-between h-16 px-6 flex-shrink-0 z-10">
            <div class="flex items-center gap-3">
                {{-- Mobile toggle --}}
                <button id="sidebar-toggle" class="md:hidden p-2 rounded-xl hover:bg-orange-100 text-orange-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                {{-- Page title using site's display-font --}}
                @if($pageTitle)
                    <h1 class="display-font text-4xl text-gray-900 leading-none">{{ $pageTitle }}</h1>
                @endif
            </div>
            <div class="flex items-center gap-3">
                {{-- Notification bell --}}
                @php($adminUnread = \App\Models\UserNotification::whereNull('read_at')->count())
                <a href="{{ route('notifications.index') }}"
                   class="relative p-2 rounded-xl hover:bg-orange-100 text-orange-700 transition"
                   title="Notifications">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                    </svg>
                    @if($adminUnread > 0)
                        <span class="absolute -top-0.5 -right-0.5 flex items-center justify-center min-w-[18px] h-[18px] px-1 text-[10px] font-black text-white bg-red-500 rounded-full shadow">
                            {{ $adminUnread > 99 ? '99+' : $adminUnread }}
                        </span>
                    @endif
                </a>
                <span class="text-sm font-semibold text-gray-600 hidden sm:block">{{ auth()->user()->name }}</span>
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-orange-400 to-amber-500 flex items-center justify-center text-white font-black text-sm shadow-md shadow-orange-200">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            </div>
        </header>

        {{-- Flash messages --}}
        @if(session('success'))
        <div class="mx-6 mt-5 flex items-center gap-3 rounded-2xl border border-green-200 bg-green-50 px-5 py-3 text-green-800 text-sm font-semibold" id="flash-success">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="mx-6 mt-5 rounded-2xl border border-red-200 bg-red-50 px-5 py-3 text-red-800 text-sm font-semibold">
            <ul class="list-disc pl-4 space-y-0.5">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
        @endif

        {{-- Slot --}}
        <main class="admin-main flex-1 overflow-y-auto p-6 space-y-6">
            {{ $slot }}
        </main>
    </div>
</div>

{{-- Mobile overlay --}}
<div id="sidebar-overlay" class="fixed inset-0 z-20 bg-black/30 backdrop-blur-sm hidden md:hidden"></div>

<script>
    const sidebar  = document.getElementById('admin-sidebar');
    const overlay  = document.getElementById('sidebar-overlay');
    const toggle   = document.getElementById('sidebar-toggle');
    function openSb()  { sidebar.classList.remove('-translate-x-full'); overlay.classList.remove('hidden'); }
    function closeSb() { sidebar.classList.add('-translate-x-full'); overlay.classList.add('hidden'); }
    if (toggle)  toggle.addEventListener('click', openSb);
    if (overlay) overlay.addEventListener('click', closeSb);
    // Auto-fade flash
    const flash = document.getElementById('flash-success');
    if (flash) setTimeout(() => { flash.style.transition = 'opacity 0.5s'; flash.style.opacity = '0'; setTimeout(() => flash.remove(), 500); }, 3500);
</script>
@stack('scripts')
</body>
</html>
