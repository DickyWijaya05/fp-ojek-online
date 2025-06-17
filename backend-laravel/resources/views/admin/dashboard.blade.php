@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="container">
        <h1>Dashboard Admin</h1>
        <p>Selamat datang, {{ Auth::user()->name }} ({{ Auth::user()->email }})</p>
    </div>
@endsection
