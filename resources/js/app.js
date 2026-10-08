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

/* Penanda "sudah pernah" (localStorage + cookie sebagai cadangan), dipakai petunjuk motor & pop-up cookie */
const flagSeen = (k) => {
    try { if (localStorage.getItem(k) === '1') return true; } catch (e) {}
    return document.cookie.split('; ').includes(k + '=1');
};
const flagMark = (k) => {
    try { localStorage.setItem(k, '1'); } catch (e) {}
    document.cookie = k + '=1; max-age=31536000; path=/; SameSite=Lax';
};

/* Petunjuk halaman detail motor: tampil otomatis SEKALI (kunjungan pertama ke halaman motor mana pun).
   Penanda disimpan SEBELUM petunjuk tampil, jadi pindah tipe/halaman tidak pernah mengulang dari awal.
   Selanjutnya hanya muncul jika tombol tanda tanya (?) diklik. */
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

/* Persetujuan cookie (Terima Semua / Tolak / Pengaturan).
   Cookie penting selalu aktif. Google Analytics HANYA dimuat setelah pengunjung menyetujui kategori "Analitik".
   Pilihan disimpan di localStorage (+ cookie cadangan) selama 1 tahun dan bisa diubah lewat tautan "Pengaturan Cookie" di footer. */
const CONSENT_KEY = 'dmhg_cookie_consent';
const CONSENT_VERSION = 1;

const readConsent = () => {
    const parse = (raw) => { try { const v = JSON.parse(raw); return v && v.v === CONSENT_VERSION ? v : null; } catch (e) { return null; } };
    try { const v = parse(localStorage.getItem(CONSENT_KEY)); if (v) return v; } catch (e) {}
    const m = document.cookie.split('; ').find((c) => c.startsWith(CONSENT_KEY + '='));
    return m ? parse(decodeURIComponent(m.slice(CONSENT_KEY.length + 1))) : null;
};
const writeConsent = (analytics) => {
    const value = JSON.stringify({ v: CONSENT_VERSION, necessary: true, analytics: !!analytics, at: Date.now() });
    try { localStorage.setItem(CONSENT_KEY, value); } catch (e) {}
    document.cookie = CONSENT_KEY + '=' + encodeURIComponent(value) + '; max-age=31536000; path=/; SameSite=Lax';
};

let gaLoaded = false;
const loadAnalytics = (id) => {
    if (!id) return;
    window['ga-disable-' + id] = false;
    if (gaLoaded) return;
    gaLoaded = true;
    const s = document.createElement('script');
    s.async = true;
    s.src = 'https://www.googletagmanager.com/gtag/js?id=' + encodeURIComponent(id);
    document.head.appendChild(s);
    window.dataLayer = window.dataLayer || [];
    window.gtag = function () { window.dataLayer.push(arguments); };
    window.gtag('js', new Date());
    window.gtag('config', id, { anonymize_ip: true });
};
const stopAnalytics = (id) => {
    if (id) window['ga-disable-' + id] = true;
    // hapus cookie Google Analytics (_ga, _ga_XXXX) jika sebelumnya sudah terpasang
    const host = location.hostname.split('.');
    const domains = ['', location.hostname, ...(host.length > 1 ? ['.' + host.slice(-2).join('.')] : [])];
    document.cookie.split('; ').map((c) => c.split('=')[0]).filter((n) => n === '_ga' || n.startsWith('_ga_') || n === '_gid').forEach((n) => {
        domains.forEach((d) => { document.cookie = n + '=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/' + (d ? '; domain=' + d : ''); });
    });
};

Alpine.data('cookieNotice', (gaId) => ({
    show: false,       // banner kecil
    settings: false,   // jendela pengaturan
    analytics: false,  // centang "Analitik"
    init() {
        const c = readConsent();
        if (c) { this.analytics = !!c.analytics; if (c.analytics) loadAnalytics(gaId); }
        else setTimeout(() => { if (!readConsent()) this.show = true; }, 1200);
    },
    openSettings() {
        const c = readConsent();
        this.analytics = c ? !!c.analytics : false;
        this.show = false;
        this.settings = true;
    },
    close() { this.settings = false; if (!readConsent()) this.show = true; },
    decide(analytics) {
        this.analytics = analytics;
        writeConsent(analytics);
        analytics ? loadAnalytics(gaId) : stopAnalytics(gaId);
        this.show = false;
        this.settings = false;
    },
    acceptAll() { this.decide(true); },
    rejectAll() { this.decide(false); },
    saveSettings() { this.decide(this.analytics); },
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
