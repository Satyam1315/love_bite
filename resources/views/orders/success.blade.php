<x-app-layout>
    <x-slot name="header">
        <h2 class="display-font text-4xl text-gray-900">Order Confirmed</h2>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <div class="lb-card border-green-200 bg-green-50 p-8 text-center">
                <p class="text-sm font-bold uppercase tracking-wide text-green-700">Success</p>
                <h3 class="mt-2 text-3xl font-extrabold text-gray-900">Thank you for ordering from Love Bite</h3>
                <p class="mt-3 text-gray-700">Your order ID: <strong>{{ $order->order_number }}</strong></p>
                <p class="mt-1 text-gray-700">Current status: <strong>{{ str_replace('_', ' ', ucfirst($order->status)) }}</strong></p>
                <p class="mt-1 text-gray-700">Payment status: <strong>{{ ucfirst($order->payment_status ?? 'pending') }}</strong></p>
                <p class="mt-4 text-sm text-gray-600">Delivery system is currently in progress.</p>
                <div class="mt-6 flex flex-wrap justify-center gap-3">
                    <a href="{{ route('orders.index') }}" class="rounded-lg bg-gray-900 px-5 py-2 text-sm font-bold text-white">Track Orders</a>
                    <a href="{{ route('home') }}" class="rounded-lg border border-gray-300 px-5 py-2 text-sm font-bold text-gray-800">Back to Menu</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
