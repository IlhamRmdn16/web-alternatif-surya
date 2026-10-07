@extends('layouts.frontend')

@section('title', ($page->meta_title ?: $page->title).' | DealerMotorHondaGarut.id')
@section('meta_description', $page->meta_description)

@push('head')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org', '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Beranda', 'item' => route('home')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => $page->title],
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')
{{-- Hero: latar putih, judul di kiri, foto (latar transparan) di kanan. Foto diatur di Admin > Halaman. --}}
<section class="relative isolate overflow-hidden border-b border-zinc-100 bg-white">
    <div class="pointer-events-none absolute -left-20 -top-20 -z-10 h-64 w-64 rounded-full bg-red-50 blur-2xl md:h-80 md:w-80"></div>

    <div class="mx-auto grid max-w-7xl items-center gap-6 px-4 py-10 md:py-14 {{ $page->banner ? 'lg:grid-cols-2 lg:gap-10' : '' }}">
        <div>
            <nav class="text-xs text-zinc-500" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-honda">Beranda</a> / <span class="text-zinc-800">{{ $page->title }}</span>
            </nav>
            <h1 class="mt-4 font-display text-3xl font-bold leading-tight tracking-tight text-zinc-900 sm:text-4xl lg:text-5xl">{{ $page->title }}</h1>
            <div class="mt-5 h-1 w-16 rounded bg-honda"></div>
        </div>

        @if($page->banner)
            <div class="relative mx-auto w-full max-w-sm sm:max-w-md lg:max-w-none">
                <div class="pointer-events-none absolute left-1/2 top-1/2 aspect-square h-[82%] -translate-x-1/2 -translate-y-1/2 rounded-full bg-gradient-to-br from-red-100 via-rose-50 to-orange-100"></div>
                <div class="pointer-events-none absolute right-3 top-1 h-10 w-10 rounded-full bg-honda/20 lg:h-14 lg:w-14"></div>
                <img src="{{ asset('storage/'.$page->banner) }}" alt="{{ $page->title }}" class="relative z-10 mx-auto h-[240px] w-full object-contain drop-shadow-xl sm:h-[300px] lg:h-[360px]">
            </div>
        @endif
    </div>
</section>

<article class="mx-auto max-w-7xl px-4 pt-10">
    <div class="page-content">{!! $page->content !!}</div>

    <p class="mt-10 text-xs text-zinc-400">Terakhir diperbarui: {{ $page->updated_at->translatedFormat('d F Y') }}</p>

    @if($page->slug !== 'kebijakan-privasi')
        <div class="mt-10 rounded-2xl bg-zinc-900 p-6 text-white md:p-8">
            <p class="text-lg font-extrabold">Masih ada pertanyaan?</p>
            <p class="mt-1 text-sm text-zinc-400">Tim sales counter kami siap membantu Anda.</p>
            <div class="mt-5 flex flex-wrap gap-3">
                <button type="button" x-data @click="$dispatch('open-wa')" class="rounded-xl bg-green-500 px-6 py-3 text-sm font-bold text-white hover:bg-green-600">Chat Sales Counter</button>
                <a href="{{ route('contact') }}" class="rounded-xl border border-zinc-600 px-6 py-3 text-sm font-bold hover:border-white">Kontak & Lokasi</a>
            </div>
        </div>
    @endif
</article>
@endsection
