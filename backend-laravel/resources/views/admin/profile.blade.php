<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Profil Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            50% {
                transform: translateX(5px);
            }

            75% {
                transform: translateX(-5px);
            }
        }

        .animate-shake {
            animation: shake 0.4s ease-in-out;
        }

        .glass {
            backdrop-filter: blur(14px);
            background: rgba(255, 255, 255, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>
</head>

<body class="bg-gradient-to-br from-yellow-50 to-white min-h-screen flex items-center justify-center px-4 py-10">

    <div class="w-full max-w-6xl mx-auto p-10 rounded-3xl glass shadow-xl border border-yellow-300 space-y-8 relative">

        <!-- Back to Dashboard -->
        <a href="{{ route('admin.dashboard') }}"
            class="absolute top-5 right-5 text-sm text-yellow-800 font-semibold hover:text-yellow-900 transition flex items-center gap-1">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Dashboard
        </a>

        <!-- Header & Avatar -->
        <div class="flex flex-col md:flex-row gap-6 items-center border-b pb-6">
            <img src="{{ $admin->avatar ? asset('storage/avatars/' . $admin->avatar) : asset('images/Logo.png') }}"
                class="w-32 h-32 rounded-full border-4 border-yellow-400 shadow-md object-cover" alt="Admin Avatar">

            <div class="flex-1">
                <h1 class="text-3xl font-bold text-yellow-700 flex items-center gap-2">
                    <i data-lucide="user-cog" class="w-6 h-6 text-yellow-600"></i> {{ $admin->name }}
                </h1>
                <p class="text-gray-600 mt-1 flex items-center gap-1">
                    <i data-lucide="mail" class="w-4 h-4 text-yellow-500"></i> {{ $admin->email }}
                </p>
                <span
                    class="mt-2 inline-block text-xs bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full shadow">Super
                    Admin</span>
            </div>
        </div>

        <!-- Flash Message -->
        @if (session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-3 rounded shadow font-medium">
                ✅ {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 text-red-800 px-4 py-3 rounded shadow font-medium animate-shake">
                ❌ {{ session('error') }}
            </div>
        @endif

        <!-- Grid Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <!-- Form Profil -->
            <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data"
                class="space-y-4">
                @csrf
                <h2 class="text-xl font-bold text-yellow-700 flex items-center gap-2">
                    <i data-lucide="edit-3" class="w-5 h-5 text-yellow-700"></i> Edit Profil
                </h2>

                <div>
                    <label class="text-sm text-gray-600 font-semibold">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $admin->name) }}"
                        class="w-full mt-1 p-3 rounded-xl border shadow-inner focus:ring-2 ring-yellow-300" required>
                </div>

                <div>
                    <label class="text-sm text-gray-600 font-semibold">Email</label>
                    <input type="email" name="email" value="{{ old('email', $admin->email) }}"
                        class="w-full mt-1 p-3 rounded-xl border shadow-inner focus:ring-2 ring-yellow-300" required>
                </div>

                <div>
                    <label class="text-sm text-gray-600 font-semibold">Ganti Foto Profil</label>
                    <div class="relative mt-1">
                        <input type="file" name="avatar" id="avatar"
                            class="peer absolute inset-0 opacity-0 w-full h-full z-10 cursor-pointer" />
                        <div
                            class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-yellow-100 hover:bg-yellow-200 border border-yellow-300 text-yellow-700 font-medium cursor-pointer peer-focus:ring-2 peer-focus:ring-yellow-300 transition-all">
                            <i data-lucide="upload" class="w-5 h-5"></i>
                            <span>Pilih Foto</span>
                        </div>
                    </div>
                </div>


                <div class="text-right">
                    <button type="submit"
                        class="group inline-flex items-center gap-2 px-6 py-3 bg-yellow-500 hover:bg-yellow-600 text-white rounded-xl shadow-lg transition font-semibold">
                        <i data-lucide="save-all" class="w-5 h-5"></i> Simpan Perubahan
                    </button>
                </div>
            </form>

            <!-- Form Password -->
            <form method="POST" action="{{ route('admin.profile.change-password') }}" class="space-y-4">
                @csrf
                <h2 class="text-xl font-bold text-yellow-700 flex items-center gap-2">
                    <i data-lucide="lock" class="w-6 h-6 text-yellow-700"></i> Ganti Password
                </h2>

                <div>
                    <label class="text-sm text-gray-600 font-semibold">Password Lama</label>
                    <input type="password" name="current_password"
                        class="w-full mt-1 p-3 rounded-xl border shadow-inner focus:ring-2 ring-yellow-300" required>
                </div>

                <div>
                    <label class="text-sm text-gray-600 font-semibold">Password Baru</label>
                    <input type="password" name="new_password"
                        class="w-full mt-1 p-3 rounded-xl border shadow-inner focus:ring-2 ring-yellow-300" required>
                </div>

                <div>
                    <label class="text-sm text-gray-600 font-semibold">Konfirmasi Password</label>
                    <input type="password" name="new_password_confirmation"
                        class="w-full mt-1 p-3 rounded-xl border shadow-inner focus:ring-2 ring-yellow-300" required>
                </div>

                <div class="text-right">
                    <button type="submit"
                        class="group inline-flex items-center gap-2 px-6 py-3 bg-gray-800 hover:bg-black text-white rounded-xl shadow-lg transition font-semibold">
                        <i data-lucide="shield-check" class="w-5 h-5"></i> Ganti Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        lucide.createIcons();
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function () {
                const btn = this.querySelector('button[type="submit"]');
                if (btn) {
                    btn.disabled = true;
                    btn.classList.add('opacity-70', 'cursor-not-allowed');
                    const icon = btn.querySelector('svg');
                    if (icon) icon.outerHTML = `
            <svg class="animate-spin w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
              viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10"
                      stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
            </svg>`;
                }
            });
        });
    </script>

</body>

</html>