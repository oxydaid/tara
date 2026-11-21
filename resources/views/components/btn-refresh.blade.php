@props([
    'href' => null,
    'title' => 'Muat Ulang Data',
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
       style="background: linear-gradient(180deg, #34D399 0%, #059669 50%, #047857 100%); box-shadow: 0 4px 0 #065F46, 0 8px 14px rgba(0,0,0,0.28), inset 0 2px 0 rgba(255,255,255,0.6), inset 0 -2px 0 rgba(0,0,0,0.25);">
        <!-- Top gloss reflection -->
        <span class="absolute top-1 left-1.5 right-1.5 h-1/3 rounded-full bg-gradient-to-b from-white/40 to-transparent pointer-events-none"></span>
        <svg viewBox="0 0 24 24" class="w-[54%] h-[54%] text-white drop-shadow-[0_2px_3px_rgba(0,0,0,0.35)] transition-transform group-hover:rotate-180 duration-500" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21.5 2v6h-6M2.5 22v-6h6M2 11.5a10 10 0 0 1 18.8-4.3M22 12.5a10 10 0 0 1-18.8 4.2"/>
        </svg>
    </a>
@else
    <button type="button" 
       {{ $attributes->merge([
           'class' => "group relative inline-flex items-center justify-center rounded-full border-[3px] sm:border-[3.5px] border-white/95 text-white transition-all duration-200 hover:scale-110 active:scale-95 active:translate-y-1 cursor-pointer select-none focus:outline-none {$sizeClasses}",
           'title' => $title
       ]) }}
       style="background: linear-gradient(180deg, #34D399 0%, #059669 50%, #047857 100%); box-shadow: 0 4px 0 #065F46, 0 8px 14px rgba(0,0,0,0.28), inset 0 2px 0 rgba(255,255,255,0.6), inset 0 -2px 0 rgba(0,0,0,0.25);">
        <!-- Top gloss reflection -->
        <span class="absolute top-1 left-1.5 right-1.5 h-1/3 rounded-full bg-gradient-to-b from-white/40 to-transparent pointer-events-none"></span>
        <svg id="btnRefreshIcon" viewBox="0 0 24 24" class="w-[54%] h-[54%] text-white drop-shadow-[0_2px_3px_rgba(0,0,0,0.35)] transition-transform group-hover:rotate-180 duration-500" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21.5 2v6h-6M2.5 22v-6h6M2 11.5a10 10 0 0 1 18.8-4.3M22 12.5a10 10 0 0 1-18.8 4.2"/>
        </svg>
    </button>
@endif
