@extends('admin.layout')
@section('title', 'Halaman')
@section('content')
<p class="mb-4 text-sm text-zinc-600">Atur isi halaman statis website dengan editor seperti MS Word. Isi awal sudah disiapkan sebagai contoh, <b>mohon sesuaikan dengan kondisi dealer Anda</b> (terutama syarat kredit dan kebijakan privasi).</p>
<div class="overflow-x-auto rounded-2xl bg-white shadow-sm">
    <table class="w-full text-sm">
        <thead class="bg-zinc-50 text-left text-xs text-zinc-500"><tr><th class="px-4 py-3">Halaman</th><th class="px-4 py-3">Alamat</th><th class="px-4 py-3">Diperbarui</th><th class="px-4 py-3 text-right">Aksi</th></tr></thead>
        <tbody class="divide-y divide-zinc-100">
        @foreach($pages as $p)
            <tr>
                <td class="px-4 py-3 font-semibold">{{ $p->title }}</td>
                <td class="px-4 py-3 text-zinc-500">/{{ $p->slug }}</td>
                <td class="px-4 py-3 text-zinc-500">{{ $p->updated_at->format('d M Y H:i') }}</td>
                <td class="whitespace-nowrap px-4 py-3 text-right">
                    <a href="{{ route('page.show', $p->slug) }}" target="_blank" class="font-bold text-zinc-600">Lihat</a>
                    <a href="{{ route('admin.pages.edit', $p) }}" class="ml-3 font-bold text-blue-600">Edit</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<p class="mt-4 text-xs text-zinc-500">Halaman <b>Kontak & Lokasi</b> diatur lewat menu <b>Sales Counter</b> (daftar sales) dan <b>Pengaturan</b> (telepon, email, jam operasional).</p>
@endsection
