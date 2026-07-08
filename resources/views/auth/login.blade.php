<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Masuk | Teman BK</title>
    
    <!-- Google Fonts & Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <!-- GSAP for Smooth Animations -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    
    <!-- Local assets compiled via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .glass-panel {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
        .login-card {
            box-shadow: 0px 4px 25px rgba(16, 106, 106, 0.06);
        }
    </style>
</head>
<body class="bg-surface font-body-md text-on-surface overflow-hidden h-screen w-full">
    <!-- Main Content Canvas -->
    <main class="h-screen w-full flex overflow-hidden">
        
        <!-- Left Side Cover (Desktop Only) -->
        <div class="hidden lg:block lg:w-1/2 h-full relative overflow-hidden">
            <img class="absolute inset-0 w-full h-full object-cover" src="{{ asset('img/bgsmkypml.png') }}" alt="Bimbingan Konseling Teman BK YPML"/>
            <div class="absolute inset-0 bg-primary/20 backdrop-brightness-95"></div>
            
            <!-- Branding Overlay on Image -->
            <div class="absolute bottom-12 left-12 z-10 text-white drop-shadow-md">
                <h1 class="font-headline-lg text-headline-lg font-bold mb-2 tracking-tight">Selamat Datang di TEMAN BK</h1>
                <p class="font-body-lg text-body-lg opacity-90 max-w-md">Ruang aman digital untuk mendukung perjalanan belajarmu di SMK YPML.</p>
            </div>
        </div>

        <!-- Right Side: Login Form Container -->
        <section class="w-full lg:w-1/2 h-full overflow-y-auto bg-surface-container-lowest relative z-10 custom-scrollbar">
            <div class="flex flex-col min-h-full p-6 sm:p-12">
                <!-- Spacer for vertical centering -->
                <div class="flex-grow"></div>
                
                <div class="w-full max-w-[28.75rem] bg-surface-container-lowest border border-outline-variant p-8 rounded-2xl login-card mx-auto shrink-0" id="login-card">
                    
                    <!-- Brand Header -->
                <header class="mb-8 text-center lg:text-left">
                    <div class="flex items-center justify-center lg:justify-start gap-2 mb-4">
                        <div class="w-10 h-10 bg-white border border-outline-variant/30 rounded-lg flex items-center justify-center overflow-hidden">
                            <img src="{{ asset('img/logo-ypml.png') }}" alt="Logo YPML" class="h-8 w-auto">
                        </div>
                        <span class="font-headline-md text-headline-md font-bold tracking-tight text-primary">TEMAN BK</span>
                    </div>
                    <h2 class="font-headline-lg text-headline-lg text-on-surface mb-1">Masuk ke Akun</h2>
                    <p class="font-body-md text-on-surface-variant text-sm">Silakan masukkan detail akun Anda untuk melanjutkan.</p>
                </header>

                <!-- Form -->
                <form action="{{ route('login.post') }}" method="POST" class="space-y-5" id="login-form">
                    @csrf
                    
                    <!-- Identity Field -->
                    <div class="space-y-1.5 form-element text-left">
                        <label class="font-label-md text-label-md text-on-surface-variant flex items-center gap-2" for="login">
                            <span class="material-symbols-outlined text-[1.125rem]">person</span>
                            Email atau Username
                        </label>
                        <input class="w-full bg-surface-container-low border {{ $errors->has('login') ? 'border-error' : 'border-outline-variant' }} rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-container focus:ring-offset-2 outline-none transition-all placeholder:text-on-surface-variant/40" 
                               id="login" 
                               name="login" 
                               value="{{ old('login') }}" 
                               placeholder="Contoh: 212210045 atau email@sekolah.com" 
                               type="text" 
                               required 
                               autofocus/>
                        @error('login')
                            <p class="text-error text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-1.5 form-element text-left">
                        <div class="flex justify-between items-center">
                            <label class="font-label-md text-label-md text-on-surface-variant flex items-center gap-2" for="password">
                                <span class="material-symbols-outlined text-[1.125rem]">lock</span>
                                Kata Sandi
                            </label>
                        </div>
                        <div class="relative">
                            <input class="w-full bg-surface-container-low border {{ $errors->has('password') ? 'border-error' : 'border-outline-variant' }} rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-container focus:ring-offset-2 outline-none transition-all placeholder:text-on-surface-variant/40 pr-12" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Masukkan kata sandi" 
                                   type="password" 
                                   minlength="8"
                                   maxlength="20"
                                   required/>
                            <button id="toggle-password" class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary transition-colors" type="button">
                                <span class="material-symbols-outlined" id="toggle-icon">visibility</span>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-error text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center gap-3 form-element text-left">
                        <input class="w-5 h-5 rounded border-outline-variant text-primary-container focus:ring-primary-container transition-all cursor-pointer" 
                               id="remember" 
                               name="remember" 
                               type="checkbox"/>
                        <label class="font-body-sm text-body-sm text-on-surface-variant cursor-pointer select-none" for="remember">Tetap masuk selama 30 hari</label>
                    </div>

                    <!-- Action Button -->
                    <div class="pt-1 form-element">
                        <button class="w-full bg-primary-container text-on-primary-container font-headline-sm text-sm py-2.5 px-6 rounded-xl shadow-sm hover:opacity-90 active:scale-95 transition-all flex items-center justify-center gap-2 cursor-pointer" type="submit">
                            Masuk Sekarang
                            <span class="material-symbols-outlined">arrow_forward</span>
                        </button>
                    </div>
                </form>

                <!-- Support Footer -->
                <footer class="mt-8 pt-4 border-t border-outline-variant text-center form-element">
                    <p class="font-body-sm text-body-sm text-on-surface-variant mb-1 text-xs">Belum punya akun atau kesulitan masuk?</p>
                    <a class="font-label-md text-label-md text-primary hover:underline inline-flex items-center gap-1 font-semibold text-xs" href="{{ route('register') }}">
                        Daftar Akun Siswa Baru
                        <span class="material-symbols-outlined text-[0.875rem]">open_in_new</span>
                    </a>
                </footer>
            </div>

            <!-- Footer -->
            <div class="mt-8 text-center text-on-surface-variant/60 font-label-md text-xs shrink-0">
                © YPML · TEMAN BK
            </div>

            <!-- Bottom Spacer for vertical centering -->
            <div class="flex-grow"></div>
        </div>
    </section>
</main>

    @include('components.sweetalert')

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // GSAP Entrance Animations
            const tl = gsap.timeline({ defaults: { ease: "power3.out" }});

            // Initial state
            gsap.set("#login-card", { opacity: 0, y: 30 });
            gsap.set(".form-element", { opacity: 0, y: 15 });

            // Execution
            tl.to("#login-card", {
                opacity: 1,
                y: 0,
                duration: 0.8,
                delay: 0.2
            })
            .to(".form-element", {
                opacity: 1,
                y: 0,
                duration: 0.5,
                stagger: 0.08
            }, "-=0.4");

            // Input interactions
            const inputs = document.querySelectorAll('input[type="text"], input[type="password"]');
            inputs.forEach(input => {
                input.addEventListener('focus', () => {
                    gsap.to(input, { scale: 1.01, duration: 0.2 });
                });
                input.addEventListener('blur', () => {
                    gsap.to(input, { scale: 1, duration: 0.2 });
                });
            });

            // Password Toggle Visibility
            const toggleBtn = document.getElementById('toggle-password');
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggle-icon');

            if (toggleBtn && passwordInput && toggleIcon) {
                toggleBtn.addEventListener('click', () => {
                    const isPassword = passwordInput.type === 'password';
                    passwordInput.type = isPassword ? 'text' : 'password';
                    toggleIcon.innerText = isPassword ? 'visibility_off' : 'visibility';
                });
            }

            // Form Submit Simulation with Native Submit
            const loginForm = document.getElementById('login-form');
            loginForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const btn = e.target.querySelector('button[type="submit"]');
                btn.disabled = true;
                btn.innerHTML = `<span class="material-symbols-outlined animate-spin">progress_activity</span> Memproses...`;
                
                setTimeout(() => {
                    loginForm.submit();
                }, 500);
            });
        });
    </script>
</body>
</html>
