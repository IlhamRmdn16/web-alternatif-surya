@extends('layouts.frontend')

@section('title', 'Kontak & Lokasi Dealer Motor Honda Garut | Dealer Motor Honda Garut')
@section('meta_description', 'Alamat, jam operasional, peta lokasi, dan nomor WhatsApp admin serta call center resmi dealer motor Honda Garut di Jl. Papandayan No.112, Garut Kota.')

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
<section class="mx-auto max-w-7xl px-4 pt-10 md:pt-12">
    <nav class="text-xs text-zinc-500" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="hover:text-honda">Beranda</a> / <span class="text-zinc-800">Kontak & Lokasi</span>
    </nav>
    <h1 class="mt-4 text-xl font-extrabold text-zinc-900 md:text-3xl">Kontak & Lokasi Dealer Motor Honda Garut</h1>
    <div class="mt-3 h-1 w-14 rounded bg-honda"></div>
    <p class="mt-4 text-sm leading-relaxed text-zinc-600">Kunjungi showroom kami atau hubungi admin kami untuk menanyakan stok, harga, promo, dan simulasi kredit motor Honda.</p>

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
                    <li>Admin Website (Surya Wijaya): <button type="button" x-data @click="$dispatch('open-wa')" class="font-semibold text-honda hover:underline">{{ \App\Support\Helpers::adminDisplay() }}</button>
                        <span class="block text-xs text-zinc-400">Klik untuk chat WhatsApp Admin Surya Wijaya</span></li>
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
</section>
@endsection
