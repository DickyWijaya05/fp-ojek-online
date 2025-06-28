@vite(['resources/css/app.css', 'resources/js/app.js'])

<!DOCTYPE html>
<html lang="en" x-data="darkMode()" x-init="init()" :class="{ 'dark': isDark }" x-cloak>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Admin Dashboard')</title>

  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- AlpineJS -->
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <!-- Lucide -->
  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          fontFamily: {
            sans: ['Inter', 'ui-sans-serif'],
          },
          colors: {
            primary: '#facc15',
            dark: '#1e293b',
          },
          boxShadow: {
            soft: '0 4px 20px rgba(0, 0, 0, 0.05)',
            neumorph: '10px 10px 20px #d4d4d4, -10px -10px 20px #ffffff',
          }
        }
      }
    }
  </script>

  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
</head>

<body
  class="text-gray-800 bg-gradient-to-br from-white via-yellow-50 to-yellow-100 dark:text-gray-100 dark:bg-gray-900">

  @if (!request()->is('admin/login'))

    <div class="h-screen flex overflow-hidden" x-data="{ sidebarOpen: true }">

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'w-72' : 'w-20'"
      class="fixed z-30 inset-y-0 left-0 transition-all duration-300 ease-in-out bg-white/70 dark:bg-gray-800/90 backdrop-blur-md shadow-xl border-r border-yellow-100 dark:border-gray-700 flex flex-col">

      <!-- Header -->
      <div class="flex items-center justify-between px-6 py-5 border-b border-yellow-100 dark:border-gray-700">
      <h1 class="text-2xl font-bold text-yellow-600 truncate transition-all dark:text-yellow-400"
        x-show="sidebarOpen">Admin Panel</h1>
      <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-yellow-500 transition">
        <i data-lucide="menu" class="w-6 h-6" x-show="!sidebarOpen"></i>
        <i data-lucide="x" class="w-6 h-6" x-show="sidebarOpen"></i>
      </button>
      </div>

      <!-- Menu -->
      <nav class="px-3 py-6 flex-1 space-y-2">
      @php
      $navItems = [
      ['route' => 'admin.dashboard', 'icon' => 'layout-dashboard', 'label' => 'Dashboard'],
      ['route' => 'admin.users', 'icon' => 'users', 'label' => 'Pengguna'],
      ['route' => 'admin.drivers.index', 'icon' => 'bike', 'label' => 'Driver'],
      ['route' => 'admin.status-driver', 'icon' => 'activity', 'label' => 'Status Driver'],
      ['route' => 'admin.tarif', 'icon' => 'wallet', 'label' => 'Tarif'],
      ['route' => 'admin.laporan-transaksi', 'icon' => 'file-text', 'label' => 'Laporan Transaksi'],
      ['route' => 'admin.laporan-aktivitas', 'icon' => 'clipboard-list', 'label' => 'Laporan Aktivitas'],
      ];
      @endphp

      @foreach ($navItems as $item)
      <a href="{{ route($item['route']) }}"
        class="group relative flex items-center gap-4 px-4 py-3 rounded-xl text-base font-semibold transition-all duration-200
      {{ request()->routeIs($item['route']) ? 'bg-yellow-300/70 text-yellow-900 shadow-md dark:bg-yellow-500/30 dark:text-yellow-100' : 'hover:bg-yellow-100 dark:hover:bg-gray-700 text-gray-800 dark:text-gray-200' }}">

        <i data-lucide="{{ $item['icon'] }}"
        class="w-6 h-6 text-yellow-600 dark:text-yellow-400 group-hover:text-yellow-800 dark:group-hover:text-yellow-300 transition duration-150"></i>

        <span x-show="sidebarOpen" class="whitespace-nowrap transition-opacity duration-200">
        {{ $item['label'] }}
        </span>

        <span x-show="!sidebarOpen"
        class="absolute left-full ml-2 px-3 py-1 rounded-md bg-yellow-600 text-white text-sm opacity-0 group-hover:opacity-100 shadow-lg z-40 transition">
        {{ $item['label'] }}
        </span>
      </a>
    @endforeach
      </nav>

      <!-- Logout -->
      <form action="{{ route('admin.logout') }}" method="POST" class="px-6 pb-6">
      @csrf
      <button type="submit"
        class="w-full flex items-center justify-center gap-2 bg-red-500 hover:bg-red-600 text-white py-3 rounded-xl shadow-md transition">
        <i data-lucide="log-out" class="w-5 h-5"></i>
        <span x-show="sidebarOpen">Logout</span>
      </button>
      </form>
    </aside>

    <!-- Main Section -->
    <div :class="sidebarOpen ? 'ml-72' : 'ml-20'" class="flex-1 flex flex-col transition-all duration-300 ease-in-out">

      <!-- Navbar -->
      <header
      class="sticky top-0 z-10 bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm border-b border-yellow-200 dark:border-gray-700 px-6 py-4 shadow-sm flex justify-between items-center">
      <div class="flex items-center gap-4">
        <button @click="sidebarOpen = !sidebarOpen"
        class="text-yellow-600 hover:text-yellow-700 dark:text-yellow-400 sm:hidden">
        <i data-lucide="menu" class="w-6 h-6"></i>
        </button>
        <h2 class="text-xl font-bold text-yellow-700 dark:text-yellow-300">@yield('title', 'Dashboard')</h2>
      </div>
      <div class="flex items-center gap-4">
        @php
      $admin = Auth::user();
    @endphp
        <button @click="toggle()"
        class="p-2 rounded-md bg-yellow-100 hover:bg-yellow-200 dark:bg-gray-700 dark:hover:bg-gray-600 transition">
        <i data-lucide="moon" x-show="!isDark" class="w-5 h-5 text-yellow-600 dark:text-gray-300"></i>
        <i data-lucide="sun" x-show="isDark" class="w-5 h-5 text-yellow-400 dark:text-yellow-200"></i>
        </button>
        @php
      $admin = Auth::user();
    @endphp

        @if ($admin && $admin->level_id == 1)
      <span class="text-sm text-gray-600 dark:text-gray-300 hidden md:block">
      Hai, {{ $admin->name }}
      </span>
      @else
      <span class="text-sm text-gray-600 dark:text-gray-300 hidden md:block">
      Hai, Admin
      </span>
      @endif

        <a href="{{ route('admin.profile') }}" title="Lihat Profil Admin">
        <img
          src="{{ $admin->photo_url ? asset('storage/avatars/' . $admin->photo_url) : asset('images/Logo.png') }}"
          class="w-11 h-11 rounded-full border-2 border-yellow-500 shadow-sm hover:scale-105 transition duration-150 object-cover"
          alt="Admin Avatar">
        </a>

      </div>
      </header>

      <!-- Content -->
      <main
      class="flex-1 px-8 py-6 overflow-y-auto bg-gradient-to-br from-white via-yellow-50 to-yellow-100 dark:bg-gradient-to-br dark:from-gray-800 dark:via-gray-900 dark:to-gray-950">
      @yield('content')
      </main>
    </div>
    </div>

  @else

    <!-- Login Layout -->
    <main class="min-h-screen flex items-center justify-center bg-yellow-50 dark:bg-gray-900 px-4">
    @yield('content')
    </main>

  @endif

  <script>lucide.createIcons();</script>

  <!-- SweetAlert2 CDN -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  <audio id="success-sound" src="{{ asset('sounds/success.mp3') }}"></audio>

  <script>
    const showPopup = (icon, title, text, color) => {
      Swal.fire({
        icon: icon,
        title: title,
        text: text,
        background: '#1f2937',
        color: '#facc15',
        iconColor: color,
        timer: 4000, // ⏱️ popup otomatis tertutup dalam 5 detik
        timerProgressBar: true, // ✅ progress bar di bawah popup
        showConfirmButton: false, // ❌ hilangkan tombol "Lanjutkan"
        showClass: {
          popup: 'animate__animated animate__fadeInDown'
        },
        hideClass: {
          popup: 'animate__animated animate__fadeOutUp'
        },
        customClass: {
          popup: 'rounded-xl shadow-xl',
          title: 'text-lg font-semibold',
          htmlContainer: 'text-sm'
        }
      });
    };


    @if (session('success'))
    window.addEventListener('DOMContentLoaded', () => {
      const sound = document.getElementById('success-sound');
      if (sound) {
      sound.play().catch(err => {
        console.warn('Audio autoplay diblokir, menunggu gesture user');
        // Bisa tambahkan fallback jika ingin
      });
      }

      showPopup('success', 'Selamat Datang', '{{ session('success') }}', '#4ade80');
    });
  @endif

  </script>

  <!-- Dark Mode Script -->
  <script>
    function darkMode() {
      return {
        isDark: localStorage.getItem('theme') === 'dark',
        sidebarOpen: true,
        init() {
          this.applyTheme();
        },
        toggle() {
          this.isDark = !this.isDark;
          localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
          this.applyTheme();
        },
        applyTheme() {
          document.documentElement.classList.toggle('dark', this.isDark);
          document.querySelector('meta[name="theme-color"]')?.setAttribute('content', this.isDark ? '#111827' : '#ffffff');
        }
      }
    }
  </script>


</body>

</html>