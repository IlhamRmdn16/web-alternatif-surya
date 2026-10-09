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
<li><strong>Data penggunaan website:</strong> melalui Google Analytics, misalnya halaman yang dibuka, durasi kunjungan, jenis perangkat dan browser, serta perkiraan wilayah. Data ini bersifat statistik dan tidak mencakup nama atau nomor telepon Anda.</li>
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
<p>Website menggunakan cookie yang diperlukan untuk keamanan dan fungsi dasar, misalnya sesi dan perlindungan formulir, serta cookie dari Google Analytics untuk memantau performa website (dijelaskan pada bagian berikut). Anda dapat mengatur atau menghapus cookie melalui pengaturan browser.</p>
<h2>Google Analytics</h2>
<p>Website ini menggunakan Google Analytics, layanan analitik dari Google, untuk memantau performa dan penggunaan website, misalnya jumlah pengunjung, halaman yang paling sering dibuka, durasi kunjungan, jenis perangkat dan browser, serta perkiraan wilayah pengunjung. Informasi ini membantu kami memperbaiki isi dan kecepatan website.</p>
<p>Google Analytics menggunakan cookie dan teknologi serupa untuk mengumpulkan informasi tersebut secara statistik. Kami tidak mengirimkan nama, nomor telepon, atau isi formulir yang Anda isi ke Google Analytics. Data diproses oleh Google sesuai <a href="https://policies.google.com/privacy" target="_blank" rel="noopener noreferrer">Kebijakan Privasi Google</a>.</p>
<p>Anda dapat menonaktifkan cookie melalui pengaturan browser atau memasang <a href="https://tools.google.com/dlpage/gaoptout" target="_blank" rel="noopener noreferrer">add-on penolakan Google Analytics</a>. Perlu diketahui, menonaktifkan cookie dapat memengaruhi sebagian fungsi website.</p>
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
