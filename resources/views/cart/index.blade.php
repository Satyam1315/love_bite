<x-app-layout>
    <x-slot name="header">
        <h2 class="display-font text-4xl text-gray-900">Your Cart</h2>
    </x-slot>

    <style>[x-cloak] { display: none !important; }</style>

    <div class="py-10" x-data="{ showCoupons: false }">
        <div class="mx-auto max-w-6xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-green-700">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            @if(empty($cart))
                <div class="lb-card p-8 text-center">
                    <h3 class="text-xl font-bold">Your cart is empty.</h3>
                    <a href="{{ route('menu.index') }}" class="mt-4 inline-block rounded-lg bg-orange-600 px-5 py-2 text-sm font-bold text-white">Browse Menu</a>
                </div>
            @else
                <div class="grid gap-6 lg:grid-cols-3">
                    <div class="space-y-4 lg:col-span-2">
                        @foreach($cart as $item)
                            <article class="lb-card p-4">
                                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900">{{ $item['name'] }}</h3>
                                        <p class="text-sm text-gray-600">{{ strtoupper($item['type']) }} | {{ strtoupper($item['portion']) }} portion</p>
                                        <p class="text-sm text-orange-700">Unit Price: Rs {{ number_format((float) $item['unit_price'], 2) }}</p>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <form action="{{ route('cart.update', $item['key']) }}" method="POST" class="flex items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <input type="number" min="1" name="quantity" value="{{ $item['quantity'] }}" class="w-20 rounded-lg border border-orange-200 px-3 py-2 text-sm" />
                                            <button class="rounded-lg border border-gray-300 px-3 py-2 text-sm font-semibold hover:bg-gray-100">Update</button>
                                        </form>

                                        <form action="{{ route('cart.remove', $item['key']) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="rounded-lg border border-red-200 px-3 py-2 text-sm font-semibold text-red-700 hover:bg-red-50">Remove</button>
                                        </form>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <aside class="lb-card p-5 space-y-4">
                        <h3 class="text-lg font-bold text-gray-900">Order Summary</h3>
                        
                        <div class="space-y-2 border-b border-orange-100 pb-4">
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Subtotal</span>
                                <span>Rs {{ number_format((float) $subtotal, 2) }}</span>
                            </div>
                            
                            @if($couponCode && $discount > 0)
                                <div class="flex justify-between text-sm text-green-700 font-semibold bg-green-50 px-2.5 py-1.5 rounded-lg">
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.5 6v.75m0 3v.75m0 3v.75m3-12h-15c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h15c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V3.375c0-.621-.504-1.125-1.125-1.125zm-12 5.25h6m-6 3h6m-6 3h6" />
                                        </svg>
                                        <span>Coupon ({{ $couponCode }})</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span>- Rs {{ number_format((float) $discount, 2) }}</span>
                                        <form method="POST" action="{{ route('cart.coupon.remove') }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs bg-red-100 hover:bg-red-200 text-red-700 font-bold px-2 py-0.5 rounded transition">
                                                Remove
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                            
                            <div class="flex justify-between text-lg font-extrabold text-gray-900 pt-2">
                                <span>Total</span>
                                <span class="text-orange-700">Rs {{ number_format((float) $total, 2) }}</span>
                            </div>
                        </div>

                        {{-- Apply Coupon Form --}}
                        @if(!$couponCode)
                            <form method="POST" action="{{ route('cart.coupon.apply') }}" class="space-y-2">
                                @csrf
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500" for="coupon_code_input">Have a Promo Code?</label>
                                <div class="flex gap-2">
                                    <input type="text" id="coupon_code_input" name="code" placeholder="Enter coupon" required
                                           class="w-full px-3 py-2 text-xs border border-orange-200 rounded-lg focus:border-orange-400 focus:ring-1 focus:ring-orange-200 transition font-bold uppercase placeholder-gray-400">
                                    <button type="submit" class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-xs font-bold text-white rounded-lg transition active:scale-[0.98]">
                                        Apply
                                    </button>
                                </div>
                            </form>
                            <button type="button" @click="showCoupons = true" class="text-xs text-orange-600 hover:text-orange-700 font-extrabold underline transition flex items-center gap-1">
                                🎟️ View Available Coupons
                            </button>
                        @else
                            <button type="button" @click="showCoupons = true" class="text-xs text-orange-600 hover:text-orange-700 font-extrabold underline transition flex items-center gap-1">
                                🎟️ View Applied / Available Coupons
                            </button>
                        @endif

                        <p class="text-xs text-gray-500">Payment method: Cash on Delivery / UPI</p>
                        @auth
                            <a href="{{ route('orders.checkout') }}" class="mt-2 inline-block w-full rounded-lg bg-orange-600 px-4 py-3 text-center text-sm font-bold text-white hover:bg-orange-700">Proceed to Checkout</a>
                        @else
                            <a href="{{ route('login') }}" class="mt-2 inline-block w-full rounded-lg bg-gray-900 px-4 py-3 text-center text-sm font-bold text-white">Login to Checkout</a>
                        @endauth
                    </aside>
                </div>
            @endif
        </div>

        {{-- Available Coupons Modal --}}
        <div x-show="showCoupons" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" x-cloak>
            <div @click.away="showCoupons = false" class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl relative border border-orange-100 animate-fade-in">
                <button @click="showCoupons = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
                <h3 class="text-xl font-extrabold text-gray-900 mb-4 flex items-center gap-2">
                    🎟️ Available Coupons
                </h3>
                <div class="space-y-3 max-h-96 overflow-y-auto pr-1">
                    @forelse($activeCoupons as $coupon)
                        <div class="border border-dashed border-orange-300 rounded-xl p-4 bg-orange-50/30 flex justify-between items-center">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 bg-orange-600 text-white font-black rounded text-xs tracking-wider uppercase">{{ $coupon->code }}</span>
                                    <span class="text-xs font-extrabold text-orange-700">
                                        {{ $coupon->type === 'percent' ? $coupon->value . '% OFF' : '₹' . number_format($coupon->value, 0) . ' OFF' }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-600 mt-2 font-medium">
                                    Min. Order: ₹{{ number_format($coupon->min_order_amount, 0) }}
                                </p>
                                @if($coupon->expires_at)
                                    <p class="text-[10px] text-gray-400 mt-0.5">Expires: {{ $coupon->expires_at->format('M d, Y') }}</p>
                                @endif
                            </div>
                            
                            @if($couponCode === $coupon->code)
                                <form method="POST" action="{{ route('cart.coupon.remove') }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" @click="showCoupons = false"
                                            class="px-3 py-1 bg-red-100 hover:bg-red-600 hover:text-white border border-red-200 text-xs font-bold text-red-700 rounded-lg transition">
                                        Remove
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('cart.coupon.apply') }}">
                                    @csrf
                                    <input type="hidden" name="code" value="{{ $coupon->code }}">
                                    <button type="submit" @click="showCoupons = false"
                                            class="px-3 py-1 bg-white hover:bg-orange-600 hover:text-white border border-orange-200 hover:border-orange-600 text-xs font-bold text-orange-700 rounded-lg transition">
                                        Apply
                                    </button>
                                </form>
                            @endif
                        </div>
                    @empty
                        <p class="text-xs text-gray-500 text-center py-4 font-medium">No coupons available right now.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
