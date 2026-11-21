@props([
    'href' => null,
    'title' => 'Pengaturan Suara',
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
       style="background: linear-gradient(180deg, #38BDF8 0%, #0284C7 50%, #0369A1 100%); box-shadow: 0 4px 0 #075985, 0 8px 14px rgba(0,0,0,0.28), inset 0 2px 0 rgba(255,255,255,0.6), inset 0 -2px 0 rgba(0,0,0,0.25);">
        <!-- Top gloss reflection -->
        <span class="absolute top-1 left-1.5 right-1.5 h-1/3 rounded-full bg-gradient-to-b from-white/40 to-transparent pointer-events-none"></span>
        <svg viewBox="0 0 24 24" class="w-[54%] h-[54%] text-white drop-shadow-[0_2px_3px_rgba(0,0,0,0.35)] transition-transform group-hover:rotate-45 duration-300" fill="currentColor">
            <path fill-rule="evenodd" d="M11.078 2.25c-.917 0-1.699.663-1.85 1.567L9.05 4.889c-.02.12-.115.26-.297.348a7.493 7.493 0 00-.986.57c-.166.11-.31.121-.433.06L6.08 5.25a1.875 1.875 0 00-2.285.807l-1.5 2.598a1.875 1.875 0 00.435 2.392l1.01.815c.1.08.14.21.12.35a7.514 7.514 0 000 1.157c.02.14-.02.27-.12.35l-1.01.815a1.875 1.875 0 00-.435 2.392l1.5 2.598a1.875 1.875 0 002.285.807l1.254-.617c.122-.06.267-.05.433.06.31.21.64.4.986.57.182.088.277.228.297.348l.178 1.072c.151.904.933 1.567 1.85 1.567h3c.917 0 1.699-.663 1.85-1.567l.178-1.072c.02-.12.115-.26.297-.348.346-.17.676-.36.986-.57.166-.11.311-.121.433-.06l1.254.617a1.875 1.875 0 002.285-.807l1.5-2.598a1.875 1.875 0 00-.435-2.392l-1.01-.815c-.1-.08-.14-.21-.12-.35a7.514 7.514 0 000-1.157c-.02-.14.02-.27.12-.35l1.01-.815a1.875 1.875 0 00.435-2.392l-1.5-2.598a1.875 1.875 0 00-2.285-.807l-1.254.617c-.122.06-.267.05-.433-.06a7.493 7.493 0 00-.986-.57c-.182-.088-.277-.228-.297-.348l-.178-1.072a1.875 1.875 0 00-1.85-1.567h-3zM12 8.25a3.75 3.75 0 100 7.5 3.75 3.75 0 000-7.5z" clip-rule="evenodd" />
        </svg>
    </a>
@else
    <button type="button" 
       {{ $attributes->merge([
           'class' => "group relative inline-flex items-center justify-center rounded-full border-[3px] sm:border-[3.5px] border-white/95 text-white transition-all duration-200 hover:scale-110 active:scale-95 active:translate-y-1 cursor-pointer select-none focus:outline-none {$sizeClasses}",
           'title' => $title
       ]) }}
       style="background: linear-gradient(180deg, #38BDF8 0%, #0284C7 50%, #0369A1 100%); box-shadow: 0 4px 0 #075985, 0 8px 14px rgba(0,0,0,0.28), inset 0 2px 0 rgba(255,255,255,0.6), inset 0 -2px 0 rgba(0,0,0,0.25);">
        <!-- Top gloss reflection -->
        <span class="absolute top-1 left-1.5 right-1.5 h-1/3 rounded-full bg-gradient-to-b from-white/40 to-transparent pointer-events-none"></span>
        <svg viewBox="0 0 24 24" class="w-[54%] h-[54%] text-white drop-shadow-[0_2px_3px_rgba(0,0,0,0.35)] transition-transform group-hover:rotate-45 duration-300" fill="currentColor">
            <path fill-rule="evenodd" d="M11.078 2.25c-.917 0-1.699.663-1.85 1.567L9.05 4.889c-.02.12-.115.26-.297.348a7.493 7.493 0 00-.986.57c-.166.11-.31.121-.433.06L6.08 5.25a1.875 1.875 0 00-2.285.807l-1.5 2.598a1.875 1.875 0 00.435 2.392l1.01.815c.1.08.14.21.12.35a7.514 7.514 0 000 1.157c.02.14-.02.27-.12.35l-1.01.815a1.875 1.875 0 00-.435 2.392l1.5 2.598a1.875 1.875 0 002.285.807l1.254-.617c.122-.06.267-.05.433.06.31.21.64.4.986.57.182.088.277.228.297.348l.178 1.072c.151.904.933 1.567 1.85 1.567h3c.917 0 1.699-.663 1.85-1.567l.178-1.072c.02-.12.115-.26.297-.348.346-.17.676-.36.986-.57.166-.11.311-.121.433-.06l1.254.617a1.875 1.875 0 002.285-.807l1.5-2.598a1.875 1.875 0 00-.435-2.392l-1.01-.815c-.1-.08-.14-.21-.12-.35a7.514 7.514 0 000-1.157c-.02-.14.02-.27.12-.35l1.01-.815a1.875 1.875 0 00.435-2.392l-1.5-2.598a1.875 1.875 0 00-2.285-.807l-1.254.617c-.122.06-.267.05-.433-.06a7.493 7.493 0 00-.986-.57c-.182-.088-.277-.228-.297-.348l-.178-1.072a1.875 1.875 0 00-1.85-1.567h-3zM12 8.25a3.75 3.75 0 100 7.5 3.75 3.75 0 000-7.5z" clip-rule="evenodd" />
        </svg>
    </button>
@endif
