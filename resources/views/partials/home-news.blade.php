{{-- Berita dan Tips di beranda. Desktop: 3 berita sebaris. HP/tablet: 2 berita sebaris (berita ke-3 disembunyikan). --}}
@if($posts->isNotEmpty())
<section class="mx-auto max-w-7xl px-4 pt-16">
    <div class="flex items-end justify-between gap-3">
        <div>
            <h2 class="text-xl font-extrabold text-zinc-900 md:text-2xl">Berita dan Tips</h2>
            <p class="mt-1 text-sm text-zinc-500">Informasi dan panduan seputar motor Honda.</p>
        </div>
        <a href="{{ route('news.index') }}" class="shrink-0 text-sm font-bold text-honda hover:text-zinc-900">Semua berita</a>
    </div>
    <div class="mt-6 grid grid-cols-2 gap-3 lg:grid-cols-3 lg:gap-6">
        @foreach($posts as $post)
            <div class="flex {{ $loop->iteration > 2 ? 'hidden lg:flex' : '' }}">@include('partials.post-card', ['compact' => true])</div>
        @endforeach
    </div>
</section>
@endif
