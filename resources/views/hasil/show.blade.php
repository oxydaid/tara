<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

    <title>Detail Hasil {{ $submission->group_name }} - TARA</title>
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
        .hasil-card {
            scrollbar-width: thin;
            scrollbar-color: rgba(217, 155, 56, 0.55) transparent;
            scrollbar-gutter: stable;
        }

        .hasil-card::-webkit-scrollbar,
        .hasil-scroll::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .hasil-card::-webkit-scrollbar-track,
        .hasil-scroll::-webkit-scrollbar-track {
            background: rgba(233, 195, 122, 0.14);
            border-radius: 999px;
            margin-block: 14px;
        }

        .hasil-card::-webkit-scrollbar-thumb,
        .hasil-scroll::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #E2AC52 0%, #C9842B 100%);
            border-radius: 999px;
            border: 2px solid #FFFDF5;
        }

        .hasil-svg-viewport {
            overflow: hidden;
        }

        .hasil-svg-viewport svg {
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
            .hasil-stage-wrapper {
                top: 60px !important;
                bottom: 8px !important;
                left: 19% !important;
                right: 10px !important;
            }
        }

        @media screen and (min-width: 1025px) {
            .hasil-card {
                height: auto !important;
                max-height: 100% !important;
            }
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-[100dvh] h-[100dvh] overflow-hidden font-['Jua'] select-none">
    @php
        $bg = asset('assets/berandamalam.webp');
        $members = is_array($submission->tasks_data) ? $submission->tasks_data : [];
        $identifications = is_array($submission->identification_data) ? $submission->identification_data : [];
        $evaluations = is_array($submission->evaluation_data) ? $submission->evaluation_data : [];
    @endphp

    <div class="landscape-force">
        <div data-app-root data-content
            style="background-image: url('{{ $bg }}'); background-size: cover; background-position: center;">

            <!-- TOP BAR KIRI: HOME -->
            <div class="absolute top-3 left-3 sm:top-5 sm:left-5 z-40">
                <x-btn-home href="{{ route('menu') }}" title="Kembali ke Beranda" />
            </div>

            <!-- TOP BAR KANAN: SETTINGS & EXIT -->
            <div class="absolute top-3 right-3 sm:top-5 sm:right-5 z-40 flex items-center gap-2 sm:gap-3">
                <x-btn-settings onclick="openSettingsModal()" title="Pengaturan Suara" />
                <x-btn-exit onclick="handleExitWeb()" title="Keluar" />
            </div>

            <!-- KARAKTER YUK DAMAR -->
            <div
                class="absolute z-20 pointer-events-none bottom-0 left-0 sm:left-2 md:left-4 lg:left-6 h-[88%] sm:h-[94%] md:h-[98%] max-h-[760px] flex items-end translate-y-[20%] select-none">
                <img src="{{ asset('assets/karakter_jempol.webp') }}" alt="Yuk Damar Apresiasi"
                    class="h-full w-auto max-h-full object-contain object-bottom drop-shadow-[0_18px_32px_rgba(0,0,0,0.4)] select-none pointer-events-none opacity-100">
            </div>

            <!-- KARTU DETAIL HASIL -->
            <div
                class="hasil-stage-wrapper absolute top-[50px] sm:top-[56px] bottom-2 sm:bottom-3 left-[23%] sm:left-[24%] md:left-[25%] right-2 sm:right-3 md:right-4 lg:top-[96px] lg:bottom-6 lg:left-[18%] lg:right-6 lg:w-auto lg:max-w-[1350px] z-30 flex items-center justify-start pointer-events-auto w-auto">
                <div
                    class="hasil-card relative w-full h-full overflow-y-auto bg-[#FFFDF5] border-4 border-[#E9C37A] rounded-[24px] sm:rounded-[34px] md:rounded-[42px] p-3 sm:p-5 md:p-6 shadow-[0_22px_50px_rgba(0,0,0,0.25),0_4px_12px_rgba(233,195,122,0.3)] space-y-4">

                    <!-- Ekor Balon Chat -->
                    <div
                        class="absolute -left-[20px] sm:-left-[26px] top-10 sm:top-14 -translate-y-1/2 w-0 h-0 border-t-[12px] sm:border-t-[16px] border-t-transparent border-r-[20px] sm:border-r-[26px] border-r-[#E9C37A] border-b-[12px] sm:border-b-[16px] border-b-transparent">
                    </div>
                    <div
                        class="absolute -left-[14px] sm:-left-[18px] top-10 sm:top-14 -translate-y-1/2 w-0 h-0 border-t-[12px] sm:border-t-[16px] border-t-transparent border-r-[20px] sm:border-r-[26px] border-r-[#FFFDF5] border-b-[12px] sm:border-b-[16px] border-b-transparent">
                    </div>

                    <!-- Header Detail -->
                    <div class="flex items-center justify-between flex-wrap gap-2 pb-2 border-b border-[#E9C37A]/50">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#FFF2D6] text-[#8A4416] border border-[#E9C37A]">
                                Ruang {{ $submission->room_number }} &bull; {{ $submission->theme ?: 'Motif Damar Kurung' }}
                            </span>
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                Sudah Selesai
                            </span>
                        </div>
                        <a href="{{ route('hasil') }}"
                           class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-white hover:bg-[#FFF2D6] border border-[#E9C37A] text-xs font-bold text-[#8A4416] transition-all shadow-2xs">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            <span>Kembali ke Daftar Hasil</span>
                        </a>
                    </div>

                    <!-- Info Kelompok -->
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-2.5 bg-white rounded-2xl border-2 border-[#E9C37A] p-3 sm:p-4 shadow-2xs">
                        <div>
                            <span class="text-[11px] text-[#8A4416]/80 block font-bold">Nama Kelompok:</span>
                            <span class="text-sm sm:text-base font-bold text-[#3D1804]">{{ $submission->group_name }}</span>
                        </div>
                        <div>
                            <span class="text-[11px] text-[#8A4416]/80 block font-bold">Ketua Kelompok:</span>
                            <span class="text-sm sm:text-base font-bold text-[#E57354]">{{ $submission->leader_name ?: '-' }}</span>
                        </div>
                        <div>
                            <span class="text-[11px] text-[#8A4416]/80 block font-bold">Jumlah Anggota:</span>
                            <span class="text-sm sm:text-base font-bold text-[#548466]">{{ count($members) }} Siswa</span>
                        </div>
                        <div>
                            <span class="text-[11px] text-[#8A4416]/80 block font-bold">Waktu Dikumpulkan:</span>
                            <span class="text-xs sm:text-sm font-bold text-[#3D1804]">
                                {{ $submission->submitted_at ? $submission->submitted_at->format('d M Y, H:i') : ($submission->created_at ? $submission->created_at->format('d M Y, H:i') : '-') }}
                            </span>
                        </div>
                    </div>

                    <!-- Grid Karya Pewarnaan + Anggota Kelompok -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-3 sm:gap-4">
                        <!-- Karya Pewarnaan 4 Sisi -->
                        <div class="lg:col-span-5 bg-white rounded-2xl border-2 border-[#E9C37A] p-3 sm:p-4 shadow-2xs flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between gap-2 mb-2 flex-wrap">
                                    <h2 class="text-xs sm:text-sm font-bold text-[#8A4416]">Misi 1: Karya Pewarnaan 4 Sisi</h2>
                                    <div class="flex items-center gap-1">
                                        @for ($sisi = 1; $sisi <= 4; $sisi++)
                                            <button type="button"
                                                    onclick="selectHasilCanvas({{ $sisi }})"
                                                    id="btnHasilTabCanvas{{ $sisi }}"
                                                    class="px-2.5 py-0.5 rounded-full text-[11px] font-bold transition-all cursor-pointer {{ $sisi === 1 ? 'bg-[#E57354] text-white shadow-2xs' : 'bg-[#FFF2D6] text-[#8A4416] border border-[#E9C37A] hover:bg-[#FFE8B8]' }}">
                                                Sisi {{ $sisi }}
                                            </button>
                                        @endfor
                                    </div>
                                </div>

                                <div class="bg-[#FFF9EA] rounded-xl border border-[#E9C37A]/70 p-3 flex items-center justify-center min-h-[220px]">
                                    @for ($sisi = 1; $sisi <= 4; $sisi++)
                                        <div id="hasilCanvasViewport-{{ $sisi }}" class="hasil-svg-viewport w-full {{ $sisi === 1 ? '' : 'hidden' }}">
                                            {!! $canvases[$sisi] ?? '' !!}
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>

                        <!-- Anggota Kelompok -->
                        <div class="lg:col-span-7 bg-white rounded-2xl border-2 border-[#E9C37A] p-3 sm:p-4 shadow-2xs">
                            <h2 class="text-xs sm:text-sm font-bold text-[#8A4416] mb-2">Tabel 1: Anggota Tim Kolaborasi</h2>
                            <div class="hasil-scroll overflow-x-auto rounded-xl border border-[#E9C37A]/70">
                                <table class="w-full text-left text-xs sm:text-sm">
                                    <thead>
                                        <tr class="bg-gradient-to-r from-[#8A4416] to-[#6E330C] text-white">
                                            <th class="p-2 text-center w-10 border-r border-white/20">No</th>
                                            <th class="p-2 border-r border-white/20">Nama Siswa</th>
                                            <th class="p-2 text-center w-24 border-r border-white/20">No. Absen</th>
                                            <th class="p-2 text-center">Peran</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[#E9C37A]/30">
                                        @forelse ($members as $idx => $m)
                                            <tr class="bg-[#FFFDF7]">
                                                <td class="p-2 text-center font-bold text-[#8A4416]">{{ $idx + 1 }}</td>
                                                <td class="p-2 font-bold text-[#3D1804]">{{ $m['user_name'] ?? '-' }}</td>
                                                <td class="p-2 text-center font-bold text-[#8A4416]">{{ $m['no_absen'] ?? '-' }}</td>
                                                <td class="p-2 text-center">
                                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                                        {{ $m['role'] ?? 'Kolaborator' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="p-3 text-center text-stone-400 italic text-xs">Tidak ada data anggota tersimpan.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel 2: Identifikasi Bangun Datar -->
                    <div class="bg-white rounded-2xl border-2 border-[#E9C37A] p-3 sm:p-4 shadow-2xs">
                        <h2 class="text-xs sm:text-sm font-bold text-[#8A4416] mb-2">Misi 2: Hasil Identifikasi Bangun Datar</h2>
                        @if (!empty($identificationItems))
                            <div class="hasil-scroll overflow-x-auto rounded-xl border border-[#E9C37A]/70">
                                <table class="w-full text-left text-xs sm:text-sm min-w-[480px]">
                                    <thead>
                                        <tr class="bg-gradient-to-r from-[#2D619B] to-[#1D487A] text-white">
                                            <th class="p-2 text-center w-10 border-r border-white/20">No</th>
                                            <th class="p-2 w-24 text-center border-r border-white/20">Bentuk</th>
                                            <th class="p-2 w-1/4 border-r border-white/20">Bangun Datar</th>
                                            <th class="p-2">Ciri-Ciri</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[#E9C37A]/30">
                                        @foreach ($identificationItems as $idx => $item)
                                            <tr class="bg-[#FFFDF7]">
                                                <td class="p-2 text-center font-bold text-[#1D487A] align-middle">{{ $idx + 1 }}</td>
                                                <td class="p-2 align-middle text-center">
                                                    @if (!empty($item['benda_svg']))
                                                        <div class="w-14 h-12 rounded-lg bg-white border-2 border-[#E9C37A] flex items-center justify-center p-1 shadow-2xs mx-auto"
                                                             title="{{ $item['benda_label'] }}">
                                                            {!! $item['benda_svg'] !!}
                                                        </div>
                                                    @else
                                                        <span class="font-bold text-[#3D1804]">{{ $item['benda_label'] ?: '-' }}</span>
                                                    @endif
                                                </td>
                                                <td class="p-2 font-bold text-[#7C3AED] align-middle">
                                                    <div class="inline-flex items-center gap-2">
                                                        @if (!empty($item['shape_custom_image']))
                                                            <img src="{{ $item['shape_custom_image'] }}" alt="{{ $item['shape_label'] }}" class="w-6 h-6 object-contain shrink-0">
                                                        @elseif (!empty($item['shape_svg']))
                                                            <span class="w-6 h-6 inline-flex items-center justify-center shrink-0" style="color: {{ $item['shape_color'] }}">
                                                                {!! $item['shape_svg'] !!}
                                                            </span>
                                                        @endif
                                                        <span>{{ $item['shape_label'] }}</span>
                                                    </div>
                                                </td>
                                                <td class="p-2 text-[#3D1804] align-middle">{{ $item['ciri'] !== '' ? $item['ciri'] : '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-xs text-stone-500 italic">Belum ada data identifikasi bangun datar yang tercatat.</p>
                        @endif
                    </div>

                    <!-- Tabel 3: Evaluasi & Apresiasi -->
                    <div class="bg-white rounded-2xl border-2 border-[#E9C37A] p-3 sm:p-4 shadow-2xs">
                        <h2 class="text-xs sm:text-sm font-bold text-[#8A4416] mb-2">Misi 3: Evaluasi & Apresiasi Antarteman</h2>
                        @if (!empty($evaluations))
                            <div class="hasil-scroll overflow-x-auto rounded-xl border border-[#E9C37A]/70">
                                <table class="w-full text-left text-xs sm:text-sm min-w-[520px]">
                                    <thead>
                                        <tr class="bg-gradient-to-r from-[#548466] to-[#3D694D] text-white">
                                            <th class="p-2 text-center w-10 border-r border-white/20">No</th>
                                            <th class="p-2 border-r border-white/20">Penilai</th>
                                            <th class="p-2 border-r border-white/20">Teman yang Dinilai</th>
                                            <th class="p-2 text-center border-r border-white/20">No. Absen</th>
                                            <th class="p-2 border-r border-white/20">Penilaian</th>
                                            <th class="p-2">Pesan Apresiasi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[#E9C37A]/30">
                                        @foreach ($evaluations as $idx => $ev)
                                            <tr class="bg-[#FFFDF7]">
                                                <td class="p-2 text-center font-bold text-[#548466]">{{ $idx + 1 }}</td>
                                                <td class="p-2 font-bold text-[#3D1804]">{{ $ev['evaluator_name'] ?? '-' }}</td>
                                                <td class="p-2 font-bold text-[#15803D]">{{ $ev['peer_name'] ?? '-' }}</td>
                                                <td class="p-2 text-center font-bold text-[#8A4416]">{{ $ev['peer_no_absen'] ?? '-' }}</td>
                                                <td class="p-2 font-bold text-[#3D1804]">{{ $ev['rating'] ?? '-' }}</td>
                                                <td class="p-2 text-[#5C2B09] italic">“{{ ($ev['note'] ?? '') !== '' ? $ev['note'] : '-' }}”</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-xs text-stone-500 italic">Belum ada catatan apresiasi yang tersimpan.</p>
                        @endif
                    </div>

                </div>
            </div>

            <!-- TOMBOL KEMBALI BAWAH -->
            <div class="absolute bottom-3 left-3 sm:bottom-5 sm:left-5 z-40">
                <x-btn-back href="{{ route('hasil') }}" title="Kembali ke Daftar Hasil" />
            </div>

            <x-sound-settings :renderTrigger="false" />
            <x-exit-modal />
        </div>
    </div>

    <script>
        const HASIL_COLORING_DATA = @json($sanitizedColors);

        function applyHasilColors() {
            if (!HASIL_COLORING_DATA || typeof HASIL_COLORING_DATA !== 'object') return;
            Object.entries(HASIL_COLORING_DATA).forEach(([partKey, color]) => {
                const rawKey = String(partKey).replace(/^review-/, '').replace(/^ident-/, '');
                const el = document.getElementById('hasil-' + rawKey);
                if (el) {
                    el.setAttribute('fill', color);
                }
            });
        }

        function selectHasilCanvas(num) {
            for (let i = 1; i <= 4; i++) {
                const btn = document.getElementById('btnHasilTabCanvas' + i);
                const vp = document.getElementById('hasilCanvasViewport-' + i);
                if (btn) {
                    btn.className = i === num
                        ? 'px-2.5 py-0.5 rounded-full text-[11px] font-bold transition-all cursor-pointer bg-[#E57354] text-white shadow-2xs'
                        : 'px-2.5 py-0.5 rounded-full text-[11px] font-bold transition-all cursor-pointer bg-[#FFF2D6] text-[#8A4416] border border-[#E9C37A] hover:bg-[#FFE8B8]';
                }
                if (vp) {
                    vp.classList.toggle('hidden', i !== num);
                }
            }
        }

        document.addEventListener('DOMContentLoaded', applyHasilColors);
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                if (typeof closeExitModal === 'function') closeExitModal();
                if (typeof closeSettingsModal === 'function') closeSettingsModal();
            }
        });
    </script>
</body>

</html>
