<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Teman BK YPML — Bimbingan &amp; Konseling Sekolah')</title>
    
    <!-- Google Fonts & Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <!-- GSAP for Smooth Transitions -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    
    <!-- Local assets compiled via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(0, 80, 80, 0.15);
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 80, 80, 0.3);
        }
    </style>
    @yield('styles')
</head>
<body class="bg-background text-on-background font-sans overflow-x-hidden custom-scrollbar flex flex-col min-h-screen">

    <!-- Header / Navbar -->
    <header id="main-header" class="fixed top-0 left-0 w-full z-50 bg-background/90 border-b border-outline-variant/30 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="{{ route('welcome') }}" class="flex items-center gap-3">
                <img src="{{ asset('img/logo-ypml.png') }}" alt="Logo" class="h-10 w-auto">
                <div>
                    <h1 class="font-headline-lg text-headline-sm font-extrabold text-primary tracking-tight leading-none">TEMAN BK</h1>
                    <span class="text-[10px] text-on-surface-variant font-semibold tracking-wider uppercase">Bimbingan &amp; Konseling YPML</span>
                </div>
            </a>
            
            <!-- Desktop Links -->
            <nav class="hidden md:flex items-center gap-8">
                @php
                    $navs = [
                        ['route' => 'welcome', 'label' => 'Beranda'],
                        ['route' => 'about',   'label' => 'BK Sahabat Siswa'],
                        ['route' => 'layanan', 'label' => 'Layanan Kami'],
                        ['route' => 'alur',    'label' => 'Alur Bimbingan'],
                        ['route' => 'faq',     'label' => 'Tanya Jawab'],
                    ];
                @endphp
                @foreach ($navs as $nav)
                    @php $active = request()->routeIs($nav['route']); @endphp
                    <a href="{{ route($nav['route']) }}" 
                       class="text-sm font-semibold transition-colors py-1 relative
                              {{ $active ? 'text-primary font-bold border-b-2 border-primary' : 'text-on-surface-variant hover:text-primary' }}">
                        {{ $nav['label'] }}
                    </a>
                @endforeach
            </nav>
            
            <!-- Auth Buttons -->
            <div class="hidden sm:flex items-center gap-4">
                <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-xl border border-primary/20 text-primary font-semibold text-sm hover:bg-primary/5 transition-colors">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-xl bg-primary hover:bg-primary-container text-white font-semibold text-sm transition-all duration-300 shadow-md shadow-primary/10">
                    Daftar Akun
                </a>
            </div>

            <!-- Hamburger Button -->
            <button onclick="toggleMobileNav()" class="md:hidden p-2 text-on-surface-variant hover:text-primary transition-colors focus:outline-none">
                <span id="nav-icon" class="material-symbols-outlined text-2xl">menu</span>
            </button>
        </div>
        
        <!-- Mobile Navigation Menu -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-outline-variant/10 bg-surface px-6 py-6 flex flex-col gap-4 shadow-lg animate-fade-in">
            @foreach ($navs as $nav)
                @php $active = request()->routeIs($nav['route']); @endphp
                <a href="{{ route($nav['route']) }}" onclick="toggleMobileNav()" 
                   class="text-sm py-2 {{ $active ? 'text-primary font-bold' : 'text-on-surface-variant font-medium' }}">
                    {{ $nav['label'] }}
                </a>
            @endforeach
            <hr class="border-outline-variant/10 my-2">
            <div class="flex flex-col gap-3">
                <a href="{{ route('login') }}" class="w-full text-center py-3 rounded-xl border border-primary/20 text-primary font-semibold text-sm hover:bg-primary/5 transition-colors">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="w-full text-center py-3 rounded-xl bg-primary text-white font-semibold text-sm shadow-md shadow-primary/10">
                    Daftar Akun
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-surface-container-low border-t border-outline-variant/30 py-16 text-left">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-12 gap-10">
            
            <!-- Left Info -->
            <div class="md:col-span-6 flex flex-col gap-4">
                <a href="{{ route('welcome') }}" class="flex items-center gap-3">
                    <img src="{{ asset('img/logo-ypml.png') }}" alt="Logo" class="h-9 w-auto">
                    <div>
                        <h1 class="font-headline-md text-headline-sm font-extrabold text-primary tracking-tight leading-none">TEMAN BK</h1>
                        <span class="text-[10px] text-on-surface-variant font-medium tracking-wide uppercase">Bimbingan &amp; Konseling YPML</span>
                    </div>
                </a>
                <p class="text-xs text-on-surface-variant font-medium max-w-sm leading-relaxed mt-2">
                    Layanan terintegrasi bimbingan dan konseling YPML. Siap membantu seluruh siswa dalam menyelesaikan problematika sekolah, pribadi, sosial, serta karir secara bijaksana.
                </p>
                <p class="text-xs text-outline mt-4 font-semibold">
                    © YPML · Teman BK. All rights reserved.
                </p>
            </div>
            
            <!-- Links -->
            <div class="md:col-span-3 flex flex-col gap-3">
                <h4 class="font-bold text-sm text-on-background uppercase tracking-wider">Tautan Cepat</h4>
                <a href="{{ route('welcome') }}" class="text-xs text-on-surface-variant hover:text-primary transition-colors">Beranda</a>
                <a href="{{ route('about') }}" class="text-xs text-on-surface-variant hover:text-primary transition-colors">BK Sahabat Siswa</a>
                <a href="{{ route('layanan') }}" class="text-xs text-on-surface-variant hover:text-primary transition-colors">Layanan Kami</a>
                <a href="{{ route('login') }}" class="text-xs text-on-surface-variant hover:text-primary transition-colors">Masuk Dasbor</a>
            </div>

            <!-- Contacts -->
            <div class="md:col-span-3 flex flex-col gap-3">
                <h4 class="font-bold text-sm text-on-background uppercase tracking-wider">Kontak &amp; Alamat</h4>
                <div class="flex items-start gap-2 text-xs text-on-surface-variant leading-relaxed">
                    <span class="material-symbols-outlined text-sm text-outline mt-0.5">location_on</span>
                    Gedung Bimbingan &amp; Konseling YPML, Lingkungan Sekolah
                </div>
                <div class="flex items-center gap-2 text-xs text-on-surface-variant">
                    <span class="material-symbols-outlined text-sm text-outline">mail</span>
                    bk@ypml.sch.id
                </div>
                <div class="flex items-center gap-2 text-xs text-on-surface-variant">
                    <span class="material-symbols-outlined text-sm text-outline">phone</span>
                    Hubungi Guru BK Sekolah
                </div>
            </div>

        </div>
    </footer>

    <!-- Master Scripts -->
    <script>
        // Toggle mobile navigation
        function toggleMobileNav() {
            const menu = document.getElementById('mobile-menu');
            const icon = document.getElementById('nav-icon');
            if (menu.classList.contains('hidden')) {
                menu.classList.remove('hidden');
                icon.innerText = 'close';
            } else {
                menu.classList.add('hidden');
                icon.innerText = 'menu';
            }
        }

        // Header Scroll shadow transition
        window.addEventListener('scroll', () => {
            const header = document.getElementById('main-header');
            if (window.scrollY > 50) {
                header.classList.add('shadow-sm', 'py-3');
                header.classList.remove('py-4');
            } else {
                header.classList.remove('shadow-sm', 'py-3');
                header.classList.add('py-4');
            }
        });
    </script>
    @yield('scripts')

</body>
</html>
