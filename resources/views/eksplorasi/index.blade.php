<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

        <title>Ruang Eksplorasi - TARA</title>
        <link rel="icon" type="image/webp" href="{{ asset('assets/logotara.webp') }}">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Jua&display=swap" rel="stylesheet">

        <script>
            try {
                if (sessionStorage.getItem('app.booted') === '1') {
                    document.documentElement.classList.add('app-booted');
                }
            } catch {}
        </script>

        <style>
            .menu-circle-btn {
                transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.2s ease, filter 0.2s ease;
            }

            .menu-circle-btn:hover {
                transform: translateY(-4px) scale(1.04);
                filter: brightness(1.05);
            }

            .menu-circle-btn:active {
                transform: translateY(2px) scale(0.96);
            }
        </style>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-[100dvh] h-[100dvh] overflow-hidden font-['Jua'] select-none">
        @php
            $bg = asset('assets/berandamalam.webp');
        @endphp

        <div class="landscape-force">
            <div data-app-root data-content style="background-image: url('{{ $bg }}'); background-size: cover; background-position: center;">
                
                <!-- TOP BAR KIRI: TOMBOL KEMBALI KE BERANDA -->
                <x-btn-back href="{{ route('menu') }}" class="absolute top-3 left-3 sm:top-5 sm:left-5 z-40" title="Kembali ke Beranda" />

                <!-- TOP BAR KANAN: SOUND SETTINGS & EXIT -->
                <div class="absolute top-3 right-3 sm:top-4 sm:right-5 md:top-5 md:right-6 z-40 flex items-center gap-2 sm:gap-3">
                    <x-btn-settings onclick="openSettingsModal()" title="Pengaturan Suara" />
                    <x-btn-exit onclick="handleExitWeb()" title="Keluar" />
                </div>

                <!-- KARAKTER YUK DAMAR DI SISI KIRI (DITENGGELAMKAN ~25%) -->
                <div class="absolute z-20 pointer-events-none bottom-0 left-0 sm:left-2 md:left-4 lg:left-6 h-[90%] sm:h-[96%] md:h-[102%] lg:h-[108%] max-h-[800px] flex items-end translate-y-[25%]">
                    <img src="{{ asset('assets/karakter_menyapa.webp') }}" 
                         alt="Yuk Damar Menyapa" 
                         class="h-full w-auto max-h-full object-contain object-bottom drop-shadow-[0_18px_32px_rgba(0,0,0,0.4)] select-none pointer-events-none">
                </div>

                <!-- CENTER 2 TOMBOL SUB-MENU LINGKARAN BESAR -->
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-30 flex items-center justify-center gap-6 sm:gap-10 md:gap-14 lg:gap-20 w-auto max-w-[95vw]">
                    
                    <!-- SUB TOMBOL 1: DAMAR KURUNGKU (CORAL / ORANGE CIRCLE) -->
                    <div class="flex flex-col items-center">
                        <a href="{{ route('eksplorasi.damar-kurung') }}" 
                           class="menu-circle-btn w-24 h-24 sm:w-32 sm:h-32 md:w-40 md:h-40 lg:w-48 lg:h-48 rounded-full border-[6px] sm:border-[8px] md:border-[10px] border-white p-3 sm:p-4 flex items-center justify-center shadow-[0_14px_32px_rgba(0,0,0,0.32)] cursor-pointer focus:outline-none"
                           style="background-color: #E57354;"
                           title="Eksplorasi Damar Kurungku">
                            <!-- Damar Kurung Lantern Illustration SVG -->
                            <svg class="w-full h-full drop-shadow-md" viewBox="0 0 100 100" fill="none">
                                <polygon points="50,12 24,34 76,34" fill="#6B2E09" stroke="#E29E4B" stroke-width="2"/>
                                <polygon points="50,15 28,33 72,33" fill="#8D4213"/>
                                <circle cx="50" cy="11" r="3" fill="#FBBF24"/>
                                
                                <rect x="25" y="34" width="50" height="42" fill="#FDF8E1" stroke="#522204" stroke-width="2.5" rx="2"/>
                                <rect x="28" y="37" width="44" height="36" fill="#FCE99B" opacity="0.9"/>
                                
                                <rect x="33" y="44" width="14" height="24" fill="#E8963D" opacity="0.4" rx="1"/>
                                <rect x="53" y="44" width="14" height="24" fill="#E8963D" opacity="0.4" rx="1"/>
                                <circle cx="40" cy="50" r="3.5" fill="#78350F"/>
                                <path d="M36 62c0-3 2-5 4-5s4 2 4 5z" fill="#78350F"/>
                                <circle cx="60" cy="50" r="3.5" fill="#78350F"/>
                                <path d="M56 62c0-3 2-5 4-5s4 2 4 5z" fill="#78350F"/>
                                <line x1="50" y1="34" x2="50" y2="76" stroke="#522204" stroke-width="2.5"/>
                                
                                <polygon points="23,76 77,76 72,82 28,82" fill="#6B2E09" stroke="#522204" stroke-width="1.5"/>
                                <polygon points="34,82 37,90 31,90" fill="#D97706"/>
                                <polygon points="50,82 53,92 47,92" fill="#D97706"/>
                                <polygon points="66,82 69,90 63,90" fill="#D97706"/>
                            </svg>
                        </a>
                        <!-- Pill Label Bawah -->
                        <div class="mt-2.5 sm:mt-3.5 px-4 sm:px-6 md:px-8 py-1 sm:py-1.5 rounded-full bg-white shadow-[0_4px_14px_rgba(0,0,0,0.18)] border border-stone-100 flex items-center justify-center">
                            <span class="font-['Jua'] text-xs sm:text-base md:text-lg lg:text-xl font-bold text-[#2E2E2E] tracking-wide whitespace-nowrap">
                                Damar Kurungku
                            </span>
                        </div>
                    </div>

                    <!-- SUB TOMBOL 2: AYO TEMUKAN BANGUNNYA! (FOREST / JADE GREEN CIRCLE) -->
                    <div class="flex flex-col items-center">
                        <a href="{{ route('eksplorasi.bangun-datar') }}" 
                           class="menu-circle-btn w-24 h-24 sm:w-32 sm:h-32 md:w-40 md:h-40 lg:w-48 lg:h-48 rounded-full border-[6px] sm:border-[8px] md:border-[10px] border-white p-3 sm:p-4 flex items-center justify-center shadow-[0_14px_32px_rgba(0,0,0,0.32)] cursor-pointer focus:outline-none"
                           style="background-color: #548466;"
                           title="Ayo Temukan Bangun Datar">
                            <!-- 4 Geometric Shapes Illustration SVG -->
                            <svg class="w-full h-full drop-shadow-md" viewBox="0 0 100 100" fill="none">
                                <polygon points="32,18 16,46 48,46" fill="#FCD34D" stroke="#D97706" stroke-width="2.5" stroke-linejoin="round"/>
                                <polygon points="32,24 22,43 42,43" fill="#FDE68A" opacity="0.7"/>

                                <rect x="54" y="20" width="28" height="28" rx="4" fill="#F97316" stroke="#C2410C" stroke-width="2.5"/>
                                <line x1="63" y1="20" x2="63" y2="48" stroke="#FED7AA" stroke-width="1.5" stroke-dasharray="2,2"/>
                                <line x1="73" y1="20" x2="73" y2="48" stroke="#FED7AA" stroke-width="1.5" stroke-dasharray="2,2"/>
                                <line x1="54" y1="29" x2="82" y2="29" stroke="#FED7AA" stroke-width="1.5" stroke-dasharray="2,2"/>
                                <line x1="54" y1="39" x2="82" y2="39" stroke="#FED7AA" stroke-width="1.5" stroke-dasharray="2,2"/>

                                <circle cx="32" cy="70" r="15" fill="#38BDF8" stroke="#0284C7" stroke-width="2.5"/>
                                <circle cx="32" cy="70" r="10" stroke="#E0F2FE" stroke-width="1.5" stroke-dasharray="2,2"/>
                                <circle cx="32" cy="70" r="5" fill="#BAE6FD"/>

                                <rect x="52" y="58" width="34" height="24" rx="4" fill="#C084FC" stroke="#7E22CE" stroke-width="2.5"/>
                                <line x1="60" y1="58" x2="60" y2="82" stroke="#F3E8FF" stroke-width="1.5"/>
                                <line x1="69" y1="58" x2="69" y2="82" stroke="#F3E8FF" stroke-width="1.5"/>
                                <line x1="77" y1="58" x2="77" y2="82" stroke="#F3E8FF" stroke-width="1.5"/>
                                <line x1="52" y1="70" x2="86" y2="70" stroke="#F3E8FF" stroke-width="1.5"/>
                            </svg>
                        </a>
                        <!-- Pill Label Bawah -->
                        <div class="mt-2.5 sm:mt-3.5 px-3.5 sm:px-5 md:px-6 py-1 sm:py-1.5 rounded-full bg-white shadow-[0_4px_14px_rgba(0,0,0,0.18)] border border-stone-100 flex items-center justify-center">
                            <span class="font-['Jua'] text-xs sm:text-base md:text-lg lg:text-xl font-bold text-[#2E2E2E] tracking-wide whitespace-nowrap">
                                Ayo Temukan Bangunnya!
                            </span>
                        </div>
                    </div>

                </div>

                <!-- SOUND SETTINGS (MODAL ONLY) -->
                <x-sound-settings :renderTrigger="false" />

                <!-- EXIT CONFIRMATION MODAL -->
                <x-exit-modal />

            </div>
        </div>
    </body>
</html>
