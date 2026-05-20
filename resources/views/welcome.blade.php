<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Love Bite | Fresh Food Delivery</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header class="sticky top-0 z-40 border-b border-orange-100 bg-white/90 backdrop-blur">
        <div class="flex items-center justify-between h-16 px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="text-3xl text-orange-700 display-font">Love Bite</a>
            <nav class="items-center hidden gap-3 md:flex">
                <a href="#menu" class="px-4 py-2 text-sm font-semibold text-gray-700 rounded-full hover:bg-orange-100">Menu</a>
                <a href="#popular" class="px-4 py-2 text-sm font-semibold text-gray-700 rounded-full hover:bg-orange-100">Popular</a>
                <a href="{{ route('cart.index') }}" class="px-4 py-2 text-sm font-bold text-orange-800 bg-orange-100 rounded-full hover:bg-orange-200">Cart</a>
                @auth
                    <a href="{{ route('auth.redirect') }}" class="px-4 py-2 text-sm font-bold text-white bg-gray-900 rounded-full">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="px-4 py-2 text-sm font-bold text-red-700 border border-red-200 rounded-full hover:bg-red-50">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-bold text-orange-700 border border-orange-200 rounded-full">Login</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-bold text-white bg-orange-600 rounded-full">Register</a>
                @endauth
            </nav>
        </div>

        <div class="flex flex-wrap items-center gap-2 px-4 pb-3 mx-auto max-w-7xl md:hidden sm:px-6 lg:px-8">
            <a href="#menu" class="px-3 py-1 text-xs font-bold text-orange-700 border border-orange-200 rounded-full">Menu</a>
            <a href="#popular" class="px-3 py-1 text-xs font-bold text-orange-700 border border-orange-200 rounded-full">Popular</a>
            <a href="{{ route('cart.index') }}" class="px-3 py-1 text-xs font-bold text-orange-800 bg-orange-100 rounded-full">Cart</a>

            @auth
                <a href="{{ route('auth.redirect') }}" class="px-3 py-1 text-xs font-bold text-white bg-gray-900 rounded-full">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-3 py-1 text-xs font-bold text-red-700 border border-red-200 rounded-full">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="px-3 py-1 text-xs font-bold text-orange-700 border border-orange-200 rounded-full">Login</a>
                <a href="{{ route('register') }}" class="px-3 py-1 text-xs font-bold text-white bg-orange-600 rounded-full">Register</a>
            @endauth
        </div>
    </header>

    <main>
        <section class="relative overflow-hidden">
            <div class="absolute w-48 h-48 rounded-full -left-12 top-12 bg-orange-300/30 blur-2xl"></div>
            <div class="absolute right-0 w-56 h-56 rounded-full top-20 bg-green-300/20 blur-2xl"></div>
            <div class="grid gap-8 px-4 py-16 mx-auto max-w-7xl sm:px-6 md:grid-cols-2 lg:px-8">
                <div class="animate-pulse">
                    <p class="mb-2 text-sm font-bold uppercase tracking-[0.2em] text-orange-600">Fast. Tasty. Reliable.</p>
                    <h1 class="mb-4 text-6xl leading-none text-gray-900 display-font sm:text-7xl">Love Bite</h1>
                    <p class="max-w-xl text-lg text-gray-700">Order delicious veg and non-veg dishes with half and full portions. Track every order from pending to delivery in progress.</p>
                    <div class="flex flex-wrap gap-3 mt-8">
                        <a href="#menu" class="px-6 py-3 text-sm font-bold tracking-wide text-white uppercase bg-orange-600 rounded-full hover:bg-orange-700">Explore Menu</a>
                        <a href="{{ route('cart.index') }}" class="px-6 py-3 text-sm font-bold tracking-wide text-gray-700 uppercase border border-gray-300 rounded-full hover:bg-gray-100">Go to Cart</a>
                    </div>
                </div>
                <div class="relative p-6 border border-orange-200 shadow-xl rounded-3xl bg-white/90">
                    <h2 class="text-4xl text-gray-900 display-font">Today Specials</h2>
                    <div class="mt-6 space-y-4">
                        @foreach($popularItems->take(3) as $item)
                            <div class="flex items-center justify-between p-4 border border-orange-100 rounded-2xl bg-orange-50">
                                <div>
                                    <p class="font-bold text-gray-900">{{ $item->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $item->category->name ?? 'Special' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-500">Half: Rs {{ number_format((float) $item->price_half, 2) }}</p>
                                    <p class="font-bold text-orange-700">Full: Rs {{ number_format((float) $item->price_full, 2) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section class="px-4 pb-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <h2 class="text-4xl text-gray-900 display-font">Categories</h2>
            <div class="flex flex-wrap gap-3 mt-4">
                <a href="{{ route('home', array_filter(request()->except('category'))) }}" class="rounded-full border border-orange-200 px-4 py-2 text-sm font-bold {{ request('category') ? 'text-gray-700' : 'bg-orange-600 text-white' }}">All</a>
                @foreach($categories as $category)
                    <a href="{{ route('home', array_merge(request()->all(), ['category' => $category->id])) }}" class="rounded-full border border-orange-200 px-4 py-2 text-sm font-bold {{ (string) request('category') === (string) $category->id ? 'bg-orange-600 text-white' : 'text-gray-700 hover:bg-orange-100' }}">{{ $category->name }}</a>
                @endforeach
            </div>
        </section>

        <section id="popular" class="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <h2 class="text-4xl text-gray-900 display-font">Popular Items</h2>
            <div class="grid gap-6 mt-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse($popularItems as $food)
                    <article class="overflow-hidden lb-card">
                        <img class="object-cover w-full h-44" src="{{ $food->image ? asset('storage/'.$food->image) : 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=900&q=80' }}" alt="{{ $food->name }}" />
                        <div class="p-5">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-lg font-bold">{{ $food->name }}</h3>
                                <span class="lb-badge {{ $food->type === 'veg' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ strtoupper($food->type) }}</span>
                            </div>
                            <p class="text-sm text-gray-600">{{ $food->category->name ?? 'Main Course' }}</p>
                        </div>
                    </article>
                @empty
                    <p class="text-gray-600">No popular items found.</p>
                @endforelse
            </div>
        </section>

        <section id="menu" class="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                <h2 class="text-4xl text-gray-900 display-font">Full Menu</h2>
                <form method="GET" action="{{ route('home') }}" class="flex flex-wrap items-center gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search food..." class="px-4 py-2 text-sm border border-orange-200 rounded-full focus:border-orange-400 focus:ring-orange-300">
                    <select name="type" class="px-4 py-2 text-sm border border-orange-200 rounded-full focus:border-orange-400 focus:ring-orange-300">
                        <option value="">All Types</option>
                        <option value="veg" @selected(request('type') === 'veg')>Veg</option>
                        <option value="non-veg" @selected(request('type') === 'non-veg')>Non-Veg</option>
                    </select>
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <button class="px-4 py-2 text-sm font-bold text-white bg-gray-900 rounded-full">Apply</button>
                </form>
            </div>

            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @forelse($foods as $food)
                    <article class="overflow-hidden lb-card">
                        <img class="object-cover w-full transition duration-500 h-44 hover:scale-105" src="{{ $food->image ? asset('storage/'.$food->image) : 'https://images.unsplash.com/photo-1515003197210-e0cd71810b5f?auto=format&fit=crop&w=900&q=80' }}" alt="{{ $food->name }}" />
                        <div class="p-5 space-y-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-gray-900">{{ $food->name }}</h3>
                                <span class="lb-badge {{ $food->type === 'veg' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ strtoupper($food->type) }}</span>
                            </div>
                            <p class="text-sm text-gray-600">{{ $food->category->name ?? '-' }}</p>
                            <p class="text-xs font-semibold {{ $food->is_available && $food->stock > 0 ? 'text-green-700' : 'text-red-700' }}">
                                {{ $food->is_available && $food->stock > 0 ? 'In Stock: '.$food->stock : 'Out of Stock / Not Available' }}
                            </p>
                            <div class="p-3 text-sm rounded-xl bg-orange-50">
                                <p>Half: <strong>Rs {{ $food->price_half ? number_format((float) $food->price_half, 2) : 'N/A' }}</strong></p>
                                <p>Full: <strong>Rs {{ $food->price_full ? number_format((float) $food->price_full, 2) : 'N/A' }}</strong></p>
                            </div>

                            <form method="POST" action="{{ route('cart.add') }}" class="space-y-2">
                                @csrf
                                <input type="hidden" name="food_id" value="{{ $food->id }}">
                                <div class="grid grid-cols-2 gap-2">
                                    <select name="portion" class="px-3 py-2 text-sm border border-orange-200 rounded-lg" required>
                                        @if($food->price_half)
                                            <option value="half">Half</option>
                                        @endif
                                        @if($food->price_full)
                                            <option value="full">Full</option>
                                        @endif
                                    </select>
                                    <input type="number" name="quantity" value="1" min="1" class="px-3 py-2 text-sm border border-orange-200 rounded-lg" required>
                                </div>
                                <button class="w-full px-4 py-2 text-sm font-bold text-white rounded-lg {{ $food->is_available && $food->stock > 0 ? 'bg-orange-600 hover:bg-orange-700' : 'bg-gray-400 cursor-not-allowed' }}" @disabled(!($food->is_available && $food->stock > 0))>
                                    {{ $food->is_available && $food->stock > 0 ? 'Add to Cart' : 'Unavailable' }}
                                </button>
                            </form>
                        </div>
                    </article>
                @empty
                    <p class="p-8 text-center text-gray-600 bg-white col-span-full rounded-xl">No food items found for this filter.</p>
                @endforelse
            </div>

            <div class="mt-8">{{ $foods->links() }}</div>
        </section>
    </main>
</body>
</html>
