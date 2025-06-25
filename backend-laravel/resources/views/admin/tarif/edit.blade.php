@extends('layouts.app')
@section('title', 'Tarif')

@section('content')
<main class="px-6 py-10 max-w-4xl mx-auto space-y-8">
  <div
    class="rounded-3xl shadow-xl p-8 bg-white/70 dark:bg-gray-900/80 border border-yellow-100 dark:border-gray-800 backdrop-blur-sm transition-all duration-300">

    <div class="flex items-center justify-between mb-6">
      <h2 class="text-3xl font-extrabold text-yellow-600 dark:text-yellow-400 flex items-center gap-3">
        <iconify-icon icon="mdi:cash-multiple" class="text-4xl"></iconify-icon>
        Pengaturan Tarif Per KM
      </h2>
      <div
        class="inline-flex items-center gap-2 bg-yellow-100 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-200 text-sm px-4 py-1.5 rounded-full shadow">
        <iconify-icon icon="mdi:information-outline"></iconify-icon>
        Atur tarif per kilometer untuk seluruh rute
      </div>
    </div>

    @if(session('success'))
    <div
      class="mb-4 p-4 rounded-lg bg-emerald-50 dark:bg-emerald-900 border border-emerald-200 dark:border-emerald-700 text-emerald-800 dark:text-emerald-100 flex items-center gap-3 animate-fade-in transition">
      <iconify-icon icon="mdi:check-circle-outline" class="text-xl"></iconify-icon>
      <span>{{ session('success') }}</span>
    </div>
    @endif

    <form action="{{ route('admin.tarif.update') }}" method="POST" class="space-y-6 animate-slide-up">
      @csrf
      <div>
        <label for="price_per_km"
          class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Tarif Per KM</label>
        <div class="relative">
          <span
            class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400 dark:text-gray-500 text-sm">Rp</span>
          <input type="number" name="price_per_km" id="price_per_km"
            class="pl-12 pr-4 py-2 w-full bg-white dark:bg-gray-800 border border-yellow-300 dark:border-yellow-600 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 rounded-xl focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 shadow-sm transition"
            step="100" min="0" value="{{ old('price_per_km', $tarif->price_per_km ?? '') }}" required
            placeholder="Masukkan tarif per KM">
        </div>
      </div>

      <div class="flex justify-end">
        <button type="submit"
          class="inline-flex items-center px-6 py-2.5 bg-yellow-500 hover:bg-yellow-600 dark:bg-yellow-600 dark:hover:bg-yellow-500 text-white font-semibold rounded-full shadow-lg transition gap-2">
          <iconify-icon icon="mdi:content-save-outline" class="text-xl"></iconify-icon>
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
