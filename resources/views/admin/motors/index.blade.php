@extends('admin.layout')
@section('title', 'Motor & Harga')
@section('actions')<a href="{{ route('admin.motors.create') }}" class="rounded-xl bg-honda px-5 py-2.5 text-sm font-bold text-white hover:bg-honda-dark">+ Tambah motor</a>@endsection
@section('content')
<p class="mb-4 text-sm text-zinc-600">Setiap baris adalah satu tipe. Tipe dengan <b>nama motor yang sama</b> otomatis digabung menjadi satu seri (mis. "Beat Series") di website.</p>
<form method="GET" class="mb-5 flex flex-wrap items-center gap-3">
    <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama / tipe motor" class="rounded-xl border border-zinc-300 px-4 py-2 text-sm">
    <select name="category" onchange="this.form.submit()" class="rounded-xl border border-zinc-300 px-4 py-2 text-sm">
        <option value="">Semua jenis</option>
        @foreach($categories as $c)<option value="{{ $c->id }}" @selected(request('category') == $c->id)>{{ $c->name }}</option>@endforeach
    </select>
    <select name="status" onchange="this.form.submit()" class="rounded-xl border border-zinc-300 px-4 py-2 text-sm">
        <option value="">Semua status</option>
        <option value="aktif" @selected(request('status') === 'aktif')>Aktif</option>
        <option value="nonaktif" @selected(request('status') === 'nonaktif')>Nonaktif</option>
    </select>
    <select name="home" onchange="this.form.submit()" class="rounded-xl border border-zinc-300 px-4 py-2 text-sm">
        <option value="">Semua (beranda)</option>
        <option value="1" @selected(request('home') === '1')>Tampil di beranda</option>
    </select>
    @include('admin.partials.per-page', ['default' => 10])
    <button class="rounded-xl bg-zinc-900 px-5 py-2 text-sm font-semibold text-white">Filter</button>
    @if(request()->hasAny(['q', 'category', 'status', 'home', 'per_page']))<a href="{{ route('admin.motors.index') }}" class="text-sm font-semibold text-zinc-500 hover:text-honda">Reset</a>@endif
</form>

<div class="overflow-x-auto rounded-2xl bg-white shadow-sm">
    <table class="w-full text-sm">
        <thead class="bg-zinc-50 text-left text-xs text-zinc-500"><tr>
            <th class="px-4 py-3">Motor</th><th class="px-4 py-3">Tipe</th><th class="px-4 py-3">Jenis</th><th class="px-4 py-3">Harga OTR</th><th class="px-4 py-3">Diskon cash</th><th class="px-4 py-3">Harga cash</th><th class="px-4 py-3">Beranda</th><th class="px-4 py-3">Status</th><th class="px-4 py-3 text-right">Aksi</th></tr></thead>
        <tbody class="divide-y divide-zinc-100">
        @forelse($motors as $m)
            <tr>
                <td class="px-4 py-3"><div class="flex items-center gap-3">@if($m->image)<img src="{{ asset('storage/'.$m->image) }}" class="h-12 w-14 rounded object-contain" alt="">@endif<span class="font-semibold">{{ $m->name }}</span></div></td>
                <td class="px-4 py-3 font-semibold">{{ $m->variant }}</td>
                <td class="px-4 py-3">{{ $m->category->name }}</td>
                <td class="px-4 py-3">{{ \App\Support\Helpers::rp($m->price) }}@if($m->has_varied_prices)<span class="mt-1 block w-fit rounded bg-amber-100 px-1.5 text-[10px] font-bold text-amber-700">ada harga warna khusus</span>@endif</td>
                <td class="px-4 py-3">{{ $m->cash_discount > 0 ? \App\Support\Helpers::rp($m->cash_discount) : '-' }}</td>
                <td class="px-4 py-3 font-bold text-honda">{{ $m->cash_discount > 0 ? \App\Support\Helpers::rp($m->cash_price) : '-' }}</td>
                <td class="px-4 py-3">{!! $m->show_on_home ? '<span class="rounded-full bg-blue-100 px-2 py-0.5 text-xs font-bold text-blue-700">Tampil</span>' : '-' !!}</td>
                <td class="px-4 py-3"><span class="rounded-full px-2 py-0.5 text-xs font-bold {{ $m->is_active ? 'bg-green-100 text-green-700' : 'bg-zinc-200 text-zinc-600' }}">{{ $m->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                <td class="whitespace-nowrap px-4 py-3 text-right">
                    <a href="{{ route('admin.motors.edit', $m) }}" class="font-bold text-blue-600">Edit</a>
                    <a href="{{ route('admin.motors.create', ['series' => $m->name]) }}" class="ml-3 font-bold text-green-700">+ Tipe lain</a>
                    <form method="POST" action="{{ route('admin.motors.destroy', $m) }}" class="ml-3 inline" onsubmit="return confirm('Hapus tipe ini beserta warnanya?')">@csrf @method('DELETE')<button class="font-bold text-red-600">Hapus</button></form>
                </td>
            </tr>
        @empty
            <tr><td colspan="9" class="px-4 py-10 text-center text-zinc-500">{{ request()->hasAny(['q', 'category', 'status', 'home']) ? 'Tidak ada motor yang cocok dengan filter.' : 'Belum ada motor. Klik "Tambah motor".' }}</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-5">{{ $motors->links('partials.pagination') }}</div>
@endsection
