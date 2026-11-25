<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="bg-slate-50" style="height: auto; min-height: 100%; overflow-y: auto;">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

        <title>{{ $title ?? 'Dashboard Admin' }} - TARA Media Pembelajaran</title>
        <link rel="icon" type="image/webp" href="{{ asset('assets/logotara.webp') }}">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Jua&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

        <style>
            html, body {
                height: auto !important;
                min-height: 100% !important;
                overflow-x: hidden !important;
                overflow-y: auto !important;
                position: static !important;
                -webkit-overflow-scrolling: touch !important;
            }
        </style>
    </head>
    <body class="min-h-screen font-['Jua'] text-slate-800 antialiased bg-[#F8FAFC] selection:bg-[#0077B6] selection:text-white" style="height: auto; min-height: 100vh; overflow-y: auto;" x-data="{ sidebarOpen: false }">
        
        <div class="min-h-screen flex flex-col bg-[#F8FAFC]">
            
            <!-- Mobile Sidebar Backdrop -->
            <div x-show="sidebarOpen" 
                 x-transition:enter="transition-opacity ease-linear duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="sidebarOpen = false" 
                 class="fixed inset-0 z-40 bg-slate-900/30 backdrop-blur-xs md:hidden"
                 style="display: none;"></div>

            <!-- Sidebar (PERMANENTLY FIXED ON DESKTOP & SLIDE DRAWER ON MOBILE) -->
            <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
                   class="fixed inset-y-0 left-0 z-50 w-72 bg-white text-slate-800 flex flex-col transition-transform duration-300 ease-in-out shadow-lg md:shadow-none border-r border-slate-200/90 h-screen overflow-hidden"
                   style="position: fixed; top: 0; bottom: 0; left: 0; width: 18rem; height: 100vh; z-index: 40;">
                
                <!-- Logo & Brand -->
                <div class="p-6 border-b border-slate-100 flex items-center justify-between shrink-0">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
                        <div class="w-11 h-11 rounded-2xl bg-amber-50 border border-amber-200 p-1.5 flex items-center justify-center shadow-xs group-hover:scale-105 transition-transform">
                            <img src="{{ asset('assets/logotara.webp') }}" alt="TARA Logo" class="w-full h-auto object-contain">
                        </div>
                        <div>
                            <div class="font-['Jua'] text-xl text-slate-900 leading-tight tracking-wide">TARA Admin</div>
                            <div class="text-xs font-semibold text-amber-600">Media Pembelajaran</div>
                        </div>
                    </a>

                    <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-slate-700 p-1 rounded-lg">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Navigation Menu -->
                <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
                    <div class="px-3 pb-2 text-[10px] font-extrabold uppercase tracking-wider text-slate-400">
                        Menu Utama
                    </div>

                    @php
                        $navItems = [
                            [
                                'label' => 'Dashboard',
                                'route' => 'admin.dashboard',
                                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />',
                            ],
                            [
                                'label' => 'Hasil Tim Kolaborasi',
                                'route' => 'admin.kolaborasi.index',
                                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />',
                            ],
                            [
                                'label' => 'Master Bangun Datar',
                                'route' => 'admin.bangun-datar.index',
                                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />',
                            ],
                            [
                                'label' => 'Data Siswa & Absen',
                                'route' => 'admin.users.index',
                                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />',
                            ],
                        ];
                    @endphp

                    @foreach ($navItems as $item)
                        @php
                            $isActive = request()->routeIs($item['route'].'*');
                        @endphp
                        <a href="{{ route($item['route']) }}"
                           class="flex items-center gap-3.5 px-4 py-3 rounded-xl font-bold text-sm transition-all {{ $isActive ? 'bg-[#0077B6] text-white shadow-md shadow-[#0077B6]/25' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-5 h-5 shrink-0 {{ $isActive ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                {!! $item['icon'] !!}
                            </svg>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </nav>

                <!-- Admin Bottom Actions -->
                <div class="p-4 border-t border-slate-100 space-y-2 shrink-0 mt-auto bg-white">
                    <a href="{{ route('menu') }}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center gap-2 w-full py-2.5 px-4 rounded-xl bg-sky-50 hover:bg-sky-100 text-[#0077B6] text-xs font-bold transition-all border border-sky-100">
                        <svg class="w-4 h-4 text-[#0077B6]" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                        </svg>
                        <span>Buka Media Siswa</span>
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center justify-center gap-2 w-full py-2.5 px-4 rounded-xl bg-slate-50 hover:bg-rose-50 text-slate-600 hover:text-rose-600 text-xs font-bold transition-all border border-slate-200/80 hover:border-rose-200">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                            </svg>
                            <span>Keluar (Logout)</span>
                        </button>
                    </form>
                </div>

            </aside>

            <!-- Main Content Area (Offset by md:pl-72 for fixed sidebar) -->
            <div class="flex-1 flex flex-col min-w-0 md:pl-72 bg-[#F8FAFC]">
                
                <!-- Top Header -->
                <header class="bg-white border-b border-slate-200/80 sticky top-0 z-30 shadow-xs">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
                        
                        <div class="flex items-center gap-3">
                            <button @click="sidebarOpen = true" class="md:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                                </svg>
                            </button>
                            <h1 class="font-['Jua'] text-xl sm:text-2xl text-slate-900 tracking-wide">
                                {{ $title ?? 'Dashboard Admin' }}
                            </h1>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 bg-slate-50 rounded-full border border-slate-200">
                                <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></div>
                                <span class="text-xs font-bold text-slate-700">Administrator</span>
                            </div>
                        </div>

                    </div>
                </header>

                <!-- Page Body -->
                <main class="flex-1 p-4 sm:p-6 lg:p-8 bg-[#F8FAFC]">
                    <div class="max-w-7xl mx-auto space-y-6">
                        
                        <!-- Success Alert -->
                        @if (session('status'))
                            <div class="rounded-2xl bg-emerald-50 border border-emerald-200 p-4 flex items-center gap-3 text-emerald-800 shadow-sm animate-fade-in">
                                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                                <span class="text-sm font-bold">{{ session('status') }}</span>
                            </div>
                        @endif

                        <!-- Error Alert -->
                        @if ($errors->any())
                            <div class="rounded-2xl bg-red-50 border border-red-200 p-4 text-red-800 shadow-sm">
                                <div class="flex items-center gap-2 font-bold text-sm mb-2 text-red-900">
                                    <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                    </svg>
                                    <span>Terdapat kesalahan pada input:</span>
                                </div>
                                <ul class="list-disc pl-6 text-xs space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @yield('content')

                    </div>
                </main>

            </div>

        </div>

    </body>
</html>
