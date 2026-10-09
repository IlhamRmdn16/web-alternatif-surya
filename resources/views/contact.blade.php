@extends('layouts.frontend')

@section('title', 'Kontak & Lokasi Dealer Motor Honda Garut | Dealer Motor Honda Garut')
@section('meta_description', 'Alamat, jam operasional, peta lokasi, dan nomor WhatsApp sales counter resmi dealer motor Honda Garut di Jl. Papandayan No.112, Garut Kota.')

@php
    $address = \App\Support\Helpers::DEALER_ADDRESS;
    $embed = trim($settings['map_embed'] ?? '');
    if (! Str::startsWith($embed, 'https://www.google.com/maps/embed')) {
        $embed = 'https://www.google.com/maps?q='.urlencode($address).'&output=embed';
    }
    $cc = \App\Models\Setting::normalizeNumber($settings['call_center'] ?? '');
    $ccDisplay = $cc === '' ? '' : (str_starts_with($cc, '62') ? '0'.substr($cc, 2) : $cc);
    $dirUrl = 'https://www.google.com/maps/dir/?api=1&destination='.urlencode($address);
    $hours = ! empty($settings['hours']) ? array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $settings['hours']))) : [];
@endphp

@push('head')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org', '@type' => 'ContactPage',
    'name' => 'Kontak & Lokasi Dealer Motor Honda Garut',
    'url' => route('contact'),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')
<section class="mx-auto max-w-7xl px-4 pt-10">
    <nav class="text-xs text-zinc-500" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="hover:text-honda">Beranda</a> / <span class="text-zinc-800">Kontak & Lokasi</span>
    </nav>
    <h1 class="mt-4 text-2xl font-extrabold text-zinc-900 md:text-4xl">Kontak & Lokasi Dealer Motor Honda Garut</h1>
    <div class="mt-3 h-1 w-14 rounded bg-honda"></div>
    <p class="mt-4 text-sm leading-relaxed text-zinc-600 md:text-base">Kunjungi showroom kami atau hubungi sales counter resmi untuk menanyakan stok, harga, promo, dan simulasi kredit motor Honda.</p>

    {{-- Info + peta --}}
    <div class="mt-8 grid gap-6 lg:grid-cols-5 lg:gap-8">
        <div class="space-y-5 rounded-2xl border border-zinc-200 bg-white p-6 lg:col-span-2">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-zinc-400">Alamat</p>
                <address class="mt-2 text-sm font-medium not-italic leading-relaxed text-zinc-800">CV. Surya Wijaya Sejahtera<br>{{ $address }}</address>
                <a href="{{ $dirUrl }}" target="_blank" rel="noopener" class="mt-3 inline-flex items-center gap-2 rounded-lg bg-honda px-4 py-2 text-xs font-bold text-white transition hover:bg-honda-dark">Petunjuk arah ke dealer</a>
            </div>

            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-zinc-400">Hubungi kami</p>
                <ul class="mt-2 space-y-2 text-sm">
                    @if($ccDisplay)
                        <li>Call Center: <button type="button" x-data @click="$dispatch('open-wa', { callCenter: true })" class="font-semibold text-honda hover:underline">{{ $ccDisplay }}</button>
                            <span class="block text-xs text-zinc-400">Klik untuk chat WhatsApp Call Center</span></li>
                    @endif
                    @if(! empty($settings['phone']))<li>Telepon: <a href="tel:{{ preg_replace('/[^0-9+]/', '', $settings['phone']) }}" class="font-semibold text-zinc-800 hover:text-honda">{{ $settings['phone'] }}</a></li>@endif
                    @if(! empty($settings['email']))<li>Email: <a href="mailto:{{ $settings['email'] }}" class="break-all font-semibold text-zinc-800 hover:text-honda">{{ $settings['email'] }}</a></li>@endif
                </ul>
            </div>

            @if($hours)
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-zinc-400">Jam operasional</p>
                <ul class="mt-2 divide-y divide-zinc-100 text-sm">
                    @foreach($hours as $line)
                        <li class="flex justify-between gap-3 py-2">
                            @if(preg_match('/^(.*?):\s*(\d.*)$/u', $line, $m))
                                <span class="text-zinc-600">{{ $m[1] }}</span><span class="font-semibold text-zinc-900">{{ $m[2] }}</span>
                            @else
                                <span class="text-zinc-600">{{ $line }}</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>

        <div class="overflow-hidden rounded-2xl border border-zinc-200 bg-zinc-100 lg:col-span-3">
            <iframe src="{{ $embed }}" title="Peta lokasi Dealer Motor Honda Garut" class="h-80 w-full md:h-full md:min-h-[420px]" style="border:0" loading="lazy" allowfullscreen referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>

    {{-- Sales counter --}}
    <div class="mt-14">
        <h2 class="text-xl font-extrabold text-zinc-900 md:text-2xl">Sales Counter Resmi</h2>
        <p class="mt-1 text-sm text-zinc-500">Untuk keamanan, hubungi hanya nomor resmi yang tercantum di halaman ini.</p>

        @if($sales->isEmpty())
            <p class="mt-6 rounded-2xl border border-dashed border-zinc-300 p-8 text-center text-sm text-zinc-500">Daftar sales counter belum tersedia.</p>
        @else
            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4 lg:gap-6">
                @foreach($sales as $s)
                    <div class="flex flex-col items-center rounded-2xl border border-zinc-200 bg-white p-6 text-center transition hover:border-honda hover:shadow-lg">
                        @if($s->photo)
                            <img src="{{ asset('storage/'.$s->photo) }}" alt="{{ $s->name }} - {{ $s->position }} Honda Garut" loading="lazy" class="h-24 w-24 rounded-full object-cover ring-4 ring-zinc-100">
                        @else
                            <div class="flex h-24 w-24 items-center justify-center rounded-full bg-zinc-100 text-3xl font-extrabold text-zinc-400 ring-4 ring-zinc-50">{{ Str::upper(Str::substr($s->name, 0, 1)) }}</div>
                        @endif
                        <p class="mt-4 text-base font-bold text-zinc-900">{{ $s->name }}</p>
                        <p class="text-xs font-semibold uppercase tracking-wide text-honda">{{ $s->position }}</p>
                        <p class="mt-2 text-sm text-zinc-500">{{ $s->display_phone }}</p>
                        <button type="button" @click="$dispatch('open-wa', {{ Js::from(['salesId' => $s->id]) }})"
                                class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-green-500 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-green-600" x-data>
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                            Chat WhatsApp
                        </button>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
