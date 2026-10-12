{{-- Param: $post, $compact (opsional: true = lebih ringkas di HP, dipakai di beranda) --}}
@php($compact = $compact ?? false)
<a href="{{ route('news.show', $post) }}" class="group flex h-full w-full flex-col overflow-hidden rounded-2xl border border-zinc-200 bg-white transition hover:border-honda hover:shadow-lg">
    @if($post->cover)
        <img src="{{ asset('storage/'.$post->cover) }}" alt="{{ $post->title }}" loading="lazy" class="aspect-[16/10] w-full object-cover transition duration-300 group-hover:scale-[1.02]">
    @else
        <div class="flex aspect-[16/10] w-full items-center justify-center bg-zinc-100 text-xs text-zinc-400">Berita</div>
    @endif
    <div class="flex flex-1 flex-col {{ $compact ? 'p-3 sm:p-5' : 'p-5' }}">
        <p class="text-xs font-semibold text-zinc-400">{{ ($post->published_at ?? $post->created_at)->translatedFormat('d F Y') }}</p>
        <h3 class="mt-1 line-clamp-2 font-bold leading-snug text-zinc-900 group-hover:text-honda {{ $compact ? 'text-sm sm:text-base md:text-lg' : 'text-base md:text-lg' }}">{{ $post->title }}</h3>
        <p class="mt-2 line-clamp-3 text-sm leading-relaxed text-zinc-600 {{ $compact ? 'hidden sm:block' : '' }}">{{ $post->summary }}</p>
        <span class="mt-auto pt-3 font-bold text-honda {{ $compact ? 'text-xs sm:text-sm' : 'text-sm' }}">Baca selengkapnya</span>
    </div>
</a>
