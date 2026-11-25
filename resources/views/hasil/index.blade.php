<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

    <title>Cek Hasil Kolaborasi - TARA</title>
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
        $roomStyles = [
            1 => ['border' => 'border-[#E57354]', 'badge' => 'from-[#E57354] to-[#C94F32]', 'btn' => 'bg-[#E57354] hover:bg-[#D95F3F]'],
            2 => ['border' => 'border-[#548466]', 'badge' => 'from-[#548466] to-[#3D694D]', 'btn' => 'bg-[#548466] hover:bg-[#3D694D]'],
            3 => ['border' => 'border-[#ECA144]', 'badge' => 'from-[#ECA144] to-[#D4892A]', 'btn' => 'bg-[#ECA144] hover:bg-[#D4892A]'],
            4 => ['border' => 'border-[#8B5CF6]', 'badge' => 'from-[#8B5CF6] to-[#6D28D9]', 'btn' => 'bg-[#8B5CF6] hover:bg-[#7C3AED]'],
            5 => ['border' => 'border-[#0284C7]', 'badge' => 'from-[#0284C7] to-[#0369A1]', 'btn' => 'bg-[#0284C7] hover:bg-[#0369A1]'],
            6 => ['border' => 'border-[#E11D48]', 'badge' => 'from-[#E11D48] to-[#BE123C]', 'btn' => 'bg-[#E11D48] hover:bg-[#BE123C]'],
            7 => ['border' => 'border-[#0D9488]', 'badge' => 'from-[#0D9488] to-[#0F766E]', 'btn' => 'bg-[#0D9488] hover:bg-[#0F766E]'],
            8 => ['border' => 'border-[#4F46E5]', 'badge' => 'from-[#4F46E5] to-[#4338CA]', 'btn' => 'bg-[#4F46E5] hover:bg-[#4338CA]'],
        ];
    @endphp

    <div class="landscape-force">
        <div data-app-root data-content
            style="background-image: url('{{ $bg }}'); background-size: cover; background-position: center;">

            <!-- TOP BAR KIRI: HOME -->
            <div class="absolute top-3 left-3 sm:top-5 sm:left-5 z-40">
                <x-btn-home href="{{ route('menu') }}" title="Kembali ke Beranda" />
            </div>

            <!-- TOP BAR KANAN: REFRESH, SETTINGS, EXIT -->
            <div class="absolute top-3 right-3 sm:top-5 sm:right-5 z-40 flex items-center gap-2 sm:gap-3">
                <x-btn-refresh onclick="window.location.reload()" title="Muat Ulang Status Hasil" />
                <x-btn-settings onclick="openSettingsModal()" title="Pengaturan Suara" />
                <x-btn-exit onclick="handleExitWeb()" title="Keluar" />
            </div>

            <!-- KARAKTER YUK DAMAR -->
            <div
                class="absolute z-20 pointer-events-none bottom-0 left-0 sm:left-2 md:left-4 lg:left-6 h-[88%] sm:h-[94%] md:h-[98%] max-h-[760px] flex items-end translate-y-[20%] select-none">
                <img src="{{ asset('assets/karakter_menyapa.webp') }}" alt="Yuk Damar Menyapa"
                    class="h-full w-auto max-h-full object-contain object-bottom drop-shadow-[0_18px_32px_rgba(0,0,0,0.4)] select-none pointer-events-none opacity-100">
            </div>

            <!-- KARTU UTAMA CEK HASIL -->
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

                    <!-- Header Halaman -->
                    <div class="flex items-center justify-between flex-wrap gap-2 pb-2 border-b border-[#E9C37A]/50">
                        <div>
                            <div class="flex items-center gap-2 flex-wrap mb-1">
                                <div
                                    class="inline-flex items-center gap-2 bg-[#F5F3FF] px-3.5 py-1 rounded-full border border-[#8B5CF6]/40 shadow-2xs">
                                    <svg class="w-4 h-4 text-[#7C3AED]" viewBox="0 0 24 24" fill="currentColor">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H6a2 2 0 00-2 2v13a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1.382l-.724-1.447A1 1 0 0015 2H9zm6 4H9v1h6V6zm-4.293 9.707l5-5-1.414-1.414L10 13.586l-1.793-1.793-1.414 1.414 2.5 2.5a1 1 0 001.414 0z"/>
                                    </svg>
                                    <span class="text-xs sm:text-sm font-bold text-[#6D28D9]">Cek Hasil Kolaborasi Kelompok</span>
                                </div>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-300 text-emerald-800 text-[11px] sm:text-xs font-bold shadow-2xs">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span>Sesi: {{ $activeSession->displayLabel() }}</span>
                                </span>
                            </div>
                            <p class="text-xs sm:text-sm text-[#3D1804] leading-relaxed">
                                Lihat status pengerjaan Ruang 1–8 dan buka hasil karya kelompok yang sudah dikumpulkan.
                            </p>
                        </div>

                        <a href="{{ route('kolaborasi') }}"
                           class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-[#FFF2D6] hover:bg-[#FFE8B8] border border-[#E9C37A] text-xs font-bold text-[#8A4416] transition-all shadow-2xs">
                            <span>Ke Ruang Kolaborasi</span>
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>

                    <!-- Bagian 1: Status 8 Ruang Kolaborasi -->
                    <div>
                        <h2 class="text-xs sm:text-sm font-bold text-[#8A4416] mb-2 flex items-center gap-1.5">
                            <span>Status Ruang Kolaborasi (Ruang 1 – 8)</span>
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-3">
                            @foreach ($roomStatuses as $roomStatus)
                                @php
                                    $num = $roomStatus['room_number'];
                                    $style = $roomStyles[$num] ?? $roomStyles[1];
                                    $statusKey = $roomStatus['status_key'];
                                    $latestSub = $roomStatus['latest_submission'];
                                @endphp
                                <div class="bg-white rounded-2xl border-2 {{ $style['border'] }} p-3 flex flex-col justify-between shadow-xs gap-2.5">
                                    <div>
                                        <div class="flex items-center justify-between gap-2 mb-2">
                                            <div class="flex items-center gap-2 min-w-0">
                                                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-gradient-to-br {{ $style['badge'] }} text-white font-black text-xs sm:text-sm flex items-center justify-center shrink-0">
                                                    {{ $num }}
                                                </div>
                                                <div class="min-w-0">
                                                    <h3 class="font-bold text-xs sm:text-sm text-[#3D1804] truncate">
                                                        {{ $roomStatus['name'] }}
                                                    </h3>
                                                </div>
                                            </div>

                                            @if ($statusKey === 'completed')
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300 shrink-0">
                                                    Sudah Selesai
                                                </span>
                                            @elseif ($statusKey === 'in_progress')
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-300 shrink-0">
                                                    Sedang Dikerjakan
                                                </span>
                                            @else
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-stone-100 text-stone-600 border border-stone-300 shrink-0">
                                                    Belum Selesai
                                                </span>
                                            @endif
                                        </div>

                                        @if ($statusKey === 'completed' && $latestSub)
                                            <div class="bg-[#FFFDF5] rounded-xl border border-[#E9C37A]/50 p-2 space-y-1 text-[11px] text-[#5C2B09]">
                                                <div class="font-bold text-xs text-[#3D1804] truncate" title="{{ $latestSub->group_name }}">
                                                    {{ $latestSub->group_name }}
                                                </div>
                                                <div>Ketua: <strong class="text-[#8A4416]">{{ $latestSub->leader_name ?: '-' }}</strong></div>
                                                <div>Anggota: <strong>{{ is_array($latestSub->tasks_data) ? count($latestSub->tasks_data) : 0 }}/{{ $roomStatus['max_members'] }} Siswa</strong></div>
                                                <div class="text-[10px] text-[#8A4416]/80">
                                                    Dikumpulkan: {{ $latestSub->submitted_at ? $latestSub->submitted_at->format('d M Y, H:i') : '-' }}
                                                </div>
                                            </div>
                                        @elseif ($statusKey === 'in_progress')
                                            <div class="bg-amber-50/70 rounded-xl border border-amber-200 p-2 space-y-1 text-[11px] text-amber-900">
                                                @if (!empty($roomStatus['active_group_name']))
                                                    <div class="font-bold truncate">{{ $roomStatus['active_group_name'] }}</div>
                                                @endif
                                                <div class="font-bold">
                                                    Aktif: {{ $roomStatus['member_count'] }}/{{ $roomStatus['max_members'] }} Siswa
                                                </div>
                                                <p class="leading-snug text-[10px] sm:text-[11px] text-amber-800">
                                                    {{ $roomStatus['status_description'] }}
                                                </p>
                                            </div>
                                        @else
                                            <div class="bg-stone-50 rounded-xl border border-stone-200 p-2.5 text-[11px] text-stone-600 leading-snug">
                                                {{ $roomStatus['status_description'] }}
                                            </div>
                                        @endif
                                    </div>

                                    @if ($latestSub)
                                        <a href="{{ route('hasil.show', $latestSub->id) }}"
                                           class="w-full py-1.5 px-3 rounded-xl {{ $style['btn'] }} text-white font-bold text-xs flex items-center justify-center gap-1.5 shadow-xs hover:brightness-105 transition-all">
                                            <span>{{ $statusKey === 'in_progress' ? 'Lihat Hasil Sebelumnya' : 'Lihat Hasil' }}</span>
                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                            </svg>
                                        </a>
                                    @else
                                        <div class="w-full py-1.5 px-3 rounded-xl bg-stone-100 text-stone-400 font-bold text-[11px] text-center border border-stone-200">
                                            Belum Ada Hasil
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Bagian 2: Daftar Kelompok yang Sudah Mengumpulkan -->
                    <div class="pt-1">
                        <div class="flex items-center justify-between gap-2 mb-2 flex-wrap">
                            <h2 class="text-xs sm:text-sm font-bold text-[#8A4416]">
                                Daftar Kelompok yang Sudah Menyelesaikan Misi
                            </h2>
                            <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-[#FFF2D6] text-[#8A4416] border border-[#E9C37A]">
                                Total {{ $submissions->total() }} Kelompok Selesai
                            </span>
                        </div>

                        @if ($submissions->isEmpty())
                            <div class="rounded-2xl border-2 border-dashed border-[#E9C37A] bg-[#FFF8E7]/60 p-5 text-center space-y-1.5">
                                <p class="text-xs sm:text-sm font-bold text-[#8A4416]">Belum ada kelompok yang mengumpulkan hasil kolaborasi</p>
                                <p class="text-[11px] sm:text-xs text-[#6D4C33]">
                                    Setelah kelompok menyelesaikan Misi 1, Misi 2, dan Misi 3 lalu menekan tombol kumpulkan tugas, hasil karya akan tampil di halaman ini.
                                </p>
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2.5 sm:gap-3">
                                @foreach ($submissions as $sub)
                                    @php
                                        $members = is_array($sub->tasks_data) ? $sub->tasks_data : [];
                                        $identCount = is_array($sub->identification_data) ? count($sub->identification_data) : 0;
                                        $evalCount = is_array($sub->evaluation_data) ? count($sub->evaluation_data) : 0;
                                    @endphp
                                    <div class="bg-white rounded-2xl border-2 border-[#E9C37A]/80 hover:border-[#8B5CF6] p-3 shadow-xs transition-all flex flex-col justify-between gap-2.5">
                                        <div>
                                            <div class="flex items-center justify-between gap-2 mb-1.5">
                                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#FFF2D6] text-[#8A4416] border border-[#E9C37A]">
                                                    Ruang {{ $sub->room_number }}
                                                </span>
                                                <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full">
                                                    Sudah Selesai
                                                </span>
                                            </div>
                                            <h3 class="text-sm sm:text-base font-bold text-[#3D1804] truncate" title="{{ $sub->group_name }}">
                                                {{ $sub->group_name }}
                                            </h3>
                                            <p class="text-[11px] text-[#6D4C33] mb-2">
                                                Ketua: <strong class="text-[#8A4416]">{{ $sub->leader_name ?: '-' }}</strong> &bull;
                                                {{ $sub->submitted_at ? $sub->submitted_at->format('d M Y, H:i') : ($sub->created_at ? $sub->created_at->format('d M Y, H:i') : '-') }}
                                            </p>
                                            <div class="grid grid-cols-3 gap-1.5 bg-[#FFFDF5] rounded-xl border border-[#E9C37A]/40 p-2 text-center text-[10px]">
                                                <div>
                                                    <span class="text-[#8A4416]/80 block">Anggota</span>
                                                    <strong class="text-[#3D1804]">{{ count($members) }} Siswa</strong>
                                                </div>
                                                <div class="border-x border-[#E9C37A]/40">
                                                    <span class="text-[#8A4416]/80 block">Bangun Datar</span>
                                                    <strong class="text-[#7C3AED]">{{ $identCount }} Item</strong>
                                                </div>
                                                <div>
                                                    <span class="text-[#8A4416]/80 block">Apresiasi</span>
                                                    <strong class="text-[#15803D]">{{ $evalCount }} Catatan</strong>
                                                </div>
                                            </div>
                                        </div>

                                        <a href="{{ route('hasil.show', $sub->id) }}"
                                           class="w-full py-1.5 px-3 rounded-xl bg-[#8B5CF6] hover:bg-[#7C3AED] text-white font-bold text-xs flex items-center justify-center gap-1.5 shadow-xs transition-all">
                                            <span>Lihat Hasil Lengkap</span>
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                            </svg>
                                        </a>
                                    </div>
                                @endforeach
                            </div>

                            @if ($submissions->hasPages())
                                <div class="mt-3 pt-2 border-t border-[#E9C37A]/50">
                                    {{ $submissions->links('vendor.pagination.tara-user') }}
                                </div>
                            @endif
                        @endif
                    </div>

                </div>
            </div>

            <!-- TOMBOL KEMBALI BAWAH -->
            <div class="absolute bottom-3 left-3 sm:bottom-5 sm:left-5 z-40">
                <x-btn-back href="{{ route('menu') }}" title="Kembali ke Beranda" />
            </div>

            <x-sound-settings :renderTrigger="false" />
            <x-exit-modal />
        </div>
    </div>

    <script>
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                if (typeof closeExitModal === 'function') closeExitModal();
                if (typeof closeSettingsModal === 'function') closeSettingsModal();
            }
        });
    </script>
</body>

</html>
