import Alpine from 'alpinejs';
import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';

window.Alpine = Alpine;
window.Swiper = Swiper;

const csrf = () => document.querySelector('meta[name="csrf-token"]')?.content;

async function post(url, data) {
    const res = await fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': csrf() },
        body: JSON.stringify(data),
    });
    const json = await res.json().catch(() => ({}));
    return { ok: res.ok, data: json };
}

const firstError = (d) => (d.errors ? Object.values(d.errors)[0][0] : d.message) || 'Terjadi kesalahan, coba lagi.';

/* Penanda "sudah pernah" (localStorage + cookie sebagai cadangan), dipakai petunjuk motor & pemberitahuan cookie */
const flagSeen = (k) => {
    try { if (localStorage.getItem(k) === '1') return true; } catch (e) {}
    return document.cookie.split('; ').includes(k + '=1');
};
const flagMark = (k) => {
    try { localStorage.setItem(k, '1'); } catch (e) {}
    document.cookie = k + '=1; max-age=31536000; path=/; SameSite=Lax';
};

/* Tombol WhatsApp melayang: simpan nama + nomor sebagai prospek, lalu buka WhatsApp admin (atau call center dari halaman Kontak) */
Alpine.data('waLead', (url) => ({
    open: false, loading: false, name: '', phone: '', error: '', consent: false,
    callCenter: false,   // true = tujuan call center (dari halaman Kontak)
    topic: '',
    openFor(d) {
        d = d || {};
        this.error = ''; this.topic = d.topic || ''; this.callCenter = !!d.callCenter;
        this.open = true;
    },
    async submit() {
        this.error = '';
        if (!this.name.trim()) { this.error = 'Nama wajib diisi.'; return; }
        if (!this.consent) { this.error = 'Centang persetujuan untuk dihubungi oleh admin kami.'; return; }
        if (!this.phone.trim()) { this.error = 'Nomor WhatsApp wajib diisi.'; return; }
        if (!/^[0-9+\-\s]{8,20}$/.test(this.phone.trim())) { this.error = 'Format nomor WhatsApp tidak valid (contoh: 08123456789).'; return; }
        this.loading = true;
        const win = window.open('', '_blank');
        const r = await post(url, {
            name: this.name, phone: this.phone, consent: this.consent, page: location.href, topic: this.topic,
            call_center: this.callCenter,
        });
        this.loading = false;
        if (r.ok && r.data.url) {
            win ? (win.location.href = r.data.url) : (location.href = r.data.url);
            this.open = false; this.name = ''; this.phone = ''; this.consent = false;
        } else {
            if (win) win.close();
            this.error = firstError(r.data);
        }
    },
}));

/* Halaman detail motor: warna menentukan harga + foto bisa digeser + form konsultasi pembelian */
const SWIPE_KEY = 'dmhg_swipe_hint_v1';
Alpine.data('motorPage', (cfg) => {
    let sw = null; // instance Swiper (di luar state reaktif Alpine)
    return {
        colors: cfg.colors, mainImage: cfg.image,
        ci: 0,
        hint: false,
        open: false, loading: false, done: false, error: '', waUrl: '', consent: false,
        form: { name: '', phone: '', purpose: '', dp: '', tenor: '' },
        rp: (n) => 'Rp ' + Number(n || 0).toLocaleString('id-ID'),
        get image() { return this.colors[this.ci]?.image || this.mainImage; },
        get offer() {
            const c = this.colors[this.ci];
            const price = Number(c ? c.price : cfg.base.price);
            const discount = Math.min(Number(c ? c.discount : cfg.base.discount), price);
            return { price, discount, cash: Math.max(price - discount, 0) };
        },
        init() {
            this.$nextTick(() => {
                const el = this.$refs.photo;
                if (!el) return;
                const total = el.querySelectorAll('.swiper-slide').length;
                sw = new Swiper(el, {
                    slidesPerView: 1,
                    spaceBetween: 16,
                    speed: 350,
                    grabCursor: total > 1,
                    allowTouchMove: total > 1,
                    resistanceRatio: 0.6,
                    a11y: false,
                    // Titik penanda posisi geser: satu titik per foto/warna, titik aktif berwarna hitam
                    pagination: this.$refs.dots ? { el: this.$refs.dots, clickable: true } : false,
                });
                // Geser foto -> warna & harga ikut berubah
                sw.on('slideChange', () => { this.ci = sw.activeIndex; });
                // Klik bulatan warna -> foto ikut bergeser
                this.$watch('ci', (v) => { if (sw && sw.activeIndex !== v) sw.slideTo(v, 350); });

                // Animasi petunjuk geser: hanya saat pertama kali membuka halaman motor
                if (total > 1 && !flagSeen(SWIPE_KEY)) {
                    flagMark(SWIPE_KEY);
                    setTimeout(() => {
                        this.hint = true;
                        setTimeout(() => { this.hint = false; }, 2600);
                    }, 500);
                }
            });
        },
        formatDp(e) {
            const d = e.target.value.replace(/\D/g, '');
            this.form.dp = d;
            e.target.value = d ? Number(d).toLocaleString('id-ID') : '';
        },
        async submit() {
            this.error = '';
            if (!this.consent) { this.error = 'Centang persetujuan untuk dihubungi oleh admin kami.'; return; }
            this.loading = true;
            // Buka tab WhatsApp langsung saat klik (agar tidak diblokir popup blocker), isi alamatnya setelah data tersimpan.
            const win = window.open('', '_blank');
            const r = await post(cfg.url, { ...this.form, consent: this.consent, motor_id: cfg.motorId, color_name: this.colors[this.ci]?.name, page: location.href });
            this.loading = false;
            if (r.ok) {
                this.waUrl = r.data.url || '';
                if (this.waUrl) { win ? (win.location.href = this.waUrl) : (location.href = this.waUrl); }
                else if (win) win.close();
                this.done = true; this.consent = false;
                this.form = { name: '', phone: '', purpose: '', dp: '', tenor: '' };
            } else {
                if (win) win.close();
                this.error = firstError(r.data);
            }
        },
    };
});

/* Petunjuk halaman detail motor: tampil otomatis SEKALI (kunjungan pertama ke halaman motor mana pun).
   Penanda disimpan SEBELUM petunjuk tampil, jadi pindah tipe/halaman tidak pernah mengulang dari awal.
   Selanjutnya hanya muncul jika tombol tanda tanya (?) diklik. */
const GUIDE_KEY = 'dmhg_motor_guide_v1';
Alpine.data('motorGuide', () => ({
    active: false, i: 0, list: [], el: null,
    steps: [
        { key: 'colors', title: 'Pilih warna', text: 'Klik bulatan warna di bawah foto, atau geser fotonya ke kiri/kanan, untuk melihat foto dan harga warna tersebut.' },
        { key: 'types', title: 'Ganti tipe', text: 'Klik tombol tipe (misalnya CBS atau CBS ISS) untuk membuka tipe lain dari motor yang sama. Setiap tipe punya harga sendiri.' },
        { key: 'price', title: 'Lihat harga', text: 'Harga OTR (dan harga setelah diskon, jika ada) tampil di sini, dan berubah otomatis mengikuti warna yang Anda pilih.' },
        { key: 'consult', title: 'Konsultasi pembelian', text: 'Klik tombol ini untuk tanya stok, minta simulasi kredit, atau pesan unit. Tim kami akan menghubungi Anda.' },
    ],
    init() {
        if (!flagSeen(GUIDE_KEY)) {
            flagMark(GUIDE_KEY);
            setTimeout(() => this.start(), 900);
        }
    },
    build() {
        this.list = this.steps.filter((s) => {
            const e = document.querySelector('[data-tour="' + s.key + '"]');
            return e && e.offsetParent !== null;
        });
    },
    start() {
        this.build();
        if (!this.list.length) return;
        this.i = 0; this.active = true; this.focus();
    },
    focus() {
        this.clear();
        const s = this.list[this.i];
        const e = s && document.querySelector('[data-tour="' + s.key + '"]');
        if (!e) return;
        this.el = e;
        e.classList.add('tour-hl');
        window.scrollTo({ top: Math.max(e.getBoundingClientRect().top + window.scrollY - 150, 0), behavior: 'smooth' });
    },
    clear() { if (this.el) { this.el.classList.remove('tour-hl'); this.el = null; } },
    next() { if (this.i < this.list.length - 1) { this.i++; this.focus(); } else this.finish(); },
    prev() { if (this.i > 0) { this.i--; this.focus(); } },
    finish() { this.clear(); this.active = false; },
    get step() { return this.list[this.i] || {}; },
}));

/* Kolom cari di navbar: saran langsung saat mengetik; Enter ke detail motor jika nama persis cocok */
Alpine.data('siteSearch', (url, initial) => ({
    q: initial || '', results: [], open: false, loading: false, active: -1, timer: null,
    input() {
        clearTimeout(this.timer);
        const v = this.q.trim();
        this.active = -1;
        if (v.length < 2) { this.results = []; this.open = false; return; }
        this.timer = setTimeout(() => this.fetchResults(v), 200);
    },
    async fetchResults(v) {
        this.loading = true;
        try {
            const res = await fetch(url + '?q=' + encodeURIComponent(v), { headers: { Accept: 'application/json' } });
            const json = await res.json();
            if (v === this.q.trim()) { this.results = json.results || []; this.open = true; }
        } catch (e) { /* abaikan */ }
        this.loading = false;
    },
    move(d) {
        if (!this.results.length) return;
        this.open = true;
        this.active = (this.active + d + this.results.length) % this.results.length;
    },
    go(e) {
        // Saran dipilih dengan panah: langsung buka. Selain itu form dikirim ke /cari (server memutuskan).
        if (this.active >= 0 && this.results[this.active]) { e.preventDefault(); location.href = this.results[this.active].url; }
    },
    close() { this.open = false; this.active = -1; },
}));

/* Pemberitahuan cookie (strip di atas footer): hanya informasi, tampil sampai pengunjung klik "Mengerti" */
const COOKIE_KEY = 'dmhg_cookie_ok';
Alpine.data('cookieNotice', () => ({
    show: false,
    init() { this.show = !flagSeen(COOKIE_KEY); },
    accept() { flagMark(COOKIE_KEY); this.show = false; },
}));

/* Form motor di admin: warna (nama + foto + harga opsional), auto-pilih jenis jika seri sudah ada */
Alpine.data('motorForm', (colors, seriesMap, init) => {
    let uid = 0;
    const isCustom = (c) => c.custom === true || c.custom === 1 || c.custom === '1';
    const make = (c) => ({
        name: c.name || '', hex: c.hex || '#000000', old_image: c.old_image || null, preview: null,
        auto: !isCustom(c), price: c.price ?? '', discount: c.discount ?? c.cash_discount ?? '', uid: ++uid,
    });
    return {
        colors: colors.map(make),
        name: init.name || '', category: init.category || '', price: init.price || '', discount: init.discount || '',
        addColor() { this.colors.push(make({})); },
        setAuto(c, auto) {
            c.auto = auto;
            if (!auto) {
                if (c.price === '' || c.price === null) c.price = this.price;
                if (c.discount === '' || c.discount === null) c.discount = this.discount || 0;
            }
        },
        preview(e, c) { const f = e.target.files[0]; c.preview = f ? URL.createObjectURL(f) : null; },
        pickCategory() {
            const id = seriesMap[(this.name || '').trim().toLowerCase()];
            if (id) this.category = String(id);
        },
    };
});

document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('.hero-swiper')) {
        new Swiper('.hero-swiper', {
            loop: document.querySelectorAll('.hero-swiper .swiper-slide').length > 1,
            autoplay: { delay: 2500, disableOnInteraction: false },
            speed: 800,
            effect: 'fade',
            fadeEffect: { crossFade: true },
            pagination: { el: '.swiper-pagination', clickable: true },
            grabCursor: true,
        });
    }
});

Alpine.start();
