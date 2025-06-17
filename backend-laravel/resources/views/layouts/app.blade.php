<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'My Laravel App')</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    @stack('styles')
</head>

<body class="bg-gray-50">

    @if (!request()->is('admin/login'))
        <div class="flex min-h-screen">
            <!-- Sidebar -->
            <aside class="w-64 bg-white shadow-lg rounded-r-lg flex flex-col px-6 py-8 border-r border-gray-200">
                <!-- Brand / Title -->
                <h2 class="text-3xl font-semibold text-gray-800 text-center mb-10 tracking-wide select-none">
                    Admin Panel
                </h2>

                <!-- Navigation Links -->
                <nav class="flex-1 flex flex-col space-y-2">
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 font-medium hover:bg-indigo-100 hover:text-indigo-700 transition-colors
                                  {{ request()->is('admin/dashboard') ? 'bg-indigo-100 text-indigo-700 font-semibold shadow' : '' }}">
                        <span class="text-xl">📊</span> Dashboard
                    </a>
                    <a href="{{ route('admin.users') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 font-medium hover:bg-indigo-100 hover:text-indigo-700 transition-colors
                                  {{ request()->is('admin/users') ? 'bg-indigo-100 text-indigo-700 font-semibold shadow' : '' }}">
                        <span class="text-xl">👥</span> Manajemen Pengguna
                    </a>
                    <a href="{{ route('admin.drivers.index') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 font-medium hover:bg-indigo-100 hover:text-indigo-700 transition-colors
                                  {{ request()->is('admin/drivers') ? 'bg-indigo-100 text-indigo-700 font-semibold shadow' : '' }}">
                        <span class="text-xl">🛵</span> Manajemen Driver
                    </a>
                    <a href="{{ route('admin.status-driver') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 font-medium hover:bg-indigo-100 hover:text-indigo-700 transition-colors
                                  {{ request()->is('admin/status-driver') ? 'bg-indigo-100 text-indigo-700 font-semibold shadow' : '' }}">
                        <span class="text-xl">🔄</span> Status Driver
                    </a>
                    <a href="{{ route('admin.tarif') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 font-medium hover:bg-indigo-100 hover:text-indigo-700 transition-colors
                                  {{ request()->is('admin/tarif') ? 'bg-indigo-100 text-indigo-700 font-semibold shadow' : '' }}">
                        <span class="text-xl">💰</span> Tarif
                    </a>
                    <a href="{{ route('admin.laporan-transaksi') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 font-medium hover:bg-indigo-100 hover:text-indigo-700 transition-colors
                                  {{ request()->is('admin/laporan-transaksi') ? 'bg-indigo-100 text-indigo-700 font-semibold shadow' : '' }}">
                        <span class="text-xl">📑</span> Laporan Transaksi
                    </a>
                    <a href="{{ route('admin.laporan-aktivitas') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 font-medium hover:bg-indigo-100 hover:text-indigo-700 transition-colors
                                  {{ request()->is('admin/laporan-aktivitas') ? 'bg-indigo-100 text-indigo-700 font-semibold shadow' : '' }}">
                        <span class="text-xl">📋</span> Laporan Aktivitas
                    </a>
                </nav>

                <!-- Logout Button -->
                <form action="{{ route('admin.logout') }}" method="POST" class="mt-8">
                    @csrf
                    <button type="submit"
                        class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded-lg font-semibold transition-colors shadow">
                        🚪 Logout
                    </button>
                </form>
            </aside>

            <!-- Main Content Area -->
            <main class="flex-1 p-8 bg-gray-50 overflow-auto">
                @yield('content')
            </main>
        </div>
    @else
        {{-- Login Page Layout --}}
        <main class="min-h-screen flex items-center justify-center p-4 bg-gray-50">
            @yield('content')
        </main>
    @endif

    @stack('scripts')
</body>

</html>