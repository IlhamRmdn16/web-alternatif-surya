<?php

namespace App\Support;

/**
 * Isi awal halaman Tentang Kami. Semua teks dan foto dapat diubah di Admin > Tentang Kami.
 * Catatan: Visi, Misi, dan angka pada kartu adalah CONTOH, sesuaikan dengan data resmi perusahaan.
 */
class AboutDefaults
{
    public static function all(): array
    {
        return [
            'hero' => [
                'eyebrow'   => 'Perjalanan sejak 1991',
                'title'     => 'Tiga Dekade Bersama Pengendara Garut',
                'highlight' => 'Tiga Dekade',
                'text'      => 'Sejak 1991, CV. Surya Wijaya Sejahtera mendampingi masyarakat Garut dan sekitarnya dalam memilih, membeli, serta merawat motor Honda, dengan pelayanan yang jujur dan transparan.',
                'btn1'      => 'Kenali Kami',
                'btn2'      => 'Jelajahi Layanan',
                'photo'     => null,
            ],
            'stats' => [
                ['value' => '1991', 'label' => 'Melayani sejak', 'desc' => 'Dealer motor Honda di Kota Garut'],
                ['value' => 'H1 · H2 · H3', 'label' => 'Layanan lengkap', 'desc' => 'Penjualan, servis, dan suku cadang asli'],
                ['value' => 'Garut Kota', 'label' => 'Lokasi dealer', 'desc' => 'Jl. Papandayan No.112'],
            ],
            'story' => [
                'eyebrow' => 'Tentang kami',
                'title'   => 'Dari Kota Garut untuk Kebutuhan Berkendara Anda',
                'text'    => "CV. Surya Wijaya Sejahtera adalah dealer sepeda motor Honda yang berdiri di Kota Garut sejak tahun 1991. Lebih dari tiga dekade kami hadir untuk membantu masyarakat Garut dan sekitarnya mendapatkan motor yang sesuai dengan kebutuhan dan kemampuan mereka.\n\nKami percaya membeli motor adalah keputusan penting. Karena itu, harga setiap tipe kami tampilkan secara terbuka, lengkap dengan pilihan warna dan potongan untuk pembelian cash, sehingga Anda dapat membandingkan dengan tenang sebelum datang ke dealer.\n\nSetelah motor menjadi milik Anda, kami tetap mendampingi lewat layanan servis di bengkel AHASS dan suku cadang asli Honda, agar kendaraan selalu terawat dan nyaman dipakai setiap hari.",
                'photo'   => null,
            ],
            'services' => [
                'eyebrow' => 'Layanan terpadu Honda',
                'title'   => 'Satu tempat, tiga layanan utama.',
                'intro'   => 'Kami mendampingi Anda sejak memilih motor, merawatnya, hingga mendapatkan suku cadang yang tepat.',
                'items'   => [
                    ['code' => 'H1', 'title' => 'Penjualan Motor Baru', 'text' => 'Motor Honda baru dengan beragam jenis, tipe, dan warna, disertai informasi harga serta proses pembelian yang jelas.', 'chip' => 'Penjualan · Unit baru'],
                    ['code' => 'H2', 'title' => 'Servis & Perawatan', 'text' => 'Perawatan dan perbaikan di bengkel AHASS agar motor Anda tetap nyaman dan andal dipakai sehari-hari.', 'chip' => 'AHASS · Purna jual'],
                    ['code' => 'H3', 'title' => 'Suku Cadang & Aksesori', 'text' => 'Suku cadang dan aksesori asli Honda untuk menjaga kualitas, keselamatan, dan performa kendaraan Anda.', 'chip' => 'Honda Genuine Parts'],
                ],
            ],
            'vision' => [
                'eyebrow'      => 'Arah perusahaan',
                'title'        => 'Visi dan misi kami.',
                'vision_quote' => 'Menjadi dealer motor Honda pilihan masyarakat Garut.',
                'vision_text'  => 'Dipercaya karena pelayanan yang jujur, harga yang terbuka, dan dukungan purna jual yang prima.',
                'missions'     => "Memberikan pelayanan yang ramah, jujur, dan transparan kepada setiap pelanggan.\nMenyediakan produk dan layanan purna jual Honda yang berkualitas.\nTerus meningkatkan kemampuan tim agar pelayanan semakin cepat dan tepat.\nMenjaga hubungan jangka panjang dengan pelanggan melalui pengalaman yang positif.",
            ],
            'places' => [
                'eyebrow' => 'Lokasi kami',
                'title'   => 'Mudah dijangkau di Kota Garut.',
                'intro'   => 'Kunjungi dealer kami atau hubungi sales counter untuk informasi stok, harga, dan promo terbaru.',
                // satu lokasi per baris, format: Nama lokasi | Keterangan
                'items'   => 'Dealer Garut Kota | Jl. Papandayan No.112, Kota Kulon, Kec. Garut Kota',
            ],
            'seo' => [
                'title'       => 'Tentang Kami - Dealer Motor Honda Garut Sejak 1991',
                'description' => 'Profil CV. Surya Wijaya Sejahtera, dealer motor Honda di Garut sejak 1991: layanan penjualan motor Honda, servis AHASS, dan suku cadang asli Honda.',
            ],
        ];
    }
}
