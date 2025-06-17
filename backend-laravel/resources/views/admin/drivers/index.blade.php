@extends('layouts.app')

@section('title', 'Daftar Driver')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-800">Daftar Driver</h1>
        <a href="{{ route('admin.drivers.create') }}" class="btn btn-primary">
            + Tambah Driver
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success mb-4">{{ session('success') }}</div>
    @endif

    <div class="overflow-x-auto">
        <table class="table table-striped table-hover w-full">
            <thead class="table-primary">
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Nomor HP</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($drivers as $driver)
                    <tr>
                        <td>{{ $driver->id }}</td>
                        <td>{{ $driver->name }}</td>
                        <td>{{ $driver->email }}</td>
                        <td>{{ $driver->phone }}</td>
                        <td>
                            @if($driver->status === 'active')
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.drivers.edit', $driver->id) }}" class="btn btn-warning btn-sm">Edit</a>

                            <form action="{{ route('admin.drivers.destroy', $driver->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus driver ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-gray-500">Belum ada driver terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $drivers->links() }}
    </div>
</div>
@endsection
