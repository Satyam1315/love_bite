<x-app-layout pageTitle="Dashboard">
    <div class="space-y-6">
        <div class="grid gap-6 md:grid-cols-3">
            <div class="lb-card p-6 md:col-span-2">
                <h3 class="text-xl font-bold text-gray-900">Welcome, {{ auth()->user()->name }}</h3>
                <p class="mt-2 text-gray-600 font-semibold leading-relaxed">Track your latest order status and quickly reorder your favorite dishes.</p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('menu.index') }}" class="rounded-xl bg-orange-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-orange-700 shadow-md shadow-orange-100 transition active:scale-[0.98]">Browse Menu</a>
                    <a href="{{ route('orders.index') }}" class="rounded-xl border border-orange-200 px-5 py-2.5 text-sm font-bold text-orange-700 hover:bg-orange-50 transition active:scale-[0.98]">Order History</a>
                </div>
            </div>
            <div class="lb-card border-yellow-200 bg-yellow-50/50 p-6">
                <h3 class="text-lg font-bold text-yellow-800">🛵 Delivery Notice</h3>
                <p class="mt-2 text-sm text-yellow-700 font-semibold leading-relaxed">Delivery system is currently in progress.</p>
                <p class="mt-2 text-sm text-yellow-700 font-semibold leading-relaxed">You can still track statuses up to Delivery In Progress.</p>
            </div>
        </div>

        <div class="lb-card p-6">
            <h3 class="text-xl font-bold text-gray-900">Quick Actions</h3>
            <div class="mt-4 grid gap-3 sm:grid-cols-2 md:grid-cols-4">
                <a class="rounded-xl border border-orange-100 p-4 text-sm font-bold hover:bg-orange-50/50 text-orange-800 transition block text-center" href="{{ route('cart.index') }}">🛒 View Cart</a>
                <a class="rounded-xl border border-orange-100 p-4 text-sm font-bold hover:bg-orange-50/50 text-orange-800 transition block text-center" href="{{ route('orders.checkout') }}">💳 Checkout</a>
                <a class="rounded-xl border border-orange-100 p-4 text-sm font-bold hover:bg-orange-50/50 text-orange-800 transition block text-center" href="{{ route('orders.index') }}">📦 Track Orders</a>
                <a class="rounded-xl border border-orange-100 p-4 text-sm font-bold hover:bg-orange-50/50 text-orange-800 transition block text-center" href="{{ route('profile.edit') }}">👤 Edit Profile</a>
            </div>
        </div>
    </div>
</x-app-layout>
