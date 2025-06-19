@extends('layouts.app') {{-- Ganti dengan layout yang kamu gunakan --}}

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Pengaturan Tarif Per KM</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.tarif.update') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="price_per_km">Tarif Per KM (Rp)</label>
            <input
                type="number"
                name="price_per_km"
                id="price_per_km"
                class="form-control"
                step="100"
                min="0"
                value="{{ old('price_per_km', $tarif->price_per_km ?? '') }}"
                required
            >
        </div>
        <button type="submit" class="btn btn-primary mt-3">💾 Simpan Tarif</button>
    </form>
</div>
@endsection
