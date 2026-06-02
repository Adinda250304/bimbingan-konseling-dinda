<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Daftar Akun | Teman BK</title>
    
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
        .register-card {
            box-shadow: 0px 4px 25px rgba(16, 106, 106, 0.06);
        }
        /* Custom scrollbar for small screen heights */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(0, 80, 80, 0.1);
            border-radius: 10px;
        }
    </style>
</head>
<body class="bg-surface font-body-md text-on-surface overflow-hidden h-screen w-full">
    <!-- Main Content Canvas -->
    <main class="h-screen w-full flex overflow-hidden">
        
        <!-- Left Side Cover (Desktop Only) -->
        <div class="hidden lg:block lg:w-1/2 h-full relative overflow-hidden">
            <img class="absolute inset-0 w-full h-full object-cover" src="{{ asset('img/counseling_student_warm.png') }}" alt="Bimbingan Konseling Teman BK YPML"/>
            <div class="absolute inset-0 bg-primary/20 backdrop-brightness-95"></div>
            
            <!-- Branding Overlay on Image -->
            <div class="absolute bottom-12 left-12 z-10 text-white drop-shadow-md">
                <h1 class="font-headline-lg text-headline-lg font-bold mb-2 tracking-tight">Selamat Datang di TEMAN BK</h1>
                <p class="font-body-lg text-body-lg opacity-90 max-w-md">Ruang aman digital untuk mendukung perjalanan belajarmu di SMK YPML.</p>
            </div>
        </div>

        <!-- Right Side: Register Form Container -->
        <section class="w-full lg:w-1/2 h-full overflow-y-auto bg-surface-container-lowest relative z-10 custom-scrollbar">
            <div class="flex flex-col min-h-full p-6 md:p-8">
                <!-- Spacer for vertical centering -->
                <div class="flex-grow"></div>
                
                <div class="w-full max-w-[28.75rem] bg-surface-container-lowest border border-outline-variant py-6 px-8 rounded-2xl register-card mx-auto shrink-0" id="register-card">
                    
                    <!-- Brand Header -->
                <header class="mb-5 text-center lg:text-left">
                    <div class="flex items-center justify-center lg:justify-start gap-2 mb-3">
                        <div class="w-10 h-10 bg-white border border-outline-variant/30 rounded-lg flex items-center justify-center overflow-hidden">
                            <img src="{{ asset('img/logo-ypml.png') }}" alt="Logo YPML" class="h-8 w-auto">
                        </div>
                        <span class="font-headline-md text-headline-md font-bold tracking-tight text-primary">TEMAN BK</span>
                    </div>
                    <h2 class="font-headline-lg text-headline-md text-on-surface mb-0.5">Daftar Akun Baru</h2>
                    <p class="font-body-md text-on-surface-variant text-xs">Buat akun siswa baru untuk mengakses layanan bimbingan konseling.</p>
                </header>

                @if($errors->any())
                <div class="mb-3 bg-error-container/30 border border-error/20 text-error rounded-xl px-4 py-2 text-xs font-semibold text-left form-element">
                    {{ $errors->first() }}
                </div>
                @endif

                <!-- Form (Using compact vertical spacing to avoid scrollbars at 100% zoom) -->
                <form action="{{ route('register.post') }}" method="POST" class="space-y-3.5" id="register-form">
                    @csrf
                    
                    <!-- Nama Lengkap -->
                    <div class="space-y-1 form-element text-left">
                        <label class="font-label-md text-xs font-bold text-on-surface-variant flex items-center gap-2" for="name">
                            <span class="material-symbols-outlined text-[1.125rem]">person</span>
                            Nama Lengkap
                        </label>
                        <input class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-primary-container focus:ring-offset-2 outline-none transition-all placeholder:text-on-surface-variant/40" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}"
                               placeholder="Nama Lengkap Anda" 
                               type="text" 
                               required/>
                    </div>

                    <!-- Username -->
                    <div class="space-y-1 form-element text-left">
                        <label class="font-label-md text-xs font-bold text-on-surface-variant flex items-center gap-2" for="username">
                            <span class="material-symbols-outlined text-[1.125rem]">account_box</span>
                            Username
                        </label>
                        <input class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-primary-container focus:ring-offset-2 outline-none transition-all placeholder:text-on-surface-variant/40" 
                               id="username" 
                               name="username" 
                               value="{{ old('username') }}"
                               placeholder="Username unik untuk masuk" 
                               type="text" 
                               required/>
                    </div>

                    <!-- Email -->
                    <div class="space-y-1 form-element text-left">
                        <label class="font-label-md text-xs font-bold text-on-surface-variant flex items-center gap-2" for="email">
                            <span class="material-symbols-outlined text-[1.125rem]">mail</span>
                            Email
                        </label>
                        <input class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-primary-container focus:ring-offset-2 outline-none transition-all placeholder:text-on-surface-variant/40" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               placeholder="email@sekolah.com" 
                               type="email" 
                               required/>
                    </div>

                    <!-- Kelas -->
                    <div class="space-y-1 form-element text-left">
                        <label class="font-label-md text-xs font-bold text-on-surface-variant flex items-center gap-2" for="kelas">
                            <span class="material-symbols-outlined text-[1.125rem]">school</span>
                            Kelas
                        </label>
                        <input class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-primary-container focus:ring-offset-2 outline-none transition-all placeholder:text-on-surface-variant/40" 
                               id="kelas" 
                               name="kelas" 
                               value="{{ old('kelas') }}"
                               placeholder="Contoh: XII MIPA 1" 
                               type="text"/>
                    </div>

                    <!-- Password & Konfirmasi (Samping-sampingan dengan font proporsional agar hemat ruang vertikal) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Password -->
                        <div class="space-y-1 form-element text-left">
                            <label class="font-label-md text-xs font-bold text-on-surface-variant flex items-center gap-2" for="password">
                                <span class="material-symbols-outlined text-[1.125rem]">lock</span>
                                Kata Sandi
                            </label>
                            <div class="relative">
                                <input class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-primary-container focus:ring-offset-2 outline-none transition-all placeholder:text-on-surface-variant/40 pr-12" 
                                       id="password" 
                                       name="password" 
                                       placeholder="8-20 karakter" 
                                       type="password" 
                                       required 
                                       minlength="8"
                                       maxlength="20"/>
                                <button id="toggle-pw" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary transition-colors" type="button">
                                    <span class="material-symbols-outlined text-[1.125rem]" id="toggle-pw-icon">visibility</span>
                                </button>
                            </div>
                        </div>

                        <!-- Password Confirmation -->
                        <div class="space-y-1 form-element text-left">
                            <label class="font-label-md text-xs font-bold text-on-surface-variant flex items-center gap-2" for="password_confirmation">
                                <span class="material-symbols-outlined text-[1.125rem]">lock_clock</span>
                                Konfirmasi
                            </label>
                            <input class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-primary-container focus:ring-offset-2 outline-none transition-all placeholder:text-on-surface-variant/40" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   placeholder="Ulangi sandi" 
                                   type="password" 
                                   required
                                   minlength="8"
                                   maxlength="20"/>
                        </div>
                    </div>

                    <!-- Password Strength Indicator -->
                    <div class="form-element text-left" id="pw-requirements">
                        <div class="bg-surface-container-low rounded-xl p-3 border border-outline-variant/30">
                            <p class="text-[0.625rem] font-bold text-on-surface-variant uppercase tracking-wider mb-2">Persyaratan Kata Sandi:</p>
                            <div class="grid grid-cols-2 gap-x-4 gap-y-1">
                                <div class="flex items-center gap-1.5" id="req-length">
                                    <span class="material-symbols-outlined text-[0.875rem] text-outline-variant" id="icon-length">circle</span>
                                    <span class="text-[0.625rem] text-on-surface-variant">8–20 karakter</span>
                                </div>
                                <div class="flex items-center gap-1.5" id="req-upper">
                                    <span class="material-symbols-outlined text-[0.875rem] text-outline-variant" id="icon-upper">circle</span>
                                    <span class="text-[0.625rem] text-on-surface-variant">Huruf besar (A-Z)</span>
                                </div>
                                <div class="flex items-center gap-1.5" id="req-lower">
                                    <span class="material-symbols-outlined text-[0.875rem] text-outline-variant" id="icon-lower">circle</span>
                                    <span class="text-[0.625rem] text-on-surface-variant">Huruf kecil (a-z)</span>
                                </div>
                                <div class="flex items-center gap-1.5" id="req-number">
                                    <span class="material-symbols-outlined text-[0.875rem] text-outline-variant" id="icon-number">circle</span>
                                    <span class="text-[0.625rem] text-on-surface-variant">Angka (0-9)</span>
                                </div>
                                <div class="flex items-center gap-1.5" id="req-special">
                                    <span class="material-symbols-outlined text-[0.875rem] text-outline-variant" id="icon-special">circle</span>
                                    <span class="text-[0.625rem] text-on-surface-variant">Spesial (@$!%*#?&^)</span>
                                </div>
                                <div class="flex items-center gap-1.5" id="req-match">
                                    <span class="material-symbols-outlined text-[0.875rem] text-outline-variant" id="icon-match">circle</span>
                                    <span class="text-[0.625rem] text-on-surface-variant">Konfirmasi cocok</span>
                                </div>
                            </div>
                            <!-- Strength Bar -->
                            <div class="mt-2 w-full bg-outline-variant/20 rounded-full h-1.5">
                                <div id="pw-strength-bar" class="h-1.5 rounded-full transition-all duration-300 bg-outline-variant" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="pt-2 form-element">
                        <button class="w-full bg-primary-container text-on-primary-container font-headline-sm text-sm py-2.5 px-6 rounded-xl shadow-sm hover:opacity-90 active:scale-95 transition-all flex items-center justify-center gap-2 cursor-pointer" type="submit">
                            Daftar Akun Baru
                            <span class="material-symbols-outlined">arrow_forward</span>
                        </button>
                    </div>
                </form>

                <!-- Support Footer -->
                <footer class="mt-4 pt-3 border-t border-outline-variant text-center form-element">
                    <p class="font-body-sm text-on-surface-variant mb-1 text-[0.6875rem]">Sudah memiliki akun siswa?</p>
                    <a class="font-label-md text-primary hover:underline inline-flex items-center gap-1 font-semibold text-[0.6875rem]" href="{{ route('login') }}">
                        Masuk Ke Portal
                        <span class="material-symbols-outlined text-[0.875rem]">login</span>
                    </a>
                </footer>
            </div>

            <!-- Footer -->
            <div class="mt-4 text-center text-on-surface-variant/60 font-label-md text-xs shrink-0">
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
            gsap.set("#register-card", { opacity: 0, y: 30 });
            gsap.set(".form-element", { opacity: 0, y: 15 });

            // Execution
            tl.to("#register-card", {
                opacity: 1,
                y: 0,
                duration: 0.8,
                delay: 0.2
            })
            .to(".form-element", {
                opacity: 1,
                y: 0,
                duration: 0.5,
                stagger: 0.06
            }, "-=0.4");

            // Input interactions
            const inputs = document.querySelectorAll('input[type="text"], input[type="password"], input[type="email"]');
            inputs.forEach(input => {
                input.addEventListener('focus', () => {
                    gsap.to(input, { scale: 1.01, duration: 0.2 });
                });
                input.addEventListener('blur', () => {
                    gsap.to(input, { scale: 1, duration: 0.2 });
                });
            });

            // Password Toggle Visibility
            const toggleBtn = document.getElementById('toggle-pw');
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggle-pw-icon');
            if (toggleBtn && passwordInput && toggleIcon) {
                toggleBtn.addEventListener('click', () => {
                    const isPassword = passwordInput.type === 'password';
                    passwordInput.type = isPassword ? 'text' : 'password';
                    toggleIcon.innerText = isPassword ? 'visibility_off' : 'visibility';
                });
            }

            // Password Strength Indicator
            const pwInput = document.getElementById('password');
            const pwConfirm = document.getElementById('password_confirmation');
            const strengthBar = document.getElementById('pw-strength-bar');

            function checkReq(id, iconId, passed) {
                const icon = document.getElementById(iconId);
                if (passed) {
                    icon.innerText = 'check_circle';
                    icon.classList.remove('text-outline-variant', 'text-error');
                    icon.classList.add('text-primary');
                    icon.style.fontVariationSettings = "'FILL' 1";
                } else {
                    icon.innerText = 'circle';
                    icon.classList.remove('text-primary', 'text-error');
                    icon.classList.add('text-outline-variant');
                    icon.style.fontVariationSettings = "'FILL' 0";
                }
            }

            function validatePassword() {
                const val = pwInput.value;
                const confirmVal = pwConfirm.value;
                let score = 0;

                const hasLength = val.length >= 8 && val.length <= 20;
                const hasUpper = /[A-Z]/.test(val);
                const hasLower = /[a-z]/.test(val);
                const hasNumber = /[0-9]/.test(val);
                const hasSpecial = /[@$!%*#?&^]/.test(val);
                const hasMatch = val.length > 0 && confirmVal.length > 0 && val === confirmVal;

                checkReq('req-length', 'icon-length', hasLength);
                checkReq('req-upper', 'icon-upper', hasUpper);
                checkReq('req-lower', 'icon-lower', hasLower);
                checkReq('req-number', 'icon-number', hasNumber);
                checkReq('req-special', 'icon-special', hasSpecial);
                checkReq('req-match', 'icon-match', hasMatch);

                if (hasLength) score++;
                if (hasUpper) score++;
                if (hasLower) score++;
                if (hasNumber) score++;
                if (hasSpecial) score++;
                if (hasMatch) score++;

                const pct = Math.round((score / 6) * 100);
                strengthBar.style.width = pct + '%';

                if (pct <= 33) {
                    strengthBar.className = 'h-1.5 rounded-full transition-all duration-300 bg-error';
                } else if (pct <= 66) {
                    strengthBar.className = 'h-1.5 rounded-full transition-all duration-300 bg-[#F57F17]';
                } else {
                    strengthBar.className = 'h-1.5 rounded-full transition-all duration-300 bg-primary';
                }
            }

            pwInput.addEventListener('input', validatePassword);
            pwConfirm.addEventListener('input', validatePassword);

            // Form Submit Simulation with Native Submit
            const registerForm = document.getElementById('register-form');
            registerForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const btn = e.target.querySelector('button[type="submit"]');
                btn.disabled = true;
                btn.innerHTML = `<span class="material-symbols-outlined animate-spin text-sm">progress_activity</span> Memproses...`;
                
                setTimeout(() => {
                    registerForm.submit();
                }, 500);
            });
        });
    </script>
</body>
</html>
