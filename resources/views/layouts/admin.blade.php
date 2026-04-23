<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Teman BK') — Guru BK</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Rajdhani:wght@600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { theme: { fontFamily: { sans: ['Poppins','sans-serif'], poppins: ['Poppins','sans-serif'] } } }</script>
    {{-- Sidebar state: set immediately before paint so no slide animation on desktop refresh --}}
    <script>
        (function(){
            if (window.innerWidth >= 1024) {
                var s = document.createElement('style');
                s.id = 'sidebar-init';
                s.textContent = '#sidebar-drawer{transform:translateX(0)!important;transition:none!important}#main-content{margin-left:240px!important;transition:none!important}';
                document.head.appendChild(s);
            }
        })();
    </script>
    @yield('styles')
</head>
<body class="min-h-screen font-poppins" style="background: linear-gradient(to bottom, #DBE9F2 0%, #DBE9F2 45%, #FDE2E4 55%, #FDE2E4 100%); background-attachment: fixed;">

    {{-- ══ OVERLAY (mobile only) ══ --}}
    <div id="sidebar-overlay" class="hidden fixed inset-0 bg-black/40 z-40" onclick="closeSidebar()"></div>

    {{-- ══ SIDEBAR (satu untuk semua screen size) ══ --}}
    <aside id="sidebar-drawer"
        class="fixed inset-y-0 left-0 w-60 bg-white border-r border-gray-200 flex flex-col z-50
               -translate-x-full">

        {{-- Brand --}}
        <div class="px-5 py-4 border-b border-gray-200 min-h-[65px]">
            <div class="flex items-center gap-3">
                <img src="{{ asset('img/logo-ypml.png') }}" alt="Logo" class="h-9 w-auto flex-shrink-0">
                <div>
                    <div class="text-gray-800 leading-tight" style="font-family:'Rajdhani',sans-serif;font-weight:700;font-size:18px;letter-spacing:0.04em">TEMAN BK</div>
                    <div class="text-gray-400 leading-tight" style="font-family:'Rajdhani',sans-serif;font-weight:600;font-size:11px;letter-spacing:0.12em">SMK YPML</div>
                </div>
            </div>
        </div>



        {{-- Nav --}}
        <nav class="flex-1 py-4 overflow-y-auto px-4">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-2 mb-3">Menu Utama</p>
            @php
                $navItems = [
                    ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>'],
                    ['route' => 'admin.users',     'label' => 'Kelola Data User', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>'],
                    ['route' => 'admin.kalender',  'label' => 'Kalender Ketersediaan', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5m-9-6h.008v.008H12V12zm0 3h.008v.008H12V15zm0 3h.008v.008H12V18zm-3-6h.008v.008H9V12zm0 3h.008v.008H9V15zm0 3h.008v.008H9V18zm6-6h.008v.008H15V12zm0 3h.008v.008H15V15z"/>'],
                    ['route' => 'admin.jadwal',    'label' => 'Jadwal Konseling', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                    ['route' => 'admin.riwayat',   'label' => 'Riwayat Konseling', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/>'],
                ];
            @endphp
            @foreach($navItems as $item)
            @php $active = request()->routeIs($item['route']); @endphp
            <a href="{{ route($item['route']) }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors mb-1
                      {{ $active ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-5 h-5 flex-shrink-0 {{ $active ? 'text-blue-500' : 'text-gray-400' }}"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    {!! $item['icon'] !!}
                </svg>
                {{ $item['label'] }}
            </a>
            @endforeach
        </nav>


    </aside>

    {{-- ══ MAIN AREA ══ --}}
    <div id="main-content" class="transition-all duration-300">

        {{-- Top bar --}}
        <header class="sticky top-0 z-30 bg-white border-b border-gray-200 px-5 py-4 flex items-center gap-4 min-h-[57px]">
            {{-- Hamburger (semua screen) --}}
            <button onclick="toggleSidebar()" class="flex flex-col gap-1.5 p-1 rounded hover:bg-gray-100 transition flex-shrink-0" aria-label="Toggle Menu">
                <span class="block w-5 h-0.5 bg-gray-700 rounded"></span>
                <span class="block w-5 h-0.5 bg-gray-700 rounded"></span>
                <span class="block w-5 h-0.5 bg-gray-700 rounded"></span>
            </button>

            {{-- Page title --}}
            <h1 class="text-gray-800 font-semibold text-base flex-1 truncate">
                @yield('nav-title', 'Dashboard')
            </h1>

            {{-- Profile Dropdown --}}
            <div class="relative dd-wrap flex-shrink-0">
                <button onclick="toggleDd('profile-menu')" class="flex items-center gap-2 text-sm focus:outline-none">
                    <div class="relative flex-shrink-0 cursor-pointer hover:ring-4 hover:ring-blue-50 transition-all rounded-full">
                        <div class="w-9 h-9 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-sm">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 border-white rounded-full"></span>
                    </div>
                </button>

                <div id="profile-menu" class="hidden dd-menu absolute right-0 mt-3 w-56 bg-white border border-gray-100 rounded-2xl shadow-xl py-1 z-50 overflow-hidden text-left origin-top-right transition-all">
                    {{-- User Info Header --}}
                    <div class="px-4 py-3 bg-gray-50/50">
                        <p class="text-sm font-bold text-gray-800 leading-tight truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 font-medium leading-tight mt-1 truncate">Guru BK</p>
                    </div>
                    
                    <hr class="border-gray-100">

                    {{-- Links --}}
                    <div class="py-1.5">
                        <a href="{{ route('admin.profile') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Pengaturan Profil
                        </a>
                    </div>
                    
                    <hr class="border-gray-100">
                    
                    {{-- Logout --}}
                    <div class="py-1.5">
                        <form action="{{ route('logout') }}" method="POST" class="block w-full">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                <svg class="w-4 h-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>


        {{-- Content --}}
        <main class="px-5 py-4 pb-10">
            @yield('content')
        </main>
    </div>

    {{-- Modals --}}
    @yield('modals')

    <script>
        const SIDEBAR_W = 240; // w-60 = 240px
        let isOpen = window.innerWidth >= 1024; // default open on desktop

        function _applyTransitions() {
            var el = document.getElementById('sidebar-drawer');
            var mc = document.getElementById('main-content');
            el.style.transition = 'transform 0.3s ease';
            mc.style.transition = 'margin-left 0.3s ease';
        }

        function openSidebar(animate) {
            isOpen = true;
            if (animate) _applyTransitions();
            document.getElementById('sidebar-drawer').style.transform = 'translateX(0)';
            if (window.innerWidth < 1024) {
                document.getElementById('sidebar-overlay').classList.remove('hidden');
                document.getElementById('main-content').style.marginLeft = '0';
            } else {
                document.getElementById('sidebar-overlay').classList.add('hidden');
                document.getElementById('main-content').style.marginLeft = SIDEBAR_W + 'px';
            }
        }

        function closeSidebar(animate) {
            isOpen = false;
            if (animate) _applyTransitions();
            document.getElementById('sidebar-drawer').style.transform = 'translateX(-100%)';
            document.getElementById('sidebar-overlay').classList.add('hidden');
            document.getElementById('main-content').style.marginLeft = '0';
        }

        function toggleSidebar() {
            // Remove the head-injected init style (re-enables natural transition)
            var initStyle = document.getElementById('sidebar-init');
            if (initStyle) initStyle.remove();
            isOpen ? closeSidebar(true) : openSidebar(true);
        }

        // On load: set state without animation (init-style already handled desktop visibility)
        document.addEventListener('DOMContentLoaded', function () {
            if (window.innerWidth >= 1024) {
                document.getElementById('sidebar-drawer').style.transform = 'translateX(0)';
                document.getElementById('main-content').style.marginLeft = SIDEBAR_W + 'px';
            }
        });

        window.addEventListener('resize', function () {
            if (window.innerWidth >= 1024) {
                document.getElementById('sidebar-overlay').classList.add('hidden');
                if (isOpen) document.getElementById('main-content').style.marginLeft = SIDEBAR_W + 'px';
                else document.getElementById('main-content').style.marginLeft = '0';
            } else {
                document.getElementById('main-content').style.marginLeft = '0';
            }
        });

        // Modal helpers
        function openModal(id)  { document.getElementById(id).classList.remove('hidden'); document.getElementById(id).classList.add('flex'); }
        function closeModal(id) { document.getElementById(id).classList.add('hidden'); document.getElementById(id).classList.remove('flex'); }

        // Dropdown helper
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.dd-wrap')) {
                document.querySelectorAll('.dd-menu').forEach(m => m.classList.add('hidden'));
            }
        });
        function toggleDd(id) {
            const el = document.getElementById(id);
            document.querySelectorAll('.dd-menu').forEach(m => { if (m.id !== id) m.classList.add('hidden'); });
            el.classList.toggle('hidden');
        }
    </script>
    @include('components.sweetalert')
    @yield('scripts')
</body>
</html>
