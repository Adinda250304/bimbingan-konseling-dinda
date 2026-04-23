<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teman BK</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{fontFamily:{poppins:['Poppins','sans-serif']}}}}</script>
    <script>
        setTimeout(() => { window.location.href = "{{ route('welcome') }}"; }, 2200);
    </script>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-500 to-indigo-600 font-poppins flex flex-col items-center justify-center gap-5">
    <img src="{{ asset('img/logo-ypml.png') }}" alt="Logo" class="h-24 w-auto drop-shadow-xl animate-pulse">
    <div class="text-center">
        <h1 class="text-white font-bold text-3xl tracking-wide">Teman BK</h1>
        <p class="text-blue-200 text-sm mt-1">Bimbingan &amp; Konseling YPML</p>
    </div>
    <div class="flex gap-1.5 mt-4">
        <span class="w-2 h-2 bg-white/80 rounded-full animate-bounce [animation-delay:0ms]"></span>
        <span class="w-2 h-2 bg-white/80 rounded-full animate-bounce [animation-delay:150ms]"></span>
        <span class="w-2 h-2 bg-white/80 rounded-full animate-bounce [animation-delay:300ms]"></span>
    </div>
</body>
</html>
