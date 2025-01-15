<x-filament::page>
    <div class="grid grid-cols-1 xl:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <h2 class="text-gray-800 dark:text-gray-200 text-lg font-semibold">Total Earnings</h2>
            <p class="text-2xl font-bold text-green-500 mt-2">$ {{ number_format($totalRevenue, 2) }}</p>
            <span class="text-sm text-gray-500 dark:text-gray-400">Overall earnings from completed orders</span>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <h2 class="text-gray-800 dark:text-gray-200 text-lg font-semibold">Total Orders</h2>
            <p class="text-2xl font-bold text-orange-500 mt-2">{{ $totalOrders }}</p>
            <span class="text-sm text-gray-500 dark:text-gray-400">Total number of orders placed</span>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <h2 class="text-gray-800 dark:text-gray-200 text-lg font-semibold">Total Customers</h2>
            <p class="text-2xl font-bold text-blue-500 mt-2">{{ $totalCustomers }}</p>
            <span class="text-sm text-gray-500 dark:text-gray-400">Registered customers on the platform</span>
        </div>
    </div>

    <x-filament::card>
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Total Sales by Month</h2>
        <canvas id="monthlySalesChart" width="400" height="200"></canvas>
    </x-filament::card>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mt-6">
        <h2 class="text-gray-900 dark:text-gray-200 text-lg font-semibold">Recent Orders</h2>
        <div class="overflow-x-auto mt-4">
            <table class="table-auto w-full border-collapse border border-gray-300 dark:border-gray-600">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700">
                        <th class="px-4 py-2 text-left font-bold text-gray-900 dark:text-gray-300">Customer</th>
                        <th class="px-4 py-2 text-left font-bold text-gray-900 dark:text-gray-300">Total Price</th>
                        <th class="px-4 py-2 text-left font-bold text-gray-900 dark:text-gray-300">Status</th>
                        <th class="px-4 py-2 text-left font-bold text-gray-900 dark:text-gray-300">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentOrders as $order)
                        <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 py-2 text-gray-900 dark:text-gray-300">{{ $order->customer_name }}</td>
                            <td class="px-4 py-2 text-gray-900 dark:text-gray-300">$ {{ number_format($order->total_price, 2) }}</td>
                            <td class="px-4 py-2">
                                <span class="px-3 py-1 rounded-full text-sm font-semibold 
                                    @if ($order->status === 'completed') bg-green-500 text-black
                                    @elseif ($order->status === 'pending') bg-yellow-500 text-black
                                    @elseif ($order->status === 'cancelled') bg-red-500 text-black
                                    @else bg-gray-500 text-black @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-gray-900 dark:text-gray-300">
                                {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, h:i A') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-2 text-center text-gray-500 dark:text-gray-400">
                                No recent orders available.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mt-6">
    <h2 class="text-gray-900 dark:text-gray-100 text-lg font-semibold">Top Products</h2>
    <ul class="mt-4">
        @forelse ($topProducts as $product)
            <li class="flex justify-between items-center border-b border-gray-200 dark:border-gray-700 py-2">
                <span class="text-gray-800 dark:text-gray-300">{{ $product->name }}</span>
                <span class="text-gray-900 dark:text-gray-200 font-semibold">{{ $product->total_sold }} sold</span>
            </li>
        @empty
            <li class="text-gray-600 dark:text-gray-400 text-center py-2">
                No products available.
            </li>
        @endforelse
    </ul>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('monthlySalesChart').getContext('2d');

        // Function to get the current theme mode (light or dark)
        function getThemeColors() {
            const isDarkMode = document.documentElement.classList.contains('dark');
            return {
                textColor: isDarkMode ? 'rgba(255, 255, 255, 0.9)' : 'rgba(0, 0, 0, 0.9)',
                gridColor: isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)',
                legendColor: isDarkMode ? 'rgba(255, 255, 255, 0.9)' : 'rgba(0, 0, 0, 0.9)'
            };
        }

        // Initial theme colors
        let themeColors = getThemeColors();

        // Create the Chart
        const monthlySalesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($monthlyOrders->pluck('month')), // Month labels
                datasets: [{
                    label: 'Total Sales',
                    data: @json($monthlyOrders->pluck('revenue')), // Revenue data
                    borderColor: 'rgba(255, 165, 0, 1)', // Orange
                    backgroundColor: 'rgba(255, 165, 0, 0.2)', // Light orange
                    fill: true,
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(255, 165, 0, 1)', // Orange for points
                    pointBorderColor: '#fff',
                    tension: 0.4, // Adds smooth curves to the line
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        labels: {
                            color: themeColors.legendColor, // Legend text color
                        },
                    },
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Month',
                            color: themeColors.textColor, // X-axis title color
                            font: {
                                size: 14,
                            }
                        },
                        ticks: {
                            color: themeColors.textColor, // X-axis tick color
                        },
                        grid: {
                            color: themeColors.gridColor, // X-axis grid line color
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Revenue (MYR)',
                            color: themeColors.textColor, // Y-axis title color
                            font: {
                                size: 14,
                            }
                        },
                        ticks: {
                            color: themeColors.textColor, // Y-axis tick color
                        },
                        grid: {
                            color: themeColors.gridColor, // Y-axis grid line color
                        }
                    }
                },
                elements: {
                    line: {
                        borderWidth: 3, // Thicker line for aesthetics
                    },
                    point: {
                        radius: 5, // Larger points for emphasis
                        hoverRadius: 7, // Larger points on hover
                        hoverBorderWidth: 2, // Border width when hovered
                    }
                }
            }
        });

        // Watch for theme changes
        const observer = new MutationObserver(() => {
            themeColors = getThemeColors();

            // Update Chart.js options dynamically when theme changes
            monthlySalesChart.options.plugins.legend.labels.color = themeColors.legendColor;
            monthlySalesChart.options.scales.x.title.color = themeColors.textColor;
            monthlySalesChart.options.scales.x.ticks.color = themeColors.textColor;
            monthlySalesChart.options.scales.x.grid.color = themeColors.gridColor;
            monthlySalesChart.options.scales.y.title.color = themeColors.textColor;
            monthlySalesChart.options.scales.y.ticks.color = themeColors.textColor;
            monthlySalesChart.options.scales.y.grid.color = themeColors.gridColor;

            // Update the chart
            monthlySalesChart.update();
        });

        // Observe the HTML class attribute for theme changes
        observer.observe(document.documentElement, {
            attributes: true,
            attributeFilter: ['class'],
        });
    });
</script>



</x-filament::page>