@props([
    'food',
    'showOrderControls' => true,
])

<article class="group relative overflow-hidden rounded-2xl border border-orange-100/80 bg-white shadow-sm transition-all duration-400 hover:-translate-y-2 hover:shadow-xl hover:shadow-orange-100/50">
    {{-- Image wrapper --}}
    <div class="relative overflow-hidden">
        <img
            class="object-cover w-full h-48 transition-transform duration-700 group-hover:scale-110"
            src="{{ $food->image ? asset('storage/'.$food->image) : 'https://images.unsplash.com/photo-1515003197210-e0cd71810b5f?auto=format&fit=crop&w=900&q=80' }}"
            alt="{{ $food->name }}"
            loading="lazy"
        />
        {{-- Gradient overlay --}}
        <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-black/10 to-transparent opacity-60 group-hover:opacity-40 transition-opacity duration-500"></div>

        {{-- Veg/Non-veg badge (top-left) --}}
        <span class="absolute top-3 left-3 inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider backdrop-blur-md
            {{ $food->type === 'veg' ? 'bg-green-500/90 text-white' : 'bg-red-500/90 text-white' }}">
            <span class="w-1.5 h-1.5 rounded-full {{ $food->type === 'veg' ? 'bg-green-200' : 'bg-red-200' }}"></span>
            {{ $food->type === 'veg' ? 'Veg' : 'Non-Veg' }}
        </span>

        {{-- Availability badge (top-right) --}}
        @if(!\App\Models\Setting::isOpen())
            <span class="absolute top-3 right-3 rounded-full bg-red-600/95 backdrop-blur-md px-2.5 py-1 text-[10px] font-bold text-white">
                🔒 Closed
            </span>
        @elseif($food->is_available)
            <span class="absolute top-3 right-3 rounded-full bg-green-500/90 backdrop-blur-md px-2.5 py-1 text-[10px] font-bold text-white">
                Available
            </span>
        @else
            <span class="absolute top-3 right-3 rounded-full bg-red-500/90 backdrop-blur-md px-2.5 py-1 text-[10px] font-bold text-white">
                Unavailable
            </span>
        @endif

        {{-- Price overlay (bottom of image) --}}
        <div class="absolute bottom-0 left-0 right-0 flex items-end justify-between px-4 pb-3">
            <div>
                <p class="text-xs text-gray-300 font-medium">from</p>
                <p class="text-xl font-bold text-white display-font">₹{{ number_format((float) ($food->price_half ?: $food->price_full), 0) }}</p>
            </div>
            @if($food->price_half && $food->price_full)
                <div class="text-right">
                    <p class="text-[10px] text-gray-300 uppercase tracking-wide">Full</p>
                    <p class="text-sm font-bold text-orange-300">₹{{ number_format((float) $food->price_full, 0) }}</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Content --}}
    <div class="p-4 space-y-3">
        {{-- Title & Category --}}
        <div>
            <h3 class="text-base font-bold text-gray-900 leading-snug group-hover:text-orange-700 transition-colors">{{ $food->name }}</h3>
            <p class="mt-0.5 text-xs text-gray-500 font-medium">{{ $food->category->name ?? 'Special' }}</p>
        </div>

        {{-- Order controls --}}
        @if($showOrderControls)
            <form method="POST" action="{{ route('cart.add') }}" class="space-y-2.5">
                @csrf
                <input type="hidden" name="food_id" value="{{ $food->id }}">
                <div class="flex gap-2">
                    <select name="portion" class="flex-1 px-3 py-2 text-xs font-semibold text-gray-700 bg-gray-50 border border-gray-200 rounded-xl focus:border-orange-400 focus:ring-1 focus:ring-orange-200 transition" required>
                        @if($food->price_half)
                            <option value="half">Half — ₹{{ number_format((float) $food->price_half, 0) }}</option>
                        @endif
                        @if($food->price_full)
                            <option value="full">Full — ₹{{ number_format((float) $food->price_full, 0) }}</option>
                        @endif
                    </select>
                    <input type="number" name="quantity" value="1" min="1" class="w-16 px-3 py-2 text-xs font-semibold text-center text-gray-700 bg-gray-50 border border-gray-200 rounded-xl focus:border-orange-400 focus:ring-1 focus:ring-orange-200 transition" required>
                </div>
                @php($isOpen = \App\Models\Setting::isOpen())
                @php($canOrder = $isOpen && $food->is_available)
                <button class="w-full flex items-center justify-center gap-2 px-4 py-2.5 text-xs font-bold uppercase tracking-wider rounded-xl transition-all duration-300
                    {{ $canOrder
                        ? 'text-white bg-orange-600 hover:bg-orange-700 hover:shadow-lg hover:shadow-orange-200 active:scale-[0.97]'
                        : 'text-gray-400 bg-gray-100 cursor-not-allowed' }}"
                    @disabled(!$canOrder)>
                    @if(!$isOpen)
                        Closed
                    @elseif($food->is_available)
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Add to Cart
                    @else
                        Unavailable
                    @endif
                </button>
            </form>
        @endif
    </div>
</article>
