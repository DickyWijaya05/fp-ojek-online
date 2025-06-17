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
    <div class="min-h-screen">
        <!-- Main Content Area tanpa sidebar -->
        <main class="p-8 bg-gray-50 overflow-auto">
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
