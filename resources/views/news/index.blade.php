@extends('layouts.frontend')

@section('title', 'Berita & Tips Motor Honda Garut | DealerMotorHondaGarut.id')
@section('meta_description', 'Berita, promo, dan tips seputar motor Honda dari dealer motor Honda Garut: info terbaru, panduan memilih motor, dan perawatan.')

@section('content')
<section class="mx-auto max-w-7xl px-4 pt-10">
    <h1 class="text-2xl font-extrabold text-zinc-900 md:text-4xl">Berita & Tips Motor Honda</h1>
    <div class="mt-3 h-1 w-14 rounded bg-honda"></div>
    <p class="mt-4 text-sm leading-relaxed text-zinc-600 md:text-base">Informasi terbaru dari dealer, panduan memilih motor, dan tips perawatan untuk pengguna motor Honda.</p>

    @if($posts->isEmpty())
        <p class="mt-8 rounded-2xl border border-dashed border-zinc-300 p-8 text-center text-sm text-zinc-500">Belum ada berita.</p>
    @else
        <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3 lg:gap-6">
            @foreach($posts as $post) @include('partials.post-card') @endforeach
        </div>
        <div class="mt-8">{{ $posts->links('partials.pagination') }}</div>
    @endif
</section>
@endsection
