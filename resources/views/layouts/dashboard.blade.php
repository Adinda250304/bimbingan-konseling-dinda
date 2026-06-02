<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TEMAN BK') — Student Dashboard</title>
    
    <!-- Google Fonts & Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@600;700&family=Inter:wght@400;600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <!-- Local assets compiled via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
    
    {{-- Sidebar state: set immediately before paint so no slide animation on desktop refresh --}}
    <script>
        (function(){
            if (window.innerWidth >= 1024) {
                var s = document.createElement('style');
                s.id = 'sidebar-init';
                s.textContent = '#sidebar-drawer{transition:none!important}#main-content{transition:none!important}';
                document.head.appendChild(s);
            }
        })();
    </script>
    @yield('styles')
</head>
<body class="bg-background text-on-background font-body-md min-h-screen overflow-x-hidden">

    <!-- Mobile Drawer Overlay -->
    <div id="sidebar-overlay" class="hidden fixed inset-0 bg-black/40 z-40" onclick="closeSidebar()"></div>

    <!-- SideNavBar -->
    <aside id="sidebar-drawer" 
           class="h-screen w-64 fixed left-0 top-0 bg-surface/70 backdrop-blur-md border-r border-outline-variant/30 shadow-sm flex flex-col p-gutter z-50 -translate-x-full lg:translate-x-0 transition-transform duration-300">
        <div class="mb-8 px-4 flex items-center gap-3">
            <img src="{{ asset('img/logo-ypml.png') }}" alt="Logo" class="h-10 w-auto flex-shrink-0">
            <div>
                <h1 class="font-headline-md text-headline-md font-bold text-primary leading-tight">TEMAN BK</h1>
                <p class="font-body-sm text-body-sm text-on-surface-variant leading-tight">Serene Sanctuary</p>
            </div>
        </div>
        
        <nav class="flex-1 flex flex-col gap-2">
            @php
                if (auth()->user()->hasRole('wali_kelas')) {
                    $items = [
                        ['route' => 'wali.dashboard', 'label' => 'Dashboard Wali',   'icon' => 'dashboard'],
                        ['route' => 'wali.siswa',     'label' => 'Data Siswa',       'icon' => 'groups'],
                        ['route' => 'wali.jadwal',    'label' => 'Jadwal Panggilan', 'icon' => 'event_available'],
                        ['route' => 'wali.riwayat',   'label' => 'Rekap Konseling',  'icon' => 'history'],
                    ];
                } else {
                    $items = [
                        ['route' => 'siswa.dashboard', 'label' => 'Dashboard',        'icon' => 'dashboard'],
                        ['route' => 'siswa.pengajuan', 'label' => 'Ajukan Konseling', 'icon' => 'chat_bubble'],
                        ['route' => 'siswa.kalender',  'label' => 'Kalender Guru BK', 'icon' => 'calendar_month'],
                        ['route' => 'siswa.jadwal',    'label' => 'Jadwal Konseling',  'icon' => 'event_available'],
                        ['route' => 'siswa.riwayat',   'label' => 'Riwayat Konseling', 'icon' => 'history'],
                        ['route' => 'siswa.artikel.index', 'label' => 'Artikel Siswa',    'icon' => 'article'],
                    ];
                }
            @endphp
            
            @foreach($items as $item)
                @php $active = request()->routeIs($item['route']); @endphp
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors duration-200
                          {{ $active ? 'text-primary font-bold bg-primary-container/20' : 'text-on-surface-variant hover:text-primary hover:bg-surface-container-low' }}">
                    <span class="material-symbols-outlined" {!! $active ? 'style="font-variation-settings: \'FILL\' 1;"' : '' !!}>{{ $item['icon'] }}</span>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>


    </aside>

    <!-- Main Content Wrapper -->
    <div id="main-content" class="flex-1 flex flex-col min-h-screen lg:ml-64 min-w-0 transition-all duration-300">
        <!-- TopAppBar -->
        <header class="bg-surface/70 backdrop-blur-md border-b border-outline-variant/30 px-container-padding py-4 flex items-center justify-between min-h-16 sticky top-0 z-40">
            <div class="flex items-center gap-4">
                <button id="sidebar-toggle-btn" onclick="toggleSidebar()" class="lg:hidden text-on-surface-variant p-2 rounded-full hover:bg-surface-container-low transition-colors">
                    <span class="material-symbols-outlined transition-transform duration-300" id="sidebar-toggle-icon">menu</span>
                </button>
                <h2 class="font-headline-md text-headline-md font-bold text-primary">@yield('nav-title', 'Dashboard Siswa')</h2>
            </div>
            
            <div class="flex items-center gap-6">
                {{-- Notifications Dropdown --}}
                <div class="relative dd-wrap flex-shrink-0">
                    <button onclick="toggleDd('notification-menu')" class="text-on-surface-variant p-2 rounded-full hover:bg-surface-container-low transition-colors relative flex items-center justify-center">
                        <span class="material-symbols-outlined">notifications</span>
                        <!-- Unread Dot Badge -->
                        <span id="notif-badge" class="hidden absolute top-1 right-1 min-w-4 h-4 px-1 bg-error text-white text-[0.5625rem] font-bold rounded-full flex items-center justify-center">0</span>
                    </button>
 
                    <div id="notification-menu" class="hidden dd-menu absolute right-0 mt-3 w-[calc(100vw-2rem)] max-w-xs sm:max-w-none sm:w-80 bg-surface-container-lowest border border-outline-variant/30 rounded-2xl shadow-xl py-1 z-50 overflow-hidden text-left origin-top-right transition-all">
                        <div class="px-4 py-3 bg-surface-container-low flex justify-between items-center">
                            <p class="text-xs font-bold text-on-surface">Notifikasi</p>
                            <button onclick="markAllNotifRead()" class="text-[0.6875rem] text-primary font-bold hover:underline cursor-pointer">Tandai semua dibaca</button>
                        </div>
                        <hr class="border-outline-variant/30">
                        <div id="notif-list" class="max-h-64 overflow-y-auto py-1">
                            <!-- Items populated dynamically -->
                            <p class="text-xs text-outline text-center py-4">Tidak ada notifikasi baru.</p>
                        </div>
                    </div>
                </div>
                
                {{-- Dropdown Profile --}}
                <div class="relative dd-wrap flex-shrink-0">
                    <button onclick="toggleDd('profile-menu-dash')" class="flex items-center focus:outline-none">
                        <div class="w-10 h-10 rounded-full border border-outline-variant bg-primary-container/20 flex items-center justify-center text-primary font-bold shadow-sm hover:ring-4 hover:ring-primary/10 transition-all cursor-pointer">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    </button>
                    
                    <div id="profile-menu-dash" class="hidden dd-menu absolute right-0 mt-3 w-56 bg-surface-container-lowest border border-outline-variant/30 rounded-2xl shadow-xl py-1 z-50 overflow-hidden text-left origin-top-right transition-all">
                        <div class="px-4 py-3 bg-surface-container-low">
                            <p class="text-sm font-bold text-on-surface leading-tight truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-on-surface-variant font-medium leading-tight mt-1 truncate">{{ auth()->user()->kelas ?? 'Siswa' }}</p>
                        </div>
                        <hr class="border-outline-variant/30">
                        <div class="py-1.5">
                            <form action="{{ route('logout') }}" method="POST" class="block w-full">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 text-left px-4 py-2 text-sm text-error hover:bg-error-container/10 transition-colors font-medium">
                                    <span class="material-symbols-outlined text-error">logout</span>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="px-4 sm:px-container-padding py-6 sm:py-8 pb-12 max-w-[80rem] mx-auto min-h-[calc(100vh-4rem)] w-full">
            @yield('content')
        </main>
    </div>

    <!-- Modals -->
    @yield('modals')

    <script>
        const SIDEBAR_W = 256; // w-64 = 256px
        let isOpen = window.innerWidth >= 1024;

        function _updateToggleIcon() {
            var icon = document.getElementById('sidebar-toggle-icon');
            if (!icon) return;
            if (isOpen && window.innerWidth < 1024) {
                icon.textContent = 'close';
                icon.style.transform = 'rotate(90deg)';
            } else {
                icon.textContent = 'menu';
                icon.style.transform = 'rotate(0deg)';
            }
        }

        function openSidebar() {
            isOpen = true;
            document.getElementById('sidebar-drawer').classList.remove('-translate-x-full');
            if (window.innerWidth < 1024) {
                document.getElementById('sidebar-overlay').classList.remove('hidden');
                document.getElementById('main-content').style.marginLeft = '0';
                document.body.style.overflow = 'hidden';
            } else {
                document.getElementById('sidebar-overlay').classList.add('hidden');
                document.getElementById('main-content').style.marginLeft = SIDEBAR_W + 'px';
            }
            _updateToggleIcon();
        }

        function closeSidebar() {
            isOpen = false;
            document.getElementById('sidebar-drawer').classList.add('-translate-x-full');
            document.getElementById('sidebar-overlay').classList.add('hidden');
            document.getElementById('main-content').style.marginLeft = '0';
            document.body.style.overflow = '';
            _updateToggleIcon();
        }

        function toggleSidebar() {
            var initStyle = document.getElementById('sidebar-init');
            if (initStyle) initStyle.remove();
            isOpen ? closeSidebar() : openSidebar();
        }

        document.addEventListener('DOMContentLoaded', function () {
            if (window.innerWidth >= 1024) {
                document.getElementById('sidebar-drawer').classList.remove('-translate-x-full');
                document.getElementById('main-content').style.marginLeft = SIDEBAR_W + 'px';
            }
            // Auto-close sidebar when a nav link is tapped on mobile
            document.querySelectorAll('#sidebar-drawer nav a').forEach(function(link) {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 1024) {
                        closeSidebar(true);
                    }
                });
            });
        });

        window.addEventListener('resize', function () {
            if (window.innerWidth >= 1024) {
                document.getElementById('sidebar-overlay').classList.add('hidden');
                document.body.style.overflow = '';
                if (isOpen) document.getElementById('main-content').style.marginLeft = SIDEBAR_W + 'px';
                else document.getElementById('main-content').style.marginLeft = '0';
            } else {
                document.getElementById('main-content').style.marginLeft = '0';
            }
            _updateToggleIcon();
        });

        function openModal(id)  { document.getElementById(id).classList.remove('hidden'); document.getElementById(id).classList.add('flex'); }
        function closeModal(id) { document.getElementById(id).classList.add('hidden'); document.getElementById(id).classList.remove('flex'); }

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.dd-wrap')) {
                document.querySelectorAll('.dd-menu').forEach(function(m) { m.classList.add('hidden'); });
            }
        });
        function toggleDd(id) {
            var el = document.getElementById(id);
            document.querySelectorAll('.dd-menu').forEach(function(m) { if (m.id !== id) m.classList.add('hidden'); });
            el.classList.toggle('hidden');
        }

        // Notifications Real-Time & Management
        function fetchNotifications() {
            fetch('/api/notifications')
                .then(res => res.json())
                .then(data => {
                    const badge = document.getElementById('notif-badge');
                    if (!badge) return;
                    if (data.unread_count > 0) {
                        badge.innerText = data.unread_count;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }

                    const list = document.getElementById('notif-list');
                    if (!list) return;
                    if (data.notifications.length === 0) {
                        list.innerHTML = '<p class="text-xs text-outline text-center py-4">Tidak ada notifikasi baru.</p>';
                        return;
                    }

                    list.innerHTML = data.notifications.map(n => {
                        const bgClass = n.is_read ? 'hover:bg-surface-container-low' : 'bg-primary/5 hover:bg-primary/10';
                        const dotClass = n.is_read ? 'hidden' : 'bg-primary';
                        const textWeight = n.is_read ? 'font-medium' : 'font-bold';
                        const timeStr = new Date(n.created_at).toLocaleString('id-ID', {
                            day: 'numeric',
                            month: 'short',
                            hour: '2-digit',
                            minute: '2-digit'
                        });

                        return `
                            <div class="px-4 py-3 hover:bg-surface-container-low transition-colors cursor-pointer border-b border-outline-variant/10 flex items-start gap-3 relative ${bgClass}" onclick="markNotifRead(${n.id}, event)">
                                <div class="flex-1">
                                    <p class="text-xs ${textWeight} text-on-surface">${n.title}</p>
                                    <p class="text-xs text-outline leading-snug mt-0.5">${n.message}</p>
                                    <span class="text-[0.625rem] text-outline/80 mt-1 block">${timeStr}</span>
                                </div>
                                <span class="w-2 h-2 rounded-full ${dotClass} mt-1.5 flex-shrink-0"></span>
                            </div>
                        `;
                    }).join('');
                })
                .catch(err => console.error('Error fetching notifications:', err));
        }

        function markAllNotifRead() {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch('/api/notifications/read-all', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                }
            })
            .then(res => res.json())
            .then(() => fetchNotifications())
            .catch(err => console.error('Error marking all as read:', err));
        }

        function markNotifRead(id, event) {
            event.stopPropagation();
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch(`/api/notifications/${id}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                }
            })
            .then(res => res.json())
            .then(() => fetchNotifications())
            .catch(err => console.error('Error marking notification as read:', err));
        }

        document.addEventListener('DOMContentLoaded', () => {
            fetchNotifications();
            // Poll every 10 seconds
            setInterval(fetchNotifications, 10000);
        });
    </script>
    @include('components.sweetalert')
    @yield('scripts')
</body>
</html>
