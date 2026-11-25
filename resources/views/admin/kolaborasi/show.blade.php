@extends('admin.layout', ['title' => 'Detail Tugas Kelompok ' . $submission->group_name])

@section('page_title', 'Detail Tugas: ' . $submission->group_name)
@section('page_description', 'Laporan lengkap pembagian peran tugas, karya pewarnaan motif, identifikasi bangun datar, dan evaluasi tim.')

@section('content')
@php
    $members = is_array($submission->tasks_data) ? $submission->tasks_data : [];
    $evaluations = is_array($submission->evaluation_data) ? $submission->evaluation_data : [];

    $adminCanvas1 = $canvases[1] ?? '';
    $adminCanvas2 = $canvases[2] ?? '';
    $adminCanvas3 = $canvases[3] ?? '';
    $adminCanvas4 = $canvases[4] ?? '';
@endphp

<style>
    .admin-svg-viewport svg {
        width: 100%;
        max-width: 250px;
        height: auto;
        max-height: 320px;
        display: block;
        margin: 0 auto;
        filter: drop-shadow(0 6px 12px rgba(0,0,0,0.1));
    }
</style>

<div class="space-y-6 font-['Jua']">
    <!-- Header Navigasi -->
    <div class="flex items-center justify-between flex-wrap gap-3">
        <a href="{{ route('admin.kolaborasi.index') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900 bg-white border border-slate-200 px-4 py-2.5 rounded-xl shadow-xs transition-all flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Daftar Kelompok</span>
        </a>
        <div class="flex items-center gap-2">
            <span class="text-xs px-3.5 py-1.5 rounded-xl font-bold bg-emerald-100 text-emerald-800 border border-emerald-300 flex items-center gap-1.5 shadow-2xs">
                <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
                <span>Dikumpulkan: {{ $submission->submitted_at ? $submission->submitted_at->format('d M Y, H:i') : ($submission->created_at ? $submission->created_at->format('d M Y, H:i') : '-') }}</span>
            </span>
            <form method="POST" action="{{ route('admin.kolaborasi.destroy', $submission->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pengumpulan ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-xs font-bold text-rose-600 hover:text-rose-700 bg-rose-50 border border-rose-200 px-4 py-2 rounded-xl transition-all flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span>Hapus Data</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Info Kelompok -->
    <div class="bg-white rounded-3xl border-2 border-emerald-300 p-6 shadow-xs">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <span class="text-xs text-slate-400 font-bold block">Sesi Kolaborasi:</span>
                <span class="text-sm font-bold text-purple-700">{{ $submission->session ? $submission->session->displayLabel() : '-' }}</span>
            </div>
            <div>
                <span class="text-xs text-slate-400 font-bold block">Nama Kelompok:</span>
                <span class="text-lg font-black text-slate-800">{{ $submission->group_name }}</span>
            </div>
            <div>
                <span class="text-xs text-slate-400 font-bold block">Ketua Kelompok:</span>
                <span class="text-base font-bold text-amber-700">{{ $submission->leader_name ?: '-' }}</span>
            </div>
            <div>
                <span class="text-xs text-slate-400 font-bold block">Ruang:</span>
                <span class="text-base font-bold text-slate-800">Ruang {{ $submission->room_number }}</span>
            </div>
            <div>
                <span class="text-xs text-slate-400 font-bold block">Jumlah Anggota:</span>
                <span class="text-base font-bold text-emerald-600">{{ count($members) }} Siswa</span>
            </div>
        </div>
    </div>

    <!-- Grid 2 Kolom: Pewarnaan & Pembagian Tugas (Tabel 1) -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
        <!-- Hasil Pewarnaan Sketsa (4 Kanvas) -->
        <div class="md:col-span-5 bg-white rounded-3xl border-2 border-slate-200 p-5 sm:p-6 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3 flex-wrap gap-2">
                    <h3 class="font-bold text-base text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                        </svg>
                        <span>Karya Pewarnaan 4 Sisi Motif</span>
                    </h3>
                </div>

                <!-- Tab Pilihan Sisi di Admin -->
                <div class="flex items-center gap-1.5 mb-3 overflow-x-auto pb-1">
                    <button type="button" onclick="selectAdminCanvas(1)" id="btnAdminTabCanvas1" class="admin-tab-btn px-2.5 py-1 rounded-full text-xs font-bold transition-all bg-amber-500 text-white shadow-2xs">
                        Sisi 1
                    </button>
                    <button type="button" onclick="selectAdminCanvas(2)" id="btnAdminTabCanvas2" class="admin-tab-btn px-2.5 py-1 rounded-full text-xs font-bold transition-all bg-slate-100 text-slate-700 hover:bg-slate-200">
                        Sisi 2
                    </button>
                    <button type="button" onclick="selectAdminCanvas(3)" id="btnAdminTabCanvas3" class="admin-tab-btn px-2.5 py-1 rounded-full text-xs font-bold transition-all bg-slate-100 text-slate-700 hover:bg-slate-200">
                        Sisi 3
                    </button>
                    <button type="button" onclick="selectAdminCanvas(4)" id="btnAdminTabCanvas4" class="admin-tab-btn px-2.5 py-1 rounded-full text-xs font-bold transition-all bg-slate-100 text-slate-700 hover:bg-slate-200">
                        Sisi 4
                    </button>
                </div>

                <div class="bg-[#FFF9EA] p-3 sm:p-4 rounded-2xl border border-[#E9C37A]/60 flex justify-center min-h-[260px] max-h-[380px] overflow-hidden">
                    <div id="adminCanvasViewport-1" class="admin-svg-viewport w-full flex justify-center">
                        {!! $adminCanvas1 !!}
                    </div>
                    <div id="adminCanvasViewport-2" class="admin-svg-viewport w-full flex justify-center hidden">
                        {!! $adminCanvas2 !!}
                    </div>
                    <div id="adminCanvasViewport-3" class="admin-svg-viewport w-full flex justify-center hidden">
                        {!! $adminCanvas3 !!}
                    </div>
                    <div id="adminCanvasViewport-4" class="admin-svg-viewport w-full flex justify-center hidden">
                        {!! $adminCanvas4 !!}
                    </div>
                </div>
            </div>
            <p class="text-xs text-slate-400 text-center mt-2">Dikerjakan bersama-sama oleh seluruh anggota tim</p>
        </div>

        <!-- Tabel 1: Identitas Tim Kolaborasi -->
        <div class="md:col-span-7 bg-white rounded-3xl border-2 border-slate-200 p-5 sm:p-6 shadow-xs flex flex-col justify-between">
            <div>
                <h3 class="font-bold text-base text-slate-800 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span>Tabel 1: Identitas Tim Kolaborasi</span>
                </h3>

                <div class="overflow-x-auto rounded-2xl border border-slate-200">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="bg-slate-50 text-slate-700">
                                <th class="p-3 font-bold border-b border-slate-200 w-12 text-center">No</th>
                                <th class="p-3 font-bold border-b border-slate-200">Nama Siswa</th>
                                <th class="p-3 font-bold border-b border-slate-200 text-center">No. Absen</th>
                                <th class="p-3 font-bold border-b border-slate-200 text-center">Status / Peran</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($members as $idx => $m)
                                <tr>
                                    <td class="p-3 text-center font-bold text-slate-500">{{ $idx + 1 }}</td>
                                    <td class="p-3 font-bold text-slate-800">{{ $m['user_name'] ?? '-' }}</td>
                                    <td class="p-3 text-center font-bold text-amber-700">{{ $m['no_absen'] ?? '-' }}</td>
                                    <td class="p-3 text-center">
                                        @php
                                            $role = $m['role'] ?? 'Kolaborator';
                                        @endphp
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold {{ $role === 'identifikasi' ? 'bg-purple-100 text-purple-800' : ($role === 'mewarnai' ? 'bg-orange-100 text-orange-800' : 'bg-emerald-100 text-emerald-800') }}">
                                            {{ $role === 'identifikasi' ? 'Identifikasi Bangun Datar' : ($role === 'mewarnai' ? 'Mewarnai Motif' : 'Kolaborator') }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-4 text-center text-slate-400 italic">Belum ada data anggota kelompok</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel 2: Identifikasi Bangun Datar (Tampilan Visual Bentuk seperti Misi 2) -->
    <div class="bg-white rounded-3xl border-2 border-slate-200 p-5 sm:p-6 shadow-xs">
        <h3 class="font-bold text-base text-slate-800 mb-3 flex items-center gap-2">
            <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            <span>Tabel 2: Hasil Identifikasi Bangun Datar & Ciri-cirinya</span>
        </h3>

        @if (!empty($identificationItems))
            <div class="overflow-x-auto rounded-2xl border border-slate-200">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-700">
                            <th class="p-3 font-bold border-b border-slate-200 w-12 text-center">No</th>
                            <th class="p-3 font-bold border-b border-slate-200 w-28 text-center">Bentuk</th>
                            <th class="p-3 font-bold border-b border-slate-200 w-1/4">Bangun Datar Teridentifikasi</th>
                            <th class="p-3 font-bold border-b border-slate-200">Ciri-Ciri Bangun Datar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($identificationItems as $idx => $idItem)
                            <tr>
                                <td class="p-3 text-center font-bold text-slate-500 align-middle">{{ $idx + 1 }}</td>
                                <td class="p-3 align-middle text-center">
                                    @if (!empty($idItem['benda_svg']))
                                        <div class="w-14 h-12 rounded-xl bg-[#FFFDF5] border-2 border-[#E9C37A] flex items-center justify-center p-1 shadow-2xs mx-auto"
                                             title="{{ $idItem['benda_label'] }}">
                                            {!! $idItem['benda_svg'] !!}
                                        </div>
                                    @else
                                        <span class="font-bold text-slate-800">{{ $idItem['benda_label'] ?: '-' }}</span>
                                    @endif
                                </td>
                                <td class="p-3 font-bold text-purple-700 align-middle">
                                    <div class="inline-flex items-center gap-2">
                                        @if (!empty($idItem['shape_custom_image']))
                                            <img src="{{ $idItem['shape_custom_image'] }}" alt="{{ $idItem['shape_label'] }}" class="w-6 h-6 object-contain shrink-0">
                                        @elseif (!empty($idItem['shape_svg']))
                                            <span class="w-6 h-6 inline-flex items-center justify-center shrink-0" style="color: {{ $idItem['shape_color'] }}">
                                                {!! $idItem['shape_svg'] !!}
                                            </span>
                                        @endif
                                        <span>{{ $idItem['shape_label'] }}</span>
                                    </div>
                                </td>
                                <td class="p-3 text-slate-700 align-middle">{{ $idItem['ciri'] !== '' ? $idItem['ciri'] : '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-xs text-slate-400 italic">Belum ada hasil identifikasi bangun datar yang dikirimkan.</p>
        @endif
    </div>

    <!-- Tabel 3: Evaluasi & Apresiasi Antarteman -->
    <div class="bg-white rounded-3xl border-2 border-slate-200 p-5 sm:p-6 shadow-xs">
        <h3 class="font-bold text-base text-slate-800 mb-3 flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
            </svg>
            <span>Tabel 3: Catatan Evaluasi & Apresiasi Antarteman</span>
        </h3>

        @if (!empty($evaluations))
            <div class="overflow-x-auto rounded-2xl border border-slate-200">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-700">
                            <th class="p-3 font-bold border-b border-slate-200 w-12 text-center">No</th>
                            <th class="p-3 font-bold border-b border-slate-200">Penilai</th>
                            <th class="p-3 font-bold border-b border-slate-200">Teman yang Dinilai</th>
                            <th class="p-3 font-bold border-b border-slate-200 text-center">No. Absen</th>
                            <th class="p-3 font-bold border-b border-slate-200">Penilaian</th>
                            <th class="p-3 font-bold border-b border-slate-200">Pesan Apresiasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($evaluations as $idx => $ev)
                            <tr>
                                <td class="p-3 text-center font-bold text-slate-500">{{ $idx + 1 }}</td>
                                <td class="p-3 font-bold text-slate-700">{{ $ev['evaluator_name'] ?? '-' }}</td>
                                <td class="p-3 font-bold text-emerald-800">{{ $ev['peer_name'] ?? '-' }}</td>
                                <td class="p-3 text-center font-bold text-amber-700">{{ $ev['peer_no_absen'] ?? '-' }}</td>
                                <td class="p-3 font-bold text-slate-800">{{ $ev['rating'] ?? '-' }}</td>
                                <td class="p-3 text-slate-600 italic">"{{ $ev['note'] ?? '-' }}"</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-xs text-slate-400 italic">Belum ada evaluasi & apresiasi yang dikirimkan oleh kelompok ini.</p>
        @endif
    </div>
</div>

<script>
    const coloringData = @json($sanitizedColors);

    function applyAdminColors() {
        if (!coloringData || typeof coloringData !== 'object') return;
        Object.keys(coloringData).forEach(id => {
            const rawId = String(id).replace(/^(review-|admin-)/, '');
            const el = document.getElementById('admin-' + rawId);
            if (el) {
                el.setAttribute('fill', coloringData[id]);
            }
        });
    }

    function selectAdminCanvas(num) {
        for (let i = 1; i <= 4; i++) {
            const btn = document.getElementById('btnAdminTabCanvas' + i);
            const vp = document.getElementById('adminCanvasViewport-' + i);
            if (btn) {
                if (i === num) {
                    btn.className = 'admin-tab-btn px-2.5 py-1 rounded-full text-xs font-bold transition-all bg-amber-500 text-white shadow-2xs';
                } else {
                    btn.className = 'admin-tab-btn px-2.5 py-1 rounded-full text-xs font-bold transition-all bg-slate-100 text-slate-700 hover:bg-slate-200';
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

    document.addEventListener('DOMContentLoaded', () => {
        applyAdminColors();
        const defaultRoom = {{ $submission->room_number ?? 1 }};
        if (defaultRoom >= 1 && defaultRoom <= 4) {
            selectAdminCanvas(defaultRoom);
        }
    });
</script>
@endsection

