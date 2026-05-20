<x-admin-layout pageTitle="Manage Orders">
    <div class="space-y-6">

            <div class="lb-card p-5">
                <div class="overflow-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b border-orange-100 text-left text-gray-600">
                                <th class="px-3 py-2">Order</th>
                                <th class="px-3 py-2">Customer</th>
                                <th class="px-3 py-2">Items</th>
                                <th class="px-3 py-2">Total</th>
                                <th class="px-3 py-2">Status</th>
                                <th class="px-3 py-2">Update</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr class="border-b border-orange-50 align-top">
                                    <td class="px-3 py-2">
                                        <div class="font-semibold">{{ $order->order_number }}</div>
                                        <div class="mt-1 flex flex-col gap-1">
                                            <span class="inline-block w-max rounded-full bg-blue-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-blue-800">{{ str_replace('_', '-', $order->order_type) }}</span>
                                        </div>
                                    </td>
                                    <td class="px-3 py-2">
                                        <div class="font-bold text-gray-900">{{ $order->user->name ?? '-' }}</div>
                                        <div class="mt-1 text-xs text-gray-600">
                                            @if($order->order_type === 'delivery')
                                                <span class="font-semibold text-gray-800">Address:</span> {{ Str::limit($order->address, 50) }}
                                            @elseif($order->order_type === 'dine_in')
                                                <span class="font-semibold text-gray-800">Table:</span> {{ $order->table_number }}
                                            @elseif($order->order_type === 'takeaway' && $order->pickup_time)
                                                <span class="font-semibold text-gray-800">Pickup:</span> {{ $order->pickup_time->format('d M, h:i A') }}
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-3 py-2">
                                        <ul class="space-y-1">
                                            @foreach($order->items as $item)
                                                <li>{{ $item->food->name ?? 'Deleted Item' }} x {{ $item->quantity }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="px-3 py-2">Rs {{ number_format((float) $order->total_amount, 2) }}</td>
                                    <td class="px-3 py-2">
                                        @if($order->is_cancelled)
                                            <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-bold text-red-700">Cancelled</span>
                                        @else
                                            <span class="rounded-full bg-orange-100 px-3 py-1 text-xs font-bold text-orange-800">{{ str_replace('_', ' ', ucfirst($order->status)) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2">
                                        @if($order->is_cancelled)
                                            <span class="text-xs font-semibold text-red-700">No updates allowed</span>
                                        @else
                                            <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="flex gap-2">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" class="rounded border border-orange-200 px-2 py-1 text-xs">
                                                    @foreach($statuses as $status)
                                                        <option value="{{ $status }}" @selected($order->status === $status)>{{ str_replace('_', ' ', ucfirst($status)) }}</option>
                                                    @endforeach
                                                </select>
                                                <button class="rounded border border-gray-300 px-2 py-1 text-xs font-bold">Save</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $orders->links() }}</div>
            </div>
    </div>
</x-admin-layout>
