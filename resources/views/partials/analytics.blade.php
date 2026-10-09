{{-- Google Analytics. Isi "Google Analytics Measurement ID" di Admin > Pengaturan. Aktif hanya saat APP_ENV=production. --}}
@php($gaId = trim($settings['ga_id'] ?? ''))
@if($gaId !== '' && app()->environment('production') && preg_match('/^(G|GT|AW|UA)-[A-Za-z0-9-]+$/', $gaId))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());
        gtag('config', '{{ $gaId }}');
    </script>
@endif
