@extends('admin.layout')
@section('title', 'Pengaturan Website')
@section('content')
<form method="POST" enctype="multipart/form-data" action="{{ route('admin.settings.update') }}" class="max-w-3xl space-y-6">
    @csrf @method('PUT')
    @foreach($groups as $group => $fields)
        <div class="space-y-4 rounded-2xl bg-white p-6 shadow-sm">
            <h2 class="font-bold">{{ $group }}</h2>
            @foreach($fields as $key => [$label, $type])
                <label class="block text-sm font-semibold">{{ $label }}
                    @if($type === 'textarea')
                        <textarea name="{{ $key }}" rows="3" class="mt-1 w-full rounded-xl border border-zinc-300 px-4 py-2.5 focus:border-honda focus:ring-honda">{{ old($key, $settings[$key] ?? '') }}</textarea>
                    @elseif($type === 'image')
                        @if(! empty($settings[$key]))<img src="{{ asset('storage/'.$settings[$key]) }}" class="mt-2 h-16 rounded" alt="">@endif
                        <input type="file" name="{{ $key }}" accept="image/*" class="mt-2 block w-full text-sm">
                    @else
                        <input type="text" name="{{ $key }}" value="{{ old($key, $settings[$key] ?? '') }}" class="mt-1 w-full rounded-xl border border-zinc-300 px-4 py-2.5 focus:border-honda focus:ring-honda">
                    @endif
                </label>
            @endforeach
        </div>
    @endforeach
    <button class="rounded-xl bg-honda px-8 py-3 text-sm font-bold text-white hover:bg-honda-dark">Simpan pengaturan</button>
</form>
@endsection
