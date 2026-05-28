<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Teman BK - Splash</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <!-- GSAP for Animations -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Inter:wght@400;600&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "outline": "#6f7979",
                        "on-surface": "#1a1c1b",
                        "on-primary-container": "#9be7e6",
                        "on-primary-fixed-variant": "#004f50",
                        "error": "#ba1a1a",
                        "tertiary-container": "#8b502f",
                        "inverse-surface": "#2f3130",
                        "error-container": "#ffdad6",
                        "primary-fixed-dim": "#88d3d3",
                        "on-tertiary": "#ffffff",
                        "on-error": "#ffffff",
                        "surface-container": "#eeeeec",
                        "outline-variant": "#bec9c8",
                        "on-tertiary-container": "#ffd0b9",
                        "surface-container-high": "#e8e8e6",
                        "on-primary-fixed": "#002020",
                        "on-primary": "#ffffff",
                        "inverse-primary": "#88d3d3",
                        "on-tertiary-fixed-variant": "#6e3819",
                        "on-tertiary-fixed": "#341100",
                        "surface-bright": "#f9f9f7",
                        "on-secondary-fixed-variant": "#6a3a2e",
                        "surface-dim": "#dadad8",
                        "primary-container": "#106a6a",
                        "surface-tint": "#0e6969",
                        "secondary-container": "#feb9a9",
                        "inverse-on-surface": "#f1f1ef",
                        "surface-variant": "#e2e3e1",
                        "on-secondary-fixed": "#351007",
                        "secondary-fixed-dim": "#fbb6a6",
                        "on-error-container": "#93000a",
                        "primary": "#005050",
                        "on-background": "#1a1c1b",
                        "surface-container-highest": "#e2e3e1",
                        "secondary-fixed": "#ffdad2",
                        "tertiary": "#6e391a",
                        "surface": "#f9f9f7",
                        "primary-fixed": "#a4f0ef",
                        "on-secondary": "#ffffff",
                        "tertiary-fixed-dim": "#ffb690",
                        "background": "#f9f9f7",
                        "surface-container-lowest": "#ffffff",
                        "surface-container-low": "#f4f4f2",
                        "tertiary-fixed": "#ffdbca",
                        "secondary": "#865044",
                        "on-surface-variant": "#3f4948",
                        "on-secondary-container": "#7a473b"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "gutter": "24px",
                        "container-padding": "32px",
                        "section-margin": "48px",
                        "unit": "4px",
                        "card-gap": "24px"
                    },
                    "fontFamily": {
                        "headline-md": ["Manrope"],
                        "headline-lg": ["Manrope"],
                        "body-sm": ["Inter"],
                        "headline-sm": ["Manrope"],
                        "label-md": ["Inter"],
                        "body-md": ["Inter"],
                        "body-lg": ["Inter"],
                        "display-lg": ["Manrope"]
                    },
                    "fontSize": {
                        "headline-md": ["24px", {"lineHeight": "1.3", "fontWeight": "600"}],
                        "headline-lg": ["32px", {"lineHeight": "1.25", "fontWeight": "600"}],
                        "body-sm": ["14px", {"lineHeight": "1.5", "fontWeight": "400"}],
                        "headline-sm": ["20px", {"lineHeight": "1.4", "fontWeight": "600"}],
                        "label-md": ["12px", {"lineHeight": "1.2", "letterSpacing": "0.05em", "fontWeight": "600"}],
                        "body-md": ["16px", {"lineHeight": "1.6", "fontWeight": "400"}],
                        "body-lg": ["18px", {"lineHeight": "1.6", "fontWeight": "400"}],
                        "display-lg": ["48px", {"lineHeight": "1.2", "letterSpacing": "-0.02em", "fontWeight": "700"}]
                    }
                },
            },
        }
    </script>
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
            <div class="absolute w-24 h-24 border border-primary/20 rounded-full" id="ring-1"></div>
            <div class="absolute w-32 h-32 border border-primary/10 rounded-full" id="ring-2"></div>
            <!-- The Brand Identity (Using the original YPML image logo) -->
            <div class="bg-white p-5 rounded-3xl shadow-xl flex items-center justify-center" id="logo-icon">
                <img src="{{ asset('img/logo-ypml.png') }}" alt="Logo YPML" class="h-16 w-auto">
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
