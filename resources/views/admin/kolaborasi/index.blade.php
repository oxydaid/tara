@extends('admin.layout', ['title' => 'Hasil Pengumpulan Kolaborasi'])

@section('page_title', 'Hasil Kolaborasi Kelompok Siswa')
@section('page_description', 'Daftar data hasil pekerjaan per kelompok yang telah dikumpulkan siswa (Pewarnaan Motif, Identifikasi Bangun Datar, & Evaluasi Teman).')

@section('content')
<div class="space-y-6 font-['Jua']">
    
    <!-- Top Filter & Search Bar -->
    <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/90 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                <span class="w-8 h-8 rounded-xl bg-amber-500 text-white flex items-center justify-center text-sm shadow-xs">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </span>
                <span>Tugas Kelompok Terkumpul</span>
            </h2>
            <p class="text-xs text-slate-500 font-normal mt-0.5">
                Total <span class="font-bold text-amber-600">{{ $submissions->total() }}</span> kelompok telah mengumpulkan tugas
            </p>
        </div>

        <form method="GET" action="{{ route('admin.kolaborasi.index') }}" class="flex items-center gap-2.5">
            <div class="relative flex-1 sm:w-72">
                <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Cari nama kelompok / tema..."
                       class="w-full h-10 pl-9 pr-4 rounded-xl bg-slate-50 border border-slate-200 text-xs font-medium text-slate-800 placeholder-slate-400 outline-none focus:bg-white focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
            </div>
            <button type="submit" class="h-10 px-4 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold transition-all shadow-xs shrink-0">
                Cari
            </button>
            @if (!empty($search))
                <a href="{{ route('admin.kolaborasi.index') }}" class="h-10 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold transition-all flex items-center justify-center shrink-0">
                    Reset
                </a>
            @endif
        </form>
    </div>

    @if ($submissions->isEmpty())
        <!-- Empty State -->
        <div class="bg-white rounded-3xl p-12 text-center border-2 border-dashed border-slate-200 shadow-xs space-y-3">
            <div class="w-16 h-16 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center mx-auto shadow-2xs">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800">Belum Ada Tugas Kelompok yang Dikumpulkan</h3>
            <p class="text-xs text-slate-500 max-w-md mx-auto leading-relaxed">
                Saat siswa menyelesaikan tahap evaluasi & apresiasi di Ruang Kolaborasi dan menekan tombol <strong class="text-emerald-700">"Kumpulkan"</strong>, data pekerjaan per kelompok akan langsung masuk dan tersimpan di sini.
            </p>
        </div>
    @else
        <!-- Grid Kartu Tugas per Kelompok -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($submissions as $sub)
                @php
                    $members = is_array($sub->tasks_data) ? $sub->tasks_data : [];
                    $evalCount = is_array($sub->evaluation_data) ? count($sub->evaluation_data) : 0;
                    $identCount = is_array($sub->identification_data) ? count($sub->identification_data) : 0;
                @endphp
                <div class="bg-white rounded-3xl border-2 border-slate-200/90 hover:border-amber-400 p-5 shadow-xs hover:shadow-md transition-all flex flex-col justify-between group">
                    <div>
                        <!-- Header Kartu -->
                        <div class="flex items-center justify-between mb-3">
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200/80 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                {{ $sub->theme ?: 'Motif Budaya' }}
                            </span>
                            <span class="text-[11px] font-semibold text-slate-400">
                                {{ $sub->submitted_at ? $sub->submitted_at->diffForHumans() : ($sub->created_at ? $sub->created_at->diffForHumans() : '-') }}
                            </span>
                        </div>

                        <!-- Nama Kelompok & Ketua -->
                        <h3 class="text-xl font-bold text-slate-900 group-hover:text-amber-600 transition-colors leading-tight mb-1">
                            {{ $sub->group_name }}
                        </h3>
                        <div class="text-xs text-slate-500 mb-3 flex items-center gap-1.5">
                            <span class="font-bold text-slate-700">Ketua Tim:</span>
                            <span class="font-extrabold text-amber-700">{{ $sub->leader_name ?: '-' }}</span>
                        </div>

                        <!-- Ringkasan Kelengkapan Tugas -->
                        <div class="grid grid-cols-3 gap-2 bg-slate-50 p-2.5 rounded-2xl border border-slate-100 mb-3 text-center text-[11px]">
                            <div class="space-y-0.5">
                                <span class="text-slate-400 block font-bold">Anggota</span>
                                <span class="font-black text-slate-800">{{ count($members) }} Siswa</span>
                            </div>
                            <div class="space-y-0.5 border-x border-slate-200">
                                <span class="text-slate-400 block font-bold">Identifikasi</span>
                                <span class="font-black text-purple-700">{{ $identCount }} Bangun</span>
                            </div>
                            <div class="space-y-0.5">
                                <span class="text-slate-400 block font-bold">Evaluasi</span>
                                <span class="font-black text-emerald-700">{{ $evalCount }} Catatan</span>
                            </div>
                        </div>

                        <!-- Daftar Anggota Kelompok -->
                        <div class="space-y-1.5 mb-4">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Anggota Kelompok:</span>
                            @if (!empty($members))
                                <div class="space-y-1 max-h-32 overflow-y-auto pr-1">
                                    @foreach ($members as $m)
                                        <div class="text-xs text-slate-700 flex items-center justify-between bg-slate-50/70 px-2.5 py-1.5 rounded-xl border border-slate-100">
                                            <span class="font-bold truncate max-w-[140px]">{{ $m['user_name'] ?? '-' }}</span>
                                            <div class="flex items-center gap-1 shrink-0">
                                                <span class="text-[10px] bg-amber-100 text-amber-800 px-1.5 py-0.5 rounded font-bold">Absen {{ $m['no_absen'] ?? '-' }}</span>
                                                <span class="text-[10px] {{ ($m['role'] ?? '') === 'identifikasi' ? 'bg-purple-100 text-purple-800' : 'bg-orange-100 text-orange-800' }} px-1.5 py-0.5 rounded font-bold">
                                                    {{ ($m['role'] ?? '') === 'identifikasi' ? 'Identifikasi' : 'Mewarnai' }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-xs text-slate-400 italic">Data anggota tidak tercatat</div>
                            @endif
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="pt-3 border-t border-slate-100 flex items-center gap-2">
                        <a href="{{ route('admin.kolaborasi.show', $sub->id) }}" 
                           class="flex-1 text-center py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs shadow-xs transition-all flex items-center justify-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <span>Lihat Hasil Lengkap</span>
                        </a>
                        <form method="POST" action="{{ route('admin.kolaborasi.destroy', $sub->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pengumpulan kelompok {{ $sub->group_name }} ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold text-xs border border-rose-200 transition-all flex items-center justify-center" title="Hapus Data Kelompok">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $submissions->links() }}
        </div>
    @endif

</div>
@endsection
