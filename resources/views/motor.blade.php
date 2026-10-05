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
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Daftar Harga', 'item' => route('pricelist')],
        ['@type' => 'ListItem', 'position' => 3, 'name' => $motor->full_name],
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')
<div class="mx-auto max-w-7xl px-4 pt-8"
     x-data="motorPage({{ Js::from([
        'colors' => $colorsJs, 'base' => ['price' => $base['price'], 'discount' => $base['discount']],
        'image' => $imgUrl, 'url' => route('prospect.store'), 'motorId' => $motor->id, 'sales' => $salesList,
     ]) }})"
     @keydown.escape.window="open = false">

    <nav class="text-xs text-zinc-500" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="hover:text-honda">Beranda</a> /
        <a href="{{ route('pricelist') }}" class="hover:text-honda">Daftar Harga</a> /
        <span class="text-zinc-800">{{ $motor->full_name }}</span>
    </nav>

    <div class="mt-6 grid items-start gap-8 lg:grid-cols-2 lg:gap-14">
        <div class="lg:sticky lg:top-28">
            <div class="flex aspect-square items-center justify-center rounded-3xl bg-zinc-50 p-6 md:aspect-[4/3]">
                <img :src="image" alt="{{ $motor->full_name }} Garut" class="max-h-full max-w-full object-contain" x-show="image" @if(! $imgUrl && ! optional($motor->colors->first())->image) style="display:none" @endif>
                <p x-show="!image" class="text-sm text-zinc-400" @if($imgUrl) style="display:none" @endif>Foto belum tersedia</p>
            </div>
            @if($motor->colors->isNotEmpty())
            <div class="mt-5" data-tour="colors">
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
                <div class="mt-3 flex flex-wrap gap-2" data-tour="types">
                    @foreach($siblings as $t)
                        <a href="{{ route('motor.show', $t) }}" @if($t->id === $motor->id) aria-current="page" @endif
                           class="rounded-xl border-2 px-4 py-2 text-sm font-bold transition {{ $t->id === $motor->id ? 'border-honda bg-red-50 text-honda' : 'border-zinc-300 text-zinc-700 hover:border-zinc-500' }}">{{ $t->variant }}</a>
                    @endforeach
                </div>
            @endif

            {{-- Harga: berubah otomatis sesuai warna. Harga OTR & harga cash SELALU tampil (tanpa diskon, nilainya sama). --}}
            <div class="mt-6 rounded-2xl bg-zinc-50 p-5" data-tour="price">
                <p class="text-xs text-zinc-500">Harga Garut tipe {{ $motor->variant }}<span x-show="colors[ci]?.name"> - warna <span x-text="colors[ci]?.name">{{ $motor->colors->first()?->name }}</span></span></p>

                <p x-show="offer.price <= 0" class="mt-2 text-2xl font-extrabold text-honda" @if($first['price'] > 0) style="display:none" @endif>Hubungi kami</p>

                <div x-show="offer.price > 0" class="mt-3 grid grid-cols-2 gap-4" @if($first['price'] <= 0) style="display:none" @endif>
                    <div>
                        <p class="text-xs font-semibold text-zinc-500">Harga OTR</p>
                        <p class="mt-1 text-lg font-bold text-zinc-900 md:text-xl"
                           x-text="rp(offer.price)"
                           :style="offer.discount > 0 ? 'text-decoration:line-through;color:#a1a1aa' : ''"
                           @if($first['discount'] > 0) style="text-decoration:line-through;color:#a1a1aa" @endif>{{ $rp($first['price']) }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-zinc-500">Harga cash</p>
                        <p class="mt-1 text-2xl font-extrabold text-honda md:text-3xl" x-text="rp(offer.cash)">{{ $rp($first['cash']) }}</p>
                    </div>
                </div>

                <p x-show="offer.discount > 0" class="mt-3 inline-block rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-700" @if($first['discount'] <= 0) style="display:none" @endif>
                    Diskon pembelian cash <span x-text="rp(offer.discount)">{{ $rp($first['discount']) }}</span></p>
                {{-- Catatan wajib: tampil di semua unit (dengan maupun tanpa diskon) --}}
                <div class="mt-4 flex gap-2.5 rounded-xl border border-amber-200 bg-amber-50 p-3 text-xs leading-relaxed text-amber-900">
                    <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    <p>Harga di atas belum termasuk diskon khusus. Ingin tanya diskon atau penawaran terbaik?
                        <button type="button" @click="$dispatch('open-wa')" class="font-bold underline underline-offset-2 hover:text-honda">Hubungi sales kami via WhatsApp</button>.</p>
                </div>
            </div>

            <button type="button" data-tour="consult" @click="open = true; done = false" class="mt-6 w-full rounded-xl bg-honda py-4 text-base font-bold text-white shadow-lg shadow-red-200 transition hover:bg-honda-dark md:w-auto md:px-10">Konsultasi Pembelian</button>

            @if($motor->description)
                <div class="mt-8">
                    <h2 class="text-lg font-bold text-zinc-900">Tentang {{ $motor->full_name }}</h2>
                    <p class="mt-2 whitespace-pre-line text-sm leading-relaxed text-zinc-600">{{ $motor->description }}</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Tabel harga: lebar penuh di bawah foto & info, supaya tidak ada ruang kosong di kolom foto --}}
    <section class="mt-12 grid gap-8 lg:gap-14 {{ $varied ? 'lg:grid-cols-2' : '' }}" aria-label="Daftar harga">
            @if($varied)
                <div>
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
                                        <td class="px-4 py-3 text-right font-bold text-honda">{{ $o['price'] > 0 ? $rp($o['cash']) : '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <div>
                <h2 class="text-lg font-bold text-zinc-900">Daftar harga {{ $motor->display_name }} Garut</h2>
                <div class="mt-3 overflow-x-auto rounded-xl border border-zinc-200">
                    <table class="w-full text-sm">
                        <thead class="bg-zinc-50 text-left text-xs text-zinc-500"><tr><th class="px-4 py-2.5">Tipe</th><th class="px-4 py-2.5 text-right">Harga OTR</th><th class="px-4 py-2.5 text-right">Harga cash</th></tr></thead>
                        <tbody class="divide-y divide-zinc-100">
                            @foreach($siblings as $t)
                                @php($o = $t->lowest_offer)
                                <tr class="{{ $t->id === $motor->id ? 'bg-red-50/50' : '' }}">
                                    <td class="px-4 py-3 font-medium"><a href="{{ route('motor.show', $t) }}" class="hover:text-honda">{{ $t->variant }}</a></td>
                                    <td class="px-4 py-3 text-right {{ $o['discount'] > 0 ? 'text-zinc-400 line-through' : 'font-bold text-honda' }}">{{ $o['price'] > 0 ? $rp($o['price']) : '-' }}</td>
                                    <td class="px-4 py-3 text-right font-bold text-honda">{{ $o['price'] > 0 ? $rp($o['cash']) : '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
    </section>

    {{-- Tombol petunjuk (?) di atas tombol WhatsApp. Klik untuk membuka tutorial halaman. --}}
    <button type="button" x-data @click="$dispatch('open-guide')" title="Petunjuk penggunaan halaman" aria-label="Petunjuk penggunaan halaman"
            class="fixed bottom-[88px] right-[26px] z-40 flex h-11 w-11 items-center justify-center rounded-full bg-white text-honda shadow-lg ring-1 ring-zinc-200 transition hover:scale-110 hover:bg-honda hover:text-white md:bottom-[108px] md:right-[42px]">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
    </button>

    {{-- Petunjuk penggunaan halaman: otomatis tampil sekali saja (lihat motorGuide di app.js) --}}
    <div x-data="motorGuide" @open-guide.window="start(false)" x-cloak>
        <div x-show="active" x-transition.opacity class="fixed inset-x-4 bottom-4 z-[70] mx-auto max-w-md rounded-2xl bg-zinc-900 p-5 text-white shadow-2xl sm:bottom-6" role="dialog" aria-live="polite" aria-label="Petunjuk halaman">
            <div class="flex items-start justify-between gap-3">
                <p class="text-xs font-bold uppercase tracking-widest text-red-400" x-text="'Petunjuk ' + (i + 1) + ' dari ' + list.length"></p>
                <button type="button" @click="finish()" class="text-xs font-semibold text-zinc-400 hover:text-white">Lewati</button>
            </div>
            <p class="mt-2 text-base font-bold" x-text="step.title"></p>
            <p class="mt-1 text-sm leading-relaxed text-zinc-300" x-text="step.text"></p>
            <div class="mt-4 flex items-center justify-between gap-3">
                <div class="flex gap-1.5">
                    <template x-for="(s, n) in list" :key="n"><span class="h-1.5 w-5 rounded-full" :class="n === i ? 'bg-red-500' : 'bg-zinc-600'"></span></template>
                </div>
                <div class="flex gap-2">
                    <button type="button" x-show="i > 0" @click="prev()" class="rounded-lg border border-zinc-600 px-4 py-2 text-xs font-bold hover:border-white">Kembali</button>
                    <button type="button" @click="next()" class="rounded-lg bg-honda px-4 py-2 text-xs font-bold hover:bg-honda-dark" x-text="i < list.length - 1 ? 'Lanjut' : 'Mengerti'"></button>
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
                    <p class="mt-2 text-sm text-zinc-600">Terima kasih! Data Anda sudah kami terima dan WhatsApp sales counter dibuka di tab baru. Jika tidak terbuka, klik tombol di bawah.</p>
                    <a x-show="waUrl" :href="waUrl" target="_blank" rel="noopener" class="mt-5 block w-full rounded-xl bg-green-500 py-3 text-sm font-semibold text-white hover:bg-green-600">Buka WhatsApp</a>
                    <button @click="open = false" class="mt-3 w-full rounded-xl border border-zinc-300 py-3 text-sm font-semibold">Tutup</button>
                </div>
            </template>
            <form x-show="!done" @submit.prevent="submit">
                <h2 class="text-lg font-bold text-zinc-900">Konsultasi pembelian</h2>
                <p class="mt-1 text-sm text-zinc-500">{{ $motor->full_name }}<span x-show="colors[ci]?.name"> - <span x-text="colors[ci]?.name"></span></span></p>

                @include('partials.sales-picker')

                <label class="mt-4 block text-sm font-semibold">Nama
                    <input x-model="form.name" type="text" required class="mt-1 w-full rounded-xl border border-zinc-300 px-4 py-2.5"></label>
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

                <p class="mt-3 text-xs text-zinc-500">Setelah dikirim, Anda akan diarahkan ke WhatsApp sales counter pilihan Anda dengan pesan yang sudah terisi. Dengan mengirim, Anda menyetujui <a href="{{ route('page.show', 'kebijakan-privasi') }}" target="_blank" class="font-semibold text-honda underline">Kebijakan Privasi</a> kami.</p>
                <p x-show="error" x-text="error" class="mt-3 text-sm text-red-600"></p>
                <div class="mt-5 flex gap-3">
                    <button type="button" @click="open = false" class="flex-1 rounded-xl border border-zinc-300 py-3 text-sm font-semibold">Batal</button>
                    <button type="submit" :disabled="loading" class="flex-1 rounded-xl bg-honda py-3 text-sm font-semibold text-white hover:bg-honda-dark disabled:opacity-60">
                        <span x-show="!loading">Kirim via WhatsApp</span><span x-show="loading">Mengirim...</span>
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
