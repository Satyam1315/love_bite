<x-admin-layout pageTitle="Dashboard">
    <div class="space-y-6">
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <a href="{{ route('admin.orders.index') }}" class="lb-card p-5 block">
                    <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Total Orders</p>
                    <p class="mt-2 text-3xl font-extrabold text-gray-900">{{ $totalOrders }}</p>
                </a>
                <a href="{{ route('admin.analytics.revenue') }}" class="lb-card p-5 block">
                    <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Revenue</p>
                    <p class="mt-2 text-3xl font-extrabold text-green-700">Rs {{ number_format((float) $revenue, 2) }}</p>
                    <p class="mt-2 text-xs font-semibold text-orange-700">View analytics</p>
                </a>
                <a href="{{ route('admin.categories.index') }}" class="lb-card p-5">
                    <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Manage</p>
                    <p class="mt-2 text-lg font-bold text-gray-900">Categories</p>
                </a>
                <a href="{{ route('admin.foods.index') }}" class="lb-card p-5">
                    <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Manage</p>
                    <p class="mt-2 text-lg font-bold text-gray-900">Foods</p>
                </a>
                <a href="{{ route('admin.coupons.index') }}" class="lb-card p-5">
                    <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Manage</p>
                    <p class="mt-2 text-lg font-bold text-gray-900">Coupons</p>
                    <!-- <p class="text-xs font-semibold text-orange-700 mt-1">Create & toggle coupons →</p> -->
                </a>
                @php($__isOpen = \App\Models\Setting::isOpen())
                <a href="{{ route('admin.settings') }}" class="lb-card p-5 {{ !$__isOpen ? 'border-red-200 bg-red-50/30' : '' }}">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Restaurant</p>
                        <span class="w-2 h-2 rounded-full {{ $__isOpen ? 'bg-green-500' : 'bg-red-500' }} animate-pulse"></span>
                    </div>
                    <p class="mt-2 text-lg font-bold {{ $__isOpen ? 'text-green-700' : 'text-red-700' }}">{{ $__isOpen ? 'Open' : 'Closed' }}</p>
                    <p class="text-xs font-semibold text-orange-700 mt-1">Manage hours →</p>
                </a>
            </div>

            <div class="lb-card p-5">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-xl font-bold">Recent Orders</h3>
                    <a href="{{ route('admin.orders.index') }}" class="text-sm font-bold text-orange-700">View All</a>
                </div>

                <div class="overflow-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b border-orange-100 text-left text-gray-600">
                                <th class="px-3 py-2">Order</th>
                                <th class="px-3 py-2">Customer</th>
                                <th class="px-3 py-2">Amount</th>
                                <th class="px-3 py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                                <tr class="border-b border-orange-50">
                                    <td class="px-3 py-2 font-semibold">{{ $order->order_number }}</td>
                                    <td class="px-3 py-2">{{ $order->user->name ?? '-' }}</td>
                                    <td class="px-3 py-2">Rs {{ number_format((float) $order->total_amount, 2) }}</td>
                                    <td class="px-3 py-2">{{ str_replace('_', ' ', ucfirst($order->status)) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
    </div>
</x-admin-layout>
