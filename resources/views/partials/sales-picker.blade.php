{{-- Pilihan sales counter (foto + nama). Butuh scope Alpine dengan: sales (array) dan target (id terpilih). --}}
<div class="mt-4">
    <p class="text-sm font-semibold">Pilih sales counter yang ingin dihubungi</p>
    <p x-show="sales.length === 0" class="mt-2 rounded-xl bg-zinc-50 p-3 text-sm text-zinc-500">Sales counter belum tersedia. Silakan coba lagi nanti.</p>
    <div class="mt-2 max-h-64 space-y-2 overflow-y-auto pr-1" x-show="sales.length > 0">
        <template x-for="s in sales" :key="s.id">
            <button type="button" @click="target = s.id"
                    :class="target === s.id ? 'border-honda bg-red-50 ring-1 ring-honda' : 'border-zinc-200 hover:border-zinc-400'"
                    class="flex w-full items-center gap-3 rounded-xl border p-2.5 text-left transition">
                <img x-show="s.photo" :src="s.photo" :alt="s.name" class="h-11 w-11 shrink-0 rounded-full object-cover">
                <span x-show="!s.photo" class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-zinc-100 font-bold text-zinc-400" x-text="s.name.charAt(0).toUpperCase()"></span>
                <span class="min-w-0 flex-1">
                    <span class="block truncate text-sm font-bold text-zinc-900" x-text="s.name"></span>
                    <span class="block truncate text-xs text-zinc-500" x-text="s.position"></span>
                </span>
                <svg x-show="target === s.id" class="h-5 w-5 shrink-0 text-honda" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
            </button>
        </template>
    </div>
</div>
