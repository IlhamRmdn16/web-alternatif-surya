<?php

return [
    // Di mode production, alihkan (301) semua akses dari www, domain lain, atau http ke alamat APP_URL.
    // Jika terjadi "terlalu banyak pengalihan" di hosting, isi CANONICAL_REDIRECT=false di .env.
    'canonical_redirect' => env('CANONICAL_REDIRECT', true),
];
