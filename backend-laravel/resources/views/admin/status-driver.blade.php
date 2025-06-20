@extends('layouts.app')

@section('title', 'Status Driver')

@section('content')
<div class="p-6 max-w-7xl mx-auto space-y-6">

  <!-- Search -->
  <div class="flex justify-end">
    <input type="search" id="searchDriver" placeholder="Cari nama driver..."
      class="w-full sm:w-72 px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-400 shadow-sm">
  </div>

  <!-- Table -->
  <div class="overflow-x-auto bg-white rounded-2xl shadow-xl border border-gray-100 animate-fade-in-up">
    <table class="min-w-full divide-y divide-gray-200 text-sm text-left">
      <thead class="bg-gray-100 text-gray-600 uppercase text-xs tracking-wider">
        <tr>
          <th class="px-6 py-3">Foto</th>
          <th class="px-6 py-3">Nama</th>
          <th class="px-6 py-3">Status</th>
          <th class="px-6 py-3">Rating</th>
          <th class="px-6 py-3">Perjalanan</th>
        </tr>
      </thead>
      <tbody id="driverBody" class="divide-y divide-gray-100 text-gray-700">
        @foreach([
          ['nama' => 'Dian Pratama', 'status' => 'online', 'rating' => 4.9, 'trip' => 120, 'foto' => 'https://i.pravatar.cc/100?img=1'],
          ['nama' => 'Bayu Saputra', 'status' => 'offline', 'rating' => 4.3, 'trip' => 95, 'foto' => 'https://i.pravatar.cc/100?img=2'],
          ['nama' => 'Rizki Alamsyah', 'status' => 'online', 'rating' => 4.8, 'trip' => 132, 'foto' => 'https://i.pravatar.cc/100?img=3'],
          ['nama' => 'Yusuf Kurniawan', 'status' => 'offline', 'rating' => 4.4, 'trip' => 87, 'foto' => 'https://i.pravatar.cc/100?img=4'],
        ] as $driver)
        <tr class="hover:bg-gray-50 transition-all duration-200 driver-row">
          <td class="px-6 py-4">
            <img src="{{ $driver['foto'] }}" alt="Foto {{ $driver['nama'] }}" class="w-12 h-12 rounded-full border shadow">
          </td>
          <td class="px-6 py-4 font-semibold driver-name">{{ $driver['nama'] }}</td>
          <td class="px-6 py-4">
            @if($driver['status'] === 'online')
              <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-medium animate-pulse">
                <i data-lucide="radio" class="w-4 h-4"></i> Online
              </span>
            @else
              <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gray-200 text-gray-600 text-xs font-medium">
                <i data-lucide="moon-star" class="w-4 h-4"></i> Offline
              </span>
            @endif
          </td>
          <td class="px-6 py-4 text-yellow-500 font-medium">
            <i data-lucide="star" class="inline-block w-4 h-4 -mt-0.5"></i>
            {{ number_format($driver['rating'], 1) }}
          </td>
          <td class="px-6 py-4">
            <i data-lucide="navigation" class="inline-block w-4 h-4 text-blue-500 -mt-0.5"></i>
            {{ $driver['trip'] }}
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection

@push('scripts')
<script>
  lucide.createIcons();

  document.getElementById('searchDriver').addEventListener('keyup', function () {
    const keyword = this.value.toLowerCase();
    const rows = document.querySelectorAll('.driver-row');
    rows.forEach(row => {
      const name = row.querySelector('.driver-name').innerText.toLowerCase();
      row.style.display = name.includes(keyword) ? '' : 'none';
    });
  });
</script>
@endpush
