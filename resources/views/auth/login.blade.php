<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

        <title>Masuk Petualangan - TARA</title>
        <link rel="icon" type="image/webp" href="{{ asset('assets/logotara.webp') }}">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Jua&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen overflow-hidden font-['Jua'] select-none">
        <div class="landscape-force">
            <div data-app-root data-content style="background-image: url('{{ asset('assets/berandamalam.webp') }}'); background-size: cover; background-position: center;">
                <div class="w-full h-full flex items-center justify-center p-3 sm:p-4 md:p-6">
                    <div class="w-full max-w-[440px] flex flex-col items-center">
                        <img src="{{ asset('assets/logotara.webp') }}" alt="TARA Logo" class="w-24 sm:w-28 md:w-32 h-auto mb-1 drop-shadow-md">

                        <h1 class="text-2xl sm:text-3xl md:text-4xl text-[#3D1804] mb-2 text-center leading-tight" style="text-shadow: 2px 2px 0 #FFF2A6, -2px -2px 0 #FFF2A6, 2px -2px 0 #FFF2A6, -2px 2px 0 #FFF2A6;">
                            Selamat Datang di TARA!
                        </h1>

                        @if ($errors->any())
                            <div class="w-full mb-2.5 rounded-xl bg-red-100 border-2 border-red-300 px-4 py-2 text-xs font-bold text-red-800 text-center shadow-xs">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}" class="w-full">
                            @csrf

                            <div class="w-full rounded-3xl border-4 border-[#E9C37A] bg-[#FFFDF5] px-5 py-4 shadow-[0_12px_30px_rgba(0,0,0,0.2)]">
                                <div class="space-y-2.5">
                                    <div>
                                        <label class="block text-xs md:text-sm text-[#8A4416] font-bold mb-1" for="name">Nama Lengkap Siswa</label>
                                        <input id="name" name="name" type="text" value="{{ old('name') }}" placeholder="Tuliskan nama lengkapmu..." required autofocus
                                            class="w-full h-10 md:h-11 rounded-xl bg-white border-2 border-[#E9C37A]/70 px-3.5 text-slate-800 text-xs md:text-sm font-bold outline-none focus:ring-3 focus:ring-[#E57354]/40 placeholder:font-normal placeholder:text-slate-400">
                                    </div>

                                    <div>
                                        <label class="block text-xs md:text-sm text-[#8A4416] font-bold mb-1" for="no_absen">Nomor Absen</label>
                                        <input id="no_absen" name="no_absen" type="text" value="{{ old('no_absen') }}" placeholder="Contoh: 07 atau 12..." required
                                            class="w-full h-10 md:h-11 rounded-xl bg-white border-2 border-[#E9C37A]/70 px-3.5 text-slate-800 text-xs md:text-sm font-bold outline-none focus:ring-3 focus:ring-[#E57354]/40 placeholder:font-normal placeholder:text-slate-400">
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-center mt-4">
                                <button type="submit"
                                    class="w-full max-w-[260px] rounded-full border-3 border-white bg-gradient-to-b from-[#E57354] to-[#C94F32] py-2.5 text-white text-base md:text-lg font-bold tracking-wide shadow-[0_6px_0_#9E3319] hover:brightness-110 active:translate-y-[2px] active:shadow-[0_2px_0_#9E3319] transition-all cursor-pointer">
                                    Mulai Petualangan!
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
