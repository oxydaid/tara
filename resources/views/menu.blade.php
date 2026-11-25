<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

        <title>Beranda Menu Utama - TARA</title>
        <link rel="icon" type="image/webp" href="{{ asset('assets/logotara.webp') }}">

        <!-- Preload Essential Assets for Zero-Delay Render -->
        <link rel="preload" as="image" href="{{ asset('assets/berandamalam.webp') }}" type="image/webp">
        <link rel="preload" as="image" href="{{ asset('assets/karakter_default.webp') }}" type="image/webp">

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
            html, body, .landscape-force {
                background-color: #3D1804;
                background-image: url('{{ asset('assets/berandamalam.webp') }}');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
            }

            /* Circular Menu Buttons */
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

            .wood-text-shadow {
                text-shadow: 2px 2px 0 #3D1804, -2px -2px 0 #3D1804, 2px -2px 0 #3D1804, -2px 2px 0 #3D1804, 0 3px 5px rgba(0,0,0,0.5);
            }
        </style>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-[100dvh] h-[100dvh] overflow-hidden font-['Jua'] select-none" style="background-color: #3D1804;">
        
        @php
            $menuBackground = asset('assets/berandamalam.webp');
        @endphp

        <div class="landscape-force" style="background-color: #3D1804; background-image: url('{{ $menuBackground }}'); background-size: cover; background-position: center;">
            <div data-app-root data-content style="background-color: #3D1804; background-image: url('{{ $menuBackground }}'); background-size: cover; background-position: center;">
                
                <!-- ============================================================
                     1. TOP BAR: PROFIL / SOUND / EXIT (POJOK KANAN ATAS - SINGLE BUTTON SET)
                     ============================================================ -->
                <div class="absolute top-3 right-3 sm:top-4 sm:right-5 md:top-5 md:right-6 z-40 flex items-center gap-2 sm:gap-3">
                    <x-btn-profile onclick="openProfileModal()" title="Identitas Siswa" />
                    <x-btn-settings onclick="openSettingsModal()" title="Pengaturan Suara" />
                    <x-btn-exit onclick="handleExitWeb()" title="Keluar" />
                </div>

                <!-- ============================================================
                     2. KARAKTER YUK DAMAR DI SISI KIRI (DITENGGELAMKAN ~25%)
                     ============================================================ -->
                <div class="absolute z-20 pointer-events-none bottom-0 left-0 sm:left-2 md:left-4 lg:left-6 h-[90%] sm:h-[96%] md:h-[102%] lg:h-[108%] max-h-[800px] flex items-end translate-y-[25%]">
                    <img src="{{ asset('assets/karakter_default.webp') }}" 
                         alt="Yuk Damar" 
                         class="h-full w-auto max-h-full object-contain object-bottom drop-shadow-[0_18px_32px_rgba(0,0,0,0.4)] select-none pointer-events-none">
                </div>

                <!-- ============================================================
                     3. TAMPILAN 4 MENU UTAMA (CENTER DI TENGAH HALAMAN)
                     ============================================================ -->
                <div id="mainMenuStage" class="absolute top-1/2 left-[57%] sm:left-[54%] md:left-1/2 -translate-x-1/2 -translate-y-1/2 z-30 flex items-center justify-center gap-2.5 sm:gap-5 md:gap-7 lg:gap-10 w-auto max-w-[82vw] sm:max-w-[90vw] md:max-w-[95vw] transition-all duration-300">
                    
                    <!-- MENU 1: PROFIL (CORAL / ORANGE CIRCLE) -->
                    <div class="flex flex-col items-center">
                        <a href="{{ route('profil') }}" 
                           class="menu-circle-btn w-16 h-16 sm:w-24 sm:h-24 md:w-32 md:h-32 lg:w-40 lg:h-40 rounded-full border-[4px] sm:border-[6px] md:border-[8px] border-white p-2 sm:p-3 flex items-center justify-center shadow-[0_12px_28px_rgba(0,0,0,0.3)] cursor-pointer focus:outline-none"
                           style="background-color: #E57354;"
                           title="Profil Pembelajaran & Deskripsi Media">
                            <!-- Icon Profil Siluet Putih -->
                            <svg class="w-8 h-8 sm:w-12 sm:h-12 md:w-16 md:h-16 lg:w-20 lg:h-20 text-white drop-shadow-sm" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 12c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm0 2.5c-3.33 0-10 1.67-10 5v2.5h20v-2.5c0-3.33-6.67-5-10-5z"/>
                            </svg>
                        </a>
                        <!-- Pill Label Bawah -->
                        <div class="mt-2 sm:mt-3 px-3 sm:px-5 md:px-7 py-1 sm:py-1.5 rounded-full bg-white shadow-[0_4px_14px_rgba(0,0,0,0.18)] border border-stone-100 flex items-center justify-center">
                            <span class="font-['Jua'] text-[11px] sm:text-sm md:text-base lg:text-xl font-bold text-[#2E2E2E] tracking-wide whitespace-nowrap">
                                Profil
                            </span>
                        </div>
                    </div>

                    <!-- MENU 2: RUANG EKSPLORASI (FOREST / JADE GREEN CIRCLE) -->
                    <div class="flex flex-col items-center">
                        <a href="{{ route('eksplorasi') }}" 
                           class="menu-circle-btn w-16 h-16 sm:w-24 sm:h-24 md:w-32 md:h-32 lg:w-40 lg:h-40 rounded-full border-[4px] sm:border-[6px] md:border-[8px] border-white p-2 sm:p-3 flex items-center justify-center shadow-[0_12px_28px_rgba(0,0,0,0.3)] cursor-pointer focus:outline-none"
                           style="background-color: #548466;"
                           title="Masuk ke Ruang Eksplorasi">
                            <!-- Icon Buku Terbuka Siluet Putih -->
                            <svg class="w-8 h-8 sm:w-12 sm:h-12 md:w-16 md:h-16 lg:w-20 lg:h-20 text-white drop-shadow-sm" viewBox="0 0 36 36" fill="currentColor">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M6 7C6 5.89543 6.89543 5 8 5H17C17.5523 5 18 5.44772 18 6V28.5C17.4477 28.5 16.9067 28.3262 16.4472 28.0006L16.4144 27.9773C15.0934 27.0396 13.5042 26.5417 11.875 26.5417H8C6.89543 26.5417 6 25.6462 6 24.5417V7ZM18 6C18 5.44772 18.4477 5 19 5H28C29.1046 5 30 5.89543 30 7V24.5417C30 25.6462 29.1046 26.5417 28 26.5417H24.125C22.4958 26.5417 20.9066 27.0396 19.5856 27.9773L19.5528 28.0006C19.0933 28.3262 18.5523 28.5 18 28.5V6ZM10 10C9.44772 10 9 10.4477 9 11C9 11.5523 9.44772 12 10 12H15C15.5523 12 16 11.5523 16 11C16 10.4477 15.5523 10 15 10H10ZM9 15C9 14.4477 9.44772 14 10 14H15C15.5523 14 16 14.4477 16 15C16 15.5523 15.5523 16 15 16H10C9.44772 16 9 15.5523 9 15ZM10 18C9.44772 18 9 18.4477 9 19C9 19.5523 9.44772 20 10 20H13C13.5523 20 14 19.5523 14 19C14 18.4477 13.5523 18 13 18H10ZM20 11C20 10.4477 20.4477 10 21 10H26C26.5523 10 27 10.4477 27 11C27 11.5523 26.5523 12 26 12H21C20.4477 12 20 11.5523 20 11ZM21 14C20.4477 14 20 14.4477 20 15C20 15.5523 20.4477 16 21 16H26C26.5523 16 27 15.5523 27 15C27 14.4477 26.5523 14 26 14H21ZM20 19C20 18.4477 20.4477 18 21 18H24C24.5523 18 25 18.4477 25 19C25 19.5523 24.5523 20 24 20H21C20.4477 20 20 19.5523 20 19Z"/>
                            </svg>
                        </a>
                        <!-- Pill Label Bawah -->
                        <div class="mt-2 sm:mt-3 px-2.5 sm:px-4 md:px-6 py-1 sm:py-1.5 rounded-full bg-white shadow-[0_4px_14px_rgba(0,0,0,0.18)] border border-stone-100 flex items-center justify-center">
                            <span class="font-['Jua'] text-[11px] sm:text-sm md:text-base lg:text-xl font-bold text-[#2E2E2E] tracking-wide whitespace-nowrap">
                                Ruang Eksplorasi
                            </span>
                        </div>
                    </div>

                    <!-- MENU 3: KOLABORASI (GOLD / HONEY YELLOW CIRCLE) -->
                    <div class="flex flex-col items-center">
                        <a href="{{ route('kolaborasi') }}" 
                           class="menu-circle-btn w-16 h-16 sm:w-24 sm:h-24 md:w-32 md:h-32 lg:w-40 lg:h-40 rounded-full border-[4px] sm:border-[6px] md:border-[8px] border-white p-2 sm:p-3 flex items-center justify-center shadow-[0_12px_28px_rgba(0,0,0,0.3)] cursor-pointer focus:outline-none"
                           style="background-color: #ECA144;"
                           title="Masuk ke Tim Kolaborasi">
                            <!-- Icon Tim / Kolaborasi 3 Orang Siluet Putih -->
                            <svg class="w-8 h-8 sm:w-12 sm:h-12 md:w-16 md:h-16 lg:w-20 lg:h-20 text-white drop-shadow-sm" viewBox="0 0 36 36" fill="currentColor">
                                <circle cx="18" cy="11" r="4.5"/>
                                <path d="M18 17.5c-4 0-8.5 2-8.5 5.5v2h17v-2c0-3.5-4.5-5.5-8.5-5.5z"/>
                                <circle cx="9.5" cy="13" r="3.5"/>
                                <path d="M9.5 18.5c-1.5 0-3.2.4-4.5 1.1v2.9h4.2c-.4-.7-.7-1.5-.7-2.5 0-.5.1-1 .3-1.5h.7z"/>
                                <circle cx="26.5" cy="13" r="3.5"/>
                                <path d="M26.5 18.5c-.2.5-.3 1-.3 1.5 0 1 .3 1.8.7 2.5h4.1v-2.9c-1.3-.7-3-1.1-4.5-1.1z"/>
                            </svg>
                        </a>
                        <!-- Pill Label Bawah -->
                        <div class="mt-2 sm:mt-3 px-3 sm:px-5 md:px-7 py-1 sm:py-1.5 rounded-full bg-white shadow-[0_4px_14px_rgba(0,0,0,0.18)] border border-stone-100 flex items-center justify-center">
                            <span class="font-['Jua'] text-[11px] sm:text-sm md:text-base lg:text-xl font-bold text-[#2E2E2E] tracking-wide whitespace-nowrap">
                                Kolaborasi
                            </span>
                        </div>
                    </div>

                    <!-- MENU 4: CEK HASIL (PURPLE CIRCLE DI SEBELAH KOLABORASI) -->
                    <div class="flex flex-col items-center">
                        <a href="{{ route('hasil') }}" 
                           class="menu-circle-btn w-16 h-16 sm:w-24 sm:h-24 md:w-32 md:h-32 lg:w-40 lg:h-40 rounded-full border-[4px] sm:border-[6px] md:border-[8px] border-white p-2 sm:p-3 flex items-center justify-center shadow-[0_12px_28px_rgba(0,0,0,0.3)] cursor-pointer focus:outline-none"
                           style="background-color: #8B5CF6;"
                           title="Cek Hasil Kolaborasi Kelompok">
                            <!-- Icon Papan Hasil Centang Siluet Putih -->
                            <svg class="w-8 h-8 sm:w-12 sm:h-12 md:w-16 md:h-16 lg:w-20 lg:h-20 text-white drop-shadow-sm" viewBox="0 0 24 24" fill="currentColor">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H6a2 2 0 00-2 2v13a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1.382l-.724-1.447A1 1 0 0015 2H9zm6 4H9v1h6V6zm-4.293 9.707l5-5-1.414-1.414L10 13.586l-1.793-1.793-1.414 1.414 2.5 2.5a1 1 0 001.414 0z"/>
                            </svg>
                        </a>
                        <!-- Pill Label Bawah -->
                        <div class="mt-2 sm:mt-3 px-3 sm:px-5 md:px-7 py-1 sm:py-1.5 rounded-full bg-white shadow-[0_4px_14px_rgba(0,0,0,0.18)] border border-stone-100 flex items-center justify-center">
                            <span class="font-['Jua'] text-[11px] sm:text-sm md:text-base lg:text-xl font-bold text-[#2E2E2E] tracking-wide whitespace-nowrap">
                                Cek Hasil
                            </span>
                        </div>
                    </div>

                </div>

                <!-- SOUND SETTINGS (MODAL ONLY - NO DUPLICATE BUTTON) -->
                <x-sound-settings :renderTrigger="false" />

                <!-- EXIT CONFIRMATION MODAL -->
                <x-exit-modal />

                <!-- ============================================================
                     MODAL 5: IDENTITAS PROFIL SISWA
                     ============================================================ -->
                <div id="profileModal" 
                     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/65 backdrop-blur-sm opacity-0 pointer-events-none transition-all duration-300 font-['Jua'] select-none">
                    
                    <div class="absolute inset-0" onclick="closeProfileModal()"></div>

                    <div id="profileBox" 
                         class="relative z-10 w-full max-w-[430px] transform scale-90 transition-all duration-300 rounded-3xl border-4 border-[#3D1804] px-6 py-6 shadow-[0_20px_40px_rgba(0,0,0,0.5),0_8px_0_#280E02]"
                         style="background: linear-gradient(180deg, #9E531F 0%, #7E3E11 25%, #6E330C 65%, #522204 100%);">
                        
                        <div class="text-center mb-3">
                            <h2 class="text-2xl sm:text-3xl font-bold text-[#FFF2A6] wood-text-shadow">
                                Identitas Siswa
                            </h2>
                            <p class="text-xs text-[#FFE8A3] font-medium mt-0.5">
                                Data nama dan nomor absen belajarmu
                            </p>
                        </div>

                        <div class="rounded-2xl border-3 border-[#612A07] bg-gradient-to-b from-[#FFFDF0] to-[#FCEEC7] p-4 sm:p-5 shadow space-y-3.5">
                            <div>
                                <label class="block text-xs font-bold text-[#6D360B] mb-1" for="popup_profile_name">Nama Lengkap</label>
                                <input id="popup_profile_name" type="text" value="{{ auth()->user()->name ?? 'Budi Santoso' }}" readonly class="w-full h-10 rounded-xl bg-white border-2 border-[#A85D25] px-3 text-amber-950 text-sm font-semibold">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-[#6D360B] mb-1" for="popup_profile_absen">Nomor Absen</label>
                                <input id="popup_profile_absen" type="text" value="{{ auth()->user()->no_absen ?? '1' }}" readonly class="w-full h-10 rounded-xl bg-white border-2 border-[#A85D25] px-3 text-amber-950 text-sm font-semibold">
                            </div>

                            <button type="button" onclick="closeProfileModal()" class="w-full rounded-full bg-emerald-600 hover:bg-emerald-500 py-2.5 text-white font-bold text-sm shadow cursor-pointer">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <script>
            function openProfileModal() {
                var m = document.getElementById('profileModal');
                var b = document.getElementById('profileBox');
                if (m) { m.classList.remove('opacity-0', 'pointer-events-none'); m.classList.add('opacity-100'); }
                if (b) { b.classList.remove('scale-90'); b.classList.add('scale-100'); }
            }
            function closeProfileModal() {
                var m = document.getElementById('profileModal');
                var b = document.getElementById('profileBox');
                if (m) { m.classList.remove('opacity-100'); m.classList.add('opacity-0', 'pointer-events-none'); }
                if (b) { b.classList.remove('scale-100'); b.classList.add('scale-90'); }
            }

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeExitModal();
                    closeProfileModal();
                    if (typeof closeSettingsModal === 'function') closeSettingsModal();
                }
            });
        </script>
    </body>
</html>
