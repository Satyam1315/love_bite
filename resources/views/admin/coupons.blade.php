<x-admin-layout pageTitle="Discount Coupons">
    <div class="space-y-6">

        {{-- Add Coupon Card --}}
        <div class="lb-card p-6">
            <h3 class="text-xl font-bold mb-4">Create New Coupon</h3>
            <form method="POST" action="{{ route('admin.coupons.store') }}" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5 items-end">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1.5" for="code">Coupon Code</label>
                    <input type="text" id="code" name="code" placeholder="e.g. LOVE50" required
                           class="w-full px-4 py-2.5 text-sm border border-orange-200 rounded-xl focus:border-orange-400 focus:ring-1 focus:ring-orange-200 transition font-bold uppercase placeholder-gray-400">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1.5" for="type">Discount Type</label>
                    <select id="type" name="type" required
                            class="w-full px-4 py-2.5 text-sm border border-orange-200 rounded-xl focus:border-orange-400 focus:ring-1 focus:ring-orange-200 transition font-semibold">
                        <option value="percent">Percentage (%)</option>
                        <option value="fixed">Fixed Amount (₹)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1.5" for="value">Value</label>
                    <input type="number" step="0.01" min="0" id="value" name="value" placeholder="e.g. 10 or 150" required
                           class="w-full px-4 py-2.5 text-sm border border-orange-200 rounded-xl focus:border-orange-400 focus:ring-1 focus:ring-orange-200 transition font-semibold">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1.5" for="min_order_amount">Min. Order Amount (₹)</label>
                    <input type="number" step="0.01" min="0" id="min_order_amount" name="min_order_amount" value="0.00" required
                           class="w-full px-4 py-2.5 text-sm border border-orange-200 rounded-xl focus:border-orange-400 focus:ring-1 focus:ring-orange-200 transition font-semibold">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1.5" for="expires_at">Expiry Date</label>
                    <input type="date" id="expires_at" name="expires_at"
                           class="w-full px-4 py-2.5 text-sm border border-orange-200 rounded-xl focus:border-orange-400 focus:ring-1 focus:ring-orange-200 transition font-semibold">
                </div>
                <div class="sm:col-span-2 lg:col-span-5 flex justify-end">
                    <button type="submit"
                            class="px-6 py-2.5 bg-orange-600 hover:bg-orange-700 text-white text-sm font-bold rounded-xl transition active:scale-[0.98] shadow-md shadow-orange-200">
                        Create Coupon
                    </button>
                </div>
            </form>
        </div>

        {{-- Coupons List --}}
        <div class="lb-card p-6">
            <h3 class="text-xl font-bold mb-4">Active & Expired Coupons</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left">
                    <thead>
                        <tr class="border-b border-orange-100 text-gray-500 font-bold uppercase tracking-wider text-xs">
                            <th class="px-4 py-3">Code</th>
                            <th class="px-4 py-3">Type</th>
                            <th class="px-4 py-3">Value</th>
                            <th class="px-4 py-3">Min. Order</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Expires At</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-orange-50">
                        @forelse($coupons as $coupon)
                            <tr class="hover:bg-orange-50/20 transition duration-150">
                                <td class="px-4 py-3.5 font-black text-orange-700 tracking-wider text-base">{{ $coupon->code }}</td>
                                <td class="px-4 py-3.5 font-semibold text-gray-600">
                                    {{ $coupon->type === 'percent' ? 'Percentage' : 'Fixed Amount' }}
                                </td>
                                <td class="px-4 py-3.5 font-bold text-gray-900">
                                    {{ $coupon->type === 'percent' ? $coupon->value . '%' : '₹' . number_format($coupon->value, 2) }}
                                </td>
                                <td class="px-4 py-3.5 font-semibold text-gray-600">₹{{ number_format($coupon->min_order_amount, 2) }}</td>
                                <td class="px-4 py-3.5">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold
                                        {{ $coupon->is_active && (!$coupon->expires_at || !$coupon->expires_at->isPast()) ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $coupon->is_active && (!$coupon->expires_at || !$coupon->expires_at->isPast()) ? 'bg-green-500 animate-pulse' : 'bg-red-500' }}"></span>
                                        {{ $coupon->is_active && (!$coupon->expires_at || !$coupon->expires_at->isPast()) ? 'Active' : 'Inactive/Expired' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-gray-500 font-medium">
                                    {{ $coupon->expires_at ? $coupon->expires_at->format('M d, Y') : 'Never' }}
                                </td>
                                <td class="px-4 py-3.5 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <form method="POST" action="{{ route('admin.coupons.toggle', $coupon) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="px-3 py-1 text-xs font-bold rounded-lg border
                                                           {{ $coupon->is_active ? 'border-amber-200 text-amber-700 hover:bg-amber-50' : 'border-green-200 text-green-700 hover:bg-green-50' }}">
                                                {{ $coupon->is_active ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>

                                        <button type="submit" form="delete-coupon-{{ $coupon->id }}"
                                                class="px-3 py-1 text-xs font-bold rounded-lg border border-red-200 text-red-700 hover:bg-red-50">
                                            Delete
                                        </button>
                                    </div>

                                    <form id="delete-coupon-{{ $coupon->id }}" method="POST" action="{{ route('admin.coupons.delete', $coupon) }}" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500 font-medium">
                                    No coupons created yet. Use the form above to add your first discount code!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($coupons->hasPages())
                <div class="mt-4">
                    {{ $coupons->links() }}
                </div>
            @endif
        </div>

    </div>
</x-admin-layout>
