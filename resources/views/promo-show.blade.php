@extends('layouts.frontend')

@section('title', $promo->title.' | Promo Motor Honda Garut')
@php
    $descHtml = \App\Support\Html::fromPlain($promo->description);
    $descText = trim(preg_replace('/\s+/', ' ', strip_tags(str_replace(['</p>', '<br>', '<br />', '</li>'], ' ', $descHtml))));
@endphp
@section('meta_description', Str::limit($descText ?: 'Promo '.$promo->title.' dari dealer motor Honda Garut resmi.', 155))
@if($promo->image)@section('og_image', asset('storage/'.$promo->image))@endif

@section('content')
<article class="mx-auto w-full max-w-7xl px-4 pt-10 md:px-8">
    <nav class="text-xs text-zinc-500"><a href="{{ route('promos.index') }}" class="hover:text-honda">Promo</a> / <span class="text-zinc-800">{{ $promo->title }}</span></nav>
    <h1 class="mt-4 text-2xl font-extrabold text-zinc-900 md:text-4xl">{{ $promo->title }}</h1>
    @if($promo->start_date || $promo->end_date)
        <p class="mt-2 text-sm text-zinc-500">Periode:
            {{ $promo->start_date?->translatedFormat('d F Y') ?? 'sekarang' }} - {{ $promo->end_date?->translatedFormat('d F Y') ?? 'sampai pemberitahuan lebih lanjut' }}</p>
    @endif

    <div class="page-content mt-6 flow-root">
        @if($promo->image)
            <img src="{{ asset('storage/'.$promo->image) }}" alt="{{ $promo->title }}"
                 class="mb-4 h-auto w-full rounded-2xl object-contain md:float-left md:mr-8 md:w-5/12 md:max-w-lg lg:w-2/5">
        @endif
        {!! $descHtml !!}
    </div>

    <button type="button" x-data @click="$dispatch('open-wa', {{ Js::from(['topic' => 'Promo: '.$promo->title]) }})" class="mt-8 inline-block rounded-xl bg-green-500 px-8 py-3 text-sm font-bold text-white hover:bg-green-600">Tanya promo ini ke Sales Counter</button>
</article>
@endsection