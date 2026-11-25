@extends('admin.layout', ['title' => 'Dashboard Overview'])

@section('content')
<div class="space-y-6">

    <!-- Welcome Hero Banner -->
    <div class="rounded-3xl bg-white p-6 sm:p-8 border border-slate-200/90 shadow-sm relative overflow-hidden flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
        <div class="max-w-2xl">
            <div class="flex items-center gap-2 flex-wrap mb-3">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-sky-50 border border-sky-100 text-[#0077B6] text-xs font-extrabold uppercase tracking-wider">
                    <span>Panel Pengelola Media TARA</span>
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Sesi Aktif: {{ $activeSession->displayLabel() }}</span>
                </span>
            </div>
            <h2 class="font-['Jua'] text-2xl sm:text-3xl text-slate-900 mb-2 leading-tight">
                Selamat Datang di Administrator TARA
            </h2>
            <p class="text-sm text-slate-600 leading-relaxed font-medium">
                Kelola sesi kolaborasi per kelas &amp; jadwal pelajaran, pantau progres 8 ruang Damar Kurung secara real-time, serta atur master data bangun datar.
            </p>
        </div>
        <div class="shrink-0 flex items-center gap-3 flex-wrap">
            <a href="{{ route('admin.kolaborasi.index') }}"
               class="px-5 py-3 rounded-2xl bg-amber-500 hover:bg-amber-600 text-white text-xs sm:text-sm font-bold shadow-md shadow-amber-500/25 transition-all flex items-center gap-2 active:scale-95">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                </svg>
                <span>Kelola Sesi &amp; Kolaborasi</span>
            </a>
        </div>
    </div>

    <!-- KPI Metrics Grid (4 Kartu Tema TARA) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">

        <!-- Total Siswa -->
        <a href="{{ route('admin.users.index') }}" class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-sm hover:border-sky-300 hover:shadow-md transition-all group block">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider group-hover:text-[#0077B6]">Total Siswa</span>
                <div class="w-10 h-10 rounded-xl bg-sky-50 text-[#0077B6] border border-sky-100 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg>
                </div>
            </div>
            <div class="font-['Jua'] text-3xl text-slate-900">{{ number_format($totalStudents) }}</div>
            <div class="text-xs font-semibold text-slate-400 mt-1">Siswa terdaftar di media</div>
        </a>

        <!-- Sesi Kolaborasi Aktif -->
        <a href="{{ route('admin.kolaborasi.index') }}" class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-sm hover:border-purple-300 hover:shadow-md transition-all group block">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider group-hover:text-purple-700">Sesi Kolaborasi</span>
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 border border-purple-100 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>
                </div>
            </div>
            <div class="font-['Jua'] text-3xl text-slate-900">{{ number_format($totalSessions) }}</div>
            <div class="text-xs font-semibold text-purple-700 mt-1 truncate">Aktif: {{ $activeSession->name }}</div>
        </a>

        <!-- Tugas Kolaborasi Kelompok -->
        <a href="{{ route('admin.kolaborasi.index') }}" class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-sm hover:border-amber-300 hover:shadow-md transition-all group block">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider group-hover:text-amber-700">Kelompok Selesai</span>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
            </div>
            <div class="font-['Jua'] text-3xl text-slate-900">{{ number_format($totalActiveSessionSubmissions) }} <span class="text-base text-slate-400 font-normal">/ 8 Ruang</span></div>
            <div class="text-xs font-semibold text-amber-600/90 mt-1">Total semua sesi: {{ number_format($totalCollaboration) }} hasil</div>
        </a>

        <!-- Master Bangun Datar -->
        <a href="{{ route('admin.bangun-datar.index') }}" class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-sm hover:border-emerald-300 hover:shadow-md transition-all group block">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider group-hover:text-emerald-700">Bangun Datar</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                    </svg>
                </div>
            </div>
            <div class="font-['Jua'] text-3xl text-slate-900">{{ number_format($totalActiveShapes) }}</div>
            <div class="text-xs font-semibold text-emerald-700 mt-1">Bentuk aktif di Eksplorasi &amp; Misi 2</div>
        </a>

    </div>

    <!-- Main Content Grid: Room Monitoring + Recent Submissions & Recent Students -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Kiri (2 Kolom): Pantauan 8 Ruang Sesi Aktif & Pengumpulan Terbaru -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Status 8 Ruang pada Sesi Aktif -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100 flex-wrap gap-2">
                    <div>
                        <h3 class="font-['Jua'] text-lg text-slate-900">Status 8 Ruang ({{ $activeSession->name }})</h3>
                        <p class="text-xs text-slate-500 font-medium">Pantauan langsung kuota 5 siswa per ruang pada sesi yang sedang aktif</p>
                    </div>
                    <a href="{{ route('admin.kolaborasi.index') }}" class="text-xs font-bold text-[#0077B6] hover:text-[#005f92]">
                        Atur Sesi &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    @foreach ($activeRooms as $room)
                        @php
                            $count = $room->members->count();
                            $isCompleted = $room->status === 'completed';
                        @endphp
                        <div class="p-3 rounded-2xl border {{ $isCompleted ? 'bg-emerald-50/70 border-emerald-200' : ($count > 0 ? 'bg-amber-50/70 border-amber-200' : 'bg-slate-50/70 border-slate-200') }}">
                            <div class="flex items-center justify-between gap-1 mb-1">
                                <span class="text-xs font-bold text-slate-800">Ruang {{ $room->room_number }}</span>
                                @if ($isCompleted)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Selesai</span>
                                @elseif ($count > 0)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">Aktif</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-200 text-slate-600">Kosong</span>
                                @endif
                            </div>
                            <div class="text-[11px] font-semibold text-slate-600">
                                {{ $isCompleted ? 'Tugas terkumpul' : $count.'/'.($room->max_members ?? 5).' Siswa' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Pengumpulan Kolaborasi Terbaru -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="font-['Jua'] text-lg text-slate-900">Hasil Kolaborasi Terbaru</h3>
                        <p class="text-xs text-slate-500 font-medium">Daftar tugas kelompok yang baru dikumpulkan siswa</p>
                    </div>
                    <a href="{{ route('admin.kolaborasi.index') }}" class="text-xs font-bold text-[#0077B6] hover:text-[#005f92] flex items-center gap-1">
                        <span>Lihat Semua</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                </div>

                @if ($recentSubmissions->isEmpty())
                    <div class="text-center py-10 text-slate-400">
                        <p class="text-sm font-bold text-slate-500">Belum ada tugas kolaborasi yang dikumpulkan.</p>
                        <p class="text-xs text-slate-400 mt-1">Hasil karya kelompok yang selesai akan langsung tampil di sini.</p>
                    </div>
                @else
                    <div class="space-y-2.5">
                        @foreach ($recentSubmissions as $sub)
                            @php
                                $memberTotal = is_array($sub->tasks_data) ? count($sub->tasks_data) : 0;
                            @endphp
                            <a href="{{ route('admin.kolaborasi.show', $sub->id) }}"
                               class="p-3.5 rounded-2xl bg-slate-50/80 border border-slate-100 hover:border-amber-300 hover:bg-amber-50/30 transition-all flex items-center justify-between gap-4">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="font-bold text-sm text-slate-900 truncate">{{ $sub->group_name }}</span>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                            Ruang {{ $sub->room_number }}
                                        </span>
                                        @if ($sub->session)
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-purple-800">
                                                {{ $sub->session->name }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-slate-500 mt-0.5">
                                        Ketua: <strong>{{ $sub->leader_name ?: '-' }}</strong> &bull; {{ $memberTotal }} Anggota
                                    </p>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="text-[11px] font-bold text-[#0077B6] block">Lihat Detail &rarr;</span>
                                    <span class="text-[10px] text-slate-400">
                                        {{ $sub->submitted_at ? $sub->submitted_at->diffForHumans() : ($sub->created_at ? $sub->created_at->diffForHumans() : '-') }}
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Kanan (1 Kolom): Siswa Baru Terdaftar & Aksi Cepat -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-5 pb-4 border-b border-slate-100">
                    <div>
                        <h3 class="font-['Jua'] text-lg text-slate-900">Siswa Baru</h3>
                        <p class="text-xs text-slate-500 font-medium">Siswa yang baru masuk ke TARA</p>
                    </div>
                    <a href="{{ route('admin.users.index') }}" class="text-xs font-bold text-[#0077B6] hover:text-[#005f92] flex items-center gap-1">
                        <span>Semua</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                </div>

                @if ($recentStudents->isEmpty())
                    <div class="text-center py-10 text-slate-400">
                        <p class="text-xs font-bold text-slate-500">Belum ada siswa terdaftar.</p>
                    </div>
                @else
                    <div class="space-y-2.5">
                        @foreach ($recentStudents as $student)
                            <div class="flex items-center justify-between gap-3 p-3 rounded-2xl bg-slate-50 border border-slate-100">
                                <div class="min-w-0">
                                    <div class="font-bold text-sm text-slate-900 truncate">{{ $student->name }}</div>
                                    <div class="text-[11px] font-medium text-slate-500">
                                        No. Absen: {{ $student->no_absen ?: '-' }}
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold text-slate-400 shrink-0">
                                    {{ $student->created_at ? $student->created_at->format('d M H:i') : '-' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100 space-y-2">
                <a href="{{ route('admin.bangun-datar.create') }}"
                   class="w-full py-2.5 px-4 rounded-xl bg-[#0077B6] hover:bg-[#005f92] text-white text-xs font-bold transition-all flex items-center justify-center gap-2 shadow-sm shadow-[#0077B6]/20">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Tambah Bangun Datar</span>
                </a>
            </div>
        </div>

    </div>

</div>
@endsection
