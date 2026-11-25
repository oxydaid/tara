@extends('admin.layout', ['title' => 'Master Bangun Datar'])

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="font-['Jua'] text-xl text-slate-900">Master Data Bangun Datar</h2>
            <p class="text-xs text-slate-500 font-medium mt-0.5">
                Atur warna, gambar custom, ciri-ciri, bagian Damar Kurung, dan urutan tampil pada Ruang Eksplorasi serta Kolaborasi.
            </p>
        </div>
        <a href="{{ route('admin.bangun-datar.create') }}"
           class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-[#0077B6] hover:bg-[#005f92] text-white text-xs font-bold transition-all shadow-sm shadow-[#0077B6]/20 shrink-0">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Tambah Bangun Datar</span>
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                        <th class="py-4 px-5 w-20">Urutan</th>
                        <th class="py-4 px-5 w-28 text-center">Bentuk</th>
                        <th class="py-4 px-5">Nama & Jenis</th>
                        <th class="py-4 px-5">Bagian Damar Kurung</th>
                        <th class="py-4 px-5">Ciri-Ciri</th>
                        <th class="py-4 px-5 w-28">Status</th>
                        <th class="py-4 px-5 text-right w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse ($shapes as $shape)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-4 px-5 font-bold text-slate-500">
                                #{{ $shape->urutan }}
                            </td>
                            <td class="py-4 px-5 text-center">
                                <div class="w-16 h-14 mx-auto flex items-center justify-center" style="color: {{ $shape->color }};">
                                    @if ($shape->custom_image_path)
                                        <img src="{{ asset($shape->custom_image_path) }}" alt="{{ $shape->name }}" class="max-w-full max-h-full object-contain">
                                    @else
                                        {!! $shape->svg_inline !!}
                                    @endif
                                </div>
                                <div class="mt-1 flex items-center justify-center gap-1">
                                    <span class="w-3 h-3 rounded-full border border-slate-300 inline-block" style="background-color: {{ $shape->color }};"></span>
                                    <span class="text-[10px] text-slate-400 font-mono">{{ $shape->color }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-5">
                                <div class="font-bold text-slate-900">{{ $shape->name }}</div>
                                <div class="text-[11px] text-slate-500 mt-0.5">
                                    {{ $shape->type }}@if($shape->subtype) / {{ $shape->subtype }}@endif
                                </div>
                            </td>
                            <td class="py-4 px-5 text-xs text-slate-700">
                                {{ $shape->bagian_damar_kurung ?: '-' }}
                            </td>
                            <td class="py-4 px-5">
                                <ol class="list-decimal pl-4 text-xs text-slate-600 space-y-0.5 max-w-md">
                                    @foreach ($shape->ciri_ciri ?? [] as $ciri)
                                        <li>{{ $ciri }}</li>
                                    @endforeach
                                </ol>
                            </td>
                            <td class="py-4 px-5">
                                @if ($shape->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-5 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.bangun-datar.edit', $shape->id) }}"
                                       class="p-2 rounded-xl bg-slate-100 hover:bg-[#0077B6] hover:text-white text-slate-600 transition-all text-xs font-bold inline-flex items-center"
                                       title="Edit {{ $shape->name }}">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                    </a>

                                    <form method="POST" action="{{ route('admin.bangun-datar.destroy', $shape->id) }}" onsubmit="return confirm('Hapus bangun datar {{ $shape->name }}?')">
                                        @csrf
                                        <button type="submit"
                                                class="p-2 rounded-xl bg-red-50 hover:bg-red-500 hover:text-white text-red-600 transition-all text-xs font-bold inline-flex items-center border border-red-100"
                                                title="Hapus {{ $shape->name }}">
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
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <p class="text-sm font-bold text-slate-500">Belum ada data bangun datar.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($shapes->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $shapes->links('vendor.pagination.tara-admin') }}
            </div>
        @endif
    </div>

</div>
@endsection
