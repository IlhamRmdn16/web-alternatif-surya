{{-- Persetujuan cookie (semua halaman).
     Banner: kiri bawah di desktop (kanan bawah dipakai tombol WhatsApp), selebar layar di HP.
     Pengaturan: jendela berisi kategori cookie yang bisa dicentang.
     ID Google Analytics dibaca dari config('services.google_analytics.id') (lihat catatan pemasangan). --}}
<div x-data="cookieNotice('{{ config('services.google_analytics.id') }}')" @open-cookie.window="openSettings()" @keydown.escape.window="settings && close()">

    {{-- BANNER --}}
    <div x-show="show" x-cloak
         x-transition:enter="transition duration-500 ease-out" x-transition:enter-start="translate-y-6 opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
         x-transition:leave="transition duration-300 ease-in" x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="translate-y-6 opacity-0"
         class="fixed inset-x-3 bottom-3 z-[60] md:inset-x-auto md:bottom-6 md:left-6 md:w-[24rem]"
         role="dialog" aria-live="polite" aria-label="Pemberitahuan cookie">
        <div class="relative overflow-hidden rounded-2xl border border-zinc-200 bg-white p-5 shadow-2xl">
            <div class="pointer-events-none absolute -right-8 -top-8 h-28 w-28 rounded-full bg-amber-100/80"></div>

            <div class="relative flex items-start gap-3">
                <span class="cookie-wiggle flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-amber-100 text-2xl" aria-hidden="true">🍪</span>
                <div>
                    <p class="text-base font-extrabold text-zinc-900">Pengaturan Cookie</p>
                    <p class="mt-1 text-sm leading-relaxed text-zinc-600">Kami menggunakan cookie dari Google Analytics untuk menganalisis performa website kami agar selalu cepat dan nyaman diakses.</p>
                </div>
            </div>

            <div class="relative mt-4 grid grid-cols-2 gap-2">
                <button type="button" @click="acceptAll()" class="col-span-2 rounded-xl bg-honda py-3 text-sm font-bold text-white shadow-lg shadow-red-200 transition hover:bg-honda-dark active:scale-95">Terima Semua</button>
                <button type="button" @click="rejectAll()" class="rounded-xl border border-zinc-300 py-2.5 text-sm font-bold text-zinc-700 transition hover:border-zinc-500 active:scale-95">Tolak</button>
                <button type="button" @click="openSettings()" class="rounded-xl border border-zinc-300 py-2.5 text-sm font-bold text-zinc-700 transition hover:border-zinc-500 active:scale-95">Pengaturan</button>
            </div>
            <a href="{{ route('page.show', 'kebijakan-privasi') }}" class="relative mt-3 block text-center text-xs font-semibold text-zinc-500 underline-offset-2 hover:text-honda hover:underline">Baca Kebijakan Privasi</a>
        </div>
    </div>

    {{-- JENDELA PENGATURAN --}}
    <div x-show="settings" x-cloak class="fixed inset-0 z-[70] flex items-end justify-center p-3 sm:items-center" role="dialog" aria-modal="true" aria-labelledby="cookie-settings-title">
        <div class="absolute inset-0 bg-zinc-900/60" x-show="settings" x-transition.opacity @click="close()"></div>

        <div x-show="settings" x-transition
             class="relative max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-2xl bg-white p-6 shadow-2xl">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 id="cookie-settings-title" class="text-lg font-extrabold text-zinc-900">Pengaturan Cookie</h2>
                    <p class="mt-1 text-sm text-zinc-600">Pilih cookie yang boleh digunakan. Anda dapat mengubahnya kapan saja lewat tautan "Pengaturan Cookie" di bagian bawah website.</p>
                </div>
                <button type="button" @click="close()" class="shrink-0 rounded-full p-1 text-zinc-400 hover:text-zinc-700" aria-label="Tutup">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/></svg>
                </button>
            </div>

            <div class="mt-5 space-y-3">
                {{-- Cookie penting: selalu aktif --}}
                <label class="flex items-start gap-3 rounded-xl border border-zinc-200 bg-zinc-50 p-4">
                    <input type="checkbox" checked disabled class="mt-1 h-5 w-5 rounded border-zinc-300 text-honda opacity-60">
                    <span>
                        <span class="block text-sm font-bold text-zinc-900">Cookie Penting <span class="ml-1 rounded-full bg-zinc-200 px-2 py-0.5 text-[10px] font-bold uppercase text-zinc-600">Selalu aktif</span></span>
                        <span class="mt-1 block text-sm text-zinc-600">Diperlukan agar website berjalan dengan baik dan aman, misalnya saat mengisi formulir atau memakai Chat Sales Counter, serta menyimpan pilihan cookie Anda.</span>
                    </span>
                </label>

                {{-- Analitik --}}
                <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-zinc-200 p-4 transition hover:border-zinc-400">
                    <input type="checkbox" x-model="analytics" class="mt-1 h-5 w-5 rounded border-zinc-300 text-honda focus:ring-honda">
                    <span>
                        <span class="block text-sm font-bold text-zinc-900">Cookie Analitik (Google Analytics)</span>
                        <span class="mt-1 block text-sm text-zinc-600">Kami menggunakan cookie dari Google Analytics untuk menganalisis performa website kami agar selalu cepat dan nyaman diakses. Data bersifat umum dan tidak menampilkan nama atau nomor telepon Anda.</span>
                    </span>
                </label>
            </div>

            <div class="mt-6 flex flex-col gap-2 sm:flex-row">
                <button type="button" @click="saveSettings()" class="flex-1 rounded-xl border border-zinc-300 py-3 text-sm font-bold text-zinc-800 transition hover:border-zinc-500 active:scale-95">Simpan Pilihan</button>
                <button type="button" @click="acceptAll()" class="flex-1 rounded-xl bg-honda py-3 text-sm font-bold text-white shadow-lg shadow-red-200 transition hover:bg-honda-dark active:scale-95">Terima Semua</button>
            </div>
            <a href="{{ route('page.show', 'kebijakan-privasi') }}" target="_blank" class="mt-4 block text-center text-xs font-semibold text-zinc-500 underline-offset-2 hover:text-honda hover:underline">Baca Kebijakan Privasi</a>
        </div>
    </div>
</div>
