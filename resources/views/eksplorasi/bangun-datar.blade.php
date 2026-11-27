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

            /* Responsif Modal Jenis Bangun Datar pada Mobile Portrait (ter-rotasi) & Landscape Pendek */
            @media screen and (max-width: 1024px) and (orientation: portrait), screen and (max-height: 540px) {
                #shapeVariantModal.shape-variant-modal-wrap {
                    padding: 8px 14px !important;
                }
                #shapeVariantModalBox {
                    max-width: 96% !important;
                    max-height: 94% !important;
                    padding: 10px 14px !important;
                    border-radius: 20px !important;
                }
                #shapeVariantModalHeader {
                    margin-bottom: 8px !important;
                }
                #shapeVariantModalTitle {
                    font-size: 16px !important;
                }
                #shapeVariantModalSubtitle {
                    font-size: 11px !important;
                }
                .shape-variant-close-btn {
                    width: 32px !important;
                    height: 32px !important;
                    font-size: 16px !important;
                }
                .shape-variant-inner {
                    padding: 10px !important;
                    border-radius: 14px !important;
                }
                #shapeVariantListView {
                    grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
                    gap: 8px !important;
                }
                .shape-variant-card {
                    padding: 8px !important;
                    gap: 5px !important;
                    border-radius: 14px !important;
                }
                .shape-variant-thumb {
                    height: 48px !important;
                    padding: 4px !important;
                }
                .shape-variant-name {
                    font-size: 11px !important;
                    line-height: 1.2 !important;
                }
                .shape-variant-badge {
                    font-size: 9px !important;
                    padding: 1px 6px !important;
                }
                .shape-variant-detail-grid {
                    grid-template-columns: 165px minmax(0, 1fr) !important;
                    gap: 10px !important;
                    padding: 10px !important;
                    align-items: start !important;
                }
                .shape-variant-detail-grid > div {
                    grid-column: auto !important;
                }
                .shape-variant-detail-left {
                    padding: 8px !important;
                }
                .shape-variant-detail-thumb {
                    width: 96px !important;
                    height: 74px !important;
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
                                    $segitigaVariants = $shapeList->where('type', 'segitiga')->values();
                                    $trapesiumVariants = $shapeList->where('type', 'trapesium')->values();

                                    $mapVariantPayload = static function ($variant): array {
                                        $svgHtml = $variant->custom_image_path
                                            ? '<img src="'.e(asset($variant->custom_image_path)).'" alt="'.e($variant->name).'" class="max-w-full max-h-full object-contain">'
                                            : ($variant->svg_inline ?? '');

                                        return [
                                            'id' => $variant->id,
                                            'name' => $variant->name,
                                            'color' => $variant->color,
                                            'bagian_damar_kurung' => $variant->bagian_damar_kurung,
                                            'ciri_ciri' => $variant->ciri_ciri ?? [],
                                            'svg_html' => $svgHtml,
                                        ];
                                    };

                                    $variantGroupsData = [
                                        'segitiga' => [
                                            'title' => 'Jenis-Jenis Segitiga',
                                            'singular' => 'Segitiga',
                                            'color' => $segitigaVariants->first()->color ?? '#E57354',
                                            'items' => $segitigaVariants->map($mapVariantPayload)->all(),
                                        ],
                                        'trapesium' => [
                                            'title' => 'Jenis-Jenis Trapesium',
                                            'singular' => 'Trapesium',
                                            'color' => $trapesiumVariants->first()->color ?? '#EC4899',
                                            'items' => $trapesiumVariants->map($mapVariantPayload)->all(),
                                        ],
                                    ];

                                    $displayRows = collect();
                                    $addedGroupTypes = [];

                                    foreach ($shapeList as $shape) {
                                        if (in_array($shape->type, ['segitiga', 'trapesium'], true)) {
                                            if (in_array($shape->type, $addedGroupTypes, true)) {
                                                continue;
                                            }
                                            $addedGroupTypes[] = $shape->type;

                                            if ($shape->type === 'segitiga') {
                                                $displayRows->push((object) [
                                                    'is_modal_group' => true,
                                                    'group_type' => 'segitiga',
                                                    'name' => 'Segitiga',
                                                    'color' => '#E57354',
                                                    'bagian_damar_kurung' => 'Atap Menara & Rumah',
                                                    'custom_image_path' => null,
                                                    'svg_inline' => \App\Models\BangunDatarShape::generateDefaultSvg('segitiga'),
                                                    'variant_count' => $segitigaVariants->count(),
                                                    'ciri_ciri' => [
                                                        'Memiliki 3 sisi dan 3 titik sudut',
                                                        'Jumlah ketiga sudutnya selalu 180°',
                                                        'Memiliki beberapa jenis berdasarkan sisi dan sudutnya',
                                                    ],
                                                ]);
                                            } else {
                                                $displayRows->push((object) [
                                                    'is_modal_group' => true,
                                                    'group_type' => 'trapesium',
                                                    'name' => 'Trapesium',
                                                    'color' => '#EC4899',
                                                    'bagian_damar_kurung' => 'Perahu / Bak',
                                                    'custom_image_path' => null,
                                                    'svg_inline' => \App\Models\BangunDatarShape::generateDefaultSvg('trapesium'),
                                                    'variant_count' => $trapesiumVariants->count(),
                                                    'ciri_ciri' => [
                                                        'Bangun segi empat dengan sepasang sisi sejajar',
                                                        'Memiliki 4 sisi dan 4 titik sudut berjumlah 360°',
                                                        'Memiliki beberapa jenis berdasarkan sisi dan sudutnya',
                                                    ],
                                                ]);
                                            }

                                            continue;
                                        }

                                        $displayRows->push((object) [
                                            'is_modal_group' => false,
                                            'group_type' => null,
                                            'name' => $shape->name,
                                            'color' => $shape->color,
                                            'bagian_damar_kurung' => $shape->bagian_damar_kurung,
                                            'custom_image_path' => $shape->custom_image_path,
                                            'svg_inline' => $shape->svg_inline,
                                            'variant_count' => 0,
                                            'ciri_ciri' => $shape->ciri_ciri ?? [],
                                        ]);
                                    }

                                    $totalShapePages = max(1, (int) ceil($displayRows->count() / 2));
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
                                                @forelse ($displayRows as $shape)
                                                    @php
                                                        $pageIdx = intdiv($loop->index, 2);
                                                        $isGroupModal = ! empty($shape->is_modal_group);
                                                    @endphp
                                                    <tr class="shape-paginated-row transition-colors {{ $isGroupModal ? 'cursor-pointer hover:bg-[#FFF2D6]/85' : 'hover:bg-[#FFF8E7]' }} {{ $loop->even ? 'bg-[#FFFFFF]' : 'bg-[#FFFDF7]' }} {{ $pageIdx > 0 ? 'hidden' : '' }}"
                                                        data-shape-page="{{ $pageIdx }}"
                                                        @if ($isGroupModal)
                                                            onclick="openShapeVariantModal('{{ $shape->group_type }}')"
                                                            onkeydown="if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); openShapeVariantModal('{{ $shape->group_type }}'); }"
                                                            tabindex="0"
                                                            role="button"
                                                            aria-label="Lihat jenis-jenis {{ $shape->name }}"
                                                        @endif>
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
                                                            <div class="flex flex-col items-center justify-center gap-1.5">
                                                                <span class="inline-block px-2 sm:px-3 py-1 rounded-full text-[11px] sm:text-xs font-bold leading-snug break-words"
                                                                      style="background-color: {{ $shape->color }}1A; border: 1px solid {{ $shape->color }}55; color: #3D1804;">
                                                                    {{ $shape->name }}
                                                                </span>
                                                                @if ($isGroupModal)
                                                                    <button type="button"
                                                                            onclick="event.stopPropagation(); openShapeVariantModal('{{ $shape->group_type }}')"
                                                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-[#FFF2D6] hover:bg-[#FFE4A8] border border-[#E9C37A] text-[#8A4416] text-[10px] sm:text-[11px] font-bold shadow-2xs transition-transform active:scale-95 cursor-pointer">
                                                                        <span>Lihat {{ $shape->variant_count }} Jenis</span>
                                                                        <svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                                                        </svg>
                                                                    </button>
                                                                @endif
                                                            </div>
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

                                    @if ($displayRows->isNotEmpty())
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

                <!-- MODAL JENIS-JENIS BANGUN DATAR (SEGITIGA & TRAPESIUM) -->
                <div id="shapeVariantModal"
                     class="shape-variant-modal-wrap fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-5 bg-black/65 backdrop-blur-sm opacity-0 pointer-events-none transition-all duration-300 font-['Jua'] select-none"
                     role="dialog"
                     aria-modal="true"
                     aria-labelledby="shapeVariantModalTitle">

                    <div class="absolute inset-0" onclick="closeShapeVariantModal()"></div>

                    <div id="shapeVariantModalBox"
                         class="relative z-10 w-full max-w-[680px] max-h-[90%] flex flex-col transform scale-90 transition-all duration-300 rounded-3xl border-4 border-[#3D1804] px-4 py-4 sm:px-6 sm:py-5 shadow-[0_20px_40px_rgba(0,0,0,0.5),0_8px_0_#280E02]"
                         style="background: linear-gradient(180deg, #9E531F 0%, #7E3E11 25%, #6E330C 65%, #522204 100%);">

                        <!-- Modal Header -->
                        <div id="shapeVariantModalHeader" class="flex items-start justify-between gap-3 mb-3 shrink-0">
                            <div class="min-w-0">
                                <h2 id="shapeVariantModalTitle"
                                    class="text-lg sm:text-2xl font-bold text-[#FFF2A6] leading-tight"
                                    style="text-shadow: 2px 2px 0 #3D1804, -2px -2px 0 #3D1804, 2px -2px 0 #3D1804, -2px 2px 0 #3D1804;">
                                    Jenis-Jenis Segitiga
                                </h2>
                                <p id="shapeVariantModalSubtitle" class="text-[11px] sm:text-xs text-[#FFE8A3] font-medium mt-0.5">
                                    Klik salah satu jenis untuk melihat nama dan ciri-cirinya.
                                </p>
                            </div>
                            <button type="button"
                                    onclick="closeShapeVariantModal()"
                                    aria-label="Tutup modal jenis bangun datar"
                                    class="shape-variant-close-btn w-9 h-9 sm:w-10 sm:h-10 rounded-full border-2 border-white/90 bg-gradient-to-b from-[#EF4444] to-[#B91C1C] text-white font-bold text-lg leading-none flex items-center justify-center shadow-[0_4px_0_#7F1D1D] hover:brightness-110 active:translate-y-[1px] shrink-0 cursor-pointer">
                                &times;
                            </button>
                        </div>

                        <!-- Modal Inner Content -->
                        <div class="bangun-datar-card shape-variant-inner rounded-2xl border-3 border-[#612A07] bg-gradient-to-b from-[#FFFDF0] to-[#FCEEC7] p-3.5 sm:p-5 shadow overflow-y-auto flex-1 min-h-0">
                            <!-- State 1: Daftar Jenis -->
                            <div id="shapeVariantListView" class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 sm:gap-3"></div>

                            <!-- State 2: Detail Jenis Terpilih -->
                            <div id="shapeVariantDetailView" class="hidden flex-col gap-3 sm:gap-4"></div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        <script>
            const SHAPE_VARIANT_GROUPS = @json($variantGroupsData);
            let activeVariantGroupType = null;
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

            function escapeVariantHtml(str) {
                return String(str ?? '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;');
            }

            function renderShapeVariantList(groupType) {
                const group = SHAPE_VARIANT_GROUPS[groupType];
                if (!group) return;

                const titleEl = document.getElementById('shapeVariantModalTitle');
                const subEl = document.getElementById('shapeVariantModalSubtitle');
                const listView = document.getElementById('shapeVariantListView');
                const detailView = document.getElementById('shapeVariantDetailView');

                if (titleEl) titleEl.textContent = group.title;
                if (subEl) subEl.textContent = 'Klik salah satu jenis untuk melihat nama dan ciri-cirinya.';

                if (detailView) {
                    detailView.classList.add('hidden');
                    detailView.classList.remove('flex');
                    detailView.innerHTML = '';
                }

                if (listView) {
                    listView.classList.remove('hidden');
                    listView.innerHTML = (group.items || []).map((item, idx) => `
                        <button type="button"
                                onclick="showShapeVariantDetail('${escapeVariantHtml(groupType)}', ${idx})"
                                class="shape-variant-card group flex flex-col items-center justify-between gap-2 p-3 rounded-2xl border-2 border-[#E9C37A] bg-white hover:bg-[#FFF8E7] hover:border-[#E57354] hover:-translate-y-0.5 active:translate-y-0 shadow-xs hover:shadow-md transition-all cursor-pointer text-center">
                            <div class="shape-variant-thumb w-full h-16 sm:h-20 rounded-xl bg-[#FFFDF7] border border-[#E9C37A]/50 flex items-center justify-center p-2"
                                 style="color: ${escapeVariantHtml(item.color)};">
                                ${item.svg_html || ''}
                            </div>
                            <div class="w-full flex flex-col items-center gap-1">
                                <span class="shape-variant-name text-xs sm:text-sm font-bold text-[#3D1804] leading-snug group-hover:text-[#B45309]">
                                    ${escapeVariantHtml(item.name)}
                                </span>
                                <span class="shape-variant-badge inline-flex items-center gap-1 text-[10px] sm:text-[11px] font-bold text-[#8A4416] bg-[#FFF2D6] px-2 py-0.5 rounded-full border border-[#E9C37A]/70">
                                    Lihat Detail
                                </span>
                            </div>
                        </button>
                    `).join('');
                }
            }

            function showShapeVariantDetail(groupType, idx) {
                const group = SHAPE_VARIANT_GROUPS[groupType];
                const item = group && group.items ? group.items[idx] : null;
                if (!item) return;

                if (window.globalAudio && typeof window.globalAudio.playSfx === 'function') {
                    window.globalAudio.playSfx('button_click');
                }

                const titleEl = document.getElementById('shapeVariantModalTitle');
                const subEl = document.getElementById('shapeVariantModalSubtitle');
                const listView = document.getElementById('shapeVariantListView');
                const detailView = document.getElementById('shapeVariantDetailView');

                if (titleEl) titleEl.textContent = item.name;
                if (subEl) subEl.textContent = `${group.title} • Detail & Ciri-Ciri`;

                if (listView) listView.classList.add('hidden');

                const ciriListHtml = (item.ciri_ciri || []).map(c => `<li>${escapeVariantHtml(c)}</li>`).join('');
                const bagianBadge = item.bagian_damar_kurung
                    ? `<span class="inline-block px-3 py-1 rounded-full bg-[#FFF2D6] border border-[#E9C37A] text-[#8A4416] text-xs font-bold">Bagian Damar Kurung: ${escapeVariantHtml(item.bagian_damar_kurung)}</span>`
                    : '';

                const totalItems = group.items.length;
                const prevIdx = idx > 0 ? idx - 1 : totalItems - 1;
                const nextIdx = idx < totalItems - 1 ? idx + 1 : 0;

                if (detailView) {
                    detailView.innerHTML = `
                        <div class="shape-variant-detail-grid grid grid-cols-1 sm:grid-cols-12 gap-3 sm:gap-4 items-center bg-white rounded-2xl border-2 border-[#E9C37A]/80 p-3.5 sm:p-4 shadow-xs">
                            <div class="shape-variant-detail-left sm:col-span-5 flex flex-col items-center justify-center gap-2 bg-[#FFFDF7] rounded-xl border border-[#E9C37A]/50 p-3 sm:p-4">
                                <div class="shape-variant-detail-thumb w-28 h-24 sm:w-36 sm:h-28 flex items-center justify-center"
                                     style="color: ${escapeVariantHtml(item.color)};">
                                    ${item.svg_html || ''}
                                </div>
                                ${bagianBadge}
                            </div>
                            <div class="sm:col-span-7 flex flex-col gap-2 text-left min-w-0">
                                <div>
                                    <span class="inline-block px-3 py-1 rounded-full text-xs sm:text-sm font-bold"
                                          style="background-color: ${escapeVariantHtml(item.color)}1A; border: 1px solid ${escapeVariantHtml(item.color)}55; color: #3D1804;">
                                        ${escapeVariantHtml(item.name)}
                                    </span>
                                </div>
                                <div>
                                    <h3 class="text-xs sm:text-sm font-bold text-[#8A4416] mb-1">Ciri-Ciri Bangun Datar:</h3>
                                    <ol class="list-decimal pl-4 sm:pl-5 space-y-1 text-xs sm:text-sm text-[#3D1804] leading-relaxed">
                                        ${ciriListHtml}
                                    </ol>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center justify-between gap-2 pt-1">
                            <button type="button"
                                    onclick="backToVariantList()"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-full border-2 border-[#E9C37A] bg-[#FFF2D6] hover:bg-[#FFE4A8] text-[#8A4416] font-bold text-xs sm:text-sm shadow-2xs transition-transform active:scale-95 cursor-pointer">
                                &larr; Kembali ke ${escapeVariantHtml(group.title)}
                            </button>
                            ${totalItems > 1 ? `
                                <div class="flex items-center gap-2">
                                    <button type="button"
                                            onclick="showShapeVariantDetail('${escapeVariantHtml(groupType)}', ${prevIdx})"
                                            class="px-3 py-1.5 rounded-full border-2 border-[#E9C37A] bg-white hover:bg-[#FFF8E7] text-[#8A4416] font-bold text-xs cursor-pointer">
                                        &lsaquo; Sebelumnya
                                    </button>
                                    <button type="button"
                                            onclick="showShapeVariantDetail('${escapeVariantHtml(groupType)}', ${nextIdx})"
                                            class="px-3 py-1.5 rounded-full border-2 border-[#E9C37A] bg-white hover:bg-[#FFF8E7] text-[#8A4416] font-bold text-xs cursor-pointer">
                                        Berikutnya &rsaquo;
                                    </button>
                                </div>
                            ` : ''}
                        </div>
                    `;
                    detailView.classList.remove('hidden');
                    detailView.classList.add('flex');
                }
            }

            function backToVariantList() {
                if (!activeVariantGroupType) return;
                if (window.globalAudio && typeof window.globalAudio.playSfx === 'function') {
                    window.globalAudio.playSfx('button_click');
                }
                renderShapeVariantList(activeVariantGroupType);
            }

            function openShapeVariantModal(groupType) {
                if (!SHAPE_VARIANT_GROUPS[groupType]) return;
                activeVariantGroupType = groupType;
                renderShapeVariantList(groupType);

                if (window.globalAudio && typeof window.globalAudio.playSfx === 'function') {
                    window.globalAudio.playSfx('button_click');
                }

                const modal = document.getElementById('shapeVariantModal');
                const box = document.getElementById('shapeVariantModalBox');
                if (modal) {
                    modal.classList.remove('opacity-0', 'pointer-events-none');
                    modal.classList.add('opacity-100');
                }
                if (box) {
                    box.classList.remove('scale-90');
                    box.classList.add('scale-100');
                }
            }

            function closeShapeVariantModal() {
                const modal = document.getElementById('shapeVariantModal');
                const box = document.getElementById('shapeVariantModalBox');
                if (modal) {
                    modal.classList.remove('opacity-100');
                    modal.classList.add('opacity-0', 'pointer-events-none');
                }
                if (box) {
                    box.classList.remove('scale-100');
                    box.classList.add('scale-90');
                }
            }

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeShapeVariantModal();
                }
            });

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
