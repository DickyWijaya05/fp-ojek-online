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
    <input type="search" id="searchInput" placeholder="Cari nama atau email..." class="w-full sm:w-72 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-yellow-400">
  </div>

  @if(session('success'))
    <div class="p-4 rounded-lg bg-green-50 border border-green-200 text-green-800 flex items-center gap-2">
      <span class="iconify text-xl" data-icon="mdi:check-circle-outline"></span>
      <span>{{ session('success') }}</span>
    </div>
  @endif

  @if(session('error'))
    <div class="p-4 rounded-lg bg-red-50 border border-red-200 text-red-800 flex items-center gap-2">
      <span class="iconify text-xl" data-icon="mdi:alert-circle-outline"></span>
      <span>{{ session('error') }}</span>
    </div>
  @endif

  <div class="overflow-x-auto bg-white rounded-xl shadow-xl">
    <table class="min-w-full text-sm text-left" id="usersTable">
      <thead class="bg-gray-50 text-gray-700 uppercase text-xs">
        <tr>
          <th class="px-6 py-3 cursor-pointer" onclick="sortTable(0)">ID</th>
          <th class="px-6 py-3 cursor-pointer" onclick="sortTable(1)">Nama</th>
          <th class="px-6 py-3 cursor-pointer" onclick="sortTable(2)">Email</th>
          <th class="px-6 py-3">Verifikasi</th>
          <th class="px-6 py-3">Status</th>
          <th class="px-6 py-3 text-center">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100" id="userBody">
        @foreach ($users as $user)
          <tr class="hover:bg-gray-50">
            <td class="px-6 py-4">{{ $user->id }}</td>
            <td class="px-6 py-4 font-medium text-gray-800">{{ $user->name }}</td>
            <td class="px-6 py-4">{{ $user->email }}</td>
            <td class="px-6 py-4">
              <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $user->is_verified ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                <span class="iconify mr-1" data-icon="{{ $user->is_verified ? 'solar:verified-check-bold' : 'ph:x-circle-bold' }}"></span>
                {{ $user->is_verified ? 'Terverifikasi' : 'Belum' }}
              </span>
            </td>
            <td class="px-6 py-4">
              <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }}">
                <span class="iconify mr-1" data-icon="{{ $user->is_active ? 'mdi:account-check-outline' : 'mdi:account-off-outline' }}"></span>
                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
              </span>
            </td>
            <td class="px-6 py-4 text-center space-y-1">
              <div class="flex justify-center flex-wrap gap-2">
                <button type="button" onclick="showDetail('{{ $user->name }}', '{{ $user->email }}', '{{ $user->is_verified ? 'Terverifikasi' : 'Belum' }}', '{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}')" class="px-3 py-1 text-xs font-medium text-white bg-indigo-500 hover:bg-indigo-600 rounded-lg flex items-center gap-1">
                  <span class="iconify" data-icon="mdi:eye-outline"></span> Detail
                </button>

                <form action="{{ route('admin.users.verify', $user->id) }}" method="POST" class="inline">
                  @csrf
                  @method('PUT')
                  <button type="submit" class="px-3 py-1 text-xs font-medium text-white rounded-lg flex items-center gap-1 {{ $user->is_verified ? 'bg-gray-300 cursor-not-allowed' : 'bg-blue-500 hover:bg-blue-600' }}" {{ $user->is_verified ? 'disabled' : '' }}>
                    <span class="iconify" data-icon="mdi:shield-check-outline"></span> Verifikasi
                  </button>
                </form>

                <form action="{{ route('admin.users.activate', $user->id) }}" method="POST" class="inline">
                  @csrf
                  @method('PUT')
                  <button type="submit" class="px-3 py-1 text-xs font-medium text-white rounded-lg flex items-center gap-1 {{ $user->is_active ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-emerald-500 hover:bg-emerald-600' }}">
                    <span class="iconify" data-icon="mdi:power"></span> {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                  </button>
                </form>

                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit" onclick="return confirm('Yakin ingin menghapus user ini?')" class="px-3 py-1 text-xs font-medium text-white bg-red-500 hover:bg-red-600 rounded-lg flex items-center gap-1">
                    <span class="iconify" data-icon="mdi:trash-outline"></span> Hapus
                  </button>
                </form>
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
  <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-xl">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-bold text-gray-800">Detail Pengguna</h2>
      <button onclick="closeDetail()" class="text-gray-600 hover:text-gray-900">
        <span class="iconify text-2xl" data-icon="mdi:close"></span>
      </button>
    </div>
    <div class="space-y-2 text-sm text-gray-700">
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
