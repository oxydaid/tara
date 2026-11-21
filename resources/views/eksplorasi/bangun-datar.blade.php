<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

        <title>Ayo Temukan Bangunnya - TARA</title>
        <link rel="icon" type="image/webp" href="{{ asset('assets/logotara.webp') }}">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Jua&display=swap" rel="stylesheet">

        <!-- 3D Model Viewer Engine -->
        <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/4.0.0/model-viewer.min.js"></script>

        <script>
            try {
                if (sessionStorage.getItem('app.booted') === '1') {
                    document.documentElement.classList.add('app-booted');
                }
            } catch {}
        </script>

        <style>
            /* Styling scrollbar kartu panel utama */
            .bangun-datar-card {
                scrollbar-width: thin;
                scrollbar-color: #E9C37A #FFFDF5;
                -webkit-overflow-scrolling: touch;
                overscroll-behavior: contain;
            }
            .bangun-datar-card::-webkit-scrollbar {
                width: 6px;
            }
            .bangun-datar-card::-webkit-scrollbar-track {
                background: #FFFDF5;
                border-radius: 9999px;
            }
            .bangun-datar-card::-webkit-scrollbar-thumb {
                background: #E9C37A;
                border-radius: 9999px;
            }

            /* Responsif Layar Menengah / Laptop / Mobile Landscape: Menjamin panel tidak tertutup tombol navigasi bawah */
            @media screen and (max-height: 720px), screen and (max-width: 1024px) {
                .bangun-datar-wrapper {
                    top: 48px !important;
                    bottom: 64px !important;
                    left: 24% !important;
                    right: 12px !important;
                    width: auto !important;
                    max-width: none !important;
                    transform: none !important;
                }
                .bangun-datar-card {
                    padding: 12px 16px 20px 16px !important;
                    border-radius: 24px !important;
                }
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
                
                <!-- ============================================================
                     1. TOP BAR KIRI: TOMBOL HOME (BERANDA)
                     ============================================================ -->
                <div class="absolute top-3 left-3 sm:top-5 sm:left-5 z-40">
                    <x-btn-home href="{{ route('menu') }}" title="Kembali ke Beranda" />
                </div>

                <!-- ============================================================
                     2. TOP BAR KANAN: PENGATURAN SUARA & EXIT (SINGLE SET)
                     ============================================================ -->
                <div class="absolute top-3 right-3 sm:top-5 sm:right-5 z-40 flex items-center gap-2 sm:gap-3">
                    <x-btn-settings onclick="openSettingsModal()" title="Pengaturan Suara" />
                    <x-btn-exit onclick="handleExitWeb()" title="Keluar" />
                </div>

                <!-- ============================================================
                     3. KARAKTER YUK DAMAR DI SISI KIRI (100% OPASITAS PENUH)
                     ============================================================ -->
                <div id="karakterYukDamar" class="absolute z-20 pointer-events-none bottom-0 left-0 sm:left-2 md:left-4 lg:left-6 h-[88%] sm:h-[94%] md:h-[98%] max-h-[760px] flex items-end translate-y-[20%] select-none">
                    <img src="{{ asset('assets/karakter_menyapa.webp') }}" 
                         alt="Yuk Damar Menyapa" 
                         class="h-full w-auto max-h-full object-contain object-bottom drop-shadow-[0_18px_32px_rgba(0,0,0,0.4)] select-none pointer-events-none opacity-100">
                </div>

                <!-- ============================================================
                     MAIN PANEL: 1 HALAMAN (MOTIF TEMA ADAT + TABEL ANALISIS)
                     ============================================================ -->
                <div class="absolute inset-0 z-30 pointer-events-auto overflow-hidden">
                    
                    <!-- Bubble Chat & Analysis Panel Wrapper (Safe area: top clears Home/Settings, bottom clears Back/Next) -->
                    <div class="bangun-datar-wrapper absolute top-[52px] sm:top-[56px] md:top-[60px] bottom-16 sm:bottom-20 left-[24%] sm:left-[26%] md:left-1/2 md:-translate-x-1/2 right-3 sm:right-4 md:right-auto z-30 flex items-center justify-center pointer-events-auto w-auto md:w-full md:max-w-4xl lg:max-w-5xl xl:max-w-6xl px-1 sm:px-2">
                        <div class="bangun-datar-card relative w-full h-full max-h-full overflow-y-auto bg-[#FFFDF5] border-3 sm:border-4 border-[#E9C37A] rounded-[24px] sm:rounded-[34px] md:rounded-[42px] p-3.5 sm:p-5 md:p-6 shadow-[0_22px_50px_rgba(0,0,0,0.25),0_4px_12px_rgba(233,195,122,0.3)] flex flex-col">
                            
                            <!-- Ekor Balon Chat Mengarah ke Karakter Yuk Damar di Kiri -->
                            <div class="absolute -left-[20px] sm:-left-[24px] top-12 sm:top-14 -translate-y-1/2 w-0 h-0 border-t-[12px] sm:border-t-[14px] border-t-transparent border-r-[20px] sm:border-r-[24px] border-r-[#E9C37A] border-b-[12px] sm:border-b-[14px] border-b-transparent"></div>
                            <div class="absolute -left-[14px] sm:-left-[17px] top-12 sm:top-14 -translate-y-1/2 w-0 h-0 border-t-[12px] sm:border-t-[14px] border-t-transparent border-r-[20px] sm:border-r-[24px] border-r-[#FFFDF5] border-b-[12px] sm:border-b-[14px] border-b-transparent"></div>

                            <!-- Header Bubble / Penjelasan Yuk Damar -->
                            <div class="mb-3 sm:mb-4 shrink-0">
                                <div class="inline-flex items-center gap-1.5 sm:gap-2 bg-[#FFF2D6] px-3 sm:px-3.5 py-0.5 sm:py-1 rounded-full border border-[#E9C37A] mb-1.5 shadow-2xs">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-[#8A4416]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" />
                                    </svg>
                                    <span class="text-xs sm:text-sm font-bold text-[#8A4416]">Ayo Temukan Bangunnya!</span>
                                </div>
                                <p class="text-xs sm:text-sm md:text-base text-[#3D1804] font-normal leading-relaxed text-left select-none tracking-wide">
                                    “Saat kita mengamati motif dari lampion <strong class="text-[#E57354]">Damar Kurung (Tema Adat)</strong>, terdapat benda-benda dalam kehidupan sehari-hari yang membentuk bangun datar! Ayo amati gambar motif di sebelah kiri dan cermati tabel analisis ciri-cirinya!”
                                </p>
                            </div>

                            <!-- Grid 2 Kolom: Sisi Kiri Motif Tema Adat, Sisi Kanan Langsung Tabel Analisis -->
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 sm:gap-4 items-start">
                                
                                <!-- Sisi Kiri: Model 3D GLB Tema Adat Istiadat Interaktif -->
                                <div class="md:col-span-5 bg-gradient-to-b from-[#FFF8E7] to-[#FFF3D6] rounded-2xl md:rounded-3xl p-3 sm:p-3.5 border-2 border-[#E9C37A]/80 shadow-xs flex flex-col justify-between">
                                    <div class="flex items-center justify-between gap-2 mb-2 pb-1.5 border-b border-[#E9C37A]/50">
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-[#8A4416]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5" />
                                            </svg>
                                            <h3 class="font-bold text-xs sm:text-sm text-[#8A4416]">Model 3D Tema Adat</h3>
                                        </div>
                                        <span class="text-[10px] text-[#A2571D] bg-[#FFEBBF] px-2 py-0.5 rounded-full font-semibold animate-pulse">Bisa Diputar 3D</span>
                                    </div>

                                    <div class="relative w-full aspect-[4/3] rounded-xl overflow-hidden border-2 border-[#E9C37A] bg-white shadow-inner flex items-center justify-center">
                                        <model-viewer
                                            id="bangunDatarGlbViewer"
                                            src="{{ asset('assets/damar-kurung-adat.glb') }}"
                                            poster="{{ asset('assets/tema_adat.webp') }}"
                                            alt="Model 3D Motif Adat Damar Kurung"
                                            camera-controls
                                            touch-action="pan-y"
                                            auto-rotate
                                            rotation-per-second="20deg"
                                            shadow-intensity="1.3"
                                            exposure="1.05"
                                            camera-orbit="0deg 75deg 105%"
                                            loading="lazy"
                                            reveal="auto"
                                            style="width: 100%; height: 100%; min-height: 180px; display: block;"
                                            class="cursor-grab active:cursor-grabbing w-full h-full"
                                        >
                                            <div slot="poster" class="w-full h-full flex flex-col items-center justify-center bg-transparent">
                                                <img src="{{ asset('assets/tema_adat.webp') }}" class="max-h-[85%] max-w-[85%] object-contain drop-shadow-md animate-pulse" alt="Motif Adat">
                                                <span class="text-[10px] text-[#8A4416] font-bold mt-1 bg-[#FFF8E7]/90 px-2 py-0.5 rounded-full border border-[#E9C37A] shadow-sm">Memuat Model 3D...</span>
                                            </div>
                                        </model-viewer>
                                    </div>

                                    <div class="bg-white/80 rounded-xl p-2 border border-[#E9C37A]/50 mt-2 text-center">
                                        <p class="text-[10px] sm:text-xs text-[#6D4C33] leading-snug flex items-center justify-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-[#8A4416] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                            <span><strong>Objek Teramati:</strong> Atap Menara, Ketupat, Atap Penjual, Buah-buahan, dll.</span>
                                        </p>
                                    </div>
                                </div>

                                <!-- Sisi Kanan: Tabel Analisis Langsung -->
                                <div class="md:col-span-7 flex flex-col justify-start">
                                    <div class="overflow-x-auto rounded-2xl border-2 border-[#E9C37A] shadow-md bg-white">
                                        <table class="w-full text-left border-collapse min-w-[380px]">
                                            <thead class="sticky top-0 z-10">
                                                <tr class="bg-gradient-to-r from-[#2B5F8C] via-[#3B7BB3] to-[#2B5F8C] text-white text-xs sm:text-sm uppercase tracking-wider shadow-sm">
                                                    <th class="py-2.5 px-2.5 sm:px-3 text-center w-[30%] font-bold border-r border-[#6393BD]">BENDA</th>
                                                    <th class="py-2.5 px-2.5 sm:px-3 text-center w-[30%] font-bold border-r border-[#6393BD]">BANGUN DATAR</th>
                                                    <th class="py-2.5 px-2.5 sm:px-3 text-left w-[40%] font-bold">CIRI-CIRI</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-[#E9C37A]/50 text-xs sm:text-sm text-[#3D1804]">
                                                
                                                <!-- Row 1: Segitiga (Atap Menara) -->
                                                <tr class="hover:bg-[#FFF8E7] transition-colors bg-[#FFFDF7]">
                                                    <td class="py-2 px-2.5 sm:px-3 text-center border-r border-[#E9C37A]/40">
                                                        <div class="flex flex-col items-center justify-center gap-0.5">
                                                            <div class="w-9 h-8 rounded-lg bg-[#FFE7D6] border border-[#E57354]/40 flex items-center justify-center p-0.5 overflow-hidden">
                                                                <svg viewBox="0 0 40 32" class="w-full h-full">
                                                                    <polygon points="20,2 2,30 38,30" fill="#E57354" stroke="#A83216" stroke-width="2"/>
                                                                </svg>
                                                            </div>
                                                            <span class="font-bold text-[11px] sm:text-xs text-[#8A4416]">Atap Menara</span>
                                                        </div>
                                                    </td>
                                                    <td class="py-2 px-2.5 sm:px-3 text-center font-bold text-[#E57354] border-r border-[#E9C37A]/40">
                                                        <span class="inline-block bg-[#FFEBE6] px-2.5 py-0.5 rounded-full border border-[#E57354]/40 text-xs">
                                                            Segitiga
                                                        </span>
                                                    </td>
                                                    <td class="py-2 px-2.5 sm:px-3 text-[#5C2B09] leading-tight">
                                                        <ul class="list-disc list-inside space-y-0.5 text-[10px] sm:text-[11px]">
                                                            <li>Memiliki <strong>3 sisi</strong></li>
                                                            <li>Memiliki <strong>3 titik sudut</strong></li>
                                                        </ul>
                                                    </td>
                                                </tr>

                                                <!-- Row 2: Belah Ketupat (Ketupat) -->
                                                <tr class="hover:bg-[#FFF8E7] transition-colors bg-[#FFFFFF]">
                                                    <td class="py-2 px-2.5 sm:px-3 text-center border-r border-[#E9C37A]/40">
                                                        <div class="flex flex-col items-center justify-center gap-0.5">
                                                            <div class="w-9 h-8 rounded-lg bg-[#EDE9FE] border border-[#8B5CF6]/40 flex items-center justify-center p-0.5 overflow-hidden">
                                                                <svg viewBox="0 0 32 32" class="w-5 h-5">
                                                                    <polygon points="16,2 30,16 16,30 2,16" fill="#8B5CF6" stroke="#6D28D9" stroke-width="2"/>
                                                                </svg>
                                                            </div>
                                                            <span class="font-bold text-[11px] sm:text-xs text-[#8A4416]">Ketupat</span>
                                                        </div>
                                                    </td>
                                                    <td class="py-2 px-2.5 sm:px-3 text-center font-bold text-[#7C3AED] border-r border-[#E9C37A]/40">
                                                        <span class="inline-block bg-[#EDE9FE] px-2.5 py-0.5 rounded-full border border-[#8B5CF6]/40 text-xs">
                                                            Belah Ketupat
                                                        </span>
                                                    </td>
                                                    <td class="py-2 px-2.5 sm:px-3 text-[#5C2B09] leading-tight">
                                                        <ul class="list-disc list-inside space-y-0.5 text-[10px] sm:text-[11px]">
                                                            <li>Memiliki <strong>4 sisi sama panjang</strong></li>
                                                            <li>Diagonal berpotongan <strong>tegak lurus</strong></li>
                                                        </ul>
                                                    </td>
                                                </tr>

                                                <!-- Row 3: Jajar Genjang (Atap dari Penjual) -->
                                                <tr class="hover:bg-[#FFF8E7] transition-colors bg-[#FFFDF7]">
                                                    <td class="py-2 px-2.5 sm:px-3 text-center border-r border-[#E9C37A]/40">
                                                        <div class="flex flex-col items-center justify-center gap-0.5">
                                                            <div class="w-9 h-8 rounded-lg bg-[#FEF3C7] border border-[#D97706]/40 flex items-center justify-center p-0.5 overflow-hidden">
                                                                <svg viewBox="0 0 36 24" class="w-6 h-4">
                                                                    <polygon points="10,3 34,3 26,21 2,21" fill="#D97706" stroke="#92400E" stroke-width="2"/>
                                                                </svg>
                                                            </div>
                                                            <span class="font-bold text-[11px] sm:text-xs text-[#8A4416]">Atap Penjual</span>
                                                        </div>
                                                    </td>
                                                    <td class="py-2 px-2.5 sm:px-3 text-center font-bold text-[#B45309] border-r border-[#E9C37A]/40">
                                                        <span class="inline-block bg-[#FEF3C7] px-2.5 py-0.5 rounded-full border border-[#D97706]/40 text-xs">
                                                            Jajar Genjang
                                                        </span>
                                                    </td>
                                                    <td class="py-2 px-2.5 sm:px-3 text-[#5C2B09] leading-tight">
                                                        <ul class="list-disc list-inside space-y-0.5 text-[10px] sm:text-[11px]">
                                                            <li>Memiliki <strong>2 pasang sisi sejajar & sama panjang</strong></li>
                                                            <li>Sudut berhadapan <strong>sama besar</strong></li>
                                                        </ul>
                                                    </td>
                                                </tr>

                                                <!-- Row 4: Lingkaran (Buah) -->
                                                <tr class="hover:bg-[#FFF8E7] transition-colors bg-[#FFFFFF]">
                                                    <td class="py-2 px-2.5 sm:px-3 text-center border-r border-[#E9C37A]/40">
                                                        <div class="flex flex-col items-center justify-center gap-0.5">
                                                            <div class="w-9 h-8 rounded-lg bg-[#E0F2FE] border border-[#0284C7]/40 flex items-center justify-center p-0.5 overflow-hidden">
                                                                <svg viewBox="0 0 30 30" class="w-5 h-5">
                                                                    <circle cx="15" cy="15" r="12" fill="#0284C7" stroke="#0369A1" stroke-width="2"/>
                                                                </svg>
                                                            </div>
                                                            <span class="font-bold text-[11px] sm:text-xs text-[#8A4416]">Buah</span>
                                                        </div>
                                                    </td>
                                                    <td class="py-2 px-2.5 sm:px-3 text-center font-bold text-[#0284C7] border-r border-[#E9C37A]/40">
                                                        <span class="inline-block bg-[#E0F2FE] px-2.5 py-0.5 rounded-full border border-[#0284C7]/40 text-xs">
                                                            Lingkaran
                                                        </span>
                                                    </td>
                                                    <td class="py-2 px-2.5 sm:px-3 text-[#5C2B09] leading-tight">
                                                        <ul class="list-disc list-inside space-y-0.5 text-[10px] sm:text-[11px]">
                                                            <li>Memiliki <strong>1 sisi lengkung</strong></li>
                                                            <li>Tidak memiliki titik sudut (<strong>0 sudut</strong>)</li>
                                                        </ul>
                                                    </td>
                                                </tr>

                                                <!-- Row 5: Persegi (Pintu / Kotak) -->
                                                <tr class="hover:bg-[#FFF8E7] transition-colors bg-[#FFFDF7]">
                                                    <td class="py-2 px-2.5 sm:px-3 text-center border-r border-[#E9C37A]/40">
                                                        <div class="flex flex-col items-center justify-center gap-0.5">
                                                            <div class="w-9 h-8 rounded-lg bg-[#FEF3C7] border border-[#F59E0B]/40 flex items-center justify-center p-0.5 overflow-hidden">
                                                                <svg viewBox="0 0 32 32" class="w-5 h-5">
                                                                    <rect x="2" y="2" width="28" height="28" rx="2" fill="#F59E0B" stroke="#B45309" stroke-width="2"/>
                                                                </svg>
                                                            </div>
                                                            <span class="font-bold text-[11px] sm:text-xs text-[#8A4416]">Kotak Dagangan</span>
                                                        </div>
                                                    </td>
                                                    <td class="py-2 px-2.5 sm:px-3 text-center font-bold text-[#D97706] border-r border-[#E9C37A]/40">
                                                        <span class="inline-block bg-[#FEF3C7] px-2.5 py-0.5 rounded-full border border-[#F59E0B]/40 text-xs">
                                                            Persegi
                                                        </span>
                                                    </td>
                                                    <td class="py-2 px-2.5 sm:px-3 text-[#5C2B09] leading-tight">
                                                        <ul class="list-disc list-inside space-y-0.5 text-[10px] sm:text-[11px]">
                                                            <li>Memiliki <strong>4 sisi sama panjang</strong></li>
                                                            <li>Memiliki <strong>4 sudut siku-siku (90°)</strong></li>
                                                        </ul>
                                                    </td>
                                                </tr>

                                                <!-- Row 6: Persegi Panjang (Meja Stand) -->
                                                <tr class="hover:bg-[#FFF8E7] transition-colors bg-[#FFFFFF]">
                                                    <td class="py-2 px-2.5 sm:px-3 text-center border-r border-[#E9C37A]/40">
                                                        <div class="flex flex-col items-center justify-center gap-0.5">
                                                            <div class="w-9 h-8 rounded-lg bg-[#D1FAE5] border border-[#10B981]/40 flex items-center justify-center p-0.5 overflow-hidden">
                                                                <svg viewBox="0 0 38 24" class="w-6 h-4">
                                                                    <rect x="2" y="2" width="34" height="20" rx="2" fill="#10B981" stroke="#047857" stroke-width="2"/>
                                                                </svg>
                                                            </div>
                                                            <span class="font-bold text-[11px] sm:text-xs text-[#8A4416]">Meja Jualan</span>
                                                        </div>
                                                    </td>
                                                    <td class="py-2 px-2.5 sm:px-3 text-center font-bold text-[#059669] border-r border-[#E9C37A]/40">
                                                        <span class="inline-block bg-[#D1FAE5] px-2.5 py-0.5 rounded-full border border-[#10B981]/40 text-xs">
                                                            Persegi Panjang
                                                        </span>
                                                    </td>
                                                    <td class="py-2 px-2.5 sm:px-3 text-[#5C2B09] leading-tight">
                                                        <ul class="list-disc list-inside space-y-0.5 text-[10px] sm:text-[11px]">
                                                            <li>Memiliki <strong>2 pasang sisi sejajar & sama panjang</strong></li>
                                                            <li>Memiliki <strong>4 sudut siku-siku (90°)</strong></li>
                                                        </ul>
                                                    </td>
                                                </tr>

                                                <!-- Row 7: Trapesium (Perahu / Kios) -->
                                                <tr class="hover:bg-[#FFF8E7] transition-colors bg-[#FFFDF7]">
                                                    <td class="py-2 px-2.5 sm:px-3 text-center border-r border-[#E9C37A]/40">
                                                        <div class="flex flex-col items-center justify-center gap-0.5">
                                                            <div class="w-9 h-8 rounded-lg bg-[#FCE7F3] border border-[#EC4899]/40 flex items-center justify-center p-0.5 overflow-hidden">
                                                                <svg viewBox="0 0 36 24" class="w-6 h-4">
                                                                    <polygon points="8,3 28,3 34,21 2,21" fill="#EC4899" stroke="#BE185D" stroke-width="2"/>
                                                                </svg>
                                                            </div>
                                                            <span class="font-bold text-[11px] sm:text-xs text-[#8A4416]">Perahu / Bak</span>
                                                        </div>
                                                    </td>
                                                    <td class="py-2 px-2.5 sm:px-3 text-center font-bold text-[#DB2777] border-r border-[#E9C37A]/40">
                                                        <span class="inline-block bg-[#FCE7F3] px-2.5 py-0.5 rounded-full border border-[#EC4899]/40 text-xs">
                                                            Trapesium
                                                        </span>
                                                    </td>
                                                    <td class="py-2 px-2.5 sm:px-3 text-[#5C2B09] leading-tight">
                                                        <ul class="list-disc list-inside space-y-0.5 text-[10px] sm:text-[11px]">
                                                            <li>Memiliki <strong>sepasang sisi sejajar</strong></li>
                                                            <li>Memiliki <strong>4 titik sudut</strong></li>
                                                        </ul>
                                                    </td>
                                                </tr>

                                                <!-- Row 8: Layang-layang (Hiasan Tradisi) -->
                                                <tr class="hover:bg-[#FFF8E7] transition-colors bg-[#FFFFFF]">
                                                    <td class="py-2 px-2.5 sm:px-3 text-center border-r border-[#E9C37A]/40">
                                                        <div class="flex flex-col items-center justify-center gap-0.5">
                                                            <div class="w-9 h-8 rounded-lg bg-[#F1F5F9] border border-[#64748B]/40 flex items-center justify-center p-0.5 overflow-hidden">
                                                                <svg viewBox="0 0 32 32" class="w-5 h-5">
                                                                    <polygon points="16,2 30,12 16,30 2,12" fill="#64748B" stroke="#334155" stroke-width="2"/>
                                                                </svg>
                                                            </div>
                                                            <span class="font-bold text-[11px] sm:text-xs text-[#8A4416]">Hiasan Gantung</span>
                                                        </div>
                                                    </td>
                                                    <td class="py-2 px-2.5 sm:px-3 text-center font-bold text-[#475569] border-r border-[#E9C37A]/40">
                                                        <span class="inline-block bg-[#F1F5F9] px-2.5 py-0.5 rounded-full border border-[#64748B]/40 text-xs">
                                                            Layang-Layang
                                                        </span>
                                                    </td>
                                                    <td class="py-2 px-2.5 sm:px-3 text-[#5C2B09] leading-tight">
                                                        <ul class="list-disc list-inside space-y-0.5 text-[10px] sm:text-[11px]">
                                                            <li>Memiliki <strong>2 pasang sisi berdekatan sama panjang</strong></li>
                                                            <li>Memiliki <strong>1 pasang sudut berhadapan sama besar</strong></li>
                                                        </ul>
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>

                            <!-- Spacer bawah agar saat di-scroll mentok, batas kartu bawah dan tabel tampak 100% sempurna -->
                            <div class="h-4 sm:h-6 shrink-0"></div>

                        </div>
                    </div>

                    <!-- TOMBOL NAVIGASI BAWAH: BACK DI KIRI & NEXT DI KANAN -->
                    <div class="absolute bottom-4 left-4 sm:bottom-6 sm:left-6 z-40">
                        <x-btn-back href="{{ route('eksplorasi') }}" title="Kembali ke Ruang Eksplorasi" />
                    </div>
                    <div class="absolute bottom-4 right-4 sm:bottom-6 sm:right-6 z-40">
                        <x-btn-next href="{{ route('eksplorasi') }}" title="Selesai Eksplorasi Bangun Datar" />
                    </div>
                </div>

                <form id="globalLogoutForm" method="POST" action="{{ route('logout') }}" class="hidden">
                    @csrf
                </form>

                <!-- SOUND SETTINGS (MODAL ONLY) -->
                <x-sound-settings :renderTrigger="false" />

                <!-- EXIT CONFIRMATION MODAL -->
                <div id="exitModal" 
                     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/65 backdrop-blur-sm opacity-0 pointer-events-none transition-all duration-300 font-['Jua'] select-none">
                    
                    <div class="absolute inset-0" onclick="closeExitModal()"></div>

                    <div id="exitBox" 
                         class="relative z-10 w-full max-w-[420px] transform scale-90 transition-all duration-300 rounded-3xl border-4 border-[#3D1804] px-6 py-6 shadow-[0_20px_40px_rgba(0,0,0,0.5),0_8px_0_#280E02]"
                         style="background: linear-gradient(180deg, #9E531F 0%, #7E3E11 25%, #6E330C 65%, #522204 100%);">
                        
                        <div class="text-center mb-3.5 pt-1">
                            <h2 class="text-2xl sm:text-3xl font-bold text-[#FFF2A6]" style="text-shadow: 2px 2px 0 #3D1804, -2px -2px 0 #3D1804, 2px -2px 0 #3D1804, -2px 2px 0 #3D1804;">
                                Konfirmasi Keluar
                            </h2>
                            <p class="text-xs text-[#FFE8A3] font-medium mt-1">
                                Apakah kamu yakin ingin keluar dari petualangan TARA?
                            </p>
                        </div>

                        <div class="rounded-2xl border-3 border-[#612A07] bg-gradient-to-b from-[#FFFDF0] to-[#FCEEC7] p-4 sm:p-5 shadow text-center space-y-4">
                            <p class="text-xs sm:text-sm text-[#785135] font-semibold leading-relaxed">
                                Catatan belajarmu telah tersimpan. Kamu dapat kembali melanjutkan belajar kapan saja!
                            </p>

                            <div class="flex flex-col sm:flex-row items-center gap-2.5 pt-1">
                                <button type="button" 
                                        onclick="closeExitModal()" 
                                        class="w-full flex-1 rounded-full border-3 border-white/90 bg-gradient-to-b from-[#4ADE80] via-[#22C55E] to-[#15803D] py-2.5 text-white font-bold text-sm sm:text-base tracking-wide shadow-[0_5px_0_#0F5128] hover:brightness-110 active:translate-y-[2px] cursor-pointer">
                                    Lanjut Belajar
                                </button>
                                <button type="button" 
                                        onclick="confirmExitAction()" 
                                        class="w-full sm:w-auto px-6 rounded-full border-3 border-white/90 bg-gradient-to-b from-[#EF4444] via-[#DC2626] to-[#B91C1C] py-2.5 text-white font-bold text-sm sm:text-base tracking-wide shadow-[0_5px_0_#7F1D1D] hover:brightness-110 active:translate-y-[2px] cursor-pointer">
                                    Ya, Keluar
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        <script>
            function getGlbModelUrl(url) {
                if (window.__TARA_GLB_CACHE) {
                    if (window.__TARA_GLB_CACHE.has(url)) return window.__TARA_GLB_CACHE.get(url);
                    try {
                        const parsed = new URL(url, window.location.origin);
                        if (window.__TARA_GLB_CACHE.has(parsed.pathname)) return window.__TARA_GLB_CACHE.get(parsed.pathname);
                    } catch {}
                }
                return url;
            }

            function ensureModelViewerScript() {
                if (!customElements.get('model-viewer') && !document.querySelector('script[src*="model-viewer"]')) {
                    const s = document.createElement('script');
                    s.type = 'module';
                    s.src = 'https://ajax.googleapis.com/ajax/libs/model-viewer/4.0.0/model-viewer.min.js';
                    document.head.appendChild(s);
                }
            }

            function initBangunDatarGlb() {
                ensureModelViewerScript();
                const viewer = document.getElementById('bangunDatarGlbViewer');
                if (viewer) {
                    const resolved = getGlbModelUrl("{{ asset('assets/damar-kurung-adat.glb') }}");
                    viewer.src = resolved;
                    viewer.setAttribute('src', resolved);
                    if (typeof viewer.dismissPoster === 'function') {
                        viewer.dismissPoster();
                    }
                }
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initBangunDatarGlb);
            } else {
                initBangunDatarGlb();
            }

            function handleExitWeb() {
                var m = document.getElementById('exitModal');
                var b = document.getElementById('exitBox');
                if (m) { m.classList.remove('opacity-0', 'pointer-events-none'); m.classList.add('opacity-100'); }
                if (b) { b.classList.remove('scale-90'); b.classList.add('scale-100'); }
            }
            function closeExitModal() {
                var m = document.getElementById('exitModal');
                var b = document.getElementById('exitBox');
                if (m) { m.classList.remove('opacity-100'); m.classList.add('opacity-0', 'pointer-events-none'); }
                if (b) { b.classList.remove('scale-100'); b.classList.add('scale-90'); }
            }
            function confirmExitAction() {
                var f = document.getElementById('globalLogoutForm');
                if (f) f.submit();
            }

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeExitModal();
                    if (typeof closeSettingsModal === 'function') closeSettingsModal();
                }
            });
        </script>
    </body>
</html>
