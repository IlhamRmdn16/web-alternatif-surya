{{-- Param: $path (mis. hero.title), $label, $type (text|textarea), $rows --}}
@php
    $name = 'about['.str_replace('.', '][', $path).']';
    $val = old('about.'.$path, data_get($about, $path));
    $type = $type ?? 'text';
    $rows = $rows ?? 4;
@endphp
<label class="block text-sm font-semibold">{{ $label }}
    @if($type === 'textarea')
        <textarea name="{{ $name }}" rows="{{ $rows }}" class="mt-1 w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm font-normal">{{ $val }}</textarea>
    @else
        <input type="text" name="{{ $name }}" value="{{ $val }}" class="mt-1 w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm font-normal">
    @endif
</label>
