@extends('layouts.app')

@section('title', 'Laporan Aktivitas')

@section('content')
    <main class="max-w-7xl mx-auto px-6 py-12 space-y-14">
        <!-- Statistik Ringkas -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            @php
                $cards = [
                    ['label' => 'Total Aktivitas Hari Ini', 'value' => $totalAktivitas, 'icon' => 'activity', 'color' => 'indigo'],
                    ['label' => 'Login Driver', 'value' => $loginDriver, 'icon' => 'log-in', 'color' => 'blue'],
                    ['label' => 'Logout Driver', 'value' => $logoutDriver, 'icon' => 'log-out', 'color' => 'rose']
                ];
            @endphp

            @foreach ($cards as $card)
                <div
                    class="p-6 rounded-[2rem] shadow-xl bg-gradient-to-br from-{{ $card['color'] }}-300 via-{{ $card['color'] }}-200 to-white dark:from-{{ $card['color'] }}-600 dark:via-{{ $card['color'] }}-700 dark:to-gray-900 flex items-center justify-between border border-{{ $card['color'] }}-400 dark:border-{{ $card['color'] }}-600">
                    <div>
                        <p class="text-sm font-medium text-{{ $card['color'] }}-700 dark:text-{{ $card['color'] }}-200">
                            {{ $card['label'] }}</p>
                        <h3 class="text-4xl font-extrabold text-gray-800 dark:text-white tracking-wide mt-1">
                            {{ $card['value'] }}</h3>
                    </div>
                    <div class="bg-{{ $card['color'] }}-200 dark:bg-{{ $card['color'] }}-800 p-4 rounded-full shadow-inner">
                        <i data-lucide="{{ $card['icon'] }}"
                            class="w-7 h-7 text-{{ $card['color'] }}-700 dark:text-{{ $card['color'] }}-200"></i>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Filter dan Tabel Aktivitas -->
        <div
            class="bg-white dark:bg-gray-900 p-10 rounded-[2rem] shadow-2xl border border-gray-100 dark:border-gray-700 transition-all">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-800 dark:text-white">Riwayat Aktivitas Driver</h2>
                <form method="GET" class="flex items-center gap-3">
                    <label for="filter" class="text-sm text-gray-600 dark:text-gray-300">Filter:</label>
                    <select name="filter" id="filter" onchange="this.form.submit()"
                        class="px-4 py-2 rounded-md bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-sm text-gray-800 dark:text-gray-200 shadow-sm">
                        <option value="">Semua</option>
                        <option value="hari" {{ request('filter') == 'hari' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="minggu" {{ request('filter') == 'minggu' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="bulan" {{ request('filter') == 'bulan' ? 'selected' : '' }}>Bulan Ini</option>
                    </select>
                </form>
            </div>

            <div
                class="overflow-x-auto bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 transition">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm text-left">
                    <thead
                        class="bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-3">Waktu</th>
                            <th class="px-6 py-3">Nama Driver</th>
                            <th class="px-6 py-3">Aktivitas</th>
                            <th class="px-6 py-3">IP Address</th>
                            <th class="px-6 py-3">Device</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-gray-700 dark:text-gray-200">
                        @foreach ($aktivitas as $log)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-all duration-200">
                                            <td class="px-6 py-4 font-mono text-xs">{{ $log['waktu'] }}</td>
                                            <td class="px-6 py-4">{{ $log['nama'] }}</td>
                                            <td class="px-6 py-4">
                                                <span class="inline-block px-3 py-1 text-xs font-bold rounded-full 
                                    {{ $log['jenis'] == 'Login'
                            ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300'
                            : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-700 dark:text-yellow-100' }}">
                                                    {{ $log['jenis'] }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-xs">{{ $log['ip'] }}</td>
                                            <td class="px-6 py-4 text-xs">{{ $log['device'] }}</td>
                                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Chart Statistik -->
        <div class="mt-10 bg-white dark:bg-gray-900 p-6 rounded-2xl shadow-xl border dark:border-gray-800">
            <h3 class="text-lg font-bold mb-4 text-gray-800 dark:text-white">Statistik Login & Logout</h3>
            <canvas id="chartAktivitas" class="w-full h-64"></canvas>
        </div>
    </main>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            lucide.createIcons();

            let chartAktivitas = null;

            function renderChart() {
                if (chartAktivitas) chartAktivitas.destroy();

                const isDark = document.documentElement.classList.contains('dark');
                const ctx = document.getElementById('chartAktivitas').getContext('2d');

                chartAktivitas = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Login', 'Logout'],
                        datasets: [{
                            label: 'Jumlah Aktivitas',
                            data: [{{ $loginDriver }}, {{ $logoutDriver }}],
                            backgroundColor: isDark ? ['#166534', '#854d0e'] : ['#22c55e', '#facc15'],
                            borderRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: isDark ? '#1e293b' : '#fff',
                                titleColor: isDark ? '#fefce8' : '#111827',
                                bodyColor: isDark ? '#e5e7eb' : '#1f2937'
                            }
                        },
                        scales: {
                            x: {
                                ticks: {
                                    color: isDark ? '#cbd5e1' : '#374151'
                                },
                                grid: {
                                    color: isDark ? '#334155' : '#e5e7eb'
                                }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1,
                                    color: isDark ? '#cbd5e1' : '#374151'
                                },
                                grid: {
                                    color: isDark ? '#334155' : '#e5e7eb'
                                }
                            }
                        }
                    }
                });
            }

            renderChart();

            const observer = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    if (mutation.attributeName === 'class') renderChart();
                });
            });

            observer.observe(document.documentElement, { attributes: true });
        </script>
    @endpush

@endsection