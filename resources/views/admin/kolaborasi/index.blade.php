@extends('admin.layout', ['title' => 'Hasil Pengumpulan & Sesi Kolaborasi'])

@section('content')
<div class="space-y-6 font-['Jua']">

    <!-- Panel Manajemen Sesi Kolaborasi Anti-Bentrok -->
    <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/90 shadow-sm space-y-5">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-4 border-b border-slate-100">
            <div>
                <div class="flex items-center gap-2 flex-wrap mb-1">
                    <h2 class="text-xl font-bold text-slate-900">Manajemen Sesi Kolaborasi</h2>
                    <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Aktif: {{ $activeSession->displayLabel() }}</span>
                    </span>
                </div>
                <p class="text-xs text-slate-500 font-medium">
                    Atur sesi berdasarkan kelas atau jadwal pelajaran agar data 8 ruang &amp; hasil siswa tidak bentrok antar kelas.
                </p>
            </div>
        </div>

        <!-- Form Buat & Aktifkan Sesi Baru -->
        <form method="POST" action="{{ route('admin.kolaborasi.sessions.store') }}"
              class="grid grid-cols-1 md:grid-cols-12 gap-3 bg-slate-50/80 p-4 rounded-2xl border border-slate-200/80 items-end">
            @csrf
            <div class="md:col-span-4">
                <label for="session_name" class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">
                    Nama Sesi <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="session_name" name="name" value="{{ old('name') }}" required
                       placeholder="Misal: Pertemuan 1 Damar Kurung"
                       class="w-full h-10 px-3.5 rounded-xl bg-white border border-slate-200 text-xs font-medium text-slate-800 outline-none focus:border-[#0077B6] focus:ring-2 focus:ring-[#0077B6]/20">
            </div>
            <div class="md:col-span-3">
                <label for="class_label" class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">
                    Kelas (Opsional)
                </label>
                <input type="text" id="class_label" name="class_label" value="{{ old('class_label') }}"
                       placeholder="Misal: Kelas 4A"
                       class="w-full h-10 px-3.5 rounded-xl bg-white border border-slate-200 text-xs font-medium text-slate-800 outline-none focus:border-[#0077B6] focus:ring-2 focus:ring-[#0077B6]/20">
            </div>
            <div class="md:col-span-3">
                <label for="schedule_label" class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">
                    Jadwal / Jam (Opsional)
                </label>
                <input type="text" id="schedule_label" name="schedule_label" value="{{ old('schedule_label') }}"
                       placeholder="Misal: Selasa 08:00–09:30"
                       class="w-full h-10 px-3.5 rounded-xl bg-white border border-slate-200 text-xs font-medium text-slate-800 outline-none focus:border-[#0077B6] focus:ring-2 focus:ring-[#0077B6]/20">
            </div>
            <div class="md:col-span-2">
                <button type="submit"
                        class="w-full h-10 px-4 rounded-xl bg-[#0077B6] hover:bg-[#005f92] text-white text-xs font-bold transition-all shadow-xs flex items-center justify-center gap-1.5 cursor-pointer">
                    <span>+ Buat &amp; Aktifkan</span>
                </button>
            </div>
        </form>

        <!-- Daftar Sesi -->
        <div class="space-y-2.5">
            @foreach ($sessions as $sess)
                <div x-data="{ editing: false }"
                     class="p-3.5 rounded-2xl border {{ $sess->is_active ? 'bg-emerald-50/50 border-emerald-300' : 'bg-white border-slate-200' }} transition-all">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-bold text-sm text-slate-900">{{ $sess->name }}</span>
                                @if ($sess->class_label)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-sky-100 text-sky-800">{{ $sess->class_label }}</span>
                                @endif
                                @if ($sess->schedule_label)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-purple-800">{{ $sess->schedule_label }}</span>
                                @endif
                                @if ($sess->is_active)
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-600 text-white">Sedang Aktif</span>
                                @endif
                            </div>
                            <div class="text-[11px] text-slate-500 mt-0.5">
                                {{ $sess->submissions_count }} kelompok selesai (maks 8 ruang per sesi)
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0 flex-wrap">
                            <button type="button" @click="editing = !editing"
                                    class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all cursor-pointer">
                                <span x-text="editing ? 'Batal' : 'Edit'">Edit</span>
                            </button>

                            @if (! $sess->is_active)
                                <form method="POST" action="{{ route('admin.kolaborasi.sessions.activate', $sess->id) }}">
                                    @csrf
                                    <button type="submit"
                                            class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-all cursor-pointer">
                                        Aktifkan Sesi
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('admin.kolaborasi.sessions.destroy', $sess->id) }}"
                                      onsubmit="return confirm('Hapus sesi {{ $sess->name }} beserta seluruh data ruang & hasil di dalamnya?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="px-3 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 text-xs font-bold transition-all cursor-pointer">
                                        Hapus
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <form x-show="editing" x-cloak method="POST" action="{{ route('admin.kolaborasi.sessions.update', $sess->id) }}"
                          class="mt-3 pt-3 border-t border-slate-200/80 grid grid-cols-1 sm:grid-cols-4 gap-2">
                        @csrf
                        <input type="text" name="name" value="{{ $sess->name }}" required placeholder="Nama Sesi"
                               class="h-9 px-3 rounded-xl bg-white border border-slate-200 text-xs font-medium text-slate-800 outline-none focus:border-[#0077B6]">
                        <input type="text" name="class_label" value="{{ $sess->class_label }}" placeholder="Kelas (opsional)"
                               class="h-9 px-3 rounded-xl bg-white border border-slate-200 text-xs font-medium text-slate-800 outline-none focus:border-[#0077B6]">
                        <input type="text" name="schedule_label" value="{{ $sess->schedule_label }}" placeholder="Jadwal / Jam (opsional)"
                               class="h-9 px-3 rounded-xl bg-white border border-slate-200 text-xs font-medium text-slate-800 outline-none focus:border-[#0077B6]">
                        <button type="submit"
                                class="h-9 px-3 rounded-xl bg-[#0077B6] hover:bg-[#005f92] text-white text-xs font-bold cursor-pointer">
                            Simpan Perubahan
                        </button>
                    </form>
                </div>
            @endforeach
        </div>

        @if ($sessions->hasPages())
            <div class="pt-3 border-t border-slate-100">
                {{ $sessions->links('vendor.pagination.tara-admin') }}
            </div>
        @endif
    </div>

    <!-- Top Filter & Search Bar Hasil Kelompok -->
    <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/90 shadow-sm flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Tugas Kelompok Terkumpul</h2>
            <p class="text-xs text-slate-500 font-normal mt-0.5">
                Total <span class="font-bold text-amber-600">{{ $submissions->total() }}</span> hasil kelompok ditemukan
            </p>
        </div>

        <form method="GET" action="{{ route('admin.kolaborasi.index') }}" class="flex flex-wrap items-center gap-2.5">
            <select name="session_id"
                    class="h-10 px-3 rounded-xl bg-slate-50 border border-slate-200 text-xs font-bold text-slate-700 outline-none focus:bg-white focus:border-amber-500">
                <option value="">Semua Sesi</option>
                @foreach ($allSessions as $sessOpt)
                    <option value="{{ $sessOpt->id }}" {{ (string) $sessionFilter === (string) $sessOpt->id ? 'selected' : '' }}>
                        {{ $sessOpt->displayLabel() }}{{ $sessOpt->is_active ? ' (Aktif)' : '' }}
                    </option>
                @endforeach
            </select>

            <div class="relative flex-1 sm:w-64">
                <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Cari nama kelompok / ketua..."
                       class="w-full h-10 pl-9 pr-4 rounded-xl bg-slate-50 border border-slate-200 text-xs font-medium text-slate-800 placeholder-slate-400 outline-none focus:bg-white focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
            </div>
            <button type="submit" class="h-10 px-4 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold transition-all shadow-xs shrink-0 cursor-pointer">
                Terapkan
            </button>
            @if (!empty($search) || !empty($sessionFilter))
                <a href="{{ route('admin.kolaborasi.index') }}" class="h-10 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold transition-all flex items-center justify-center shrink-0">
                    Reset
                </a>
            @endif
        </form>
    </div>

    @if ($submissions->isEmpty())
        <div class="bg-white rounded-3xl p-12 text-center border-2 border-dashed border-slate-200 shadow-xs space-y-3">
            <h3 class="text-lg font-bold text-slate-800">Belum Ada Tugas Kelompok yang Dikumpulkan</h3>
            <p class="text-xs text-slate-500 max-w-md mx-auto leading-relaxed">
                Saat siswa menyelesaikan misi di Ruang Kolaborasi dan menekan tombol <strong class="text-emerald-700">"Kumpulkan"</strong>, hasil kelompok akan tersimpan di sini sesuai sesi yang sedang aktif.
            </p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($submissions as $sub)
                @php
                    $members = is_array($sub->tasks_data) ? $sub->tasks_data : [];
                    $evalCount = is_array($sub->evaluation_data) ? count($sub->evaluation_data) : 0;
                    $identCount = is_array($sub->identification_data) ? count($sub->identification_data) : 0;
                @endphp
                <div class="bg-white rounded-3xl border-2 border-slate-200/90 hover:border-amber-400 p-5 shadow-xs hover:shadow-md transition-all flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-3 flex-wrap">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-800 border border-amber-200/80">
                                    Ruang {{ $sub->room_number }}
                                </span>
                                @if ($sub->session)
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-50 text-purple-800 border border-purple-200">
                                        {{ $sub->session->name }}
                                    </span>
                                @endif
                            </div>
                            <span class="text-[11px] font-semibold text-slate-400">
                                {{ $sub->submitted_at ? $sub->submitted_at->diffForHumans() : ($sub->created_at ? $sub->created_at->diffForHumans() : '-') }}
                            </span>
                        </div>

                        <h3 class="text-xl font-bold text-slate-900 group-hover:text-amber-600 transition-colors leading-tight mb-1">
                            {{ $sub->group_name }}
                        </h3>
                        <div class="text-xs text-slate-500 mb-3 flex items-center gap-1.5">
                            <span class="font-bold text-slate-700">Ketua Tim:</span>
                            <span class="font-extrabold text-amber-700">{{ $sub->leader_name ?: '-' }}</span>
                        </div>

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

                        <div class="space-y-1.5 mb-4">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Anggota Kelompok:</span>
                            @if (!empty($members))
                                <div class="space-y-1 max-h-28 overflow-y-auto pr-1">
                                    @foreach ($members as $m)
                                        <div class="text-xs text-slate-700 flex items-center justify-between bg-slate-50/70 px-2.5 py-1.5 rounded-xl border border-slate-100">
                                            <span class="font-bold truncate max-w-[150px]">{{ $m['user_name'] ?? '-' }}</span>
                                            <span class="text-[10px] bg-amber-100 text-amber-800 px-1.5 py-0.5 rounded font-bold shrink-0">Absen {{ $m['no_absen'] ?? '-' }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-xs text-slate-400 italic">Data anggota tidak tercatat</div>
                            @endif
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center gap-2">
                        <a href="{{ route('admin.kolaborasi.show', $sub->id) }}"
                           class="flex-1 text-center py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs shadow-xs transition-all flex items-center justify-center gap-1.5">
                            <span>Lihat Hasil Lengkap</span>
                        </a>
                        <form method="POST" action="{{ route('admin.kolaborasi.destroy', $sub->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pengumpulan kelompok {{ $sub->group_name }} ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold text-xs border border-rose-200 transition-all flex items-center justify-center cursor-pointer" title="Hapus Data Kelompok">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($submissions->hasPages())
            <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-xs">
                {{ $submissions->links('vendor.pagination.tara-admin') }}
            </div>
        @endif
    @endif

</div>
@endsection
