@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi Halaman Admin" class="flex flex-col sm:flex-row items-center justify-between gap-3 font-['Jua']">
        <div class="text-xs font-medium text-slate-500">
            Menampilkan
            <span class="font-bold text-slate-800">{{ $paginator->firstItem() ?? 0 }}</span>
            –
            <span class="font-bold text-slate-800">{{ $paginator->lastItem() ?? 0 }}</span>
            dari
            <span class="font-bold text-[#0077B6]">{{ $paginator->total() }}</span>
            data
        </div>

        <div class="flex items-center gap-1.5 flex-wrap justify-center">
            @if ($paginator->onFirstPage())
                <span class="px-3 py-1.5 rounded-xl bg-slate-100 text-slate-400 border border-slate-200 text-xs font-bold cursor-not-allowed">
                    &lsaquo; Sebelumnya
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                   class="px-3 py-1.5 rounded-xl bg-white hover:bg-sky-50 text-slate-700 hover:text-[#0077B6] border border-slate-200 hover:border-[#0077B6]/40 text-xs font-bold transition-all shadow-2xs">
                    &lsaquo; Sebelumnya
                </a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-2.5 py-1.5 text-xs font-bold text-slate-400">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page"
                                  class="min-w-[34px] text-center px-2.5 py-1.5 rounded-xl bg-[#0077B6] text-white border border-[#0077B6] text-xs font-bold shadow-xs shadow-[#0077B6]/25">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                               class="min-w-[34px] text-center px-2.5 py-1.5 rounded-xl bg-white hover:bg-sky-50 text-slate-700 hover:text-[#0077B6] border border-slate-200 hover:border-[#0077B6]/40 text-xs font-bold transition-all">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                   class="px-3 py-1.5 rounded-xl bg-white hover:bg-sky-50 text-slate-700 hover:text-[#0077B6] border border-slate-200 hover:border-[#0077B6]/40 text-xs font-bold transition-all shadow-2xs">
                    Berikutnya &rsaquo;
                </a>
            @else
                <span class="px-3 py-1.5 rounded-xl bg-slate-100 text-slate-400 border border-slate-200 text-xs font-bold cursor-not-allowed">
                    Berikutnya &rsaquo;
                </span>
            @endif
        </div>
    </nav>
@endif
