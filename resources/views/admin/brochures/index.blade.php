@extends('admin.layout')
@section('title', 'Brosur & Daftar Harga (PDF)')
@section('actions')<a href="{{ route('admin.brochures.create') }}" class="rounded-xl bg-honda px-5 py-2.5 text-sm font-bold text-white hover:bg-honda-dark">+ Upload PDF</a>@endsection
@section('content')
<p class="mb-4 text-sm text-zinc-600">PDF yang diunggah di sini tampil di halaman <b>Daftar Harga</b> sebagai kartu "Brosur & Daftar Harga" dengan tombol <b>Lihat</b> dan <b>Unduh</b>. Cocok untuk daftar harga motor dan simulasi kredit.</p>
<div class="overflow-x-auto rounded-2xl bg-white shadow-sm">
    <table class="w-full text-sm">
        <thead class="bg-zinc-50 text-left text-xs text-zinc-500"><tr>
            <th class="px-4 py-3">Judul</th><th class="px-4 py-3">Ukuran</th><th class="px-4 py-3">Urutan</th><th class="px-4 py-3">Status</th><th class="px-4 py-3">Diperbarui</th><th class="px-4 py-3 text-right">Aksi</th></tr></thead>
        <tbody class="divide-y divide-zinc-100">
        @forelse($brochures as $b)
            <tr>
                <td class="px-4 py-3"><p class="font-semibold">{{ $b->title }}</p>@if($b->description)<p class="text-xs text-zinc-500">{{ Str::limit($b->description, 80) }}</p>@endif</td>
                <td class="px-4 py-3 text-zinc-600">{{ $b->size_label ?: '-' }}</td>
                <td class="px-4 py-3 text-zinc-600">{{ $b->sort }}</td>
                <td class="px-4 py-3"><span class="rounded-full px-2 py-0.5 text-xs font-bold {{ $b->is_active ? 'bg-green-100 text-green-700' : 'bg-zinc-200 text-zinc-600' }}">{{ $b->is_active ? 'Tampil' : 'Disembunyikan' }}</span></td>
                <td class="whitespace-nowrap px-4 py-3 text-zinc-500">{{ $b->updated_at->format('d M Y H:i') }}</td>
                <td class="whitespace-nowrap px-4 py-3 text-right">
                    <a href="{{ route('brochures.show', $b) }}" target="_blank" class="font-bold text-zinc-600">Lihat</a>
                    <a href="{{ route('admin.brochures.edit', $b) }}" class="ml-3 font-bold text-blue-600">Edit</a>
                    <form method="POST" action="{{ route('admin.brochures.destroy', $b) }}" class="ml-3 inline" onsubmit="return confirm('Hapus brosur ini?')">@csrf @method('DELETE')<button class="font-bold text-red-600">Hapus</button></form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="px-4 py-10 text-center text-zinc-500">Belum ada brosur. Klik "+ Upload PDF".</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
