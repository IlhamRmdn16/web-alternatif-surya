{{-- Param: $s (hasil App\Support\Series::group) --}}
@php($c = $s->cheapest)
@php($o = $c->lowest_offer)
<a href="{{ $s->url }}" class="group flex flex-col overflow-hidden rounded-2xl border border-zinc-200 bg-white transition hover:border-honda hover:shadow-lg">
    <div class="relative aspect-square bg-zinc-50 p-5">
        <span class="absolute left-3 top-3 rounded-full bg-honda px-2.5 py-1 text-[11px] font-semibold text-white">{{ $s->category->name }}</span>
        @if($o['discount'] > 0)<span class="absolute right-3 top-3 rounded-full bg-green-600 px-2.5 py-1 text-[11px] font-semibold text-white">Diskon cash</span>@endif
        @if($s->image)
            <img src="{{ asset('storage/'.$s->image) }}" alt="{{ $c->display_name }} Garut - harga dan tipe" loading="lazy" class="h-full w-full object-contain transition duration-300 group-hover:scale-105">
        @else
            <div class="flex h-full items-center justify-center text-xs text-zinc-400">Foto belum tersedia</div>
        @endif
    </div>
    <div class="flex flex-1 flex-col p-4">
        <h3 class="text-base font-bold leading-snug text-zinc-900 md:text-lg">{{ $s->name }} Series</h3>
        <p class="mt-0.5 text-xs text-zinc-500">Tipe: {{ $s->types->pluck('variant')->take(3)->implode(', ') }}{{ $s->types->count() > 3 ? ', ...' : '' }}</p>
        <p class="mt-4 text-xs text-zinc-500">Harga OTR</p>
        @include('partials.price', ['price' => $o['price'], 'disc' => $o['discount']])
        <span class="mt-4 rounded-xl bg-zinc-900 py-2.5 text-center text-sm font-semibold text-white transition group-hover:bg-honda">Lihat tipe & harga</span>
    </div>
</a>
