<x-filament-panels::page>

    @livewireStyles
    <style>
        canvas {
            max-width: 100%;
            margin: 0 auto;
            border-radius: 8px;
            background-color: transparent;
        }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            display: none;
            padding: 1rem;
            border-radius: 8px;
            color: #fff;
            background-color: rgba(54, 162, 235, 0.9);
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .notification.show {
            display: block;
            animation: fadeInOut 5s ease-in-out;
        }

        @keyframes fadeInOut {

            0%,
            100% {
                opacity: 0;
                transform: translateY(-10px);
            }

            20%,
            80% {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <!-- Notification -->
    <div id="notificationContainer" class="notification"></div>

    <!-- Metrics Section -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4 mb-8">
        <div class="p-4 shadow rounded-lg bg-white dark:bg-gray-800">
            <div class="text-sm text-gray-500 dark:text-gray-400">Total Users</div>
            <div class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">{{ $totalUsers }}</div>
        </div>
        <div class="p-4 shadow rounded-lg bg-white dark:bg-gray-800">
            <div class="text-sm text-gray-500 dark:text-gray-400">Total Products</div>
            <div class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">{{ $totalProducts }}</div>
        </div>
        <div class="p-4 shadow rounded-lg bg-white dark:bg-gray-800">
            <div class="text-sm text-gray-500 dark:text-gray-400">Total Today Revenue</div>
            <div class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">RM {{ number_format($totalRevenueToday, 2) }}</div>
        </div>
        <div class="p-4 shadow rounded-lg bg-white dark:bg-gray-800">
            <div class="text-sm text-gray-500 dark:text-gray-400">Orders Today</div>
            <div class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">{{ $ordersToday }}</div>
        </div>
    </div>


    <!-- chart canvas -->
    <div class="shadow rounded-lg p-6 bg-white dark:bg-gray-800">
        <div class="text-xl font-bold text-gray-900 dark:text-white mb-4">Today's Sales</div>
        <div style="height: 500px; width: 100%;"> <!-- Set width to 100% -->
            <canvas id="revenueOrdersChart" style="width: 100%; height: 100%;"></canvas>
        </div>
    </div>


    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartCtx = document.getElementById('revenueOrdersChart').getContext('2d');

            const hourlyData = @json($hourlyData);

            // Generate 24-hour intervals for the x-axis
            const hours = Array.from({
                length: 24
            }, (_, i) => i); // [0, 1, ..., 23]
            const formattedHours = hours.map((hour) => `${hour.toString().padStart(2, '0')}:00`); // Format as "00:00", "01:00", etc.

            // Map orders and revenue data to 24-hour intervals
            const orders = hours.map((hour) => hourlyData[hour]?.orders || 0);
            const revenue = hours.map((hour) => hourlyData[hour]?.revenue || 0);

            // Function to determine if dark mode is enabled
            const isDarkMode = () =>
                document.documentElement.classList.contains('dark') ||
                window.matchMedia('(prefers-color-scheme: dark)').matches;

            // Function to get theme-specific colors
            const getThemeColors = (darkMode) => ({
                textColor: darkMode ? '#ffffff' : '#000000', // Axis label and legend text color
                gridColor: darkMode ? 'rgba(255, 255, 255, 0.2)' : 'rgba(0, 0, 0, 0.1)', // Gridline color
            });

            // Chart data
            const chartData = {
                labels: formattedHours, // 24-hour intervals for the x-axis
                datasets: [{
                        label: 'Orders Entry Value',
                        data: orders,
                        borderColor: '#FFA500', // Orange line
                        backgroundColor: 'rgba(255, 165, 0, 0.5)', // Light orange fill
                        fill: true,
                        tension: 0.4, // Smooth curve
                    },
                    {
                        label: 'Net Revenue Value',
                        data: revenue,
                        borderColor: '#00BFFF', // Blue line
                        backgroundColor: 'rgba(0, 191, 255, 0.5)', // Light blue fill
                        fill: true,
                        tension: 0.4, // Smooth curve
                    },
                ],
            };

            // Function to create a chart with updated colors
            const createChart = () => {
                const darkMode = isDarkMode();
                const themeColors = getThemeColors(darkMode);

                const chartData = {
                    labels: formattedHours,
                    datasets: [{
                            label: 'Orders Entry Value',
                            data: orders,
                            borderColor: '#FFA500',
                            backgroundColor: 'rgba(255, 165, 0, 0.5)',
                            fill: true,
                            tension: 0.4,
                        },
                        {
                            label: 'Net Revenue Value',
                            data: revenue,
                            borderColor: '#00BFFF',
                            backgroundColor: 'rgba(0, 191, 255, 0.5)',
                            fill: true,
                            tension: 0.4,
                        },
                    ],
                };

                const chartOptions = {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                color: themeColors.textColor
                            },
                        },
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Time (24 Hours)',
                                color: themeColors.textColor
                            },
                            ticks: {
                                autoSkip: false,
                                maxRotation: 45,
                                minRotation: 45,
                                color: themeColors.textColor
                            },
                            grid: {
                                color: themeColors.gridColor
                            },
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Value',
                                color: themeColors.textColor
                            },
                            ticks: {
                                color: themeColors.textColor
                            },
                            grid: {
                                color: themeColors.gridColor
                            },
                        },
                    },
                };

                // Check if the chart instance exists and destroy it if needed
                if (window.revenueOrdersChart && typeof window.revenueOrdersChart.destroy === 'function') {
                    window.revenueOrdersChart.destroy();
                }

                // Create the new chart instance
                window.revenueOrdersChart = new Chart(chartCtx, {
                    type: 'line',
                    data: chartData,
                    options: chartOptions,
                });
            };
            // Create the initial chart
            createChart();

            // Listen for theme changes and update the chart colors
            const observer = new MutationObserver(createChart);
            observer.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['class'], // Watches for 'class' attribute changes
            });

            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', createChart);
        });
    </script>

</x-filament-panels::page>