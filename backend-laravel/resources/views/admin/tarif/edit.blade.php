@extends('layouts.app')
@section('title', 'Tarif')

@section('content')
<main class="p-6 max-w-4xl mx-auto space-y-8">
  <div class="bg-gradient-to-br from-white via-yellow-50 to-yellow-100 rounded-2xl shadow-2xl p-8">
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-3xl font-extrabold text-yellow-600 flex items-center gap-3">
        <span class="iconify text-4xl" data-icon="mdi:cash-multiple"></span>
        Pengaturan Tarif Per KM
      </h2>
      <span class="inline-flex items-center gap-2 bg-yellow-100 text-yellow-700 text-sm px-4 py-1 rounded-full shadow">
        <span class="iconify" data-icon="mdi:information-outline"></span>
        Atur tarif per kilometer untuk seluruh rute
      </span>
    </div>

    @if(session('success'))
      <div class="mb-4 p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center gap-2 animate-fade-in">
        <span class="iconify text-xl" data-icon="mdi:check-circle-outline"></span>
        <span>{{ session('success') }}</span>
      </div>
    @endif

    <form action="{{ route('admin.tarif.update') }}" method="POST" class="space-y-6 animate-slide-up">
      @csrf
      <div>
        <label for="price_per_km" class="block text-sm font-bold text-gray-700 mb-2">Tarif Per KM</label>
        <div class="relative">
          <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400 text-sm">Rp</span>
          <input
            type="number"
            name="price_per_km"
            id="price_per_km"
            class="pl-12 pr-4 py-2 w-full border border-yellow-300 rounded-xl focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 shadow-sm transition"
            step="100"
            min="0"
            value="{{ old('price_per_km', $tarif->price_per_km ?? '') }}"
            required
            placeholder="Masukkan tarif per KM"
          >
        </div>
      </div>

      <div class="flex justify-end">
        <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-full shadow-lg transition gap-2">
          <span class="iconify text-xl" data-icon="mdi:content-save-outline"></span>
          Simpan Tarif
        </button>
      </div>
    </form>
  </div>
</main>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const alertBox = document.querySelector('.animate-fade-in');
    if (alertBox) {
      setTimeout(() => alertBox.classList.add('opacity-0'), 5000);
    }
  });
</script>
@endpush
@endsection