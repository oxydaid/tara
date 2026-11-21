@props([
    'href' => null,
    'title' => 'Menu Beranda',
    'size' => null,
])

@php
    $sizeClasses = $size ?? 'w-10 h-10 sm:w-12 sm:h-12 md:w-14 md:h-14';
@endphp

@if($href)
    <a href="{{ $href }}" 
       {{ $attributes->merge([
           'class' => "group relative inline-flex items-center justify-center rounded-full border-[3.5px] border-white/95 text-white transition-all duration-200 hover:scale-110 active:scale-95 active:translate-y-1 cursor-pointer select-none focus:outline-none {$sizeClasses}",
           'title' => $title
       ]) }}
       style="background: linear-gradient(180deg, #FBBF24 0%, #F59E0B 50%, #D97706 100%); box-shadow: 0 5px 0 #92400E, 0 8px 16px rgba(0,0,0,0.3), inset 0 2px 0 rgba(255,255,255,0.6), inset 0 -2px 0 rgba(0,0,0,0.25);">
        <!-- Top gloss reflection -->
        <span class="absolute top-1 left-2 right-2 h-1/3 rounded-full bg-gradient-to-b from-white/40 to-transparent pointer-events-none"></span>
        <svg viewBox="0 0 24 24" class="w-[54%] h-[54%] text-white drop-shadow-[0_2px_3px_rgba(0,0,0,0.35)]" fill="currentColor">
            <path d="M11.47 3.84a.75.75 0 011.06 0l8.69 8.69a.75.75 0 101.06-1.06l-8.689-8.69a2.25 2.25 0 00-3.182 0l-8.69 8.69a.75.75 0 001.061 1.06l8.69-8.69z" />
            <path d="M12 5.432l8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 01-.75-.75v-4.5a.75.75 0 00-.75-.75h-3a.75.75 0 00-.75.75V21a.75.75 0 01-.75.75H5.625a1.875 1.875 0 01-1.875-1.875v-6.198a2.29 2.29 0 00.091-.086L12 5.432z" />
        </svg>
    </a>
@else
    <button type="button" 
       {{ $attributes->merge([
           'class' => "group relative inline-flex items-center justify-center rounded-full border-[3.5px] border-white/95 text-white transition-all duration-200 hover:scale-110 active:scale-95 active:translate-y-1 cursor-pointer select-none focus:outline-none {$sizeClasses}",
           'title' => $title
       ]) }}
       style="background: linear-gradient(180deg, #FBBF24 0%, #F59E0B 50%, #D97706 100%); box-shadow: 0 5px 0 #92400E, 0 8px 16px rgba(0,0,0,0.3), inset 0 2px 0 rgba(255,255,255,0.6), inset 0 -2px 0 rgba(0,0,0,0.25);">
        <!-- Top gloss reflection -->
        <span class="absolute top-1 left-2 right-2 h-1/3 rounded-full bg-gradient-to-b from-white/40 to-transparent pointer-events-none"></span>
        <svg viewBox="0 0 24 24" class="w-[54%] h-[54%] text-white drop-shadow-[0_2px_3px_rgba(0,0,0,0.35)]" fill="currentColor">
            <path d="M11.47 3.84a.75.75 0 011.06 0l8.69 8.69a.75.75 0 101.06-1.06l-8.689-8.69a2.25 2.25 0 00-3.182 0l-8.69 8.69a.75.75 0 001.061 1.06l8.69-8.69z" />
            <path d="M12 5.432l8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 01-.75-.75v-4.5a.75.75 0 00-.75-.75h-3a.75.75 0 00-.75.75V21a.75.75 0 01-.75.75H5.625a1.875 1.875 0 01-1.875-1.875v-6.198a2.29 2.29 0 00.091-.086L12 5.432z" />
        </svg>
    </button>
@endif
