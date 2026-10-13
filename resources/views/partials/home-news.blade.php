{{-- Berita dan Tips di beranda. HP/tablet: semua berita bisa digeser ke kanan. Desktop: 3 berita sebaris. --}}
@if($posts->isNotEmpty())
<section class="mx-auto max-w-7xl px-4 pt-16">
    <div class="flex items-end justify-between gap-3">
        <div>
            <h2 class="text-xl font-extrabold text-zinc-900 md:text-2xl">Berita dan Tips</h2>
            <p class="mt-1 text-sm text-zinc-500">Informasi dan panduan seputar motor Honda.</p>
        </div>
        <a href="{{ route('news.index') }}" class="shrink-0 text-sm font-bold text-honda hover:text-zinc-900">Semua berita</a>
    </div>

    <p class="mt-3 text-xs text-zinc-400 lg:hidden">Geser ke kanan untuk melihat berita lainnya &rarr;</p>
    <div class="no-scrollbar -mx-4 mt-3 flex snap-x snap-mandatory gap-3 overflow-x-auto scroll-pl-4 px-4 pb-2 lg:mx-0 lg:mt-6 lg:grid lg:grid-cols-3 lg:gap-6 lg:overflow-visible lg:px-0 lg:pb-0">
        @foreach($posts as $post)
            <div class="w-[72%] shrink-0 snap-start sm:w-[46%] lg:w-auto [&>a]:h-full">@include('partials.post-card')</div>
        @endforeach
    </div>
</section>
@endif
