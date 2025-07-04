@extends('layouts.app')

@section('title', 'Dokumen Driver')

@section('content')
<main class="max-w-6xl mx-auto p-6 space-y-8">
  <div class="bg-white dark:bg-gray-900 shadow-xl rounded-2xl p-6 border border-gray-200 dark:border-gray-700 transition">

    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-extrabold text-gray-800 dark:text-white tracking-tight">
        📄 Dokumen Driver: {{ $driver->name }}
      </h1>
      <a href="{{ route('admin.users') }}"
         class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 rounded-lg shadow hover:shadow-md hover:bg-gray-200 dark:hover:bg-gray-700 transition">
        ← Kembali ke Daftar
      </a>
    </div>

    @if ($document)
      <div class="grid sm:grid-cols-2 gap-6">
        @foreach([
          'Pas Foto' => $document->pas_photo,
          'KTP' => $document->ktp,
          'SIM' => $document->sim,
          'STNK' => $document->stnk,
          'Foto Kendaraan' => $document->vehicle_photo,
          'Selfie dengan KTP' => $document->selfie_ktp,
        ] as $label => $path)
          <div class="bg-gray-50 dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="p-4 border-b border-gray-100 dark:border-gray-700">
              <h3 class="font-semibold text-gray-700 dark:text-gray-200">{{ $label }}</h3>
            </div>
            <div class="p-4">
              @if ($path)
                <img src="{{ asset('storage/' . $path) }}" alt="{{ $label }}"
                     class="w-full h-auto rounded-lg transition hover:scale-105 duration-300 shadow-lg">
              @else
                <p class="text-red-500">Tidak tersedia.</p>
              @endif
            </div>
          </div>
        @endforeach
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 mt-8 gap-4 bg-gray-50 dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
        <p><strong class="text-gray-700 dark:text-gray-200">Plat Nomor:</strong> {{ $document->plate_number }}</p>
        <p><strong class="text-gray-700 dark:text-gray-200">Kendaraan:</strong> {{ $document->vehicle_name }} ({{ $document->vehicle_color }})</p>
        <p><strong class="text-gray-700 dark:text-gray-200">Status Dokumen:</strong>
          <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full
                {{ $document->status === 'approved' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-300' :
                   ($document->status === 'rejected' ? 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' :
                   'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300') }}">
            {{ ucfirst($document->status) }}
          </span>
        </p>
      </div>
    @else
      <div class="text-red-600 dark:text-red-400 font-medium">
        Belum ada dokumen yang diunggah oleh driver ini.
      </div>
    @endif

  </div>
</main>
@endsection
