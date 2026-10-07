{{-- Pilihan jumlah data per halaman. Param: $default. Dipakai di dalam <form method="GET">. --}}
<label class="flex items-center gap-2 text-sm text-zinc-600">Tampilkan
    <select name="per_page" onchange="this.form.submit()" class="rounded-xl border border-zinc-300 px-3 py-2 text-sm">
        @foreach([10, 20, 50, 100] as $n)
            <option value="{{ $n }}" @selected((int) request('per_page', $default ?? 10) === $n)>{{ $n }}</option>
        @endforeach
    </select>
    data/halaman
</label>
