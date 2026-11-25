@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi Halaman Hasil" class="flex flex-col sm:flex-row items-center justify-between gap-2.5 pt-2 font-['Jua']">
        <div class="text-[11px] sm:text-xs font-bold text-[#8A4416]">
            Halaman {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}
            <span class="font-normal text-[#6D4C33]">({{ $paginator->total() }} kelompok)</span>
        </div>

        <div class="flex items-center gap-1.5 flex-wrap justify-center">
            @if ($paginator->onFirstPage())
                <span class="px-3 py-1 rounded-full bg-stone-100 text-stone-400 border border-stone-200 text-xs font-bold cursor-not-allowed">
                    &lsaquo; Prev
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                   class="px-3 py-1 rounded-full bg-[#FFF2D6] hover:bg-[#FFE8B8] text-[#8A4416] border border-[#E9C37A] text-xs font-bold transition-all shadow-2xs">
                    &lsaquo; Prev
                </a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-2 py-1 text-xs font-bold text-[#8A4416]/60">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page"
                                  class="min-w-[30px] text-center px-2.5 py-1 rounded-full bg-[#8B5CF6] text-white border border-[#7C3AED] text-xs font-bold shadow-xs">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                               class="min-w-[30px] text-center px-2.5 py-1 rounded-full bg-white hover:bg-[#FFF2D6] text-[#8A4416] border border-[#E9C37A] text-xs font-bold transition-all">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                   class="px-3 py-1 rounded-full bg-[#FFF2D6] hover:bg-[#FFE8B8] text-[#8A4416] border border-[#E9C37A] text-xs font-bold transition-all shadow-2xs">
                    Next &rsaquo;
                </a>
            @else
                <span class="px-3 py-1 rounded-full bg-stone-100 text-stone-400 border border-stone-200 text-xs font-bold cursor-not-allowed">
                    Next &rsaquo;
                </span>
            @endif
        </div>
    </nav>
@endif
