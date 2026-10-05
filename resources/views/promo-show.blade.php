@extends('layouts.frontend')

@section('title', $promo->title.' | Promo Motor Honda Garut')
@section('meta_description', Str::limit(strip_tags($promo->description ?: 'Promo '.$promo->title.' dari dealer motor Honda Garut resmi.'), 155))
@if($promo->image)@section('og_image', asset('storage/'.$promo->image))@endif

@section('content')
<article class="mx-auto max-w-3xl px-4 pt-10">
    <nav class="text-xs text-zinc-500"><a href="{{ route('promos.index') }}" class="hover:text-honda">Promo</a> / <span class="text-zinc-800">{{ $promo->title }}</span></nav>
    <h1 class="mt-4 text-2xl font-extrabold text-zinc-900 md:text-4xl">{{ $promo->title }}</h1>
    @if($promo->start_date || $promo->end_date)
        <p class="mt-2 text-sm text-zinc-500">Periode:
            {{ $promo->start_date?->translatedFormat('d F Y') ?? 'sekarang' }} - {{ $promo->end_date?->translatedFormat('d F Y') ?? 'sampai pemberitahuan lebih lanjut' }}</p>
    @endif
    @if($promo->image)<img src="{{ asset('storage/'.$promo->image) }}" alt="{{ $promo->title }}" class="mt-6 w-full rounded-2xl">@endif
    @if($promo->description)<div class="mt-6 whitespace-pre-line text-sm leading-relaxed text-zinc-700 md:text-base">{{ $promo->description }}</div>@endif
    <button type="button" x-data @click="$dispatch('open-wa', {{ Js::from(['topic' => 'Promo: '.$promo->title]) }})" class="mt-8 inline-block rounded-xl bg-green-500 px-8 py-3 text-sm font-bold text-white hover:bg-green-600">Tanya promo ini ke Sales Counter</button>
</article>
@endsection
