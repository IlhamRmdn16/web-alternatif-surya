@extends('layouts.frontend')

@section('title', 'Promo Motor Honda Garut Terbaru | Dealer Motor Honda Garut')
@section('meta_description', 'Promo motor Honda Garut terbaru: potongan harga, cicilan ringan, dan penawaran spesial dari dealer motor Honda Garut resmi.')

@section('content')
<section class="mx-auto max-w-7xl px-4 pt-10">
    <h1 class="text-2xl font-extrabold text-zinc-900 md:text-4xl">Promo Motor Honda Garut</h1>
    <div class="mt-3 h-1 w-14 rounded bg-honda"></div>
    @if($promos->isEmpty())
        <p class="mt-8 rounded-2xl border border-dashed border-zinc-300 p-8 text-center text-sm text-zinc-500">Belum ada promo aktif. Hubungi kami via WhatsApp untuk info terbaru.</p>
    @else
        <div class="mt-8 grid gap-4 md:grid-cols-3 md:gap-6">
            @foreach($promos as $p) @include('partials.promo-card') @endforeach
        </div>
        <div class="mt-8">{{ $promos->links() }}</div>
    @endif
</section>
@endsection
