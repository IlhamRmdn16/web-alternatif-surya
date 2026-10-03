@extends('admin.layout')
@section('title', ($item->exists ? 'Edit ' : 'Tambah ').$c['title'])
@section('content')
@php($hideErrors = true)
<form method="POST" enctype="multipart/form-data" action="{{ $item->exists ? route($c['route'].'.update', $item) : route($c['route'].'.store') }}" class="max-w-2xl space-y-5 rounded-2xl bg-white p-6 shadow-sm">
    @csrf @if($item->exists) @method('PUT') @endif
    @foreach($c['fields'] as $name => $f)
        @php($t = $f['type'] ?? 'text')
        @php($val = old($name, $item->exists ? ($item->$name instanceof \Carbon\Carbon ? $item->$name->format('Y-m-d') : $item->$name) : ($f['default'] ?? ($t === 'number' ? 0 : null))))
        <div>
            @if($t === 'checkbox')
                <label class="flex items-center gap-2 text-sm font-semibold">
                    <input type="hidden" name="{{ $name }}" value="0">
                    <input type="checkbox" name="{{ $name }}" value="1" class="rounded border-zinc-300 text-honda focus:ring-honda" @checked($item->exists ? old($name, $item->$name) : old($name, true))>
                    {{ $f['label'] }}
                </label>
            @else
                <label class="block text-sm font-semibold">{{ $f['label'] }}
                    @if($t === 'textarea')
                        <textarea name="{{ $name }}" rows="5" class="mt-1 w-full rounded-xl border border-zinc-300 px-4 py-2.5 focus:border-honda focus:ring-honda">{{ $val }}</textarea>
                    @elseif($t === 'image')
                        @if($item->exists && $item->$name)<img src="{{ asset('storage/'.$item->$name) }}" class="mt-2 h-24 rounded-lg object-cover" alt="">@endif
                        <input type="file" name="{{ $name }}" accept="image/*" class="mt-2 block w-full text-sm">
                    @else
                        <input type="{{ $t === 'number' ? 'number' : ($t === 'date' ? 'date' : 'text') }}" name="{{ $name }}" value="{{ $val }}" class="mt-1 w-full rounded-xl border border-zinc-300 px-4 py-2.5 focus:border-honda focus:ring-honda">
                    @endif
                </label>
            @endif
            @error($name)<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
    @endforeach
    <div class="flex gap-3 pt-2">
        <button class="rounded-xl bg-honda px-6 py-2.5 text-sm font-bold text-white hover:bg-honda-dark">Simpan</button>
        <a href="{{ route($c['route'].'.index') }}" class="rounded-xl border border-zinc-300 px-6 py-2.5 text-sm font-semibold">Batal</a>
    </div>
</form>
@endsection
