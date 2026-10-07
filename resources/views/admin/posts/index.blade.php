@extends('admin.layout')
@section('title', 'Berita')
@section('actions')<a href="{{ route('admin.posts.create') }}" class="rounded-xl bg-honda px-5 py-2.5 text-sm font-bold text-white hover:bg-honda-dark">+ Tulis berita</a>@endsection
@section('content')
<div class="overflow-x-auto rounded-2xl bg-white shadow-sm">
    <table class="w-full text-sm">
        <thead class="bg-zinc-50 text-left text-xs text-zinc-500"><tr>
            <th class="px-4 py-3">Berita</th><th class="px-4 py-3">Status</th><th class="px-4 py-3">Tanggal terbit</th><th class="px-4 py-3 text-right">Aksi</th></tr></thead>
        <tbody class="divide-y divide-zinc-100">
        @forelse($posts as $post)
            <tr>
                <td class="px-4 py-3"><div class="flex items-center gap-3">
                    @if($post->cover)<img src="{{ asset('storage/'.$post->cover) }}" class="h-12 w-20 rounded object-cover" alt="">@endif
                    <span class="font-semibold">{{ Str::limit($post->title, 70) }}</span></div></td>
                <td class="px-4 py-3">
                    @if($post->is_live)<span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-bold text-green-700">Terbit</span>
                    @elseif($post->is_published)<span class="rounded-full bg-amber-100 px-2 py-0.5 text-xs font-bold text-amber-700">Terjadwal</span>
                    @else<span class="rounded-full bg-zinc-200 px-2 py-0.5 text-xs font-bold text-zinc-600">Draft</span>@endif
                </td>
                <td class="px-4 py-3 text-zinc-500">{{ $post->published_at?->format('d M Y H:i') ?? '-' }}</td>
                <td class="whitespace-nowrap px-4 py-3 text-right">
                    @if($post->is_live)<a href="{{ route('news.show', $post) }}" target="_blank" class="font-bold text-zinc-600">Lihat</a>@endif
                    <a href="{{ route('admin.posts.edit', $post) }}" class="ml-3 font-bold text-blue-600">Edit</a>
                    <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" class="ml-3 inline" onsubmit="return confirm('Hapus berita ini?')">@csrf @method('DELETE')<button class="font-bold text-red-600">Hapus</button></form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="px-4 py-10 text-center text-zinc-500">Belum ada berita. Klik "Tulis berita".</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-5">{{ $posts->links('partials.pagination') }}</div>
@endsection
