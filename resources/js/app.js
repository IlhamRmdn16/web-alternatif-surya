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
Alpine.data('waLead', (url) => ({
    open: false, loading: false, name: '', address: '', error: '',
    async submit() {
        this.error = '';
        if (!this.name.trim() || !this.address.trim()) { this.error = 'Nama dan alamat wajib diisi.'; return; }
        this.loading = true;
        const win = window.open('', '_blank');
        const r = await post(url, { name: this.name, address: this.address, page: location.href });
        this.loading = false;
        if (r.ok && r.data.url) {
            win ? (win.location.href = r.data.url) : (location.href = r.data.url);
            this.open = false; this.name = ''; this.address = '';
        } else {
            if (win) win.close();
            this.error = firstError(r.data);
        }
    },
}));

/* Halaman detail motor: warna menentukan harga + form konsultasi pembelian */
Alpine.data('motorPage', (cfg) => ({
    colors: cfg.colors, mainImage: cfg.image,
    ci: 0,
    open: false, loading: false, done: false, error: '',
    form: { name: '', address: '', phone: '', purpose: '', dp: '', tenor: '' },
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
        this.loading = true;
        const r = await post(cfg.url, { ...this.form, motor_id: cfg.motorId, color_name: this.colors[this.ci]?.name });
        this.loading = false;
        if (r.ok) { this.done = true; this.form = { name: '', address: '', phone: '', purpose: '', dp: '', tenor: '' }; }
        else this.error = firstError(r.data);
    },
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
            autoplay: { delay: 5000, disableOnInteraction: false },
            pagination: { el: '.swiper-pagination', clickable: true },
            navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
            grabCursor: true,
        });
    }
});

Alpine.start();
