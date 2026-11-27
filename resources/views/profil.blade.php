<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

        <title>Profil Media - TARA</title>
        <link rel="icon" type="image/webp" href="{{ asset('assets/logotara.webp') }}">

        <!-- Preload Essential Assets -->
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
            }
        </style>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-[100dvh] h-[100dvh] overflow-hidden font-['Jua'] select-none" style="background-color: #3D1804;">
        @php
            $bg = asset('assets/berandamalam.webp');
        @endphp

        <div class="landscape-force" style="background-color: #3D1804; background-image: url('{{ $bg }}'); background-size: cover; background-position: center;">
            <div data-app-root data-content style="background-color: #3D1804; background-image: url('{{ $bg }}'); background-size: cover; background-position: center;">
                
                <!-- TOP BAR KIRI: TOMBOL KEMBALI KE BERANDA -->
                <x-btn-back href="{{ route('menu') }}" class="absolute top-3 left-3 sm:top-5 sm:left-5 z-40" title="Kembali ke Beranda" />

                <!-- TOP BAR KANAN: SOUND SETTINGS & EXIT -->
                <div class="absolute top-3 right-3 sm:top-4 sm:right-5 md:top-5 md:right-6 z-40 flex items-center gap-2 sm:gap-3">
                    <x-btn-settings onclick="openSettingsModal()" title="Pengaturan Suara" />
                    <x-btn-exit onclick="handleExitWeb()" title="Keluar" />
                </div>

                <!-- KARAKTER YUK DAMAR DI KIRI BAWAH (100% OPASITAS, BERDIRI JELAS DI SISI KIRI) -->
                <div class="absolute z-20 pointer-events-none bottom-0 left-0 sm:left-1 md:left-2 lg:left-3 h-[88%] sm:h-[94%] md:h-[97%] max-h-[740px] flex items-end translate-y-[2%] opacity-100">
                    <img src="{{ asset('assets/karakter_default.webp') }}" 
                         alt="Yuk Damar" 
                         class="h-full w-auto max-h-full object-contain object-bottom drop-shadow-[0_18px_32px_rgba(0,0,0,0.35)] select-none pointer-events-none opacity-100">
                </div>

                <!-- GOLDEN CREAM PANEL PROFIL -->
                <div class="absolute top-[60px] sm:top-[76px] lg:top-[96px] bottom-2 sm:bottom-3 lg:bottom-8 left-[25%] sm:left-[25%] md:left-[24%] lg:left-[21%] xl:left-[20%] right-2 sm:right-4 lg:right-10 xl:right-14 z-30 flex items-center">
                    <div class="relative w-full h-full lg:h-auto lg:max-h-full flex flex-row rounded-[24px] sm:rounded-[32px] md:rounded-[36px] bg-[#FFFDF5] border-3 sm:border-4 border-[#E9C37A] shadow-[0_20px_50px_rgba(0,0,0,0.22),0_4px_16px_rgba(217,119,6,0.15)] overflow-hidden p-3.5 sm:p-4 md:p-5 lg:p-6 gap-3 sm:gap-4 md:gap-5">
                        
                        <!-- Ekor Balon Chat Mengarah ke Karakter Yuk Damar di Kiri -->
                        <div class="absolute -left-[20px] sm:-left-[24px] top-16 sm:top-20 -translate-y-1/2 w-0 h-0 border-t-[12px] sm:border-t-[14px] border-t-transparent border-r-[20px] sm:border-r-[24px] border-r-[#E9C37A] border-b-[12px] sm:border-b-[14px] border-b-transparent"></div>
                        <div class="absolute -left-[12px] sm:-left-[16px] top-16 sm:top-20 -translate-y-1/2 w-0 h-0 border-t-[12px] sm:border-t-[14px] border-t-transparent border-r-[20px] sm:border-r-[24px] border-r-[#FFFDF5] border-b-[12px] sm:border-b-[14px] border-b-transparent"></div>

                        <!-- KOLOM KIRI: DAFTAR 4 TAB MENU (MENTOK ATAS) -->
                        <div class="w-[170px] sm:w-[190px] md:w-[220px] lg:w-[240px] flex-shrink-0 flex flex-col gap-2 sm:gap-2.5 justify-start pt-0.5 h-full lg:h-auto">
                            
                            <!-- Tab 1: Deskripsi Media -->
                            <button type="button" 
                                    onclick="switchProfTab('deskripsi')" 
                                    id="mTabBtn-deskripsi" 
                                    class="pTabBtn active w-full flex items-center gap-2.5 sm:gap-3 p-2.5 sm:p-3 rounded-2xl bg-[#FDECE4] border-2 border-[#E57354]/60 transition-all text-left cursor-pointer group">
                                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-[#E57354] text-white flex items-center justify-center flex-shrink-0 shadow-sm">
                                    <svg class="w-4.5 h-4.5 sm:w-5 sm:h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
                                    </svg>
                                </div>
                                <span class="font-['Jua'] text-xs sm:text-sm md:text-base font-bold text-[#3D1804] group-hover:text-[#E57354] transition-colors">
                                    Deskripsi Media
                                </span>
                            </button>

                            <!-- Tab 2: Capaian dan Tujuan Pembelajaran -->
                            <button type="button" 
                                    onclick="switchProfTab('cptp')" 
                                    id="mTabBtn-cptp" 
                                    class="pTabBtn w-full flex items-center gap-2.5 sm:gap-3 p-2.5 sm:p-3 rounded-2xl bg-[#FFF9EA] hover:bg-[#FFF3DA] border border-[#E9C37A]/60 transition-all text-left cursor-pointer group">
                                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-amber-200 text-amber-900 flex items-center justify-center flex-shrink-0 shadow-sm">
                                    <svg class="w-4.5 h-4.5 sm:w-5 sm:h-5 text-amber-900" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                                    </svg>
                                </div>
                                <span class="font-['Jua'] text-xs sm:text-sm md:text-base font-bold text-[#6D4C33] group-hover:text-[#3D1804] transition-colors leading-tight">
                                    Capaian & Tujuan
                                </span>
                            </button>

                            <!-- Tab 3: Petunjuk Penggunaan Media -->
                            <button type="button" 
                                    onclick="switchProfTab('petunjuk')" 
                                    id="mTabBtn-petunjuk" 
                                    class="pTabBtn w-full flex items-center gap-2.5 sm:gap-3 p-2.5 sm:p-3 rounded-2xl bg-[#FFF9EA] hover:bg-[#FFF3DA] border border-[#E9C37A]/60 transition-all text-left cursor-pointer group">
                                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-amber-200 text-amber-900 flex items-center justify-center flex-shrink-0 shadow-sm">
                                    <svg class="w-4.5 h-4.5 sm:w-5 sm:h-5 text-amber-900" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M18 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 4h5v8l-2.5-1.5L6 12V4z"/>
                                    </svg>
                                </div>
                                <span class="font-['Jua'] text-xs sm:text-sm md:text-base font-bold text-[#6D4C33] group-hover:text-[#3D1804] transition-colors leading-tight">
                                    Petunjuk Penggunaan
                                </span>
                            </button>

                            <!-- Tab 4: Profil Pengembang -->
                            <button type="button" 
                                    onclick="switchProfTab('pengembang')" 
                                    id="mTabBtn-pengembang" 
                                    class="pTabBtn w-full flex items-center gap-2.5 sm:gap-3 p-2.5 sm:p-3 rounded-2xl bg-[#FFF9EA] hover:bg-[#FFF3DA] border border-[#E9C37A]/60 transition-all text-left cursor-pointer group">
                                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-amber-200 text-amber-900 flex items-center justify-center flex-shrink-0 shadow-sm">
                                    <svg class="w-4.5 h-4.5 sm:w-5 sm:h-5 text-amber-900" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                    </svg>
                                </div>
                                <span class="font-['Jua'] text-xs sm:text-sm md:text-base font-bold text-[#6D4C33] group-hover:text-[#3D1804] transition-colors leading-tight">
                                    Profil Pengembang
                                </span>
                            </button>

                        </div>

                        <!-- KOLOM KANAN: KONTEN TAB AKTIF (MENTOK ATAS DENGAN SCROLL RAPI) -->
                        <div class="flex-1 h-full lg:h-auto lg:max-h-[calc(100vh-176px)] flex flex-col justify-start overflow-y-auto pr-1 sm:pr-2 pt-0.5">
                            
                            <!-- 1. KONTEN: DESKRIPSI MEDIA -->
                            <div id="mTabContent-deskripsi" class="pTabContent block space-y-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-[#E57354] text-white flex items-center justify-center shadow-md flex-shrink-0">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
                                        </svg>
                                    </div>
                                    <h2 class="font-['Jua'] text-lg sm:text-xl md:text-2xl font-bold text-[#2D221C]">
                                        Deskripsi Media
                                    </h2>
                                </div>

                                <div class="text-xs sm:text-sm md:text-base text-[#4A3B32] leading-relaxed space-y-2.5">
                                    <p>
                                        Hai, Salam kenal teman-teman! <strong>TARA (Lentera Eksplorasi Bangun Datar)</strong> merupakan media pembelajaran untuk kelas 4 Sekolah Dasar yang akan membantu kamu memahami jenis dan sifat bangun datar. Melalui kegiatan eksplorasi warisan budaya <strong>Damar Kurung khas Gresik</strong>, kamu diajak menerapkan pemahaman tentang karakteristik bangun datar untuk mengelompokkan jenis-jenis bangun datar yang ada pada motif Damar Kurung dengan baik dan benar!
                                    </p>
                                    <p class="font-bold text-[#8A4416]">
                                        Selamat bereksplorasi teman-teman!
                                    </p>
                                </div>

                                <!-- Ilustrasi Lentera Damar Kurung Berpendar -->
                                <div class="flex justify-center items-center pt-1">
                                    <div class="relative flex flex-col items-center">
                                        <svg viewBox="0 0 160 180" class="w-20 sm:w-24 md:w-28 h-auto drop-shadow-[0_8px_16px_rgba(245,158,11,0.35)]">
                                            <polygon points="80,15 20,50 140,50" fill="#9E531F" stroke="#522204" stroke-width="3"/>
                                            <polygon points="80,22 35,50 125,50" fill="#B8652A"/>
                                            <circle cx="80" cy="12" r="6" fill="#F59E0B" stroke="#78350F" stroke-width="2"/>
                                            <rect x="26" y="50" width="108" height="90" fill="#FFFBEB" stroke="#522204" stroke-width="3" rx="4"/>
                                            <circle cx="80" cy="95" r="28" fill="#FDE68A" opacity="0.9"/>
                                            <circle cx="80" cy="95" r="14" fill="#FEF08A"/>
                                            <circle cx="65" cy="90" r="6" fill="#B45309"/>
                                            <path d="M65 96 L58 114 L72 114 Z" fill="#92400E"/>
                                            <circle cx="95" cy="90" r="6" fill="#B45309"/>
                                            <path d="M95 96 L88 114 L102 114 Z" fill="#92400E"/>
                                            <line x1="26" y1="50" x2="26" y2="140" stroke="#522204" stroke-width="5"/>
                                            <line x1="134" y1="50" x2="134" y2="140" stroke="#522204" stroke-width="5"/>
                                            <line x1="80" y1="50" x2="80" y2="140" stroke="#78350F" stroke-width="2" stroke-dasharray="3,3"/>
                                            <polygon points="26,140 134,140 126,155 34,155" fill="#9E531F" stroke="#522204" stroke-width="3"/>
                                            <line x1="40" y1="155" x2="35" y2="172" stroke="#522204" stroke-width="4" stroke-linecap="round"/>
                                            <line x1="120" y1="155" x2="125" y2="172" stroke="#522204" stroke-width="4" stroke-linecap="round"/>
                                            <polygon points="80,155 86,166 80,177 74,166" fill="#EF4444"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. KONTEN: CAPAIAN & TUJUAN PEMBELAJARAN (SVG ICONS BERSIH) -->
                            <div id="mTabContent-cptp" class="pTabContent hidden space-y-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-[#D97706] text-white flex items-center justify-center shadow-md flex-shrink-0">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                                        </svg>
                                    </div>
                                    <h2 class="font-['Jua'] text-lg sm:text-xl md:text-2xl font-bold text-[#2D221C]">
                                        Capaian & Tujuan Pembelajaran
                                    </h2>
                                </div>

                                <div class="text-xs sm:text-sm md:text-base text-[#4A3B32] space-y-2.5 leading-relaxed">
                                    <div class="bg-[#FFF9EA] p-3 sm:p-3.5 rounded-2xl border border-[#E9C37A]/60">
                                        <div class="flex items-center gap-2 mb-1">
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[#8A4416] shrink-0" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm0-14c-3.31 0-6 2.69-6 6s2.69 6 6 6 6-2.69 6-6-2.69-6-6-6zm0 10c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm0-6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                                            </svg>
                                            <h3 class="font-bold text-[#8A4416] text-xs sm:text-sm md:text-base">Capaian Pembelajaran (CP)</h3>
                                        </div>
                                        <p>Pada materi ini murid dapat mendeskripsikan ciri-ciri berbagai bentuk bangun datar segi banyak dan bukan segi banyak.</p>
                                    </div>

                                    <div class="bg-[#FFF9EA] p-3 sm:p-3.5 rounded-2xl border border-[#E9C37A]/60">
                                        <div class="flex items-center gap-2 mb-1">
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[#8A4416] shrink-0" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                            </svg>
                                            <h3 class="font-bold text-[#8A4416] text-xs sm:text-sm md:text-base">Tujuan Pembelajaran (TP)</h3>
                                        </div>
                                        <ol class="list-decimal pl-5 space-y-1.5 text-xs sm:text-sm md:text-base">
                                            <li>Murid dapat menerapkan (C3) ciri-ciri bentuk bangun datar (segi banyak dan bukan segi banyak) berdasarkan karakteristiknya pada motif damar kurung dengan baik.</li>
                                            <li>Murid dapat menguraikan (C4) jenis dan ciri-ciri bangun datar penyusun motif damar kurung dengan tepat.</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>

                            <!-- 3. KONTEN: PETUNJUK PENGGUNAAN MEDIA -->
                            <div id="mTabContent-petunjuk" class="pTabContent hidden space-y-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-[#548466] text-white flex items-center justify-center shadow-md flex-shrink-0">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M18 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 4h5v8l-2.5-1.5L6 12V4z"/>
                                        </svg>
                                    </div>
                                    <h2 class="font-['Jua'] text-lg sm:text-xl md:text-2xl font-bold text-[#2D221C]">
                                        Petunjuk Penggunaan Media
                                    </h2>
                                </div>

                                <div class="text-xs sm:text-sm md:text-base text-[#4A3B32] space-y-2 leading-relaxed">
                                    <div class="bg-[#FFF9EA] p-2.5 sm:p-3 rounded-2xl border border-[#E9C37A]/60 flex gap-3 items-start">
                                        <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-[#548466] text-white font-bold flex items-center justify-center flex-shrink-0 text-xs shadow-sm">1</div>
                                        <div>
                                            <strong class="text-[#2D221C] text-xs sm:text-sm md:text-base">Ruang Eksplorasi:</strong> Pelajari sejarah Damar Kurung pada Sub Menu A, lalu buka Sub Menu B dan cermati analisis bangun datar.
                                        </div>
                                    </div>
                                    <div class="bg-[#FFF9EA] p-2.5 sm:p-3 rounded-2xl border border-[#E9C37A]/60 flex gap-3 items-start">
                                        <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-[#ECA144] text-white font-bold flex items-center justify-center flex-shrink-0 text-xs shadow-sm">2</div>
                                        <div>
                                            <strong class="text-[#2D221C] text-xs sm:text-sm md:text-base">Tim Kolaborasi:</strong> Masuk ke Ruang 1-8 bersama kelompok (hingga 5 siswa). Bagi tugas, warnai sketsa Damar Kurung, dan isi tabel analisis bangun datar.
                                        </div>
                                    </div>
                                    <div class="bg-[#FFF9EA] p-2.5 sm:p-3 rounded-2xl border border-[#E9C37A]/60 flex gap-3 items-start">
                                        <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-[#E57354] text-white font-bold flex items-center justify-center flex-shrink-0 text-xs shadow-sm">3</div>
                                        <div>
                                            <strong class="text-[#2D221C] text-xs sm:text-sm md:text-base">Pengaturan & Profil:</strong> Gunakan tombol di pojok kanan atas untuk mengatur suara musik (BGM) dan keluar dari aplikasi.
                                        </div>
                                    </div>
                                    <div class="bg-[#FFF9EA] p-2.5 sm:p-3 rounded-2xl border border-[#E9C37A]/60 flex gap-3 items-start">
                                        <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-[#7E57C2] text-white font-bold flex items-center justify-center flex-shrink-0 text-xs shadow-sm">4</div>
                                        <div>
                                            <strong class="text-[#2D221C] text-xs sm:text-sm md:text-base">Cek Hasil:</strong> Buka menu Hasil untuk memantau status pengerjaan Ruang 1-8 serta melihat karya Damar Kurung, tabel bangun datar, dan catatan apresiasi kelompok pada sesi aktif.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 4. KONTEN: PROFIL PENGEMBANG (FOTO MAKSIMAL BESAR & PROPORSIAL) -->
                            <div id="mTabContent-pengembang" class="pTabContent hidden space-y-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-[#8B5CF6] text-white flex items-center justify-center shadow-md flex-shrink-0">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="font-['Jua'] text-lg sm:text-xl md:text-2xl font-bold text-[#2D221C] leading-none">
                                            Profil Tim Pengembang
                                        </h2>
                                        <p class="text-[11px] sm:text-xs text-[#8A4416] mt-1 font-medium">
                                            Fakultas Ilmu Pendidikan - Universitas Negeri Surabaya
                                        </p>
                                    </div>
                                </div>

                                <!-- 2 Cards Profil Pengembang (Foto Besar Mendominasi Card) -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 md:gap-5 pt-1">
                                    <!-- Profil 1: Dosen / Guru Besar -->
                                    <div class="bg-[#FFF9EA] rounded-2xl sm:rounded-3xl border-2 sm:border-3 border-[#E9C37A] p-3 sm:p-4 text-center shadow-[0_4px_14px_rgba(0,0,0,0.06)] flex flex-col items-center gap-2.5 hover:shadow-md transition-all">
                                        <div class="w-full flex justify-center">
                                            <img src="{{ asset('assets/dosen.jpeg') }}" 
                                                 alt="Prof. Dr. Wiryanto, M.Si." 
                                                 class="w-36 h-36 sm:w-40 sm:h-40 md:w-44 md:h-44 lg:w-48 lg:h-48 xl:w-52 xl:h-52 aspect-square rounded-2xl sm:rounded-3xl object-cover object-top border-3 sm:border-4 border-[#E9C37A] shadow-md">
                                        </div>
                                        <div class="space-y-1">
                                            <h3 class="font-bold text-[#2D221C] text-xs sm:text-sm md:text-base leading-tight">
                                                Prof. Dr. Wiryanto, M.Si.
                                            </h3>
                                            <div class="inline-block px-3 py-0.5 rounded-full bg-[#8B5CF6]/15 text-[#6D28D9] font-bold text-xs sm:text-sm">
                                                Guru Besar
                                            </div>
                                            <p class="text-[11px] sm:text-xs text-[#6D4C33] leading-snug">
                                                S1-Pendidikan Guru Sekolah Dasar
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Profil 2: Mahasiswa -->
                                    <div class="bg-[#FFF9EA] rounded-2xl sm:rounded-3xl border-2 sm:border-3 border-[#E9C37A] p-3 sm:p-4 text-center shadow-[0_4px_14px_rgba(0,0,0,0.06)] flex flex-col items-center gap-2.5 hover:shadow-md transition-all">
                                        <div class="w-full flex justify-center">
                                            <img src="{{ asset('assets/wulan.jpeg') }}" 
                                                 alt="Wulan Rizki Putri" 
                                                 class="w-36 h-36 sm:w-40 sm:h-40 md:w-44 md:h-44 lg:w-48 lg:h-48 xl:w-52 xl:h-52 aspect-square rounded-2xl sm:rounded-3xl object-cover object-top border-3 sm:border-4 border-[#E9C37A] shadow-md">
                                        </div>
                                        <div class="space-y-1">
                                            <h3 class="font-bold text-[#2D221C] text-xs sm:text-sm md:text-base leading-tight">
                                                Wulan Rizki Putri
                                            </h3>
                                            <div class="inline-block px-3 py-0.5 rounded-full bg-[#E57354]/15 text-[#C44322] font-bold text-xs sm:text-sm">
                                                Mahasiswa
                                            </div>
                                            <p class="text-[11px] sm:text-xs text-[#6D4C33] leading-snug">
                                                S1-Pendidikan Guru Sekolah Dasar
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- SOUND SETTINGS (MODAL ONLY) -->
                <x-sound-settings :renderTrigger="false" />

                <!-- EXIT CONFIRMATION MODAL -->
                <x-exit-modal />

            </div>
        </div>

        <script>
            function switchProfTab(tab) {
                document.querySelectorAll('.pTabBtn').forEach(b => {
                    b.classList.remove('bg-[#FDECE4]', 'border-2', 'border-[#E57354]/60', 'active');
                    b.classList.add('bg-[#FFF9EA]', 'border', 'border-[#E9C37A]/60');
                    const iconContainer = b.querySelector('div');
                    if (iconContainer) {
                        iconContainer.className = 'w-10 h-10 rounded-full bg-amber-200 text-amber-900 flex items-center justify-center flex-shrink-0 shadow-sm';
                        const svg = iconContainer.querySelector('svg');
                        if (svg) { svg.className = 'w-5 h-5 text-amber-900'; }
                    }
                    const textSpan = b.querySelector('span');
                    if (textSpan) {
                        textSpan.classList.remove('text-[#3D1804]');
                        textSpan.classList.add('text-[#6D4C33]');
                    }
                });

                document.querySelectorAll('.pTabContent').forEach(c => {
                    c.classList.add('hidden');
                    c.classList.remove('block');
                });

                const activeB = document.getElementById('mTabBtn-' + tab);
                const activeC = document.getElementById('mTabContent-' + tab);
                if (activeB) {
                    activeB.classList.add('bg-[#FDECE4]', 'border-2', 'border-[#E57354]/60', 'active');
                    activeB.classList.remove('bg-[#FFF9EA]', 'border', 'border-[#E9C37A]/60');
                    const iconContainer = activeB.querySelector('div');
                    if (iconContainer) {
                        let bgCol = 'bg-[#E57354]';
                        if (tab === 'cptp') bgCol = 'bg-[#D97706]';
                        else if (tab === 'petunjuk') bgCol = 'bg-[#548466]';
                        else if (tab === 'pengembang') bgCol = 'bg-[#8B5CF6]';
                        iconContainer.className = `w-10 h-10 rounded-full ${bgCol} text-white flex items-center justify-center flex-shrink-0 shadow-sm`;
                        const svg = iconContainer.querySelector('svg');
                        if (svg) { svg.className = 'w-5 h-5 text-white'; }
                    }
                    const textSpan = activeB.querySelector('span');
                    if (textSpan) {
                        textSpan.classList.add('text-[#3D1804]');
                        textSpan.classList.remove('text-[#6D4C33]');
                    }
                }
                if (activeC) {
                    activeC.classList.remove('hidden');
                    activeC.classList.add('block');
                }
            }

            </script>
    </body>
</html>
