@extends('admin.layout')
@section('title', $c['title'])
@section('actions')<a href="{{ route($c['route'].'.create') }}" class="rounded-xl bg-honda px-5 py-2.5 text-sm font-bold text-white hover:bg-honda-dark">+ Tambah</a>@endsection
@section('content')
<div class="overflow-x-auto rounded-2xl bg-white shadow-sm">
    <table class="w-full text-sm">
        <thead class="bg-zinc-50 text-left text-xs text-zinc-500"><tr>
            @foreach($c['columns'] as $col)<th class="px-4 py-3">{{ $c['fields'][$col]['label'] ?? ucfirst($col) }}</th>@endforeach
            <th class="px-4 py-3 text-right">Aksi</th>
        </tr></thead>
        <tbody class="divide-y divide-zinc-100">
        @forelse($items as $item)
            <tr>
                @foreach($c['columns'] as $col)
                    @php($t = $c['fields'][$col]['type'] ?? 'text')
                    <td class="px-4 py-3">
                        @if($t === 'image')
                            @if($item->$col)<img src="{{ asset('storage/'.$item->$col) }}" class="h-12 w-20 rounded object-cover" alt="">@endif
                        @elseif($t === 'checkbox')
                            <span class="rounded-full px-2 py-0.5 text-xs font-bold {{ $item->$col ? 'bg-green-100 text-green-700' : 'bg-zinc-200 text-zinc-600' }}">{{ $item->$col ? 'Aktif' : 'Nonaktif' }}</span>
                        @elseif($t === 'date')
                            {{ $item->$col?->format('d M Y') }}
                        @else
                            {{ Str::limit($item->$col, 70) }}
                        @endif
                    </td>
                @endforeach
                <td class="whitespace-nowrap px-4 py-3 text-right">
                    <a href="{{ route($c['route'].'.edit', $item) }}" class="font-bold text-blue-600">Edit</a>
                    <form method="POST" action="{{ route($c['route'].'.destroy', $item) }}" class="ml-3 inline" onsubmit="return confirm('Hapus data ini?')">@csrf @method('DELETE')<button class="font-bold text-red-600">Hapus</button></form>
                </td>
            </tr>
        @empty
            <tr><td colspan="{{ count($c['columns']) + 1 }}" class="px-4 py-10 text-center text-zinc-500">Belum ada data.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-5">{{ $items->links() }}</div>
@endsection
