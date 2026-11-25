@extends('admin.layout', ['title' => ($isEdit ? 'Edit Bangun Datar' : 'Tambah Bangun Datar')])

@section('content')
<div class="max-w-3xl mx-auto">

    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm">

        <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
            <div>
                <h2 class="font-['Jua'] text-xl text-slate-900">
                    {{ $isEdit ? 'Edit Bangun Datar' : 'Tambah Bangun Datar Baru' }}
                </h2>
                <p class="text-xs text-slate-500 font-medium">
                    Pengaturan ini otomatis tampil pada Ruang Eksplorasi dan dropdown Ruang Kolaborasi.
                </p>
            </div>
            <a href="{{ route('admin.bangun-datar.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 transition-colors">
                Kembali
            </a>
        </div>

        <form method="POST"
              action="{{ $isEdit ? route('admin.bangun-datar.update', $shape->id) : route('admin.bangun-datar.store') }}"
              enctype="multipart/form-data"
              class="space-y-5"
              x-data="{
                  colorHex: @js(old('color', $shape->color ?? '#E57354')),
                  ciriList: @js(old('ciri_ciri', $shape->ciri_ciri ?: ['']))
              }">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5" for="name">
                        Nama Bangun Datar <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $shape->name) }}" required
                           placeholder="Contoh: Segitiga Sama Sisi"
                           class="w-full h-11 px-4 rounded-xl bg-slate-50 border border-slate-200 text-sm font-medium text-slate-800 outline-none focus:bg-white focus:border-[#0077B6] focus:ring-2 focus:ring-[#0077B6]/20 transition-all">
                </div>

                <div>
                    <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5" for="bagian_damar_kurung">
                        Bagian pada Damar Kurung (Opsional)
                    </label>
                    <input type="text" id="bagian_damar_kurung" name="bagian_damar_kurung" value="{{ old('bagian_damar_kurung', $shape->bagian_damar_kurung) }}"
                           placeholder="Contoh: Atap Menara (boleh kosong)"
                           class="w-full h-11 px-4 rounded-xl bg-slate-50 border border-slate-200 text-sm font-medium text-slate-800 outline-none focus:bg-white focus:border-[#0077B6] focus:ring-2 focus:ring-[#0077B6]/20 transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5" for="type">
                        Kategori Utama <span class="text-red-500">*</span>
                    </label>
                    <select id="type" name="type" required
                            class="w-full h-11 px-3.5 rounded-xl bg-slate-50 border border-slate-200 text-sm font-medium text-slate-800 outline-none focus:bg-white focus:border-[#0077B6] focus:ring-2 focus:ring-[#0077B6]/20 transition-all">
                        @foreach ($shapeTypes as $typeKey => $typeLabel)
                            <option value="{{ $typeKey }}" {{ old('type', $shape->type) === $typeKey ? 'selected' : '' }}>
                                {{ $typeLabel }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5" for="subtype">
                        Sub-Jenis / Varian (Opsional)
                    </label>
                    <input type="text" id="subtype" name="subtype" value="{{ old('subtype', $shape->subtype) }}"
                           placeholder="Contoh: sama_sisi, siku_siku, tumpul"
                           class="w-full h-11 px-4 rounded-xl bg-slate-50 border border-slate-200 text-sm font-medium text-slate-800 outline-none focus:bg-white focus:border-[#0077B6] focus:ring-2 focus:ring-[#0077B6]/20 transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5" for="color">
                        Warna Bangun Datar <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center gap-2">
                        <input type="color" x-model="colorHex"
                               class="w-12 h-11 p-1 rounded-xl bg-slate-50 border border-slate-200 cursor-pointer shrink-0">
                        <input type="text" id="color" name="color" x-model="colorHex" required pattern="^#[0-9A-Fa-f]{6}$"
                               class="w-full h-11 px-3 rounded-xl bg-slate-50 border border-slate-200 text-sm font-mono font-medium text-slate-800 outline-none focus:bg-white focus:border-[#0077B6]">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5" for="urutan">
                        Urutan Tampil <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="urutan" name="urutan" value="{{ old('urutan', $shape->urutan) }}" required min="0"
                           class="w-full h-11 px-4 rounded-xl bg-slate-50 border border-slate-200 text-sm font-medium text-slate-800 outline-none focus:bg-white focus:border-[#0077B6] focus:ring-2 focus:ring-[#0077B6]/20 transition-all">
                </div>

                <div>
                    <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5" for="is_active">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select id="is_active" name="is_active"
                            class="w-full h-11 px-3.5 rounded-xl bg-slate-50 border border-slate-200 text-sm font-medium text-slate-800 outline-none focus:bg-white focus:border-[#0077B6] focus:ring-2 focus:ring-[#0077B6]/20 transition-all">
                        <option value="1" {{ old('is_active', $shape->is_active ? '1' : '0') === '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('is_active', $shape->is_active ? '1' : '0') === '0' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>

            <!-- Gambar Custom & Preview -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="space-y-1">
                        <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-800" for="custom_image">
                            Gambar Custom (Opsional)
                        </label>
                        <p class="text-[11px] text-slate-500">
                            Unggah gambar PNG, JPG, atau WEBP (maks. 2MB) jika ingin mengganti gambar default.
                        </p>
                        <input type="file" id="custom_image" name="custom_image" accept=".png,.jpg,.jpeg,.webp"
                               class="block w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#0077B6]/10 file:text-[#0077B6] hover:file:bg-[#0077B6]/20 cursor-pointer pt-1">
                    </div>

                    <div class="w-24 h-20 rounded-xl bg-white border border-slate-200 p-2 flex items-center justify-center shrink-0" :style="{ color: colorHex }">
                        @if ($shape->custom_image_path)
                            <img src="{{ asset($shape->custom_image_path) }}" alt="{{ $shape->name }}" class="max-w-full max-h-full object-contain">
                        @elseif ($shape->svg_inline)
                            {!! $shape->svg_inline !!}
                        @else
                            <span class="text-[10px] text-slate-400">Preview Default</span>
                        @endif
                    </div>
                </div>

                @if ($isEdit && $shape->custom_image_path)
                    <label class="inline-flex items-center gap-2 text-xs font-bold text-rose-600 cursor-pointer">
                        <input type="checkbox" name="remove_custom_image" value="1" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                        <span>Hapus gambar custom dan gunakan gambar SVG default</span>
                    </label>
                @endif
            </div>

            <!-- Ciri-ciri Repeater -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                <div class="flex items-center justify-between">
                    <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-800">
                        Ciri-Ciri Bangun Datar <span class="text-red-500">*</span>
                    </label>
                    <button type="button" @click="ciriList.push('')"
                            class="px-3 py-1.5 rounded-lg bg-[#0077B6] hover:bg-[#005f92] text-white text-xs font-bold transition-all cursor-pointer">
                        + Tambah Ciri
                    </button>
                </div>

                <div class="space-y-2">
                    <template x-for="(ciri, idx) in ciriList" :key="idx">
                        <div class="flex items-center gap-2">
                            <span class="w-6 text-xs font-bold text-slate-500 text-center shrink-0" x-text="(idx + 1) + '.'"></span>
                            <input type="text" name="ciri_ciri[]" x-model="ciriList[idx]" required
                                   placeholder="Tuliskan sifat atau ciri bangun datar..."
                                   class="flex-1 h-10 px-3.5 rounded-xl bg-white border border-slate-200 text-sm font-medium text-slate-800 outline-none focus:border-[#0077B6]">
                            <button type="button" x-show="ciriList.length > 1" @click="ciriList.splice(idx, 1)"
                                    class="p-2 rounded-xl bg-red-50 hover:bg-red-500 hover:text-white text-red-600 border border-red-100 transition-all cursor-pointer"
                                    title="Hapus baris">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('admin.bangun-datar.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#0077B6] hover:bg-[#005f92] text-white text-xs font-bold transition-all shadow-sm shadow-[#0077B6]/20 cursor-pointer">
                    {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Bangun Datar' }}
                </button>
            </div>

        </form>

    </div>

</div>
@endsection
