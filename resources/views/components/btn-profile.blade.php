@props([
    'href' => null,
    'title' => 'Identitas & Profil Siswa',
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
       style="background: linear-gradient(180deg, #A855F7 0%, #9333EA 50%, #7E22CE 100%); box-shadow: 0 4px 0 #581C87, 0 8px 14px rgba(0,0,0,0.28), inset 0 2px 0 rgba(255,255,255,0.6), inset 0 -2px 0 rgba(0,0,0,0.25);">
        <!-- Top gloss reflection -->
        <span class="absolute top-1 left-1.5 right-1.5 h-1/3 rounded-full bg-gradient-to-b from-white/40 to-transparent pointer-events-none"></span>
        <svg viewBox="0 0 24 24" class="w-[54%] h-[54%] text-white drop-shadow-[0_2px_3px_rgba(0,0,0,0.35)]" fill="currentColor">
            <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z" clip-rule="evenodd" />
        </svg>
    </a>
@else
    <button type="button" 
       {{ $attributes->merge([
           'class' => "group relative inline-flex items-center justify-center rounded-full border-[3px] sm:border-[3.5px] border-white/95 text-white transition-all duration-200 hover:scale-110 active:scale-95 active:translate-y-1 cursor-pointer select-none focus:outline-none {$sizeClasses}",
           'title' => $title
       ]) }}
       style="background: linear-gradient(180deg, #A855F7 0%, #9333EA 50%, #7E22CE 100%); box-shadow: 0 4px 0 #581C87, 0 8px 14px rgba(0,0,0,0.28), inset 0 2px 0 rgba(255,255,255,0.6), inset 0 -2px 0 rgba(0,0,0,0.25);">
        <!-- Top gloss reflection -->
        <span class="absolute top-1 left-1.5 right-1.5 h-1/3 rounded-full bg-gradient-to-b from-white/40 to-transparent pointer-events-none"></span>
        <svg viewBox="0 0 24 24" class="w-[54%] h-[54%] text-white drop-shadow-[0_2px_3px_rgba(0,0,0,0.35)]" fill="currentColor">
            <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z" clip-rule="evenodd" />
        </svg>
    </button>
@endif
