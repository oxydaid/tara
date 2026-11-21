<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

        <title>Damar Kurungku - TARA</title>
        <link rel="icon" type="image/webp" href="{{ asset('assets/logotara.webp') }}">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Jua&display=swap" rel="stylesheet">

        <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/4.0.0/model-viewer.min.js"></script>

        <script>
            try {
                if (sessionStorage.getItem('app.booted') === '1') {
                    document.documentElement.classList.add('app-booted');
                }
            } catch {}
        </script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-[100dvh] h-[100dvh] overflow-hidden font-['Jua'] select-none">
        @php
            $bg = asset('assets/berandamalam.webp');
        @endphp

        <div class="landscape-force">
            <div data-app-root data-content style="background-image: url('{{ $bg }}'); background-size: cover; background-position: center;">
                
                <!-- ============================================================
                     1. TOP BAR KIRI: HANYA TOMBOL HOME (BERANDA)
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
                     SLIDE 1: PEMBUKA (DESKTOP: PERFECT CENTER, MOBILE: SAMPING YUK DAMAR)
                     ============================================================ -->
                <div id="slide-1" class="dkSlide absolute inset-0 z-30 pointer-events-auto transition-opacity duration-300">
                    
                    <!-- Bubble Chat Panel Wrapper -->
                    <div class="absolute top-1/2 -translate-y-1/2 left-[28%] sm:left-[30%] right-[5%] sm:right-[8%] md:left-1/2 md:right-auto md:-translate-x-1/2 z-30 flex items-center justify-center pointer-events-auto w-auto md:w-full md:max-w-2xl px-2">
                        <div class="relative w-full bg-[#FFFDF5] border-4 border-[#E9C37A] rounded-[32px] sm:rounded-[42px] md:rounded-[50px] p-6 sm:p-9 md:p-11 shadow-[0_20px_45px_rgba(0,0,0,0.22),0_4px_12px_rgba(233,195,122,0.3)]">
                            <!-- Ekor Balon Chat Mengarah ke Karakter Yuk Damar di Kiri -->
                            <div class="absolute -left-[24px] sm:-left-[28px] top-1/2 -translate-y-1/2 w-0 h-0 border-t-[14px] sm:border-t-[18px] border-t-transparent border-r-[24px] sm:border-r-[28px] border-r-[#E9C37A] border-b-[14px] sm:border-b-[18px] border-b-transparent"></div>
                            <div class="absolute -left-[16px] sm:-left-[19px] top-1/2 -translate-y-1/2 w-0 h-0 border-t-[14px] sm:border-t-[18px] border-t-transparent border-r-[24px] sm:border-r-[28px] border-r-[#FFFDF5] border-b-[14px] sm:border-b-[18px] border-b-transparent"></div>
                            
                            <p class="text-base sm:text-lg md:text-xl lg:text-2xl text-[#3D1804] font-normal leading-relaxed text-left select-none tracking-wide">
                                “Hai teman-teman, apa kamu sudah pernah melihat lampion tersebut? Ini disebut sebagai <strong class="text-[#E57354]">Damar Kurung</strong> warisan budaya benda dari Kabupaten Gresik, Jawa Timur! Nah, untuk mengetahui lebih dalam tentang Damar Kurung, ayo ikuti kegiatan eksplorasi ini ya!”
                            </p>
                        </div>
                    </div>

                    <!-- TOMBOL NAVIGASI BAWAH SLIDE 1 -->
                    <div class="absolute bottom-4 left-4 sm:bottom-6 sm:left-6 z-40">
                        <x-btn-back href="{{ route('eksplorasi') }}" title="Kembali ke Ruang Eksplorasi" />
                    </div>
                    <div class="absolute bottom-4 right-4 sm:bottom-6 sm:right-6 z-40">
                        <x-btn-next onclick="goToSlide(2)" title="Lanjut ke Sejarah Damar Kurung" />
                    </div>
                </div>

                <!-- ============================================================
                     SLIDE 2: SEJARAH DAMAR KURUNG (BUBBLE CHAT & VIDEO YOUTUBE)
                     ============================================================ -->
                <div id="slide-2" class="dkSlide hidden absolute inset-0 z-30 pointer-events-auto transition-opacity duration-300">
                    
                    <!-- Bubble Chat Panel Wrapper (Enlarged) -->
                    <div class="absolute top-1/2 -translate-y-1/2 left-[28%] sm:left-[30%] right-[4%] sm:right-[6%] md:left-1/2 md:right-auto md:-translate-x-1/2 z-30 flex items-center justify-center pointer-events-auto w-auto md:w-full md:max-w-4xl lg:max-w-5xl px-2">
                        <div class="relative w-full max-h-[88vh] overflow-y-auto bg-[#FFFDF5] border-4 border-[#E9C37A] rounded-[30px] sm:rounded-[38px] md:rounded-[46px] p-5 sm:p-7 md:p-8 lg:p-9 shadow-[0_22px_50px_rgba(0,0,0,0.25),0_4px_12px_rgba(233,195,122,0.3)]">
                            <!-- Ekor Balon Chat Mengarah ke Karakter Yuk Damar di Kiri -->
                            <div class="absolute -left-[24px] sm:-left-[28px] top-1/3 -translate-y-1/2 w-0 h-0 border-t-[14px] sm:border-t-[18px] border-t-transparent border-r-[24px] sm:border-r-[28px] border-r-[#E9C37A] border-b-[14px] sm:border-b-[18px] border-b-transparent"></div>
                            <div class="absolute -left-[16px] sm:-left-[19px] top-1/3 -translate-y-1/2 w-0 h-0 border-t-[14px] sm:border-t-[18px] border-t-transparent border-r-[24px] sm:border-r-[28px] border-r-[#FFFDF5] border-b-[14px] sm:border-b-[18px] border-b-transparent"></div>
                            
                            <p class="text-xs sm:text-sm md:text-base lg:text-lg text-[#3D1804] font-normal leading-relaxed text-left select-none tracking-wide mb-3 sm:mb-4">
                                “Damar Kurung dipelopori dan dilestarikan oleh maestro <strong class="text-[#E57354]">Sriwati Mas'ud (Mbah Mas'ud)</strong>. Lampion ini memiliki ciri khas bentuk kerucut di bagian atas serta 4 sisi lukisan bercerita tentang tradisi masyarakat Gresik menyambut malam Lailatul Qadar. Yuk tonton video animasinya!”
                            </p>

                            <!-- Responsive YouTube Video Frame (Enlarged) -->
                            <div class="relative w-full aspect-video max-h-[380px] sm:max-h-[430px] md:max-h-[480px] lg:max-h-[520px] rounded-2xl overflow-hidden border-2 border-[#E9C37A] shadow-md bg-black mx-auto">
                                <iframe id="youtubePlayer"
                                        class="w-full h-full"
                                        src="https://www.youtube-nocookie.com/embed/zGuzJby8Xuw?rel=0&modestbranding=1&enablejsapi=1" 
                                        title="Apa itu Damar Kurung?" 
                                        frameborder="0" 
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                        referrerpolicy="strict-origin-when-cross-origin"
                                        allowfullscreen>
                                </iframe>
                            </div>
                        </div>
                    </div>

                    <!-- TOMBOL NAVIGASI BAWAH SLIDE 2 -->
                    <div class="absolute bottom-4 left-4 sm:bottom-6 sm:left-6 z-40">
                        <x-btn-back onclick="goToSlide(1)" title="Kembali ke Pembuka" />
                    </div>
                    <div class="absolute bottom-4 right-4 sm:bottom-6 sm:right-6 z-40">
                        <x-btn-next onclick="goToSlide(3)" title="Lanjut ke 4 Tema Motif" />
                    </div>
                </div>

                <!-- ============================================================
                     SLIDE 3: TEMA MOTIF DAMAR KURUNG (3D INTERACTIVE SHOWCASE)
                     ============================================================ -->
                <div id="slide-3" class="dkSlide hidden absolute inset-0 z-30 pointer-events-auto transition-opacity duration-300">
                    
                    <!-- Konten Tengah Layar Penuh -->
                    <div class="absolute inset-x-0 top-2 sm:top-4 bottom-16 sm:bottom-20 flex justify-center px-4 sm:px-8 z-30 pointer-events-auto">
                        <div class="w-full max-w-4xl h-full flex flex-col justify-between items-center gap-1.5 sm:gap-2.5">
                            
                            <!-- 1. Header Atas: Kotak Judul Motif & 4 Tab Selector -->
                            <div class="shrink-0 flex flex-col items-center gap-1.5 z-40">
                                <!-- Kotak Judul Motif Utama (Tanpa Nomor) -->
                                <div class="inline-flex items-center justify-center gap-2.5 px-6 py-1.5 sm:px-8 sm:py-2 rounded-2xl sm:rounded-full border-3 border-[#E9C37A] shadow-[0_6px_16px_rgba(0,0,0,0.35)] select-none"
                                     style="background: linear-gradient(180deg, #9E531F 0%, #7E3E11 40%, #522204 100%);">
                                    <span id="motifName" class="text-[#FFF2A6] text-sm sm:text-lg md:text-2xl font-bold tracking-wide" 
                                          style="text-shadow: 2px 2px 0 #3D1804, -2px -2px 0 #3D1804, 2px -2px 0 #3D1804, -2px 2px 0 #3D1804;">
                                        Motif Religi
                                    </span>
                                </div>

                                <!-- 4 Tombol Pilihan Tema Motif (Hanya Nama Motif) -->
                                <div class="flex items-center justify-center gap-1.5 sm:gap-2.5 select-none">
                                    <button type="button" onclick="selectMotif(0)" class="motifPillBtn py-1 sm:py-1.5 px-3 sm:px-4 rounded-xl text-xs sm:text-sm md:text-base font-bold border-2 transition-all flex items-center justify-center gap-1.5 cursor-pointer bg-[#E57354] border-[#E57354] text-white shadow-md scale-105 pointer-events-auto">
                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l1.5 2h-3L12 2zm0 3c2.76 0 5 2.24 5 5v1h1c1.1 0 2 .9 2 2v8H4v-8c0-1.1.9-2 2-2h1v-1c0-2.76 2.24-5 5-5zm0 11a2.5 2.5 0 00-2.5 2.5V20h5v-1.5A2.5 2.5 0 0012 16z"/></svg>
                                        <span>Religi</span>
                                    </button>
                                    <button type="button" onclick="selectMotif(1)" class="motifPillBtn py-1 sm:py-1.5 px-3 sm:px-4 rounded-xl text-xs sm:text-sm md:text-base font-bold border-2 transition-all flex items-center justify-center gap-1.5 cursor-pointer bg-[#FFF8E7]/90 border-[#E9C37A] text-[#8A4416] hover:bg-[#FFEEC2] pointer-events-auto">
                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                                        <span>Sosial</span>
                                    </button>
                                    <button type="button" onclick="selectMotif(2)" class="motifPillBtn py-1 sm:py-1.5 px-3 sm:px-4 rounded-xl text-xs sm:text-sm md:text-base font-bold border-2 transition-all flex items-center justify-center gap-1.5 cursor-pointer bg-[#FFF8E7]/90 border-[#E9C37A] text-[#8A4416] hover:bg-[#FFEEC2] pointer-events-auto">
                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3c-4.97 0-9 4.03-9 9 0 2.12.74 4.07 1.97 5.61L4.35 19.4c-.39.39-.39 1.02 0 1.41.39.39 1.02.39 1.41 0l1.9-1.9C9.28 19.59 10.59 20 12 20c4.97 0 9-4.03 9-9s-4.03-9-9-9zm-4 6c.83 0 1.5.67 1.5 1.5S8.83 12 8 12s-1.5-.67-1.5-1.5S7.17 9 8 9zm4-2c.83 0 1.5.67 1.5 1.5S12.83 10 12 10s-1.5-.67-1.5-1.5S11.17 7 12 7zm4 4c.83 0 1.5.67 1.5 1.5S16.83 14 16 14s-1.5-.67-1.5-1.5S15.17 11 16 11z"/></svg>
                                        <span>Kesenian</span>
                                    </button>
                                    <button type="button" onclick="selectMotif(3)" class="motifPillBtn py-1 sm:py-1.5 px-3 sm:px-4 rounded-xl text-xs sm:text-sm md:text-base font-bold border-2 transition-all flex items-center justify-center gap-1.5 cursor-pointer bg-[#FFF8E7]/90 border-[#E9C37A] text-[#8A4416] hover:bg-[#FFEEC2] pointer-events-auto">
                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L1 7l11 5 9-4.09V17h2V7L12 2zm0 13l-7-3.18V17l7 4 7-4v-5.18L12 15z"/></svg>
                                        <span>Adat</span>
                                    </button>
                                </div>
                            </div>

                            <!-- 2. Area 3D Model Center diapit Arrow Hijau Kiri & Kanan -->
                            <div class="w-full flex-1 min-h-0 flex items-center justify-center gap-3 sm:gap-6 md:gap-8 my-auto z-40">
                                
                                <!-- Panah Kiri (Motif Sebelumnya) -->
                                <button type="button" 
                                        id="btnPrevMotif"
                                        onclick="changeMotif(-1)" 
                                        class="group relative inline-flex items-center justify-center rounded-full border-[3.5px] border-white/95 text-white transition-all duration-200 hover:scale-110 active:scale-95 active:translate-y-1 cursor-pointer select-none focus:outline-none shrink-0 z-50 pointer-events-auto w-10 h-10 sm:w-12 sm:h-12 md:w-14 md:h-14"
                                        style="background: linear-gradient(180deg, #4ADE80 0%, #22C55E 50%, #15803D 100%); box-shadow: 0 5px 0 #0F5128, 0 8px 16px rgba(0,0,0,0.3), inset 0 2px 0 rgba(255,255,255,0.6), inset 0 -2px 0 rgba(0,0,0,0.25);"
                                        title="Motif Sebelumnya">
                                    <span class="absolute top-1 left-2 right-2 h-1/3 rounded-full bg-gradient-to-b from-white/40 to-transparent pointer-events-none"></span>
                                    <svg viewBox="0 0 24 24" class="w-1/2 h-1/2 text-white -translate-x-0.5 drop-shadow-[0_2px_3px_rgba(0,0,0,0.35)]" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>

                                <!-- Kontainer Model 3D -->
                                <div class="relative flex-1 max-w-[340px] sm:max-w-[400px] md:max-w-[460px] h-full min-h-[160px] sm:min-h-[200px] md:min-h-[240px] flex items-center justify-center">
                                    <model-viewer
                                        id="motifViewer"
                                        src="{{ asset('assets/damar-kurung-religii.glb') }}"
                                        poster="{{ asset('assets/tema_religi.webp') }}"
                                        alt="Model 3D Motif Damar Kurung"
                                        camera-controls
                                        touch-action="pan-y"
                                        auto-rotate
                                        rotation-per-second="20deg"
                                        auto-rotate-delay="1000"
                                        shadow-intensity="1.3"
                                        exposure="1.05"
                                        camera-orbit="0deg 75deg 105%"
                                        loading="lazy"
                                        reveal="auto"
                                        style="width: 100%; height: 100%; min-height: 160px; display: block;"
                                        class="cursor-grab active:cursor-grabbing w-full h-full"
                                    >
                                        <div slot="poster" class="w-full h-full flex flex-col items-center justify-center bg-transparent">
                                            <img src="{{ asset('assets/tema_religi.webp') }}" class="max-h-[85%] max-w-[85%] object-contain drop-shadow-lg animate-pulse" alt="Motif Religi">
                                            <span class="text-xs text-[#8A4416] font-bold mt-1 bg-[#FFF8E7]/90 px-2.5 py-0.5 rounded-full border border-[#E9C37A] shadow-sm">Memuat Model 3D...</span>
                                        </div>
                                    </model-viewer>
                                </div>

                                <!-- Panah Kanan (Motif Berikutnya) -->
                                <button type="button" 
                                        id="btnNextMotif"
                                        onclick="changeMotif(1)" 
                                        class="group relative inline-flex items-center justify-center rounded-full border-[3.5px] border-white/95 text-white transition-all duration-200 hover:scale-110 active:scale-95 active:translate-y-1 cursor-pointer select-none focus:outline-none shrink-0 z-50 pointer-events-auto w-10 h-10 sm:w-12 sm:h-12 md:w-14 md:h-14"
                                        style="background: linear-gradient(180deg, #4ADE80 0%, #22C55E 50%, #15803D 100%); box-shadow: 0 5px 0 #0F5128, 0 8px 16px rgba(0,0,0,0.3), inset 0 2px 0 rgba(255,255,255,0.6), inset 0 -2px 0 rgba(0,0,0,0.25);"
                                        title="Motif Berikutnya">
                                    <span class="absolute top-1 left-2 right-2 h-1/3 rounded-full bg-gradient-to-b from-white/40 to-transparent pointer-events-none"></span>
                                    <svg viewBox="0 0 24 24" class="w-1/2 h-1/2 text-white translate-x-0.5 drop-shadow-[0_2px_3px_rgba(0,0,0,0.35)]" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>

                            <!-- 3. Kotak Deskripsi Model Rounded Mewah & Rapi -->
                            <div class="w-full max-w-3xl shrink-0 rounded-[22px] sm:rounded-[28px] md:rounded-[32px] border-3 sm:border-4 border-[#E9C37A] shadow-[0_12px_28px_rgba(0,0,0,0.3),inset_0_2px_0_rgba(255,255,255,0.9)] px-5 sm:px-8 md:px-10 py-2.5 sm:py-3.5 text-center select-none"
                                 style="background: linear-gradient(180deg, #FFFDF8 0%, #FFF9EC 50%, #FDF3DB 100%);">
                                <div id="motifDescription" class="text-[#3D1804] text-[11px] sm:text-xs md:text-sm font-semibold leading-relaxed whitespace-pre-line max-h-16 sm:max-h-20 md:max-h-24 overflow-y-auto">
Motif Religi melukiskan kekhusyukan dan semarak kegiatan ibadah masyarakat Muslim di Gresik, terutama saat menyambut bulan suci Ramadhan dan malam Lailatul Qadar.

Lukisan pada lampion ini menggambarkan suasana shalat tarawih berjamaah di surau atau masjid, tadarus Al-Qur'an, serta arak-arakan anak yang riang membawa damar kurung.
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- TOMBOL NAVIGASI BAWAH SLIDE 3 -->
                    <div class="absolute bottom-3 left-3 sm:bottom-5 sm:left-5 z-40">
                        <x-btn-back onclick="goToSlide(2)" title="Sebelumnya: Sejarah" />
                    </div>
                    <div class="absolute bottom-3 right-3 sm:bottom-5 sm:right-5 z-40">
                        <x-btn-next href="{{ route('eksplorasi') }}" title="Selesai Eksplorasi" />
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
            var currentSlide = 1;

            function goToSlide(slideNum) {
                currentSlide = slideNum;
                document.querySelectorAll('.dkSlide').forEach(slide => {
                    slide.classList.add('hidden');
                });
                const targetSlide = document.getElementById('slide-' + slideNum);
                if (targetSlide) {
                    targetSlide.classList.remove('hidden');
                }

                // If leaving video slide, pause video if needed
                if (slideNum !== 2) {
                    const iframe = document.getElementById('youtubePlayer');
                    if (iframe && iframe.contentWindow) {
                        iframe.contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
                    }
                }

                // If going to slide 3, refresh model-viewer rendering framing
                if (slideNum === 3) {
                    const mv = document.getElementById('motifViewer');
                    if (mv) {
                        setTimeout(() => {
                            if (typeof mv.dismissPoster === 'function') mv.dismissPoster();
                            if (typeof mv.updateFraming === 'function') mv.updateFraming();
                        }, 80);
                    }
                }
            }

            // DATA 4 TEMA MOTIF 3D DAMAR KURUNG LENGKAP
            var motifsData = [
                {
                    id: 1,
                    name: "Motif Religi",
                    poster: "{{ asset('assets/tema_religi.webp') }}",
                    modelUrl: "{{ asset('assets/damar-kurung-religii.glb') }}",
                    description: "Motif Religi melukiskan kekhusyukan dan semarak kegiatan ibadah masyarakat Muslim di Gresik, terutama saat menyambut bulan suci Ramadhan dan malam Lailatul Qadar.\n\nLukisan pada lampion ini menggambarkan suasana shalat tarawih berjamaah di surau atau masjid, tadarus Al-Qur'an, serta arak-arakan anak yang riang membawa damar kurung."
                },
                {
                    id: 2,
                    name: "Motif Kehidupan Sosial",
                    poster: "{{ asset('assets/tema_kehidupansosial.webp') }}",
                    modelUrl: "{{ asset('assets/damar-kurung-sosial.glb') }}",
                    description: "Motif Kehidupan Sosial melukiskan kehangatan interaksi dan kebersamaan masyarakat Gresik tempo dulu.\n\nNuansa lukisan ini merekam indahnya semangat gotong royong warga desa, keceriaan suasana pasar malam rakyat, aneka permainan tradisional anak-anak, serta momen silaturahmi antar warga yang rukun dan penuh kegembiraan."
                },
                {
                    id: 3,
                    name: "Motif Kesenian & Budaya",
                    poster: "{{ asset('assets/tema_kesenian.webp') }}",
                    modelUrl: "{{ asset('assets/damar-kurung-kesenian.glb') }}",
                    description: "Motif Kesenian memperlihatkan kekayaan seni pertunjukan tradisional Gresik yang meriah dan penuh warna.\n\nSalah satu adegan yang paling ikonik adalah atraksi Tari Pencak Macan (singa jantan, macan, genderuwo, dan monyet), alunan musik patrol sahur, tabuhan rebana terbangan, serta pawai seni keliling kota."
                },
                {
                    id: 4,
                    name: "Motif Adat Istiadat",
                    poster: "{{ asset('assets/tema_adat.webp') }}",
                    modelUrl: "{{ asset('assets/damar-kurung-adat.glb') }}",
                    description: "Motif Adat Istiadat mengabadikan upacara dan perayaan tradisi turun-temurun masyarakat Gresik sebagai ungkapan rasa syukur dan pelestarian warisan leluhur.\n\nDi antaranya adalah tradisi Pasar Bandeng menjelang Hari Raya Idul Fitri untuk menimbang dan melelang bandeng raksasa terbaik, arak-arakan gunungan tumpeng selamatan desa, serta tradisi pesta laut nelayan pesisir."
                }
            ];

            var activeMotifIndex = 0;

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

            function selectMotif(index) {
                if (index < 0) index = motifsData.length - 1;
                if (index >= motifsData.length) index = 0;
                activeMotifIndex = index;

                const item = motifsData[activeMotifIndex];
                const motifViewer = document.getElementById('motifViewer');

                // Update 3D Model src & poster
                if (motifViewer) {
                    const resolvedUrl = getGlbModelUrl(item.modelUrl);
                    motifViewer.setAttribute('src', resolvedUrl);
                    motifViewer.setAttribute('poster', item.poster);
                    motifViewer.src = resolvedUrl;
                    motifViewer.poster = item.poster;
                    motifViewer.cameraOrbit = '0deg 75deg 105%';
                    if (typeof motifViewer.dismissPoster === 'function') {
                        motifViewer.dismissPoster();
                    }
                }

                // Update Title Name & Description
                const motifName = document.getElementById('motifName');
                if (motifName) motifName.textContent = item.name;

                const motifDescription = document.getElementById('motifDescription');
                if (motifDescription) motifDescription.textContent = item.description;

                // Update 4 indicator pills
                document.querySelectorAll('.motifPillBtn').forEach((btn, i) => {
                    if (i === activeMotifIndex) {
                        btn.className = 'motifPillBtn py-1 sm:py-1.5 px-3 sm:px-4 rounded-xl text-xs sm:text-sm md:text-base font-bold border-2 transition-all flex items-center justify-center gap-1.5 cursor-pointer bg-[#E57354] border-[#E57354] text-white shadow-md scale-105 pointer-events-auto';
                    } else {
                        btn.className = 'motifPillBtn py-1 sm:py-1.5 px-3 sm:px-4 rounded-xl text-xs sm:text-sm md:text-base font-bold border-2 transition-all flex items-center justify-center gap-1.5 cursor-pointer bg-[#FFF8E7]/90 border-[#E9C37A] text-[#8A4416] hover:bg-[#FFEEC2] pointer-events-auto';
                    }
                });
            }

            function changeMotif(step) {
                selectMotif(activeMotifIndex + step);
            }

            // Expose to window explicitly
            window.changeMotif = changeMotif;
            window.selectMotif = selectMotif;

            function initDamarKurungPage() {
                ensureModelViewerScript();
                const motifViewer = document.getElementById('motifViewer');
                if (motifViewer) {
                    const initialUrl = getGlbModelUrl("{{ asset('assets/damar-kurung-religii.glb') }}");
                    motifViewer.src = initialUrl;
                    motifViewer.setAttribute('src', initialUrl);
                }
                selectMotif(0);
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initDamarKurungPage);
            } else {
                initDamarKurungPage();
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
                } else if (e.key === 'ArrowRight') {
                    if (currentSlide === 3) {
                        changeMotif(1);
                    } else if (currentSlide < 3) {
                        goToSlide(currentSlide + 1);
                    }
                } else if (e.key === 'ArrowLeft') {
                    if (currentSlide === 3) {
                        changeMotif(-1);
                    } else if (currentSlide > 1) {
                        goToSlide(currentSlide - 1);
                    }
                }
            });
        </script>
