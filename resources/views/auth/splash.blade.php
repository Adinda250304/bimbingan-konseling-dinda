<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Teman BK - Splash</title>
    <!-- Tailwind CSS via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- GSAP for Animations -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Inter:wght@400;600&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        
        .silk-gradient {
            background: radial-gradient(circle at 50% 50%, #ffffff 0%, #f9f9f7 100%);
        }

        .blur-orb {
            filter: blur(80px);
            opacity: 0.4;
            background: #106a6a;
        }
    </style>
</head>
<body class="bg-surface text-on-surface overflow-hidden silk-gradient h-screen w-full flex items-center justify-center">

    <!-- Subtle background atmosphere -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full blur-orb" id="orb-1"></div>
        <div class="absolute bottom-1/4 -right-24 w-80 h-80 rounded-full blur-orb" id="orb-2" style="background: #feb9a9; opacity: 0.2;"></div>
    </div>

    <!-- Splash Content -->
    <main class="relative z-10 flex flex-col items-center justify-center text-center px-container-padding">
        <!-- Logo Container -->
        <div class="mb-gutter relative flex items-center justify-center" id="logo-shell">
            <!-- Pulsing Rings (Visual Atmosphere) -->
            <div class="absolute w-32 h-32 border border-primary/20 rounded-full" id="ring-1"></div>
            <div class="absolute w-40 h-40 border border-primary/10 rounded-full" id="ring-2"></div>
            <!-- The Brand Identity (Using the original YPML image logo) -->
            <div class="flex items-center justify-center z-10" id="logo-icon">
                <img src="{{ asset('img/logo-ypml.png') }}" alt="Logo YPML" class="h-24 w-auto drop-shadow-xl">
            </div>
        </div>

        <!-- Typography Branding -->
        <div class="space-y-unit overflow-hidden" id="brand-text-container">
            <h1 class="font-headline-lg text-headline-lg text-primary tracking-tight font-extrabold" id="brand-name">
                TEMAN BK
            </h1>
            <p class="font-body-md text-body-md text-on-surface-variant max-w-xs opacity-0" id="tagline">
                Bimbingan &amp; Konseling YPML
            </p>
        </div>

        <!-- Progress Indicator (Minimalist) -->
        <div class="mt-section-margin w-48 h-[2px] bg-outline-variant/30 rounded-full overflow-hidden relative" id="loader-track">
            <div class="absolute left-0 top-0 h-full w-0 bg-primary rounded-full" id="loader-bar"></div>
        </div>

        <!-- Footer context -->
        <div class="fixed bottom-gutter w-full flex justify-center opacity-0" id="splash-footer">
            <span class="font-label-md text-label-md text-outline tracking-widest uppercase">SMK YPML • TEMAN BK</span>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // GSAP Timeline with route redirect on complete
            const tl = gsap.timeline({
                onComplete: () => {
                    window.location.href = "{{ route('welcome') }}";
                }
            });

            // 1. Initial State
            gsap.set("#logo-icon", { scale: 0.8, opacity: 0 });
            gsap.set("#brand-name", { y: 40, opacity: 0 });
            gsap.set("#tagline", { y: 20, opacity: 0 });
            gsap.set("#loader-track", { opacity: 0, scaleX: 0 });
            gsap.set("#ring-1, #ring-2", { scale: 0.5, opacity: 0 });

            // 2. Entrance Animation
            tl.to("#logo-icon", {
                opacity: 1,
                scale: 1,
                duration: 1.2,
                ease: "expo.out"
            })
            .to("#brand-name", {
                opacity: 1,
                y: 0,
                duration: 1,
                ease: "power3.out"
            }, "-=0.6")
            .to("#tagline", {
                opacity: 1,
                y: 0,
                duration: 1,
                ease: "power3.out"
            }, "-=0.8")
            .to("#loader-track", {
                opacity: 1,
                scaleX: 1,
                duration: 0.8,
                ease: "back.out(1.7)"
            }, "-=0.4")
            .to("#splash-footer", {
                opacity: 1,
                duration: 1.5
            }, "-=0.2");

            // 3. Subtle Atmospheric Pulse for Rings
            gsap.to("#ring-1", {
                scale: 1.5,
                opacity: 1,
                duration: 2,
                repeat: -1,
                ease: "sine.inOut",
                yoyo: true
            });
            gsap.to("#ring-2", {
                scale: 1.8,
                opacity: 0.6,
                duration: 2.5,
                delay: 0.3,
                repeat: -1,
                ease: "sine.inOut",
                yoyo: true
            });

            // 4. Loading Progress Simulation
            tl.to("#loader-bar", {
                width: "100%",
                duration: 3,
                ease: "power2.inOut"
            }, "-=1");

            // 5. Floating Orbs Movement
            gsap.to("#orb-1", {
                x: 40,
                y: 20,
                duration: 8,
                repeat: -1,
                yoyo: true,
                ease: "sine.inOut"
            });
            gsap.to("#orb-2", {
                x: -30,
                y: -40,
                duration: 10,
                repeat: -1,
                yoyo: true,
                ease: "sine.inOut"
            });
        });
    </script>
</body>
</html>
