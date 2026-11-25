<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full overflow-hidden">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

        <title>TARA - Media Pembelajaran Etnomatematika Bangun Datar</title>
        <meta name="description" content="TARA - Media Pembelajaran Interaktif Etnomatematika Bangun Datar Damar Kurung Bojonegoro">
        <meta name="robots" content="noindex, nofollow">
        <meta property="og:title" content="TARA - Media Pembelajaran Etnomatematika">
        <meta property="og:description" content="Media pembelajaran interaktif berbasis Damar Kurung untuk siswa SD/SMP.">
        <meta property="og:type" content="website">
        <link rel="icon" type="image/webp" href="{{ asset('assets/logotara.webp') }}">
        <link rel="apple-touch-icon" href="{{ asset('assets/logotara.webp') }}">

        <!-- Preload Essential Preloader Assets -->
        <link rel="preload" as="image" href="{{ asset('assets/loadingscreen.webp') }}" type="image/webp">
        <link rel="preload" as="image" href="{{ asset('assets/logotara.webp') }}" type="image/webp">
        <link rel="preload" as="image" href="{{ asset('assets/karakter_menyapa.webp') }}" type="image/webp">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Jua&display=swap" rel="stylesheet">

        <script>
            try {
                sessionStorage.removeItem('app.booted');
            } catch (e) {}
            window.__TARA_BGM_URL = "{{ asset('assets/bgm.mp3') }}";
            window.__TARA_ALL_ASSETS = [
                "{{ asset('assets/bgm.mp3') }}",
                "{{ asset('assets/loadingscreen.webp') }}",
                "{{ asset('assets/berandamalam.webp') }}",
                "{{ asset('assets/logotara.webp') }}",
                "{{ asset('assets/karakter_menyapa.webp') }}",
                "{{ asset('assets/karakter_jempol.webp') }}",
                "{{ asset('assets/karakter_ide.webp') }}",
                "{{ asset('assets/karakter_default.webp') }}",
                "{{ asset('assets/karakter_berpikir.webp') }}",
                "{{ asset('assets/mulai.webp') }}",
                "{{ asset('assets/tema_religi.webp') }}",
                "{{ asset('assets/tema_kehidupansosial.webp') }}",
                "{{ asset('assets/tema_kesenian.webp') }}",
                "{{ asset('assets/tema_adat.webp') }}",
                "{{ asset('assets/dosen.jpeg') }}",
                "{{ asset('assets/wulan.jpeg') }}",
                "{{ asset('assets/home_button.webp') }}",
                "{{ asset('assets/left_button.webp') }}",
                "{{ asset('assets/right_button.webp') }}"
            ];
            window.__TARA_GLB_ASSETS = []; // GLB dimuat lazy saat halaman Damar Kurung dibuka
            window.__TARA_PREFETCH_ROUTES = [];
        </script>

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles

        <style>
            @keyframes lanternGlow {
                0%, 100% {
                    transform: scale(1) translateY(0);
                    filter: drop-shadow(0 0 15px rgba(245, 158, 11, 0.7)) drop-shadow(0 0 30px rgba(217, 119, 6, 0.4));
                }
                50% {
                    transform: scale(1.04) translateY(-5px);
                    filter: drop-shadow(0 0 25px rgba(251, 191, 36, 0.9)) drop-shadow(0 0 45px rgba(245, 158, 11, 0.6));
                }
            }

            .animate-lantern {
                animation: lanternGlow 3s ease-in-out infinite;
            }

            .bubbly-loading-title {
                font-family: 'Jua', sans-serif;
                color: #F59E0B;
                -webkit-text-stroke: 4px #FFFFFF;
                paint-order: stroke fill;
                filter: drop-shadow(0 4px 10px rgba(180, 83, 9, 0.45));
                letter-spacing: 2px;
            }
        </style>
    </head>
    <body class="antialiased font-['Jua'] select-none">
        
        <div class="landscape-force">
            <!-- 1. GLOBAL PRELOADER / LOADING SCREEN (Tema TARA Damar Kurung) -->
            <div id="globalAppPreloader" class="absolute inset-0 z-50 flex flex-col items-center justify-between overflow-hidden select-none bg-cover bg-center"
                 style="background: #2D1406 url('{{ asset('assets/loadingscreen.webp') }}') center / cover no-repeat;">
                
                <!-- Top Logo TARA -->
                <div class="w-full flex flex-col items-center pt-6 sm:pt-8 md:pt-10 z-10">
                    <img src="{{ asset('assets/logotara.webp') }}" 
                         alt="TARA Logo" 
                         class="w-[280px] sm:w-[400px] md:w-[500px] lg:w-[580px] h-auto drop-shadow-2xl animate-pulse">
                </div>

                <!-- Center Progress Area (Tema TARA Damar Kurung) -->
                <div class="flex flex-col items-center justify-center w-full max-w-[320px] sm:max-w-[420px] md:max-w-[480px] px-6 z-30 my-auto">
                    
                    <!-- Title: LOADING (Bubbly warm golden amber text with thick white stroke & glow) -->
                    <div id="loaderText" class="bubbly-loading-title font-black text-3xl sm:text-4xl md:text-5xl mb-3 sm:mb-4 tracking-widest text-center select-none font-['Jua']">
                        LOADING
                    </div>
                    
                    <!-- Capsule Progress Bar (Tema Damar Kurung) -->
                    <div class="relative w-full">
                        <!-- Warm Ivory Capsule Bar Container with Golden Amber Border -->
                        <div class="w-full h-8 sm:h-9 md:h-10 bg-white/95 rounded-full border-[3.5px] sm:border-[4px] border-[#F59E0B] p-1 shadow-[0_6px_22px_rgba(217,119,6,0.35)] flex items-center overflow-hidden relative z-10">
                            <div id="loaderProgressBar" 
                                 class="h-full bg-gradient-to-r from-[#FBBF24] via-[#F59E0B] to-[#D97706] rounded-full transition-all duration-200 ease-out shadow-xs"
                                 style="width: 0%;"></div>
                        </div>
                    </div>
                    
                    <!-- Status & Percentage below bar -->
                    <div class="flex flex-col items-center justify-center mt-3 gap-1">
                        <span id="loaderPercent" class="text-base sm:text-lg font-bold text-[#78350F] tracking-wider select-none font-['Jua'] drop-shadow-xs">
                            0%
                        </span>
                        <span id="loaderStatus" class="text-xs sm:text-sm text-[#92400E] font-medium tracking-wide select-none font-['Jua']">
                            Menyiapkan media pembelajaran...
                        </span>
                    </div>
                </div>

                <!-- Karakter Kiri Bawah (Mascot Yuk Damar) -->
                <div class="absolute -bottom-2 sm:-bottom-4 left-0 sm:left-3 md:left-6 z-20 pointer-events-none">
                    <img src="{{ asset('assets/karakter_menyapa.webp') }}" 
                         alt="Yuk Damar" 
                         class="h-[52vh] sm:h-[62vh] md:h-[72vh] lg:h-[80vh] max-h-[560px] w-auto drop-shadow-[0_14px_24px_rgba(0,0,0,0.45)] select-none">
                </div>
            </div>

            <!-- 2. SPLASH SCREEN (Livewire Beranda Component) -->
            <div data-app-root data-content style="background-image: url('{{ asset('assets/loadingscreen.webp') }}'); visibility: hidden;">
                <livewire:beranda />
            </div>
        </div>

        <script>
            (function() {
                try {
                    sessionStorage.removeItem('app.booted');
                } catch (e) {}

                var preloader = document.getElementById('globalAppPreloader');
                var bar = document.getElementById('loaderProgressBar');
                var txt = document.getElementById('loaderPercent');
                var root = document.querySelector('[data-app-root]');

                // Function dipanggil saat seluruh asset 100% ter-load
                window.dismissAppPreloader = function() {
                    if (bar) bar.style.width = '100%';
                    if (txt) txt.textContent = '100%';
                    var status = document.getElementById('loaderStatus');
                    if (status) status.textContent = 'Memuat selesai! Siap bereksplorasi!';

                    // Tunda sejenak agar siswa melihat angka 100%, lalu buka halaman mulai
                    setTimeout(function() {
                        if (root) root.style.visibility = 'visible';
                        if (preloader) {
                            preloader.style.opacity = '0';
                            preloader.style.transition = 'opacity 400ms ease-out, transform 400ms ease-out';
                            preloader.style.transform = 'scale(1.02)';
                            preloader.style.pointerEvents = 'none';
                            setTimeout(function() {
                                preloader.style.display = 'none';
                            }, 450);
                        }
                    }, 350);
                };
            })();
        </script>
        
        @livewireScripts
    </body>
</html>
