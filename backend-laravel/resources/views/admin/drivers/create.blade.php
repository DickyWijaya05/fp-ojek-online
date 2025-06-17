@extends('layouts.app')

@section('title', 'Tambah Driver')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow-md max-w-xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Tambah Driver Baru</h1>

        <form action="{{ route('admin.drivers.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="form-label fw-bold">Nama</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                    class="form-control @error('name') is-invalid @enderror" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="email" class="form-label fw-bold">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                    class="form-control @error('email') is-invalid @enderror" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="phone" class="form-label fw-bold">Nomor HP</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                    class="form-control @error('phone') is-invalid @enderror" required>
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="status" class="form-label fw-bold">Status</label>
                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="{{ route('admin.drivers.index') }}" class="btn btn-secondary ms-2">Batal</a>
        </form>
    </div>
@endsection