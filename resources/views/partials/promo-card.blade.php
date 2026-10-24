{{-- Param: $p (promo), $compact (opsional). Ukuran & susunan SAMA dengan partials/post-card (card berita). --}}
@php
    $compact = $compact ?? false;
    $promoSummary = \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags((string) $p->description), ENT_QUOTES | ENT_HTML5, 'UTF-8'))), 160);
@endphp
<a href="{{ route('promos.show', $p) }}" class="group flex h-full w-full flex-col overflow-hidden rounded-2xl border border-zinc-200 bg-white transition hover:border-honda hover:shadow-lg">
    @if($p->image)
        <img src="{{ asset('storage/'.$p->image) }}" alt="{{ $p->title }}" loading="lazy" class="aspect-[16/10] w-full object-cover transition duration-300 group-hover:scale-[1.02]">
    @else
        <div class="flex aspect-[16/10] w-full items-center justify-center bg-zinc-100 text-xs text-zinc-400">Promo</div>
    @endif
    <div class="flex flex-1 flex-col {{ $compact ? 'p-3 sm:p-5' : 'p-5' }}">
        <p class="text-xs font-semibold text-zinc-400">{{ $p->end_date ? 'Berlaku hingga '.$p->end_date->translatedFormat('d F Y') : 'Promo' }}</p>
        <h3 class="mt-1 line-clamp-2 font-bold leading-snug text-zinc-900 group-hover:text-honda {{ $compact ? 'text-sm md:text-base' : 'text-sm md:text-base' }}">{{ $p->title }}</h3>
        <p class="mt-2 line-clamp-3 text-sm leading-relaxed text-zinc-600 {{ $compact ? 'hidden sm:block' : '' }}">{{ $promoSummary }}</p>
        <span class="mt-auto pt-3 font-bold text-honda {{ $compact ? 'text-xs sm:text-sm' : 'text-sm' }}">Lihat detail promo</span>
    </div>
</a>
