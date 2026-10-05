{{-- Param: $post --}}
<a href="{{ route('news.show', $post) }}" class="group flex flex-col overflow-hidden rounded-2xl border border-zinc-200 bg-white transition hover:border-honda hover:shadow-lg">
    @if($post->cover)
        <img src="{{ asset('storage/'.$post->cover) }}" alt="{{ $post->title }}" loading="lazy" class="aspect-[16/10] w-full object-cover transition duration-300 group-hover:scale-[1.02]">
    @else
        <div class="flex aspect-[16/10] w-full items-center justify-center bg-zinc-100 text-xs text-zinc-400">Berita</div>
    @endif
    <div class="flex flex-1 flex-col p-5">
        <p class="text-xs font-semibold text-zinc-400">{{ ($post->published_at ?? $post->created_at)->translatedFormat('d F Y') }}</p>
        <h3 class="mt-1 line-clamp-2 text-base font-bold leading-snug text-zinc-900 group-hover:text-honda md:text-lg">{{ $post->title }}</h3>
        <p class="mt-2 line-clamp-3 text-sm leading-relaxed text-zinc-600">{{ $post->summary }}</p>
        <span class="mt-4 text-sm font-bold text-honda">Baca selengkapnya</span>
    </div>
</a>
