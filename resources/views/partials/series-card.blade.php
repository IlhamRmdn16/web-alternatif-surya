{{-- Param: $s. Dua bentuk:
     - seri (App\Support\Series::group): kartu "Beat Series" berisi beberapa tipe
     - tipe tunggal ($s->single = true; beranda & rekomendasi halaman motor): kartu satu tipe berlabel "<Nama> Series",
       lengkap dengan tipe, titik pilihan warna, serta foto dan harga tipe itu sendiri --}}
@php($single = $s->single ?? false)
@php($c = $s->cheapest)
@php($o = $c->lowest_offer)
<a href="{{ $s->url }}" class="group flex flex-col overflow-hidden rounded-2xl border border-zinc-200 bg-white transition hover:border-honda hover:shadow-lg">
    <div class="relative aspect-square bg-zinc-50 p-5">
        <span class="absolute left-3 top-3 rounded-full bg-honda px-2.5 py-1 text-[11px] font-semibold text-white">{{ $s->category->name }}</span>
        @if($o['discount'] > 0)<span class="absolute right-3 top-3 rounded-full bg-green-600 px-2.5 py-1 text-[11px] font-semibold text-white">Diskon cash</span>@endif
        @if($s->image)
            <img src="{{ asset('storage/'.$s->image) }}" alt="{{ $single ? $c->full_name : $c->display_name }} Garut - harga dan tipe" loading="lazy" class="h-full w-full object-contain transition duration-300 group-hover:scale-105">
        @else
            <div class="flex h-full items-center justify-center text-xs text-zinc-400">Foto belum tersedia</div>
        @endif
    </div>
    <div class="flex flex-1 flex-col p-4">
        @if($single)
            <h3 class="text-base font-bold leading-snug text-zinc-900 md:text-lg">{{ $c->name }} Series</h3>
            <p class="mt-0.5 text-xs font-semibold text-zinc-500">Tipe: {{ $c->variant }}</p>
            @php($dots = $c->colors->filter(fn ($col) => $col->hex)->values())
            @if($dots->isNotEmpty())
                <div class="mt-2 flex items-center gap-1.5" aria-label="Pilihan warna: {{ $dots->pluck('name')->implode(', ') }}">
                    @foreach($dots->take(6) as $col)
                        <span class="h-3.5 w-3.5 rounded-full ring-1 ring-zinc-300" style="background: {{ $col->hex }}" title="{{ $col->name }}"></span>
                    @endforeach
                    @if($dots->count() > 6)<span class="text-[11px] font-semibold text-zinc-400">+{{ $dots->count() - 6 }}</span>@endif
                </div>
            @endif
            <p class="mt-3 text-xs text-zinc-500">{{ $c->has_varied_prices ? 'Harga OTR mulai' : 'Harga OTR' }}</p>
        @else
            <h3 class="text-base font-bold leading-snug text-zinc-900 md:text-lg">{{ $s->name }} Series</h3>
            <p class="mt-0.5 text-xs text-zinc-500">{{ $s->types->count() }} tipe: {{ $s->types->pluck('variant')->take(3)->implode(', ') }}{{ $s->types->count() > 3 ? ', ...' : '' }}</p>
            <p class="mt-4 text-xs text-zinc-500">Harga OTR mulai</p>
        @endif
        @include('partials.price', ['price' => $o['price'], 'disc' => $o['discount']])
        <span class="mt-4 rounded-xl bg-zinc-900 py-2.5 text-center text-sm font-semibold text-white transition group-hover:bg-honda">{{ $single ? 'Lihat detail' : 'Lihat tipe & harga' }}</span>
    </div>
</a>
