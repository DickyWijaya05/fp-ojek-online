<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
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
  <style>
    body {
      background: radial-gradient(circle at top left, #fef3c7, transparent),
                  radial-gradient(circle at bottom right, #fde68a, transparent),
                  linear-gradient(135deg, #fff7ed, #fef9c3);
      background-repeat: no-repeat;
      background-size: cover;
    }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center px-4">

  <!-- Glass Container -->
  <div class="relative z-10 w-full max-w-md p-8 sm:p-10 bg-white/50 backdrop-blur-2xl border border-yellow-200 rounded-3xl shadow-2xl transition-all duration-300">

    <!-- Decorative Floating Circles -->
    <div class="absolute -top-16 -left-16 w-44 h-44 bg-yellow-200 opacity-30 rounded-full blur-3xl animate-pulse"></div>
    <div class="absolute -bottom-16 -right-16 w-44 h-44 bg-yellow-300 opacity-30 rounded-full blur-3xl animate-pulse"></div>

    <!-- LOGO -->
    <div class="relative z-10 flex justify-center mb-6">
      <img src="{{ asset('images/Logo.png') }}" alt="Logo Admin"
           class="h-28 w-28 object-contain rounded-full border-4 border-yellow-400 bg-white p-2 shadow-lg transition-transform duration-300 hover:scale-105">
    </div>

    <!-- TITLE -->
    <h2 class="text-3xl font-extrabold text-center text-yellow-600 mb-8 tracking-wide relative z-10">Admin Login</h2>

    <!-- SESSION ERROR -->
    @if(session('error'))
      <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 text-sm z-10 relative">
        {{ session('error') }}
      </div>
    @endif

    <!-- FORM -->
    <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-6 relative z-10">
      @csrf

      <!-- EMAIL -->
      <div class="relative">
        <input type="email" name="email" id="email" required
               class="peer w-full px-4 pt-5 pb-2 bg-white/70 border border-yellow-300 text-gray-800 rounded-lg placeholder-transparent focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 transition"
               placeholder="Email" />
        <label for="email"
               class="absolute left-4 top-2 text-gray-600 text-sm transition-all peer-placeholder-shown:top-4 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-focus:top-2 peer-focus:text-sm peer-focus:text-yellow-600">
          Email Admin
        </label>
      </div>

      <!-- PASSWORD -->
      <div class="relative">
        <input type="password" name="password" id="password" required
               class="peer w-full px-4 pt-5 pb-2 bg-white/70 border border-yellow-300 text-gray-800 rounded-lg placeholder-transparent focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 transition"
               placeholder="Password" />
        <label for="password"
               class="absolute left-4 top-2 text-gray-600 text-sm transition-all peer-placeholder-shown:top-4 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-focus:top-2 peer-focus:text-sm peer-focus:text-yellow-600">
          Password
        </label>
      </div>

      <!-- SUBMIT BUTTON -->
      <button type="submit"
              class="w-full py-3 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold rounded-xl shadow-md hover:shadow-xl transition duration-300">
        Masuk
      </button>
    </form>

    <!-- FOOTER -->
    <div class="text-center mt-6 relative z-10">
      <p class="text-sm text-gray-600">© {{ date('Y') }} Admin Panel • Semua hak cipta dilindungi</p>
    </div>
  </div>

</body>
</html>
