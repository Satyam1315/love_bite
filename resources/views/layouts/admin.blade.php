<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($pageTitle) ? $pageTitle . ' | ' : '' }}Admin · {{ config('app.name', 'Love Bite') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="antialiased bg-gray-100 text-gray-800">

<div class="flex h-screen overflow-hidden">

    {{-- ===== SIDEBAR ===== --}}
    <aside id="admin-sidebar" class="flex flex-col w-64 flex-shrink-0 bg-gray-900 text-white transition-all duration-300 z-30">

        {{-- Logo --}}
        <div class="flex items-center h-16 px-6 border-b border-white/10 flex-shrink-0">
            <a href="{{ route('home') }}" class="display-font text-2xl text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-amber-400 leading-none">
                Love Bite
            </a>
            <span class="ml-2 text-[10px] font-bold text-gray-500 uppercase tracking-widest mt-0.5">Admin</span>
        </div>

        {{-- Nav Items --}}
        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">

            @php
                $navItems = [
                    ['route' => 'admin.dashboard',        'label' => 'Home',       'match' => 'admin.dashboard', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>'],
                    ['route' => 'admin.analytics.revenue','label' => 'Revenue',    'match' => 'admin.analytics.*','icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/>'],
                    ['route' => 'admin.categories.index', 'label' => 'Categories', 'match' => 'admin.categories.*','icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/>'],
                    ['route' => 'admin.foods.index',      'label' => 'Food Items', 'match' => 'admin.foods.*',    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8.25v-1.5m0 1.5c-1.355 0-2.697.056-4.024.166C6.845 8.51 6 9.473 6 10.608v2.513m6-4.87c1.355 0 2.697.055 4.024.165C17.155 8.51 18 9.473 18 10.608v2.513m-3-4.87v-1.5m-6 1.5v-1.5m12 9.75l-1.5.75a3.354 3.354 0 01-3 0 3.354 3.354 0 00-3 0 3.354 3.354 0 01-3 0 3.354 3.354 0 00-3 0 3.354 3.354 0 01-3 0L3 16.5m15-3.38a48.474 48.474 0 00-6-.37c-2.032 0-4.034.125-6 .37m12 0c.39.049.777.102 1.163.16 1.07.16 1.837 1.094 1.837 2.175v5.17c0 .62-.504 1.124-1.125 1.124H4.125A1.125 1.125 0 013 20.625v-5.17c0-1.08.768-2.014 1.837-2.174A47.78 47.78 0 015 13.12M12.265 3.11a.375.375 0 11-.53 0L12 2.845l.265.265z"/>'],
                    ['route' => 'admin.settings',         'label' => 'Settings',   'match' => 'admin.settings',   'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>'],
                ];
            @endphp

            @foreach($navItems as $item)
                @php($active = request()->routeIs($item['match']))
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 group
                          {{ $active ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/20' : 'text-gray-400 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0 {{ $active ? 'text-white' : 'text-gray-500 group-hover:text-orange-400' }} transition"
                         fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        {!! $item['icon'] !!}
                    </svg>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        {{-- Sidebar Footer: status + back to site --}}
        <div class="px-3 py-4 border-t border-white/10 space-y-2 flex-shrink-0">
            @php($__isOpen = \App\Models\Setting::isOpen())
            <a href="{{ route('admin.settings') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-bold {{ $__isOpen ? 'text-green-400 hover:bg-green-500/10' : 'text-red-400 hover:bg-red-500/10' }} transition">
                <span class="w-2 h-2 rounded-full {{ $__isOpen ? 'bg-green-400' : 'bg-red-400' }} animate-pulse flex-shrink-0"></span>
                {{ $__isOpen ? 'Restaurant Open' : 'Restaurant Closed' }}
            </a>
            <a href="{{ route('home') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold text-gray-500 hover:bg-white/10 hover:text-white transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Site
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold text-gray-500 hover:bg-red-500/10 hover:text-red-400 transition text-left">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- ===== MAIN CONTENT AREA ===== --}}
    <div class="flex flex-col flex-1 min-w-0 overflow-hidden">

        {{-- Top bar --}}
        <header class="flex items-center justify-between h-16 px-6 bg-white border-b border-gray-200 flex-shrink-0 shadow-sm">
            <div class="flex items-center gap-3">
                {{-- Mobile sidebar toggle --}}
                <button id="sidebar-toggle" class="md:hidden p-2 rounded-lg hover:bg-gray-100 text-gray-500 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                @isset($pageTitle)
                    <h1 class="display-font text-2xl text-gray-900">{{ $pageTitle }}</h1>
                @else
                    <h1 class="display-font text-2xl text-gray-900">Admin Panel</h1>
                @endisset
            </div>
            <div class="flex items-center gap-3">
                <span class="text-sm font-semibold text-gray-600 hidden sm:block">{{ auth()->user()->name }}</span>
                <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center text-orange-700 font-bold text-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            </div>
        </header>

        {{-- Flash messages --}}
        @if(session('success'))
        <div class="mx-6 mt-4 flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 px-5 py-3 text-green-800 text-sm font-semibold" id="flash-success">
            <svg class="w-5 h-5 flex-shrink-0 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="mx-6 mt-4 rounded-xl border border-red-200 bg-red-50 px-5 py-3 text-red-800 text-sm">
            <ul class="list-disc pl-4 space-y-0.5">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
        @endif

        {{-- Page content --}}
        <main class="flex-1 overflow-y-auto p-6">
            {{ $slot }}
        </main>
    </div>
</div>

{{-- Mobile sidebar overlay --}}
<div id="sidebar-overlay" class="fixed inset-0 z-20 bg-black/40 backdrop-blur-sm hidden md:hidden"></div>

<script>
    const sidebar = document.getElementById('admin-sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const toggle  = document.getElementById('sidebar-toggle');

    function openSidebar()  { sidebar.classList.remove('-translate-x-full'); overlay.classList.remove('hidden'); }
    function closeSidebar() { sidebar.classList.add('-translate-x-full'); overlay.classList.add('hidden'); }

    if (toggle)  toggle.addEventListener('click', openSidebar);
    if (overlay) overlay.addEventListener('click', closeSidebar);

    // Auto-hide flash after 4s
    const flash = document.getElementById('flash-success');
    if (flash) setTimeout(() => flash.style.display = 'none', 4000);
</script>

@stack('scripts')
</body>
</html>
