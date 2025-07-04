@extends('layouts.app')

@section('title', 'Daftar Pengguna')

@section('content')
<main class="p-6 max-w-7xl mx-auto space-y-8">
  @php $aktifFilter = request('filter'); @endphp

  <!-- Tombol Filter -->
  <div class="flex justify-between items-center flex-wrap gap-4">
    <div class="flex gap-2 flex-wrap">
      <a href="{{ route('admin.users', ['filter' => 'customer']) }}"
        class="px-4 py-2 rounded-lg text-white shadow hover:scale-105 transition bg-yellow-500 hover:bg-yellow-600">
        Customer
      </a>
      <a href="{{ route('admin.users', ['filter' => 'driver']) }}"
        class="px-4 py-2 rounded-lg text-white shadow hover:scale-105 transition bg-yellow-500 hover:bg-yellow-600">
        Driver
      </a>
    </div>

    <!-- Search -->
    <input type="search" id="searchInput" placeholder="Cari nama/email..."
      class="px-4 py-2 w-full sm:w-72 rounded-md border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-yellow-400 transition">
  </div>

  <!-- Flash Message -->
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

  <!-- Tabel -->
  <div class="overflow-x-auto bg-white dark:bg-gray-900 rounded-xl shadow-xl transition">
    <table class="min-w-full text-sm text-left">
      <thead class="bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-200 uppercase text-xs">
        <tr>
          <th class="px-6 py-3">ID</th>
          <th class="px-6 py-3">Nama</th>
          <th class="px-6 py-3">Email</th>
          <th class="px-6 py-3">Verifikasi</th>
          <th class="px-6 py-3">Status</th>
          <th class="px-6 py-3 text-center">Aksi</th>
        </tr>
      </thead>
      <tbody id="userBody" class="divide-y divide-gray-100 dark:divide-gray-700">
        @foreach ($users as $user)
          @php
            $isDriver = $user->level_id == 2;
            $isAktif = strtolower(trim($user->status)) === 'aktif';
            $isDeactive = strtolower(trim($user->status)) === 'deactive';
            $doc = $isDriver ? \App\Models\DriverDocument::where('user_id', $user->id)->first() : null;
          @endphp
          <tr class="{{ $isDeactive ? 'bg-red-100 dark:bg-red-900' : 'hover:bg-gray-50 dark:hover:bg-gray-800' }} transition">
            <td class="px-6 py-4">{{ $user->id }}</td>
            <td class="px-6 py-4">{{ $user->name }}</td>
            <td class="px-6 py-4">{{ $user->email }}</td>
            <td class="px-6 py-4">{{ $isAktif ? '✔️' : '❌' }}</td>
            <td class="px-6 py-4 capitalize">{{ $user->status }}</td>
            <td class="px-6 py-4 text-center relative">
              <div x-data="{ open: false }" class="relative inline-block text-left">
                <button @click="open = !open" class="px-3 py-1 bg-slate-800 hover:bg-slate-700 text-white rounded-full shadow flex items-center justify-center transition">
                  <i data-lucide="menu" class="w-5 h-5"></i>
                </button>
                <div x-show="open" @click.outside="open = false" x-transition
                  class="absolute z-10 mt-2 w-44 right-0 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md shadow-xl space-y-1 p-2 text-sm">

                  @if($isDriver)
                    <form action="{{ route('admin.users.verify', $user->id) }}" method="POST">
                      @csrf @method('PUT')
                      <button class="flex items-center gap-2 w-full px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                        <i data-lucide="check-circle" class="w-4 h-4"></i> Verifikasi
                      </button>
                    </form>
                    <a href="{{ route('admin.driver.documents', $user->id) }}"
                      class="flex items-center gap-2 w-full px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded text-left">
                      <i data-lucide="file-text" class="w-4 h-4"></i> Dokumen
                    </a>
                  @endif

                  <form action="{{ route('admin.users.activate', $user->id) }}" method="POST">
                    @csrf @method('PUT')
                    <button class="flex items-center gap-2 w-full px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                      <i data-lucide="power" class="w-4 h-4"></i> {{ $isAktif ? 'Nonaktifkan' : 'Aktifkan' }}
                    </button>
                  </form>

                  <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                    @csrf @method('DELETE')
                    <button onclick="return confirm('Yakin ingin menghapus user ini?')"
                      class="flex items-center gap-2 w-full px-3 py-2 text-red-600 hover:bg-red-100 dark:hover:bg-red-800 rounded">
                      <i data-lucide="trash-2" class="w-4 h-4"></i> Hapus
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
        <i data-lucide="x" class="w-6 h-6"></i>
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
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script src="https://unpkg.com/lucide@latest"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    lucide.createIcons();
  });

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
</script>
@endpush
