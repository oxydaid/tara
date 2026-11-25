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
                        <div class="bangun-datar-card relative w-full h-auto max-h-full overflow-y-auto bg-[#FFFDF5] border-3 sm:border-4 border-[#E9C37A] rounded-[24px] sm:rounded-[34px] md:rounded-[42px] p-3.5 sm:p-5 md:p-6 shadow-[0_22px_50px_rgba(0,0,0,0.25),0_4px_12px_rgba(233,195,122,0.3)] flex flex-col">
                            
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

                            <!-- Grid 2 Kolom: Sisi Kiri Motif Tema Adat, Sisi Kanan Tabel Analisis -->
                            <div class="grid grid-cols-1 lg:grid-cols-12 gap-3 sm:gap-4 items-start min-w-0 w-full">
                                
                                <!-- Sisi Kiri: Model 3D GLB Tema Adat Istiadat Interaktif -->
                                <div class="lg:col-span-5 min-w-0 w-full bg-gradient-to-b from-[#FFF8E7] to-[#FFF3D6] rounded-2xl md:rounded-3xl p-3 sm:p-3.5 border-2 border-[#E9C37A]/80 shadow-xs flex flex-col justify-between">
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

                                <!-- Sisi Kanan: Tabel Analisis Langsung (2 Bangun Datar per Halaman) -->
                                @php
                                    $shapeList = $shapes ?? collect();
                                    $totalShapePages = max(1, (int) ceil($shapeList->count() / 2));
                                @endphp
                                <div class="lg:col-span-7 min-w-0 w-full flex flex-col justify-start">
                                    <div class="w-full overflow-x-auto rounded-2xl border-2 border-[#E9C37A] shadow-md bg-white">
                                        <table class="w-full text-left border-collapse table-fixed sm:table-auto">
                                            <thead class="sticky top-0 z-10">
                                                <tr class="bg-gradient-to-r from-[#2B5F8C] via-[#3B7BB3] to-[#2B5F8C] text-white text-[11px] sm:text-xs md:text-sm uppercase tracking-wider shadow-sm">
                                                    <th class="py-2.5 px-2 sm:px-3 text-center w-[28%] font-bold border-r border-[#6393BD]">GAMBAR</th>
                                                    <th class="py-2.5 px-2 sm:px-3 text-center w-[26%] font-bold border-r border-[#6393BD]">BANGUN DATAR</th>
                                                    <th class="py-2.5 px-2 sm:px-3 text-left w-[46%] font-bold">CIRI-CIRI</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-[#E9C37A]/50 text-xs sm:text-sm text-[#3D1804]">
                                                @forelse ($shapeList as $shape)
                                                    @php
                                                        $pageIdx = intdiv($loop->index, 2);
                                                    @endphp
                                                    <tr class="shape-paginated-row hover:bg-[#FFF8E7] transition-colors {{ $loop->even ? 'bg-[#FFFFFF]' : 'bg-[#FFFDF7]' }} {{ $pageIdx > 0 ? 'hidden' : '' }}"
                                                        data-shape-page="{{ $pageIdx }}">
                                                        <td class="py-3 px-2 sm:px-3 text-center border-r border-[#E9C37A]/40 align-middle">
                                                            <div class="flex flex-col items-center justify-center gap-1.5">
                                                                <div class="w-20 h-16 sm:w-24 sm:h-20 md:w-28 md:h-24 mx-auto flex items-center justify-center"
                                                                     style="color: {{ $shape->color }};">
                                                                    @if ($shape->custom_image_path)
                                                                        <img src="{{ asset($shape->custom_image_path) }}"
                                                                             alt="{{ $shape->name }}"
                                                                             class="max-w-full max-h-full object-contain">
                                                                    @else
                                                                        {!! $shape->svg_inline !!}
                                                                    @endif
                                                                </div>
                                                                @if (filled($shape->bagian_damar_kurung))
                                                                    <span class="font-bold text-[11px] sm:text-xs text-[#8A4416] leading-tight break-words">
                                                                        {{ $shape->bagian_damar_kurung }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td class="py-3 px-2 sm:px-3 text-center font-bold border-r border-[#E9C37A]/40 align-middle">
                                                            <span class="inline-block px-2 sm:px-3 py-1 rounded-full text-[11px] sm:text-xs font-bold leading-snug break-words"
                                                                  style="background-color: {{ $shape->color }}1A; border: 1px solid {{ $shape->color }}55; color: #3D1804;">
                                                                {{ $shape->name }}
                                                            </span>
                                                        </td>
                                                        <td class="py-3 px-2.5 sm:px-3 text-[#5C2B09] leading-snug align-middle">
                                                            <ol class="list-decimal pl-4 sm:pl-5 space-y-1 text-[11px] sm:text-xs text-left break-words">
                                                                @foreach ($shape->ciri_ciri ?? [] as $ciri)
                                                                    <li>{{ $ciri }}</li>
                                                                @endforeach
                                                            </ol>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="py-8 px-4 text-center text-xs sm:text-sm text-[#8A4416] font-bold">
                                                            Data bangun datar belum tersedia. Silakan hubungi guru atau admin.
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    @if ($shapeList->isNotEmpty())
                                        <div class="flex items-center justify-between gap-2 mt-3 px-1">
                                            <x-btn-back id="btnShapePrev"
                                                        onclick="changeShapePage(-1)"
                                                        disabled
                                                        size="w-10 h-10 sm:w-11 sm:h-11"
                                                        variant="orange"
                                                        title="Halaman Sebelumnya"
                                                        aria-label="Halaman Sebelumnya"
                                                        class="disabled:opacity-35 disabled:cursor-not-allowed disabled:hover:scale-100" />

                                            <div id="shapePageIndicator" class="text-xs sm:text-sm font-bold text-[#8A4416] bg-[#FFF2D6] px-3.5 py-1.5 rounded-full border border-[#E9C37A] shadow-2xs text-center">
                                                Halaman 1 dari {{ $totalShapePages }}
                                            </div>

                                            <x-btn-next id="btnShapeNext"
                                                        onclick="changeShapePage(1)"
                                                        :disabled="$totalShapePages <= 1"
                                                        size="w-10 h-10 sm:w-11 sm:h-11"
                                                        variant="orange"
                                                        title="Halaman Berikutnya"
                                                        aria-label="Halaman Berikutnya"
                                                        class="disabled:opacity-35 disabled:cursor-not-allowed disabled:hover:scale-100" />
                                        </div>
                                    @endif
                                </div>

                            </div>

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

                <!-- SOUND SETTINGS (MODAL ONLY) -->
                <x-sound-settings :renderTrigger="false" />

                <!-- EXIT CONFIRMATION MODAL -->
                <x-exit-modal />

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

            var currentShapePage = 0;
            var totalShapePages = {{ $totalShapePages ?? 1 }};

            function renderShapePagination() {
                document.querySelectorAll('.shape-paginated-row').forEach(row => {
                    const pageIdx = parseInt(row.getAttribute('data-shape-page'), 10);
                    row.classList.toggle('hidden', pageIdx !== currentShapePage);
                });

                const indicator = document.getElementById('shapePageIndicator');
                if (indicator) {
                    indicator.textContent = `Halaman ${currentShapePage + 1} dari ${totalShapePages}`;
                }

                const prevBtn = document.getElementById('btnShapePrev');
                const nextBtn = document.getElementById('btnShapeNext');
                if (prevBtn) prevBtn.disabled = currentShapePage <= 0;
                if (nextBtn) nextBtn.disabled = currentShapePage >= totalShapePages - 1;
            }

            function changeShapePage(delta) {
                const next = currentShapePage + delta;
                if (next < 0 || next >= totalShapePages) return;
                currentShapePage = next;
                if (window.globalAudio && typeof window.globalAudio.playSfx === 'function') {
                    window.globalAudio.playSfx('button_click');
                }
                renderShapePagination();
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => {
                    initBangunDatarGlb();
                    renderShapePagination();
                });
            } else {
                initBangunDatarGlb();
                renderShapePagination();
            }

            </script>
    </body>
</html>
