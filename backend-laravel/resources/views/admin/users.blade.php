@extends('layouts.app')

@section('title', 'Daftar User')

@section('content')
    <h1>Daftar User</h1>

    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div style="color: red;">{{ session('error') }}</div>
    @endif

    <table border="1" cellpadding="10" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Verifikasi</th>
                <th>Status Aktif</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->is_verified ? 'Terverifikasi' : 'Belum' }}</td>
                    <td>{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                    <td>
                        <form action="{{ route('admin.users.verify', $user->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PUT')
                            @if (!$user->is_verified)
                                <button type="submit">Verifikasi</button>
                            @else
                                <button type="submit" disabled>Terverifikasi</button>
                            @endif
                        </form>

                        <form action="{{ route('admin.users.activate', $user->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PUT')
                            <button type="submit">{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                        </form>

                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Yakin ingin menghapus user ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
