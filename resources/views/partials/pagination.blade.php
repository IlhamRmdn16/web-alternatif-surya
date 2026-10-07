{{-- Paginasi: "Menampilkan x-y dari z" + tombol halaman. Dipakai di admin (motor, prospek, berita) dan berita publik. --}}
@if ($paginator->total() > 0)
<nav role="navigation" aria-label="Navigasi halaman" class="flex flex-col items-center justify-between gap-3 sm:flex-row">
    <p class="text-sm text-zinc-500">
        Menampilkan <b class="text-zinc-800">{{ $paginator->firstItem() }}</b>&ndash;<b class="text-zinc-800">{{ $paginator->lastItem() }}</b>
        dari <b class="text-zinc-800">{{ $paginator->total() }}</b> data
    </p>

    @if ($paginator->hasPages())
        <ul class="flex flex-wrap items-center justify-center gap-1.5">
            {{-- Sebelumnya --}}
            @if ($paginator->onFirstPage())
                <li><span class="inline-flex h-9 cursor-not-allowed items-center rounded-lg border border-zinc-200 px-3 text-sm font-semibold text-zinc-300">&lsaquo; Sebelumnya</span></li>
            @else
                <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex h-9 items-center rounded-lg border border-zinc-300 bg-white px-3 text-sm font-semibold text-zinc-700 transition hover:border-honda hover:text-honda">&lsaquo; Sebelumnya</a></li>
            @endif

            {{-- Nomor halaman --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li><span class="px-1.5 text-zinc-400">{{ $element }}</span></li>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li><span aria-current="page" class="inline-flex h-9 min-w-[2.25rem] items-center justify-center rounded-lg bg-honda px-3 text-sm font-bold text-white">{{ $page }}</span></li>
                        @else
                            <li><a href="{{ $url }}" aria-label="Halaman {{ $page }}" class="inline-flex h-9 min-w-[2.25rem] items-center justify-center rounded-lg border border-zinc-300 bg-white px-3 text-sm font-semibold text-zinc-700 transition hover:border-honda hover:text-honda">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Berikutnya --}}
            @if ($paginator->hasMorePages())
                <li><a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex h-9 items-center rounded-lg border border-zinc-300 bg-white px-3 text-sm font-semibold text-zinc-700 transition hover:border-honda hover:text-honda">Berikutnya &rsaquo;</a></li>
            @else
                <li><span class="inline-flex h-9 cursor-not-allowed items-center rounded-lg border border-zinc-200 px-3 text-sm font-semibold text-zinc-300">Berikutnya &rsaquo;</span></li>
            @endif
        </ul>
    @endif
</nav>
@endif
