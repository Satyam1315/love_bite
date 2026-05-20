<x-app-layout pageTitle="My Orders">
    <div class="space-y-6">
        <div class="rounded-xl border border-yellow-200 bg-yellow-50/50 p-4 text-sm font-bold text-yellow-800">
            🛵 Delivery system is currently in progress. You can track statuses up to Delivery In Progress.
        </div>

        @forelse($orders as $order)
            <article class="lb-card p-5">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-orange-100 pb-3">
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-lg font-bold text-gray-900">{{ $order->order_number }}</h3>
                            <span class="rounded-full bg-blue-100 px-2 py-0.5 text-xs font-bold text-blue-800 capitalize">{{ str_replace('_', '-', $order->order_type) }}</span>
                        </div>
                        <p class="text-sm text-gray-600 mt-0.5">{{ $order->created_at->format('d M Y, h:i A') }}</p>
                        
                        @if($order->order_type === 'delivery')
                            <p class="text-xs text-gray-500 mt-1"><span class="font-bold text-gray-700">Delivery To:</span> {{ $order->address }}</p>
                        @elseif($order->order_type === 'dine_in')
                            <p class="text-xs text-gray-500 mt-1"><span class="font-bold text-gray-700">Table:</span> {{ $order->table_number }}</p>
                        @elseif($order->order_type === 'takeaway' && $order->pickup_time)
                            <p class="text-xs text-gray-500 mt-1"><span class="font-bold text-gray-700">Pickup At:</span> {{ $order->pickup_time->format('d M Y, h:i A') }}</p>
                        @endif
                    </div>
                    <div class="flex flex-col items-end gap-1">
                        @if($order->is_cancelled)
                            <span class="rounded-full bg-red-100 px-4 py-1 text-sm font-bold text-red-700">Cancelled</span>
                        @else
                            <span class="rounded-full bg-orange-100 px-4 py-1 text-sm font-bold text-orange-800">{{ str_replace('_', ' ', ucfirst($order->status)) }}</span>
                        @endif
                        <span class="text-xs font-bold text-gray-500 uppercase">{{ $order->payment_method }} - {{ $order->payment_status }}</span>
                    </div>
                </div>

                <div class="mt-4 space-y-2 text-sm text-gray-700 font-semibold">
                    @foreach($order->items as $item)
                        <div class="flex items-center justify-between">
                            <span>{{ $item->food->name ?? 'Deleted Item' }} x {{ $item->quantity }}</span>
                            <span>Rs {{ number_format((float) ($item->price * $item->quantity), 2) }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 border-t border-orange-100 pt-3 text-right">
                    <p class="text-xs text-gray-500 font-bold">Total Amount</p>
                    <p class="text-lg font-extrabold text-orange-700">Rs {{ number_format((float) $order->total_amount, 2) }}</p>

                    @if($order->canBeCancelledByUser())
                        <form method="POST" action="{{ route('orders.cancel', $order) }}" class="mt-3 inline-block">
                            @csrf
                            @method('PATCH')
                            <button class="rounded-xl border border-red-200 px-4 py-2 text-xs font-bold text-red-700 hover:bg-red-50 transition active:scale-[0.98]">Cancel Order</button>
                        </form>
                    @elseif($order->is_cancelled)
                        <p class="mt-2 text-xs font-bold text-red-700">Cancelled by customer</p>
                    @endif
                </div>
            </article>
        @empty
            <div class="lb-card p-8 text-center text-gray-600 font-bold">No orders placed yet.</div>
        @endforelse

        <div>{{ $orders->links() }}</div>
    </div>
</x-app-layout>
