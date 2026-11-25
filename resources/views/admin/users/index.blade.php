@extends('admin.layout', ['title' => 'Data Siswa & Absen'])

@section('content')
<div class="space-y-6">

    <!-- Search & Summary Bar -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="font-['Jua'] text-lg text-slate-900">Daftar Siswa Pengguna Media TARA</h2>
            <p class="text-xs text-slate-500 font-medium mt-0.5">
                Kelola data nama, nomor absen, serta keaktifan siswa di ruang kolaborasi Damar Kurung.
            </p>
        </div>

        <form method="GET" action="{{ route('admin.users.index') }}" class="flex items-center gap-3">
            <div class="relative">
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama / no. absen..."
                       class="w-64 h-11 pl-10 pr-4 rounded-xl bg-slate-50 border border-slate-200 text-xs font-medium text-slate-800 placeholder-slate-400 outline-none focus:bg-white focus:border-[#0077B6] focus:ring-2 focus:ring-[#0077B6]/20 transition-all">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
            </div>
            <button type="submit" class="h-11 px-4 rounded-xl bg-[#0077B6] hover:bg-[#005f92] text-white text-xs font-bold transition-all shadow-sm shadow-[#0077B6]/20 cursor-pointer">
                Cari
            </button>
            @if ($search)
                <a href="{{ route('admin.users.index') }}" class="h-11 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold transition-all flex items-center justify-center">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Table of Students -->
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                        <th class="py-4 px-6">Nama Siswa</th>
                        <th class="py-4 px-6 text-center">No. Absen</th>
                        <th class="py-4 px-6">Status Ruang Kolaborasi</th>
                        <th class="py-4 px-6">Tanggal Masuk</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse ($users as $user)
                        @php
                            $membership = $activeMemberships->get($user->id);
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-900 text-base">{{ $user->name }}</div>
                                <div class="text-[11px] font-medium text-slate-400">ID #{{ $user->id }}</div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if ($user->no_absen)
                                    <span class="inline-flex items-center justify-center px-3 py-1 rounded-xl bg-sky-50 border border-sky-200 text-xs font-black text-sky-800">
                                        {{ $user->no_absen }}
                                    </span>
                                @else
                                    <span class="text-xs text-slate-400 font-medium italic">-</span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                @if ($membership && $membership->room)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                        <span>Ruang {{ $membership->room->room_number }}{{ $membership->room->session ? ' • '.$membership->room->session->name : '' }}</span>
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-500">
                                        Tidak di ruang aktif
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-xs text-slate-400 font-medium whitespace-nowrap">
                                {{ $user->created_at ? $user->created_at->translatedFormat('d F Y, H:i') : '-' }}
                            </td>
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    @if ($membership)
                                        <form method="POST" action="{{ route('admin.users.reset-progress', $user->id) }}" onsubmit="return confirm('Keluarkan siswa {{ $user->name }} dari ruang kolaborasi aktif?')">
                                            @csrf
                                            <button type="submit"
                                                    class="px-3 py-1.5 rounded-xl bg-amber-50 hover:bg-amber-500 hover:text-white text-amber-700 transition-all text-xs font-bold inline-flex items-center gap-1.5 border border-amber-200 hover:border-amber-500 cursor-pointer">
                                                <span>Keluarkan dari Ruang</span>
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" onsubmit="return confirm('Hapus data siswa {{ $user->name }}?')">
                                        @csrf
                                        <button type="submit"
                                                class="p-2 rounded-xl bg-red-50 hover:bg-red-500 hover:text-white text-red-600 transition-all text-xs font-bold inline-flex items-center border border-red-200 hover:border-red-500 cursor-pointer"
                                                title="Hapus Siswa">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400">
                                <p class="text-sm font-bold text-slate-500">Tidak ada data siswa ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $users->links('vendor.pagination.tara-admin') }}
            </div>
        @endif
    </div>

</div>
@endsection
