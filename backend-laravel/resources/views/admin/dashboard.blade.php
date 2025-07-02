@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
  <main class="p-6 max-w-7xl mx-auto space-y-10">
    @php
      $stats = [
        ['title' => 'Total Pengguna', 'value' => $totalUsers, 'icon' => 'users', 'color' => 'from-indigo-400 to-indigo-600', 'route' => route('admin.users')],
        ['title' => 'Total Driver', 'value' => $totalDrivers, 'icon' => 'bike', 'color' => 'from-teal-400 to-teal-600', 'route' => route('admin.drivers.index')],
        ['title' => 'Laporan Transaksi', 'value' => 'Lihat Detail', 'icon' => 'file-text', 'color' => 'from-orange-400 to-orange-600', 'route' => route('admin.laporan-transaksi')],
        ['title' => 'Status Driver', 'value' => 'Pantau Sekarang', 'icon' => 'activity', 'color' => 'from-pink-400 to-pink-600', 'route' => route('admin.status-driver')],
      ];

      $gradients = [
        'from-indigo-400 to-indigo-600' => 'linear-gradient(to bottom right, #818cf8, #4f46e5)',
        'from-teal-400 to-teal-600' => 'linear-gradient(to bottom right, #2dd4bf, #0d9488)',
        'from-orange-400 to-orange-600' => 'linear-gradient(to bottom right, #fb923c, #ea580c)',
        'from-pink-400 to-pink-600' => 'linear-gradient(to bottom right, #f472b6, #db2777)',
      ];
    @endphp

    <!-- Statistik -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      @foreach ($stats as $s)
        <a href="{{ $s['route'] }}"
          class="text-white p-6 rounded-2xl shadow-lg hover:scale-105 transition-all duration-300 flex items-center gap-5"
          style="background-image: {{ $gradients[$s['color']] ?? 'linear-gradient(to bottom right, #94a3b8, #64748b)' }};">
          <div class="bg-white bg-opacity-20 p-4 rounded-full shadow-inner">
            <i data-lucide="{{ $s['icon'] }}" class="w-7 h-7 text-white"></i>
          </div>
          <div>
            <p class="text-sm opacity-90">{{ $s['title'] }}</p>
            <h3 class="text-2xl font-bold">{{ $s['value'] }}</h3>
          </div>
        </a>
      @endforeach
    </div>

    <!-- Welcome -->
    <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl shadow-md border-l-4 border-yellow-400">
      <h2 class="text-xl font-semibold text-yellow-600 dark:text-yellow-400 mb-1">Selamat Datang, {{ Auth::user()->name }}</h2>
      <p class="text-gray-700 dark:text-gray-300">
        Anda login sebagai <strong>{{ Auth::user()->email }}</strong>. Dashboard ini dirancang untuk membantu Anda memantau sistem dengan mudah.
      </p>
    </div>

    <!-- Chart Filter -->
    <div class="flex justify-between items-center">
      <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Statistik Aktivitas</h3>
      <select id="filterWaktu" class="border rounded-md px-4 py-2 text-sm shadow-sm text-gray-800 bg-white dark:text-white dark:bg-gray-800 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-yellow-400 transition">
        <option value="mingguan" selected>Mingguan</option>
        <option value="bulanan">Bulanan</option>
        <option value="tahunan">Tahunan</option>
      </select>
    </div>

    <!-- Loader -->
    <div id="chartLoader" class="flex items-center justify-center h-64 w-full bg-gray-50 dark:bg-gray-800 rounded-xl">
      <svg class="animate-spin h-10 w-10 text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none"
        viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
      </svg>
    </div>

    <!-- Charts -->
    <canvas id="chartAktivitas" class="w-full h-64 hidden"></canvas>
    <canvas id="chartTransaksi" class="w-full h-64 mt-10"></canvas>
    <canvas id="chartDistribusi" class="w-full h-64 mt-10"></canvas>
  </main>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  lucide.createIcons();

  // === Variabel dari backend Laravel ===
  const aktivitasLabels = @json($labels);
  const loginData = @json($logins);
  const logoutData = @json($logouts);

  // Loader effect
  window.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
      document.getElementById('chartLoader')?.classList.add('hidden');
      document.getElementById('chartAktivitas')?.classList.remove('hidden');
      renderCharts();
    }, 1200);
  });

  function renderCharts() {
    new Chart(document.getElementById('chartAktivitas'), {
      type: 'bar',
      data: {
        labels: aktivitasLabels,
        datasets: [
          {
            label: 'Login',
            data: loginData,
            backgroundColor: '#10b981',
            borderRadius: 6
          },
          {
            label: 'Logout',
            data: logoutData,
            backgroundColor: '#facc15',
            borderRadius: 6
          }
        ]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { position: 'bottom' }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: { stepSize: 1 }
          }
        }
      }
    });

    new Chart(document.getElementById('chartTransaksi'), {
      type: 'line',
      data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
        datasets: [{
          label: 'Transaksi',
          data: [100, 120, 90, 150, 130, 170],
          fill: true,
          borderColor: '#38bdf8',
          backgroundColor: 'rgba(56, 189, 248, 0.2)',
          tension: 0.4,
          pointRadius: 4
        }]
      },
      options: {
        responsive: true,
        plugins: { legend: { display: false } }
      }
    });

    new Chart(document.getElementById('chartDistribusi'), {
      type: 'doughnut',
      data: {
        labels: ['Admin', 'Customer', 'Driver'],
        datasets: [{
          data: [10, 500, 340],
          backgroundColor: ['#f97316', '#22c55e', '#3b82f6'],
          hoverOffset: 10
        }]
      },
      options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } }
      }
    });
  }

  document.getElementById('filterWaktu')?.addEventListener('change', (e) => {
    alert('Filter diubah ke: ' + e.target.value);
    // Tambahkan logic AJAX di sini kalau ingin interaktif
  });
</script>
@endpush
