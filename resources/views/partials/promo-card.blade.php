<a href="{{ route('promos.show', $p) }}" class="group overflow-hidden rounded-2xl border border-zinc-200 bg-white transition hover:border-honda hover:shadow-lg">
    @if($p->image)<img src="{{ asset('storage/'.$p->image) }}" alt="{{ $p->title }}" loading="lazy" class="aspect-[4/3] w-full object-cover">@endif
    <div class="p-5">
        <h3 class="font-bold text-zinc-900 group-hover:text-honda">{{ $p->title }}</h3>
        @if($p->end_date)<p class="mt-1 text-xs text-zinc-500">Berlaku hingga {{ $p->end_date->translatedFormat('d F Y') }}</p>@endif
        <span class="mt-4 inline-block text-sm font-bold text-honda">Lihat detail promo</span>
    </div>
</a>
