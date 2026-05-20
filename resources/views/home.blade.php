<x-app-layout>
    <x-slot name="pageTitle">Fresh Food Delivery</x-slot>
    <x-slot name="pageDescription">Love Bite — Fresh Indian food delivery. Order tandoor, biryani, curries & more. Fast delivery, real-time tracking.</x-slot>

    {{-- ========== HERO ========== --}}
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-orange-50 via-amber-50/40 to-white"></div>
        <div class="absolute w-[500px] h-[500px] rounded-full -right-40 -top-40 bg-orange-200/30 blur-3xl animate-blob"></div>
        <div class="absolute w-80 h-80 rounded-full -left-20 bottom-0 bg-amber-100/40 blur-3xl animate-blob" style="animation-delay:4s"></div>

        <div class="relative z-10 grid items-center gap-8 px-4 py-16 mx-auto max-w-7xl sm:px-6 lg:px-8 md:grid-cols-2 md:py-20 lg:py-24 lg:gap-16">
            {{-- Left: Content --}}
            <div class="fade-up">
                <div class="flex items-center gap-2 mb-6">
                    @if(\App\Models\Setting::isOpen())
                        <span class="inline-flex items-center gap-1.5 px-4 py-1.5 text-xs font-bold tracking-[0.2em] text-orange-700 uppercase bg-orange-100 rounded-full">
                            <span class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></span>
                            Open Now
                        </span>
                        <span class="px-3 py-1.5 text-xs font-bold text-green-700 bg-green-50 rounded-full">Free Delivery</span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-4 py-1.5 text-xs font-bold tracking-[0.2em] text-red-700 uppercase bg-red-100 rounded-full">
                            <span class="w-2 h-2 rounded-full bg-red-500"></span>
                            Closed Now
                        </span>
                    @endif
                </div>

                <h1 class="mb-5 text-5xl leading-[1.05] text-gray-900 sm:text-6xl lg:text-7xl display-font">
                    Craving<br>Something<br>
                    <span class="relative inline-block text-transparent bg-clip-text bg-gradient-to-r from-orange-600 to-amber-500">
                        Delicious?
                        <svg class="absolute -bottom-2 left-0 w-full" viewBox="0 0 200 12" fill="none"><path d="M2 8c40-6 80-6 120-2s60 4 76-2" stroke="#f97316" stroke-width="3" stroke-linecap="round" opacity=".5"/></svg>
                    </span>
                </h1>

                <p class="max-w-md text-base leading-relaxed text-gray-600 sm:text-lg">
                    Handcrafted Indian cuisine delivered hot to your door. From smoky tandoor to aromatic biryani — every dish is a celebration.
                </p>

                <div class="flex flex-wrap items-center gap-4 mt-8">
                    <a href="{{ route('menu.index') }}" class="inline-flex items-center gap-2 px-8 py-3.5 text-sm font-bold tracking-wider text-white uppercase rounded-full btn-shimmer hover:shadow-xl hover:shadow-orange-500/25 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
                        Explore Menu
                    </a>
                    <a href="{{ route('cart.index') }}" class="inline-flex items-center gap-2 px-8 py-3.5 text-sm font-bold tracking-wider text-gray-700 uppercase transition border-2 border-gray-200 rounded-full hover:border-orange-300 hover:bg-orange-50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                        My Cart
                    </a>
                </div>

                <div class="flex flex-wrap items-center gap-5 mt-10 text-sm text-gray-500">
                    <span class="flex items-center gap-1.5"><span class="text-orange-500">⚡</span> 15 min avg</span>
                    <span class="flex items-center gap-1.5"><span class="text-yellow-500">⭐</span> 4.8 rating</span>
                    <span class="flex items-center gap-1.5"><span class="text-green-500">✓</span> 1000+ served</span>
                </div>
            </div>

            {{-- Right: Hero Image + Floating Cards --}}
            <div class="relative fade-up lb-delay-200">
                <div class="relative">
                    <div class="relative aspect-square max-h-[420px] mx-auto">
                        <div class="absolute -inset-4 rounded-[2rem] border-2 border-dashed border-orange-200/60 animate-[spin_40s_linear_infinite]"></div>
                        <div class="relative w-full h-full overflow-hidden shadow-2xl rounded-[2rem]">
                            <img src="{{ asset('hero-banner.png') }}" alt="Delicious Indian food spread" class="object-cover w-full h-full transition-transform duration-700 hover:scale-105" loading="eager">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent"></div>
                        </div>
                    </div>

                    @if($todaySpecials->count() > 0)
                    <div class="absolute z-20 hidden p-4 -left-8 top-8 glass rounded-2xl shadow-xl sm:block animate-float" style="animation-delay:1s">
                        <p class="mb-2 text-xs font-bold tracking-wider text-orange-600 uppercase">🔥 Today's Special</p>
                        <p class="text-sm font-bold text-gray-900">{{ $todaySpecials->first()->name }}</p>
                        <p class="text-lg font-bold text-orange-600">₹{{ number_format((float) $todaySpecials->first()->price_full, 0) }}</p>
                    </div>
                    @endif

                    <div class="absolute z-20 hidden p-4 -right-6 bottom-16 glass rounded-2xl shadow-xl sm:block animate-float" style="animation-delay:2.5s">
                        <div class="flex items-center gap-2">
                            <div class="flex -space-x-1 text-yellow-400 text-xs">⭐⭐⭐⭐⭐</div>
                        </div>
                        <p class="mt-1 text-sm font-bold text-gray-900">Loved by 1000+</p>
                        <p class="text-xs text-gray-500">customers</p>
                    </div>

                    <div class="absolute z-20 hidden p-3 -left-4 bottom-6 sm:block animate-float" style="animation-delay:0.5s">
                        <div class="flex items-center gap-2 px-4 py-2 bg-green-500 shadow-lg rounded-xl">
                            <span class="text-lg">🛵</span>
                            <div>
                                <p class="text-xs font-bold text-white">Fast Delivery</p>
                                <p class="text-[10px] text-green-100">15 min average</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Scrolling marquee strip --}}
        <div class="relative py-3 overflow-hidden border-t border-b bg-gray-900/95 border-orange-900/20">
            <div class="flex gap-8 whitespace-nowrap animate-marquee">
                @for($m = 0; $m < 3; $m++)
                <span class="text-sm font-bold tracking-wider text-orange-400 uppercase">🍗 Tandoori Chicken</span>
                <span class="text-sm text-gray-600">·</span>
                <span class="text-sm font-bold tracking-wider text-amber-400 uppercase">🍛 Butter Chicken</span>
                <span class="text-sm text-gray-600">·</span>
                <span class="text-sm font-bold tracking-wider text-orange-400 uppercase">🥘 Biryani</span>
                <span class="text-sm text-gray-600">·</span>
                <span class="text-sm font-bold tracking-wider text-amber-400 uppercase">🫓 Naan & Roti</span>
                <span class="text-sm text-gray-600">·</span>
                <span class="text-sm font-bold tracking-wider text-orange-400 uppercase">🥗 Fresh Salads</span>
                <span class="text-sm text-gray-600">·</span>
                <span class="text-sm font-bold tracking-wider text-amber-400 uppercase">🍲 Dal Tadka</span>
                <span class="text-sm text-gray-600">·</span>
                @endfor
            </div>
        </div>
    </section>

    {{-- ========== CATEGORIES ========== --}}
    <section class="px-4 pt-16 pb-8 mx-auto max-w-7xl sm:px-6 lg:px-8 reveal">
        <h2 class="text-4xl text-gray-900 display-font section-label">Browse by Category</h2>
        <div class="mt-6 scroll-strip">
            @foreach($categories as $category)
            <a href="{{ route('menu.index', ['category' => $category->name]) }}" class="flex items-center gap-2 px-6 py-3 text-sm font-bold text-gray-700 transition bg-white border border-orange-100 whitespace-nowrap rounded-full shadow-sm hover:border-orange-300 hover:shadow-md hover:bg-gradient-to-r hover:from-orange-50 hover:to-amber-50 glow-hover">
                <span class="text-lg">🍴</span> {{ $category->name }}
            </a>
            @endforeach
        </div>
    </section>

    {{-- ========== BEST SELLERS ========== --}}
    <section id="best-sellers" class="px-4 py-12 mx-auto max-w-7xl sm:px-6 lg:px-8 reveal">
        <div class="flex items-end justify-between mb-8">
            <div>
                <span class="text-xs font-bold tracking-[0.2em] text-orange-500 uppercase">Most Loved</span>
                <h2 class="mt-1 text-4xl text-gray-900 display-font section-label">Best Sellers</h2>
            </div>
            <a href="{{ route('menu.index') }}" class="hidden text-sm font-bold text-orange-600 transition sm:inline-flex hover:text-orange-700">View All →</a>
        </div>
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($popularItems->take(3) as $food)
            <x-food-card :food="$food" :show-order-controls="false" />
            @empty
            <p class="text-gray-600">No best sellers available.</p>
            @endforelse
        </div>
    </section>

    {{-- ========== NEW LAUNCHES ========== --}}
    <section id="new-launches" class="px-4 py-12 mx-auto max-w-7xl sm:px-6 lg:px-8 reveal">
        <div class="flex items-end justify-between mb-8">
            <div>
                <span class="text-xs font-bold tracking-[0.2em] text-green-600 uppercase">Just Added</span>
                <h2 class="mt-1 text-4xl text-gray-900 display-font section-label">New Launches</h2>
            </div>
        </div>
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($latestItems->take(3) as $food)
            <x-food-card :food="$food" :show-order-controls="false" />
            @empty
            <p class="text-gray-600">No new launches available.</p>
            @endforelse
        </div>
    </section>

    {{-- ========== CHEF RECOMMENDATIONS ========== --}}
    <section id="recommendations" class="relative px-4 py-16 overflow-hidden reveal">
        <div class="absolute inset-0 bg-gradient-to-br from-orange-50 via-amber-50/50 to-white"></div>
        <div class="absolute w-96 h-96 rounded-full -right-32 top-0 bg-orange-200/30 blur-3xl animate-blob" style="animation-delay:2s"></div>
        <div class="relative mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex items-end justify-between mb-8">
                <div>
                    <span class="text-xs font-bold tracking-[0.2em] text-amber-600 uppercase">Handpicked for You</span>
                    <h2 class="mt-1 text-4xl text-gray-900 display-font section-label">Chef Recommendations</h2>
                </div>
            </div>
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse($recommendedItems->take(3) as $food)
                <x-food-card :food="$food" :show-order-controls="false" />
                @empty
                <p class="text-gray-600">No recommendations available.</p>
                @endforelse
            </div>
            <div class="mt-10 text-center">
                <a href="{{ route('menu.index') }}" class="inline-flex items-center gap-2 px-8 py-3.5 text-sm font-bold tracking-wider text-white uppercase rounded-full btn-shimmer hover:shadow-xl hover:shadow-orange-500/20 transition-shadow">
                    Explore Full Menu
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </div>
    </section>

    {{-- ========== CTA BANNER ========== --}}
    <section class="px-4 py-16 mx-auto max-w-7xl sm:px-6 lg:px-8 reveal">
        <div class="relative p-10 overflow-hidden text-center shadow-2xl md:p-16 rounded-3xl bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
            <div class="absolute w-64 h-64 rounded-full -left-20 -top-20 bg-orange-500/20 blur-3xl animate-blob"></div>
            <div class="absolute w-48 h-48 rounded-full -right-12 -bottom-12 bg-amber-400/15 blur-3xl animate-blob" style="animation-delay:3s"></div>
            <div class="relative z-10">
                <span class="inline-block px-4 py-1 mb-4 text-xs font-bold tracking-widest text-orange-400 uppercase border rounded-full border-orange-500/30">Ready to Order?</span>
                <h2 class="text-4xl text-white sm:text-5xl display-font">Order your favorite food now</h2>
                <p class="max-w-lg mx-auto mt-4 text-gray-400">Fresh ingredients. Fast delivery. Premium experience. Every bite tells our story.</p>
                <div class="mt-8">
                    @auth
                    <a href="{{ route('menu.index') }}" class="inline-flex items-center gap-2 px-8 py-4 text-sm font-bold tracking-wider text-white uppercase rounded-full btn-shimmer hover:shadow-xl hover:shadow-orange-500/30 transition-shadow">
                        Start Ordering
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    @else
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-8 py-4 text-sm font-bold tracking-wider text-white uppercase rounded-full btn-shimmer hover:shadow-xl hover:shadow-orange-500/30 transition-shadow">
                        Create Account
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    @endauth
                </div>
            </div>
        </div>
    </section>
</x-app-layout>