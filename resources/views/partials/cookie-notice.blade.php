{{-- Pemberitahuan cookie minimalis: strip tepat di atas footer. Bersifat informasi ("Mengerti"), muncul sampai diklik. --}}
<div x-data="cookieNotice" x-show="show" x-cloak class="border-t border-zinc-200 bg-zinc-100" role="region" aria-label="Pemberitahuan cookie">
    <div class="mx-auto flex max-w-7xl flex-col items-start gap-3 px-4 py-3 text-xs text-zinc-600 sm:flex-row sm:items-center sm:justify-between md:text-sm">
        <p>Website ini menggunakan cookies untuk pengalaman pengguna yang lebih baik. Pelajari selengkapnya di <a href="{{ route('page.show', 'kebijakan-privasi') }}" class="font-semibold text-honda underline underline-offset-2 hover:text-honda-dark">Kebijakan Privasi</a>.</p>
        <button type="button" @click="accept()" class="shrink-0 rounded-lg bg-zinc-900 px-5 py-2 text-xs font-bold text-white transition hover:bg-honda">Mengerti</button>
    </div>
</div>
