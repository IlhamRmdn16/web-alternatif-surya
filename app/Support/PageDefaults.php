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

            'syarat-kredit' => [
                'title' => 'Syarat Kredit Motor Honda',
                'meta_title' => 'Syarat Kredit Motor Honda di Garut',
                'meta_description' => 'Syarat dan dokumen pengajuan kredit motor Honda di dealer Garut, proses pengajuan, serta cara meminta simulasi cicilan dengan DP dan tenor pilihan Anda.',
                'content' => <<<'HTML'
<h2>Syarat pengajuan kredit motor Honda</h2>
<p>Pembelian motor Honda secara kredit diproses melalui perusahaan pembiayaan (leasing) mitra dealer. Dokumen yang umumnya dibutuhkan:</p>
<ul>
<li>KTP pemohon (dan pasangan jika sudah menikah)</li>
<li>Kartu Keluarga</li>
<li>Bukti penghasilan: slip gaji atau surat keterangan kerja untuk karyawan, atau dokumen usaha untuk wiraswasta</li>
<li>Dokumen pendukung lain sesuai permintaan perusahaan pembiayaan</li>
</ul>
<p><em>Syarat dan ketentuan dapat berbeda tergantung perusahaan pembiayaan (leasing) dan hasil survei. Hubungi sales counter kami untuk syarat terbaru.</em></p>
<h2>Proses pengajuan kredit</h2>
<ol>
<li><strong>Pilih motor</strong> di halaman <a href="/daftar-harga">Daftar Harga</a>.</li>
<li><strong>Minta simulasi cicilan</strong> lewat tombol <em>Konsultasi Pembelian</em> di halaman detail motor.</li>
<li><strong>Siapkan dokumen</strong> persyaratan dan serahkan kepada sales counter kami.</li>
<li><strong>Survei dan verifikasi</strong> oleh perusahaan pembiayaan.</li>
<li><strong>Persetujuan kredit</strong> dari perusahaan pembiayaan.</li>
<li><strong>Pembayaran DP dan serah terima unit.</strong></li>
</ol>
<h2>Simulasi cicilan</h2>
<p>Buka halaman detail motor, klik <em>Konsultasi Pembelian</em>, pilih sales counter, lalu pilih keperluan <em>Minta hitungan kredit (simulasi)</em>. Isi nominal DP dan pilih tenor (11, 17, 23, 29, 33, atau 35 bulan). Sales counter akan menghubungi Anda dengan hasil simulasinya.</p>
<h2>Masih ada pertanyaan?</h2>
<p>Baca juga <a href="/faq">pertanyaan yang sering diajukan</a>, atau hubungi sales counter kami di halaman <a href="/kontak">Kontak &amp; Lokasi</a>.</p>
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
<p>Website menggunakan cookie yang diperlukan untuk keamanan dan fungsi dasar, misalnya sesi dan perlindungan formulir. Browser Anda juga menyimpan penanda kecil agar petunjuk penggunaan halaman motor tidak ditampilkan berulang. Jika di kemudian hari kami menambahkan layanan analitik, kebijakan ini akan diperbarui.</p>
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
