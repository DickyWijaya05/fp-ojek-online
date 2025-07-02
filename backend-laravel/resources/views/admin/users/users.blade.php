@extends('layouts.app')

@section('title', 'Daftar User')


@section('content')
<main class="p-6 max-w-7xl mx-auto space-y-8">
  <div class="flex justify-between items-center flex-wrap gap-4">
    <button
      class="px-5 py-2.5 text-white rounded-lg shadow-lg hover:scale-105 transition flex items-center gap-2"
      style="background-image: linear-gradient(to right, #facc15, #eab308);">
      <span class="iconify text-lg" data-icon="material-symbols:person-add-rounded"></span>
      Tambah User
    </button>

    <input type="search" id="searchInput" placeholder="Cari nama atau email..."
      class="w-full sm:w-72 px-4 py-2 border rounded-md focus:outline-none focus:ring focus:border-yellow-400
             text-gray-800 dark:text-white dark:bg-gray-800 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 transition">
  </div>

  @if(session('success'))
  <div class="p-4 rounded-lg bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-600 text-green-800 dark:text-green-100 flex items-center gap-2 transition">
    {{ session('success') }}
  </div>
  @endif

  @if(session('error'))
  <div class="p-4 rounded-lg bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-600 text-red-800 dark:text-red-100 flex items-center gap-2 transition">
    {{ session('error') }}
  </div>
  @endif

  <div class="overflow-x-auto bg-white dark:bg-gray-900 rounded-xl shadow-xl transition">
    <table class="min-w-full text-sm text-left">
      <thead class="bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-200 uppercase text-xs">
        <tr>
          <th class="px-6 py-3 cursor-pointer" onclick="sortTable(0)">ID</th>
          <th class="px-6 py-3 cursor-pointer" onclick="sortTable(1)">Nama</th>
          <th class="px-6 py-3 cursor-pointer" onclick="sortTable(2)">Email</th>
          <th class="px-6 py-3">Verifikasi</th>
          <th class="px-6 py-3">Status</th>
          <th class="px-6 py-3 text-center">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100 dark:divide-gray-700" id="userBody">
        @foreach ($users as $user)
          <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
            <td class="px-6 py-4 text-gray-800 dark:text-gray-100">{{ $user->id }}</td>
            <td class="px-6 py-4 font-medium text-gray-800 dark:text-white">{{ $user->name }}</td>
            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $user->email }}</td>
            <td class="px-6 py-4">
              <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium 
                {{ $user->is_verified ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-800 dark:text-emerald-200' : 'bg-red-100 text-red-700 dark:bg-red-800 dark:text-red-200' }}">
                <span class="iconify mr-1" data-icon="{{ $user->is_verified ? 'solar:verified-check-bold' : 'ph:x-circle-bold' }}"></span>
                {{ $user->is_verified ? 'Terverifikasi' : 'Belum' }}
              </span>
            </td>
            <td class="px-6 py-4">
              <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium 
                {{ $user->is_active ? 'bg-green-100 text-green-700 dark:bg-green-800 dark:text-green-200' : 'bg-gray-200 text-gray-600 dark:bg-gray-700 dark:text-gray-300' }}">
                <span class="iconify mr-1" data-icon="{{ $user->is_active ? 'mdi:account-check-outline' : 'mdi:account-off-outline' }}"></span>
                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
              </span>
         <td class="px-6 py-4 text-center">
  <div class="relative w-24 h-24 mx-auto grid place-items-center">

    {{-- Detail (Atas) --}}
    <div class="absolute top-0 left-1/2 transform -translate-x-1/2">
      <button 
        type="button"
        onclick="showDetail('{{ $user->name }}', '{{ $user->email }}', '{{ $user->is_verified ? 'Terverifikasi' : 'Belum' }}', '{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}')" 
        class="p-2 rounded-full bg-indigo-600 hover:bg-indigo-700 text-white shadow-lg hover:shadow-indigo-500/50 transition-all duration-300 hover:scale-110 backdrop-blur-sm"
        title="Lihat Detail">
        <i data-lucide="eye" class="w-4 h-4"></i>
      </button>
    </div>

    {{-- Verifikasi (Kiri) --}}
    <div class="absolute left-0 top-1/2 transform -translate-y-1/2">
      <form action="{{ route('admin.users.verify', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <button 
          type="submit"
          {{ $user->is_verified ? 'disabled' : '' }}
          class="p-2 rounded-full text-white shadow-lg backdrop-blur-sm transition-all duration-300 hover:scale-110
          {{ $user->is_verified ? 'bg-gray-400 dark:bg-gray-600 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700 hover:shadow-blue-400/50' }}"
          title="Verifikasi Akun">
          <i data-lucide="shield-check" class="w-4 h-4"></i>
        </button>
      </form>
    </div>

    {{-- Aktifkan / Nonaktifkan (Kanan) --}}
    <div class="absolute right-0 top-1/2 transform -translate-y-1/2">
      <form action="{{ route('admin.users.activate', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <button 
          type="submit" 
          class="p-2 rounded-full text-white shadow-lg hover:scale-110 transition-all duration-300 backdrop-blur-sm
          {{ $user->is_active ? 'bg-yellow-500 hover:bg-yellow-600 hover:shadow-yellow-400/50' : 'bg-emerald-500 hover:bg-emerald-600 hover:shadow-emerald-400/50' }}"
          title="{{ $user->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}">
          <i data-lucide="power" class="w-4 h-4"></i>
        </button>
      </form>
    </div>

    {{-- Hapus (Bawah) --}}
    <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2">
      <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
        @csrf
        @method('DELETE')
        <button 
          type="submit" 
          onclick="return confirm('Yakin ingin menghapus user ini?')" 
          class="p-2 rounded-full bg-red-600 hover:bg-red-700 text-white shadow-lg hover:scale-110 hover:shadow-red-400/50 transition-all duration-300 backdrop-blur-sm"
          title="Hapus User">
          <i data-lucide="trash-2" class="w-4 h-4"></i>
        </button>
      </form>
    </div>

  </div>
</td>

          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</main>

<!-- Modal -->
<div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
  <div class="bg-white dark:bg-gray-900 rounded-lg p-6 w-full max-w-md shadow-xl transition">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-bold text-gray-800 dark:text-white">Detail Pengguna</h2>
      <button onclick="closeDetail()" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
        <span class="iconify text-2xl" data-icon="mdi:close"></span>
      </button>
    </div>
    <div class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
      <p><strong>Nama:</strong> <span id="detailName"></span></p>
      <p><strong>Email:</strong> <span id="detailEmail"></span></p>
      <p><strong>Verifikasi:</strong> <span id="detailVerifikasi"></span></p>
      <p><strong>Status Aktif:</strong> <span id="detailStatus"></span></p>
    </div>
  </div>
</div>
@push('scripts')
<script>
  function showDetail(name, email, verifikasi, status) {
    document.getElementById('detailName').innerText = name;
    document.getElementById('detailEmail').innerText = email;
    document.getElementById('detailVerifikasi').innerText = verifikasi;
    document.getElementById('detailStatus').innerText = status;
    document.getElementById('detailModal').classList.remove('hidden');
    document.getElementById('detailModal').classList.add('flex');
  }

  function closeDetail() {
    document.getElementById('detailModal').classList.add('hidden');
    document.getElementById('detailModal').classList.remove('flex');
  }

  document.getElementById('searchInput').addEventListener('keyup', function () {
    const value = this.value.toLowerCase();
    const rows = document.querySelectorAll('#userBody tr');
    rows.forEach(row => {
      row.style.display = row.innerText.toLowerCase().includes(value) ? '' : 'none';
    });
  });

  function sortTable(n) {
    const table = document.getElementById("usersTable");
    let switching = true, dir = "asc", switchcount = 0;
    while (switching) {
      switching = false;
      const rows = table.rows;
      for (let i = 1; i < (rows.length - 1); i++) {
        let shouldSwitch = false;
        const x = rows[i].getElementsByTagName("TD")[n];
        const y = rows[i + 1].getElementsByTagName("TD")[n];
        if ((dir === "asc" && x.innerText.toLowerCase() > y.innerText.toLowerCase()) ||
            (dir === "desc" && x.innerText.toLowerCase() < y.innerText.toLowerCase())) {
          shouldSwitch = true;
          break;
        }
      }
      if (shouldSwitch) {
        rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
        switching = true;
        switchcount++;
      } else {
        if (switchcount === 0 && dir === "asc") {
          dir = "desc";
          switching = true;
        }
      }
    }
  }
</script>
@endpush
@endsection
