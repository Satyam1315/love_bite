<x-admin-layout pageTitle="Revenue">
    <div class="space-y-6">
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div class="lb-card p-5">
                    <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Total Revenue</p>
                    <p class="mt-2 text-3xl font-extrabold text-green-700">Rs {{ number_format($totalRevenue, 2) }}</p>
                </div>
                <div class="lb-card p-5">
                    <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Total Orders</p>
                    <p class="mt-2 text-3xl font-extrabold text-gray-900">{{ $totalOrders }}</p>
                </div>
                <div class="lb-card p-5">
                    <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Average Order Value</p>
                    <p class="mt-2 text-3xl font-extrabold text-orange-700">Rs {{ number_format($averageOrderValue, 2) }}</p>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="lb-card p-5">
                    <h3 class="text-lg font-bold text-gray-900">Revenue Trend (Last 30 Days)</h3>
                    <div class="mt-4 h-[320px]">
                        <canvas id="dailyRevenueChart"></canvas>
                    </div>
                </div>

                <div class="lb-card p-5">
                    <h3 class="text-lg font-bold text-gray-900">Payments Breakdown</h3>
                    <div class="mt-4 h-[320px]">
                        <canvas id="paymentBreakdownChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="lb-card p-5">
                <h3 class="text-lg font-bold text-gray-900">Order Status Revenue</h3>
                <div class="mt-4 overflow-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b border-orange-100 text-left text-gray-600">
                                <th class="px-3 py-2">Status</th>
                                <th class="px-3 py-2">Orders</th>
                                <th class="px-3 py-2">Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($statusBreakdown as $status)
                                <tr class="border-b border-orange-50">
                                    <td class="px-3 py-2 font-semibold">{{ str_replace('_', ' ', ucfirst($status->status)) }}</td>
                                    <td class="px-3 py-2">{{ $status->count }}</td>
                                    <td class="px-3 py-2">Rs {{ number_format((float) $status->total, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script id="analytics-data" type="application/json">{!! json_encode([
        'dailyLabels' => $dailyLabels,
        'dailyValues' => $dailyValues,
        'paymentLabels' => $paymentLabels,
        'paymentCounts' => $paymentCounts,
        'paymentTotals' => $paymentTotals,
    ]) !!}</script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const analyticsDataElement = document.getElementById('analytics-data');
        const analyticsData = JSON.parse(analyticsDataElement.textContent || '{}');
        const dailyRevenueCtx = document.getElementById('dailyRevenueChart');
        const paymentBreakdownCtx = document.getElementById('paymentBreakdownChart');

        new Chart(dailyRevenueCtx, {
            type: 'line',
            data: {
                labels: analyticsData.dailyLabels || [],
                datasets: [{
                    label: 'Revenue (Rs)',
                    data: analyticsData.dailyValues || [],
                    borderColor: '#ea580c',
                    backgroundColor: 'rgba(234, 88, 12, 0.15)',
                    fill: true,
                    tension: 0.35,
                    pointRadius: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: (context) => {
                                const value = Number(context.parsed.y || 0);
                                return `Revenue: Rs ${value.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        new Chart(paymentBreakdownCtx, {
            type: 'doughnut',
            data: {
                labels: analyticsData.paymentLabels || [],
                datasets: [{
                    label: 'Payment Orders',
                    data: analyticsData.paymentCounts || [],
                    backgroundColor: ['#ea580c', '#16a34a', '#0284c7', '#a855f7'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: (context) => {
                                const count = Number(context.parsed || 0);
                                const total = Number((analyticsData.paymentTotals || [])[context.dataIndex] || 0);
                                const amount = total.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                                return `${context.label}: ${count} orders (Rs ${amount})`;
                            }
                        }
                    }
                }
            }
        });
    </script>
    </div>
</x-admin-layout>
