<?php

namespace App\Support;

/**
 * Isi awal halaman statis (HTML). Setelah dibuat, isinya diedit lewat Admin > Halaman
 * dengan editor seperti MS Word.
 */
class PageDefaults
{
    public static function all(): array
    {
        return [
            'tentang-kami' => [
                'title' => 'Tentang Kami',
                'meta_title' => 'Tentang Kami - Dealer Motor Honda Garut Sejak 1991',
                'meta_description' => 'Profil CV. Surya Wijaya Sejahtera, dealer motor Honda di Garut sejak 1991: layanan penjualan motor Honda, servis AHASS, dan suku cadang asli Honda.',
                'content' => <<<'HTML'
<h2>Dealer motor Honda Garut sejak 1991</h2>
<p>CV. Surya Wijaya Sejahtera adalah dealer sepeda motor Honda yang berdiri di Kota Garut sejak tahun 1991. Selama lebih dari tiga dekade, kami melayani masyarakat Garut dan sekitarnya dalam memilih, membeli, dan merawat motor Honda.</p>
<h2>Layanan kami</h2>
<p>Kami menjalankan tiga pilar layanan Honda (H1, H2, H3):</p>
<ul>
<li><strong>Penjualan motor Honda (H1)</strong>: unit baru dengan pilihan jenis, tipe, dan warna yang dapat dilihat langsung di website ini, lengkap dengan harga OTR dan potongan untuk pembelian cash.</li>
<li><strong>Perawatan dan servis (H2)</strong>: layanan servis di bengkel AHASS untuk menjaga performa motor Anda.</li>
<li><strong>Suku cadang asli (H3)</strong>: suku cadang asli Honda (Honda Genuine Parts) untuk kebutuhan motor Anda.</li>
</ul>
<h2>Mengapa memilih kami</h2>
<ul>
<li>Harga setiap tipe ditampilkan terbuka di halaman <a href="/daftar-harga">Daftar Harga</a>.</li>
<li>Konsultasi pembelian dan simulasi kredit dapat dilakukan langsung dari website.</li>
<li>Tim sales counter siap membantu melalui WhatsApp. Daftar nomor resmi ada di halaman <a href="/kontak">Kontak &amp; Lokasi</a>.</li>
</ul>
<h2>Kunjungi kami</h2>
<p>Datang langsung ke dealer kami di Jl. Papandayan No.112, Kota Kulon, Kec. Garut Kota, Kabupaten Garut, Jawa Barat 44114. Jam operasional dan peta lokasi dapat dilihat di halaman <a href="/kontak">Kontak &amp; Lokasi</a>.</p>
HTML,
            ],

            'kebijakan-privasi' => [
                'title' => 'Kebijakan Privasi',
                'meta_title' => 'Kebijakan Privasi',
                'meta_description' => 'Kebijakan privasi DealerMotorHondaGarut.id: data yang kami kumpulkan, cara penggunaannya, dan hak Anda atas data pribadi.',
                'content' => <<<'HTML'
<p>Kebijakan Privasi ini menjelaskan bagaimana DealerMotorHondaGarut.id ("website"), yang dikelola oleh CV. Surya Wijaya Sejahtera, mengumpulkan, menggunakan, dan melindungi data pribadi Anda.</p>
<h2>Data yang kami kumpulkan</h2>
<p>Kami hanya mengumpulkan data yang Anda isi sendiri melalui website:</p>
<ul>
<li><strong>Formulir Konsultasi Pembelian:</strong> nama, nomor HP/WhatsApp, keperluan (tanya stok, simulasi kredit, booking unit, atau lainnya), unit yang Anda minati, serta sales counter yang Anda pilih. Untuk simulasi kredit, kami juga menyimpan nominal DP dan tenor yang Anda pilih.</li>
<li><strong>Tombol WhatsApp:</strong> nama dan nomor WhatsApp yang Anda isi, serta sales counter atau call center yang Anda pilih, sebelum diarahkan ke WhatsApp.</li>
</ul>
<h2>Cara kami menggunakan data</h2>
<ul>
<li>Menghubungi Anda untuk menjawab pertanyaan, mengonfirmasi stok dan harga, serta memberikan simulasi kredit atau memproses pemesanan.</li>
<li>Menindaklanjuti permintaan Anda dan meningkatkan kualitas layanan kami.</li>
</ul>
<p>Kami tidak menjual data pribadi Anda kepada pihak lain.</p>
<h2>Pembagian data</h2>
<p>Kami membatasi akses ke data Anda hanya untuk tim dealer yang berkepentingan. Data dapat dibagikan kepada perusahaan pembiayaan (leasing) atau pihak terkait lainnya hanya apabila Anda mengajukan pembelian kredit atau pemesanan unit, atau apabila diwajibkan oleh hukum.</p>
<h2>WhatsApp dan layanan pihak ketiga</h2>
<p>Saat Anda diarahkan ke WhatsApp, percakapan berlangsung di platform WhatsApp dan tunduk pada kebijakan privasi WhatsApp. Website ini juga memuat font dari Google Fonts dan peta dari Google Maps.</p>
<h2>Cookie dan penyimpanan di browser</h2>
<p>Website menggunakan cookie yang diperlukan untuk keamanan dan fungsi dasar, misalnya sesi dan perlindungan formulir. Jika di kemudian hari kami menambahkan layanan analitik, kebijakan ini akan diperbarui.</p>
<h2>Penyimpanan dan keamanan</h2>
<p>Kami menyimpan data selama diperlukan untuk menindaklanjuti permintaan Anda dan keperluan administrasi dealer, serta berupaya menjaga keamanannya. Namun, tidak ada sistem yang sepenuhnya bebas risiko.</p>
<h2>Hak Anda</h2>
<p>Anda dapat meminta untuk mengakses, memperbaiki, atau menghapus data pribadi Anda dengan menghubungi kami melalui halaman <a href="/kontak">Kontak &amp; Lokasi</a>.</p>
<h2>Perubahan kebijakan</h2>
<p>Kebijakan ini dapat diperbarui sewaktu-waktu. Tanggal pembaruan terakhir tercantum di bagian bawah halaman ini.</p>
<h2>Kontak</h2>
<p>CV. Surya Wijaya Sejahtera, Jl. Papandayan No.112, Kota Kulon, Kec. Garut Kota, Kabupaten Garut, Jawa Barat 44114. Hubungi kami melalui halaman <a href="/kontak">Kontak &amp; Lokasi</a>.</p>
HTML,
            ],
        ];
    }
}
