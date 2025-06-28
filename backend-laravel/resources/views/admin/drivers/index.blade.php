@extends('layouts.app')
@section('title', 'Drivers')

@section('content')
<main class="p-6 max-w-7xl mx-auto space-y-6">
  <!-- Header -->
  <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
    <!-- Tombol Tambah Driver -->
    <button onclick="document.getElementById('modalTambahDriver').classList.remove('hidden')" 
            class="inline-flex items-center gap-2 px-4 py-2 
                   bg-yellow-500 hover:bg-yellow-600 
                   text-white rounded-lg shadow 
                   transition-transform hover:scale-105">
      <span class="iconify text-lg" data-icon="tabler:plus"></span>
      Tambah Driver
    </button>

    <!-- Search & Filter -->
    <div class="flex items-center gap-2">
      <div class="relative">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
          <span class="iconify text-gray-400 dark:text-gray-500" data-icon="tabler:search"></span>
        </span>
        <input id="searchInput" type="text" placeholder="Cari driver..."
               class="pl-10 pr-4 py-2 w-full sm:w-64 border rounded-lg shadow-sm
                      focus:ring-yellow-400 focus:border-yellow-400
                      bg-white dark:bg-gray-800 text-gray-800 dark:text-white
                      border-gray-300 dark:border-gray-600
                      placeholder:text-gray-500 dark:placeholder:text-gray-400" />
      </div>
      <select id="statusFilter"
              class="px-4 py-2 border rounded-lg 
                     focus:ring-yellow-400 focus:border-yellow-400
                     bg-white dark:bg-gray-800 text-gray-800 dark:text-white
                     border-gray-300 dark:border-gray-600">
        <option value="">Semua Status</option>
        <option value="active">Aktif</option>
        <option value="nonactive">Nonaktif</option>
      </select>
    </div>
  </div>

  <!-- Alert Success -->
  @if(session('success'))
  <div class="p-4 rounded bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 text-green-800 dark:text-green-100 flex items-center gap-2 animate-fade-in">
    <span class="iconify" data-icon="mdi:check-circle-outline"></span>
    {{ session('success') }}
  </div>
  @endif

  <!-- Table -->
  <div class="overflow-x-auto rounded-xl shadow-lg bg-white dark:bg-gray-900 animate-fade-in">
    <table id="driverTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
      <thead class="bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300">
        <tr>
          <th class="px-6 py-3 text-left">ID</th>
          <th class="px-6 py-3 text-left">Nama</th>
          <th class="px-6 py-3 text-left">Email</th>
          <th class="px-6 py-3 text-left">Nomor HP</th>
          <th class="px-6 py-3 text-left">Status</th>
          <th class="px-6 py-3 text-center">Aksi</th>
        </tr>
      </thead>
      <tbody class="text-gray-800 dark:text-gray-200">
        @forelse ($drivers as $driver)
        <tr class="driver-row hover:bg-yellow-50 dark:hover:bg-gray-700 transition duration-300 ease-in-out"
            data-name="{{ strtolower($driver->name) }}" data-status="{{ strtolower($driver->status) }}">
          <td class="px-6 py-4">{{ $driver->id }}</td>
          <td class="px-6 py-4 font-medium">{{ $driver->name }}</td>
          <td class="px-6 py-4">{{ $driver->email }}</td>
          <td class="px-6 py-4">{{ $driver->phone }}</td>
          <td class="px-6 py-4">
            <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-semibold 
                         {{ $driver->status === 'active' 
                             ? 'bg-green-100 dark:bg-green-800 text-green-700 dark:text-green-100' 
                             : 'bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-100' }}">
              <span class="iconify" data-icon="{{ $driver->status === 'active' ? 'ph:check-circle' : 'ph:pause-circle' }}"></span>
              {{ ucfirst($driver->status) }}
            </span>
          </td>
       <td class="px-6 py-4 text-center space-x-2">
  <!-- Tombol Edit -->
  <a href="{{ route('admin.drivers.edit', $driver->id) }}"
     class="inline-flex items-center justify-center w-10 h-10 rounded-full
            bg-gradient-to-tr from-sky-500 to-blue-600
            shadow-md hover:shadow-lg hover:scale-110 transform
            transition duration-300 ease-in-out relative group"
     title="Edit">
    <!-- Icon Edit -->
    <svg xmlns="http://www.w3.org/2000/svg"
         class="w-5 h-5 text-gray-800 dark:text-white"
         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
      <path d="M12 20h9" />
      <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z" />
    </svg>
    <!-- Tooltip -->
    <div class="absolute bottom-full mb-2 hidden group-hover:block px-2 py-1 text-xs rounded shadow
                bg-white text-gray-800 dark:bg-gray-800 dark:text-gray-100">
      Edit
    </div>
  </a>

  <!-- Tombol Hapus -->
  <form action="{{ route('admin.drivers.destroy', $driver->id) }}" method="POST" class="inline-block">
    @csrf
    @method('DELETE')
    <button type="submit"
            onclick="return confirm('Yakin ingin menghapus driver ini?')"
            class="inline-flex items-center justify-center w-10 h-10 rounded-full
                   bg-gradient-to-tr from-rose-500 to-red-600
                   shadow-md hover:shadow-lg hover:scale-110 transform
                   transition duration-300 ease-in-out relative group"
            title="Hapus">
      <!-- Icon Delete -->
      <svg xmlns="http://www.w3.org/2000/svg"
           class="w-5 h-5 text-gray-800 dark:text-white"
           fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path d="M3 6h18" />
        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3-2V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
        <line x1="10" y1="11" x2="10" y2="17" />
        <line x1="14" y1="11" x2="14" y2="17" />
      </svg>
      <!-- Tooltip -->
      <div class="absolute bottom-full mb-2 hidden group-hover:block px-2 py-1 text-xs rounded shadow
                  bg-white text-gray-800 dark:bg-gray-800 dark:text-gray-100">
        Hapus
      </div>
    </button>
  </form>
</td>

        </tr>
        @empty
        <tr>
          <td colspan="6" class="text-center px-6 py-4 text-gray-500 dark:text-gray-400">Belum ada driver terdaftar.</td>
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
  <div class="bg-white dark:bg-gray-900 w-full max-w-lg p-6 rounded-xl shadow-xl animate-fade-in scale-95">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-lg font-bold text-gray-900 dark:text-white">Tambah Driver</h2>
      <button onclick="document.getElementById('modalTambahDriver').classList.add('hidden')" class="text-gray-400 hover:text-black dark:hover:text-white text-xl">&times;</button>
    </div>
    <form action="{{ route('admin.drivers.store') }}" method="POST">
      @csrf
      <div class="space-y-4">
        @foreach (['name' => 'Nama Lengkap', 'email' => 'Email', 'phone' => 'Nomor HP', 'vehicle' => 'Kendaraan'] as $field => $placeholder)
        <input type="{{ $field === 'email' ? 'email' : 'text' }}" name="{{ $field }}" placeholder="{{ $placeholder }}"
               class="w-full border rounded px-4 py-2
                      focus:ring-yellow-400 focus:border-yellow-400
                      bg-white dark:bg-gray-800 text-gray-800 dark:text-white
                      border-gray-300 dark:border-gray-600
                      placeholder:text-gray-500 dark:placeholder:text-gray-400"
               required>
        @endforeach
      </div>
      <div class="mt-4 text-right">
        <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded shadow">
          Simpan
        </button>
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
