@extends('layouts.app')
@section('title', 'Drivers')

@section('content')
<main class="p-6 max-w-7xl mx-auto space-y-6">
  <!-- Header Actions Left + Search Right -->
  <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
    <button onclick="document.getElementById('modalTambahDriver').classList.remove('hidden')" class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg shadow transition-transform hover:scale-105">
      <span class="iconify text-lg" data-icon="tabler:plus"></span>
      Tambah Driver
    </button>

    <div class="flex items-center gap-2">
      <div class="relative">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
          <span class="iconify text-gray-400" data-icon="tabler:search"></span>
        </span>
        <input id="searchInput" type="text" placeholder="Cari driver..." class="pl-10 pr-4 py-2 w-full sm:w-64 border rounded-lg shadow-sm focus:ring-yellow-400 focus:border-yellow-400"/>
      </div>
      <select id="statusFilter" class="px-4 py-2 border rounded-lg focus:ring-yellow-400 focus:border-yellow-400">
        <option value="">Semua Status</option>
        <option value="active">Aktif</option>
        <option value="nonactive">Nonaktif</option>
      </select>
    </div>
  </div>

  <!-- Alerts -->
  @if(session('success'))
    <div class="p-4 rounded bg-green-50 border border-green-200 text-green-800 flex items-center gap-2 animate-fade-in">
      <span class="iconify" data-icon="mdi:check-circle-outline"></span>
      {{ session('success') }}
    </div>
  @endif

  <!-- Table -->
  <div class="overflow-x-auto rounded-xl shadow-lg bg-white animate-fade-in">
    <table id="driverTable" class="min-w-full divide-y divide-gray-200 text-sm">
      <thead class="bg-gray-100 text-gray-600">
        <tr>
          <th class="px-6 py-3 text-left">ID</th>
          <th class="px-6 py-3 text-left">Nama</th>
          <th class="px-6 py-3 text-left">Email</th>
          <th class="px-6 py-3 text-left">Nomor HP</th>
          <th class="px-6 py-3 text-left">Status</th>
          <th class="px-6 py-3 text-center">Aksi</th>
        </tr>
      </thead>
      <tbody class="text-gray-700">
        @forelse ($drivers as $driver)
          <tr class="driver-row hover:bg-yellow-50 transition duration-300 ease-in-out" data-name="{{ strtolower($driver->name) }}" data-status="{{ strtolower($driver->status) }}">
            <td class="px-6 py-4">{{ $driver->id }}</td>
            <td class="px-6 py-4 font-medium">{{ $driver->name }}</td>
            <td class="px-6 py-4">{{ $driver->email }}</td>
            <td class="px-6 py-4">{{ $driver->phone }}</td>
            <td class="px-6 py-4">
              <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-semibold {{ $driver->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }}">
                <span class="iconify" data-icon="{{ $driver->status === 'active' ? 'ph:check-circle' : 'ph:pause-circle' }}"></span>
                {{ ucfirst($driver->status) }}
              </span>
            </td>
            <td class="px-6 py-4 text-center space-x-2">
              <a href="{{ route('admin.drivers.edit', $driver->id) }}" class="inline-flex items-center justify-center p-2 bg-blue-500 hover:bg-blue-600 text-white rounded-full transition-transform hover:scale-110" title="Edit">
                <span class="iconify text-lg" data-icon="tabler:edit"></span>
              </a>
              <form action="{{ route('admin.drivers.destroy', $driver->id) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Yakin ingin menghapus driver ini?')" class="inline-flex items-center justify-center p-2 bg-red-500 hover:bg-red-600 text-white rounded-full transition-transform hover:scale-110" title="Hapus">
                  <span class="iconify text-lg" data-icon="tabler:trash"></span>
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center px-6 py-4 text-gray-500">Belum ada driver terdaftar.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <div class="mt-4">
    {{ $drivers->links() }}
  </div>
</main>

<!-- Modal Tambah Driver -->
<div id="modalTambahDriver" class="fixed inset-0 bg-black bg-opacity-40 z-50 hidden flex items-center justify-center">
  <div class="bg-white w-full max-w-lg p-6 rounded-xl shadow-xl animate-fade-in scale-95">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-lg font-bold">Tambah Driver</h2>
      <button onclick="document.getElementById('modalTambahDriver').classList.add('hidden')" class="text-gray-400 hover:text-black text-xl">&times;</button>
    </div>
    <form action="{{ route('admin.drivers.store') }}" method="POST">
      @csrf
      <div class="space-y-4">
        <input type="text" name="name" placeholder="Nama Lengkap" class="w-full border rounded px-4 py-2 focus:ring-yellow-400 focus:border-yellow-400" required>
        <input type="email" name="email" placeholder="Email" class="w-full border rounded px-4 py-2 focus:ring-yellow-400 focus:border-yellow-400" required>
        <input type="text" name="phone" placeholder="Nomor HP" class="w-full border rounded px-4 py-2 focus:ring-yellow-400 focus:border-yellow-400" required>
        <input type="text" name="vehicle" placeholder="Kendaraan" class="w-full border rounded px-4 py-2 focus:ring-yellow-400 focus:border-yellow-400" required>
      </div>
      <div class="mt-4 text-right">
        <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded shadow">Simpan</button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
  const searchInput = document.getElementById('searchInput');
  const statusFilter = document.getElementById('statusFilter');
  const rows = document.querySelectorAll('.driver-row');

  function filterTable() {
    const keyword = searchInput.value.toLowerCase();
    const status = statusFilter.value;

    rows.forEach(row => {
      const name = row.dataset.name;
      const stat = row.dataset.status;
      const matchName = name.includes(keyword);
      const matchStatus = status === '' || stat === status;
      row.style.display = matchName && matchStatus ? '' : 'none';
    });
  }

  searchInput.addEventListener('input', filterTable);
  statusFilter.addEventListener('change', filterTable);

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      document.getElementById('modalTambahDriver').classList.add('hidden');
    }
  });
</script>
@endpush
@endsection