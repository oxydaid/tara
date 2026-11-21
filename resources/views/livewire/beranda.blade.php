<div class="relative w-full h-full flex flex-col items-center justify-center p-4 overflow-hidden">

    <style>
        /* ============================================================
           PENGATURAN TAMPILAN KARAKTER
           ============================================================ */
    </style>

    <!-- ============================================================
         1. KARAKTER YUK DAMAR DI SISI KIRI BAWAH (karakter_menyapa.webp)
         ============================================================ -->
    <div class="absolute z-20 pointer-events-none -bottom-2 sm:-bottom-4 md:-bottom-8 -left-1 sm:left-3 md:left-6">
        <div>
            <img src="{{ asset('assets/karakter_menyapa.webp') }}" 
                 alt="Yuk Damar Menyapa" 
                 class="h-[62vh] sm:h-[75vh] md:h-[88vh] lg:h-[96vh] max-h-[720px] w-auto drop-shadow-[0_16px_28px_rgba(0,0,0,0.45)] select-none pointer-events-none">
        </div>
    </div>

    <!-- Speech Bubble Sambutan Yuk Damar (Di Dekat Karakter) -->
    <div class="absolute z-25 left-[180px] sm:left-[260px] md:left-[350px] lg:left-[410px] top-[30%] sm:top-[32%] -translate-y-1/2 max-w-[210px] sm:max-w-[280px] md:max-w-[340px] pointer-events-auto select-none">
        <div class="relative" style="filter: drop-shadow(-4px 6px 0 rgba(180, 83, 9, 0.4));">
            <div class="bg-white rounded-[20px] sm:rounded-[24px] px-3.5 sm:px-4.5 py-2.5 sm:py-3 text-left border-2 border-amber-300 shadow-md">
                <p class="text-[#78350F] text-[11px] sm:text-xs md:text-sm font-['Jua'] leading-snug">
                    Halo teman-teman! Kenalkan, aku <span class="text-[#D97706] font-bold">Yuk Damar</span>. Selamat datang di media <span class="text-[#B45309] font-bold">TARA</span>! Ayo belajar dan bereksplorasi bersama!
                </p>
            </div>
            <!-- Ekor Bubble -->
            <div class="absolute -bottom-2.5 left-4 sm:left-6 w-5 h-3.5 pointer-events-none">
                <svg viewBox="0 0 28 20" class="w-full h-full fill-white overflow-visible">
                    <path d="M0,0 L24,0 C16,8 6,15 -10,20 C-2,12 0,6 0,0 Z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- ============================================================
         2. KONTEN TENGAH: LOGO TARA BESAR & TOMBOL MULAI
         ============================================================ -->
    <div class="z-30 flex flex-col items-center justify-center text-center gap-3 sm:gap-4 md:gap-5 max-w-3xl px-3 sm:px-4 my-auto ml-auto sm:mr-6 md:mr-14 lg:mr-20">
        
        <!-- Logo TARA -->
        <img src="{{ asset('assets/logotara.webp') }}" 
             alt="TARA" 
             class="w-[260px] sm:w-[380px] md:w-[500px] lg:w-[600px] xl:w-[680px] max-h-[46vh] h-auto object-contain drop-shadow-2xl transition-transform hover:scale-105 duration-300 select-none pointer-events-none">

        <!-- Tombol Mulai Petualangan Belajar -->
        @auth
            <a href="{{ route('menu') }}" data-sfx="hover" class="block transition-transform hover:scale-105 active:scale-95 cursor-pointer">
                <img src="{{ asset('assets/mulai.webp') }}" alt="Mulai Petualangan Belajar" class="w-36 sm:w-48 md:w-60 lg:w-64 max-h-[18vh] h-auto object-contain drop-shadow-xl select-none pointer-events-none">
            </a>
        @else
            <button type="button" onclick="openNameModal()" data-sfx="hover" class="block transition-transform hover:scale-105 active:scale-95 cursor-pointer focus:outline-none border-none bg-transparent p-0">
                <img src="{{ asset('assets/mulai.webp') }}" alt="Mulai Petualangan Belajar" class="w-36 sm:w-48 md:w-60 lg:w-64 max-h-[18vh] h-auto object-contain drop-shadow-xl select-none pointer-events-none">
            </button>
        @endauth
    </div>

    <!-- ============================================================
         3. POP UP MODAL MASUKKAN NAMA (Siapa Namamu?)
         ============================================================ -->
    <div id="nameModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm opacity-0 pointer-events-none transition-all duration-300 font-['Jua']">
        
        <!-- Backdrop clickable to close -->
        <div class="absolute inset-0" onclick="closeNameModal()"></div>

        <!-- Modal Box: Papan Kayu / Perkamen Damar Kurung -->
        <div id="modalBox" 
             class="relative z-10 w-full max-w-[420px] transform scale-90 transition-all duration-300 rounded-3xl border-4 border-[#3D1804] px-6 py-6 shadow-[0_20px_40px_rgba(0,0,0,0.6),0_8px_0_#280E02,inset_0_2px_0_rgba(255,255,255,0.25)]"
             style="background: linear-gradient(180deg, #9E531F 0%, #7E3E11 25%, #9E531F 30%, #6E330C 65%, #8A4416 70%, #522204 100%);">
            
            <!-- 4 Baut Sudut -->
            <div class="absolute top-3 left-3 w-4 h-4 rounded-full bg-gradient-to-br from-[#E6BC7E] to-[#6A3F14] border border-[#3E1A04] shadow-inner"></div>
            <div class="absolute top-3 right-3 w-4 h-4 rounded-full bg-gradient-to-br from-[#E6BC7E] to-[#6A3F14] border border-[#3E1A04] shadow-inner"></div>
            <div class="absolute bottom-3 left-3 w-4 h-4 rounded-full bg-gradient-to-br from-[#E6BC7E] to-[#6A3F14] border border-[#3E1A04] shadow-inner"></div>
            <div class="absolute bottom-3 right-3 w-4 h-4 rounded-full bg-gradient-to-br from-[#E6BC7E] to-[#6A3F14] border border-[#3E1A04] shadow-inner"></div>

            <!-- Tombol Tutup (X) -->
            <button type="button" 
                    onclick="closeNameModal()" 
                    class="absolute -top-3.5 -right-3.5 w-10 h-10 rounded-full bg-gradient-to-b from-[#EF4444] to-[#B91C1C] hover:from-[#F87171] hover:to-[#DC2626] text-white font-bold text-lg flex items-center justify-center border-2 border-white shadow-[0_4px_8px_rgba(0,0,0,0.3)] transition-transform hover:scale-110 active:scale-95 cursor-pointer z-20"
                    title="Tutup">
                <svg class="w-5 h-5 text-white stroke-[2.8]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Header Modal -->
            <div class="text-center mb-3">
                <img src="{{ asset('assets/logotara.webp') }}" alt="TARA Logo" class="w-24 md:w-28 h-auto mx-auto mb-1 drop-shadow-md">
                <h2 class="text-2xl md:text-3xl font-bold text-[#FFF2A6] tracking-wide" 
                    style="text-shadow: 2px 2px 0 #3D1804, -2px -2px 0 #3D1804, 2px -2px 0 #3D1804, -2px 2px 0 #3D1804, 0 4px 6px rgba(0,0,0,0.4);">
                    Siapa Namamu?
                </h2>
                <p class="text-xs text-[#FFE8A3] font-medium mt-0.5">
                    Masukkan nama dan nomor absen untuk memulai petualangan!
                </p>
            </div>

            <!-- Bagian Dalam: Kartu Perkamen -->
            <div class="rounded-2xl border-3 border-[#612A07] bg-gradient-to-b from-[#FFFDF0] to-[#FCEEC7] p-4 md:p-5 shadow-[inset_0_2px_4px_rgba(0,0,0,0.15)]">
                @if ($errors->any())
                    <div class="mb-3 rounded-xl bg-red-100 border-2 border-red-400 p-2 text-center text-xs font-bold text-red-700 shadow-sm">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="w-full">
                    @csrf
                    <input type="hidden" name="avatar" value="co">

                    <div class="space-y-3">
                        <!-- Input Nama Lengkap -->
                        <div>
                            <label class="block text-xs md:text-sm font-bold text-[#6D360B] mb-1 text-left" for="modal_name">
                                Nama Lengkap
                            </label>
                            <input id="modal_name" 
                                   name="name" 
                                   type="text" 
                                   value="{{ old('name') }}"
                                   placeholder="Contoh: Budi Santoso" 
                                   required 
                                   autocomplete="name"
                                   class="w-full h-11 rounded-xl bg-white border-2 border-[#A85D25] px-4 text-amber-950 text-sm font-semibold outline-none focus:ring-4 focus:ring-[#E68A2E]/35 focus:border-[#7A3408] placeholder:font-normal placeholder:text-amber-800/40 shadow-inner font-['Jua']">
                        </div>

                        <!-- Input Nomor Absen -->
                        <div>
                            <label class="block text-xs md:text-sm font-bold text-[#6D360B] mb-1 text-left" for="modal_no_absen">
                                Nomor Absen
                            </label>
                            <input id="modal_no_absen" 
                                   name="no_absen" 
                                   type="text" 
                                   value="{{ old('no_absen') }}"
                                   placeholder="Contoh: 12" 
                                   autocomplete="off"
                                   class="w-full h-11 rounded-xl bg-white border-2 border-[#A85D25] px-4 text-amber-950 text-sm font-semibold outline-none focus:ring-4 focus:ring-[#E68A2E]/35 focus:border-[#7A3408] placeholder:font-normal placeholder:text-amber-800/40 shadow-inner font-['Jua']">
                        </div>

                        <!-- Petunjuk Singkat -->
                        <div class="bg-amber-100/80 border border-amber-300/80 rounded-xl p-2 text-left">
                            <p class="text-[11px] leading-tight text-amber-950/85 font-semibold">
                                Petunjuk: Gunakan <span class="font-bold text-[#8A4416]">Nama & Nomor Absen</span> yang sama untuk melanjutkan progres belajarmu.
                            </p>
                        </div>

                        <!-- Tombol Submit -->
                        <div class="pt-1">
                            <button type="submit"
                                    class="w-full rounded-full border-3 border-white/90 bg-gradient-to-b from-[#4ADE80] via-[#22C55E] to-[#15803D] py-2.5 text-white font-bold text-lg tracking-wide shadow-[0_6px_0_#0F5128,0_10px_15px_rgba(0,0,0,0.2)] hover:brightness-110 active:translate-y-[2px] active:shadow-[0_2px_0_#0F5128] transition-all cursor-pointer font-['Jua']">
                                Mulai Belajar!
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Script Kontrol Pop Up Modal -->
    <script>
        function openNameModal() {
            const modal = document.getElementById('nameModal');
            const modalBox = document.getElementById('modalBox');
            const nameInput = document.getElementById('modal_name');

            if (modal && modalBox) {
                modal.classList.remove('opacity-0', 'pointer-events-none');
                modal.classList.add('opacity-100');
                
                modalBox.classList.remove('scale-90');
                modalBox.classList.add('scale-100');

                setTimeout(() => {
                    if (nameInput) nameInput.focus();
                }, 150);
            }
        }

        function closeNameModal() {
            const modal = document.getElementById('nameModal');
            const modalBox = document.getElementById('modalBox');

            if (modal && modalBox) {
                modal.classList.remove('opacity-100');
                modal.classList.add('opacity-0', 'pointer-events-none');
                
                modalBox.classList.remove('scale-100');
                modalBox.classList.add('scale-90');
            }
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeNameModal();
            }
        });

        @if ($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                openNameModal();
            });
        @endif
    </script>
</div>
