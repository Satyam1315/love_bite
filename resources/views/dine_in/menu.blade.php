<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Love Bite - Dine In Menu</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="text-gray-800 bg-gray-50">
    <div id="toast" class="lb-toast"></div>

    {{-- Simple Header for Dine In --}}
    <header class="sticky top-0 z-40 border-b border-orange-100 bg-white/95 backdrop-blur-xl">
        <div class="flex items-center justify-between h-16 px-4 mx-auto max-w-[1400px]">
            <div class="flex items-center gap-2">
                <span class="text-2xl text-transparent bg-clip-text bg-gradient-to-r from-orange-600 to-amber-500 display-font">Love Bite</span>
                <span class="px-2 py-0.5 text-[10px] font-bold text-orange-800 bg-orange-100 rounded-full uppercase tracking-wider">Dine-In</span>
            </div>
            <button id="cart-toggle" class="relative lg:hidden px-3 py-2 text-sm font-bold text-white bg-orange-600 rounded-full shadow-lg hover:bg-orange-700 transition">
                <svg class="inline w-4 h-4 mr-1 -mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                View Order
                <span id="cart-badge-mobile" class="absolute -top-1 -right-1 flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-gray-900 rounded-full {{ count($cart) > 0 ? '' : 'hidden' }}">{{ count($cart) }}</span>
            </button>
        </div>
    </header>

    <main class="px-4 py-6 mx-auto max-w-[1400px]">
        <div class="flex gap-6 items-start">
            {{-- LEFT: Menu Items --}}
            <section class="flex-1 min-w-0">
                <div class="mb-6">
                    <h1 class="text-3xl text-gray-900 display-font mb-2">Order from your table</h1>
                    <p class="text-sm text-gray-500">Select your items below. We'll give you a token number to complete your order.</p>
                </div>

                {{-- Categories --}}
                <div class="scroll-strip mb-6">
                    <a href="{{ route('dine-in.menu') }}" class="px-4 py-2 text-sm font-bold rounded-full transition {{ !request('category') ? 'bg-orange-600 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-orange-50' }}">All</a>
                    @foreach($categories as $category)
                        <a href="{{ route('dine-in.menu', ['category' => $category->name]) }}" class="px-4 py-2 text-sm font-bold rounded-full transition {{ request('category') === $category->name ? 'bg-orange-600 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-orange-50' }}">{{ $category->name }}</a>
                    @endforeach
                </div>

                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                    @forelse($foods as $food)
                        <x-food-card :food="$food" :show-order-controls="true" />
                    @empty
                        <p class="p-8 text-center text-gray-500 bg-white col-span-full rounded-2xl border border-gray-100">No food items found.</p>
                    @endforelse
                </div>

                <div class="mt-8">{{ $foods->links() }}</div>
            </section>

            {{-- RIGHT: Side Cart --}}
            <aside id="side-cart" class="side-cart closed fixed lg:sticky top-[68px] right-0 z-30 w-80 lg:w-[320px] h-[calc(100vh-68px)] lg:h-auto lg:max-h-[calc(100vh-100px)] flex-shrink-0 bg-white lg:bg-white/80 lg:backdrop-blur-md border-l lg:border border-gray-200 lg:rounded-2xl shadow-2xl lg:shadow-sm overflow-hidden flex flex-col">
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 bg-gray-50/80">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 display-font">Your Order</h2>
                        <p id="cart-count-label" class="text-xs text-gray-500 font-medium">{{ count($cart) }} items</p>
                    </div>
                    <button id="cart-close" class="lg:hidden p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div id="cart-items" class="flex-1 overflow-y-auto px-4 py-3 space-y-3"></div>

                <div id="cart-footer" class="border-t border-gray-100 bg-gray-50/80 px-5 py-4 space-y-3 hidden">
                    <div class="flex items-center justify-between">
                        <span class="text-base font-bold text-gray-900">Total Payable</span>
                        <span id="cart-total" class="text-xl font-bold text-orange-600 display-font">₹0</span>
                    </div>
                    
                    <button onclick="placeDineInOrder()" id="place-order-btn" class="flex items-center justify-center gap-2 w-full px-4 py-3 text-sm font-bold text-white bg-orange-600 rounded-xl hover:bg-orange-700 transition active:scale-[0.97]">
                        Generate Token & Pay
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
            </aside>
        </div>
    </main>

    <div id="cart-overlay" class="fixed inset-0 z-20 bg-black/40 backdrop-blur-sm hidden lg:hidden"></div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const CSRF = document.querySelector('meta[name="csrf-token"]').content;
        const cartItems = document.getElementById('cart-items');
        const cartFooter = document.getElementById('cart-footer');
        const cartCountLabel = document.getElementById('cart-count-label');
        const cartTotal = document.getElementById('cart-total');
        const cartBadge = document.getElementById('cart-badge-mobile');
        const sideCart = document.getElementById('side-cart');
        const cartToggle = document.getElementById('cart-toggle');
        const cartClose = document.getElementById('cart-close');
        const cartOverlay = document.getElementById('cart-overlay');
        const toastEl = document.getElementById('toast');
        const placeBtn = document.getElementById('place-order-btn');

        function openCart() { sideCart.classList.remove('closed'); cartOverlay.classList.remove('hidden'); document.body.style.overflow = 'hidden'; }
        function closeCart() { sideCart.classList.add('closed'); cartOverlay.classList.add('hidden'); document.body.style.overflow = ''; }
        if (cartToggle) cartToggle.addEventListener('click', openCart);
        if (cartClose) cartClose.addEventListener('click', closeCart);
        if (cartOverlay) cartOverlay.addEventListener('click', closeCart);

        let toastTimer;
        function showToast(msg, isError = false) {
            toastEl.textContent = (isError ? '✗ ' : '✓ ') + msg;
            toastEl.className = 'lb-toast' + (isError ? ' error' : '');
            requestAnimationFrame(() => toastEl.classList.add('show'));
            clearTimeout(toastTimer);
            toastTimer = setTimeout(() => toastEl.classList.remove('show'), 2200);
        }

        function fmt(n) { return '₹' + Math.round(n).toLocaleString('en-IN'); }

        function renderCart(data) {
            const { cart, total, count } = data;
            cartCountLabel.textContent = count + ' ' + (count === 1 ? 'item' : 'items');
            if (cartBadge) { cartBadge.textContent = count; cartBadge.classList.toggle('hidden', count === 0); }

            if (count === 0) {
                cartItems.innerHTML = `
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <div class="w-16 h-16 rounded-full bg-orange-50 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-orange-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                        </div>
                        <p class="text-sm font-bold text-gray-500">Your order is empty</p>
                    </div>`;
                cartFooter.classList.add('hidden');
                return;
            }

            cartFooter.classList.remove('hidden');
            cartTotal.textContent = fmt(total);

            cartItems.innerHTML = cart.map(item => {
                const lineTotal = item.unit_price * item.quantity;
                return `
                <div class="flex gap-3 p-3 bg-gray-50/80 rounded-xl border border-gray-100 cart-item-enter">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-gray-900 truncate">${item.name}</p>
                                <p class="text-[11px] text-orange-600 font-semibold capitalize">${item.portion}</p>
                            </div>
                            <p class="text-sm font-bold text-gray-900 whitespace-nowrap">${fmt(lineTotal)}</p>
                        </div>
                        <div class="flex items-center justify-between mt-2">
                            <button onclick="cartUpdate('${item.key}', ${Math.max(1, item.quantity - 1)})" class="w-7 h-7 flex items-center justify-center text-sm font-bold text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 ${item.quantity <= 1 ? 'opacity-40 pointer-events-none' : ''}">−</button>
                            <span class="w-8 text-center text-sm font-bold text-gray-900">${item.quantity}</span>
                            <button onclick="cartUpdate('${item.key}', ${item.quantity + 1})" class="w-7 h-7 flex items-center justify-center text-sm font-bold text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-100">+</button>
                            <button onclick="cartRemove('${item.key}')" class="ml-2 text-[11px] font-bold text-red-500 hover:text-red-700">Remove</button>
                        </div>
                    </div>
                </div>`;
            }).join('');
        }

        async function cartFetch(url, method, body = null) {
            const opts = { method, headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' } };
            if (body) opts.body = JSON.stringify(body);
            try {
                const res = await fetch(url, opts);
                const data = await res.json();
                if (!res.ok) { showToast(data.error || 'Error', true); return; }
                renderCart(data);
                if (data.success) showToast(data.success);
            } catch (e) { showToast('Network error', true); }
        }

        window.cartUpdate = (key, qty) => cartFetch(`/cart/${key}`, 'PATCH', { quantity: qty });
        window.cartRemove = (key) => cartFetch(`/cart/${key}`, 'DELETE');

        document.addEventListener('submit', (e) => {
            const form = e.target;
            if (form.action && form.action.includes('/cart/add')) {
                e.preventDefault();
                const fd = new FormData(form);
                cartFetch('/cart/add', 'POST', {
                    food_id: fd.get('food_id'),
                    portion: fd.get('portion'),
                    quantity: parseInt(fd.get('quantity')) || 1,
                });
            }
        });

        window.placeDineInOrder = async () => {
            placeBtn.disabled = true;
            placeBtn.innerHTML = 'Processing...';
            try {
                const res = await fetch('{{ route("dine-in.place") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (!res.ok) {
                    showToast(data.error || 'Failed to place order', true);
                    placeBtn.disabled = false;
                    placeBtn.innerHTML = 'Generate Token & Pay';
                    return;
                }
                if (data.redirect) window.location.href = data.redirect;
            } catch (e) {
                showToast('Network error', true);
                placeBtn.disabled = false;
            }
        };

        fetch('/cart/data', { headers: { 'Accept': 'application/json' } }).then(r => r.json()).then(renderCart);
    });
    </script>
</body>
</html>
