<x-app-layout>
    <x-slot name="pageTitle">Menu</x-slot>
    <x-slot name="pageDescription">Browse the full Love Bite menu. Order delicious Indian food — tandoor, biryani, curries and more.</x-slot>

    {{-- Toast notification --}}
    <div id="toast" class="lb-toast"></div>

    {{-- ========== MAIN LAYOUT ========== --}}
    <div class="px-4 py-6 mx-auto max-w-[1400px] sm:px-6 lg:px-8">
        <div class="flex gap-6 items-start">
            {{-- LEFT: Menu Items --}}
            <section class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                    <h1 class="text-4xl text-gray-900 display-font">Full Menu</h1>
                    <form method="GET" action="{{ route('menu.index') }}" class="flex flex-wrap items-center gap-2">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search food..."
                            class="px-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:border-orange-400 focus:ring-1 focus:ring-orange-200 transition">
                        <select name="category" class="px-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:border-orange-400 focus:ring-1 focus:ring-orange-200 transition">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->name }}" @selected(request('category') === $category->name)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <select name="type" class="px-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:border-orange-400 focus:ring-1 focus:ring-orange-200 transition">
                            <option value="">All Types</option>
                            <option value="veg" @selected(request('type') === 'veg')>Veg</option>
                            <option value="non-veg" @selected(request('type') === 'non-veg')>Non-Veg</option>
                        </select>
                        <button class="px-5 py-2 text-sm font-bold text-white bg-gray-900 rounded-xl hover:bg-gray-800 transition">Apply</button>
                    </form>
                </div>

                <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                    @forelse($foods as $food)
                        <x-food-card :food="$food" :show-order-controls="true" />
                    @empty
                        <p class="p-8 text-center text-gray-500 bg-white col-span-full rounded-2xl border border-gray-100">No food items found for this filter.</p>
                    @endforelse
                </div>

                <div class="mt-8">{{ $foods->links() }}</div>
            </section>

            {{-- RIGHT: Side Cart --}}
            <aside id="side-cart" class="side-cart closed fixed lg:sticky top-[68px] right-0 z-30 w-80 lg:w-[320px] h-[calc(100vh-68px)] lg:h-auto lg:max-h-[calc(100vh-100px)] flex-shrink-0 bg-white lg:bg-white/80 lg:backdrop-blur-md border-l lg:border border-gray-200 lg:rounded-2xl shadow-2xl lg:shadow-sm overflow-hidden flex flex-col">
                {{-- Cart Header --}}
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 bg-gray-50/80">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 display-font">Order Cart</h2>
                        <p id="cart-count-label" class="text-xs text-gray-500 font-medium">{{ count($cart) }} {{ Str::plural('item', count($cart)) }}</p>
                    </div>
                    <button id="cart-close" class="lg:hidden p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Cart Items (JS rendered) --}}
                <div id="cart-items" class="flex-1 overflow-y-auto px-4 py-3 space-y-3"></div>

                {{-- Cart Footer --}}
                <div id="cart-footer" class="border-t border-gray-100 bg-gray-50/80 px-5 py-4 space-y-3 hidden">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500 font-medium">Subtotal</span>
                        <span id="cart-subtotal" class="text-sm font-bold text-gray-700">₹0</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-base font-bold text-gray-900">Total</span>
                        <span id="cart-total" class="text-xl font-bold text-orange-600 display-font">₹0</span>
                    </div>
                    @auth
                        <a href="{{ route('orders.checkout') }}" class="flex items-center justify-center gap-2 w-full px-4 py-3 text-sm font-bold text-white bg-orange-600 rounded-xl hover:bg-orange-700 transition active:scale-[0.97]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            Proceed to Checkout
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="flex items-center justify-center gap-2 w-full px-4 py-3 text-sm font-bold text-white bg-gray-900 rounded-xl hover:bg-gray-800 transition">
                            Login to Checkout
                        </a>
                    @endauth
                </div>
            </aside>
        </div>
    </div>

    {{-- Mobile cart overlay --}}
    <div id="cart-overlay" class="fixed inset-0 z-20 bg-black/40 backdrop-blur-sm hidden lg:hidden"></div>

    {{-- Mobile cart toggle (injected into nav via push) --}}
    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const CSRF = document.querySelector('meta[name="csrf-token"]').content;
        const cartItems = document.getElementById('cart-items');
        const cartFooter = document.getElementById('cart-footer');
        const cartCountLabel = document.getElementById('cart-count-label');
        const cartSubtotal = document.getElementById('cart-subtotal');
        const cartTotal = document.getElementById('cart-total');
        const cartBadge = document.getElementById('cart-badge-mobile');
        const sideCart = document.getElementById('side-cart');
        const cartToggle = document.getElementById('cart-toggle');
        const cartClose = document.getElementById('cart-close');
        const cartOverlay = document.getElementById('cart-overlay');
        const toastEl = document.getElementById('toast');

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
                        <p class="text-sm font-bold text-gray-500">Your cart is empty</p>
                        <p class="text-xs text-gray-400 mt-1">Add items from the menu</p>
                    </div>`;
                cartFooter.classList.add('hidden');
                return;
            }

            cartFooter.classList.remove('hidden');
            cartSubtotal.textContent = fmt(total);
            cartTotal.textContent = fmt(total);

            cartItems.innerHTML = cart.map(item => {
                const lineTotal = item.unit_price * item.quantity;
                const imgHtml = item.image
                    ? `<img src="/storage/${item.image}" alt="${item.name}" class="w-14 h-14 rounded-lg object-cover flex-shrink-0">`
                    : `<div class="w-14 h-14 rounded-lg bg-orange-100 flex items-center justify-center flex-shrink-0"><span class="text-xl">🍽️</span></div>`;
                return `
                <div class="flex gap-3 p-3 bg-gray-50/80 rounded-xl border border-gray-100 cart-item-enter" data-key="${item.key}">
                    ${imgHtml}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-gray-900 truncate">${item.name}</p>
                                <p class="text-[11px] text-orange-600 font-semibold capitalize">${item.portion}</p>
                                <p class="text-[11px] text-gray-400">${fmt(item.unit_price)} each</p>
                            </div>
                            <p class="text-sm font-bold text-gray-900 whitespace-nowrap">${fmt(lineTotal)}</p>
                        </div>
                        <div class="flex items-center justify-between mt-2">
                            <button onclick="cartUpdate('${item.key}', ${Math.max(1, item.quantity - 1)})" class="w-7 h-7 flex items-center justify-center text-sm font-bold text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 transition ${item.quantity <= 1 ? 'opacity-40 pointer-events-none' : ''}">−</button>
                            <span class="w-8 text-center text-sm font-bold text-gray-900">${item.quantity}</span>
                            <button onclick="cartUpdate('${item.key}', ${item.quantity + 1})" class="w-7 h-7 flex items-center justify-center text-sm font-bold text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 transition">+</button>
                            <button onclick="cartRemove('${item.key}')" class="ml-2 text-[11px] font-bold text-red-500 hover:text-red-700 transition">Remove</button>
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
                if (!res.ok) { showToast(data.error || 'Something went wrong', true); return; }
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

        fetch('/cart/data', { headers: { 'Accept': 'application/json' } }).then(r => r.json()).then(renderCart).catch(() => {});
    });
    </script>
    @endpush
</x-app-layout>
