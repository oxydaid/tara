@props([
    'href' => null,
    'title' => 'Keluar',
    'size' => null,
])

@php
    $sizeClasses = $size ?? 'w-9 h-9 sm:w-11 sm:h-11 md:w-13 md:h-13';
@endphp

@if($href)
    <a href="{{ $href }}" 
       {{ $attributes->merge([
           'class' => "group relative inline-flex items-center justify-center rounded-full border-[3px] sm:border-[3.5px] border-white/95 text-white transition-all duration-200 hover:scale-110 active:scale-95 active:translate-y-1 cursor-pointer select-none focus:outline-none {$sizeClasses}",
           'title' => $title
       ]) }}
       style="background: linear-gradient(180deg, #EF4444 0%, #DC2626 50%, #B91C1C 100%); box-shadow: 0 4px 0 #7F1D1D, 0 8px 14px rgba(0,0,0,0.28), inset 0 2px 0 rgba(255,255,255,0.6), inset 0 -2px 0 rgba(0,0,0,0.25);">
        <!-- Top gloss reflection -->
        <span class="absolute top-1 left-1.5 right-1.5 h-1/3 rounded-full bg-gradient-to-b from-white/40 to-transparent pointer-events-none"></span>
        <svg viewBox="0 0 24 24" class="w-[52%] h-[52%] text-white drop-shadow-[0_2px_3px_rgba(0,0,0,0.35)]" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
            <path d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
        </svg>
    </a>
@else
    <button type="button" 
       {{ $attributes->merge([
           'class' => "group relative inline-flex items-center justify-center rounded-full border-[3px] sm:border-[3.5px] border-white/95 text-white transition-all duration-200 hover:scale-110 active:scale-95 active:translate-y-1 cursor-pointer select-none focus:outline-none {$sizeClasses}",
           'title' => $title
       ]) }}
       style="background: linear-gradient(180deg, #EF4444 0%, #DC2626 50%, #B91C1C 100%); box-shadow: 0 4px 0 #7F1D1D, 0 8px 14px rgba(0,0,0,0.28), inset 0 2px 0 rgba(255,255,255,0.6), inset 0 -2px 0 rgba(0,0,0,0.25);">
        <!-- Top gloss reflection -->
        <span class="absolute top-1 left-1.5 right-1.5 h-1/3 rounded-full bg-gradient-to-b from-white/40 to-transparent pointer-events-none"></span>
        <svg viewBox="0 0 24 24" class="w-[52%] h-[52%] text-white drop-shadow-[0_2px_3px_rgba(0,0,0,0.35)]" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
            <path d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
        </svg>
    </button>
@endif
