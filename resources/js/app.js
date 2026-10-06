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

/* Tombol WhatsApp melayang: simpan nama + alamat sebagai prospek, lalu buka WhatsApp */
Alpine.data('waLead', (url, sales) => ({
    sales, open: false, loading: false, name: '', phone: '', error: '',
    target: null,        // id sales counter terpilih
    callCenter: false,   // true = tujuan call center (dari halaman Kontak)
    topic: '',
    openFor(d) {
        d = d || {};
        this.error = ''; this.topic = d.topic || ''; this.callCenter = !!d.callCenter;
        if (this.callCenter) this.target = null;
        else if (d.salesId) this.target = d.salesId;
        else this.target = this.sales.length === 1 ? this.sales[0].id : null;
        this.open = true;
    },
    async submit() {
        this.error = '';
        if (!this.callCenter && !this.target) { this.error = 'Pilih sales counter yang akan dihubungi.'; return; }
        if (!this.name.trim()) { this.error = 'Nama wajib diisi.'; return; }
        if (!this.phone.trim()) { this.error = 'Nomor WhatsApp wajib diisi.'; return; }
        if (!/^[0-9+\-\s]{8,20}$/.test(this.phone.trim())) { this.error = 'Format nomor WhatsApp tidak valid (contoh: 08123456789).'; return; }
        this.loading = true;
        const win = window.open('', '_blank');
        const r = await post(url, {
            name: this.name, phone: this.phone, page: location.href, topic: this.topic,
            sales_id: this.callCenter ? null : this.target, call_center: this.callCenter,
        });
        this.loading = false;
        if (r.ok && r.data.url) {
            win ? (win.location.href = r.data.url) : (location.href = r.data.url);
            this.open = false; this.name = ''; this.phone = '';
        } else {
            if (win) win.close();
            this.error = firstError(r.data);
        }
    },
}));

/* Halaman detail motor: warna menentukan harga + form konsultasi pembelian */
Alpine.data('motorPage', (cfg) => ({
    colors: cfg.colors, mainImage: cfg.image, sales: cfg.sales || [],
    target: (cfg.sales && cfg.sales.length === 1) ? cfg.sales[0].id : null,
    ci: 0,
    open: false, loading: false, done: false, error: '', waUrl: '',
    form: { name: '', phone: '', purpose: '', dp: '', tenor: '' },
    rp: (n) => 'Rp ' + Number(n || 0).toLocaleString('id-ID'),
    get image() { return this.colors[this.ci]?.image || this.mainImage; },
    get offer() {
        const c = this.colors[this.ci];
        const price = Number(c ? c.price : cfg.base.price);
        const discount = Math.min(Number(c ? c.discount : cfg.base.discount), price);
        return { price, discount, cash: Math.max(price - discount, 0) };
    },
    formatDp(e) {
        const d = e.target.value.replace(/\D/g, '');
        this.form.dp = d;
        e.target.value = d ? Number(d).toLocaleString('id-ID') : '';
    },
    async submit() {
        this.error = '';
        if (!this.target) { this.error = 'Pilih sales counter yang akan dihubungi.'; return; }
        this.loading = true;
        // Buka tab WhatsApp langsung saat klik (agar tidak diblokir popup blocker), isi alamatnya setelah data tersimpan.
        const win = window.open('', '_blank');
        const r = await post(cfg.url, { ...this.form, sales_id: this.target, motor_id: cfg.motorId, color_name: this.colors[this.ci]?.name, page: location.href });
        this.loading = false;
        if (r.ok) {
            this.waUrl = r.data.url || '';
            if (this.waUrl) { win ? (win.location.href = this.waUrl) : (location.href = this.waUrl); }
            else if (win) win.close();
            this.done = true;
            this.form = { name: '', phone: '', purpose: '', dp: '', tenor: '' };
        } else {
            if (win) win.close();
            this.error = firstError(r.data);
        }
    },
}));

/* Petunjuk halaman detail motor: tampil otomatis SEKALI saja (kunjungan pertama ke halaman detail mana pun) */
const GUIDE_KEY = 'dmhg_motor_guide_v1';
Alpine.data('motorGuide', () => ({
    active: false, i: 0, list: [], el: null,
    steps: [
        { key: 'colors', title: 'Pilih warna', text: 'Klik bulatan warna di bawah foto untuk melihat foto dan harga warna tersebut.' },
        { key: 'types', title: 'Ganti tipe', text: 'Klik tombol tipe (misalnya CBS atau CBS ISS) untuk membuka tipe lain dari motor yang sama. Setiap tipe punya harga sendiri.' },
        { key: 'price', title: 'Lihat harga', text: 'Harga OTR (dan harga setelah diskon, jika ada) tampil di sini, dan berubah otomatis mengikuti warna yang Anda pilih.' },
        { key: 'consult', title: 'Konsultasi pembelian', text: 'Klik tombol ini untuk tanya stok, minta simulasi kredit, atau pesan unit. Tim kami akan menghubungi Anda.' },
    ],
    init() {
        let seen = true;
        try { seen = localStorage.getItem(GUIDE_KEY) === '1'; } catch (e) { seen = true; }
        if (!seen) setTimeout(() => this.start(true), 900);
    },
    build() {
        this.list = this.steps.filter((s) => {
            const e = document.querySelector('[data-tour="' + s.key + '"]');
            return e && e.offsetParent !== null;
        });
    },
    start(first) {
        this.build();
        if (!this.list.length) return;
        if (first) { try { localStorage.setItem(GUIDE_KEY, '1'); } catch (e) {} }
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
        const multi = document.querySelectorAll('.hero-swiper .swiper-slide').length > 1;
        new Swiper('.hero-swiper', {
            loop: multi,
            allowTouchMove: multi,
            autoplay: multi ? { delay: 4500, disableOnInteraction: false, pauseOnMouseEnter: true } : false,
            speed: 600,
            pagination: { el: '.swiper-pagination', clickable: true },
            grabCursor: multi,
        });
    }
});

Alpine.start();
