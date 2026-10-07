@extends('admin.layout')
@section('title', 'Prospek')
@section('content')
<form method="GET" class="mb-5 flex flex-wrap gap-3">
    <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama / no. HP" class="rounded-xl border border-zinc-300 px-4 py-2 text-sm">
    <select name="source" class="rounded-xl border border-zinc-300 px-4 py-2 text-sm">
        <option value="">Semua sumber</option>
        <option value="form" @selected(request('source') === 'form')>Form konsultasi</option>
        <option value="whatsapp" @selected(request('source') === 'whatsapp')>Klik WhatsApp</option>
    </select>
    <select name="status" class="rounded-xl border border-zinc-300 px-4 py-2 text-sm">
        <option value="">Semua status</option>
        @foreach(\App\Models\Prospect::STATUSES as $k => $l)<option value="{{ $k }}" @selected(request('status') === $k)>{{ $l }}</option>@endforeach
    </select>
    <select name="sales" class="rounded-xl border border-zinc-300 px-4 py-2 text-sm">
        <option value="">Semua sales / call center</option>
        @foreach($salesNames as $n)<option value="{{ $n }}" @selected(request('sales') === $n)>{{ $n }}</option>@endforeach
    </select>
    @include('admin.partials.per-page', ['default' => 20])
    <button class="rounded-xl bg-zinc-900 px-5 py-2 text-sm font-semibold text-white">Filter</button>
    <a href="{{ route('admin.prospects.export', request()->query()) }}" class="rounded-xl bg-green-600 px-5 py-2 text-sm font-semibold text-white hover:bg-green-700">Export CSV</a>
</form>

<div class="overflow-x-auto rounded-2xl bg-white shadow-sm">
    <table class="w-full text-sm">
        <thead class="bg-zinc-50 text-left text-xs text-zinc-500"><tr>
            <th class="px-4 py-3">Tanggal</th><th class="px-4 py-3">Sumber</th><th class="px-4 py-3">Nama</th><th class="px-4 py-3">Menghubungi</th><th class="px-4 py-3">No. HP</th><th class="px-4 py-3">Keperluan</th><th class="px-4 py-3">Motor</th><th class="px-4 py-3">Status</th><th class="px-4 py-3"></th></tr></thead>
        <tbody class="divide-y divide-zinc-100">
        @forelse($prospects as $p)
            <tr class="{{ $p->status === 'baru' ? 'bg-red-50/40' : '' }}">
                <td class="whitespace-nowrap px-4 py-3 text-zinc-500">{{ $p->created_at->format('d M Y H:i') }}</td>
                <td class="px-4 py-3"><span class="rounded-full px-2 py-0.5 text-xs font-bold {{ $p->source === 'form' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">{{ $p->source === 'form' ? 'Form' : 'WhatsApp' }}</span></td>
                <td class="px-4 py-3 font-semibold">{{ $p->name }}</td>
                <td class="px-4 py-3 font-semibold text-zinc-700">{{ $p->sales_name ?: '-' }}</td>
                <td class="px-4 py-3">{{ $p->phone ?: '-' }}</td>
                <td class="px-4 py-3">{{ $p->purpose ? $p->purpose_label : '-' }}@if($p->purpose === 'simulasi')<br><span class="text-xs text-zinc-500">DP Rp {{ number_format($p->dp, 0, ',', '.') }} / {{ $p->tenor }} bln</span>@endif</td>
                <td class="px-4 py-3">{{ $p->motor?->name ?: '-' }}@if($p->variant_name)<br><span class="text-xs text-zinc-500">{{ $p->variant_name }}{{ $p->color_name ? ' - '.$p->color_name : '' }}</span>@endif</td>
                <td class="px-4 py-3">{{ \App\Models\Prospect::STATUSES[$p->status] ?? $p->status }}</td>
                <td class="px-4 py-3"><a href="{{ route('admin.prospects.show', $p) }}" class="font-bold text-honda">Detail</a></td>
            </tr>
        @empty
            <tr><td colspan="9" class="px-4 py-10 text-center text-zinc-500">Belum ada prospek.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-5">{{ $prospects->links('partials.pagination') }}</div>
@endsection
