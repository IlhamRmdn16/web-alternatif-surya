@extends('admin.layout')
@section('title', 'Detail Prospek')
@section('actions')<a href="{{ route('admin.prospects.index') }}" class="text-sm font-bold text-honda">&larr; Kembali</a>@endsection
@section('content')
<div class="grid max-w-4xl gap-6 md:grid-cols-2">
    <div class="rounded-2xl bg-white p-6 shadow-sm">
        <dl class="space-y-3 text-sm">
            @foreach([
                'Sumber' => $prospect->source === 'form' ? 'Form konsultasi' : 'Klik WhatsApp',
                'Tanggal' => $prospect->created_at->format('d M Y H:i'),
                'Nama' => $prospect->name,
                'Alamat' => $prospect->address ?: '-',
                'No. HP' => $prospect->phone ?: '-',
                'Keperluan' => $prospect->purpose ? $prospect->purpose_label : '-',
                'Motor' => $prospect->motor?->name ?: '-',
                'Tipe' => $prospect->variant_name ?: '-',
                'Warna' => $prospect->color_name ?: '-',
            ] as $k => $v)
                <div class="flex gap-3"><dt class="w-24 shrink-0 text-zinc-500">{{ $k }}</dt><dd class="font-semibold">{{ $v }}</dd></div>
            @endforeach
            @if($prospect->purpose === 'simulasi')
                <div class="flex gap-3"><dt class="w-24 shrink-0 text-zinc-500">DP</dt><dd class="font-semibold">Rp {{ number_format($prospect->dp, 0, ',', '.') }}</dd></div>
                <div class="flex gap-3"><dt class="w-24 shrink-0 text-zinc-500">Tenor</dt><dd class="font-semibold">{{ $prospect->tenor }} bulan</dd></div>
            @endif
            @if($prospect->source === 'whatsapp' && $prospect->message)
                <div class="flex gap-3"><dt class="w-24 shrink-0 text-zinc-500">Dari halaman</dt><dd class="break-all font-semibold">{{ $prospect->message }}</dd></div>
            @endif
        </dl>
        @if($prospect->phone)
            <a target="_blank" rel="noopener" href="https://wa.me/{{ \App\Models\Setting::normalizeNumber($prospect->phone) }}?text={{ rawurlencode('Halo '.$prospect->name.', kami dari Dealer Motor Honda Garut menindaklanjuti permintaan Anda.') }}" class="mt-5 inline-block rounded-xl bg-green-500 px-5 py-2.5 text-sm font-bold text-white hover:bg-green-600">Hubungi via WhatsApp</a>
        @endif
    </div>

    <form method="POST" action="{{ route('admin.prospects.update', $prospect) }}" class="rounded-2xl bg-white p-6 shadow-sm">
        @csrf @method('PUT')
        <label class="block text-sm font-semibold">Status
            <select name="status" class="mt-1 w-full rounded-xl border border-zinc-300 px-4 py-2.5">
                @foreach(\App\Models\Prospect::STATUSES as $k => $l)<option value="{{ $k }}" @selected($prospect->status === $k)>{{ $l }}</option>@endforeach
            </select></label>
        <label class="mt-4 block text-sm font-semibold">Catatan internal
            <textarea name="notes" rows="5" class="mt-1 w-full rounded-xl border border-zinc-300 px-4 py-2.5">{{ old('notes', $prospect->notes) }}</textarea></label>
        <button class="mt-4 rounded-xl bg-honda px-6 py-2.5 text-sm font-bold text-white hover:bg-honda-dark">Simpan</button>
    </form>
</div>
<form method="POST" action="{{ route('admin.prospects.destroy', $prospect) }}" class="mt-6" onsubmit="return confirm('Hapus prospek ini?')">@csrf @method('DELETE')<button class="text-sm font-bold text-red-600">Hapus prospek</button></form>
@endsection
