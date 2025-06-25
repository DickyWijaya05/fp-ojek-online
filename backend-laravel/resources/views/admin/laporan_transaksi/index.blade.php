@extends('layouts.app')

@section('title', 'Laporan Transaksi')

@section('content')
<main class="max-w-7xl mx-auto px-6 py-10 space-y-12">
  <!-- Statistik Ringkas -->
  <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
    <div class="p-6 rounded-3xl shadow-xl bg-gradient-to-br from-yellow-300 via-yellow-200 to-white dark:from-yellow-600 dark:via-yellow-700 dark:to-gray-900 flex items-center justify-between border border-yellow-400 dark:border-yellow-600 transition-all duration-300">
      <div>
        <p class="text-sm font-medium text-yellow-700 dark:text-yellow-200">Total Transaksi</p>
        <h3 class="text-4xl font-extrabold text-gray-800 dark:text-white tracking-wide mt-1">{{ $totalTransaksi }}</h3>
      </div>
      <div class="bg-yellow-200 dark:bg-yellow-800 p-4 rounded-full shadow-inner">
        <i data-lucide="list" class="w-7 h-7 text-yellow-700 dark:text-yellow-200"></i>
      </div>
    </div>
    <div class="p-6 rounded-3xl shadow-xl bg-gradient-to-br from-green-300 via-green-200 to-white dark:from-green-600 dark:via-green-700 dark:to-gray-900 flex items-center justify-between border border-green-400 dark:border-green-600 transition-all duration-300">
      <div>
        <p class="text-sm font-medium text-green-700 dark:text-green-200">Total Penghasilan</p>
        <h3 class="text-4xl font-extrabold text-gray-800 dark:text-white tracking-wide mt-1">Rp {{ number_format($totalPenghasilan, 0, ',', '.') }}</h3>
      </div>
      <div class="bg-green-200 dark:bg-green-800 p-4 rounded-full shadow-inner">
        <i data-lucide="credit-card" class="w-7 h-7 text-green-700 dark:text-green-200"></i>
      </div>
    </div>
  </div>

 <!-- Tombol Cetak PDF -->
<section class="flex justify-end">
  <a href="{{ route('admin.laporan.transaksi.pdf') }}" target="_blank"
     class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-medium shadow-lg transition-all duration-200">
    <i data-lucide="printer" class="w-5 h-5"></i>
    <span>Cetak PDF</span>
  </a>
</section>


  <!-- Tabel Transaksi -->
  <div class="overflow-x-auto bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 transition">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm text-left">
      <thead class="bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 uppercase text-xs tracking-wider">
        <tr>
          <th class="px-6 py-3">ID</th>
          <th class="px-6 py-3">Pelanggan</th>
          <th class="px-6 py-3">Driver</th>
          <th class="px-6 py-3">Tanggal</th>
          <th class="px-6 py-3">Jarak (km)</th>
          <th class="px-6 py-3">Tarif</th>
          <th class="px-6 py-3">Metode</th>
          <th class="px-6 py-3">Status</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-gray-700 dark:text-gray-200">
        @foreach ($laporan as $item)
        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-all duration-200">
          <td class="px-6 py-4 font-mono text-xs">{{ $item['id'] }}</td>
          <td class="px-6 py-4">{{ $item['pelanggan'] }}</td>
          <td class="px-6 py-4">{{ $item['driver'] }}</td>
          <td class="px-6 py-4">{{ $item['tanggal'] }}</td>
          <td class="px-6 py-4">{{ number_format($item['jarak'], 1) }}</td>
          <td class="px-6 py-4">Rp {{ number_format($item['tarif'], 0, ',', '.') }}</td>
          <td class="px-6 py-4">
            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700 dark:bg-blue-700 dark:text-blue-100">
              {{ $item['metode'] }}
            </span>
          </td>
          <td class="px-6 py-4">
            <span class="inline-block px-3 py-1 text-xs font-bold rounded-full 
              {{ $item['status'] == 'Lunas' 
                  ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' 
                  : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-700 dark:text-yellow-100' }}">
              {{ $item['status'] }}
            </span>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</main>

@push('scripts')
<script>
  lucide.createIcons();
</script>
@endpush
@endsection
