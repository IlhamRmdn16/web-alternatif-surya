@extends('layouts.frontend')

@section('title', 'FAQ Beli Motor Honda di Garut: Kredit, Booking & Servis | DealerMotorHondaGarut.id')
@section('meta_description', 'Jawaban pertanyaan seputar pembelian motor Honda di dealer Garut: cara beli, kredit, DP, tenor, harga, dan layanan purna jual.')

@push('head')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org', '@type' => 'FAQPage',
    'mainEntity' => $faqs->map(fn ($f) => ['@type' => 'Question', 'name' => $f->question, 'acceptedAnswer' => ['@type' => 'Answer', 'text' => strip_tags($f->answer)]])->values(),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')
<section class="mx-auto max-w-3xl px-4 pt-10">
    <h1 class="text-2xl font-extrabold text-zinc-900 md:text-4xl">Pertanyaan Seputar Beli Motor Honda di Garut</h1>
    <div class="mt-3 h-1 w-14 rounded bg-honda"></div>
    <div class="mt-8 space-y-3" x-data="{ open: 0 }">
        @forelse($faqs as $i => $f)
            <div class="rounded-xl border border-zinc-200">
                <button class="flex w-full items-center justify-between gap-4 px-5 py-4 text-left font-bold text-zinc-900" @click="open = open === {{ $i }} ? null : {{ $i }}" :aria-expanded="open === {{ $i }}">
                    {{ $f->question }}<span class="text-xl text-honda" x-text="open === {{ $i }} ? '−' : '+'"></span>
                </button>
                <div x-show="open === {{ $i }}" @if($i !== 0) x-cloak @endif class="whitespace-pre-line px-5 pb-5 text-sm leading-relaxed text-zinc-600">{{ $f->answer }}</div>
            </div>
        @empty
            <p class="rounded-2xl border border-dashed border-zinc-300 p-8 text-center text-sm text-zinc-500">FAQ belum tersedia.</p>
        @endforelse
    </div>
</section>
@endsection
