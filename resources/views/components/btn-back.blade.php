@props([
    'href' => null,
    'title' => 'Kembali',
    'size' => null,
    'variant' => 'orange',
])

@php
    $sizeClasses = $size ?? 'w-10 h-10 sm:w-12 sm:h-12 md:w-14 md:h-14';

    $bgStyles = match($variant) {
        'green' => 'background: linear-gradient(180deg, #4ADE80 0%, #22C55E 50%, #15803D 100%); box-shadow: 0 5px 0 #0F5128, 0 8px 16px rgba(0,0,0,0.3), inset 0 2px 0 rgba(255,255,255,0.6), inset 0 -2px 0 rgba(0,0,0,0.25);',
        'amber' => 'background: linear-gradient(180deg, #FBBF24 0%, #F59E0B 50%, #D97706 100%); box-shadow: 0 5px 0 #92400E, 0 8px 16px rgba(0,0,0,0.3), inset 0 2px 0 rgba(255,255,255,0.6), inset 0 -2px 0 rgba(0,0,0,0.25);',
        'sky'   => 'background: linear-gradient(180deg, #38BDF8 0%, #0284C7 50%, #0369A1 100%); box-shadow: 0 5px 0 #075985, 0 8px 16px rgba(0,0,0,0.3), inset 0 2px 0 rgba(255,255,255,0.6), inset 0 -2px 0 rgba(0,0,0,0.25);',
        default => 'background: linear-gradient(180deg, #FFA940 0%, #FA8C16 50%, #D46B08 100%); box-shadow: 0 5px 0 #873800, 0 8px 16px rgba(0,0,0,0.3), inset 0 2px 0 rgba(255,255,255,0.6), inset 0 -2px 0 rgba(0,0,0,0.25);',
    };
@endphp

@if($href)
    <a href="{{ $href }}" 
       {{ $attributes->merge([
           'class' => "group relative inline-flex items-center justify-center rounded-full border-[3.5px] border-white/95 text-white transition-all duration-200 hover:scale-110 active:scale-95 active:translate-y-1 cursor-pointer select-none focus:outline-none {$sizeClasses}",
           'title' => $title
       ]) }}
       style="{{ $bgStyles }}">
        <!-- Top gloss reflection -->
        <span class="absolute top-1 left-2 right-2 h-1/3 rounded-full bg-gradient-to-b from-white/40 to-transparent pointer-events-none"></span>
        <svg viewBox="0 0 24 24" class="w-1/2 h-1/2 text-white -translate-x-0.5 drop-shadow-[0_2px_3px_rgba(0,0,0,0.35)]" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M15 19l-7-7 7-7" />
        </svg>
    </a>
@else
    <button type="button" 
       {{ $attributes->merge([
           'class' => "group relative inline-flex items-center justify-center rounded-full border-[3.5px] border-white/95 text-white transition-all duration-200 hover:scale-110 active:scale-95 active:translate-y-1 cursor-pointer select-none focus:outline-none {$sizeClasses}",
           'title' => $title
       ]) }}
       style="{{ $bgStyles }}">
        <!-- Top gloss reflection -->
        <span class="absolute top-1 left-2 right-2 h-1/3 rounded-full bg-gradient-to-b from-white/40 to-transparent pointer-events-none"></span>
        <svg viewBox="0 0 24 24" class="w-1/2 h-1/2 text-white -translate-x-0.5 drop-shadow-[0_2px_3px_rgba(0,0,0,0.35)]" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M15 19l-7-7 7-7" />
        </svg>
    </button>
@endif
