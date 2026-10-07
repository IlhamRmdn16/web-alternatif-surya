{{-- Berita dan Tips: 4 berita terbaru. Dipasang tepat di bawah daftar motor di beranda. --}}
@if($posts->isNotEmpty())
<section class="mx-auto max-w-7xl px-4 pt-16">
    <div class="flex items-end justify-between gap-3">
        <div>
            <h2 class="text-xl font-extrabold text-zinc-900 md:text-2xl">Berita dan Tips</h2>
            <p class="mt-1 text-sm text-zinc-500">Informasi dan panduan seputar motor Honda.</p>
        </div>
        <a href="{{ route('news.index') }}" class="text-sm font-bold text-honda hover:text-zinc-900">Semua berita</a>
    </div>
    <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4 lg:gap-6">
        @foreach($posts as $post) @include('partials.post-card') @endforeach
    </div>
</section>
@endif
