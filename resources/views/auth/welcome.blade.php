<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang — Teman BK</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{fontFamily:{poppins:['Poppins','sans-serif']}}}}</script>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-100 via-sky-50 to-purple-100 font-poppins flex flex-col items-center justify-center p-6">
    <img src="{{ asset('img/logo-ypml.png') }}" alt="Logo" class="h-20 w-auto mb-5 drop-shadow-sm">
    <h1 class="text-blue-600 font-bold text-2xl text-center">Teman BK</h1>
    <p class="text-gray-500 text-sm text-center mt-1 mb-8">Platform Bimbingan &amp; Konseling YPML</p>

    <div class="flex flex-col gap-3 w-full max-w-xs">
        <a href="{{ route('login') }}"
           class="block text-center py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-2xl text-sm transition shadow-md shadow-blue-200">
            Masuk
        </a>
        <a href="{{ route('register') }}"
           class="block text-center py-3.5 border-2 border-blue-600 text-blue-600 hover:bg-blue-50 font-semibold rounded-2xl text-sm transition">
            Daftar Akun Siswa
        </a>
    </div>

    <p class="text-gray-400 text-xs mt-8 text-center">© {{ date('Y') }} YPML · Teman BK</p>
</body>
</html>
