@props([
    'uid' => 'dk',
    'canvasesCube' => [],
    'canvasesFlat' => [],
    'sanitizedColors' => [],
    'theme' => 'student',
    'defaultSide' => 1,
    'title' => 'Misi 1: Karya Pewarnaan 4 Sisi',
])

@php
    $isAdmin = $theme === 'admin';
    $cubeCanvases = ! empty($canvasesCube)
        ? $canvasesCube
        : \App\Support\CollaborationSubmissionFormatter::buildCanvases("{$uid}-cube-", "{$uid}-cube-part");
    $flatCanvases = ! empty($canvasesFlat)
        ? $canvasesFlat
        : \App\Support\CollaborationSubmissionFormatter::buildCanvases("{$uid}-flat-", "{$uid}-flat-part");
    $initialSide = max(1, min(4, (int) $defaultSide));
@endphp

@once
    <style>
        .dk-cube-stage {
            perspective: 920px;
            touch-action: none;
            user-select: none;
            cursor: grab;
        }

        .dk-cube-stage.is-dragging {
            cursor: grabbing;
        }

        .dk-cube-pivot {
            --dk-cube-size: 186px;
            --dk-cube-half: calc(var(--dk-cube-size) / 2);
            width: var(--dk-cube-size);
            height: var(--dk-cube-size);
            position: relative;
            transform-style: preserve-3d;
            will-change: transform;
        }

        .dk-cube-pivot.is-snapping {
            transition: transform 0.52s cubic-bezier(0.22, 1, 0.36, 1);
        }

        .dk-cube-face {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            overflow: hidden;
            box-sizing: border-box;
        }

        .dk-cube-face-side {
            background: #FFFDF7;
            border: 3px solid #6E330C;
            box-shadow: inset 0 0 18px rgba(245, 158, 11, 0.14);
        }

        .dk-cube-face-side svg {
            width: 100% !important;
            height: 100% !important;
            max-width: none !important;
            max-height: none !important;
            display: block;
            margin: 0 !important;
            filter: none !important;
        }

        .dk-cube-face--front {
            transform: rotateY(0deg) translateZ(var(--dk-cube-half));
        }

        .dk-cube-face--right {
            transform: rotateY(90deg) translateZ(var(--dk-cube-half));
        }

        .dk-cube-face--back {
            transform: rotateY(180deg) translateZ(var(--dk-cube-half));
        }

        .dk-cube-face--left {
            transform: rotateY(-90deg) translateZ(var(--dk-cube-half));
        }

        .dk-cube-face--top {
            transform: rotateX(90deg) translateZ(var(--dk-cube-half));
            background: radial-gradient(circle at center, #FFFBEB 0%, #FDE68A 48%, #B45309 100%);
            border: 4px solid #6E330C;
        }

        .dk-cube-face--bottom {
            transform: rotateX(-90deg) translateZ(var(--dk-cube-half));
            background: linear-gradient(135deg, #7E3E11 0%, #522204 100%);
            border: 4px solid #3D1804;
        }

        .dk-flat-viewport svg {
            width: 100%;
            max-width: 240px;
            height: auto;
            max-height: 240px;
            display: block;
            margin: 0 auto;
            overflow: hidden;
            filter: drop-shadow(0 6px 12px rgba(0, 0, 0, 0.1));
        }

        @media screen and (max-width: 1024px) {
            .dk-cube-pivot {
                --dk-cube-size: 162px;
            }
        }

        @media screen and (max-width: 1024px) and (orientation: portrait), screen and (max-height: 540px) {
            .dk-cube-stage {
                height: 206px !important;
                min-height: 206px !important;
            }
            .dk-cube-pivot {
                --dk-cube-size: 142px;
            }
        }
    </style>
@endonce

<div id="{{ $uid }}-cube-viewer-root"
     data-cube-viewer
     data-uid="{{ $uid }}"
     data-default-side="{{ $initialSide }}"
     class="flex flex-col justify-between h-full">
    <div>
        <!-- Header + Mode Toggle (Kubus 3D vs Tampilan Datar 2D) -->
        <div class="flex items-center justify-between gap-2 mb-2.5 flex-wrap">
            <h2 class="{{ $isAdmin ? 'font-bold text-base text-slate-800 flex items-center gap-2' : 'text-xs sm:text-sm font-bold text-[#8A4416]' }}">
                @if ($isAdmin)
                    <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                @endif
                <span>{{ $title }}</span>
            </h2>

            <div class="inline-flex items-center p-0.5 rounded-full {{ $isAdmin ? 'bg-slate-100 border border-slate-200' : 'bg-[#FFF2D6] border border-[#E9C37A]' }}">
                <button type="button"
                        id="{{ $uid }}-btn-mode-3d"
                        class="px-2.5 py-0.5 rounded-full text-[11px] font-bold transition-all cursor-pointer {{ $isAdmin ? 'bg-amber-500 text-white shadow-2xs' : 'bg-[#E57354] text-white shadow-2xs' }}">
                    Kubus 3D
                </button>
                <button type="button"
                        id="{{ $uid }}-btn-mode-2d"
                        class="px-2.5 py-0.5 rounded-full text-[11px] font-bold transition-all cursor-pointer {{ $isAdmin ? 'text-slate-600 hover:text-slate-900' : 'text-[#8A4416] hover:text-[#3D1804]' }}">
                    Tampilan Datar 2D
                </button>
            </div>
        </div>

        <!-- PANEL 1 (DEFAULT): KUBUS 3D INTERAKTIF -->
        <div id="{{ $uid }}-panel-3d" class="space-y-2">
            <div class="relative rounded-xl border {{ $isAdmin ? 'border-[#E9C37A]/70 bg-gradient-to-b from-[#FFF9EA] to-[#FDEFC8]' : 'border-[#E9C37A]/70 bg-gradient-to-b from-[#FFF9EA] to-[#FCECC5]' }} p-2.5 sm:p-3 overflow-hidden">
                <!-- Panggung 3D Kubus -->
                <div id="{{ $uid }}-cube-stage"
                     class="dk-cube-stage w-full h-[255px] sm:h-[270px] flex items-center justify-center relative">
                    <div id="{{ $uid }}-cube-pivot" class="dk-cube-pivot">
                        <!-- Sisi 1: Depan -->
                        <div class="dk-cube-face dk-cube-face-side dk-cube-face--front" data-cube-side="1">
                            {!! $cubeCanvases[1] ?? '' !!}
                            <span class="pointer-events-none absolute top-1 left-1 px-1.5 py-0.5 rounded bg-[#3D1804]/75 text-[#FFF2A6] text-[9px] font-bold">
                                Sisi 1
                            </span>
                        </div>
                        <!-- Sisi 2: Kanan -->
                        <div class="dk-cube-face dk-cube-face-side dk-cube-face--right" data-cube-side="2">
                            {!! $cubeCanvases[2] ?? '' !!}
                            <span class="pointer-events-none absolute top-1 left-1 px-1.5 py-0.5 rounded bg-[#3D1804]/75 text-[#FFF2A6] text-[9px] font-bold">
                                Sisi 2
                            </span>
                        </div>
                        <!-- Sisi 3: Belakang -->
                        <div class="dk-cube-face dk-cube-face-side dk-cube-face--back" data-cube-side="3">
                            {!! $cubeCanvases[3] ?? '' !!}
                            <span class="pointer-events-none absolute top-1 left-1 px-1.5 py-0.5 rounded bg-[#3D1804]/75 text-[#FFF2A6] text-[9px] font-bold">
                                Sisi 3
                            </span>
                        </div>
                        <!-- Sisi 4: Kiri -->
                        <div class="dk-cube-face dk-cube-face-side dk-cube-face--left" data-cube-side="4">
                            {!! $cubeCanvases[4] ?? '' !!}
                            <span class="pointer-events-none absolute top-1 left-1 px-1.5 py-0.5 rounded bg-[#3D1804]/75 text-[#FFF2A6] text-[9px] font-bold">
                                Sisi 4
                            </span>
                        </div>
                        <!-- Atas: Rangka Atap Lampion -->
                        <div class="dk-cube-face dk-cube-face--top flex items-center justify-center">
                            <div class="w-3/4 h-3/4 border-2 border-dashed border-[#8A4416]/50 rounded-lg flex items-center justify-center bg-white/35">
                                <span class="text-[10px] font-bold text-[#8A4416]/80 tracking-wide uppercase">Atas</span>
                            </div>
                        </div>
                        <!-- Bawah: Alas Damar Kurung -->
                        <div class="dk-cube-face dk-cube-face--bottom flex items-center justify-center">
                            <span class="text-[10px] font-bold text-[#FFE8A3]/85 tracking-wide uppercase">Alas Damar Kurung</span>
                        </div>
                    </div>
                </div>

                <!-- Badge Info & Kontrol Cepat Sisi 1-4 + Auto-Putar + Reset -->
                <div class="mt-2 pt-2 border-t border-[#E9C37A]/50 flex flex-wrap items-center justify-between gap-1.5">
                    <div class="flex items-center gap-1 flex-wrap">
                        @for ($sisi = 1; $sisi <= 4; $sisi++)
                            <button type="button"
                                    data-focus-side="{{ $sisi }}"
                                    class="dk-side-chip px-2 py-0.5 rounded-full text-[11px] font-bold transition-all cursor-pointer {{ $sisi === $initialSide ? ($isAdmin ? 'bg-amber-500 text-white shadow-2xs' : 'bg-[#E57354] text-white shadow-2xs') : 'bg-white text-[#8A4416] border border-[#E9C37A] hover:bg-[#FFF2D6]' }}">
                                Sisi {{ $sisi }}
                            </button>
                        @endfor
                    </div>

                    <div class="flex items-center gap-1">
                        <button type="button"
                                id="{{ $uid }}-btn-autorotate"
                                aria-pressed="true"
                                class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300 hover:bg-emerald-200 transition-all cursor-pointer">
                            Putar: Aktif
                        </button>
                        <button type="button"
                                id="{{ $uid }}-btn-reset"
                                class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-white text-[#8A4416] border border-[#E9C37A] hover:bg-[#FFF2D6] transition-all cursor-pointer">
                            Reset
                        </button>
                    </div>
                </div>
            </div>
            <p class="text-[11px] {{ $isAdmin ? 'text-slate-500' : 'text-[#8A4416]/85' }} text-center font-medium">
                Geser kubus ke segala arah untuk melihat 4 sisi Damar Kurung secara 3D.
            </p>
        </div>

        <!-- PANEL 2 (ALTERNATIF): TAMPILAN DATAR 2D PER SISI -->
        <div id="{{ $uid }}-panel-2d" class="hidden space-y-2.5">
            <div class="flex items-center justify-between gap-1.5 flex-wrap">
                <div class="flex items-center gap-1">
                    @for ($sisi = 1; $sisi <= 4; $sisi++)
                        <button type="button"
                                data-flat-side="{{ $sisi }}"
                                class="dk-flat-tab px-2.5 py-0.5 rounded-full text-[11px] font-bold transition-all cursor-pointer {{ $sisi === $initialSide ? ($isAdmin ? 'bg-amber-500 text-white shadow-2xs' : 'bg-[#E57354] text-white shadow-2xs') : ($isAdmin ? 'bg-slate-100 text-slate-700 hover:bg-slate-200' : 'bg-[#FFF2D6] text-[#8A4416] border border-[#E9C37A] hover:bg-[#FFE8B8]') }}">
                            Sisi {{ $sisi }}
                        </button>
                    @endfor
                </div>
            </div>

            <div class="bg-[#FFF9EA] rounded-xl border border-[#E9C37A]/70 p-3 flex items-center justify-center min-h-[220px]">
                @for ($sisi = 1; $sisi <= 4; $sisi++)
                    <div id="{{ $uid }}-flat-viewport-{{ $sisi }}" class="dk-flat-viewport w-full {{ $sisi === $initialSide ? '' : 'hidden' }}">
                        {!! $flatCanvases[$sisi] ?? '' !!}
                    </div>
                @endfor
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        const colorsPayload = @json($sanitizedColors);
        const root = document.getElementById('{{ $uid }}-cube-viewer-root');
        if (!root) return;

        const uid = root.dataset.uid;
        const isAdmin = @json($isAdmin);
        const defaultSide = parseInt(root.dataset.defaultSide || '1', 10) || 1;

        function applyCubeAndFlatColors() {
            if (!colorsPayload || typeof colorsPayload !== 'object') return;
            Object.entries(colorsPayload).forEach(([partKey, color]) => {
                const rawKey = String(partKey).replace(/^(review-|ident-|admin-|hasil-)/, '');
                const cubeEl = document.getElementById(`${uid}-cube-${rawKey}`);
                const flatEl = document.getElementById(`${uid}-flat-${rawKey}`);
                if (cubeEl) cubeEl.setAttribute('fill', color);
                if (flatEl) flatEl.setAttribute('fill', color);
            });
        }

        applyCubeAndFlatColors();

        const btnMode3d = document.getElementById(`${uid}-btn-mode-3d`);
        const btnMode2d = document.getElementById(`${uid}-btn-mode-2d`);
        const panel3d = document.getElementById(`${uid}-panel-3d`);
        const panel2d = document.getElementById(`${uid}-panel-2d`);

        const stage = document.getElementById(`${uid}-cube-stage`);
        const pivot = document.getElementById(`${uid}-cube-pivot`);
        const btnAutoRotate = document.getElementById(`${uid}-btn-autorotate`);
        const btnReset = document.getElementById(`${uid}-btn-reset`);
        const sideChips = root.querySelectorAll('.dk-side-chip');
        const flatTabs = root.querySelectorAll('.dk-flat-tab');

        const activeChipClass = isAdmin
            ? 'dk-side-chip px-2 py-0.5 rounded-full text-[11px] font-bold transition-all cursor-pointer bg-amber-500 text-white shadow-2xs'
            : 'dk-side-chip px-2 py-0.5 rounded-full text-[11px] font-bold transition-all cursor-pointer bg-[#E57354] text-white shadow-2xs';
        const inactiveChipClass = 'dk-side-chip px-2 py-0.5 rounded-full text-[11px] font-bold transition-all cursor-pointer bg-white text-[#8A4416] border border-[#E9C37A] hover:bg-[#FFF2D6]';

        const activeFlatClass = isAdmin
            ? 'dk-flat-tab px-2.5 py-0.5 rounded-full text-[11px] font-bold transition-all cursor-pointer bg-amber-500 text-white shadow-2xs'
            : 'dk-flat-tab px-2.5 py-0.5 rounded-full text-[11px] font-bold transition-all cursor-pointer bg-[#E57354] text-white shadow-2xs';
        const inactiveFlatClass = isAdmin
            ? 'dk-flat-tab px-2.5 py-0.5 rounded-full text-[11px] font-bold transition-all cursor-pointer bg-slate-100 text-slate-700 hover:bg-slate-200'
            : 'dk-flat-tab px-2.5 py-0.5 rounded-full text-[11px] font-bold transition-all cursor-pointer bg-[#FFF2D6] text-[#8A4416] border border-[#E9C37A] hover:bg-[#FFE8B8]';

        let rotX = -12;
        let rotY = -((defaultSide - 1) * 90) - 24;
        let isDragging = false;
        let lastX = 0;
        let lastY = 0;
        let autoRotateEnabled = true;
        let currentMode = '3d';
        let rafId = null;

        function isLandscapeForced() {
            return window.innerWidth <= 1024
                && window.innerHeight > window.innerWidth
                && Boolean(stage && stage.closest('.landscape-force'));
        }

        function syncSideChipHighlight() {
            const norm = ((((-rotY) % 360) + 360) % 360);
            const facingSide = (Math.round(norm / 90) % 4) + 1;
            sideChips.forEach(chip => {
                const s = parseInt(chip.dataset.focusSide || '0', 10);
                chip.className = s === facingSide ? activeChipClass : inactiveChipClass;
            });
        }

        function applyCubeTransform(withTransition = false) {
            if (!pivot) return;
            pivot.classList.toggle('is-snapping', withTransition);
            pivot.style.transform = `rotateX(${rotX.toFixed(2)}deg) rotateY(${rotY.toFixed(2)}deg)`;
            syncSideChipHighlight();
        }

        function setAutoRotate(enabled) {
            autoRotateEnabled = enabled;
            if (btnAutoRotate) {
                btnAutoRotate.setAttribute('aria-pressed', enabled ? 'true' : 'false');
                btnAutoRotate.textContent = enabled ? 'Putar: Aktif' : 'Putar: Jeda';
                btnAutoRotate.className = enabled
                    ? 'px-2 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300 hover:bg-emerald-200 transition-all cursor-pointer'
                    : 'px-2 py-0.5 rounded-full text-[11px] font-bold bg-white text-[#8A4416] border border-[#E9C37A] hover:bg-[#FFF2D6] transition-all cursor-pointer';
            }
        }

        function tickAutoRotate() {
            if (autoRotateEnabled && !isDragging && currentMode === '3d') {
                rotY += 0.28;
                applyCubeTransform(false);
            }
            rafId = requestAnimationFrame(tickAutoRotate);
        }

        function focusSide(sideNum) {
            setAutoRotate(false);
            const targetBase = -(sideNum - 1) * 90;
            const delta = ((((targetBase - rotY) % 360) + 540) % 360) - 180;
            rotY += delta;
            rotX = -10;
            applyCubeTransform(true);
        }

        function resetView() {
            rotX = -12;
            rotY = -((defaultSide - 1) * 90) - 24;
            applyCubeTransform(true);
            setAutoRotate(true);
        }

        function switchMode(mode) {
            currentMode = mode;
            const is3d = mode === '3d';
            if (panel3d) panel3d.classList.toggle('hidden', !is3d);
            if (panel2d) panel2d.classList.toggle('hidden', is3d);

            const activeBtn = isAdmin
                ? 'px-2.5 py-0.5 rounded-full text-[11px] font-bold transition-all cursor-pointer bg-amber-500 text-white shadow-2xs'
                : 'px-2.5 py-0.5 rounded-full text-[11px] font-bold transition-all cursor-pointer bg-[#E57354] text-white shadow-2xs';
            const inactiveBtn = isAdmin
                ? 'px-2.5 py-0.5 rounded-full text-[11px] font-bold transition-all cursor-pointer text-slate-600 hover:text-slate-900'
                : 'px-2.5 py-0.5 rounded-full text-[11px] font-bold transition-all cursor-pointer text-[#8A4416] hover:text-[#3D1804]';

            if (btnMode3d) btnMode3d.className = is3d ? activeBtn : inactiveBtn;
            if (btnMode2d) btnMode2d.className = !is3d ? activeBtn : inactiveBtn;
        }

        function selectFlatSide(sideNum) {
            flatTabs.forEach(tab => {
                const s = parseInt(tab.dataset.flatSide || '0', 10);
                tab.className = s === sideNum ? activeFlatClass : inactiveFlatClass;
            });
            for (let i = 1; i <= 4; i++) {
                const vp = document.getElementById(`${uid}-flat-viewport-${i}`);
                if (vp) vp.classList.toggle('hidden', i !== sideNum);
            }
        }

        if (stage) {
            stage.addEventListener('pointerdown', (e) => {
                if (e.button !== undefined && e.button !== 0 && e.pointerType === 'mouse') return;
                isDragging = true;
                lastX = e.clientX;
                lastY = e.clientY;
                stage.classList.add('is-dragging');
                if (pivot) pivot.classList.remove('is-snapping');
                setAutoRotate(false);
                try {
                    stage.setPointerCapture(e.pointerId);
                } catch {}
            });

            stage.addEventListener('pointermove', (e) => {
                if (!isDragging) return;
                const rawDx = e.clientX - lastX;
                const rawDy = e.clientY - lastY;
                lastX = e.clientX;
                lastY = e.clientY;

                const visualDx = isLandscapeForced() ? rawDy : rawDx;
                const visualDy = isLandscapeForced() ? -rawDx : rawDy;

                rotY += visualDx * 0.6;
                rotX = Math.max(-75, Math.min(75, rotX - visualDy * 0.45));
                applyCubeTransform(false);
            });

            const endDrag = (e) => {
                if (!isDragging) return;
                isDragging = false;
                stage.classList.remove('is-dragging');
                try {
                    if (e && e.pointerId !== undefined) {
                        stage.releasePointerCapture(e.pointerId);
                    }
                } catch {}
            };

            stage.addEventListener('pointerup', endDrag);
            stage.addEventListener('pointercancel', endDrag);
        }

        sideChips.forEach(chip => {
            chip.addEventListener('click', () => {
                const s = parseInt(chip.dataset.focusSide || '1', 10);
                focusSide(s);
            });
        });

        flatTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const s = parseInt(tab.dataset.flatSide || '1', 10);
                selectFlatSide(s);
            });
        });

        if (btnAutoRotate) {
            btnAutoRotate.addEventListener('click', () => {
                setAutoRotate(!autoRotateEnabled);
            });
        }

        if (btnReset) {
            btnReset.addEventListener('click', resetView);
        }

        if (btnMode3d) btnMode3d.addEventListener('click', () => switchMode('3d'));
        if (btnMode2d) btnMode2d.addEventListener('click', () => switchMode('2d'));

        applyCubeTransform(false);
        selectFlatSide(defaultSide);
        if (typeof requestAnimationFrame === 'function') {
            rafId = requestAnimationFrame(tickAutoRotate);
        }

        window.addEventListener('pagehide', () => {
            if (rafId !== null) cancelAnimationFrame(rafId);
        });
    })();
</script>
