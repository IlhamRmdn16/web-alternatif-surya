@extends('layouts.frontend')

@php
    $imgUrl = $motor->image ? asset('storage/'.$motor->image) : null;
    $rp = fn ($n) => \App\Support\Helpers::rp($n);
    $base = $motor->offerFor();
    $first = $motor->offerFor($motor->colors->first());   // harga warna yang tampil pertama
    $low = $motor->lowest_offer;
    $varied = $motor->has_varied_prices;
    $desc = 'Harga '.$motor->full_name.' di Garut '.($low['price'] > 0 ? ($varied ? 'mulai ' : '').$rp($low['price']).' (OTR)' : '').'.'
        .($low['discount'] > 0 ? ' Diskon cash '.$rp($low['discount']).', jadi '.$rp($low['cash']).'.' : '')
        .' Lihat warna, tipe lain, dan simulasi kredit di dealer motor Honda Garut resmi.';
    $colorsJs = $motor->colors->map(function ($c) use ($motor) {
        $o = $motor->offerFor($c);
        return ['name' => $c->name, 'hex' => $c->hex, 'image' => $c->image ? asset('storage/'.$c->image) : null,
                'price' => $o['price'], 'discount' => $o['discount'], 'custom' => $o['custom']];
    })->values();
    $cashes = $motor->offers->filter(fn ($o) => $o['price'] > 0)->pluck('cash');
@endphp

@section('title', $motor->full_name.' Garut: Harga OTR & Diskon Cash | DealerMotorHondaGarut.id')
@section('meta_description', Str::limit($desc, 160))
@section('og_image', $imgUrl)
@section('og_type', 'product')

@push('head')
<script type="application/ld+json">
{!! json_encode(array_filter([
    '@context' => 'https://schema.org',
    '@type' => 'Product',
    'name' => $motor->full_name,
    'image' => $imgUrl,
    'description' => Str::limit(strip_tags($motor->description ?: $desc), 300),
    'brand' => ['@type' => 'Brand', 'name' => 'Honda'],
    'category' => $motor->category->name,
    'offers' => $cashes->isEmpty() ? null : ($varied
        ? ['@type' => 'AggregateOffer', 'priceCurrency' => 'IDR', 'lowPrice' => $cashes->min(), 'highPrice' => $cashes->max(), 'offerCount' => $cashes->count()]
        : ['@type' => 'Offer', 'priceCurrency' => 'IDR', 'price' => $cashes->first(), 'url' => route('motor.show', $motor),
           'seller' => ['@type' => 'Organization', 'name' => 'CV. Surya Wijaya Sejahtera']]),
]), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org', '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Beranda', 'item' => route('home')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Pricelist', 'item' => route('pricelist')],
        ['@type' => 'ListItem', 'position' => 3, 'name' => $motor->full_name],
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')
<div class="mx-auto max-w-7xl px-4 pt-8"
     x-data="motorPage({{ Js::from([
        'colors' => $colorsJs, 'base' => ['price' => $base['price'], 'discount' => $base['discount']],
        'image' => $imgUrl, 'url' => route('prospect.store'), 'motorId' => $motor->id,
     ]) }})"
     @keydown.escape.window="open = false">

    <nav class="text-xs text-zinc-500" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="hover:text-honda">Beranda</a> /
        <a href="{{ route('pricelist') }}" class="hover:text-honda">Pricelist</a> /
        <span class="text-zinc-800">{{ $motor->full_name }}</span>
    </nav>

    <div class="mt-6 grid gap-8 lg:grid-cols-2 lg:gap-14">
        <div>
            <div class="flex aspect-square items-center justify-center rounded-3xl bg-zinc-50 p-6 md:aspect-[4/3]">
                <img :src="image" alt="{{ $motor->full_name }} Garut" class="max-h-full max-w-full object-contain" x-show="image" @if(! $imgUrl && ! optional($motor->colors->first())->image) style="display:none" @endif>
                <p x-show="!image" class="text-sm text-zinc-400" @if($imgUrl) style="display:none" @endif>Foto belum tersedia</p>
            </div>
            @if($motor->colors->isNotEmpty())
            <div class="mt-5">
                <p class="text-sm font-bold text-zinc-900">Pilihan warna: <span class="font-medium text-zinc-600" x-text="colors[ci]?.name">{{ $motor->colors->first()->name }}</span></p>
                <div class="mt-3 flex flex-wrap gap-3">
                    <template x-for="(c, i) in colors" :key="i">
                        <button type="button" @click="ci = i" :title="c.name" :aria-label="c.name"
                                :class="ci === i ? 'ring-2 ring-honda ring-offset-2' : 'ring-1 ring-zinc-300'"
                                class="h-9 w-9 rounded-full transition" :style="'background:' + c.hex"></button>
                    </template>
                </div>
            </div>
            @endif
        </div>

        <div>
            <span class="rounded-full bg-honda px-3 py-1 text-xs font-semibold text-white">{{ $motor->category->name }}</span>
            <h1 class="mt-3 text-2xl font-extrabold text-zinc-900 md:text-4xl">{{ $motor->full_name }}</h1>

            @if($siblings->count() > 1)
                <p class="mt-6 text-sm font-bold text-zinc-900">Pilih tipe {{ $motor->name }}</p>
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach($siblings as $t)
                        <a href="{{ route('motor.show', $t) }}" @if($t->id === $motor->id) aria-current="page" @endif
                           class="rounded-xl border-2 px-4 py-2 text-sm font-bold transition {{ $t->id === $motor->id ? 'border-honda bg-red-50 text-honda' : 'border-zinc-300 text-zinc-700 hover:border-zinc-500' }}">{{ $t->variant }}</a>
                    @endforeach
                </div>
            @endif

            {{-- Harga: berubah otomatis sesuai warna yang dipilih --}}
            <div class="mt-6 rounded-2xl bg-zinc-50 p-5">
                <p class="text-xs text-zinc-500">Harga OTR Garut tipe {{ $motor->variant }}<span x-show="colors[ci]?.name"> - warna <span x-text="colors[ci]?.name">{{ $motor->colors->first()?->name }}</span></span></p>
                <div class="mt-1">
                    <span x-show="offer.price <= 0" class="text-2xl font-extrabold text-honda" @if($first['price'] > 0) style="display:none" @endif>Hubungi kami</span>
                    <div x-show="offer.price > 0" class="flex flex-wrap items-baseline gap-x-3" @if($first['price'] <= 0) style="display:none" @endif>
                        <s x-show="offer.discount > 0" x-text="rp(offer.price)" class="text-lg font-semibold text-zinc-400" @if($first['discount'] <= 0) style="display:none" @endif>{{ $rp($first['price']) }}</s>
                        <span x-text="rp(offer.cash)" class="text-3xl font-extrabold text-honda">{{ $rp($first['cash']) }}</span>
                    </div>
                </div>
                <p x-show="offer.discount > 0" class="mt-3 inline-block rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-700" @if($first['discount'] <= 0) style="display:none" @endif>
                    Diskon pembelian cash <span x-text="rp(offer.discount)">{{ $rp($first['discount']) }}</span></p>
                <p x-show="offer.discount > 0" class="mt-2 text-xs text-zinc-500" @if($first['discount'] <= 0) style="display:none" @endif>Harga coret adalah harga OTR normal. Harga setelah diskon berlaku untuk pembelian cash.</p>
                @if($varied)<p class="mt-2 text-xs text-zinc-500">Harga dapat berbeda untuk warna tertentu. Pilih warna untuk melihat harganya.</p>@endif
                <p class="mt-2 text-xs text-zinc-500">Harga dapat berubah sewaktu-waktu. Konfirmasi harga & stok ke sales kami.</p>
            </div>

            <button type="button" @click="open = true; done = false" class="mt-6 w-full rounded-xl bg-honda py-4 text-base font-bold text-white shadow-lg shadow-red-200 transition hover:bg-honda-dark md:w-auto md:px-10">Konsultasi Pembelian</button>

            @if($motor->description)
                <div class="mt-8">
                    <h2 class="text-lg font-bold text-zinc-900">Tentang {{ $motor->full_name }}</h2>
                    <p class="mt-2 whitespace-pre-line text-sm leading-relaxed text-zinc-600">{{ $motor->description }}</p>
                </div>
            @endif

            @if($varied)
                <div class="mt-8">
                    <h2 class="text-lg font-bold text-zinc-900">Harga {{ $motor->full_name }} per warna</h2>
                    <div class="mt-3 overflow-x-auto rounded-xl border border-zinc-200">
                        <table class="w-full text-sm">
                            <thead class="bg-zinc-50 text-left text-xs text-zinc-500"><tr><th class="px-4 py-2.5">Warna</th><th class="px-4 py-2.5 text-right">Harga OTR</th><th class="px-4 py-2.5 text-right">Harga cash</th></tr></thead>
                            <tbody class="divide-y divide-zinc-100">
                                @foreach($motor->colors as $c)
                                    @php($o = $motor->offerFor($c))
                                    <tr>
                                        <td class="px-4 py-3 font-medium"><span class="mr-2 inline-block h-3 w-3 rounded-full ring-1 ring-zinc-300" style="background:{{ $c->hex }}"></span>{{ $c->name }}</td>
                                        <td class="px-4 py-3 text-right {{ $o['discount'] > 0 ? 'text-zinc-400 line-through' : 'font-bold text-honda' }}">{{ $o['price'] > 0 ? $rp($o['price']) : '-' }}</td>
                                        <td class="px-4 py-3 text-right font-bold text-honda">{{ $o['discount'] > 0 ? $rp($o['cash']) : '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <div class="mt-8">
                <h2 class="text-lg font-bold text-zinc-900">Daftar harga {{ $motor->display_name }} Garut</h2>
                <div class="mt-3 overflow-x-auto rounded-xl border border-zinc-200">
                    <table class="w-full text-sm">
                        <thead class="bg-zinc-50 text-left text-xs text-zinc-500"><tr><th class="px-4 py-2.5">Tipe</th><th class="px-4 py-2.5 text-right">Harga OTR</th><th class="px-4 py-2.5 text-right">Harga cash</th></tr></thead>
                        <tbody class="divide-y divide-zinc-100">
                            @foreach($siblings as $t)
                                @php($o = $t->lowest_offer)
                                <tr class="{{ $t->id === $motor->id ? 'bg-red-50/50' : '' }}">
                                    <td class="px-4 py-3 font-medium"><a href="{{ route('motor.show', $t) }}" class="hover:text-honda">{{ $t->variant }}</a></td>
                                    <td class="px-4 py-3 text-right {{ $o['discount'] > 0 ? 'text-zinc-400 line-through' : 'font-bold text-honda' }}">{{ $t->has_varied_prices ? 'mulai ' : '' }}{{ $o['price'] > 0 ? $rp($o['price']) : '-' }}</td>
                                    <td class="px-4 py-3 text-right font-bold text-honda">{{ $o['discount'] > 0 ? $rp($o['cash']) : '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal konsultasi --}}
    <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-end justify-center overflow-y-auto bg-black/50 p-4 sm:items-center">
        <div @click.outside="open = false" class="my-auto w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
            <template x-if="done">
                <div class="text-center">
                    <p class="text-lg font-bold text-zinc-900">Data terkirim</p>
                    <p class="mt-2 text-sm text-zinc-600">Terima kasih! Tim kami akan segera menghubungi Anda.</p>
                    <button @click="open = false" class="mt-5 w-full rounded-xl bg-zinc-900 py-3 text-sm font-semibold text-white">Tutup</button>
                </div>
            </template>
            <form x-show="!done" @submit.prevent="submit">
                <h2 class="text-lg font-bold text-zinc-900">Konsultasi pembelian</h2>
                <p class="mt-1 text-sm text-zinc-500">{{ $motor->full_name }}<span x-show="colors[ci]?.name"> - <span x-text="colors[ci]?.name"></span></span></p>

                <label class="mt-4 block text-sm font-semibold">Nama
                    <input x-model="form.name" type="text" required class="mt-1 w-full rounded-xl border border-zinc-300 px-4 py-2.5"></label>
                <label class="mt-3 block text-sm font-semibold">Alamat
                    <input x-model="form.address" type="text" required class="mt-1 w-full rounded-xl border border-zinc-300 px-4 py-2.5"></label>
                <label class="mt-3 block text-sm font-semibold">No. HP / WhatsApp
                    <input x-model="form.phone" type="tel" inputmode="tel" required class="mt-1 w-full rounded-xl border border-zinc-300 px-4 py-2.5"></label>
                <label class="mt-3 block text-sm font-semibold">Keperluan
                    <select x-model="form.purpose" required class="mt-1 w-full rounded-xl border border-zinc-300 px-4 py-2.5">
                        <option value="">Pilih keperluan</option>
                        @foreach($purposes as $code => $label)<option value="{{ $code }}">{{ $label }}</option>@endforeach
                    </select></label>

                <div x-show="form.purpose === 'simulasi'" x-cloak class="mt-3 space-y-3 rounded-xl bg-zinc-50 p-4">
                    <label class="block text-sm font-semibold">Nominal DP (Rp)
                        <input @input="formatDp($event)" type="text" inputmode="numeric" placeholder="mis. 2.000.000" class="mt-1 w-full rounded-xl border border-zinc-300 px-4 py-2.5"></label>
                    <label class="block text-sm font-semibold">Tenor
                        <select x-model="form.tenor" class="mt-1 w-full rounded-xl border border-zinc-300 px-4 py-2.5">
                            <option value="">Pilih tenor</option>
                            @foreach($tenors as $t)<option value="{{ $t }}">{{ $t }} Bulan</option>@endforeach
                        </select></label>
                </div>

                <p x-show="error" x-text="error" class="mt-3 text-sm text-red-600"></p>
                <div class="mt-5 flex gap-3">
                    <button type="button" @click="open = false" class="flex-1 rounded-xl border border-zinc-300 py-3 text-sm font-semibold">Batal</button>
                    <button type="submit" :disabled="loading" class="flex-1 rounded-xl bg-honda py-3 text-sm font-semibold text-white hover:bg-honda-dark disabled:opacity-60">
                        <span x-show="!loading">Kirim</span><span x-show="loading">Mengirim...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if($related->isNotEmpty())
        <section class="pt-16">
            <h2 class="text-xl font-extrabold text-zinc-900">Motor {{ $motor->category->name }} Honda lainnya</h2>
            <div class="mt-6 grid grid-cols-2 gap-3 md:grid-cols-4 md:gap-6">
                @foreach($related as $s) @include('partials.series-card') @endforeach
            </div>
        </section>
    @endif
</div>
@endsection
