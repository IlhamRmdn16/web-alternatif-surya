@extends('admin.layout')
@section('title', 'Dashboard')
@section('content')
<div class="grid grid-cols-2 gap-4 lg:grid-cols-5">
    @foreach($stats as $label => $n)
        <div class="rounded-2xl bg-white p-5 shadow-sm"><p class="text-xs text-zinc-500">{{ $label }}</p><p class="mt-1 text-3xl font-extrabold text-zinc-900">{{ $n }}</p></div>
    @endforeach
</div>
<div class="mt-8 rounded-2xl bg-white p-5 shadow-sm">
    <div class="flex items-center justify-between"><h2 class="font-bold">Prospek terbaru</h2><a href="{{ route('admin.prospects.index') }}" class="text-sm font-bold text-honda">Lihat semua</a></div>
    <div class="mt-3 overflow-x-auto"><table class="w-full text-sm">
        @forelse($latest as $p)
            <tr class="border-t border-zinc-100">
                <td class="py-2.5 pr-3 text-zinc-500">{{ $p->created_at->format('d M H:i') }}</td>
                <td class="pr-3"><span class="rounded-full px-2 py-0.5 text-xs font-bold {{ $p->source === 'form' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">{{ $p->source === 'form' ? 'Form' : 'WhatsApp' }}</span></td>
                <td class="pr-3 font-semibold">{{ $p->name }}</td>
                <td class="pr-3">{{ $p->motor?->name }}</td>
                <td><a href="{{ route('admin.prospects.show', $p) }}" class="font-bold text-honda">Detail</a></td>
            </tr>
        @empty
            <tr><td class="py-6 text-center text-zinc-500">Belum ada prospek.</td></tr>
        @endforelse
    </table></div>
</div>
@endsection
