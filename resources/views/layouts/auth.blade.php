<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Teman BK')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { theme: { fontFamily: { sans: ['Poppins','sans-serif'], poppins: ['Poppins','sans-serif'] } } }</script>
</head>
<body class="min-h-screen font-poppins bg-gradient-to-br from-blue-100 via-sky-50 to-purple-100 flex items-center justify-center p-4">
    <div class="w-full max-w-sm">
        @yield('content')
    </div>
    @include('components.sweetalert')
</body>
</html>
