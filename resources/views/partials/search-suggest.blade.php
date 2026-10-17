{{-- Daftar saran pencarian. Dipakai di dalam <label class="relative"> pada form dengan x-data="siteSearch(...)" --}}
<div x-show="open && results.length" x-cloak class="absolute inset-x-0 top-full z-50 mt-2 overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-xl">
    <template x-for="(r, i) in results" :key="r.url">
        <a :href="r.url" :class="active === i ? 'bg-red-50' : 'hover:bg-zinc-50'" class="flex items-center gap-3 px-3 py-2.5">
            <img x-show="r.image" :src="r.image" alt="" class="h-10 w-10 shrink-0 rounded-lg bg-zinc-50 object-contain">
            <span class="min-w-0 flex-1">
                <span class="block truncate text-sm font-bold text-zinc-900" x-text="r.name"></span>
                <span class="block truncate text-xs text-zinc-500" x-text="r.category + ' · ' + r.price"></span>
            </span>
        </a>
    </template>
    <a :href="'{{ route('pricelist') }}?q=' + encodeURIComponent(q)" class="block border-t border-zinc-100 px-3 py-2 text-center text-xs font-bold text-honda hover:bg-zinc-50">Lihat semua hasil di Daftar Harga</a>
</div>
<p x-show="open && !results.length && !loading" x-cloak class="absolute inset-x-0 top-full z-50 mt-2 rounded-xl border border-zinc-200 bg-white px-4 py-3 text-sm text-zinc-500 shadow-xl">Motor tidak ditemukan. Coba kata lain, mis. "beat".</p>
