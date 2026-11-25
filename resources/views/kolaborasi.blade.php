<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Tim Kolaborasi - TARA</title>
    <link rel="icon" type="image/webp" href="{{ asset('assets/logotara.webp') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jua&display=swap" rel="stylesheet">

    <script>
        try {
            if (sessionStorage.getItem('app.booted') === '1') {
                document.documentElement.classList.add('app-booted');
            }
        } catch { }
    </script>

    <style>
        /* Sleek rounded scrollbar for Kolaborasi cards & inner scroll panels */
        .kolab-card,
        .kolabStage .overflow-y-auto,
        .kolabStage .overflow-auto,
        .shape-menu {
            -webkit-overflow-scrolling: touch;
            overscroll-behavior: contain;
        }

        @supports not selector(::-webkit-scrollbar) {
            .kolab-card,
            .kolabStage .overflow-y-auto,
            .kolabStage .overflow-auto,
            .shape-menu {
                scrollbar-width: thin;
                scrollbar-color: #E2B774 transparent;
            }
        }

        .kolab-card::-webkit-scrollbar,
        .kolabStage .overflow-y-auto::-webkit-scrollbar,
        .kolabStage .overflow-auto::-webkit-scrollbar,
        .shape-menu::-webkit-scrollbar {
            width: 7px;
            height: 7px;
        }

        .kolab-card::-webkit-scrollbar-track,
        .kolabStage .overflow-y-auto::-webkit-scrollbar-track,
        .kolabStage .overflow-auto::-webkit-scrollbar-track,
        .shape-menu::-webkit-scrollbar-track {
            background: transparent;
        }

        .kolab-card::-webkit-scrollbar-track {
            margin-block: 24px;
        }

        .kolab-card::-webkit-scrollbar-thumb,
        .kolabStage .overflow-y-auto::-webkit-scrollbar-thumb,
        .kolabStage .overflow-auto::-webkit-scrollbar-thumb,
        .shape-menu::-webkit-scrollbar-thumb {
            background-color: #E2B774;
            border-radius: 9999px;
            border: 1.5px solid transparent;
            background-clip: content-box;
        }

        .kolab-card::-webkit-scrollbar-thumb:hover,
        .kolabStage .overflow-y-auto::-webkit-scrollbar-thumb:hover,
        .kolabStage .overflow-auto::-webkit-scrollbar-thumb:hover,
        .shape-menu::-webkit-scrollbar-thumb:hover {
            background-color: #D97706;
        }

        .colorable-part {
            cursor: pointer;
            transition: fill 0.15s ease, filter 0.15s ease, opacity 0.15s ease;
        }

        .colorable-part:hover {
            filter: brightness(0.92) drop-shadow(0 0 3px rgba(229, 115, 84, 0.5));
            opacity: 0.95;
        }

        .colorable-part:active {
            filter: brightness(0.85);
        }

        @keyframes liveShapePulse {
            0% {
                filter: brightness(1.7) drop-shadow(0 0 12px rgba(229, 115, 84, 0.95));
                transform: scale(1.035);
            }

            50% {
                filter: brightness(1.3) drop-shadow(0 0 7px rgba(229, 115, 84, 0.7));
                transform: scale(1.02);
            }

            100% {
                filter: none;
                transform: scale(1);
            }
        }

        .live-shape-pulse {
            animation: liveShapePulse 1.2s cubic-bezier(0.2, 0.8, 0.2, 1) !important;
            transform-box: fill-box;
            transform-origin: center center;
        }

        .review-part {
            pointer-events: none;
        }

        .canvas-svg-viewport svg {
            width: 100%;
            max-width: 290px;
            height: auto;
            max-height: 380px;
            display: block;
            margin: 0 auto;
            overflow: hidden !important;
            filter: drop-shadow(0 8px 16px rgba(0, 0, 0, 0.12));
        }

        .review-svg-viewport svg {
            width: 100%;
            max-width: 190px;
            height: auto;
            max-height: 210px;
            display: block;
            margin: 0 auto;
            overflow: hidden !important;
        }

        .ident-canvas-svg svg {
            width: 100%;
            max-width: 310px;
            height: auto;
            max-height: 360px;
            display: block;
            margin: 0 auto;
            overflow: hidden !important;
            pointer-events: auto;
            user-select: none;
            touch-action: pan-x pan-y;
        }

        .ident-canvas-svg .ident-drag-part {
            cursor: grab;
            pointer-events: auto;
            touch-action: pan-x pan-y;
            transition: fill 0.15s ease, stroke 0.15s ease, filter 0.15s ease, stroke-width 0.15s ease;
        }

        .ident-canvas-svg .ident-drag-part:hover {
            fill: #FEF3C7 !important;
            stroke: #E57354 !important;
            stroke-width: 3.5px !important;
            stroke-linejoin: round;
            filter: drop-shadow(0 0 6px rgba(229, 115, 84, 0.75));
            cursor: grab;
        }

        .ident-canvas-svg .ident-drag-part:active {
            cursor: grabbing;
            fill: #FDE68A !important;
        }

        .ident-canvas-svg .ident-drag-part.is-selected {
            fill: #FED7AA !important;
            stroke: #EA580C !important;
            stroke-width: 4px !important;
            filter: drop-shadow(0 0 8px rgba(234, 88, 12, 0.8));
        }

        .ident-canvas-svg [id$="-p0"] {
            cursor: default !important;
            pointer-events: none !important;
        }

        .interactive-svg-part {
            cursor: grab;
            transition: fill 0.2s ease, stroke 0.2s ease, filter 0.2s ease;
        }

        .interactive-svg-part:hover {
            fill: #FEF3C7 !important;
            stroke: #E57354 !important;
            stroke-width: 3.5px !important;
            filter: drop-shadow(0 4px 6px rgba(229, 115, 84, 0.4));
        }

        .interactive-svg-part:active {
            cursor: grabbing;
            fill: #FDE68A !important;
        }

        .interactive-svg-part.is-selected {
            fill: #FED7AA !important;
            stroke: #EA580C !important;
            stroke-width: 4px !important;
        }

        .svg-part-group {
            cursor: grab;
        }

        .svg-part-group:active {
            cursor: grabbing;
        }

        /* ============================================================
               RESPONSIF KHUSUS TAMPILAN MOBILE (LAYAR KECIL / LANDSCAPE HP)
               DESKTOP TIDAK AKAN TERPENGARUH SAMA SEKALI
               ============================================================ */
        @media screen and (max-width: 1024px),
        screen and (max-height: 600px) {
            .kolab-stage-wrapper {
                position: absolute !important;
                top: 62px !important;
                bottom: 8px !important;
                left: 19% !important;
                right: 10px !important;
                width: auto !important;
                max-width: none !important;
                transform: none !important;
                display: flex !important;
                flex-direction: column !important;
                pointer-events: auto !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .kolab-card {
                position: relative !important;
                pointer-events: auto !important;
                display: block !important;
                width: 100% !important;
                height: 100% !important;
                max-height: 100% !important;
                min-height: 0 !important;
                overflow-y: auto !important;
                overflow-x: hidden !important;
                touch-action: pan-y !important;
                -webkit-overflow-scrolling: touch !important;
                overscroll-behavior: contain !important;
                border-width: 3.5px !important;
                border-radius: 22px !important;
                padding: 10px 12px 48px 12px !important;
                box-shadow: 0 16px 40px rgba(0, 0, 0, 0.22), 0 4px 14px rgba(217, 119, 6, 0.15) !important;
            }

            /* Mobile Stage 1 Grid: 2x2 Grid simetris, rapi, dan proporsional di layar mobile */
            #roomsGridContainer {
                display: grid !important;
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                gap: 8px !important;
                padding-top: 4px !important;
            }

            #roomsGridContainer>div {
                min-width: 0 !important;
                padding: 8px 10px !important;
                border-radius: 16px !important;
            }

            /* Sembunyikan tombol floating raksasa Stage 3 di mobile agar tidak menutupi tabel */
            .kolab-stage3-floating-btn {
                display: none !important;
            }

            /* Stage 3 Mobile Optimization: Tabel Pembagian Tugas langsung terlihat dan tidak terpotong */
            #kolabStage3 table {
                width: 100% !important;
            }

            #kolabStage3 th,
            #kolabStage3 td {
                padding: 5px 6px !important;
            }

            #kolabStage3 select {
                font-size: 11px !important;
                padding-top: 3px !important;
                padding-bottom: 3px !important;
                min-height: 28px !important;
            }

            /* Optimize SVG viewport height on mobile to prevent unnecessary vertical scroll */
            .canvas-svg-viewport svg {
                max-height: 230px !important;
                max-width: 200px !important;
            }

            /* Stage 4B Mobile: Bertumpuk Vertikal & Scrollable seperti Misi 1 */
            #kolabStage4B .kolab-4b-grid {
                display: grid !important;
                grid-template-columns: 1fr !important;
                gap: 10px !important;
                align-items: start !important;
                width: 100% !important;
                height: auto !important;
                min-height: 0 !important;
                flex: none !important;
            }

            #kolabStage4B .kolab-4b-grid>div {
                min-width: 0 !important;
            }

            #kolabStage4B .ident-canvas-box {
                display: flex !important;
                flex-direction: column !important;
                justify-content: flex-start !important;
                gap: 6px !important;
                padding: 8px 10px !important;
                height: auto !important;
                min-height: 0 !important;
            }

            #kolabStage4B .ident-canvas-header {
                margin-bottom: 0 !important;
                padding-bottom: 4px !important;
            }

            #kolabStage4B .ident-canvas-pagination {
                margin-top: 0 !important;
                padding-top: 4px !important;
                gap: 4px !important;
            }

            #kolabStage4B .ident-canvas-viewport {
                height: 240px !important;
                min-height: 240px !important;
                max-height: 280px !important;
                padding: 8px !important;
                overflow: auto !important;
                touch-action: pan-x pan-y !important;
                flex: none !important;
            }

            #kolabStage4B .ident-canvas-svg.hidden,
            #kolabStage4B .identCanvasSlide.hidden {
                display: none !important;
            }

            #kolabStage4B .ident-canvas-svg:not(.hidden) {
                width: 100% !important;
                min-height: 100% !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
            }

            #kolabStage4B .ident-canvas-svg svg {
                max-height: 220px !important;
                max-width: 240px !important;
                width: 100% !important;
                height: auto !important;
                display: block !important;
                margin: 0 auto !important;
            }

            #kolabStage4B .ident-table-box {
                display: flex !important;
                flex-direction: column !important;
                height: auto !important;
                min-height: 0 !important;
                flex: none !important;
            }

            #kolabStage4B .ident-table-wrapper {
                display: block !important;
                height: auto !important;
                max-height: none !important;
                min-height: 0 !important;
                flex: none !important;
                margin-bottom: 0 !important;
            }

            #kolabStage4B .ident-table-wrapper table {
                width: 100% !important;
                height: auto !important;
            }

            #kolabStage4B .ident-table-wrapper tbody {
                display: table-row-group !important;
                height: auto !important;
            }

            #kolabStage4B .ident-table-wrapper tbody tr {
                height: auto !important;
            }

            #kolabStage4B table th,
            #kolabStage4B table td {
                padding: 5px 6px !important;
            }

            /* Mobile Misi 3 (Stage 6): Seluruh panel yang discroll, tabel tampil natural tanpa terpotong */
            #kolabStage6 .eval-table-wrapper {
                max-height: none !important;
                overflow-y: visible !important;
                height: auto !important;
                flex-shrink: 0 !important;
            }

            #kolabStage6 .kolab-card {
                overflow-y: auto !important;
                -webkit-overflow-scrolling: touch !important;
                display: block !important;
            }
        }

        @media screen and (min-width: 640px) and (max-width: 1024px) and (min-height: 601px) {
            .kolab-stage-wrapper {
                top: 78px !important;
            }
        }

        /* ============================================================
               RESPONSIF KHUSUS TAMPILAN DESKTOP
               Kanvas di KIRI (BESAR & TINGGI), Tabel di KANAN (RAPI & PROPORSIONAL)
               ============================================================ */
        @media screen and (min-width: 1025px) {
            .kolab-stage-wrapper {
                position: absolute !important;
                top: 96px !important;
                bottom: 76px !important;
                left: 18% !important;
                right: 24px !important;
                width: auto !important;
                max-width: 1350px !important;
                transform: none !important;
                display: flex !important;
                flex-direction: column !important;
                align-items: stretch !important;
                justify-content: flex-start !important;
                pointer-events: auto !important;
            }

            .kolab-card {
                width: 100% !important;
                height: auto !important;
                max-height: 100% !important;
                min-height: 0 !important;
                overflow-y: auto !important;
                overflow-x: hidden !important;
            }

            #kolabStage4B .kolab-card {
                display: block !important;
                height: auto !important;
                max-height: 100% !important;
                padding: 14px 18px !important;
                overflow-y: auto !important;
            }

            #kolabStage4B .kolab-4b-grid {
                display: grid !important;
                grid-template-columns: minmax(0, 4.1fr) minmax(0, 5.9fr) !important;
                gap: 14px !important;
                align-items: stretch !important;
                height: auto !important;
                min-height: 0 !important;
                flex: none !important;
            }

            #kolabStage4B .kolab-4b-grid>div {
                grid-column: auto !important;
                min-width: 0 !important;
            }

            #kolabStage4B .ident-canvas-box {
                height: 100% !important;
                min-height: 0 !important;
                padding: 10px 12px !important;
                display: flex !important;
                flex-direction: column !important;
                justify-content: space-between !important;
                gap: 8px !important;
            }

            #kolabStage4B .ident-canvas-viewport {
                height: 380px !important;
                min-height: 340px !important;
                max-height: 440px !important;
                padding: 10px !important;
                overflow: auto !important;
                flex: 1 1 auto !important;
            }

            #kolabStage4B .ident-canvas-svg svg {
                max-height: 355px !important;
                max-width: 325px !important;
                width: 100% !important;
                height: auto !important;
            }

            #kolabStage4B .ident-table-box {
                height: 100% !important;
                display: flex !important;
                flex-direction: column !important;
                flex: none !important;
            }

            #kolabStage4B .ident-table-wrapper {
                height: 100% !important;
                max-height: none !important;
                display: flex !important;
                flex-direction: column !important;
                flex: 1 1 auto !important;
            }

            #kolabStage4B .ident-table-wrapper table {
                height: 100% !important;
                width: 100% !important;
                table-layout: fixed !important;
            }

            #kolabStage4B .ident-table-wrapper tbody tr {
                height: calc(100% / 6) !important;
            }
        }

        /* Custom In-DOM Dropdowns (ikut miring saat rotasi mobile landscape) */
        .custom-task-dropdown,
        .custom-shape-dropdown,
        .custom-eval-dropdown {
            position: relative;
            user-select: none;
        }

        .custom-task-dropdown.is-open,
        .custom-shape-dropdown.is-open,
        .custom-eval-dropdown.is-open {
            z-index: 60 !important;
        }

        #tbodyTasksList tr,
        #tbodyIdentificationForm tr,
        #tbodyEvaluationList tr {
            position: relative;
        }

        .task-role-menu,
        .shape-menu,
        .eval-menu,
        .eval-peer-menu {
            box-shadow: 0 10px 25px -5px rgba(138, 68, 22, 0.25), 0 8px 10px -6px rgba(138, 68, 22, 0.2);
        }

        /* Floating Drag Avatar (ikut miring saat rotasi mobile landscape) */
        #floatingShapeDragAvatar {
            position: fixed !important;
            z-index: 999999 !important;
            pointer-events: none !important;
            transform: translate(-50%, -50%) scale(1.1);
            transform-origin: center center;
            transition: none !important;
            will-change: left, top, transform;
            touch-action: none !important;
        }

        @media screen and (max-width: 1024px) and (orientation: portrait) {
            #floatingShapeDragAvatar {
                transform: translate(-50%, -50%) rotate(90deg) scale(1.1) !important;
            }
        }

        #floatingShapeDragAvatar.mobile-force-rotated {
            transform: translate(-50%, -50%) rotate(90deg) scale(1.1) !important;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-[100dvh] h-[100dvh] overflow-hidden font-['Jua'] select-none">
    @php
        $bg = asset('assets/berandamalam.webp');
        $shapesMaster = $bangunDatarShapes ?? collect();
        $shapesMasterJson = $shapesMaster->map(fn ($s) => [
            'key' => $s->shape_key,
            'name' => $s->name,
            'type' => $s->type,
            'subtype' => $s->subtype,
            'color' => $s->color,
            'ciri_ciri' => $s->ciri_ciri ?? [],
            'svg' => $s->svg_inline,
            'custom_image' => $s->custom_image_path ? asset($s->custom_image_path) : null,
        ])->values();

        $svgLoader = fn(string $file) => \Illuminate\Support\Facades\Cache::remember(
            "svg_{$file}",
            3600,
            fn() => file_exists(public_path("assets/{$file}")) ? file_get_contents(public_path("assets/{$file}")) : ''
        );
        $canvas1Svg = $svgLoader('canvas1a.svg');
        $canvas2Svg = $svgLoader('canvas2a.svg');
        $canvas3Svg = $svgLoader('frame3.svg');
        $canvas4Svg = $svgLoader('frame4.svg');

        $namespaceSvg = static function (string $svg, string $prefix, string $partClass, string $extraAttr = ''): string {
            $svg = str_replace(
                ['id="c1-', 'id="c2-', 'id="c3-', 'id="c4-', 'class="colorable-part"'],
                ["id=\"{$prefix}c1-", "id=\"{$prefix}c2-", "id=\"{$prefix}c3-", "id=\"{$prefix}c4-", "class=\"{$partClass}\"{$extraAttr}"],
                $svg
            );

            return str_replace(
                ['id="clip0_', 'url(#clip0_', 'id="path-', 'url(#path-'],
                ["id=\"{$prefix}clip0_", "url(#{$prefix}clip0_", "id=\"{$prefix}path-", "url(#{$prefix}path-"],
                $svg
            );
        };

        $reviewCanvas1 = $namespaceSvg($canvas1Svg, 'review-', 'review-part');
        $reviewCanvas2 = $namespaceSvg($canvas2Svg, 'review-', 'review-part');
        $reviewCanvas3 = $namespaceSvg($canvas3Svg, 'review-', 'review-part');
        $reviewCanvas4 = $namespaceSvg($canvas4Svg, 'review-', 'review-part');

        $identCanvas1 = $namespaceSvg($canvas1Svg, 'ident-', 'ident-drag-part', ' data-canvas="1"');
        $identCanvas2 = $namespaceSvg($canvas2Svg, 'ident-', 'ident-drag-part', ' data-canvas="2"');
        $identCanvas3 = $namespaceSvg($canvas3Svg, 'ident-', 'ident-drag-part', ' data-canvas="3"');
        $identCanvas4 = $namespaceSvg($canvas4Svg, 'ident-', 'ident-drag-part', ' data-canvas="4"');
    @endphp

    <div class="landscape-force">
        <div data-app-root data-content
            style="background-image: url('{{ $bg }}'); background-size: cover; background-position: center;">

            <!-- ============================================================
                     1. TOP BAR KIRI: TOMBOL HOME (BERANDA)
                     ============================================================ -->
            <div class="absolute top-3 left-3 sm:top-5 sm:left-5 z-40">
                <x-btn-home href="{{ route('menu') }}" title="Kembali ke Beranda" />
            </div>

            <!-- ============================================================
                     2. TOP BAR KANAN: REFRESH, PENGATURAN SUARA & EXIT
                     ============================================================ -->
            <div class="absolute top-3 right-3 sm:top-5 sm:right-5 z-40 flex items-center gap-2 sm:gap-3">
                <x-btn-refresh onclick="handleKolabRefresh()" title="Muat Ulang Data (Tetap di Halaman Ini)" />
                <x-btn-settings onclick="openSettingsModal()" title="Pengaturan Suara" />
                <x-btn-exit onclick="handleExitWeb()" title="Keluar" />
            </div>

            <!-- ============================================================
                     3. KARAKTER YUK DAMAR DI SISI KIRI (100% OPASITAS PENUH)
                     ============================================================ -->
            <div id="karakterYukDamar"
                class="absolute z-20 pointer-events-none bottom-0 left-0 sm:left-2 md:left-4 lg:left-6 h-[88%] sm:h-[94%] md:h-[98%] max-h-[760px] flex items-end translate-y-[20%] select-none">
                <img src="{{ asset('assets/karakter_menyapa.webp') }}" alt="Yuk Damar Menyapa"
                    class="h-full w-auto max-h-full object-contain object-bottom drop-shadow-[0_18px_32px_rgba(0,0,0,0.4)] select-none pointer-events-none opacity-100">
            </div>

            <!-- ============================================================
                     STAGE 1: PILIH RUANG KOLABORASI (ONLINE KUOTA MAKS 5)
                     ============================================================ -->
            <div id="kolabStage1"
                class="kolabStage absolute inset-0 z-30 pointer-events-auto transition-opacity duration-300">
                <div
                    class="kolab-stage-wrapper absolute top-[50px] sm:top-[56px] bottom-2 sm:bottom-3 left-[23%] sm:left-[24%] md:left-[25%] right-2 sm:right-3 md:right-4 lg:top-[52px] lg:bottom-3.5 lg:left-[18%] lg:right-6 lg:transform-none lg:w-auto lg:max-w-[1350px] z-30 flex items-center justify-start pointer-events-auto w-auto">
                    <div
                        class="kolab-card relative w-full h-full lg:h-full lg:max-h-full overflow-y-auto bg-[#FFFDF5] border-4 border-[#E9C37A] rounded-[24px] sm:rounded-[34px] md:rounded-[42px] p-3 sm:p-5 md:p-6 shadow-[0_22px_50px_rgba(0,0,0,0.25),0_4px_12px_rgba(233,195,122,0.3)]">

                        <!-- Ekor Balon Chat -->
                        <div
                            class="absolute -left-[20px] sm:-left-[26px] top-10 sm:top-14 -translate-y-1/2 w-0 h-0 border-t-[12px] sm:border-t-[16px] border-t-transparent border-r-[20px] sm:border-r-[26px] border-r-[#E9C37A] border-b-[12px] sm:border-b-[16px] border-b-transparent">
                        </div>
                        <div
                            class="absolute -left-[14px] sm:-left-[18px] top-10 sm:top-14 -translate-y-1/2 w-0 h-0 border-t-[12px] sm:border-t-[16px] border-t-transparent border-r-[20px] sm:border-r-[26px] border-r-[#FFFDF5] border-b-[12px] sm:border-b-[16px] border-b-transparent">
                        </div>

                        <!-- Header Bubble Narasi Yuk Damar -->
                        <div class="mb-2 sm:mb-3 shrink-0">
                            <div class="flex items-center justify-between gap-2 flex-wrap mb-1">
                                <div
                                    class="inline-flex items-center gap-1.5 sm:gap-2 bg-[#FFF2D6] px-2.5 sm:px-3.5 py-0.5 sm:py-1 rounded-full border border-[#E9C37A] shadow-2xs">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-[#8A4416]" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <span class="text-[11px] sm:text-xs md:text-sm font-bold text-[#8A4416]">Pilihan Ruang
                                        Kolaborasi</span>
                                </div>
                                <span id="activeSessionBadge"
                                    class="inline-flex items-center gap-1.5 px-2.5 sm:px-3 py-0.5 sm:py-1 rounded-full bg-emerald-50 border border-emerald-300 text-emerald-800 text-[10px] sm:text-xs font-bold shadow-2xs">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span id="activeSessionBadgeText">Sesi: {{ $activeSession->displayLabel() }}</span>
                                </span>
                            </div>
                            <p
                                class="text-[11px] sm:text-xs md:text-sm lg:text-base text-[#3D1804] font-normal leading-snug sm:leading-relaxed text-left select-none tracking-wide">
                                “Selamat datang pada menu tim kolaborasi! Silakan pilih salah satu dari <strong
                                    class="text-[#E57354]">8 ruang kelompok</strong> di bawah ini untuk berkolaborasi
                                bersama <strong class="text-[#8A4416]">hingga 5 anggota</strong> kelompokmu!”
                            </p>
                        </div>

                        <!-- Grid 8 Ruang Kolaborasi Tetap (2x4 di Mobile, 4 Kolom di Desktop) -->
                        @php
                            $roomThemeStyles = [
                                0 => ['border' => 'border-[#E57354]', 'badge' => 'from-[#E57354] to-[#C94F32]', 'text' => 'text-[#E57354]', 'btn' => 'bg-[#E57354] hover:bg-[#D95F3F]', 'bgLight' => 'bg-[#FFF2EE]'],
                                1 => ['border' => 'border-[#548466]', 'badge' => 'from-[#548466] to-[#3D694D]', 'text' => 'text-[#548466]', 'btn' => 'bg-[#548466] hover:bg-[#3D694D]', 'bgLight' => 'bg-[#EFF6F2]'],
                                2 => ['border' => 'border-[#ECA144]', 'badge' => 'from-[#ECA144] to-[#D4892A]', 'text' => 'text-[#ECA144]', 'btn' => 'bg-[#ECA144] hover:bg-[#D4892A]', 'bgLight' => 'bg-[#FFF8EE]'],
                                3 => ['border' => 'border-[#8B5CF6]', 'badge' => 'from-[#8B5CF6] to-[#6D28D9]', 'text' => 'text-[#8B5CF6]', 'btn' => 'bg-[#8B5CF6] hover:bg-[#7C3AED]', 'bgLight' => 'bg-[#F5F3FF]'],
                                4 => ['border' => 'border-[#0284C7]', 'badge' => 'from-[#0284C7] to-[#0369A1]', 'text' => 'text-[#0284C7]', 'btn' => 'bg-[#0284C7] hover:bg-[#0369A1]', 'bgLight' => 'bg-[#F0F9FF]'],
                                5 => ['border' => 'border-[#E11D48]', 'badge' => 'from-[#E11D48] to-[#BE123C]', 'text' => 'text-[#E11D48]', 'btn' => 'bg-[#E11D48] hover:bg-[#BE123C]', 'bgLight' => 'bg-[#FFF1F2]'],
                                6 => ['border' => 'border-[#0D9488]', 'badge' => 'from-[#0D9488] to-[#0F766E]', 'text' => 'text-[#0D9488]', 'btn' => 'bg-[#0D9488] hover:bg-[#0F766E]', 'bgLight' => 'bg-[#F0FDFA]'],
                                7 => ['border' => 'border-[#4F46E5]', 'badge' => 'from-[#4F46E5] to-[#4338CA]', 'text' => 'text-[#4F46E5]', 'btn' => 'bg-[#4F46E5] hover:bg-[#4338CA]', 'bgLight' => 'bg-[#EEF2FF]'],
                            ];
                        @endphp
                        <div id="roomsGridContainer"
                            class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-2.5 md:gap-3 lg:gap-3.5 pt-0.5 flex-1 items-stretch">
                            @foreach($rooms->take(8) as $idx => $room)
                                @php
                                    $num = $room->room_number ?? ($idx + 1);
                                    $c = $roomThemeStyles[$idx % count($roomThemeStyles)];
                                    $memberCount = $room->members ? $room->members->count() : 0;
                                    $isCompleted = $room->status === 'completed';
                                    $isFull = $memberCount >= ($room->max_members ?? 5);
                                    $isDisabled = $isCompleted || $isFull;
                                    $percent = $isCompleted ? 100 : min(100, round(($memberCount / ($room->max_members ?? 5)) * 100));
                                @endphp
                                <div id="roomCard-{{ $num }}"
                                    class="group bg-white rounded-xl sm:rounded-2xl border-2 sm:border-[2.5px] {{ $c['border'] }} p-2 sm:p-2.5 md:p-3 flex flex-col justify-between shadow-[0_4px_12px_rgba(0,0,0,0.04)] hover:shadow-[0_8px_20px_rgba(0,0,0,0.1)] transition-all min-w-0">
                                    <!-- Header: Badge Nomor + Nama & Tema -->
                                    <div>
                                        <div class="flex items-center gap-2 sm:gap-2.5">
                                            <div
                                                class="w-7 h-7 sm:w-8 sm:h-8 md:w-9 md:h-9 rounded-lg sm:rounded-xl bg-gradient-to-br {{ $c['badge'] }} text-white font-black text-xs sm:text-sm md:text-base flex items-center justify-center shrink-0 shadow-xs">
                                                {{ $num }}
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <h4 class="font-bold text-[11px] sm:text-xs md:text-sm text-[#3D1804] truncate leading-tight"
                                                    title="{{ $room->name }}">
                                                    {{ $room->name }}
                                                </h4>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Footer: Kuota, Bar, & Tombol Aksi -->
                                    <div
                                        class="mt-1.5 sm:mt-2 pt-1.5 border-t border-[#E9C37A]/35 space-y-1 sm:space-y-1.5">
                                        <div
                                            class="flex items-center justify-between text-[9px] sm:text-[10px] md:text-[11px] font-semibold text-[#6D4C33] leading-none">
                                            <span class="flex items-center gap-1 text-[#8A4416]">
                                                <svg class="w-3 h-3 text-[#8A4416]/80 shrink-0" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                <span>{{ $isCompleted ? 'Status' : 'Kuota' }}</span>
                                            </span>
                                            <span
                                                class="{{ $isCompleted ? 'text-emerald-800 bg-emerald-100 border-emerald-300' : ($isFull ? 'text-[#DC2626] bg-red-50 border-red-200' : 'text-[#15803D] bg-emerald-50 border-emerald-200') }} font-bold text-[9px] sm:text-[10px] px-1.5 py-0.5 rounded border shrink-0">
                                                @if ($isCompleted)
                                                    Sudah Selesai
                                                @else
                                                    {{ $memberCount }}/{{ $room->max_members ?? 5 }}
                                                    {{ $isFull ? 'Penuh' : 'Siswa' }}
                                                @endif
                                            </span>
                                        </div>

                                        <div class="w-full h-1.5 rounded-full bg-[#E5E7EB] overflow-hidden">
                                            <div class="h-full {{ $isCompleted ? 'bg-emerald-500' : ($isFull ? 'bg-[#DC2626]' : 'bg-[#22C55E]') }} rounded-full transition-all duration-300"
                                                style="width: {{ $percent }}%;"></div>
                                        </div>

                                        <button type="button" onclick="{{ $isDisabled ? '' : 'joinRoomOnline(' . $num . ')' }}"
                                            class="w-full py-1.5 sm:py-2 px-2 rounded-xl {{ $isCompleted ? 'bg-emerald-600/85 text-white cursor-not-allowed' : ($isFull ? 'bg-[#9CA3AF] text-white cursor-not-allowed opacity-80' : $c['btn'] . ' text-white cursor-pointer hover:shadow-sm active:scale-95') }} font-bold text-[10px] sm:text-xs shadow-xs transition-all min-h-[30px] sm:min-h-[34px] flex items-center justify-center gap-1 truncate">
                                            <span>{{ $isCompleted ? 'Sudah Selesai' : ($isFull ? 'Ruang Penuh' : 'Masuk Ruang') }}</span>
                                            @if(!$isDisabled)
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                </svg>
                                            @endif
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div id="warningPenuhToast"
                            class="hidden mt-2 p-2 bg-[#FEE2E2] border-2 border-[#EF4444] rounded-xl text-center text-xs text-[#991B1B] font-bold shadow-sm shrink-0">
                            Maaf, Ruang tersebut sudah penuh (maksimal 5 siswa). Silakan pilih ruang lain!
                        </div>

                    </div>
                </div>

                <div class="absolute bottom-3 left-3 sm:bottom-5 sm:left-5 z-40">
                    <x-btn-back href="{{ route('menu') }}" title="Kembali ke Beranda" />
                </div>
            </div>

            <!-- ============================================================
                     SLIDE 1 (STAGE 3): AYO PERSIAPKAN - TABEL 1. IDENTITAS TIM KOLABORASI & ATURAN MISI
                     ============================================================ -->
            <div id="kolabStage3"
                class="kolabStage hidden absolute inset-0 z-30 pointer-events-auto transition-opacity duration-300">
                <div
                    class="kolab-stage-wrapper absolute top-[50px] sm:top-[56px] bottom-2 sm:bottom-3 left-[23%] sm:left-[24%] md:left-[25%] right-2 sm:right-3 md:right-4 lg:top-[52px] lg:bottom-3.5 lg:left-[18%] lg:right-6 lg:transform-none lg:w-auto lg:max-w-[1350px] z-30 flex items-center justify-start pointer-events-auto w-auto">
                    <div
                        class="kolab-card relative w-full h-full lg:h-full lg:max-h-full overflow-y-auto bg-[#FFFDF5] border-4 border-[#E9C37A] rounded-[24px] sm:rounded-[34px] md:rounded-[42px] p-3 sm:p-5 md:p-6 shadow-[0_22px_50px_rgba(0,0,0,0.25),0_4px_12px_rgba(233,195,122,0.3)]">

                        <!-- Ekor Balon Chat -->
                        <div
                            class="absolute -left-[20px] sm:-left-[26px] top-10 sm:top-14 -translate-y-1/2 w-0 h-0 border-t-[12px] sm:border-t-[16px] border-t-transparent border-r-[20px] sm:border-r-[26px] border-r-[#E9C37A] border-b-[12px] sm:border-b-[16px] border-b-transparent">
                        </div>
                        <div
                            class="absolute -left-[14px] sm:-left-[18px] top-10 sm:top-14 -translate-y-1/2 w-0 h-0 border-t-[12px] sm:border-t-[16px] border-t-transparent border-r-[20px] sm:border-r-[26px] border-r-[#FFFDF5] border-b-[12px] sm:border-b-[16px] border-b-transparent">
                        </div>

                        <!-- Header Ruang & Status Online -->
                        <div
                            class="flex items-center justify-between pb-1.5 sm:pb-2.5 border-b border-[#E9C37A]/60 mb-2 sm:mb-2.5 flex-wrap gap-1.5 sm:gap-2 shrink-0">
                            <div class="flex items-center gap-1.5 sm:gap-2 flex-wrap">
                                <span class="roomNameBadge font-bold text-xs sm:text-base text-[#8A4416]">Ruang
                                    Kolaborasi</span>
                                <span id="onlineMembersBadge2"
                                    class="inline-flex items-center gap-1 text-[10px] sm:text-[11px] bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-full font-bold border border-emerald-300">
                                    <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-emerald-500"></span>
                                    <span>Online: 1 Siswa</span>
                                </span>
                            </div>
                            <button type="button" onclick="leaveRoomOnline()"
                                class="text-[10px] sm:text-xs text-[#DC2626] bg-[#FEE2E2] hover:bg-[#FECACA] border border-[#FCA5A5] px-2.5 py-0.5 sm:py-1 rounded-full font-bold transition-all cursor-pointer flex items-center gap-1 shadow-2xs hover:scale-105 active:scale-95">
                                <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span>Keluar Ruang</span>
                            </button>
                        </div>

                        <!-- Judul Slide: Ayo Persiapkan - Tabel 1. Identitas Tim Kolaborasi -->
                        <div class="mb-2 shrink-0 flex items-center justify-between gap-2 flex-wrap">
                            <div
                                class="inline-flex items-center gap-1.5 bg-[#FFF2D6] px-3 py-1 rounded-full border border-[#E9C37A] shadow-2xs">
                                <svg class="w-4 h-4 text-[#8A4416]" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <span class="text-xs sm:text-sm font-bold text-[#8A4416]">Ayo Persiapkan &bull; Tabel 1.
                                    Identitas Tim Kolaborasi</span>
                            </div>
                            <span
                                class="text-[10px] sm:text-[11px] text-[#8A4416] font-semibold bg-[#FFF8E7] px-2.5 py-0.5 rounded-full border border-[#E9C37A]/70">
                                Mengerjakan Bareng-Bareng 1 Tim
                            </span>
                        </div>

                        <!-- Input Nama Kelompok & Ketua Kelompok -->
                        <div
                            class="grid grid-cols-2 gap-1.5 sm:gap-2 mb-2 p-2 sm:p-2.5 bg-white rounded-xl sm:rounded-2xl border border-[#E9C37A]/60 shadow-2xs shrink-0">
                            <div>
                                <label class="block text-[10px] sm:text-[11px] font-bold text-[#6D4C33] mb-0.5">Nama
                                    Kelompok:</label>
                                <input id="inputNamaKelompok" type="text" oninput="autoSaveRoomData()"
                                    placeholder="Tuliskan nama kelompok..."
                                    class="w-full h-7 sm:h-8 rounded-lg bg-[#FFFDF5] border border-[#E9C37A] px-2.5 text-[11px] sm:text-xs outline-none font-bold text-[#8A4416] focus:border-[#E57354]">
                            </div>
                            <div>
                                <label class="block text-[10px] sm:text-[11px] font-bold text-[#6D4C33] mb-0.5">Ketua
                                    Kelompok:</label>
                                <input id="inputKetuaTim" type="text" oninput="autoSaveRoomData()"
                                    placeholder="Tuliskan nama ketua..."
                                    class="w-full h-7 sm:h-8 rounded-lg bg-[#FFFDF5] border border-[#E9C37A] px-2.5 text-[11px] sm:text-xs outline-none font-bold text-[#8A4416] focus:border-[#E57354]">
                            </div>
                        </div>

                        <!-- Tabel 1: Identitas Tim Kolaborasi -->
                        <div
                            class="overflow-x-auto rounded-xl sm:rounded-2xl border-2 border-[#E9C37A] shadow-xs bg-white shrink-0 mb-2.5 max-h-[175px] sm:max-h-[210px] overflow-y-auto">
                            <table class="w-full text-left text-xs">
                                <thead class="sticky top-0 z-10">
                                    <tr class="bg-gradient-to-r from-[#8A4416] to-[#6E330C] text-white">
                                        <th class="p-1.5 sm:p-2 text-center w-10 border-r border-white/20 font-bold">No
                                        </th>
                                        <th class="p-1.5 sm:p-2 border-r border-white/20 font-bold">Nama Anggota</th>
                                        <th class="p-1.5 sm:p-2 text-center w-24 border-r border-white/20 font-bold">No.
                                            Absen</th>
                                        <th class="p-1.5 sm:p-2 text-center w-36 font-bold">Status Kolaborasi</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyTasksList" class="divide-y divide-[#E9C37A]/30">
                                    @php
                                        $renderedMembers = isset($activeRoomMembers) ? $activeRoomMembers : collect();
                                    @endphp
                                    @for($i = 0; $i < 5; $i++)
                                        @php
                                            $m = $renderedMembers->get($i);
                                            $isMe = $m && $currentUser && ($m->user_id == $currentUser->id);
                                        @endphp
                                        @if($m)
                                            <tr
                                                class="hover:bg-[#FFF8E7] transition-colors {{ $isMe ? 'bg-[#FFEBE6]/50 font-bold' : '' }}">
                                                <td
                                                    class="p-1.5 sm:p-2 text-center font-bold text-[#8A4416] text-[11px] sm:text-xs">
                                                    {{ $i + 1 }}
                                                </td>
                                                <td class="p-1.5 sm:p-2">
                                                    <div class="flex items-center gap-1 sm:gap-1.5 flex-wrap">
                                                        <span
                                                            class="text-[11px] sm:text-xs text-[#3D1804] font-bold">{{ $m->user_name }}</span>
                                                        @if($isMe)
                                                            <span
                                                                class="text-[9px] bg-[#E57354] text-white px-1.5 py-0.2 rounded-full font-bold">Saya</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td
                                                    class="p-1.5 sm:p-2 text-center text-[#8A4416] font-bold text-[11px] sm:text-xs">
                                                    {{ $m->no_absen ?? '-' }}
                                                </td>
                                                <td class="p-1.5 sm:p-2 text-center">
                                                    <span
                                                        class="inline-flex items-center gap-1 text-[10px] sm:text-[11px] bg-emerald-100 text-emerald-800 px-2.5 py-0.5 rounded-full font-bold border border-emerald-300 shadow-2xs">
                                                        <span
                                                            class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                                        <span>Siap Berkolaborasi</span>
                                                    </span>
                                                </td>
                                            </tr>
                                        @else
                                            <tr class="bg-stone-50/60 opacity-60">
                                                <td
                                                    class="p-1.5 sm:p-2 text-center font-bold text-stone-400 text-[11px] sm:text-xs">
                                                    {{ $i + 1 }}
                                                </td>
                                                <td class="p-1.5 sm:p-2 text-[11px] sm:text-xs text-stone-400 italic">Menunggu
                                                    teman...</td>
                                                <td class="p-1.5 sm:p-2 text-center text-xs text-stone-400">-</td>
                                                <td class="p-1.5 sm:p-2 text-center text-[10px] text-stone-400 italic">Slot
                                                    kosong</td>
                                            </tr>
                                        @endif
                                    @endfor
                                </tbody>
                            </table>
                        </div>

                        <!-- Petunjuk dari Yuk Damar & Aturan Misi (Di Bawah Tabel Sesuai Permintaan) -->
                        <div
                            class="p-3 sm:p-4 bg-gradient-to-br from-[#FFF9EA] to-[#FFF2D6] rounded-2xl border-2 border-[#E9C37A] shadow-xs shrink-0 mb-2.5 text-left">
                            <!-- Ucapan Yuk Damar -->
                            <div class="flex items-start gap-2.5 mb-2.5">
                                <div
                                    class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-[#E57354] text-white flex items-center justify-center font-bold text-xs shadow-xs shrink-0 mt-0.5">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                </div>
                                <div>
                                    <div
                                        class="text-[10px] sm:text-[11px] font-bold text-[#E57354] uppercase tracking-wide">
                                        Yuk Damar Berkata:</div>
                                    <p class="text-xs sm:text-sm font-bold text-[#3D1804] leading-snug">
                                        “Hai, selamat datang di ruang kolaborasi kelompok! Sebelum memulai, baca aturan
                                        misi ini dengan cermat ya!”
                                    </p>
                                </div>
                            </div>

                            <!-- Kotak Aturan Misi a - f -->
                            <div
                                class="bg-white/95 backdrop-blur-xs rounded-xl p-2.5 sm:p-3 border border-[#E9C37A]/80 shadow-2xs">
                                <div
                                    class="text-xs sm:text-sm font-black text-[#8A4416] mb-1.5 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-[#E57354]" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <span>Aturan Misi:</span>
                                </div>
                                <ol
                                    class="list-[lower-alpha] pl-5 space-y-1 text-[11px] sm:text-xs text-[#3D1804] leading-relaxed font-medium">
                                    <li>Lihatlah masing-masing teman anggota kelompokmu pada tabel di atas!</li>
                                    <li>Dalam ruang ini, kalian akan berkolaborasi menyelesaikan 3 misi utama, yaitu
                                        mewarnai motif damar kurung, menemukan bentuk bangun datar dalam motif, serta
                                        melakukan evaluasi dan apresiasi hasil kerja kolaborasi.</li>
                                    <li>Selesaikan misi 1 dengan mewarnai seluruh sisi motif damar kurung sekreatif
                                        mungkin!</li>
                                    <li>Lanjutkan misi 2 untuk menemukan bentuk bangun datar berdasarkan ciri-cirinya
                                        pada gambar objek-objek yang ada di dalam motif tersebut. Masukkan temuan kalian
                                        pada tabel yang tersedia!</li>
                                    <li>Setelah itu, selesaikan misi 3 dengan cara berdiskusi untuk memberikan evaluasi
                                        dan apresiasi atas hasil kerja tim yang telah dilakukan pada tabel yang
                                        tersedia.</li>
                                    <li>Jangan lupa berdoa dan berkolaborasi dengan baik ya!</li>
                                </ol>
                            </div>
                        </div>

                        <!-- Bottom CTA Bar -->
                        <div
                            class="flex flex-col sm:flex-row items-center justify-between gap-2 p-2.5 sm:p-3 bg-[#FFF2D6] rounded-xl sm:rounded-2xl border-2 border-[#E9C37A] shrink-0">
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-[#E57354] text-white flex items-center justify-center font-bold text-xs shadow-xs shrink-0">
                                    !
                                </div>
                                <p class="text-[10px] sm:text-xs text-[#8A4416] font-bold text-left">
                                    Pastikan seluruh anggota sudah membaca petunjuk misi di atas, lalu klik tombol untuk
                                    menuju Misi 1!
                                </p>
                            </div>
                            <button type="button" onclick="goToKolabStage('4A')"
                                class="w-full sm:w-auto px-5 sm:px-6 py-2 rounded-full border-2 border-white/90 bg-gradient-to-b from-[#4ADE80] via-[#22C55E] to-[#15803D] text-white font-bold text-xs sm:text-sm tracking-wide shadow-[0_3px_0_#0F5128] hover:brightness-110 active:translate-y-[2px] active:shadow-none transition-all cursor-pointer flex items-center justify-center gap-1.5 whitespace-nowrap">
                                <span>LANJUT KE MISI 1 (MEWARNAI)</span>
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </button>
                        </div>

                    </div>
                </div>

                <!-- Tombol Navigasi Bawah Stage 3 -->
                <div class="absolute bottom-4 left-4 sm:bottom-6 sm:left-6 z-40">
                    <x-btn-back onclick="leaveRoomOnline()" title="Keluar Ruang" />
                </div>
                <div class="absolute bottom-4 right-4 sm:bottom-6 sm:right-6 z-40">
                    <x-btn-next onclick="goToKolabStage('4A')" title="Lanjut ke Misi 1 (Mewarnai)" />
                </div>
            </div>

            <!-- ============================================================
                     SLIDE 2 (STAGE 4A): MISI 1 - MEWARNAI SELURUH SISI MOTIF DAMAR KURUNG
                     ============================================================ -->
            <div id="kolabStage4A"
                class="kolabStage hidden absolute inset-0 z-30 pointer-events-auto transition-opacity duration-300">
                <div
                    class="kolab-stage-wrapper absolute top-[50px] sm:top-[56px] bottom-2 sm:bottom-3 left-[23%] sm:left-[24%] md:left-[25%] right-2 sm:right-3 md:right-4 lg:top-[52px] lg:bottom-3.5 lg:left-[18%] lg:right-6 lg:transform-none lg:w-auto lg:max-w-[1350px] z-30 flex items-center justify-start pointer-events-auto w-auto">
                    <div
                        class="kolab-card relative w-full h-full lg:h-full lg:max-h-full overflow-y-auto bg-[#FFFDF5] border-4 border-[#E9C37A] rounded-[24px] sm:rounded-[34px] md:rounded-[42px] p-3 sm:p-5 md:p-6 shadow-[0_22px_50px_rgba(0,0,0,0.25),0_4px_12px_rgba(233,195,122,0.3)]">

                        <!-- Ekor Balon Chat -->
                        <div
                            class="absolute -left-[20px] sm:-left-[26px] top-10 sm:top-14 -translate-y-1/2 w-0 h-0 border-t-[12px] sm:border-t-[16px] border-t-transparent border-r-[20px] sm:border-r-[26px] border-r-[#E9C37A] border-b-[12px] sm:border-b-[16px] border-b-transparent">
                        </div>
                        <div
                            class="absolute -left-[14px] sm:-left-[18px] top-10 sm:top-14 -translate-y-1/2 w-0 h-0 border-t-[12px] sm:border-t-[16px] border-t-transparent border-r-[20px] sm:border-r-[26px] border-r-[#FFFDF5] border-b-[12px] sm:border-b-[16px] border-b-transparent">
                        </div>

                        <!-- Header Ruang & Tombol Keluar -->
                        <div
                            class="flex items-center justify-between pb-2 border-b border-[#E9C37A]/50 mb-3 flex-wrap gap-2">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="roomNameBadge font-bold text-xs sm:text-sm text-[#8A4416]">Ruang
                                    Kolaborasi</span>
                                <span
                                    class="inline-flex items-center gap-1 text-[10px] sm:text-[11px] bg-emerald-100 text-emerald-800 px-2.5 py-0.5 rounded-full font-bold border border-emerald-300 shadow-2xs">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span>Live Kanvas Bersama</span>
                                </span>
                            </div>
                            <button type="button" onclick="leaveRoomOnline()"
                                class="text-xs text-[#DC2626] bg-[#FEE2E2] hover:bg-[#FECACA] border border-[#FCA5A5] px-3 py-1 rounded-full font-bold transition-all cursor-pointer flex items-center gap-1 shadow-2xs hover:scale-105 active:scale-95">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span>Keluar Ruang</span>
                            </button>
                        </div>

                        <div class="mb-3">
                            <div
                                class="inline-flex items-center gap-2 bg-[#FFEBE6] px-3.5 py-1 rounded-full border border-[#E57354] mb-1.5 shadow-2xs">
                                <svg class="w-4 h-4 text-[#E57354]" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                                </svg>
                                <span class="text-xs sm:text-sm font-bold text-[#E57354]">Misi 1: Mewarnai Seluruh Sisi
                                    Motif Damar Kurung</span>
                            </div>
                            <p
                                class="text-xs sm:text-sm md:text-base text-[#3D1804] font-normal leading-relaxed text-left select-none tracking-wide">
                                “Selesaikan misi 1 dengan mewarnai seluruh sisi motif damar kurung sekreatif mungkin!
                                Pilih warna dari palet di bawah dan klik bidang sketsa untuk mewarnai 4 sisi motif Damar
                                Kurung bersama teman kelompokmu.”
                            </p>
                        </div>

                        <div class="flex flex-col gap-3">
                            <!-- Tab Pemilihan Sisi (1, 2, 3, 4) & Live Toast Banner -->
                            <div
                                class="flex items-center justify-between flex-wrap gap-2 pb-1 border-b border-[#E9C37A]/50">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <span class="text-xs font-bold text-[#8A4416] mr-1">Pilih Sisi:</span>
                                    <button type="button" onclick="selectColoringCanvas(1)" id="btnTabCanvas1"
                                        class="tab-canvas-btn px-3 py-1 rounded-full text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer bg-gradient-to-r from-[#E57354] to-[#C94F32] text-white shadow-xs scale-105">
                                        <span>Sisi 1</span>
                                        <span id="tabBadge-1"
                                            class="hidden w-2 h-2 rounded-full bg-yellow-300 animate-ping"></span>
                                    </button>
                                    <button type="button" onclick="selectColoringCanvas(2)" id="btnTabCanvas2"
                                        class="tab-canvas-btn px-3 py-1 rounded-full text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer bg-white text-[#8A4416] border border-[#E9C37A] hover:bg-[#FFF2D6]">
                                        <span>Sisi 2</span>
                                        <span id="tabBadge-2"
                                            class="hidden w-2 h-2 rounded-full bg-red-500 animate-ping"></span>
                                    </button>
                                    <button type="button" onclick="selectColoringCanvas(3)" id="btnTabCanvas3"
                                        class="tab-canvas-btn px-3 py-1 rounded-full text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer bg-white text-[#8A4416] border border-[#E9C37A] hover:bg-[#FFF2D6]">
                                        <span>Sisi 3</span>
                                        <span id="tabBadge-3"
                                            class="hidden w-2 h-2 rounded-full bg-red-500 animate-ping"></span>
                                    </button>
                                    <button type="button" onclick="selectColoringCanvas(4)" id="btnTabCanvas4"
                                        class="tab-canvas-btn px-3 py-1 rounded-full text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer bg-white text-[#8A4416] border border-[#E9C37A] hover:bg-[#FFF2D6]">
                                        <span>Sisi 4</span>
                                        <span id="tabBadge-4"
                                            class="hidden w-2 h-2 rounded-full bg-red-500 animate-ping"></span>
                                    </button>
                                </div>

                                <!-- Live Activity Alert Pill (Tampil saat teman sedang mewarnai) -->
                                <div id="liveColorActivityToast"
                                    class="transition-all duration-300 opacity-0 -translate-y-1 pointer-events-none hidden items-center gap-1.5 bg-[#FFF2D6] border border-[#E57354] px-2.5 py-0.5 rounded-full shadow-2xs text-[11px] font-bold text-[#8A4416]">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping inline-block"></span>
                                    <span id="liveColorActivityText">Teman sedang mewarnai...</span>
                                    <span id="liveColorActivityDot"
                                        class="w-3 h-3 rounded-full border border-stone-300 inline-block shadow-2xs"></span>
                                </div>

                                <div class="flex items-center gap-2">
                                    <button type="button" onclick="resetCurrentCanvas()"
                                        class="text-xs text-[#B45309] bg-[#FFF2D6] hover:bg-[#FFE7BA] border border-[#E9C37A] px-2.5 py-1 rounded-full font-bold transition-all flex items-center gap-1 cursor-pointer"
                                        title="Bersihkan warna pada sisi sketsa yang sedang dibuka">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        <span>Reset Sisi Ini</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Pilihan Palet Warna -->
                            <div
                                class="flex items-center justify-between flex-wrap gap-2 bg-[#FFFDF5] p-2 rounded-xl border border-[#E9C37A]/50">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <span class="text-xs font-bold text-[#6D4C33] mr-1">Palet Warna:</span>
                                    <button type="button" onclick="pickSvgColor('#EF4444')"
                                        class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-[#EF4444] border-2 border-white shadow hover:scale-115 active:scale-95 transition-transform cursor-pointer"
                                        title="Merah"></button>
                                    <button type="button" onclick="pickSvgColor('#F97316')"
                                        class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-[#F97316] border-2 border-white shadow hover:scale-115 active:scale-95 transition-transform cursor-pointer"
                                        title="Oranye"></button>
                                    <button type="button" onclick="pickSvgColor('#FACC15')"
                                        class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-[#FACC15] border-2 border-white shadow hover:scale-115 active:scale-95 transition-transform cursor-pointer"
                                        title="Kuning Emas"></button>
                                    <button type="button" onclick="pickSvgColor('#FEF08A')"
                                        class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-[#FEF08A] border-2 border-white shadow hover:scale-115 active:scale-95 transition-transform cursor-pointer"
                                        title="Kuning Muda"></button>
                                    <button type="button" onclick="pickSvgColor('#22C55E')"
                                        class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-[#22C55E] border-2 border-white shadow hover:scale-115 active:scale-95 transition-transform cursor-pointer"
                                        title="Hijau Segar"></button>
                                    <button type="button" onclick="pickSvgColor('#14B8A6')"
                                        class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-[#14B8A6] border-2 border-white shadow hover:scale-115 active:scale-95 transition-transform cursor-pointer"
                                        title="Hijau Toska"></button>
                                    <button type="button" onclick="pickSvgColor('#38BDF8')"
                                        class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-[#38BDF8] border-2 border-white shadow hover:scale-115 active:scale-95 transition-transform cursor-pointer"
                                        title="Biru Langit"></button>
                                    <button type="button" onclick="pickSvgColor('#2563EB')"
                                        class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-[#2563EB] border-2 border-white shadow hover:scale-115 active:scale-95 transition-transform cursor-pointer"
                                        title="Biru Damar"></button>
                                    <button type="button" onclick="pickSvgColor('#8B5CF6')"
                                        class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-[#8B5CF6] border-2 border-white shadow hover:scale-115 active:scale-95 transition-transform cursor-pointer"
                                        title="Ungu"></button>
                                    <button type="button" onclick="pickSvgColor('#EC4899')"
                                        class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-[#EC4899] border-2 border-white shadow hover:scale-115 active:scale-95 transition-transform cursor-pointer"
                                        title="Merah Muda"></button>
                                    <button type="button" onclick="pickSvgColor('#78350F')"
                                        class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-[#78350F] border-2 border-white shadow hover:scale-115 active:scale-95 transition-transform cursor-pointer"
                                        title="Cokelat Kayu"></button>
                                    <button type="button" onclick="pickSvgColor('#B45309')"
                                        class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-[#B45309] border-2 border-white shadow hover:scale-115 active:scale-95 transition-transform cursor-pointer"
                                        title="Cokelat Bata"></button>
                                    <button type="button" onclick="pickSvgColor('#1C1917')"
                                        class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-[#1C1917] border-2 border-white shadow hover:scale-115 active:scale-95 transition-transform cursor-pointer"
                                        title="Hitam Arang"></button>
                                    <button type="button" onclick="pickSvgColor('#FFFFFF')"
                                        class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-white border-2 border-stone-400 shadow hover:scale-115 active:scale-95 transition-transform cursor-pointer flex items-center justify-center text-[10px] text-stone-500 font-bold"
                                        title="Hapus Warna / Putih">&#x2715;</button>
                                    <label
                                        class="w-6 h-6 sm:w-7 sm:h-7 rounded-full overflow-hidden border-2 border-white shadow hover:scale-115 active:scale-95 transition-transform cursor-pointer bg-gradient-to-tr from-pink-500 via-amber-400 to-cyan-400 flex items-center justify-center"
                                        title="Pilih Warna Bebas">
                                        <input type="color" onchange="pickSvgColor(this.value)"
                                            class="opacity-0 w-0 h-0 cursor-pointer">
                                    </label>
                                </div>

                                <div
                                    class="flex items-center gap-1.5 text-xs font-bold text-[#8A4416] bg-[#FFF2D6] px-3 py-1 rounded-full border border-[#E9C37A] shrink-0">
                                    <span>Warna Aktif:</span>
                                    <span id="activeColorDot"
                                        class="w-4 h-4 rounded-full bg-[#EF4444] border border-stone-300 inline-block shadow-2xs"></span>
                                </div>
                            </div>

                            <!-- Area Kanvas SVG Mewarnai (4 Viewports) -->
                            <div
                                class="bg-[#FFF9EA] p-3 sm:p-4 rounded-2xl border-2 border-[#E9C37A]/60 flex items-center justify-center min-h-[260px] max-h-[460px] overflow-auto relative">
                                <div id="canvasViewport-1" class="canvas-svg-viewport w-full flex justify-center">
                                    {!! $canvas1Svg !!}
                                </div>
                                <div id="canvasViewport-2"
                                    class="canvas-svg-viewport w-full flex justify-center hidden">
                                    {!! $canvas2Svg !!}
                                </div>
                                <div id="canvasViewport-3"
                                    class="canvas-svg-viewport w-full flex justify-center hidden">
                                    {!! $canvas3Svg !!}
                                </div>
                                <div id="canvasViewport-4"
                                    class="canvas-svg-viewport w-full flex justify-center hidden">
                                    {!! $canvas4Svg !!}
                                </div>
                            </div>

                            <!-- Bottom Action Card Bar -->
                            <div
                                class="flex flex-col sm:flex-row items-center justify-between gap-2 p-2 sm:p-2.5 bg-[#FFF2D6] rounded-xl border border-[#E9C37A] shrink-0">
                                <p class="text-[10px] sm:text-xs text-[#8A4416] font-bold text-left">
                                    Sudah selesai mewarnai seluruh sisi motif? Lanjutkan ke Misi 2 untuk menemukan
                                    bangun datar!
                                </p>
                                <button type="button" onclick="selesaiMisi1()"
                                    class="w-full sm:w-auto px-4 py-1.5 rounded-full border border-white/90 bg-gradient-to-r from-[#E57354] to-[#C94F32] hover:brightness-110 text-white font-bold text-xs shadow-xs active:scale-95 transition-all cursor-pointer flex items-center justify-center gap-1.5 whitespace-nowrap">
                                    <span>LANJUT KE MISI 2</span>
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </button>
                            </div>

                        </div>

                    </div>
                </div>

                <div class="absolute bottom-4 left-4 sm:bottom-6 sm:left-6 z-40">
                    <x-btn-back onclick="goToKolabStage(3)" title="Kembali ke Ayo Persiapkan" />
                </div>
                <div class="absolute bottom-4 right-4 sm:bottom-6 sm:right-6 z-40">
                    <x-btn-next onclick="selesaiMisi1()" title="Lanjut ke Misi 2 (Temukan Bangun Datar Yuk)" />
                </div>
            </div>

            <!-- ============================================================
                     SLIDE 3 (STAGE 4B): MISI 2 - TEMUKAN BANGUN DATAR YUK!
                     ============================================================ -->
            <div id="kolabStage4B"
                class="kolabStage hidden absolute inset-0 z-30 pointer-events-auto transition-opacity duration-300">
                <div
                    class="kolab-stage-wrapper absolute top-[50px] sm:top-[56px] bottom-2 sm:bottom-3 left-[23%] sm:left-[24%] md:left-[25%] right-2 sm:right-3 md:right-4 lg:top-[52px] lg:bottom-3.5 lg:left-[18%] lg:right-6 lg:transform-none lg:w-auto lg:max-w-[1350px] z-30 flex items-center justify-start pointer-events-auto w-auto">
                    <div
                        class="kolab-card relative w-full h-full lg:h-full lg:max-h-full overflow-y-auto bg-[#FFFDF5] border-4 border-[#E9C37A] rounded-[24px] sm:rounded-[34px] md:rounded-[42px] p-3 sm:p-5 md:p-6 shadow-[0_22px_50px_rgba(0,0,0,0.25),0_4px_12px_rgba(233,195,122,0.3)]">

                        <!-- Ekor Balon Chat -->
                        <div
                            class="absolute -left-[20px] sm:-left-[26px] top-10 sm:top-14 -translate-y-1/2 w-0 h-0 border-t-[12px] sm:border-t-[16px] border-t-transparent border-r-[20px] sm:border-r-[26px] border-r-[#E9C37A] border-b-[12px] sm:border-b-[16px] border-b-transparent">
                        </div>
                        <div
                            class="absolute -left-[14px] sm:-left-[18px] top-10 sm:top-14 -translate-y-1/2 w-0 h-0 border-t-[12px] sm:border-t-[16px] border-t-transparent border-r-[20px] sm:border-r-[26px] border-r-[#FFFDF5] border-b-[12px] sm:border-b-[16px] border-b-transparent">
                        </div>

                        <!-- Header Ruang & Tombol Keluar -->
                        <div
                            class="flex items-center justify-between pb-2 border-b border-[#E9C37A]/50 mb-2.5 flex-wrap gap-2">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="roomNameBadge font-bold text-xs sm:text-sm text-[#8A4416]">Ruang
                                    Kolaborasi</span>
                            </div>
                            <button type="button" onclick="leaveRoomOnline()"
                                class="text-xs text-[#DC2626] bg-[#FEE2E2] hover:bg-[#FECACA] border border-[#FCA5A5] px-3 py-1 rounded-full font-bold transition-all cursor-pointer flex items-center gap-1 shadow-2xs hover:scale-105 active:scale-95">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span>Keluar Ruang</span>
                            </button>
                        </div>

                        <!-- Header Misi 2 & Subtitle -->
                        <div class="mb-2 flex items-center justify-between gap-2 flex-wrap">
                            <div
                                class="inline-flex items-center gap-2 bg-[#EDE9FE] px-3 sm:px-3.5 py-1 rounded-full border border-[#8B5CF6] shadow-2xs">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-[#7C3AED]" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                <span class="text-xs sm:text-sm font-bold text-[#7C3AED]">Misi 2: Temukan Bangun
                                    Datar Yuk!</span>
                            </div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <span
                                    class="text-[10px] sm:text-xs text-[#8A4416] font-semibold bg-[#FFF2D6] px-2.5 py-0.5 rounded-full border border-[#E9C37A]">
                                    Sentuh/seret bentuk motif ke kolom BENDA di tabel
                                </span>
                                <button type="button" onclick="resetTabelIdentifikasi()" id="btnResetTabelIdentifikasi"
                                    class="px-2.5 sm:px-3 py-0.5 sm:py-1 rounded-full bg-white hover:bg-red-50 border border-red-300 hover:border-red-500 text-red-600 font-bold text-[11px] sm:text-xs transition-all flex items-center gap-1 shadow-2xs cursor-pointer active:scale-95"
                                    title="Kosongkan seluruh data tabel identifikasi">
                                    <svg class="w-3.5 h-3.5 text-red-500 shrink-0" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    <span>Reset Tabel</span>
                                </button>
                            </div>
                        </div>

                        <p
                            class="kolab-intro-p text-xs sm:text-sm lg:text-base text-[#3D1804] font-normal leading-relaxed text-left mb-2.5 select-none tracking-wide">
                            “Lanjutkan misi 2 untuk menemukan bentuk bangun datar berdasarkan ciri-cirinya pada gambar
                            objek-objek yang ada di dalam motif tersebut. Masukkan temuan kalian pada tabel yang
                            tersedia!”
                        </p>

                        <!-- Layout 2 Kolom: Motif di Kiri (Besar), Tabel di Kanan (Rapi & Kompak) -->
                        <div
                            class="kolab-4b-grid grid grid-cols-1 lg:grid-cols-2 gap-2 sm:gap-3 lg:gap-4 items-stretch flex-1 min-h-0 w-full mb-2.5">

                            <!-- KOLOM KIRI: Sketsa Motif (Menampilkan Hasil Pewarnaan Misi 1) -->
                            <div
                                class="col-span-1 bg-[#FFF8E7] rounded-xl sm:rounded-2xl p-2 sm:p-2.5 border-2 border-[#E9C37A] shadow-xs flex flex-col justify-start gap-2 h-auto ident-canvas-box">
                                <!-- Header Motif + Kontrol Zoom Inline (Tidak Menutupi Gambar) -->
                                <div
                                    class="ident-canvas-header flex items-center justify-between gap-1.5 pb-1.5 border-b border-[#E9C37A]/50 shrink-0 flex-wrap">
                                    <div class="flex items-center gap-1.5 min-w-0">
                                        <span class="text-xs sm:text-sm font-bold text-[#8A4416] truncate">Motif Damar
                                            Kurung</span>
                                        <span id="identCanvasBadge"
                                            class="text-[10px] sm:text-xs font-bold text-[#E57354] bg-[#FFEBE6] border border-[#E57354]/40 px-2 py-0.5 rounded-full shadow-2xs shrink-0 whitespace-nowrap">Sisi
                                            1 dari 4</span>
                                    </div>
                                    <div class="flex items-center gap-0.5 bg-white border border-[#E9C37A] rounded-lg px-1 py-0.5 shadow-2xs shrink-0">
                                        <button type="button" onclick="zoomIdentCanvas(-0.25)"
                                            class="w-6 h-6 rounded hover:bg-[#FFF2D6] text-[#8A4416] font-bold text-xs flex items-center justify-center cursor-pointer"
                                            title="Perkecil">−</button>
                                        <span id="identZoomLabel" class="text-[10px] font-bold text-[#8A4416] min-w-[34px] text-center">100%</span>
                                        <button type="button" onclick="zoomIdentCanvas(0.25)"
                                            class="w-6 h-6 rounded hover:bg-[#FFF2D6] text-[#8A4416] font-bold text-xs flex items-center justify-center cursor-pointer"
                                            title="Perbesar">+</button>
                                        <button type="button" onclick="resetIdentCanvasZoom()"
                                            class="px-1.5 h-6 rounded hover:bg-[#FFEBE6] text-[#E57354] font-bold text-[10px] flex items-center justify-center cursor-pointer border-l border-[#E9C37A]/60"
                                            title="Reset Zoom">Reset</button>
                                    </div>
                                </div>

                                <!-- Area Tampilan Kanvas Scrollable Seperti Misi 1 -->
                                <div id="identCanvasViewport"
                                    class="relative w-full bg-[#FFF9EA] rounded-xl border-2 border-[#E9C37A]/70 min-h-[240px] max-h-[380px] overflow-auto shadow-inner p-2 sm:p-3 ident-canvas-viewport">
                                    <div id="identZoomContainer"
                                        class="w-full min-h-full flex items-center justify-center mx-auto select-none">
                                        <div id="identCanvasSlide-1"
                                            class="identCanvasSlide ident-canvas-svg w-full flex items-center justify-center"
                                            style="display: flex !important;">
                                            {!! $identCanvas1 !!}
                                        </div>
                                        <div id="identCanvasSlide-2"
                                            class="identCanvasSlide ident-canvas-svg w-full items-center justify-center hidden"
                                            style="display: none !important;">
                                            {!! $identCanvas2 !!}
                                        </div>
                                        <div id="identCanvasSlide-3"
                                            class="identCanvasSlide ident-canvas-svg w-full items-center justify-center hidden"
                                            style="display: none !important;">
                                            {!! $identCanvas3 !!}
                                        </div>
                                        <div id="identCanvasSlide-4"
                                            class="identCanvasSlide ident-canvas-svg w-full items-center justify-center hidden"
                                            style="display: none !important;">
                                            {!! $identCanvas4 !!}
                                        </div>
                                    </div>
                                </div>

                                <!-- Bar Navigasi Pergantian Panel Sisi (Format Angka 1 2 3 4 Rapi Tanpa Kata 'Sisi') -->
                                <div
                                    class="ident-canvas-pagination flex items-center justify-between gap-1 pt-1 border-t border-[#E9C37A]/40 shrink-0 w-full select-none">
                                    <!-- Tombol Sisi Sebelumnya -->
                                    <button type="button" onclick="prevIdentCanvas()" id="btnIdentPrev"
                                        class="h-7 w-7 sm:h-8 sm:w-8 lg:h-9 lg:w-9 rounded-lg sm:rounded-xl bg-white hover:bg-[#FFEBE6] border-2 border-[#E9C37A] hover:border-[#E57354] text-[#8A4416] hover:text-[#E57354] font-bold text-xs flex items-center justify-center shadow-2xs hover:shadow-xs active:scale-95 transition-all cursor-pointer shrink-0"
                                        title="Sisi Sebelumnya" aria-label="Sisi Sebelumnya">
                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-[#E57354] shrink-0" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                        </svg>
                                    </button>

                                    <!-- Cluster Pill Tombol 1, 2, 3, 4 (Format Bersih 1 2 3 4 di Semua Layar) -->
                                    <div
                                        class="flex-1 min-w-0 grid grid-cols-4 gap-1 p-0.5 sm:p-1 bg-[#FFF2D6]/80 rounded-lg sm:rounded-xl border border-[#E9C37A]/70 shadow-2xs mx-0.5 sm:mx-1">
                                        <button type="button" onclick="setIdentCanvas(1)" id="identPill-1"
                                            class="ident-canvas-pill h-6.5 sm:h-7 lg:h-8 w-full rounded-md sm:rounded-lg text-[10px] sm:text-xs font-bold transition-all border-2 bg-[#E57354] text-white border-[#E57354] shadow-xs cursor-pointer flex items-center justify-center"
                                            aria-label="Tampilkan Sisi 1">1</button>
                                        <button type="button" onclick="setIdentCanvas(2)" id="identPill-2"
                                            class="ident-canvas-pill h-6.5 sm:h-7 lg:h-8 w-full rounded-md sm:rounded-lg text-[10px] sm:text-xs font-bold transition-all border bg-white/90 hover:bg-white text-[#8A4416] border-[#E9C37A] hover:border-[#E57354]/50 shadow-2xs cursor-pointer flex items-center justify-center"
                                            aria-label="Tampilkan Sisi 2">2</button>
                                        <button type="button" onclick="setIdentCanvas(3)" id="identPill-3"
                                            class="ident-canvas-pill h-6.5 sm:h-7 lg:h-8 w-full rounded-md sm:rounded-lg text-[10px] sm:text-xs font-bold transition-all border bg-white/90 hover:bg-white text-[#8A4416] border-[#E9C37A] hover:border-[#E57354]/50 shadow-2xs cursor-pointer flex items-center justify-center"
                                            aria-label="Tampilkan Sisi 3">3</button>
                                        <button type="button" onclick="setIdentCanvas(4)" id="identPill-4"
                                            class="ident-canvas-pill h-6.5 sm:h-7 lg:h-8 w-full rounded-md sm:rounded-lg text-[10px] sm:text-xs font-bold transition-all border bg-white/90 hover:bg-white text-[#8A4416] border-[#E9C37A] hover:border-[#E57354]/50 shadow-2xs cursor-pointer flex items-center justify-center"
                                            aria-label="Tampilkan Sisi 4">4</button>
                                    </div>

                                    <!-- Tombol Sisi Berikutnya -->
                                    <button type="button" onclick="nextIdentCanvas()" id="btnIdentNext"
                                        class="h-7 w-7 sm:h-8 sm:w-8 lg:h-9 lg:w-9 rounded-lg sm:rounded-xl bg-white hover:bg-[#FFEBE6] border-2 border-[#E9C37A] hover:border-[#E57354] text-[#8A4416] hover:text-[#E57354] font-bold text-xs flex items-center justify-center shadow-2xs hover:shadow-xs active:scale-95 transition-all cursor-pointer shrink-0"
                                        title="Sisi Berikutnya" aria-label="Sisi Berikutnya">
                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-[#E57354] shrink-0" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- KOLOM KANAN: Tabel Identifikasi -->
                            <div class="col-span-1 flex flex-col min-w-0 h-full flex-1 ident-table-box">
                                <div
                                    class="overflow-x-auto rounded-xl sm:rounded-2xl border-2 border-[#E9C37A] bg-white shadow-xs flex-1 h-full min-h-0 overflow-y-auto flex flex-col ident-table-wrapper">
                                    <table class="w-full text-left border-collapse">
                                        <thead class="sticky top-0 z-10 shrink-0">
                                            <tr
                                                class="bg-gradient-to-r from-[#2B5F8C] via-[#3B7BB3] to-[#2B5F8C] text-white text-[11px] sm:text-xs lg:text-sm uppercase tracking-wider font-bold shadow-sm">
                                                <th
                                                    class="py-2 px-1.5 text-center w-[19%] border-r border-[#6393BD]">
                                                    BENTUK</th>
                                                <th
                                                    class="py-2 px-2 text-center w-[29%] border-r border-[#6393BD]">
                                                    BANGUN DATAR</th>
                                                <th class="py-2 px-2 text-center w-[52%]">CIRI-CIRI</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbodyIdentificationForm"
                                            class="divide-y divide-[#E9C37A]/40 text-xs lg:text-sm text-[#3D1804]">
                                            <!-- Pre-rendered 6 baris tabel -->
                                            @for($i = 0; $i < 6; $i++)
                                                <tr data-row-idx="{{ $i }}"
                                                    class="drop-row-target hover:bg-[#FFF8E7]/60 transition-colors bg-[#FFFDF7]">
                                                    <td class="p-1.5 border-r border-[#E9C37A]/50 align-middle text-center w-[19%]"
                                                        data-row-idx="{{ $i }}" ondragover="onBendaDragOver(event)"
                                                        ondrop="onBendaDrop(event, {{ $i }})">
                                                        <div class="benda-drop-zone h-12 sm:h-14 w-full flex items-center justify-center border-2 border-dashed border-[#E9C37A] bg-[#FFFDF5] rounded-lg px-1 text-center text-[10px] lg:text-xs text-[#8A4416] font-bold hover:border-[#E57354] hover:bg-[#FFF8E7] transition-all cursor-pointer select-none"
                                                            onclick="onBendaSlotClick({{ $i }})">
                                                            + Pasang
                                                        </div>
                                                    </td>
                                                    <td class="p-1.5 border-r border-[#E9C37A]/50 align-middle w-[29%]"
                                                        data-row-idx="{{ $i }}">
                                                        <div class="custom-shape-dropdown w-full" data-shape-row="{{ $i }}">
                                                            <input type="hidden" id="shapeSelect-{{ $i }}" value="">
                                                            <button type="button"
                                                                id="shapeSelectBtn-{{ $i }}"
                                                                onclick="toggleShapeDropdown(event, {{ $i }})"
                                                                class="w-full h-10 lg:h-11 flex items-center justify-between gap-1.5 bg-white border border-[#E9C37A] rounded-lg px-2.5 py-1.5 text-[11px] sm:text-xs lg:text-sm font-bold text-[#8A4416] outline-none cursor-pointer hover:bg-[#FFF8E7] focus:border-[#E57354] shadow-2xs transition-colors text-left">
                                                                <span id="shapeLabel-{{ $i }}" class="truncate flex items-center gap-1.5 min-w-0">-- Pilih Bangun Datar --</span>
                                                                <svg id="shapeArrow-{{ $i }}" class="w-4 h-4 text-[#8A4416] shrink-0 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </td>
                                                    <td class="p-1.5 align-middle w-[52%]" data-row-idx="{{ $i }}">
                                                        <textarea id="identCiriInput-{{ $i }}"
                                                            oninput="updateIdentificationRowData({{ $i }}, 'ciri', this.value)"
                                                            placeholder="Tuliskan ciri..." rows="2"
                                                            class="w-full min-h-[48px] sm:min-h-[54px] bg-[#FFFDF5] border border-[#E9C37A]/70 rounded-lg px-2.5 py-1.5 text-[11px] sm:text-xs lg:text-sm outline-none text-[#3D1804] leading-snug resize-none focus:border-[#E57354] focus:bg-white"></textarea>
                                                    </td>
                                                </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>

                        <!-- Bottom Action Card Bar -->
                        <div
                            class="kolab-stage4b-bottom-bar flex items-center justify-between gap-2 p-2 lg:p-2.5 bg-[#FFF2D6] rounded-xl border border-[#E9C37A] shrink-0">
                            <p
                                class="hidden sm:block text-xs lg:text-sm text-[#8A4416] font-bold text-left truncate">
                                Sudah melengkapi tabel identifikasi bangun datar? Lanjutkan ke Misi 3!
                            </p>
                            <button type="button" onclick="selesaiMisi2()"
                                class="w-full sm:w-auto px-4 py-1.5 rounded-full border border-white/90 bg-gradient-to-r from-[#7C3AED] to-[#6D28D9] hover:brightness-110 text-white font-bold text-xs sm:text-sm shadow-xs active:scale-95 transition-all cursor-pointer flex items-center justify-center gap-1.5 whitespace-nowrap ml-auto">
                                <span>LANJUT KE MISI 3</span>
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </button>
                        </div>

                    </div>
                </div>

                <div class="absolute bottom-4 left-4 sm:bottom-6 sm:left-6 z-40">
                    <x-btn-back onclick="goToKolabStage('4A')" title="Kembali ke Misi 1 (Mewarnai)" />
                </div>
                <div class="absolute bottom-4 right-4 sm:bottom-6 sm:right-6 z-40">
                    <x-btn-next onclick="selesaiMisi2()" title="Lanjut ke Misi 3 (Evaluasi & Apresiasi)" />
                </div>
            </div>

            <!-- ============================================================
                     SLIDE 4 (STAGE 6): MISI 3 - EVALUASI & APRESIASI TEMAN KELOMPOK
                     ============================================================ -->
            <div id="kolabStage6"
                class="kolabStage hidden absolute inset-0 z-30 pointer-events-auto transition-opacity duration-300">
                <div
                    class="kolab-stage-wrapper absolute top-[50px] sm:top-[56px] bottom-2 sm:bottom-3 left-[23%] sm:left-[24%] md:left-[25%] right-2 sm:right-3 md:right-4 lg:top-[52px] lg:bottom-3.5 lg:left-[18%] lg:right-6 lg:transform-none lg:w-auto lg:max-w-[1350px] z-30 flex items-center justify-start pointer-events-auto w-auto">
                    <div
                        class="kolab-card relative w-full h-full lg:h-full lg:max-h-full overflow-y-auto bg-[#FFFDF5] border-4 border-[#E9C37A] rounded-[24px] sm:rounded-[34px] md:rounded-[42px] p-3 sm:p-5 md:p-6 shadow-[0_22px_50px_rgba(0,0,0,0.25),0_4px_12px_rgba(233,195,122,0.3)]">

                        <!-- Ekor Balon Chat -->
                        <div
                            class="absolute -left-[20px] sm:-left-[26px] top-10 sm:top-14 -translate-y-1/2 w-0 h-0 border-t-[12px] sm:border-t-[16px] border-t-transparent border-r-[20px] sm:border-r-[26px] border-r-[#E9C37A] border-b-[12px] sm:border-b-[16px] border-b-transparent">
                        </div>
                        <div
                            class="absolute -left-[14px] sm:-left-[18px] top-10 sm:top-14 -translate-y-1/2 w-0 h-0 border-t-[12px] sm:border-t-[16px] border-t-transparent border-r-[20px] sm:border-r-[26px] border-r-[#FFFDF5] border-b-[12px] sm:border-b-[16px] border-b-transparent">
                        </div>

                        <!-- Header Ruang & Tombol Keluar -->
                        <div
                            class="flex items-center justify-between pb-2 border-b border-[#E9C37A]/50 mb-3 flex-wrap gap-2">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="roomNameBadge font-bold text-xs sm:text-sm text-[#8A4416]">Ruang
                                    Kolaborasi</span>
                            </div>
                            <button type="button" onclick="leaveRoomOnline()"
                                class="text-xs text-[#DC2626] bg-[#FEE2E2] hover:bg-[#FECACA] border border-[#FCA5A5] px-3 py-1 rounded-full font-bold transition-all cursor-pointer flex items-center gap-1 shadow-2xs hover:scale-105 active:scale-95">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span>Keluar Ruang</span>
                            </button>
                        </div>

                        <div class="mb-3">
                            <div
                                class="inline-flex items-center gap-2 bg-[#FFF2D6] px-3.5 py-1 rounded-full border border-[#E9C37A] mb-1.5 shadow-2xs">
                                <svg class="w-4 h-4 text-[#8A4416]" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                </svg>
                                <span class="text-xs sm:text-sm font-bold text-[#8A4416]">Misi 3: Evaluasi & Apresiasi
                                    Teman Kelompok</span>
                            </div>
                            <p
                                class="text-xs sm:text-sm md:text-base text-[#3D1804] font-normal leading-relaxed text-left select-none tracking-wide">
                                “Setelah itu, selesaikan misi 3 dengan cara berdiskusi untuk memberikan evaluasi dan
                                apresiasi atas hasil kerja tim yang telah dilakukan pada tabel yang tersedia. Jangan
                                lupa berdoa dan berkolaborasi dengan baik ya!”
                            </p>
                        </div>

                        <!-- Tabel Evaluasi Teman Se-tim -->
                        <div
                            class="eval-table-wrapper overflow-x-auto rounded-2xl border-2 border-[#E9C37A] shadow-xs bg-white mb-3 shrink-0">
                            <table class="w-full text-left text-xs sm:text-sm min-w-[560px]">
                                <thead class="sticky top-0 z-10">
                                    <tr class="bg-gradient-to-r from-[#548466] to-[#3D694D] text-white shadow-xs">
                                        <th
                                            class="p-2 sm:p-2.5 text-center w-10 sm:w-12 border-r border-white/20 font-bold">
                                            No</th>
                                        <th class="p-2 sm:p-2.5 w-[26%] border-r border-white/20 font-bold">Nama Teman
                                        </th>
                                        <th class="p-2 sm:p-2.5 w-[14%] text-center border-r border-white/20 font-bold">
                                            No. Absen</th>
                                        <th class="p-2 sm:p-2.5 w-[26%] border-r border-white/20 font-bold">Penilaian
                                            Kerjasama</th>
                                        <th class="p-2 sm:p-2.5 font-bold">Pesan Apresiasi & Pujian</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyEvaluationList" class="divide-y divide-[#E9C37A]/30">
                                    <!-- Dynamically Populated by JavaScript with Room Members -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Banner Aksi Kumpulkan Tugas Kelompok (Di dalam kartu) -->
                        <div class="mt-3 flex flex-col sm:flex-row items-center justify-between gap-3 p-3.5 rounded-2xl border-2 border-[#E9C37A] shadow-xs"
                            style="background: #FFF2D6;">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full text-white flex items-center justify-center font-bold text-sm shadow-xs shrink-0"
                                    style="background: #15803D;">
                                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <div class="text-xs sm:text-sm font-bold text-[#8A4416]">Tahap Evaluasi & Apresiasi
                                        Selesai!</div>
                                    <p class="text-[11px] text-[#6D4C33] font-medium leading-tight">
                                        Klik tombol di samping untuk mengumpulkan tugas kelompok ke Guru/Admin.
                                    </p>
                                </div>
                            </div>
                            <button type="button" onclick="openConfirmSubmitModal()"
                                class="w-full sm:w-auto px-6 py-2.5 rounded-full border-2 border-white text-white font-['Jua'] font-bold text-xs sm:text-sm tracking-wide hover:brightness-110 active:translate-y-[2px] active:shadow-none transition-all cursor-pointer flex items-center justify-center gap-2 whitespace-nowrap"
                                style="background: linear-gradient(180deg, #4ADE80 0%, #22C55E 50%, #15803D 100%) !important; box-shadow: 0 4px 0 #0F5128, 0 6px 12px rgba(0,0,0,0.2) !important;">
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-white drop-shadow-[0_1px_2px_rgba(0,0,0,0.4)]">KUMPULKAN TUGAS
                                    KELOMPOK</span>
                            </button>
                        </div>

                    </div>
                </div>

                <!-- Tombol Navigasi Bawah Stage 6 -->
                <div class="absolute bottom-4 left-4 sm:bottom-6 sm:left-6 z-40">
                    <x-btn-back onclick="goToKolabStage('4B')" title="Kembali ke Misi 2 (Temukan Bangun Datar Yuk)" />
                </div>
            </div>

            <!-- ============================================================
                     MODAL: KONFIRMASI PENGUMPULAN TUGAS AKHIR
                     ============================================================ -->
            <div id="confirmSubmitModal"
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/65 backdrop-blur-xs opacity-0 pointer-events-none transition-all duration-300 font-['Jua'] select-none">
                <div class="absolute inset-0" onclick="closeConfirmSubmitModal()"></div>
                <div id="confirmSubmitBox"
                    class="relative z-10 w-full max-w-[430px] transform scale-90 transition-all duration-300 rounded-[28px] border-4 border-[#3D1804] p-5 sm:p-6 shadow-[0_20px_40px_rgba(0,0,0,0.45)]"
                    style="background: linear-gradient(180deg, #9E531F 0%, #7E3E11 25%, #6E330C 65%, #522204 100%);">

                    <div class="text-center mb-3">
                        <h3 class="text-2xl font-bold text-[#FFF2A6]"
                            style="text-shadow: 2px 2px 0 #3D1804, -2px -2px 0 #3D1804, 2px -2px 0 #3D1804, -2px 2px 0 #3D1804;">
                            Kumpulkan Tugas?
                        </h3>
                        <p class="text-xs text-[#FFE8A3] font-medium mt-0.5">Konfirmasi pengumpulan hasil kerja kelompok
                        </p>
                    </div>

                    <div
                        class="rounded-2xl border-3 border-[#612A07] bg-[#FFFDF5] p-4 sm:p-5 shadow text-center space-y-3.5">
                        <div
                            class="w-12 h-12 rounded-full bg-[#FEF3C7] border-2 border-[#F59E0B] text-[#D97706] flex items-center justify-center mx-auto shadow-2xs">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>

                        <p class="text-xs sm:text-sm text-[#5C2B09] font-bold leading-relaxed">
                            Apakah kalian yakin ingin mengumpulkan tugas kelompok ini?<br>
                            <span class="text-[11px] font-normal text-[#8A4416]">Setelah dikumpulkan, ruang kerja akan
                                selesai dan otomatis ditutup dari lobby.</span>
                        </p>

                        <div class="flex items-center gap-2 pt-1">
                            <button type="button" onclick="closeConfirmSubmitModal()"
                                class="w-1/2 py-2.5 rounded-full border-2 border-[#E9C37A] bg-[#FFF2D6] text-[#8A4416] font-bold text-xs sm:text-sm hover:bg-[#FFE8B8] cursor-pointer">
                                Kembali Periksa
                            </button>
                            <button type="button" onclick="executeFinalSubmitRoom()"
                                class="w-1/2 py-2.5 rounded-full border-2 border-white/90 bg-gradient-to-b from-[#EF4444] via-[#DC2626] to-[#B91C1C] text-white font-bold text-xs sm:text-sm shadow hover:brightness-110 active:translate-y-[1px] cursor-pointer">
                                Ya, Kumpulkan
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================================================
                     MODAL: KONFIRMASI RESET WARNA KANVAS
                     ============================================================ -->
            <div id="resetCanvasModal"
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/65 backdrop-blur-xs opacity-0 pointer-events-none transition-all duration-300 font-['Jua'] select-none">
                <div class="absolute inset-0" onclick="closeResetCanvasModal()"></div>
                <div id="resetCanvasBox"
                    class="relative z-10 w-full max-w-[430px] transform scale-90 transition-all duration-300 rounded-[28px] border-4 border-[#3D1804] p-5 sm:p-6 shadow-[0_20px_40px_rgba(0,0,0,0.5),0_8px_0_#280E02]"
                    style="background: linear-gradient(180deg, #9E531F 0%, #7E3E11 25%, #6E330C 65%, #522204 100%);">

                    <div class="text-center mb-3 pt-1">
                        <h3 class="text-2xl sm:text-3xl font-bold text-[#FFF2A6]"
                            style="text-shadow: 2px 2px 0 #3D1804, -2px -2px 0 #3D1804, 2px -2px 0 #3D1804, -2px 2px 0 #3D1804;">
                            Reset Warna Sisi Sketsa?
                        </h3>
                        <p class="text-xs text-[#FFE8A3] font-medium mt-1">
                            Peringatan pembersihan warna motif
                        </p>
                    </div>

                    <div
                        class="rounded-2xl border-3 border-[#612A07] bg-gradient-to-b from-[#FFFDF0] to-[#FCEEC7] p-4 sm:p-5 shadow text-center space-y-3.5">
                        <div
                            class="w-12 h-12 rounded-full bg-[#FEF3C7] border-2 border-[#F59E0B] text-[#D97706] flex items-center justify-center mx-auto shadow-2xs">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </div>

                        <p class="text-xs sm:text-sm text-[#5C2B09] font-bold leading-relaxed">
                            Apakah kamu yakin ingin menghapus seluruh warna pada sisi yang sedang dibuka?<br>
                            <span class="text-[11px] font-normal text-[#8A4416]">Semua goresan warna pada sisi ini akan
                                kembali putih seperti semula. Pilihan warna rekan satu tim pada sisi ini juga akan
                                terhapus.</span>
                        </p>

                        <div class="flex items-center gap-2.5 pt-1">
                            <button type="button" onclick="closeResetCanvasModal()"
                                class="w-1/2 py-2.5 rounded-full border-2 border-[#E9C37A] bg-[#FFF2D6] hover:bg-[#FFE8B8] text-[#8A4416] font-bold text-xs sm:text-sm active:translate-y-[1px] cursor-pointer transition-all">
                                Batal
                            </button>
                            <button type="button" onclick="executeResetCurrentCanvas()"
                                class="w-1/2 py-2.5 rounded-full border-2 border-white/90 bg-gradient-to-b from-[#EF4444] via-[#DC2626] to-[#B91C1C] text-white font-bold text-xs sm:text-sm shadow-[0_4px_0_#991B1B] hover:brightness-110 active:translate-y-[2px] active:shadow-none cursor-pointer transition-all">
                                Ya, Reset Sisi Ini
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================================================
                     MODAL: KONFIRMASI RESET TABEL IDENTIFIKASI
                     ============================================================ -->
            <div id="resetTableModal"
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/65 backdrop-blur-xs opacity-0 pointer-events-none transition-all duration-300 font-['Jua'] select-none">
                <div class="absolute inset-0" onclick="closeResetTableModal()"></div>
                <div id="resetTableBox"
                    class="relative z-10 w-full max-w-[430px] transform scale-90 transition-all duration-300 rounded-[28px] border-4 border-[#3D1804] p-5 sm:p-6 shadow-[0_20px_40px_rgba(0,0,0,0.5),0_8px_0_#280E02]"
                    style="background: linear-gradient(180deg, #9E531F 0%, #7E3E11 25%, #6E330C 65%, #522204 100%);">

                    <div class="text-center mb-3 pt-1">
                        <h3 class="text-2xl sm:text-3xl font-bold text-[#FFF2A6]"
                            style="text-shadow: 2px 2px 0 #3D1804, -2px -2px 0 #3D1804, 2px -2px 0 #3D1804, -2px 2px 0 #3D1804;">
                            Reset Tabel Identifikasi?
                        </h3>
                        <p class="text-xs text-[#FFE8A3] font-medium mt-1">
                            Peringatan pengosongan tabel bangun datar
                        </p>
                    </div>

                    <div
                        class="rounded-2xl border-3 border-[#612A07] bg-gradient-to-b from-[#FFFDF0] to-[#FCEEC7] p-4 sm:p-5 shadow text-center space-y-3.5">
                        <div
                            class="w-12 h-12 rounded-full bg-[#FEF3C7] border-2 border-[#F59E0B] text-[#D97706] flex items-center justify-center mx-auto shadow-2xs">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </div>

                        <p class="text-xs sm:text-sm text-[#5C2B09] font-bold leading-relaxed">
                            Apakah kamu yakin ingin mengosongkan seluruh isi tabel identifikasi?<br>
                            <span class="text-[11px] font-normal text-[#8A4416]">Semua baris pilihan benda, bentuk
                                bangun datar, dan ciri-ciri yang telah diisi pada tabel akan dikembalikan ke kondisi
                                awal.</span>
                        </p>

                        <div class="flex items-center gap-2.5 pt-1">
                            <button type="button" onclick="closeResetTableModal()"
                                class="w-1/2 py-2.5 rounded-full border-2 border-[#E9C37A] bg-[#FFF2D6] hover:bg-[#FFE8B8] text-[#8A4416] font-bold text-xs sm:text-sm active:translate-y-[1px] cursor-pointer transition-all">
                                Batal
                            </button>
                            <button type="button" onclick="executeResetTabelIdentifikasi()"
                                class="w-1/2 py-2.5 rounded-full border-2 border-white/90 bg-gradient-to-b from-[#EF4444] via-[#DC2626] to-[#B91C1C] text-white font-bold text-xs sm:text-sm shadow-[0_4px_0_#991B1B] hover:brightness-110 active:translate-y-[2px] active:shadow-none cursor-pointer transition-all">
                                Ya, Kosongkan Tabel
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================================================
                     MODAL: SUKSES PENGUMPULAN TUGAS (PROPORSIONAL SEPERTI EXIT MODAL)
                     ============================================================ -->
            <div id="successSubmitModal"
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/65 backdrop-blur-sm opacity-0 pointer-events-none transition-all duration-300 font-['Jua'] select-none">

                <div class="absolute inset-0" onclick="handleFinishSuccessSubmit()"></div>

                <div id="successSubmitBox"
                    class="relative z-10 w-full max-w-[420px] transform scale-90 transition-all duration-300 rounded-3xl border-4 border-[#3D1804] px-5 py-5 sm:px-6 sm:py-6 shadow-[0_20px_40px_rgba(0,0,0,0.5),0_8px_0_#280E02]"
                    style="background: linear-gradient(180deg, #9E531F 0%, #7E3E11 25%, #6E330C 65%, #522204 100%);">

                    <div class="text-center mb-3 pt-1">
                        <h2 class="text-2xl sm:text-3xl font-bold text-[#FFF2A6]"
                            style="text-shadow: 2px 2px 0 #3D1804, -2px -2px 0 #3D1804, 2px -2px 0 #3D1804, -2px 2px 0 #3D1804;">
                            Selamat dan Hebat!
                        </h2>
                        <p class="text-xs text-[#FFE8A3] font-medium mt-1">
                            Tugas kelompok berhasil dikumpulkan
                        </p>
                    </div>

                    <div
                        class="rounded-2xl border-3 border-[#612A07] bg-gradient-to-b from-[#FFFDF0] to-[#FCEEC7] p-4 sm:p-5 shadow text-center space-y-3.5">
                        <div class="flex justify-center -mt-1">
                            <img src="{{ asset('assets/karakter_jempol.webp') }}" alt="Karakter Jempol"
                                class="h-28 sm:h-32 w-auto object-contain drop-shadow-md">
                        </div>

                        <p id="successSubmitMessage"
                            class="text-xs sm:text-sm text-[#785135] font-semibold leading-relaxed">
                            Tugas kelompok telah berhasil dikumpulkan dan tersimpan di sistem Guru. Terima kasih atas
                            kerja sama tim yang luar biasa!
                        </p>

                        <div class="flex flex-col sm:flex-row items-center gap-2.5 pt-1">
                            <a href="{{ route('menu') }}"
                                onclick="try { sessionStorage.removeItem('tara_kolab_room'); sessionStorage.removeItem('tara_kolab_stage'); } catch(e){}"
                                class="w-full flex-1 rounded-full border-3 border-white/90 bg-gradient-to-b from-[#4ADE80] via-[#22C55E] to-[#15803D] py-2.5 text-white font-bold text-xs sm:text-sm tracking-wide shadow-[0_5px_0_#0F5128] hover:brightness-110 active:translate-y-[2px] cursor-pointer flex items-center justify-center gap-1.5 text-center">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                <span>Menu Utama</span>
                            </a>
                            <button type="button" onclick="handleFinishSuccessSubmit()"
                                class="w-full sm:w-auto px-5 rounded-full border-3 border-white/90 bg-gradient-to-b from-[#FFA940] via-[#FA8C16] to-[#D46B08] py-2.5 text-white font-bold text-xs sm:text-sm tracking-wide shadow-[0_5px_0_#873800] hover:brightness-110 active:translate-y-[2px] cursor-pointer whitespace-nowrap">
                                <span>Lobi Ruang</span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            <!-- SOUND SETTINGS (MODAL ONLY) -->
            <x-sound-settings :renderTrigger="false" />

            <!-- EXIT CONFIRMATION MODAL -->
            <x-exit-modal />

            <!-- Portal untuk Dropdown Evaluasi Misi 3 agar tidak terpotong tabel -->
            <div id="evalDropdownPortal" class="fixed inset-0 pointer-events-none z-[9999]"></div>

        </div>
    </div>

    <script>
        var currentActiveSessionId = {{ $activeSession->id }};
        var currentRoomNumber = null;
        var currentPollInterval = null;
        var roomMembersList = [];
        var TOTAL_EVAL_ROWS = 4;
        function createEmptyEvalRowStates() {
            return Array.from({ length: TOTAL_EVAL_ROWS }, () => ({
                peerId: '',
                name: '',
                noAbsen: '-',
                isManual: false,
                manualSkipped: false,
                rating: 'Sangat Baik',
                note: ''
            }));
        }
        var evalRowStates = createEmptyEvalRowStates();
        var myAssignedTask = 'mewarnai'; // 'mewarnai' or 'identifikasi'
        var activeSelectedShapeKey = null;
        var lastIdentificationSignature = '';
        var lastColoringSignature = '';
        var lastRoomsSignature = '';
        var lastRenderedMembersSignature = '';
        window.windowDraggedShapeKey = null;

        var currentUserId = {{ $currentUser->id }};
        var currentUserName = @json($currentUser->name ?? 'Siswa');
        var currentUserNoAbsen = @json($currentUser->no_absen ?? '-');
        function getCsrfToken() {
            return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
        }

        var themeImages = {
            'Sisi 1': '{{ asset("assets/canvas1a.svg") }}',
            'Sisi 2': '{{ asset("assets/canvas2a.svg") }}',
            'Sisi 3': '{{ asset("assets/frame3.svg") }}',
            'Sisi 4': '{{ asset("assets/frame4.svg") }}',
            1: '{{ asset("assets/canvas1a.svg") }}',
            2: '{{ asset("assets/canvas2a.svg") }}',
            3: '{{ asset("assets/frame3.svg") }}',
            4: '{{ asset("assets/frame4.svg") }}'
        };

        var roomColorThemes = [
            { border: 'border-[#E57354]', badge: 'from-[#E57354] to-[#C94F32]', text: 'text-[#E57354]', btn: 'bg-[#E57354] hover:bg-[#D95F3F]', bgLight: 'bg-[#FFF2EE]' },
            { border: 'border-[#548466]', badge: 'from-[#548466] to-[#3D694D]', text: 'text-[#548466]', btn: 'bg-[#548466] hover:bg-[#3D694D]', bgLight: 'bg-[#EFF6F2]' },
            { border: 'border-[#ECA144]', badge: 'from-[#ECA144] to-[#D4892A]', text: 'text-[#ECA144]', btn: 'bg-[#ECA144] hover:bg-[#D4892A]', bgLight: 'bg-[#FFF8EE]' },
            { border: 'border-[#8B5CF6]', badge: 'from-[#8B5CF6] to-[#6D28D9]', text: 'text-[#8B5CF6]', btn: 'bg-[#8B5CF6] hover:bg-[#7C3AED]', bgLight: 'bg-[#F5F3FF]' },
            { border: 'border-[#0284C7]', badge: 'from-[#0284C7] to-[#0369A1]', text: 'text-[#0284C7]', btn: 'bg-[#0284C7] hover:bg-[#0369A1]', bgLight: 'bg-[#F0F9FF]' },
            { border: 'border-[#E11D48]', badge: 'from-[#E11D48] to-[#BE123C]', text: 'text-[#E11D48]', btn: 'bg-[#E11D48] hover:bg-[#BE123C]', bgLight: 'bg-[#FFF1F2]' },
            { border: 'border-[#0D9488]', badge: 'from-[#0D9488] to-[#0F766E]', text: 'text-[#0D9488]', btn: 'bg-[#0D9488] hover:bg-[#0F766E]', bgLight: 'bg-[#F0FDFA]' },
            { border: 'border-[#4F46E5]', badge: 'from-[#4F46E5] to-[#4338CA]', text: 'text-[#4F46E5]', btn: 'bg-[#4F46E5] hover:bg-[#4338CA]', bgLight: 'bg-[#EEF2FF]' }
        ];

        var BANGUN_DATAR_MASTER = @json($shapesMasterJson);

        var GEOMETRY_SHAPES = {
            'segitiga': { key: 'segitiga', name: 'Segitiga', defaultCiri: 'Memiliki 3 sisi & 3 titik sudut', badgeBg: 'bg-[#FFEBE6]', badgeBorder: 'border-[#E57354]', badgeText: 'text-[#E57354]', svg: '<svg class="w-4 h-4 inline-block mr-1 pointer-events-none shrink-0" viewBox="0 0 24 24" fill="currentColor"><polygon points="12,3 2,21 22,21"/></svg>' },
            'persegi': { key: 'persegi', name: 'Persegi', defaultCiri: '4 sisi sama panjang & 4 sudut siku-siku 90°', badgeBg: 'bg-[#FEF3C7]', badgeBorder: 'border-[#D97706]', badgeText: 'text-[#D97706]', svg: '<svg class="w-4 h-4 inline-block mr-1 pointer-events-none shrink-0" viewBox="0 0 24 24" fill="currentColor"><rect x="3" y="3" width="18" height="18" rx="1"/></svg>' },
            'persegi_panjang': { key: 'persegi_panjang', name: 'Persegi Panjang', defaultCiri: '2 pasang sisi sejajar & 4 sudut siku-siku 90°', badgeBg: 'bg-[#EDE9FE]', badgeBorder: 'border-[#7C3AED]', badgeText: 'text-[#7C3AED]', svg: '<svg class="w-4 h-4 inline-block mr-1 pointer-events-none shrink-0" viewBox="0 0 24 24" fill="currentColor"><rect x="2" y="5" width="20" height="14" rx="1"/></svg>' },
            'lingkaran': { key: 'lingkaran', name: 'Lingkaran', defaultCiri: '1 sisi lengkung teratur & tidak memiliki titik sudut', badgeBg: 'bg-[#E0F2FE]', badgeBorder: 'border-[#0284C7]', badgeText: 'text-[#0284C7]', svg: '<svg class="w-4 h-4 inline-block mr-1 pointer-events-none shrink-0" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="9"/></svg>' },
            'jajar_genjang': { key: 'jajar_genjang', name: 'Jajar Genjang', defaultCiri: '2 pasang sisi sejajar & sudut berhadapan sama besar', badgeBg: 'bg-[#DCFCE7]', badgeBorder: 'border-[#16A34A]', badgeText: 'text-[#16A34A]', svg: '<svg class="w-4 h-4 inline-block mr-1 pointer-events-none shrink-0" viewBox="0 0 24 24" fill="currentColor"><polygon points="7,5 22,5 17,19 2,19"/></svg>' },
            'belah_ketupat': { key: 'belah_ketupat', name: 'Belah Ketupat', defaultCiri: '4 sisi sama panjang & kedua diagonal saling tegak lurus', badgeBg: 'bg-[#FCE7F3]', badgeBorder: 'border-[#DB2777]', badgeText: 'text-[#DB2777]', svg: '<svg class="w-4 h-4 inline-block mr-1 pointer-events-none shrink-0" viewBox="0 0 24 24" fill="currentColor"><polygon points="12,2 22,12 12,22 2,12"/></svg>' },
            'layang_layang': { key: 'layang_layang', name: 'Layang-Layang', defaultCiri: '2 pasang sisi sama panjang & kedua diagonal saling tegak lurus', badgeBg: 'bg-[#FEF9C3]', badgeBorder: 'border-[#CA8A04]', badgeText: 'text-[#CA8A04]', svg: '<svg class="w-4 h-4 inline-block mr-1 pointer-events-none shrink-0" viewBox="0 0 24 24" fill="currentColor"><polygon points="12,2 20,9 12,22 4,9"/></svg>' },
            'trapesium': { key: 'trapesium', name: 'Trapesium', defaultCiri: 'Memiliki 1 pasang sisi yang sejajar', badgeBg: 'bg-[#F3E8FF]', badgeBorder: 'border-[#9333EA]', badgeText: 'text-[#9333EA]', svg: '<svg class="w-4 h-4 inline-block mr-1 pointer-events-none shrink-0" viewBox="0 0 24 24" fill="currentColor"><polygon points="6,5 18,5 22,19 2,19"/></svg>' }
        };

        if (Array.isArray(BANGUN_DATAR_MASTER)) {
            BANGUN_DATAR_MASTER.forEach(item => {
                const iconHtml = item.custom_image
                    ? `<img src="${item.custom_image}" alt="${item.name}" class="w-4 h-4 inline-block mr-1 object-contain shrink-0">`
                    : `<span class="w-4 h-4 inline-flex items-center justify-center mr-1 shrink-0" style="color:${item.color || '#E57354'}">${item.svg || ''}</span>`;
                GEOMETRY_SHAPES[item.key] = {
                    key: item.key,
                    name: item.name,
                    defaultCiri: Array.isArray(item.ciri_ciri) ? item.ciri_ciri.join('; ') : '',
                    badgeBg: 'bg-[#FFF8E7]',
                    badgeBorder: 'border-[#E9C37A]',
                    badgeText: 'text-[#8A4416]',
                    svg: iconHtml
                };
            });
        }

        var BENDA_DEFINITIONS = {
            'atap': { key: 'atap', name: 'Atap Menara', iconSvg: '<polygon points="20,2 2,28 38,28" fill="#E57354" stroke="#78350F" stroke-width="2"/>', correctShape: 'segitiga' },
            'dinding': { key: 'dinding', name: 'Dinding Gedung', iconSvg: '<rect x="4" y="4" width="32" height="22" fill="#EDE9FE" stroke="#7C3AED" stroke-width="2"/>', correctShape: 'persegi_panjang' },
            'badan': { key: 'badan', name: 'Badan Gedung', iconSvg: '<rect x="4" y="4" width="32" height="22" fill="#FEF3C7" stroke="#78350F" stroke-width="2"/>', correctShape: 'persegi_panjang' },
            'kanopi': { key: 'kanopi', name: 'Kanopi Stan', iconSvg: '<polygon points="12,6 36,6 28,24 4,24" fill="#DCFCE7" stroke="#16A34A" stroke-width="2"/>', correctShape: 'jajar_genjang' },
            'bulan': { key: 'bulan', name: 'Lentera Bulan', iconSvg: '<circle cx="20" cy="15" r="11" fill="#E0F2FE" stroke="#0284C7" stroke-width="2"/>', correctShape: 'lingkaran' },
            'rumbai': { key: 'rumbai', name: 'Rumbai Hias', iconSvg: '<polygon points="20,3 32,15 20,27 8,15" fill="#FCE7F3" stroke="#DB2777" stroke-width="2"/>', correctShape: 'belah_ketupat' },
            'kotak': { key: 'kotak', name: 'Kotak Panggung', iconSvg: '<rect x="8" y="3" width="24" height="24" rx="1" fill="#FEF3C7" stroke="#D97706" stroke-width="2"/>', correctShape: 'persegi' },
            'penyangga': { key: 'penyangga', name: 'Kaki Penyangga', iconSvg: '<polygon points="5,6 35,6 29,24 11,24" fill="#F3E8FF" stroke="#9333EA" stroke-width="2"/>', correctShape: 'trapesium' },
            'payung': { key: 'payung', name: 'Payung Festival', iconSvg: '<polygon points="20,4 4,26 36,26" fill="#FEE2E2" stroke="#EF4444" stroke-width="2"/>', correctShape: 'segitiga' }
        };

        // Mapping validasi jawaban (BENDA -> BANGUN DATAR)
        var BENDA_CORRECT_SHAPES = {
            'atap': 'segitiga',
            'dinding': 'persegi_panjang',
            'badan': 'persegi_panjang',
            'kanopi': 'jajar_genjang',
            'bulan': 'lingkaran',
            'rumbai': 'belah_ketupat',
            'kotak': 'persegi',
            'penyangga': 'trapesium',
            'payung': 'segitiga'
        };

        // 6 Baris Identifikasi: DIMULAI DALAM KEADAAN KOSONG
        var identificationRows = [
            { bendaKey: '', bendaName: '', shape: '', ciri: '' },
            { bendaKey: '', bendaName: '', shape: '', ciri: '' },
            { bendaKey: '', bendaName: '', shape: '', ciri: '' },
            { bendaKey: '', bendaName: '', shape: '', ciri: '' },
            { bendaKey: '', bendaName: '', shape: '', ciri: '' },
            { bendaKey: '', bendaName: '', shape: '', ciri: '' }
        ];

        var currentActiveKolabStage = '1';

        function applyColoringToIdentCanvas() {
            if (!currentRoomColoringData || typeof currentRoomColoringData !== 'object') return;
            Object.keys(currentRoomColoringData).forEach(partKey => {
                if (partKey.startsWith('_')) return;
                const fillColor = currentRoomColoringData[partKey];
                if (!fillColor) return;
                const rawKey = String(partKey).replace(/^review-/, '').replace(/^ident-/, '');
                const identEl = document.getElementById('ident-' + rawKey);
                if (identEl) {
                    identEl.setAttribute('fill', fillColor);
                }
            });
        }

        function goToKolabStage(stageNum) {
            stageNum = String(stageNum);

            // Stage 5 (Koreksi) telah digabung ke alur 4 slide berurutan
            if (stageNum === '5') {
                goToKolabStage(6);
                return;
            }

            currentActiveKolabStage = stageNum;

            try {
                sessionStorage.setItem('tara_kolab_stage', stageNum);
                if (currentRoomNumber) {
                    sessionStorage.setItem('tara_kolab_room', currentRoomNumber);
                }
            } catch (e) { }

            if (window.globalAudio && typeof window.globalAudio.playSfx === 'function') {
                window.globalAudio.playSfx('button_click');
            }

            document.querySelectorAll('.kolabStage').forEach(st => {
                st.classList.add('hidden');
            });

            const target = document.getElementById('kolabStage' + stageNum);
            if (target) {
                target.classList.remove('hidden');
            }
        }

        async function fetchRoomsStatus() {
            try {
                const res = await fetch('/api/kolaborasi/rooms', {
                    headers: { 'Accept': 'application/json' }
                });
                if (res.status === 401) {
                    window.location.href = '/login';
                    return;
                }
                if (!res.ok) return;
                const data = await res.json();
                if (data.status === 'success' && data.rooms) {
                    if (data.session_id && String(data.session_id) !== String(currentActiveSessionId)) {
                        currentActiveSessionId = data.session_id;
                        lastRoomsSignature = '';
                        const sessText = document.getElementById('activeSessionBadgeText');
                        if (sessText && data.active_session && data.active_session.label) {
                            sessText.textContent = 'Sesi: ' + data.active_session.label;
                        }
                    }
                    const sig = JSON.stringify(data.rooms.map(r => ({ id: r.id, count: r.member_count, full: r.is_full, completed: r.is_completed, name: r.name })));
                    if (sig !== lastRoomsSignature) {
                        lastRoomsSignature = sig;
                        renderRoomsGrid(data.rooms);
                    }
                }
            } catch (e) {
                console.error('Failed to poll rooms status:', e);
            }
        }

        function renderRoomsGrid(rooms) {
            const container = document.getElementById('roomsGridContainer');
            if (!container) return;

            let html = '';

            // Strictly render the 8 fixed room cards
            rooms.slice(0, 8).forEach((room, idx) => {
                const num = room.room_number;
                const c = roomColorThemes[idx % roomColorThemes.length];
                const memberCount = room.member_count;
                const isCompleted = !!room.is_completed;
                const isFull = room.is_full;
                const isDisabled = isCompleted || isFull;
                const percent = isCompleted ? 100 : Math.min(100, Math.round((memberCount / room.max_members) * 100));

                html += `
                        <div id="roomCard-${num}" class="group bg-white rounded-xl sm:rounded-2xl border-2 sm:border-[2.5px] ${c.border} p-2 sm:p-2.5 md:p-3 flex flex-col justify-between shadow-[0_4px_12px_rgba(0,0,0,0.04)] hover:shadow-[0_8px_20px_rgba(0,0,0,0.1)] transition-all min-w-0">
                            <!-- Header: Badge Nomor + Nama & Tema -->
                            <div>
                                <div class="flex items-center gap-2 sm:gap-2.5">
                                    <div class="w-7 h-7 sm:w-8 sm:h-8 md:w-9 md:h-9 rounded-lg sm:rounded-xl bg-gradient-to-br ${c.badge} text-white font-black text-xs sm:text-sm md:text-base flex items-center justify-center shrink-0 shadow-xs">
                                        ${num}
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h4 class="font-bold text-[11px] sm:text-xs md:text-sm text-[#3D1804] truncate leading-tight" title="${room.name}">
                                            ${room.name}
                                        </h4>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer: Kuota, Bar, & Tombol Aksi -->
                            <div class="mt-1.5 sm:mt-2 pt-1.5 border-t border-[#E9C37A]/35 space-y-1 sm:space-y-1.5">
                                <div class="flex items-center justify-between text-[9px] sm:text-[10px] md:text-[11px] font-semibold text-[#6D4C33] leading-none">
                                    <span class="flex items-center gap-1 text-[#8A4416]">
                                        <svg class="w-3 h-3 text-[#8A4416]/80 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span>${isCompleted ? 'Status' : 'Kuota'}</span>
                                    </span>
                                    <span class="${isCompleted ? 'text-emerald-800 bg-emerald-100 border-emerald-300' : (isFull ? 'text-[#DC2626] bg-red-50 border-red-200' : 'text-[#15803D] bg-emerald-50 border-emerald-200')} font-bold text-[9px] sm:text-[10px] px-1.5 py-0.5 rounded border shrink-0">
                                        ${isCompleted ? 'Sudah Selesai' : `${memberCount}/${room.max_members} ${isFull ? 'Penuh' : 'Siswa'}`}
                                    </span>
                                </div>
                                
                                <div class="w-full h-1.5 rounded-full bg-[#E5E7EB] overflow-hidden">
                                    <div class="h-full ${isCompleted ? 'bg-emerald-500' : (isFull ? 'bg-[#DC2626]' : 'bg-[#22C55E]')} rounded-full transition-all duration-300" style="width: ${percent}%;"></div>
                                </div>
                                
                                <button type="button" 
                                        onclick="${isDisabled ? '' : 'joinRoomOnline(' + num + ')'}" 
                                        class="w-full py-1.5 sm:py-2 px-2 rounded-xl ${isCompleted ? 'bg-emerald-600/85 text-white cursor-not-allowed' : (isFull ? 'bg-[#9CA3AF] text-white cursor-not-allowed opacity-80' : c.btn + ' text-white cursor-pointer hover:shadow-sm active:scale-95')} font-bold text-[10px] sm:text-xs shadow-xs transition-all min-h-[30px] sm:min-h-[34px] flex items-center justify-center gap-1 truncate">
                                    <span>${isCompleted ? 'Sudah Selesai' : (isFull ? 'Ruang Penuh' : 'Masuk Ruang')}</span>
                                    ${!isDisabled ? `<svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>` : ''}
                                </button>
                            </div>
                        </div>
                    `;
            });

            container.innerHTML = html;
        }

        function syncAllRoomHeaders(roomName, roomTheme) {
            document.querySelectorAll('.roomNameBadge').forEach(el => {
                el.textContent = roomName || 'Ruang Kolaborasi';
            });
        }

        async function joinRoomOnline(roomNum) {
            if (window.globalAudio && typeof window.globalAudio.playSfx === 'function') {
                window.globalAudio.playSfx('button_click');
            }

            try {
                const token = getCsrfToken();
                const res = await fetch('/api/kolaborasi/join', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({ room_number: roomNum, _token: token })
                });

                const data = await res.json();

                if (!res.ok || data.status === 'error') {
                    var toast = document.getElementById('warningPenuhToast');
                    if (toast) {
                        toast.textContent = data.message || 'Maaf, Ruang sudah penuh (maksimal 5 siswa).';
                        toast.classList.remove('hidden');
                        setTimeout(() => toast.classList.add('hidden'), 3500);
                    }
                    return;
                }

                currentRoomNumber = roomNum;
                evalRowStates = createEmptyEvalRowStates();
                try {
                    sessionStorage.setItem('tara_kolab_room', roomNum);
                } catch (e) { }

                if (data.room) {
                    syncAllRoomHeaders(data.room.name, data.room.theme);

                    const labelNama = document.getElementById('labelNamaRuang2');
                    const labelTema = document.getElementById('labelTemaRuang2');
                    if (labelNama) labelNama.textContent = data.room.name;
                    if (labelTema) labelTema.textContent = data.room.theme;

                    const refImg = document.getElementById('motifRefImg');
                    if (refImg && (themeImages[data.room.theme] || themeImages[roomNum])) {
                        refImg.src = themeImages[data.room.theme] || themeImages[roomNum];
                    }
                }

                goToKolabStage(3);
                loadRoomOnlineData(roomNum, true);
            } catch (err) {
                console.error('Error joining room:', err);
            }
        }

        async function leaveRoomOnline() {
            if (window.globalAudio && typeof window.globalAudio.playSfx === 'function') {
                window.globalAudio.playSfx('button_click');
            }

            try {
                const token = getCsrfToken();
                await fetch('/api/kolaborasi/leave', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({ _token: token })
                });
            } catch (e) { }

            if (liveColorPollingTimer) clearInterval(liveColorPollingTimer);
            currentRoomNumber = null;
            roomMembersList = [];
            evalRowStates = createEmptyEvalRowStates();
            currentRoomColoringData = {};
            try {
                sessionStorage.removeItem('tara_kolab_room');
                sessionStorage.removeItem('tara_kolab_stage');
            } catch (e) { }
            goToKolabStage(1);
            fetchRoomsStatus();
        }

        async function loadRoomOnlineData(roomNum, isInitial) {
            try {
                const res = await fetch(`/api/kolaborasi/room/${roomNum}/data`, {
                    headers: { 'Accept': 'application/json' }
                });
                if (res.status === 401) {
                    window.location.href = '/login';
                    return;
                }
                if (!res.ok) {
                    if (res.status === 422) {
                        const errData = await res.json().catch(() => ({}));
                        if (errData && errData.message) {
                            alert(errData.message);
                        }
                        leaveCurrentRoom();
                    }
                    return;
                }
                const data = await res.json();

                if (data.session_id && String(data.session_id) !== String(currentActiveSessionId)) {
                    currentActiveSessionId = data.session_id;
                    currentRoomNumber = null;
                    roomMembersList = [];
                    evalRowStates = createEmptyEvalRowStates();
                    currentRoomColoringData = {};
                    try {
                        sessionStorage.removeItem('tara_kolab_room');
                        sessionStorage.removeItem('tara_kolab_stage');
                    } catch (e) { }
                    goToKolabStage(1);
                    fetchRoomsStatus();
                    showKolabRefreshToast('Sesi kolaborasi telah diperbarui oleh guru/admin!');
                    return;
                }

                if (data.status === 'completed') {
                    currentRoomNumber = null;
                    roomMembersList = [];
                    evalRowStates = createEmptyEvalRowStates();
                    currentRoomColoringData = {};
                    try {
                        sessionStorage.removeItem('tara_kolab_room');
                        sessionStorage.removeItem('tara_kolab_stage');
                    } catch (e) { }
                    openSuccessSubmitModal(data.message || 'Tugas kelompok telah dikumpulkan bersama tim!');
                    return;
                }

                if (data.status === 'success') {
                    roomMembersList = data.members || [];

                    // Ensure current user is always included in roomMembersList
                    const hasMeInList = roomMembersList.some(m => m && String(m.user_id) === String(currentUserId));
                    if (!hasMeInList && currentUserId) {
                        roomMembersList.push({
                            id: 999999999,
                            user_id: currentUserId,
                            user_name: currentUserName,
                            no_absen: currentUserNoAbsen,
                            role: 'Kolaborator'
                        });
                    }

                    // Urutkan anggota berdasarkan urutan pertama kali masuk ke ruang (paling awal masuk di paling atas)
                    roomMembersList.sort((a, b) => {
                        const idA = a && a.id !== undefined && a.id !== null ? Number(a.id) : 999999999;
                        const idB = b && b.id !== undefined && b.id !== null ? Number(b.id) : 999999999;
                        if (idA !== idB) return idA - idB;
                        if (a && a.created_at && b && b.created_at) {
                            return new Date(a.created_at) - new Date(b.created_at);
                        }
                        return 0;
                    });

                    const membersSignature = JSON.stringify(roomMembersList.map(m => ({ id: m.user_id, name: m.user_name, absen: m.no_absen, role: m.role })));

                    const badge2 = document.getElementById('onlineMembersBadge2');
                    if (badge2) {
                        badge2.innerHTML = '<span class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-emerald-500 inline-block mr-1"></span>Online: ' + Math.max(roomMembersList.length, 1) + ' Siswa';
                    }

                    // Populate group name and leader if any
                    if (document.activeElement !== document.getElementById('inputNamaKelompok')) {
                        const grpInput = document.getElementById('inputNamaKelompok');
                        if (grpInput) grpInput.value = data.room.group_name || '';
                    }
                    if (document.activeElement !== document.getElementById('inputKetuaTim')) {
                        const ldrInput = document.getElementById('inputKetuaTim');
                        if (ldrInput) ldrInput.value = data.room.leader_name || '';
                    }

                    // Render dynamic Table 1 (Pembagian Tugas) & Table 3 (Evaluasi)
                    if (isInitial || membersSignature !== lastRenderedMembersSignature) {
                        renderTable1Tasks(roomMembersList);
                        renderTable3Evaluations(roomMembersList, data.evaluation_data || [], isInitial);
                        lastRenderedMembersSignature = membersSignature;
                    }

                    // Apply coloring data (across 4 canvases)
                    if (data.coloring_data && typeof data.coloring_data === 'object' && Object.keys(data.coloring_data).length > 0) {
                        currentRoomColoringData = { ...data.coloring_data };
                        Object.keys(currentRoomColoringData).forEach(partKey => {
                            if (partKey.startsWith('_')) return;
                            const fillColor = currentRoomColoringData[partKey];
                            const rawKey = String(partKey).replace(/^review-/, '').replace(/^ident-/, '');
                            const el = document.getElementById(rawKey);
                            if (el) el.setAttribute('fill', fillColor);

                            const revEl = document.getElementById('review-' + rawKey);
                            if (revEl) revEl.setAttribute('fill', fillColor);

                            const identEl = document.getElementById('ident-' + rawKey);
                            if (identEl) identEl.setAttribute('fill', fillColor);
                        });
                    } else if (isInitial) {
                        currentRoomColoringData = {};
                        document.querySelectorAll('.canvas-svg-viewport .colorable-part').forEach(el => {
                            el.setAttribute('fill', 'white');
                        });
                        document.querySelectorAll('.review-svg-viewport .review-part').forEach(el => {
                            el.setAttribute('fill', 'white');
                        });
                        document.querySelectorAll('.ident-canvas-svg .ident-drag-part').forEach(el => {
                            el.setAttribute('fill', 'white');
                        });
                    }

                    if (isInitial && roomNum) {
                        const defaultCanvasNum = (roomNum >= 1 && roomNum <= 4) ? roomNum : 1;
                        selectColoringCanvas(defaultCanvasNum);
                        selectReviewCanvas(defaultCanvasNum);
                    }

                    // Apply identification data (Only re-render if data changed from peer and user is not typing)
                    let identList = data.identification_data;
                    if (identList && typeof identList === 'object') {
                        if (!Array.isArray(identList)) {
                            identList = Object.values(identList);
                        }
                        if (identList.length > 0) {
                            const newIdentSig = JSON.stringify(identList);
                            if (isInitial || (newIdentSig !== lastIdentificationSignature && !document.getElementById('tbodyIdentificationForm')?.contains(document.activeElement))) {
                                identificationRows = identList;
                                lastIdentificationSignature = newIdentSig;
                                renderIdentificationForm();
                            }
                        }
                    } else if (isInitial) {
                        identificationRows = [
                            { bendaKey: '', bendaName: '', shape: '', ciri: '' },
                            { bendaKey: '', bendaName: '', shape: '', ciri: '' },
                            { bendaKey: '', bendaName: '', shape: '', ciri: '' },
                            { bendaKey: '', bendaName: '', shape: '', ciri: '' },
                            { bendaKey: '', bendaName: '', shape: '', ciri: '' },
                            { bendaKey: '', bendaName: '', shape: '', ciri: '' }
                        ];
                        lastIdentificationSignature = JSON.stringify(identificationRows);
                        renderIdentificationForm();
                    }
                    syncReviewIdentification();
                }
            } catch (e) {
                console.error('Failed to load room data:', e);
            }
        }

        function renderTable1Tasks(members) {
            const tbody = document.getElementById('tbodyTasksList');
            if (!tbody) return;

            let memberList = Array.isArray(members) ? [...members] : [];

            // Make sure current user is always included in the table
            const hasMe = memberList.some(m => m && String(m.user_id) === String(currentUserId));
            if (!hasMe && currentUserId) {
                memberList.push({
                    id: 999999999,
                    user_id: currentUserId,
                    user_name: currentUserName,
                    no_absen: currentUserNoAbsen,
                    role: 'Kolaborator'
                });
            }

            // Urutkan anggota berdasarkan urutan paling awal masuk ke ruang (id / created_at terkecil di paling atas)
            memberList.sort((a, b) => {
                const idA = a && a.id !== undefined && a.id !== null ? Number(a.id) : 999999999;
                const idB = b && b.id !== undefined && b.id !== null ? Number(b.id) : 999999999;
                if (idA !== idB) return idA - idB;
                if (a && a.created_at && b && b.created_at) {
                    return new Date(a.created_at) - new Date(b.created_at);
                }
                return 0;
            });

            let html = '';
            for (let i = 0; i < 5; i++) {
                const member = memberList[i];
                if (member) {
                    const isMe = String(member.user_id) === String(currentUserId);

                    html += `
                            <tr class="hover:bg-[#FFF8E7] transition-colors ${isMe ? 'bg-[#FFEBE6]/50 font-bold' : ''}">
                                <td class="p-1.5 sm:p-2 text-center font-bold text-[#8A4416] text-[11px] sm:text-xs">${i + 1}</td>
                                <td class="p-1.5 sm:p-2">
                                    <div class="flex items-center gap-1 sm:gap-1.5 flex-wrap">
                                        <span class="text-[11px] sm:text-xs text-[#3D1804] font-bold">${member.user_name}</span>
                                        ${isMe ? '<span class="text-[9px] bg-[#E57354] text-white px-1.5 py-0.2 rounded-full font-bold">Saya</span>' : ''}
                                    </div>
                                </td>
                                <td class="p-1.5 sm:p-2 text-center text-[#8A4416] font-bold text-[11px] sm:text-xs">
                                    ${member.no_absen || '-'}
                                </td>
                                <td class="p-1.5 sm:p-2 text-center">
                                    <span class="inline-flex items-center gap-1 text-[10px] sm:text-[11px] bg-emerald-100 text-emerald-800 px-2.5 py-0.5 rounded-full font-bold border border-emerald-300 shadow-2xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        <span>Siap Berkolaborasi</span>
                                    </span>
                                </td>
                            </tr>
                        `;
                } else {
                    html += `
                            <tr class="bg-stone-50/60 opacity-60">
                                <td class="p-1.5 sm:p-2 text-center font-bold text-stone-400 text-[11px] sm:text-xs">${i + 1}</td>
                                <td class="p-1.5 sm:p-2 text-[11px] sm:text-xs text-stone-400 italic">Menunggu teman...</td>
                                <td class="p-1.5 sm:p-2 text-center text-xs text-stone-400">-</td>
                                <td class="p-1.5 sm:p-2 text-center text-[10px] text-stone-400 italic">Slot kosong</td>
                            </tr>
                        `;
                }
            }
            tbody.innerHTML = html;
        }

        function escapeHtmlAttr(val) {
            return String(val ?? '')
                .replace(/&/g, '&amp;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;');
        }

        function escapeHtmlText(val) {
            return String(val ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;');
        }

        function captureEvalDomState() {
            for (let idx = 0; idx < TOTAL_EVAL_ROWS; idx++) {
                if (!evalRowStates[idx]) {
                    evalRowStates[idx] = { peerId: '', name: '', noAbsen: '-', isManual: false, manualSkipped: false, rating: 'Sangat Baik', note: '' };
                }
                const noteEl = document.getElementById(`evalNote-${idx}`);
                const rateEl = document.getElementById(`evalRating-${idx}`);
                const nameEl = document.getElementById(`evalName-${idx}`);
                const absenEl = document.getElementById(`evalAbsen-${idx}`);

                if (noteEl) {
                    evalRowStates[idx].note = noteEl.value || '';
                }
                if (rateEl && rateEl.value) {
                    evalRowStates[idx].rating = rateEl.value;
                }
                if (nameEl) {
                    const isManualInput = nameEl.tagName === 'INPUT' && nameEl.type === 'text';
                    evalRowStates[idx].isManual = isManualInput;
                    evalRowStates[idx].peerId = nameEl.dataset.peerId || '';
                    evalRowStates[idx].name = nameEl.value || '';
                }
                if (absenEl) {
                    evalRowStates[idx].noAbsen = absenEl.value || '-';
                }
            }
        }

        function renderTable3Evaluations(members, savedEvaluations, isInitial = false) {
            const tbody = document.getElementById('tbodyEvaluationList');
            if (!tbody) return;
            closeAllEvalDropdowns();
            closeAllEvalPeerDropdowns();

            const allPeers = (members || []).filter(m => m && String(m.user_id) !== String(currentUserId));
            const peerById = new Map(allPeers.map(p => [String(p.user_id), p]));

            if (isInitial) {
                evalRowStates = createEmptyEvalRowStates();
                if (Array.isArray(savedEvaluations)) {
                    const mySaved = savedEvaluations.filter(ev => ev && (!ev.evaluator_id || String(ev.evaluator_id) === String(currentUserId)));
                    for (let idx = 0; idx < TOTAL_EVAL_ROWS; idx++) {
                        const ev = mySaved[idx];
                        if (!ev) continue;
                        const state = evalRowStates[idx];
                        const savedPeerId = ev.peer_id ? String(ev.peer_id) : '';
                        if (savedPeerId && peerById.has(savedPeerId)) {
                            const matchedPeer = peerById.get(savedPeerId);
                            state.peerId = savedPeerId;
                            state.name = matchedPeer.user_name;
                            state.noAbsen = matchedPeer.no_absen || '-';
                            state.isManual = false;
                            state.manualSkipped = false;
                        } else if (ev.peer_name && !/^Teman Anggota \d+$/i.test(String(ev.peer_name).trim())) {
                            state.peerId = '';
                            state.name = String(ev.peer_name);
                            state.noAbsen = ev.peer_no_absen || '-';
                            state.isManual = true;
                            state.manualSkipped = true;
                        }
                        if (ev.rating) state.rating = ev.rating;
                        if (ev.note) state.note = ev.note;
                    }
                }
            } else {
                captureEvalDomState();
            }

            // Sinkronkan nama/absen terbaru teman yang masih berada di ruang, atau kosongkan slot jika teman keluar sebelum ada catatan
            for (let idx = 0; idx < TOTAL_EVAL_ROWS; idx++) {
                const state = evalRowStates[idx];
                if (!state.isManual && state.peerId) {
                    const activePeer = peerById.get(String(state.peerId));
                    if (activePeer) {
                        state.name = activePeer.user_name;
                        state.noAbsen = activePeer.no_absen || '-';
                    } else if (!state.note.trim()) {
                        state.peerId = '';
                        state.name = '';
                        state.noAbsen = '-';
                    }
                }
            }

            // Pilih otomatis 4 anggota kelompok berbeda secara berurutan (1 teman per baris dalam ruang yang sama)
            const usedPeerIds = new Set();
            for (let idx = 0; idx < TOTAL_EVAL_ROWS; idx++) {
                const state = evalRowStates[idx];
                if (!state.isManual && state.peerId && peerById.has(String(state.peerId))) {
                    usedPeerIds.add(String(state.peerId));
                }
            }

            for (let idx = 0; idx < TOTAL_EVAL_ROWS; idx++) {
                const state = evalRowStates[idx];
                if (!state.isManual && !state.manualSkipped && !state.peerId) {
                    const nextPeer = allPeers.find(p => !usedPeerIds.has(String(p.user_id)));
                    if (nextPeer) {
                        state.peerId = String(nextPeer.user_id);
                        state.name = nextPeer.user_name;
                        state.noAbsen = nextPeer.no_absen || '-';
                        usedPeerIds.add(state.peerId);
                    }
                }
            }

            let html = '';

            for (let idx = 0; idx < TOTAL_EVAL_ROWS; idx++) {
                const state = evalRowStates[idx];
                const selectedPeerId = state.peerId || '';
                const matchedPeer = selectedPeerId ? peerById.get(String(selectedPeerId)) : null;
                const displayName = matchedPeer ? matchedPeer.user_name : (state.name || '');
                const displayAbsen = matchedPeer ? (matchedPeer.no_absen || '-') : (state.noAbsen || '-');
                const prevRating = state.rating || 'Sangat Baik';
                const prevNote = state.note || '';

                const peerMenuItems = [
                    `<button type="button"
                            data-peer-id=""
                            onclick="selectEvalPeer(event, ${idx}, '')"
                            class="w-full text-left px-3 py-2 flex items-center justify-between gap-2 hover:bg-[#F0FDF4] text-[#6D4C33] transition-colors cursor-pointer ${!selectedPeerId && !state.isManual ? 'bg-[#DCFCE7] font-bold text-[#15803D]' : ''}">
                        <span>-- Pilih Teman --</span>
                    </button>`,
                    ...allPeers.map(p => {
                        const pid = String(p.user_id);
                        const isSelectedHere = pid === String(selectedPeerId);
                        const assignedOtherIdx = evalRowStates.findIndex((s, rIdx) => rIdx !== idx && !s.isManual && String(s.peerId) === pid);
                        return `
                            <button type="button"
                                    data-peer-id="${escapeHtmlAttr(pid)}"
                                    onclick="selectEvalPeer(event, ${idx}, '${escapeHtmlAttr(pid)}')"
                                    class="w-full text-left px-3 py-2 flex items-center justify-between gap-2 hover:bg-[#F0FDF4] transition-colors cursor-pointer ${isSelectedHere ? 'bg-[#DCFCE7] font-bold text-[#15803D]' : 'text-[#3D1804]'}">
                                <span class="truncate">${escapeHtmlText(p.user_name)}</span>
                                <span class="text-[10px] px-1.5 py-0.5 rounded bg-[#FFF2D6] text-[#8A4416] border border-[#E9C37A] shrink-0">
                                    Absen ${escapeHtmlText(p.no_absen || '-')}${assignedOtherIdx !== -1 ? ` • #${assignedOtherIdx + 1}` : ''}
                                </span>
                            </button>
                        `;
                    }),
                    `<div class="border-t border-[#E9C37A]/50 mt-1 pt-1">
                        <button type="button"
                                onclick="enableEvalManualMode(event, ${idx})"
                                class="w-full text-left px-3 py-2 flex items-center gap-1.5 hover:bg-[#FFF2D6] text-[#8A4416] transition-colors cursor-pointer">
                            <span>✎ Isi Nama Manual...</span>
                        </button>
                    </div>`
                ].join('');

                html += `
                        <tr class="hover:bg-[#FFF8E7] transition-colors">
                            <td class="p-2 sm:p-2.5 text-center font-bold text-[#548466] text-xs sm:text-sm">${idx + 1}</td>
                            <td class="p-2 sm:p-2.5">
                                ${state.isManual ? `
                                    <div class="flex items-center gap-1.5">
                                        <input type="text"
                                               id="evalName-${idx}"
                                               data-peer-id=""
                                               value="${escapeHtmlAttr(state.name)}"
                                               oninput="autoSaveRoomData()"
                                               placeholder="Ketik nama teman..."
                                               class="w-full min-w-0 bg-[#FFFDF5] border border-[#E9C37A]/80 rounded-lg px-2.5 py-1.5 text-xs sm:text-sm font-bold text-[#3D1804] outline-none focus:border-[#548466]">
                                        <button type="button"
                                                onclick="enableEvalDropdownMode(${idx})"
                                                title="Pilih dari anggota ruang"
                                                class="shrink-0 p-1.5 rounded-lg bg-[#FFF2D6] border border-[#E9C37A] text-[#8A4416] hover:bg-[#FFE8B8] cursor-pointer transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                    </div>
                                ` : `
                                    <div class="relative custom-eval-peer-dropdown w-full" data-eval-peer-idx="${idx}">
                                        <input type="hidden" id="evalName-${idx}" data-peer-id="${escapeHtmlAttr(selectedPeerId)}" value="${escapeHtmlAttr(displayName)}">
                                        <button type="button"
                                                id="evalPeerBtn-${idx}"
                                                onclick="toggleEvalPeerDropdown(event, ${idx})"
                                                class="w-full flex items-center justify-between gap-1.5 bg-[#FFFDF5] border border-[#E9C37A]/80 rounded-lg px-2.5 py-1.5 text-xs sm:text-sm font-bold ${displayName ? 'text-[#3D1804]' : 'text-[#8A4416]/75'} outline-none cursor-pointer hover:bg-[#F0FDF4] active:scale-[0.98] transition-all text-left">
                                            <span id="evalPeerLabel-${idx}" class="truncate">${displayName ? escapeHtmlText(displayName) : (allPeers.length > 0 ? '-- Pilih Teman --' : 'Menunggu teman...')}</span>
                                            <svg id="evalPeerArrow-${idx}" class="w-3.5 h-3.5 text-[#548466] shrink-0 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                        <div id="evalPeerMenu-${idx}"
                                             class="eval-peer-menu hidden fixed z-[9999] bg-[#FFFDF5] border-2 border-[#548466]/40 rounded-xl shadow-lg overflow-y-auto py-1 text-xs sm:text-sm font-bold pointer-events-auto"
                                             style="max-height: 240px;">
                                            ${peerMenuItems}
                                        </div>
                                    </div>
                                `}
                            </td>
                            <td class="p-2 sm:p-2.5 text-center font-bold text-[#548466] text-xs sm:text-sm">
                                ${state.isManual ? `
                                    <input type="text"
                                           id="evalAbsen-${idx}"
                                           data-peer-id=""
                                           value="${escapeHtmlAttr(state.noAbsen === '-' ? '' : state.noAbsen)}"
                                           oninput="autoSaveRoomData()"
                                           placeholder="-"
                                           class="w-16 mx-auto text-center bg-[#FFFDF5] border border-[#E9C37A]/80 rounded-lg px-1 py-1.5 text-xs sm:text-sm font-bold text-[#548466] outline-none focus:border-[#548466]">
                                ` : `
                                    <span id="evalAbsenDisplay-${idx}">${escapeHtmlText(displayAbsen)}</span>
                                    <input type="hidden" id="evalAbsen-${idx}" data-peer-id="${escapeHtmlAttr(selectedPeerId)}" value="${escapeHtmlAttr(displayAbsen)}">
                                `}
                            </td>
                            <td class="p-2 sm:p-2.5">
                                <div class="relative custom-eval-dropdown w-full" data-eval-idx="${idx}">
                                    <input type="hidden" id="evalRating-${idx}" data-peer-id="${escapeHtmlAttr(selectedPeerId)}" value="${escapeHtmlAttr(prevRating)}">
                                    <button type="button" 
                                            id="evalRatingBtn-${idx}"
                                            onclick="toggleEvalDropdown(event, ${idx})" 
                                            class="w-full flex items-center justify-between gap-1 bg-[#FFFDF5] border border-[#E9C37A]/80 rounded-lg px-2.5 py-1.5 text-xs sm:text-sm font-bold text-[#548466] outline-none cursor-pointer hover:bg-[#F0FDF4] active:scale-[0.98] transition-all text-left">
                                        <span id="evalRatingLabel-${idx}" class="truncate">${escapeHtmlText(prevRating)}</span>
                                        <svg class="w-3.5 h-3.5 text-[#548466] shrink-0 transition-transform duration-200" id="evalArrow-${idx}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    <div id="evalMenu-${idx}" 
                                         class="eval-menu hidden fixed z-[9999] bg-[#FFFDF5] border-2 border-[#548466]/40 rounded-xl shadow-lg overflow-hidden py-1 text-xs sm:text-sm font-bold pointer-events-auto">
                                        <button type="button" 
                                                data-rating="Sangat Baik"
                                                onclick="selectEvalRating(event, ${idx}, 'Sangat Baik')" 
                                                class="w-full text-left px-2.5 py-1.5 flex items-center gap-1.5 hover:bg-[#F0FDF4] text-[#548466] transition-colors cursor-pointer ${prevRating === 'Sangat Baik' ? 'bg-[#DCFCE7] font-bold text-[#15803D]' : ''}">
                                            <span>Sangat Baik</span>
                                        </button>
                                        <button type="button" 
                                                data-rating="Baik"
                                                onclick="selectEvalRating(event, ${idx}, 'Baik')" 
                                                class="w-full text-left px-2.5 py-1.5 flex items-center gap-1.5 hover:bg-[#F0FDF4] text-[#548466] transition-colors cursor-pointer ${prevRating === 'Baik' ? 'bg-[#DCFCE7] font-bold text-[#15803D]' : ''}">
                                            <span>Baik</span>
                                        </button>
                                        <button type="button" 
                                                data-rating="Cukup Baik"
                                                onclick="selectEvalRating(event, ${idx}, 'Cukup Baik')" 
                                                class="w-full text-left px-2.5 py-1.5 flex items-center gap-1.5 hover:bg-[#F0FDF4] text-[#548466] transition-colors cursor-pointer ${prevRating === 'Cukup Baik' ? 'bg-[#DCFCE7] font-bold text-[#15803D]' : ''}">
                                            <span>Cukup Baik</span>
                                        </button>
                                    </div>
                                </div>
                            </td>
                            <td class="p-2 sm:p-2.5">
                                <input type="text" 
                                       id="evalNote-${idx}" 
                                       data-peer-id="${escapeHtmlAttr(selectedPeerId)}"
                                       value="${escapeHtmlAttr(prevNote)}"
                                       oninput="autoSaveRoomData()"
                                       placeholder="${displayName ? `Tuliskan apresiasi tulus untuk ${escapeHtmlAttr(displayName)}...` : 'Tuliskan apresiasi tulus...'}" 
                                       class="w-full bg-[#FFFDF5] border border-[#E9C37A]/60 rounded-lg px-2.5 py-1.5 text-xs sm:text-sm outline-none focus:border-[#548466]">
                            </td>
                        </tr>
                    `;
            }
            tbody.innerHTML = html;
        }

        // ==========================================
        // STAGE 4B: DRAG & DROP BENDA KE TABEL IDENTIFIKASI
        // ==========================================
        var activeSelectedBendaKey = null;
        var activeSelectedBendaName = null;
        window.windowDraggedBendaKey = null;
        window.windowDraggedBendaName = null;

        var pointerDragState = {
            isDragging: false,
            startX: 0,
            startY: 0,
            bendaKey: null,
            bendaName: null,
            canvasNum: null,
            avatarEl: null,
            targetRowIdx: null,
            sourceEl: null
        };

        var activeSelectedSvgEl = null;

        function getSvgElementSnippet(el) {
            if (!el) return '<rect width="24" height="24" fill="#FEF3C7" stroke="#E57354"/>';
            try {
                let bbox;
                if (typeof el.getBBox === 'function') {
                    bbox = el.getBBox();
                }
                if (!bbox || !bbox.width || !bbox.height || isNaN(bbox.width)) {
                    const w = parseFloat(el.getAttribute('width')) || 40;
                    const h = parseFloat(el.getAttribute('height')) || 40;
                    const x = parseFloat(el.getAttribute('x')) || 0;
                    const y = parseFloat(el.getAttribute('y')) || 0;
                    bbox = { x, y, width: w, height: h };
                }
                const pad = 3;
                const vx = Math.round((bbox.x || 0) - pad);
                const vy = Math.round((bbox.y || 0) - pad);
                const vw = Math.max(14, Math.round((bbox.width || 40) + pad * 2));
                const vh = Math.max(14, Math.round((bbox.height || 40) + pad * 2));

                const actualFill = el.getAttribute('fill') || '#FEF3C7';
                const clone = el.cloneNode(true);
                clone.removeAttribute('id');
                clone.removeAttribute('class');
                clone.removeAttribute('style');
                clone.setAttribute('fill', (actualFill && actualFill !== 'none') ? actualFill : '#FEF3C7');
                clone.setAttribute('stroke', '#E57354');
                clone.setAttribute('stroke-width', '2.5');

                return `<svg viewBox="${vx} ${vy} ${vw} ${vh}" class="w-full h-full">${clone.outerHTML}</svg>`;
            } catch (err) {
                return `<svg viewBox="0 0 60 60" class="w-full h-full">${el.outerHTML}</svg>`;
            }
        }

        var currentIdentCanvasIndex = 1;

        function setIdentCanvas(idx) {
            if (idx < 1) idx = 4;
            if (idx > 4) idx = 1;
            currentIdentCanvasIndex = idx;

            for (let i = 1; i <= 4; i++) {
                const slide = document.getElementById(`identCanvasSlide-${i}`);
                const pill = document.getElementById(`identPill-${i}`);
                if (slide) {
                    if (i === idx) {
                        slide.classList.remove('hidden');
                        slide.classList.add('flex');
                        slide.style.setProperty('display', 'flex', 'important');
                    } else {
                        slide.classList.add('hidden');
                        slide.classList.remove('flex');
                        slide.style.setProperty('display', 'none', 'important');
                    }
                }
                if (pill) {
                    if (i === idx) {
                        pill.className = 'ident-canvas-pill h-6.5 sm:h-7 lg:h-8 w-full rounded-md sm:rounded-lg text-[10px] sm:text-xs font-bold transition-all border-2 bg-[#E57354] text-white border-[#E57354] shadow-xs cursor-pointer flex items-center justify-center';
                    } else {
                        pill.className = 'ident-canvas-pill h-6.5 sm:h-7 lg:h-8 w-full rounded-md sm:rounded-lg text-[10px] sm:text-xs font-bold transition-all border bg-white/90 hover:bg-white text-[#8A4416] border-[#E9C37A] hover:border-[#E57354]/50 shadow-2xs cursor-pointer flex items-center justify-center';
                    }
                }
            }

            const badge = document.getElementById('identCanvasBadge');
            if (badge) {
                badge.textContent = `Sisi ${idx} dari 4`;
            }

            applyColoringToIdentCanvas();
            initSketchDragEngine();
            resetIdentCanvasZoom();
        }

        // ==========================================
        // ZOOM SCROLLABLE KANVAS MISI 2 (SEPERTI MISI 1)
        // ==========================================
        var identZoomState = { scale: 1 };

        function applyIdentZoomTransform() {
            const scale = identZoomState.scale;
            const label = document.getElementById('identZoomLabel');
            if (label) {
                label.textContent = Math.round(scale * 100) + '%';
            }

            const baseW = window.innerWidth <= 1024 ? 240 : 310;
            const baseH = window.innerWidth <= 1024 ? 220 : 340;
            const container = document.getElementById('identZoomContainer');

            if (container) {
                if (scale <= 1.01) {
                    container.style.width = '100%';
                    container.style.minHeight = '100%';
                } else {
                    container.style.width = Math.round(baseW * scale) + 'px';
                    container.style.minHeight = Math.round(baseH * scale) + 'px';
                }
            }

            document.querySelectorAll('#kolabStage4B .ident-canvas-svg svg').forEach(svg => {
                if (scale <= 1.01) {
                    svg.style.removeProperty('max-width');
                    svg.style.removeProperty('max-height');
                    svg.style.removeProperty('width');
                    svg.style.removeProperty('height');
                } else {
                    svg.style.setProperty('max-width', Math.round(baseW * scale) + 'px', 'important');
                    svg.style.setProperty('max-height', Math.round(baseH * scale) + 'px', 'important');
                    svg.style.setProperty('width', Math.round(baseW * scale) + 'px', 'important');
                    svg.style.setProperty('height', 'auto', 'important');
                }
            });
        }

        function zoomIdentCanvas(delta) {
            identZoomState.scale = Math.max(1, Math.min(2.75, Number((identZoomState.scale + delta).toFixed(2))));
            applyIdentZoomTransform();
        }

        function resetIdentCanvasZoom() {
            identZoomState = { scale: 1 };
            applyIdentZoomTransform();
            const vp = document.getElementById('identCanvasViewport');
            if (vp) {
                vp.scrollTop = 0;
                vp.scrollLeft = 0;
            }
        }

        function initIdentCanvasZoomPan() {
            const vp = document.getElementById('identCanvasViewport');
            if (!vp || vp.dataset.zoomBound === '1') return;
            vp.dataset.zoomBound = '1';

            // Ctrl + Scroll wheel zooms on desktop; regular wheel scrolls normally
            vp.addEventListener('wheel', (e) => {
                if (e.ctrlKey) {
                    e.preventDefault();
                    zoomIdentCanvas(e.deltaY < 0 ? 0.25 : -0.25);
                }
            }, { passive: false });
        }

        function prevIdentCanvas() {
            setIdentCanvas(currentIdentCanvasIndex - 1);
        }

        function nextIdentCanvas() {
            setIdentCanvas(currentIdentCanvasIndex + 1);
        }

        function isMobileLandscapeRotated() {
            return window.matchMedia('(max-width: 1024px) and (orientation: portrait)').matches ||
                (window.innerWidth <= 1024 && window.innerHeight > window.innerWidth);
        }

        function initSketchDragEngine() {
            const draggableElements = document.querySelectorAll('#kolabStage4B .ident-drag-part, #kolabStage4B [data-benda]');
            draggableElements.forEach(el => {
                el.style.touchAction = 'pan-x pan-y';
                el.removeEventListener('pointerdown', handlePointerDragStart);
                el.addEventListener('pointerdown', handlePointerDragStart);
                el.removeEventListener('click', handleSvgPartClick);
                el.addEventListener('click', handleSvgPartClick);
            });
        }

        function handleSvgPartClick(e) {
            e.stopPropagation();
            const targetEl = e.currentTarget || e.target.closest('.ident-drag-part');
            if (!targetEl || (targetEl.id && targetEl.id.endsWith('-p0'))) return;

            if (activeSelectedSvgEl === targetEl) {
                activeSelectedSvgEl = null;
                document.querySelectorAll('.ident-drag-part').forEach(p => p.classList.remove('is-selected'));
            } else {
                activeSelectedSvgEl = targetEl;
                document.querySelectorAll('.ident-drag-part').forEach(p => p.classList.remove('is-selected'));
                targetEl.classList.add('is-selected');
            }
            renderIdentificationForm();
        }

        function handlePointerDragStart(e) {
            if (e.pointerType && e.pointerType !== 'mouse' && e.pointerType !== 'pen') return;
            if (e.button !== undefined && e.button !== 0) return;

            const targetEl = e.currentTarget || e.target.closest('.ident-drag-part') || e.target.closest('[data-benda]');
            if (!targetEl || (targetEl.id && targetEl.id.endsWith('-p0'))) return;

            const partId = targetEl.id || targetEl.getAttribute('data-benda') || ('svg-shape-' + Date.now());
            const canvasNum = targetEl.getAttribute('data-canvas') || (partId.includes('c1-') ? '1' : partId.includes('c2-') ? '2' : partId.includes('c3-') ? '3' : partId.includes('c4-') ? '4' : '1');
            const bendaName = 'Bentuk dari Sisi ' + canvasNum;

            pointerDragState = {
                isDragging: false,
                startX: e.clientX,
                startY: e.clientY,
                pointerId: e.pointerId,
                bendaKey: partId,
                bendaName: bendaName,
                canvasNum: canvasNum,
                avatarEl: null,
                targetRowIdx: null,
                sourceEl: targetEl
            };

            if (targetEl.setPointerCapture && e.pointerId) {
                try {
                    targetEl.setPointerCapture(e.pointerId);
                } catch (err) { }
            }

            window.addEventListener('pointermove', handlePointerDragMove, { passive: false });
            window.addEventListener('pointerup', handlePointerDragEnd);
            window.addEventListener('pointercancel', handlePointerDragEnd);
        }

        function handlePointerDragMove(e) {
            if (!pointerDragState.bendaKey) return;

            const dx = e.clientX - pointerDragState.startX;
            const dy = e.clientY - pointerDragState.startY;
            const dist = Math.sqrt(dx * dx + dy * dy);

            if (!pointerDragState.isDragging && dist > 4) {
                pointerDragState.isDragging = true;
                if (e.cancelable) e.preventDefault();

                const snippet = getSvgElementSnippet(pointerDragState.sourceEl);
                const avatar = document.createElement('div');
                avatar.id = 'floatingShapeDragAvatar';
                const isRotated = isMobileLandscapeRotated();
                avatar.className = 'fixed z-[999999] pointer-events-none p-1 rounded-xl border-2 border-[#E57354] bg-[#FFF2D6] shadow-2xl flex items-center justify-center opacity-95 ' + (isRotated ? 'mobile-force-rotated' : '');
                if (isRotated) {
                    avatar.style.transform = 'translate(-50%, -50%) rotate(90deg) scale(1.1)';
                } else {
                    avatar.style.transform = 'translate(-50%, -50%) scale(1.1)';
                }
                avatar.style.left = `${e.clientX}px`;
                avatar.style.top = `${e.clientY}px`;
                avatar.innerHTML = `<div class="w-10 h-8 overflow-hidden flex items-center justify-center p-0.5 bg-white rounded-lg border-2 border-[#E57354] shadow-md">${snippet}</div>`;
                document.body.appendChild(avatar);
                pointerDragState.avatarEl = avatar;

                document.querySelectorAll('.benda-drop-zone').forEach(z => {
                    z.classList.add('ring-2', 'ring-[#E57354]/60', 'bg-[#FFF8E7]');
                });
            }

            if (pointerDragState.isDragging && pointerDragState.avatarEl) {
                if (e.cancelable) e.preventDefault();
                pointerDragState.avatarEl.style.left = `${e.clientX}px`;
                pointerDragState.avatarEl.style.top = `${e.clientY}px`;

                const elemBelow = document.elementFromPoint(e.clientX, e.clientY);
                const dropRow = elemBelow?.closest('.drop-row-target') || elemBelow?.closest('[data-row-idx]');

                document.querySelectorAll('.benda-drop-zone').forEach(z => {
                    z.classList.remove('bg-[#FFF3D6]', 'border-[#E57354]', 'scale-[1.02]');
                });

                if (dropRow) {
                    const rowIdx = parseInt(dropRow.getAttribute('data-row-idx'), 10);
                    pointerDragState.targetRowIdx = isNaN(rowIdx) ? null : rowIdx;
                    const zoneInside = dropRow.querySelector('.benda-drop-zone') || dropRow;
                    if (zoneInside) {
                        zoneInside.classList.add('bg-[#FFF3D6]', 'border-[#E57354]', 'scale-[1.02]');
                    }
                } else {
                    pointerDragState.targetRowIdx = null;
                }
            }
        }

        function handlePointerDragEnd(e) {
            window.removeEventListener('pointermove', handlePointerDragMove);
            window.removeEventListener('pointerup', handlePointerDragEnd);
            window.removeEventListener('pointercancel', handlePointerDragEnd);

            if (pointerDragState.sourceEl && pointerDragState.sourceEl.releasePointerCapture && pointerDragState.pointerId) {
                try {
                    pointerDragState.sourceEl.releasePointerCapture(pointerDragState.pointerId);
                } catch (err) { }
            }

            document.querySelectorAll('.benda-drop-zone').forEach(z => {
                z.classList.remove('ring-2', 'ring-[#E57354]/60', 'bg-[#FFF8E7]', 'border-[#E57354]', 'bg-[#FFF3D6]', 'scale-[1.02]');
            });

            if (pointerDragState.avatarEl) {
                pointerDragState.avatarEl.remove();
                pointerDragState.avatarEl = null;
            }

            if (pointerDragState.isDragging) {
                if (pointerDragState.targetRowIdx !== null && pointerDragState.sourceEl) {
                    placeSvgPartInRow(pointerDragState.targetRowIdx, pointerDragState.sourceEl);
                }
            }

            pointerDragState.isDragging = false;
            pointerDragState.bendaKey = null;
            pointerDragState.targetRowIdx = null;
            pointerDragState.pointerId = null;
        }

        function onBendaDragOver(e) { if (e) e.preventDefault(); }
        function onBendaDragEnter(e) { if (e) e.preventDefault(); }
        function onBendaDragLeave(e) { if (e) e.preventDefault(); }
        function onBendaDrop(e, rowIdx) {
            if (e) e.preventDefault();
            if (activeSelectedSvgEl) {
                placeSvgPartInRow(rowIdx, activeSelectedSvgEl);
            }
        }

        function onBendaSlotClick(rowIdx) {
            if (activeSelectedSvgEl) {
                placeSvgPartInRow(rowIdx, activeSelectedSvgEl);
            }
        }

        function placeSvgPartInRow(rowIdx, targetEl) {
            saveActiveIdentificationInputsToState();
            if (!identificationRows[rowIdx]) {
                identificationRows[rowIdx] = { bendaKey: '', bendaName: '', shape: '', ciri: '', svgSnippet: '' };
            }

            const partId = targetEl.id || ('part-' + Date.now());
            const canvasNum = targetEl.getAttribute('data-canvas') || (partId.includes('c1-') ? '1' : partId.includes('c2-') ? '2' : partId.includes('c3-') ? '3' : partId.includes('c4-') ? '4' : '1');
            const snippet = getSvgElementSnippet(targetEl);

            identificationRows[rowIdx].bendaKey = partId;
            identificationRows[rowIdx].bendaName = 'Bentuk dari Sisi ' + canvasNum;
            identificationRows[rowIdx].svgSnippet = snippet;

            if (window.globalAudio && typeof window.globalAudio.playSfx === 'function') {
                window.globalAudio.playSfx('correct');
            }

            activeSelectedSvgEl = null;
            document.querySelectorAll('.ident-drag-part').forEach(p => p.classList.remove('is-selected'));
            renderIdentificationForm();
            syncReviewIdentification();
            autoSaveRoomData();
        }

        function clearBendaInRow(rowIdx) {
            saveActiveIdentificationInputsToState();
            if (identificationRows[rowIdx]) {
                identificationRows[rowIdx].bendaKey = '';
                identificationRows[rowIdx].bendaName = '';
                identificationRows[rowIdx].svgSnippet = '';
            }
            renderIdentificationForm();
            syncReviewIdentification();
            autoSaveRoomData();
        }

        function onShapeDropdownChange(rowIdx, shapeVal) {
            if (!identificationRows[rowIdx]) {
                identificationRows[rowIdx] = { bendaKey: '', bendaName: '', shape: '', shapeName: '', ciri: '', svgSnippet: '' };
            }
            identificationRows[rowIdx].shapeName = shapeVal ? getShapeDisplayText(shapeVal) : '';
            updateIdentificationRowData(rowIdx, 'shape', shapeVal);
        }

        function updateIdentificationRowData(rowIdx, field, val) {
            if (!identificationRows[rowIdx]) {
                identificationRows[rowIdx] = { bendaKey: '', bendaName: '', shape: '', ciri: '', svgSnippet: '' };
            }
            identificationRows[rowIdx][field] = val;
            syncReviewIdentification();
            autoSaveRoomData();
        }

        function saveActiveIdentificationInputsToState() {
            for (let i = 0; i < 6; i++) {
                const c = document.getElementById(`identCiriInput-${i}`);
                const s = document.getElementById(`shapeSelect-${i}`);
                if (!identificationRows[i]) {
                    identificationRows[i] = { bendaKey: '', bendaName: '', shape: '', ciri: '', svgSnippet: '' };
                }
                if (c) identificationRows[i].ciri = c.value;
                if (s) identificationRows[i].shape = s.value;
            }
        }

        function resetTabelIdentifikasi() {
            if (window.globalAudio && typeof window.globalAudio.playSfx === 'function') {
                window.globalAudio.playSfx('button_click');
            }
            const modal = document.getElementById('resetTableModal');
            const box = document.getElementById('resetTableBox');
            if (modal) { modal.classList.remove('opacity-0', 'pointer-events-none'); modal.classList.add('opacity-100'); }
            if (box) { box.classList.remove('scale-90'); box.classList.add('scale-100'); }
        }

        function closeResetTableModal() {
            const modal = document.getElementById('resetTableModal');
            const box = document.getElementById('resetTableBox');
            if (modal) { modal.classList.remove('opacity-100'); modal.classList.add('opacity-0', 'pointer-events-none'); }
            if (box) { box.classList.remove('scale-100'); box.classList.add('scale-90'); }
        }

        function executeResetTabelIdentifikasi() {
            closeResetTableModal();

            if (window.globalAudio && typeof window.globalAudio.playSfx === 'function') {
                window.globalAudio.playSfx('button_click');
            }

            identificationRows = [];
            for (let i = 0; i < 6; i++) {
                identificationRows.push({
                    bendaKey: '',
                    bendaName: '',
                    shape: '',
                    ciri: '',
                    svgSnippet: ''
                });
            }

            activeSelectedSvgEl = null;
            document.querySelectorAll('.ident-drag-part').forEach(p => p.classList.remove('is-selected'));

            renderIdentificationForm();
            syncReviewIdentification();
            autoSaveRoomData();
            showKolabRefreshToast('Tabel identifikasi berhasil direset!');
        }

        function renderIdentificationForm() {
            const tbody = document.getElementById('tbodyIdentificationForm');
            if (!tbody) return;
            closeAllShapeDropdowns();

            let html = '';
            for (let i = 0; i < 6; i++) {
                const row = identificationRows[i] || { bendaKey: '', bendaName: '', shape: '', ciri: '', svgSnippet: '' };
                const bendaObj = BENDA_DEFINITIONS[row.bendaKey];

                let bendaSlotContent = '';
                if (row.svgSnippet || (row.bendaKey && (bendaObj || row.bendaName))) {
                    const iconHtml = row.svgSnippet || (bendaObj?.iconSvg ? `<svg viewBox="0 0 40 30" class="w-full h-full">${bendaObj.iconSvg}</svg>` : '');
                    bendaSlotContent = `
                            <div class="relative group/slot h-12 sm:h-14 w-full flex items-center justify-center p-1 rounded-lg border-2 border-[#E9C37A] bg-[#FFF8E7] shadow-2xs hover:border-[#E57354] transition-all">
                                <div class="w-12 h-10 sm:w-14 sm:h-11 rounded-md bg-white border border-[#E57354]/40 flex items-center justify-center p-0.5 overflow-hidden shadow-inner mx-auto">
                                    ${iconHtml}
                                </div>
                                <button type="button" onclick="event.stopPropagation(); clearBendaInRow(${i})" class="absolute top-0.5 right-0.5 w-4 h-4 rounded-full bg-red-500 text-white hover:bg-red-600 font-bold text-[10px] leading-none flex items-center justify-center shadow cursor-pointer transition-transform hover:scale-110" title="Hapus Benda">&times;</button>
                            </div>
                        `;
                } else if (activeSelectedSvgEl) {
                    bendaSlotContent = `
                            <div class="benda-drop-zone h-12 sm:h-14 w-full flex items-center justify-center border-2 border-dashed border-[#E57354] bg-[#FFEBE6] rounded-lg px-1 text-center text-[10px] lg:text-xs text-[#E57354] font-bold hover:bg-[#FFD7CE] transition-all cursor-pointer animate-pulse select-none"
                                 onclick="onBendaSlotClick(${i})">
                                + Pasang
                            </div>
                        `;
                } else {
                    bendaSlotContent = `
                            <div class="benda-drop-zone h-12 sm:h-14 w-full flex items-center justify-center border-2 border-dashed border-[#E9C37A] bg-[#FFFDF5] rounded-lg px-1 text-center text-[10px] lg:text-xs text-[#8A4416] font-bold hover:border-[#E57354] hover:bg-[#FFF8E7] transition-all cursor-pointer select-none"
                                 onclick="onBendaSlotClick(${i})">
                                + Pasang
                            </div>
                        `;
                }

                const shapes = Array.isArray(BANGUN_DATAR_MASTER)
                    ? BANGUN_DATAR_MASTER.map(s => ({
                        val: s.key,
                        label: s.name,
                        icon: s.custom_image
                            ? `<img src="${s.custom_image}" alt="" class="w-4 h-4 lg:w-5 lg:h-5 object-contain shrink-0">`
                            : (s.svg ? `<span class="w-4 h-4 lg:w-5 lg:h-5 inline-flex items-center justify-center shrink-0" style="color:${s.color || '#E57354'}">${s.svg}</span>` : '')
                    }))
                    : [];

                let shapeOptionsList = [
                    { val: '', label: '-- Pilih Bangun Datar --', icon: '' },
                    ...shapes
                ];

                if (row.shape && !shapeOptionsList.some(s => s.val === row.shape)) {
                    shapeOptionsList.push({
                        val: row.shape,
                        label: getShapeDisplayText(row.shape),
                        icon: GEOMETRY_SHAPES[row.shape]?.svg || ''
                    });
                }

                const selectedShapeObj = shapeOptionsList.find(s => s.val === (row.shape || '')) || shapeOptionsList[0];

                const shapeMenuItems = shapeOptionsList.map(s => `
                        <button type="button"
                                data-shape="${s.val}"
                                onclick="selectShapeItem(event, ${i}, '${s.val.replace(/'/g, "\\'")}')"
                                class="w-full text-left px-3 py-2 flex items-center gap-2.5 hover:bg-[#FFF2D6] transition-colors cursor-pointer ${row.shape === s.val ? 'bg-[#FFEBE6] font-bold text-[#E57354]' : 'text-[#5C2B09]'}">
                            ${s.icon || ''}
                            <span class="leading-snug">${s.label}</span>
                        </button>
                    `).join('');

                html += `
                        <tr data-row-idx="${i}" class="drop-row-target hover:bg-[#FFF8E7]/60 transition-colors bg-[#FFFDF7]">
                            <!-- Kolom 1: BENTUK (Hanya bentuk grafis tanpa teks) -->
                            <td class="p-1.5 border-r border-[#E9C37A]/50 align-middle text-center w-[19%]"
                                data-row-idx="${i}"
                                ondragover="onBendaDragOver(event)"
                                ondragenter="onBendaDragEnter(event)"
                                ondragleave="onBendaDragLeave(event)"
                                ondrop="onBendaDrop(event, ${i})">
                                ${bendaSlotContent}
                            </td>

                            <!-- Kolom 2: BANGUN DATAR (Custom Portal Dropdown agar sesuai rotasi landscape mobile) -->
                            <td class="p-1.5 border-r border-[#E9C37A]/50 align-middle w-[29%]" data-row-idx="${i}">
                                <div class="custom-shape-dropdown w-full" data-shape-row="${i}">
                                    <input type="hidden" id="shapeSelect-${i}" value="${row.shape || ''}">
                                    <button type="button"
                                            id="shapeSelectBtn-${i}"
                                            onclick="toggleShapeDropdown(event, ${i})"
                                            class="w-full h-10 lg:h-11 flex items-center justify-between gap-1.5 bg-white border border-[#E9C37A] rounded-lg px-2.5 py-1.5 text-[11px] sm:text-xs lg:text-sm font-bold text-[#8A4416] outline-none cursor-pointer hover:bg-[#FFF8E7] focus:border-[#E57354] shadow-2xs transition-colors text-left">
                                        <span id="shapeLabel-${i}" class="truncate flex items-center gap-1.5 min-w-0">${selectedShapeObj.icon || ''}<span class="truncate">${selectedShapeObj.label}</span></span>
                                        <svg id="shapeArrow-${i}" class="w-4 h-4 text-[#8A4416] shrink-0 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    <div id="shapeMenu-${i}"
                                         class="shape-menu hidden fixed z-[9999] bg-[#FFFDF5] border-2 border-[#E9C37A] rounded-xl shadow-xl overflow-y-auto py-1 text-xs sm:text-sm font-bold pointer-events-auto"
                                         style="max-height: 280px;">
                                        ${shapeMenuItems}
                                    </div>
                                </div>
                            </td>

                            <!-- Kolom 3: CIRI-CIRI (Textarea Ciri Geometris) -->
                            <td class="p-1.5 align-middle w-[52%]" data-row-idx="${i}">
                                <textarea id="identCiriInput-${i}"
                                          oninput="updateIdentificationRowData(${i}, 'ciri', this.value)"
                                          placeholder="Tuliskan ciri..." 
                                          rows="2"
                                          class="w-full min-h-[48px] sm:min-h-[54px] bg-[#FFFDF5] border border-[#E9C37A]/70 rounded-lg px-2.5 py-1.5 text-[11px] sm:text-xs lg:text-sm outline-none text-[#3D1804] leading-snug resize-none focus:border-[#E57354] focus:bg-white">${(row.ciri || '').replace(/</g, '&lt;').replace(/>/g, '&gt;')}</textarea>
                            </td>
                        </tr>
                    `;
            }

            tbody.innerHTML = html;
            initSketchDragEngine();
        }

        function syncReviewIdentification() {
            const tbody = document.getElementById('tbodyReviewIdentifikasi');
            if (!tbody) return;

            let html = '';
            for (let i = 0; i < 6; i++) {
                const row = identificationRows[i] || { bendaKey: '', bendaName: '', shape: '', ciri: '', svgSnippet: '' };
                const bendaObj = BENDA_DEFINITIONS[row.bendaKey];
                const shapeObj = GEOMETRY_SHAPES[row.shape];

                const iconHtml = row.svgSnippet || (bendaObj?.iconSvg ? `<svg viewBox="0 0 40 30" class="w-full h-full">${bendaObj.iconSvg}</svg>` : '');

                const bendaCell = (row.svgSnippet || (row.bendaKey && (bendaObj || row.bendaName)))
                    ? `<div class="w-10 h-8 rounded-md bg-white border border-[#E57354]/40 flex items-center justify-center p-0.5 overflow-hidden shadow-inner mx-auto">
                               ${iconHtml}
                           </div>`
                    : '<span class="text-stone-400 italic text-[10px]">-</span>';

                const shapeBadge = shapeObj
                    ? `<span class="inline-flex items-center px-2 py-0.5 rounded-full border ${shapeObj.badgeBorder} ${shapeObj.badgeBg} ${shapeObj.badgeText} font-bold text-[10px]">${shapeObj.svg} ${shapeObj.name}</span>`
                    : '<span class="text-stone-400 italic text-[10px]">Belum dipilih</span>';

                const ciriLines = row.ciri ? row.ciri.split('\n').map(l => l.trim()).filter(Boolean) : [];
                const ciriText = ciriLines.length > 0
                    ? ciriLines.map(line => `<div>${line.startsWith('-') ? line : '- ' + line}</div>`).join('')
                    : '<span class="text-stone-400 italic text-[10px]">-</span>';

                html += `
                        <tr class="hover:bg-[#FFF8E7]/50 transition-colors bg-[#FFFDF7]">
                            <td class="py-1.5 px-1.5 text-center border-r border-[#E9C37A]/40 align-middle">${bendaCell}</td>
                            <td class="py-1.5 px-2 text-center border-r border-[#E9C37A]/40 align-middle">${shapeBadge}</td>
                            <td class="py-1.5 px-2 text-[#5C2B09] text-[10px] leading-tight align-middle text-left">${ciriText}</td>
                        </tr>
                    `;
            }
            tbody.innerHTML = html;
        }

        function toggleTaskDropdown(event, userId) {
            if (event) {
                event.stopPropagation();
            }
            const dropdownEl = document.querySelector(`.custom-task-dropdown[data-user-id="${userId}"]`);
            const menu = document.getElementById(`taskRoleMenu-${userId}`);
            const arrow = document.getElementById(`taskRoleArrow-${userId}`);

            const isCurrentlyOpen = menu && !menu.classList.contains('hidden');

            closeAllTaskDropdowns();

            if (!isCurrentlyOpen && menu && dropdownEl) {
                menu.classList.remove('hidden');
                dropdownEl.classList.add('is-open');
                const tr = dropdownEl.closest('tr');
                if (tr) tr.style.zIndex = '50';
                if (arrow) arrow.classList.add('rotate-180');
                if (window.globalAudio && typeof window.globalAudio.playSfx === 'function') {
                    window.globalAudio.playSfx('button_click');
                }
            }
        }

        function closeAllTaskDropdowns() {
            document.querySelectorAll('.task-role-menu').forEach(m => m.classList.add('hidden'));
            document.querySelectorAll('.custom-task-dropdown').forEach(d => {
                d.classList.remove('is-open');
                const tr = d.closest('tr');
                if (tr) tr.style.zIndex = '';
            });
            document.querySelectorAll('[id^="taskRoleArrow-"]').forEach(a => a.classList.remove('rotate-180'));
        }

        function selectTaskRole(event, userId, roleVal) {
            if (event) {
                event.stopPropagation();
            }

            const input = document.getElementById(`taskRoleSelect-${userId}`);
            if (input) {
                input.value = roleVal;
            }

            const label = document.getElementById(`taskRoleLabel-${userId}`);
            if (label) {
                label.innerHTML = roleVal === 'identifikasi'
                    ? 'Identifikasi Bangun Datar'
                    : 'Mewarnai Motif';
            }

            const menu = document.getElementById(`taskRoleMenu-${userId}`);
            if (menu) {
                menu.querySelectorAll('button[data-role]').forEach(btn => {
                    const bRole = btn.getAttribute('data-role');
                    if (bRole === roleVal) {
                        btn.className = 'w-full text-left px-2.5 py-1.5 flex items-center gap-1.5 bg-[#FFEBE6] font-bold text-[#E57354] transition-colors cursor-pointer';
                    } else {
                        btn.className = 'w-full text-left px-2.5 py-1.5 flex items-center gap-1.5 hover:bg-[#FFEBE6] text-[#8A4416] transition-colors cursor-pointer';
                    }
                });
            }

            closeAllTaskDropdowns();
            updateMemberRole(userId, roleVal);

            if (window.globalAudio && typeof window.globalAudio.playSfx === 'function') {
                window.globalAudio.playSfx('button_click');
            }
        }

        var currentEditingShapeRowIdx = null;

        function openShapePicker(rowIdx) {
            saveActiveIdentificationInputsToState();
            currentEditingShapeRowIdx = rowIdx;
            const modal = document.getElementById('shapePickerBackdrop');
            if (!modal) return;

            const currentShape = (identificationRows[rowIdx] && identificationRows[rowIdx].shape) ? identificationRows[rowIdx].shape : '';
            document.querySelectorAll('.shape-picker-btn').forEach(btn => {
                const shape = btn.getAttribute('data-shape');
                if (shape === currentShape) {
                    btn.classList.add('bg-[#FFEBE6]', 'border-[#E57354]', 'ring-2', 'ring-[#E57354]/40');
                    btn.classList.remove('bg-white', 'border-[#E9C37A]');
                } else {
                    btn.classList.remove('bg-[#FFEBE6]', 'border-[#E57354]', 'ring-2', 'ring-[#E57354]/40');
                    btn.classList.add('bg-white', 'border-[#E9C37A]');
                }
            });

            modal.classList.remove('hidden');
            if (window.globalAudio && typeof window.globalAudio.playSfx === 'function') {
                window.globalAudio.playSfx('button_click');
            }
        }

        function closeShapePicker() {
            const modal = document.getElementById('shapePickerBackdrop');
            if (modal) {
                modal.classList.add('hidden');
            }
            currentEditingShapeRowIdx = null;
        }

        function selectShapeFromPicker(shapeVal) {
            if (currentEditingShapeRowIdx !== null) {
                onShapeDropdownChange(currentEditingShapeRowIdx, shapeVal);
                const label = document.getElementById(`shapeLabel-${currentEditingShapeRowIdx}`);
                if (label) {
                    label.textContent = getShapeDisplayText(shapeVal);
                }
            }
            closeShapePicker();
            if (window.globalAudio && typeof window.globalAudio.playSfx === 'function') {
                window.globalAudio.playSfx('button_click');
            }
        }

        function getShapeDisplayText(val) {
            if (GEOMETRY_SHAPES[val]) {
                return GEOMETRY_SHAPES[val].name;
            }
            const shapeNames = {
                'segitiga': 'Segitiga',
                'persegi': 'Persegi',
                'persegi_panjang': 'Persegi Panjang',
                'lingkaran': 'Lingkaran',
                'jajar_genjang': 'Jajar Genjang',
                'belah_ketupat': 'Belah Ketupat',
                'layang_layang': 'Layang-Layang',
                'trapesium': 'Trapesium'
            };
            return shapeNames[val] || '-- Pilih Bangun Datar --';
        }

        function openPortalDropdown(menu, btn, dropdownEl, arrow, minWidthPx = 120, maxMenuHeight = 210) {
            const portal = document.getElementById('evalDropdownPortal');
            if (portal) {
                if (!menu._originParent) {
                    menu._originParent = dropdownEl;
                }
                portal.appendChild(menu);
            }

            menu.classList.remove('hidden');
            menu.style.position = 'fixed';
            menu.style.right = 'auto';
            menu.style.zIndex = '9999';
            menu.style.maxHeight = `${maxMenuHeight}px`;

            const btnRect = btn.getBoundingClientRect();
            const naturalHeight = Math.min(maxMenuHeight, menu.scrollHeight || maxMenuHeight);

            if (isMobileLandscapeRotated()) {
                const screenW = window.innerWidth;
                const screenH = window.innerHeight;
                const localWidth = Math.max(minWidthPx, btnRect.height);
                const localLeft = Math.max(8, Math.min(btnRect.top, screenH - localWidth - 8));
                const localBtnTop = screenW - btnRect.right;
                const localBtnBottom = screenW - btnRect.left;
                const spaceBelow = screenW - localBtnBottom - 8;
                const spaceAbove = localBtnTop - 8;

                let topPos;
                let availHeight;
                if (spaceBelow >= naturalHeight || spaceBelow >= spaceAbove) {
                    availHeight = Math.max(100, Math.min(maxMenuHeight, spaceBelow));
                    topPos = localBtnBottom + 4;
                } else {
                    availHeight = Math.max(100, Math.min(maxMenuHeight, spaceAbove));
                    topPos = Math.max(8, localBtnTop - Math.min(naturalHeight, availHeight) - 4);
                }

                menu.style.left = `${localLeft}px`;
                menu.style.top = `${topPos}px`;
                menu.style.width = `${localWidth}px`;
                menu.style.maxHeight = `${availHeight}px`;
            } else {
                const localWidth = Math.max(minWidthPx, btnRect.width);
                const leftPos = Math.max(8, Math.min(btnRect.left, window.innerWidth - localWidth - 8));
                const spaceBelow = window.innerHeight - btnRect.bottom - 8;
                const spaceAbove = btnRect.top - 8;

                let topPos;
                let availHeight;
                if (spaceBelow >= naturalHeight || spaceBelow >= spaceAbove) {
                    availHeight = Math.max(100, Math.min(maxMenuHeight, spaceBelow));
                    topPos = btnRect.bottom + 4;
                } else {
                    availHeight = Math.max(100, Math.min(maxMenuHeight, spaceAbove));
                    topPos = Math.max(8, btnRect.top - Math.min(naturalHeight, availHeight) - 4);
                }

                menu.style.left = `${leftPos}px`;
                menu.style.top = `${topPos}px`;
                menu.style.width = `${localWidth}px`;
                menu.style.maxHeight = `${availHeight}px`;
            }

            dropdownEl.classList.add('is-open');
            const tr = dropdownEl.closest('tr');
            if (tr) tr.style.zIndex = '50';
            if (arrow) arrow.classList.add('rotate-180');
            if (window.globalAudio && typeof window.globalAudio.playSfx === 'function') {
                window.globalAudio.playSfx('button_click');
            }
        }

        function closePortalDropdowns(menuSelector, wrapperSelector, arrowPrefix) {
            document.querySelectorAll(menuSelector).forEach(m => {
                m.classList.add('hidden');
                m.style.top = '';
                m.style.left = '';
                m.style.width = '';
                if (m._originParent) {
                    if (document.body.contains(m._originParent)) {
                        if (m.parentElement !== m._originParent) {
                            m._originParent.appendChild(m);
                        }
                    } else {
                        m.remove();
                    }
                }
            });
            document.querySelectorAll(wrapperSelector).forEach(d => {
                d.classList.remove('is-open');
                const tr = d.closest('tr');
                if (tr) tr.style.zIndex = '';
            });
            document.querySelectorAll(`[id^="${arrowPrefix}"]`).forEach(a => a.classList.remove('rotate-180'));
        }

        function toggleShapeDropdown(event, rowIdx) {
            if (event) event.stopPropagation();
            const dropdownEl = document.querySelector(`.custom-shape-dropdown[data-shape-row="${rowIdx}"]`);
            const btn = document.getElementById(`shapeSelectBtn-${rowIdx}`);
            const menu = document.getElementById(`shapeMenu-${rowIdx}`);
            const arrow = document.getElementById(`shapeArrow-${rowIdx}`);
            const isCurrentlyOpen = menu && !menu.classList.contains('hidden');

            closeAllShapeDropdowns();
            closeAllEvalDropdowns();
            closeAllEvalPeerDropdowns();

            if (!isCurrentlyOpen && menu && dropdownEl && btn) {
                const isDesktop = window.innerWidth >= 1025;
                openPortalDropdown(menu, btn, dropdownEl, arrow, isDesktop ? 270 : 210, isDesktop ? 280 : 210);
            }
        }

        function closeAllShapeDropdowns() {
            closePortalDropdowns('.shape-menu', '.custom-shape-dropdown', 'shapeArrow-');
        }

        function selectShapeItem(event, rowIdx, shapeVal) {
            if (event) event.stopPropagation();
            const input = document.getElementById(`shapeSelect-${rowIdx}`);
            if (input) input.value = shapeVal;
            closeAllShapeDropdowns();
            onShapeDropdownChange(rowIdx, shapeVal);
            renderIdentificationForm();
            if (window.globalAudio && typeof window.globalAudio.playSfx === 'function') {
                window.globalAudio.playSfx('button_click');
            }
        }

        function toggleEvalDropdown(event, idx) {
            if (event) event.stopPropagation();
            const dropdownEl = document.querySelector(`.custom-eval-dropdown[data-eval-idx="${idx}"]`);
            const btn = document.getElementById(`evalRatingBtn-${idx}`);
            const menu = document.getElementById(`evalMenu-${idx}`);
            const arrow = document.getElementById(`evalArrow-${idx}`);
            const isCurrentlyOpen = menu && !menu.classList.contains('hidden');

            closeAllEvalDropdowns();
            closeAllEvalPeerDropdowns();
            closeAllShapeDropdowns();

            if (!isCurrentlyOpen && menu && dropdownEl && btn) {
                openPortalDropdown(menu, btn, dropdownEl, arrow, 120, 160);
            }
        }

        function closeAllEvalDropdowns() {
            closePortalDropdowns('.eval-menu', '.custom-eval-dropdown', 'evalArrow-');
        }

        function toggleEvalPeerDropdown(event, idx) {
            if (event) event.stopPropagation();
            const dropdownEl = document.querySelector(`.custom-eval-peer-dropdown[data-eval-peer-idx="${idx}"]`);
            const btn = document.getElementById(`evalPeerBtn-${idx}`);
            const menu = document.getElementById(`evalPeerMenu-${idx}`);
            const arrow = document.getElementById(`evalPeerArrow-${idx}`);
            const isCurrentlyOpen = menu && !menu.classList.contains('hidden');

            closeAllEvalPeerDropdowns();
            closeAllEvalDropdowns();
            closeAllShapeDropdowns();

            if (!isCurrentlyOpen && menu && dropdownEl && btn) {
                const isDesktop = window.innerWidth >= 1025;
                openPortalDropdown(menu, btn, dropdownEl, arrow, isDesktop ? 230 : 190, 240);
            }
        }

        function closeAllEvalPeerDropdowns() {
            closePortalDropdowns('.eval-peer-menu', '.custom-eval-peer-dropdown', 'evalPeerArrow-');
        }

        function selectEvalPeer(event, idx, peerUserId) {
            if (event) event.stopPropagation();
            captureEvalDomState();
            closeAllEvalPeerDropdowns();

            const state = evalRowStates[idx];
            if (!state) return;

            if (!peerUserId) {
                state.peerId = '';
                state.name = '';
                state.noAbsen = '-';
                state.isManual = false;
                state.manualSkipped = true;
            } else {
                const targetId = String(peerUserId);
                const selectedPeer = (roomMembersList || []).find(m => m && String(m.user_id) === targetId);
                if (selectedPeer) {
                    const prevPeerId = state.peerId;
                    const prevIsManual = state.isManual;
                    const duplicateIdx = evalRowStates.findIndex((s, rIdx) => rIdx !== idx && !s.isManual && String(s.peerId) === targetId);
                    if (duplicateIdx !== -1) {
                        const prevPeerObj = prevPeerId && !prevIsManual
                            ? (roomMembersList || []).find(m => m && String(m.user_id) === String(prevPeerId))
                            : null;
                        if (prevPeerObj) {
                            evalRowStates[duplicateIdx].peerId = String(prevPeerObj.user_id);
                            evalRowStates[duplicateIdx].name = prevPeerObj.user_name;
                            evalRowStates[duplicateIdx].noAbsen = prevPeerObj.no_absen || '-';
                            evalRowStates[duplicateIdx].manualSkipped = false;
                        } else {
                            evalRowStates[duplicateIdx].peerId = '';
                            evalRowStates[duplicateIdx].name = '';
                            evalRowStates[duplicateIdx].noAbsen = '-';
                            evalRowStates[duplicateIdx].manualSkipped = true;
                        }
                    }

                    state.peerId = targetId;
                    state.name = selectedPeer.user_name;
                    state.noAbsen = selectedPeer.no_absen || '-';
                    state.isManual = false;
                    state.manualSkipped = false;
                }
            }

            renderTable3Evaluations(roomMembersList, [], false);
            autoSaveRoomData();
            if (window.globalAudio && typeof window.globalAudio.playSfx === 'function') {
                window.globalAudio.playSfx('button_click');
            }
        }

        function enableEvalManualMode(event, idx) {
            if (event) event.stopPropagation();
            captureEvalDomState();
            closeAllEvalPeerDropdowns();
            if (evalRowStates[idx]) {
                evalRowStates[idx].peerId = '';
                evalRowStates[idx].isManual = true;
                evalRowStates[idx].manualSkipped = true;
                if (!evalRowStates[idx].noAbsen || evalRowStates[idx].noAbsen === '-') {
                    evalRowStates[idx].noAbsen = '';
                }
            }
            renderTable3Evaluations(roomMembersList, [], false);
            const nameInput = document.getElementById(`evalName-${idx}`);
            if (nameInput) nameInput.focus();
        }

        function enableEvalDropdownMode(idx) {
            captureEvalDomState();
            closeAllEvalPeerDropdowns();
            if (evalRowStates[idx]) {
                evalRowStates[idx].isManual = false;
                evalRowStates[idx].manualSkipped = false;
                evalRowStates[idx].peerId = '';
                evalRowStates[idx].name = '';
                evalRowStates[idx].noAbsen = '-';
            }
            renderTable3Evaluations(roomMembersList, [], false);
            autoSaveRoomData();
        }

        function selectEvalRating(event, idx, ratingVal) {
            if (event) event.stopPropagation();
            const input = document.getElementById(`evalRating-${idx}`);
            if (input) input.value = ratingVal;
            const label = document.getElementById(`evalRatingLabel-${idx}`);
            if (label) label.textContent = ratingVal;
            if (evalRowStates[idx]) evalRowStates[idx].rating = ratingVal;
            closeAllEvalDropdowns();
            autoSaveRoomData();
            if (window.globalAudio && typeof window.globalAudio.playSfx === 'function') {
                window.globalAudio.playSfx('button_click');
            }
        }

        document.addEventListener('pointerdown', (e) => {
            if (!e.target.closest('.custom-task-dropdown')) {
                closeAllTaskDropdowns();
            }
            if (!e.target.closest('.custom-shape-dropdown') && !e.target.closest('.shape-menu')) {
                closeAllShapeDropdowns();
            }
            if (!e.target.closest('.custom-eval-dropdown') && !e.target.closest('.eval-menu')) {
                closeAllEvalDropdowns();
            }
            if (!e.target.closest('.custom-eval-peer-dropdown') && !e.target.closest('.eval-peer-menu')) {
                closeAllEvalPeerDropdowns();
            }
        });

        window.addEventListener('scroll', (e) => {
            if (e.target instanceof Element && (e.target.closest('.shape-menu') || e.target.closest('.eval-menu') || e.target.closest('.eval-peer-menu'))) {
                return;
            }
            closeAllEvalDropdowns();
            closeAllEvalPeerDropdowns();
            closeAllShapeDropdowns();
        }, { capture: true, passive: true });
        window.addEventListener('resize', () => {
            closeAllEvalDropdowns();
            closeAllEvalPeerDropdowns();
            closeAllShapeDropdowns();
        });

        async function updateMemberRole(userId, roleVal) {
            if (userId === currentUserId) {
                myAssignedTask = roleVal;
            }
            if (currentRoomNumber) {
                try {
                    const token = getCsrfToken();
                    await fetch(`/api/kolaborasi/room/${currentRoomNumber}/save`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token
                        },
                        body: JSON.stringify({
                            _token: token,
                            target_user_id: userId,
                            target_role: roleVal,
                            my_role: myAssignedTask
                        })
                    });
                } catch (e) { }
            }
            autoSaveRoomData();
        }

        function selesaiMisi1() {
            autoSaveRoomData();
            applyColoringToIdentCanvas();
            goToKolabStage('4B');
        }

        function selesaiMisi2() {
            autoSaveRoomData();
            goToKolabStage(6);
        }

        function mulaiTugasSaya() {
            goToKolabStage('4A');
        }

        function selesaiTugasMewarnai() {
            selesaiMisi1();
        }

        function selesaiTugasIdentifikasi() {
            selesaiMisi2();
        }

        function kembaliKoreksiTugas() {
            goToKolabStage('4A');
        }

        function kumpulkanHasilTim() {
            autoSaveRoomData();
            goToKolabStage(6);
        }

        // Confirmation Submit Modal Handlers
        function openConfirmSubmitModal() {
            if (window.globalAudio && typeof window.globalAudio.playSfx === 'function') {
                window.globalAudio.playSfx('button_click');
            }
            const modal = document.getElementById('confirmSubmitModal');
            const box = document.getElementById('confirmSubmitBox');
            if (modal) { modal.classList.remove('opacity-0', 'pointer-events-none'); modal.classList.add('opacity-100'); }
            if (box) { box.classList.remove('scale-90'); box.classList.add('scale-100'); }
        }

        function closeConfirmSubmitModal() {
            const modal = document.getElementById('confirmSubmitModal');
            const box = document.getElementById('confirmSubmitBox');
            if (modal) { modal.classList.remove('opacity-100'); modal.classList.add('opacity-0', 'pointer-events-none'); }
            if (box) { box.classList.remove('scale-100'); box.classList.add('scale-90'); }
        }

        async function executeFinalSubmitRoom() {
            if (!currentRoomNumber) return;

            closeConfirmSubmitModal();

            const coloringData = { ...currentRoomColoringData };

            const evaluationData = [];
            const evalNotes = document.querySelectorAll('[id^="evalNote-"]');
            evalNotes.forEach((noteEl, i) => {
                const nameEl = document.getElementById(`evalName-${i}`);
                const absenEl = document.getElementById(`evalAbsen-${i}`);
                const rateEl = document.getElementById(`evalRating-${i}`);
                const peerId = (nameEl && nameEl.dataset.peerId) || noteEl.dataset.peerId || null;
                const peerName = nameEl ? nameEl.value.trim() : '';
                const noteText = noteEl ? noteEl.value.trim() : '';
                if ((peerId || peerName || noteText) && (rateEl || noteEl || nameEl)) {
                    evaluationData.push({
                        evaluator_id: currentUserId,
                        evaluator_name: currentUserName,
                        peer_id: peerId,
                        peer_name: peerName || `Teman Anggota ${i + 1}`,
                        peer_no_absen: absenEl && absenEl.value ? absenEl.value : '-',
                        rating: rateEl ? rateEl.value : 'Sangat Baik',
                        note: noteEl ? noteEl.value : ''
                    });
                }
            });

            const tasksData = (roomMembersList && roomMembersList.length > 0)
                ? roomMembersList.map(m => ({
                    user_id: m.user_id,
                    user_name: m.user_name,
                    no_absen: m.no_absen,
                    role: m.role || 'Kolaborator'
                }))
                : [{
                    user_id: currentUserId,
                    user_name: currentUserName,
                    no_absen: currentUserNoAbsen,
                    role: 'Kolaborator'
                }];

            try {
                const token = getCsrfToken();
                const res = await fetch(`/api/kolaborasi/room/${currentRoomNumber}/submit`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({
                        _token: token,
                        group_name: document.getElementById('inputNamaKelompok')?.value || '',
                        leader_name: document.getElementById('inputKetuaTim')?.value || '',
                        tasks_data: tasksData,
                        coloring_data: coloringData,
                        identification_data: identificationRows,
                        evaluation_data: evaluationData
                    })
                });

                const data = await res.json();
                currentRoomNumber = null;
                roomMembersList = [];
                try {
                    sessionStorage.removeItem('tara_kolab_room');
                    sessionStorage.removeItem('tara_kolab_stage');
                } catch (e) { }

                openSuccessSubmitModal(data.message || 'Tugas kelompok berhasil dikumpulkan dan tersimpan di sistem Guru. Terima kasih telah bekerjasama dengan penuh tanggung jawab dan saling menghargai.');
            } catch (e) {
                currentRoomNumber = null;
                roomMembersList = [];
                try {
                    sessionStorage.removeItem('tara_kolab_room');
                    sessionStorage.removeItem('tara_kolab_stage');
                } catch (err) { }
                openSuccessSubmitModal('Tugas kelompok berhasil dikumpulkan dan tersimpan di sistem Guru.');
            }
        }

        function openSuccessSubmitModal(msg) {
            if (window.globalAudio && typeof window.globalAudio.playSfx === 'function') {
                window.globalAudio.playSfx('correct_answer');
            }
            const modal = document.getElementById('successSubmitModal');
            const box = document.getElementById('successSubmitBox');
            const msgEl = document.getElementById('successSubmitMessage');
            if (msg && msgEl) {
                msgEl.textContent = msg;
            }
            if (modal) { modal.classList.remove('opacity-0', 'pointer-events-none'); modal.classList.add('opacity-100'); }
            if (box) { box.classList.remove('scale-90'); box.classList.add('scale-100'); }
        }

        function handleFinishSuccessSubmit() {
            const modal = document.getElementById('successSubmitModal');
            const box = document.getElementById('successSubmitBox');
            if (modal) { modal.classList.remove('opacity-100'); modal.classList.add('opacity-0', 'pointer-events-none'); }
            if (box) { box.classList.remove('scale-100'); box.classList.add('scale-90'); }

            currentRoomNumber = null;
            roomMembersList = [];
            evalRowStates = createEmptyEvalRowStates();
            currentRoomColoringData = {};
            try {
                sessionStorage.removeItem('tara_kolab_room');
                sessionStorage.removeItem('tara_kolab_stage');
            } catch (e) { }
            goToKolabStage(1);
            fetchRoomsStatus();
        }

        var currentRoomColoringData = {};
        var activeColoringCanvasNum = 1;
        var activeReviewCanvasNum = 1;

        function selectColoringCanvas(num) {
            activeColoringCanvasNum = num;
            for (let i = 1; i <= 4; i++) {
                const btn = document.getElementById('btnTabCanvas' + i);
                const vp = document.getElementById('canvasViewport-' + i);
                if (btn) {
                    if (i === num) {
                        btn.className = 'tab-canvas-btn px-3 py-1 rounded-full text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer bg-gradient-to-r from-[#E57354] to-[#C94F32] text-white shadow-xs scale-105';
                    } else {
                        btn.className = 'tab-canvas-btn px-3 py-1 rounded-full text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer bg-white text-[#8A4416] border border-[#E9C37A] hover:bg-[#FFF2D6]';
                    }
                }
                if (vp) {
                    if (i === num) {
                        vp.classList.remove('hidden');
                    } else {
                        vp.classList.add('hidden');
                    }
                }
            }
        }

        function selectReviewCanvas(num) {
            activeReviewCanvasNum = num;
            for (let i = 1; i <= 4; i++) {
                const btn = document.getElementById('btnReviewTabCanvas' + i);
                const vp = document.getElementById('reviewCanvasViewport-' + i);
                if (btn) {
                    if (i === num) {
                        btn.className = 'review-tab-btn px-2.5 py-0.5 rounded-full text-[11px] font-bold transition-all bg-[#E57354] text-white shadow-2xs';
                    } else {
                        btn.className = 'review-tab-btn px-2.5 py-0.5 rounded-full text-[11px] font-bold transition-all bg-white text-[#8A4416] border border-[#E9C37A] hover:bg-[#FFF2D6]';
                    }
                }
                if (vp) {
                    if (i === num) {
                        vp.classList.remove('hidden');
                    } else {
                        vp.classList.add('hidden');
                    }
                }
            }
        }

        function syncReviewColoring() {
            if (!currentRoomColoringData || typeof currentRoomColoringData !== 'object') return;
            Object.keys(currentRoomColoringData).forEach(id => {
                if (id.startsWith('_')) return;
                const rawId = String(id).replace(/^review-/, '');
                const rev = document.getElementById('review-' + rawId);
                if (rev) {
                    rev.setAttribute('fill', currentRoomColoringData[id]);
                }
            });
        }

        var saveDebounceTimer = null;
        function autoSaveRoomData() {
            if (!currentRoomNumber) return;
            clearTimeout(saveDebounceTimer);
            saveDebounceTimer = setTimeout(async () => {
                const groupName = document.getElementById('inputNamaKelompok')?.value || '';
                const leaderName = document.getElementById('inputKetuaTim')?.value || '';

                const coloringData = { ...currentRoomColoringData };

                const evaluationData = [];
                const evalNotes = document.querySelectorAll('[id^="evalNote-"]');
                evalNotes.forEach((noteEl, i) => {
                    const nameEl = document.getElementById(`evalName-${i}`);
                    const absenEl = document.getElementById(`evalAbsen-${i}`);
                    const rateEl = document.getElementById(`evalRating-${i}`);
                    const peerId = (nameEl && nameEl.dataset.peerId) || noteEl.dataset.peerId || null;
                    const peerName = nameEl ? nameEl.value.trim() : '';
                    const noteText = noteEl ? noteEl.value.trim() : '';
                    if ((peerId || peerName || noteText) && (rateEl || noteEl || nameEl)) {
                        evaluationData.push({
                            evaluator_id: currentUserId,
                            evaluator_name: currentUserName,
                            peer_id: peerId,
                            peer_name: peerName || `Teman Anggota ${i + 1}`,
                            peer_no_absen: absenEl && absenEl.value ? absenEl.value : '-',
                            rating: rateEl ? rateEl.value : 'Sangat Baik',
                            note: noteEl ? noteEl.value : ''
                        });
                    }
                });

                try {
                    const token = getCsrfToken();
                    await fetch(`/api/kolaborasi/room/${currentRoomNumber}/save`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token
                        },
                        body: JSON.stringify({
                            _token: token,
                            group_name: groupName,
                            leader_name: leaderName,
                            my_role: 'Kolaborator',
                            coloring_data: coloringData,
                            identification_data: identificationRows,
                            evaluation_data: evaluationData
                        })
                    });
                } catch (e) { }
            }, 500);
        }

        var paintColor = '#EF4444';
        function pickSvgColor(c) {
            paintColor = c;
            const dot = document.getElementById('activeColorDot');
            if (dot) dot.style.backgroundColor = c;
        }

        async function sendLiveColorUpdate(partId, color) {
            if (!currentRoomNumber) return;
            try {
                const token = getCsrfToken();
                fetch(`/api/kolaborasi/room/${currentRoomNumber}/color`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({
                        _token: token,
                        part_id: partId,
                        color: color,
                        canvas_num: activeColoringCanvasNum
                    })
                }).catch(err => console.error('Color sync error:', err));
            } catch (e) {
                console.error('sendLiveColorUpdate error:', e);
            }
        }

        async function sendLiveResetCanvas(canvasNum) {
            if (!currentRoomNumber) return;
            try {
                const token = getCsrfToken();
                fetch(`/api/kolaborasi/room/${currentRoomNumber}/reset-canvas`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({
                        _token: token,
                        canvas_num: canvasNum
                    })
                }).catch(err => console.error('Reset sync error:', err));
            } catch (e) {
                console.error('sendLiveResetCanvas error:', e);
            }
        }

        function colorCanvasPart(id) {
            const el = document.getElementById(id);
            if (el) {
                el.setAttribute('fill', paintColor);
                currentRoomColoringData[id] = paintColor;

                const revEl = document.getElementById('review-' + id);
                if (revEl) {
                    revEl.setAttribute('fill', paintColor);
                }

                const identEl = document.getElementById('ident-' + id);
                if (identEl) {
                    identEl.setAttribute('fill', paintColor);
                }

                if (window.globalAudio && typeof window.globalAudio.playSfx === 'function') {
                    window.globalAudio.playSfx('button_click');
                }

                // Efek denyut highlight lokal saat user mewarnai
                el.classList.remove('live-shape-pulse');
                void el.offsetWidth;
                el.classList.add('live-shape-pulse');
                setTimeout(() => el.classList.remove('live-shape-pulse'), 1400);

                // Kirim pembaruan live secara instan ke anggota kelompok lainnya
                sendLiveColorUpdate(id, paintColor);

                autoSaveRoomData();
            }
        }

        function colorSvgPart(id) {
            colorCanvasPart(id);
        }

        function resetCurrentCanvas() {
            if (window.globalAudio && typeof window.globalAudio.playSfx === 'function') {
                window.globalAudio.playSfx('button_click');
            }
            const modal = document.getElementById('resetCanvasModal');
            const box = document.getElementById('resetCanvasBox');
            if (modal) { modal.classList.remove('opacity-0', 'pointer-events-none'); modal.classList.add('opacity-100'); }
            if (box) { box.classList.remove('scale-90'); box.classList.add('scale-100'); }
        }

        function closeResetCanvasModal() {
            const modal = document.getElementById('resetCanvasModal');
            const box = document.getElementById('resetCanvasBox');
            if (modal) { modal.classList.remove('opacity-100'); modal.classList.add('opacity-0', 'pointer-events-none'); }
            if (box) { box.classList.remove('scale-100'); box.classList.add('scale-90'); }
        }

        function executeResetCurrentCanvas() {
            closeResetCanvasModal();

            if (window.globalAudio && typeof window.globalAudio.playSfx === 'function') {
                window.globalAudio.playSfx('button_click');
            }

            const vp = document.getElementById('canvasViewport-' + activeColoringCanvasNum);
            if (vp) {
                const parts = vp.querySelectorAll('.colorable-part');
                parts.forEach(p => {
                    p.setAttribute('fill', 'white');
                    delete currentRoomColoringData[p.id];
                    const rev = document.getElementById('review-' + p.id);
                    if (rev) rev.setAttribute('fill', 'white');
                    const ident = document.getElementById('ident-' + p.id);
                    if (ident) ident.setAttribute('fill', 'white');
                });
            }
            sendLiveResetCanvas(activeColoringCanvasNum);
            autoSaveRoomData();
            showKolabRefreshToast('Warna sketsa berhasil direset!');
        }

        // Global click delegation for all colorable parts across 4 canvases
        document.addEventListener('click', (e) => {
            const colorable = e.target.closest('.colorable-part');
            if (colorable && colorable.id && !colorable.id.startsWith('review-') && !colorable.id.startsWith('admin-')) {
                colorCanvasPart(colorable.id);
            }
        });

        var isRefreshingKolab = false;
        async function handleKolabRefresh() {
            if (isRefreshingKolab) return;
            isRefreshingKolab = true;

            if (window.globalAudio && typeof window.globalAudio.playSfx === 'function') {
                window.globalAudio.playSfx('button_click');
            }

            const icon = document.getElementById('btnRefreshIcon');
            if (icon) {
                icon.classList.add('animate-spin');
            }

            try {
                if (currentRoomNumber) {
                    await loadRoomOnlineData(currentRoomNumber, true);
                    if (currentActiveKolabStage === '4B' || currentActiveKolabStage === '4') {
                        renderIdentificationForm();
                    } else if (currentActiveKolabStage === '5') {
                        syncReviewColoring();
                        syncReviewIdentification();
                    }
                } else {
                    await fetchRoomsStatus();
                }
                showKolabRefreshToast('Data berhasil diperbarui!');
            } catch (e) {
                console.error('Refresh error:', e);
            } finally {
                setTimeout(() => {
                    if (icon) icon.classList.remove('animate-spin');
                    isRefreshingKolab = false;
                }, 600);
            }
        }

        function showKolabRefreshToast(msg) {
            let toast = document.getElementById('kolabRefreshToast');
            if (!toast) {
                toast = document.createElement('div');
                toast.id = 'kolabRefreshToast';
                toast.className = 'fixed top-16 right-4 sm:right-6 z-50 bg-[#15803D] text-white text-xs sm:text-sm font-bold px-4 py-2 rounded-full shadow-lg border-2 border-white flex items-center gap-2 transition-all duration-300 pointer-events-none opacity-0 translate-y-[-10px]';
                toast.innerHTML = '<svg class="w-4 h-4 text-white shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg><span id="kolabRefreshToastText"></span>';
                document.body.appendChild(toast);
            }
            const textEl = document.getElementById('kolabRefreshToastText');
            if (textEl) textEl.textContent = msg || 'Data berhasil diperbarui!';
            toast.classList.remove('opacity-0', 'translate-y-[-10px]');
            toast.classList.add('opacity-100', 'translate-y-0');
            setTimeout(() => {
                toast.classList.remove('opacity-100', 'translate-y-0');
                toast.classList.add('opacity-0', 'translate-y-[-10px]');
            }, 2200);
        }

        function confirmExitAction() {
            sendLeaveBeacon();
            var f = document.getElementById('globalLogoutForm');
            if (f) f.submit();
        }

        function sendLeaveBeacon() {
            if (currentRoomNumber) {
                const token = getCsrfToken();
                const body = new URLSearchParams({ _token: token, room_number: String(currentRoomNumber) });
                if (navigator.sendBeacon) {
                    navigator.sendBeacon('/api/kolaborasi/leave', body);
                } else if (window.fetch) {
                    fetch('/api/kolaborasi/leave', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: body.toString(),
                        keepalive: true
                    });
                }
            }
        }

        function cleanupKolabTimers() {
            if (kolabPollingTimer) { clearInterval(kolabPollingTimer); kolabPollingTimer = null; }
            if (liveColorPollingTimer) { clearInterval(liveColorPollingTimer); liveColorPollingTimer = null; }
        }
        window.addEventListener('pagehide', () => { cleanupKolabTimers(); sendLeaveBeacon(); });
        window.addEventListener('beforeunload', () => { cleanupKolabTimers(); sendLeaveBeacon(); });

        var kolabPollingTimer = null;
        var isPollingInProgress = false;
        var liveColorPollingTimer = null;
        var isLiveColorPolling = false;
        var lastProcessedColorTime = 0;
        var liveActivityToastTimer = null;

        function showLiveActivityToast(message, color) {
            const toast = document.getElementById('liveColorActivityToast');
            const textEl = document.getElementById('liveColorActivityText');
            const dotEl = document.getElementById('liveColorActivityDot');
            if (!toast) return;

            if (textEl) textEl.textContent = message;
            if (dotEl) {
                dotEl.style.backgroundColor = color || '#EF4444';
                dotEl.style.display = color ? 'inline-block' : 'none';
            }

            toast.classList.remove('hidden');
            void toast.offsetWidth;
            toast.classList.remove('opacity-0', '-translate-y-1', 'pointer-events-none');
            toast.classList.add('opacity-100', 'translate-y-0');

            if (liveActivityToastTimer) clearTimeout(liveActivityToastTimer);
            liveActivityToastTimer = setTimeout(() => {
                toast.classList.remove('opacity-100', 'translate-y-0');
                toast.classList.add('opacity-0', '-translate-y-1', 'pointer-events-none');
                setTimeout(() => toast.classList.add('hidden'), 300);
            }, 2400);
        }

        function flashTabBadge(canvasNum) {
            const badge = document.getElementById(`tabBadge-${canvasNum}`);
            if (badge) {
                badge.classList.remove('hidden');
                setTimeout(() => badge.classList.add('hidden'), 2500);
            }
        }

        function handleIncomingLiveColor(data) {
            if (!data) return;

            const coloring = data.coloring_data || {};
            const lastColored = data.last_colored;

            // 1. Cek apakah ada aksi mewarnai terbaru dari teman satu tim
            if (lastColored && lastColored.time && lastColored.time > lastProcessedColorTime) {
                lastProcessedColorTime = lastColored.time;

                const isFromMe = String(lastColored.user_id) === String(currentUserId);
                const userName = lastColored.user_name || 'Teman';
                const canvasNum = lastColored.canvas_num || 1;

                if (!isFromMe) {
                    if (lastColored.type === 'reset') {
                        const vp = document.getElementById('canvasViewport-' + canvasNum);
                        if (vp) {
                            vp.querySelectorAll('.colorable-part').forEach(p => {
                                p.setAttribute('fill', 'white');
                                delete currentRoomColoringData[p.id];
                                const rev = document.getElementById('review-' + p.id);
                                if (rev) rev.setAttribute('fill', 'white');
                                const ident = document.getElementById('ident-' + p.id);
                                if (ident) ident.setAttribute('fill', 'white');
                            });
                        }
                        showLiveActivityToast(`🔄 ${userName} mereset warna di Sisi ${canvasNum}!`, '#B45309');
                    } else if (lastColored.part_id) {
                        const rawKey = String(lastColored.part_id).replace(/^review-/, '').replace(/^ident-/, '');
                        const color = lastColored.color;

                        // Update langsung di kanvas sketsa
                        const el = document.getElementById(rawKey);
                        if (el) {
                            el.setAttribute('fill', color);
                            // Efek denyut highlight agar teman langsung tahu bentuk mana yang sedang diubah!
                            el.classList.remove('live-shape-pulse');
                            void el.offsetWidth;
                            el.classList.add('live-shape-pulse');
                            setTimeout(() => el.classList.remove('live-shape-pulse'), 1400);
                        }

                        const revEl = document.getElementById('review-' + rawKey);
                        if (revEl) revEl.setAttribute('fill', color);

                        const identEl = document.getElementById('ident-' + rawKey);
                        if (identEl) {
                            identEl.setAttribute('fill', color);
                            identEl.classList.remove('live-shape-pulse');
                            void identEl.offsetWidth;
                            identEl.classList.add('live-shape-pulse');
                            setTimeout(() => identEl.classList.remove('live-shape-pulse'), 1400);
                        }

                        currentRoomColoringData[rawKey] = color;

                        // Notifikasi banner live
                        showLiveActivityToast(`🎨 ${userName} sedang mewarnai Sisi ${canvasNum}!`, color);

                        // Jika user sedang di sisi kanvas yang berbeda, kedipkan badge di tab sisi tersebut
                        if (activeColoringCanvasNum != canvasNum) {
                            flashTabBadge(canvasNum);
                        }
                    }
                }
            }

            // 2. Sinkronisasi warna penuh
            Object.keys(coloring).forEach(partKey => {
                if (partKey.startsWith('_')) return;
                const fillColor = coloring[partKey];
                const rawKey = String(partKey).replace(/^review-/, '').replace(/^ident-/, '');

                if (currentRoomColoringData[rawKey] !== fillColor) {
                    currentRoomColoringData[rawKey] = fillColor;
                    const el = document.getElementById(rawKey);
                    if (el) el.setAttribute('fill', fillColor);
                    const revEl = document.getElementById('review-' + rawKey);
                    if (revEl) revEl.setAttribute('fill', fillColor);
                    const identEl = document.getElementById('ident-' + rawKey);
                    if (identEl) identEl.setAttribute('fill', fillColor);
                }
            });
        }

        function startLiveColorPolling() {
            if (liveColorPollingTimer) clearInterval(liveColorPollingTimer);
            liveColorPollingTimer = setInterval(async () => {
                if (document.hidden || isLiveColorPolling || !currentRoomNumber) return;
                // Hanya polling live coloring saat siswa berada di Stage 4A (Misi 1: Mewarnai)
                if (String(currentActiveKolabStage) !== '4A' && String(currentActiveKolabStage) !== '4') return;
                isLiveColorPolling = true;
                try {
                    const res = await fetch(`/api/kolaborasi/room/${currentRoomNumber}/live-coloring`, {
                        headers: { 'Accept': 'application/json' }
                    });
                    if (!res.ok) return;
                    const data = await res.json();
                    if (data.status === 'success') {
                        handleIncomingLiveColor(data);
                    }
                } catch (e) {
                    // ignore network error
                } finally {
                    isLiveColorPolling = false;
                }
            }, 1800);
        }

        let kolabPollConsecutiveErrors = 0;
        function startKolabPolling() {
            if (kolabPollingTimer) clearTimeout(kolabPollingTimer);

            const scheduleNextPoll = () => {
                const baseDelay = 3000;
                const delay = Math.min(baseDelay * Math.pow(1.5, kolabPollConsecutiveErrors), 15000);
                kolabPollingTimer = setTimeout(runPoll, delay);
            };

            const runPoll = async () => {
                if (document.hidden || isPollingInProgress) {
                    scheduleNextPoll();
                    return;
                }
                isPollingInProgress = true;
                try {
                    if (currentRoomNumber) {
                        await loadRoomOnlineData(currentRoomNumber, false);
                    } else if (currentActiveKolabStage === '1') {
                        await fetchRoomsStatus();
                    }
                    kolabPollConsecutiveErrors = 0;
                } catch (e) {
                    kolabPollConsecutiveErrors++;
                    console.error('Polling error:', e);
                } finally {
                    isPollingInProgress = false;
                    scheduleNextPoll();
                }
            };

            scheduleNextPoll();
            startLiveColorPolling();
        }

        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
                kolabPollConsecutiveErrors = 0;
                if (currentRoomNumber) {
                    loadRoomOnlineData(currentRoomNumber, false);
                } else if (currentActiveKolabStage === '1') {
                    fetchRoomsStatus();
                }
            }
        });

        async function initKolaborasiPage() {
            renderIdentificationForm();
            initIdentCanvasZoomPan();

            const savedRoom = sessionStorage.getItem('tara_kolab_room');
            const savedStage = sessionStorage.getItem('tara_kolab_stage');
            if (savedRoom && savedStage && savedStage !== '1') {
                const roomNum = parseInt(savedRoom, 10);
                if (!isNaN(roomNum)) {
                    currentRoomNumber = roomNum;
                evalRowStates = createEmptyEvalRowStates();
                    goToKolabStage(savedStage);
                    // Ensure user membership is refreshed and recognized on server
                    try {
                        const token = getCsrfToken();
                        await fetch('/api/kolaborasi/join', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': token
                            },
                            body: JSON.stringify({ room_number: roomNum, _token: token })
                        });
                    } catch (e) { }
                    await loadRoomOnlineData(roomNum, true);
                    startKolabPolling();
                    return;
                }
            }
            await fetchRoomsStatus();
            startKolabPolling();
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initKolaborasiPage);
        } else {
            initKolaborasiPage();
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeExitModal();
                if (typeof closeCreateRoomModal === 'function') closeCreateRoomModal();
                closeConfirmSubmitModal();
                if (typeof closeResetCanvasModal === 'function') closeResetCanvasModal();
                if (typeof closeResetTableModal === 'function') closeResetTableModal();
                if (typeof closeSettingsModal === 'function') closeSettingsModal();
            }
        });
    </script>
</body>

</html>