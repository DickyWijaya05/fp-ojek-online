<!DOCTYPE html>
<html lang="en" x-data="{ loading: true }" x-init="setTimeout(() => loading = false, 1500)" x-cloak>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login Admin</title>

  <!-- Tailwind & Alpine -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#facc15',
            dark: '#1e1e1e',
          }
        }
      }
    }
  </script>

  <!-- Animate.css -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

  <style>
    body {
      background: radial-gradient(circle at top left, #fef3c7, transparent),
        radial-gradient(circle at bottom right, #fde68a, transparent),
        linear-gradient(135deg, #fff7ed, #fef9c3);
      background-repeat: no-repeat;
      background-size: cover;
    }

    [x-cloak] {
      display: none;
    }

    .animate-spin-reverse {
      animation: spin-reverse 2s linear infinite;
    }

    .animate-spin-slow {
      animation: spin 3s linear infinite;
    }

    @keyframes spin-reverse {
      0% {
        transform: rotate(360deg);
      }

      100% {
        transform: rotate(0deg);
      }
    }
  </style>
</head>

<body class="min-h-screen flex items-center justify-center px-4">

  <!-- LOADING SCREEN -->
  <div x-show="loading" x-transition.opacity
    class="fixed inset-0 z-50 flex items-center justify-center bg-[#141414] backdrop-blur-sm">
    <div class="relative w-32 h-32">
      <!-- Ring 1 - Spin cepat -->
      <div
        class="absolute inset-0 rounded-full border-4 border-t-transparent border-yellow-400 animate-spin drop-shadow-glow">
      </div>

      <!-- Ring 2 - Spin lambat -->
      <div
        class="absolute inset-2 rounded-full border-4 border-t-transparent border-yellow-500 animate-spin-slow drop-shadow-glow">
      </div>

      <!-- Ring 3 - Spin reverse -->
      <div
        class="absolute inset-4 rounded-full border-4 border-t-transparent border-amber-300 animate-spin-reverse drop-shadow-glow-soft">
      </div>

      <!-- Teks Loading -->
      <div class="absolute inset-0 flex items-center justify-center">
        <span
          class="text-lg font-extrabold text-yellow-200 tracking-wider animate-pulse bg-gradient-to-r from-yellow-300 via-yellow-100 to-yellow-300 bg-clip-text text-transparent">
          GO-CABS
        </span>
      </div>
    </div>
  </div>

  <!-- Custom Animations & Effects -->
  <style>
    .animate-spin-slow {
      animation: spin 3s linear infinite;
    }

    .animate-spin-reverse {
      animation: spin-reverse 2.5s linear infinite;
    }

    @keyframes spin-reverse {
      0% {
        transform: rotate(360deg);
      }

      100% {
        transform: rotate(0deg);
      }
    }

    .drop-shadow-glow {
      filter: drop-shadow(0 0 6px rgba(253, 224, 71, 0.6));
      /* yellow-400 */
    }

    .drop-shadow-glow-soft {
      filter: drop-shadow(0 0 4px rgba(251, 191, 36, 0.5));
      /* amber-300 */
    }
  </style>


  <!-- ✨ Login Content -->
  <div x-show="!loading" x-transition.opacity.duration.700ms
    class="relative z-10 w-full max-w-md p-8 sm:p-10 bg-white/60 backdrop-blur-2xl border border-yellow-200 rounded-3xl shadow-2xl">

    <!-- Decorative Circles -->
    <div class="absolute -top-16 -left-16 w-44 h-44 bg-yellow-200 opacity-30 rounded-full blur-3xl animate-pulse"></div>
    <div class="absolute -bottom-16 -right-16 w-44 h-44 bg-yellow-300 opacity-30 rounded-full blur-3xl animate-pulse">
    </div>

    <!-- Logo -->
    <div class="relative z-10 flex justify-center mb-6">
      <img src="{{ asset('images/Logo.png') }}" alt="Logo Admin"
        class="h-28 w-28 object-contain rounded-full border-4 border-yellow-400 bg-white p-2 shadow-lg transition-transform duration-300 hover:scale-105">
    </div>

    <!-- Title -->
    <h2 class="text-3xl font-extrabold text-center text-yellow-600 mb-8 tracking-wide relative z-10">Admin Login</h2>

    <!-- Error Message -->
    @if(session('error'))
    <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 text-sm z-10 relative">
      {{ session('error') }}
    </div>
  @endif

    <!-- Form -->
    <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-6 relative z-10">
      @csrf
      <div class="relative">
        <input type="email" name="email" id="email" required
          class="peer w-full px-4 pt-5 pb-2 bg-white/70 border border-yellow-300 text-gray-800 rounded-lg placeholder-transparent focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 transition"
          placeholder="Email" />
        <label for="email"
          class="absolute left-4 top-2 text-gray-600 text-sm transition-all peer-placeholder-shown:top-4 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-focus:top-2 peer-focus:text-sm peer-focus:text-yellow-600">
          Email Admin
        </label>
      </div>

      <div class="relative">
        <input type="password" name="password" id="password" required
          class="peer w-full px-4 pt-5 pb-2 bg-white/70 border border-yellow-300 text-gray-800 rounded-lg placeholder-transparent focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 transition"
          placeholder="Password" />
        <label for="password"
          class="absolute left-4 top-2 text-gray-600 text-sm transition-all peer-placeholder-shown:top-4 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-focus:top-2 peer-focus:text-sm peer-focus:text-yellow-600">
          Password
        </label>
      </div>

      <button type="submit"
        class="w-full py-3 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold rounded-xl shadow-md hover:shadow-xl transition duration-300">
        Masuk
      </button>
    </form>

    <!-- Footer -->
    <div class="text-center mt-6 relative z-10">
      <p class="text-sm text-gray-600">© {{ date('Y') }} Admin Panel • Semua hak cipta dilindungi</p>
    </div>
  </div>


  <audio id="success-sound" src="{{ asset('sounds/success.mp3') }}"></audio>
  <audio id="error-sound" src="{{ asset('sounds/error.mp3') }}"></audio>


  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    @if (session('error'))
    // Putar suara error
    const sound = document.getElementById('error-sound');
    if (sound) sound.play();

    // Tampilkan popup gagal
    Swal.fire({
      icon: 'error',
      title: 'Gagal!',
      text: @json(session('error')),
      background: '#1f2937',
      color: '#facc15',
      iconColor: '#f87171',
      timer: 5000, // 5 detik
      timerProgressBar: true,
      showConfirmButton: false, // Tidak perlu klik tombol
      customClass: {
      popup: 'rounded-xl shadow-xl',
      title: 'text-lg font-semibold',
      htmlContainer: 'text-sm'
      }
    });
  @endif
  </script>


</body>

</html>