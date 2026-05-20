<x-app-layout>
    <x-slot name="header">
        <h2 class="display-font text-4xl text-gray-900">Checkout</h2>
    </x-slot>

    <style>[x-cloak] { display: none !important; }</style>

    <div class="py-10" x-data="{ showCoupons: false }">
        <div class="mx-auto grid max-w-6xl gap-6 px-4 sm:px-6 lg:grid-cols-3 lg:px-8">
            <div class="lb-card p-6 lg:col-span-2">
                <h3 class="text-xl font-bold">Delivery Details</h3>
                <form action="{{ route('orders.place') }}" method="POST" class="mt-5 space-y-4" id="checkout-form">
                    @csrf

                    <div class="mb-6">
                        <label class="mb-2 block text-sm font-semibold text-gray-700">Order Type</label>
                        <div class="grid grid-cols-3 gap-3">
                            <label class="relative flex cursor-pointer items-center justify-center rounded-xl border border-gray-200 bg-white p-3 text-sm font-medium hover:border-orange-200 hover:bg-orange-50 transition">
                                <input type="radio" name="order_type" value="delivery" class="peer sr-only" onchange="toggleOrderTypeFields()" @checked(old('order_type', 'delivery') === 'delivery')>
                                <div class="absolute inset-0 rounded-xl border-2 border-transparent peer-checked:border-orange-500 peer-checked:bg-orange-50"></div>
                                <span class="relative z-10 flex items-center gap-2 text-gray-700 peer-checked:text-orange-700">
                                    🛵 Delivery
                                </span>
                            </label>
                            <label class="relative flex cursor-pointer items-center justify-center rounded-xl border border-gray-200 bg-white p-3 text-sm font-medium hover:border-orange-200 hover:bg-orange-50 transition">
                                <input type="radio" name="order_type" value="takeaway" class="peer sr-only" onchange="toggleOrderTypeFields()" @checked(old('order_type') === 'takeaway')>
                                <div class="absolute inset-0 rounded-xl border-2 border-transparent peer-checked:border-orange-500 peer-checked:bg-orange-50"></div>
                                <span class="relative z-10 flex items-center gap-2 text-gray-700 peer-checked:text-orange-700">
                                    🛍️ Takeaway
                                </span>
                            </label>
                            <label class="relative flex cursor-pointer items-center justify-center rounded-xl border border-gray-200 bg-white p-3 text-sm font-medium hover:border-orange-200 hover:bg-orange-50 transition">
                                <input type="radio" name="order_type" value="dine_in" class="peer sr-only" onchange="toggleOrderTypeFields()" @checked(old('order_type') === 'dine_in')>
                                <div class="absolute inset-0 rounded-xl border-2 border-transparent peer-checked:border-orange-500 peer-checked:bg-orange-50"></div>
                                <span class="relative z-10 flex items-center gap-2 text-gray-700 peer-checked:text-orange-700">
                                    🍽️ Dine-in
                                </span>
                            </label>
                        </div>
                        @error('order_type')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="delivery-fields" class="space-y-4">
                        @if(auth()->user()->addresses->count() > 0)
                            <div>
                                <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-gray-500">Choose Saved Location</label>
                                <div class="grid gap-3 sm:grid-cols-2">
                                    @foreach(auth()->user()->addresses as $addr)
                                        <button type="button" 
                                                onclick="selectSavedAddress('{{ addslashes($addr->address_line) }}', this)"
                                                class="address-btn p-3.5 border rounded-xl text-left transition duration-150 relative group
                                                    {{ $addr->is_default ? 'border-orange-400 bg-orange-50/20' : 'border-orange-100 hover:border-orange-300 bg-orange-50/5 hover:bg-orange-50/15' }}">
                                            <div class="flex items-center gap-2">
                                                <span class="inline-flex px-1.5 py-0.5 rounded text-[9px] font-black uppercase tracking-wider
                                                    {{ $addr->type === 'home' ? 'bg-blue-100 text-blue-700' : ($addr->type === 'work' ? 'bg-purple-100 text-purple-700' : 'bg-pink-100 text-pink-700') }}">
                                                    {{ $addr->type === 'home' ? '🏠 Home' : ($addr->type === 'work' ? '💼 Work' : '📍 Other') }}
                                                </span>
                                                @if($addr->is_default)
                                                    <span class="inline-flex px-1.5 py-0.5 rounded text-[8px] font-bold uppercase bg-orange-600 text-white">Default</span>
                                                @endif
                                            </div>
                                            <p class="mt-2 text-xs font-bold text-gray-700 line-clamp-2 leading-relaxed">
                                                {{ $addr->address_line }}
                                            </p>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <label class="block text-sm font-semibold">Delivery Address <span class="text-red-500">*</span></label>
                                <a href="{{ route('profile.edit') }}" class="text-xs text-orange-600 hover:text-orange-700 font-bold underline transition">
                                    + Manage Saved Locations
                                </a>
                            </div>
                            <textarea name="address" rows="4" class="w-full rounded-xl border border-orange-200 px-4 py-3 text-sm focus:border-orange-400 focus:ring-1 focus:ring-orange-200 transition" placeholder="Enter full delivery address">{{ old('address', auth()->user()->addresses->where('is_default', true)->first()?->address_line) }}</textarea>
                            @error('address')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div id="takeaway-fields" class="space-y-4 hidden">
                        <div>
                            <label class="mb-1 block text-sm font-semibold">Pickup Time <span class="text-red-500">*</span></label>
                            <input type="datetime-local" name="pickup_time" value="{{ old('pickup_time') }}" class="w-full rounded-lg border border-orange-200 px-4 py-3 text-sm">
                            <p class="mt-1 text-xs text-gray-500">Please let us know when you'll arrive.</p>
                            @error('pickup_time')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div id="dine-in-fields" class="space-y-4 hidden">
                        <div>
                            <label class="mb-1 block text-sm font-semibold">Table Number <span class="text-red-500">*</span></label>
                            <input type="text" name="table_number" value="{{ old('table_number') }}" class="w-full rounded-lg border border-orange-200 px-4 py-3 text-sm" placeholder="e.g. Table 4">
                            @error('table_number')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold">Payment Method</label>
                        <select name="payment_method" id="payment-method" class="w-full rounded-lg border border-orange-200 px-4 py-3 text-sm">
                            <option value="cod" @selected(old('payment_method') === 'cod')>Cash on Delivery</option>
                            <option value="upi" @selected(old('payment_method') === 'upi')>UPI Payment</option>
                        </select>
                        @error('payment_method')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row">
                        <button type="submit" class="w-full rounded-lg bg-orange-600 px-4 py-3 text-sm font-bold text-white hover:bg-orange-700">Place Order</button>
                        <button type="submit" onclick="document.getElementById('payment-method').value='upi'" class="w-full rounded-lg bg-gray-900 px-4 py-3 text-sm font-bold text-white hover:bg-black">Pay Now</button>
                    </div>
                </form>
            </div>

            <aside class="lb-card p-6 space-y-4">
                <h3 class="text-lg font-bold text-gray-900">Order Summary</h3>
                <div class="space-y-3 text-sm border-b border-orange-100 pb-4">
                    @foreach($cart as $item)
                        <div class="flex items-center justify-between text-gray-600">
                            <span>{{ $item['name'] }} ({{ strtoupper($item['portion']) }}) x {{ $item['quantity'] }}</span>
                            <span>Rs {{ number_format((float) ($item['unit_price'] * $item['quantity']), 2) }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="space-y-2 border-b border-orange-100 pb-4 text-sm">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal</span>
                        <span>Rs {{ number_format((float) $subtotal, 2) }}</span>
                    </div>
                    @if($couponCode && $discount > 0)
                        <div class="flex justify-between text-green-700 font-semibold bg-green-50 px-2.5 py-1.5 rounded-lg">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.5 6v.75m0 3v.75m0 3v.75m3-12h-15c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h15c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V3.375c0-.621-.504-1.125-1.125-1.125zm-12 5.25h6m-6 3h6m-6 3h6" />
                                </svg>
                                Coupon ({{ $couponCode }})
                            </span>
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
                </div>

                {{-- Apply Coupon Form in Checkout --}}
                @if(!$couponCode)
                    <form method="POST" action="{{ route('cart.coupon.apply') }}" class="space-y-2">
                        @csrf
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500" for="checkout_coupon_code_input">Have a Promo Code?</label>
                        <div class="flex gap-2">
                            <input type="text" id="checkout_coupon_code_input" name="code" placeholder="Enter coupon" required
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

                <div>
                    <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Total Payable</p>
                    <p class="text-2xl font-extrabold text-orange-700 mt-1">Rs {{ number_format((float) $total, 2) }}</p>
                </div>
            </aside>
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

    @php($razorpayPayment = session('razorpay_payment'))
    @if($razorpayPayment)
        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
        <script type="application/json" id="razorpay-payment-data">{!! json_encode($razorpayPayment, JSON_UNESCAPED_SLASHES) !!}</script>
        <script>
            const paymentSuccessUrl = "{{ route('payment.success') }}";

            window.addEventListener('DOMContentLoaded', function () {
                const paymentData = JSON.parse(document.getElementById('razorpay-payment-data').textContent);

                if (!paymentData || !paymentData.pending_order_id) {
                    return;
                }

                paymentData.handler = function (response) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = paymentSuccessUrl;

                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';
                    form.appendChild(csrf);

                    const orderId = document.createElement('input');
                    orderId.type = 'hidden';
                    orderId.name = 'order_id';
                    orderId.value = paymentData.pending_order_id;
                    form.appendChild(orderId);

                    const paymentId = document.createElement('input');
                    paymentId.type = 'hidden';
                    paymentId.name = 'payment_id';
                    paymentId.value = response.razorpay_payment_id;
                    form.appendChild(paymentId);

                    const razorpayOrderId = document.createElement('input');
                    razorpayOrderId.type = 'hidden';
                    razorpayOrderId.name = 'razorpay_order_id';
                    razorpayOrderId.value = response.razorpay_order_id || '';
                    form.appendChild(razorpayOrderId);

                    document.body.appendChild(form);
                    form.submit();
                };

                paymentData.modal = {
                    ondismiss: function () {
                        // Payment stays pending until the user completes checkout.
                    }
                };

                const razorpay = new Razorpay(paymentData);
                razorpay.open();
            });
        </script>
    @endif

    <script>
        function toggleOrderTypeFields() {
            const orderType = document.querySelector('input[name="order_type"]:checked').value;
            
            document.getElementById('delivery-fields').classList.toggle('hidden', orderType !== 'delivery');
            document.getElementById('takeaway-fields').classList.toggle('hidden', orderType !== 'takeaway');
            document.getElementById('dine-in-fields').classList.toggle('hidden', orderType !== 'dine_in');
        }

        function selectSavedAddress(addressText, buttonEl) {
            // Set textarea value
            document.querySelector('textarea[name="address"]').value = addressText;
            
            // Clear active styles from all buttons
            document.querySelectorAll('.address-btn').forEach(btn => {
                btn.classList.remove('border-orange-400', 'bg-orange-50/20');
                btn.classList.add('border-orange-100', 'bg-orange-50/5', 'hover:border-orange-300', 'hover:bg-orange-50/15');
            });
            
            // Add active styles to clicked button
            buttonEl.classList.remove('border-orange-100', 'bg-orange-50/5', 'hover:border-orange-300', 'hover:bg-orange-50/15');
            buttonEl.classList.add('border-orange-400', 'bg-orange-50/20');
        }

        // Run once on load to set initial state
        document.addEventListener('DOMContentLoaded', toggleOrderTypeFields);
    </script>
</x-app-layout>
