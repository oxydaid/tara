@props(['logoutRoute' => null, 'onConfirm' => null])

<!-- ============================================================
     KOMPONEN REUSABLE: MODAL KONFIRMASI KELUAR (EXIT)
     ============================================================ -->
<div id="exitModal"
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/65 backdrop-blur-sm opacity-0 pointer-events-none transition-all duration-300 font-['Jua'] select-none">

    <div class="absolute inset-0" onclick="closeExitModal()"></div>

    <div id="exitBox"
         class="relative z-10 w-full max-w-[420px] transform scale-90 transition-all duration-300 rounded-3xl border-4 border-[#3D1804] px-6 py-6 shadow-[0_20px_40px_rgba(0,0,0,0.5),0_8px_0_#280E02]"
         style="background: linear-gradient(180deg, #9E531F 0%, #7E3E11 25%, #6E330C 65%, #522204 100%);">

        <div class="text-center mb-3.5 pt-1">
            <h2 class="text-2xl sm:text-3xl font-bold text-[#FFF2A6]"
                style="text-shadow: 2px 2px 0 #3D1804, -2px -2px 0 #3D1804, 2px -2px 0 #3D1804, -2px 2px 0 #3D1804;">
                Konfirmasi Keluar
            </h2>
            <p class="text-xs text-[#FFE8A3] font-medium mt-1">
                Apakah kamu yakin ingin keluar dari petualangan TARA?
            </p>
        </div>

        <div class="rounded-2xl border-3 border-[#612A07] bg-gradient-to-b from-[#FFFDF0] to-[#FCEEC7] p-4 sm:p-5 shadow text-center space-y-4">
            <p class="text-xs sm:text-sm text-[#785135] font-semibold leading-relaxed">
                Catatan belajarmu telah tersimpan. Kamu dapat kembali melanjutkan belajar kapan saja!
            </p>

            <div class="flex flex-col sm:flex-row items-center gap-2.5 pt-1">
                <button type="button"
                        onclick="closeExitModal()"
                        class="w-full flex-1 rounded-full border-3 border-white/90 bg-gradient-to-b from-[#4ADE80] via-[#22C55E] to-[#15803D] py-2.5 text-white font-bold text-sm sm:text-base tracking-wide shadow-[0_5px_0_#0F5128] hover:brightness-110 active:translate-y-[2px] cursor-pointer">
                    Lanjut Belajar
                </button>
                <button type="button"
                        onclick="{{ $onConfirm ?? 'confirmExitAction()' }}"
                        class="w-full sm:w-auto px-6 rounded-full border-3 border-white/90 bg-gradient-to-b from-[#EF4444] via-[#DC2626] to-[#B91C1C] py-2.5 text-white font-bold text-sm sm:text-base tracking-wide shadow-[0_5px_0_#7F1D1D] hover:brightness-110 active:translate-y-[2px] cursor-pointer">
                    Ya, Keluar
                </button>
            </div>
        </div>

    </div>
</div>

<form id="globalLogoutForm" method="POST" action="{{ $logoutRoute ?? route('logout') }}" class="hidden">
    @csrf
</form>

<script>
    if (typeof window.handleExitWeb !== 'function') {
        window.handleExitWeb = function() {
            var m = document.getElementById('exitModal');
            var b = document.getElementById('exitBox');
            if (m) { m.classList.remove('opacity-0', 'pointer-events-none'); m.classList.add('opacity-100'); }
            if (b) { b.classList.remove('scale-90'); b.classList.add('scale-100'); }
        };
    }
    if (typeof window.closeExitModal !== 'function') {
        window.closeExitModal = function() {
            var m = document.getElementById('exitModal');
            var b = document.getElementById('exitBox');
            if (m) { m.classList.remove('opacity-100'); m.classList.add('opacity-0', 'pointer-events-none'); }
            if (b) { b.classList.remove('scale-100'); b.classList.add('scale-90'); }
        };
    }
    if (typeof window.confirmExitAction !== 'function') {
        window.confirmExitAction = function() {
            var f = document.getElementById('globalLogoutForm');
            if (f) f.submit();
        };
    }
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (typeof window.closeExitModal === 'function') window.closeExitModal();
            if (typeof closeSettingsModal === 'function') closeSettingsModal();
            if (typeof closeProfileModal === 'function') closeProfileModal();
        }
    });
</script>
