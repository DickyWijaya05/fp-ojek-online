@extends('layouts.app')

@section('title', 'Edit Driver')

@section('content')
<div class="bg-white dark:bg-gray-900 p-6 rounded-lg shadow-md max-w-xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Edit Driver</h1>

    <form action="{{ route('admin.drivers.update', $driver->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <!-- Nama -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama</label>
            <input type="text" name="name" id="name" value="{{ old('name', $driver->name) }}"
                   class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-800 text-gray-800 dark:text-white 
                          border-gray-300 dark:border-gray-600 focus:ring-yellow-400 focus:border-yellow-400" required>
            @error('name')
                <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email', $driver->email) }}"
                   class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-800 text-gray-800 dark:text-white 
                          border-gray-300 dark:border-gray-600 focus:ring-yellow-400 focus:border-yellow-400" required>
            @error('email')
                <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Nomor HP -->
        <div>
            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor HP</label>
            <input type="text" name="phone" id="phone" value="{{ old('phone', $driver->phone) }}"
                   class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-800 text-gray-800 dark:text-white 
                          border-gray-300 dark:border-gray-600 focus:ring-yellow-400 focus:border-yellow-400" required>
            @error('phone')
                <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Status -->
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
            <select name="status" id="status"
                    class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-800 text-gray-800 dark:text-white 
                           border-gray-300 dark:border-gray-600 focus:ring-yellow-400 focus:border-yellow-400" required>
                <option value="active" {{ old('status', $driver->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ old('status', $driver->status) == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
            </select>
            @error('status')
                <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tombol -->
        <div class="pt-4">
            <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow">
                Update
            </button>
            <a href="{{ route('admin.drivers.index') }}"
               class="ml-2 px-5 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white hover:bg-gray-300 dark:hover:bg-gray-600 rounded-lg shadow">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
