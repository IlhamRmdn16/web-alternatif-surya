<?php

namespace App\Http\Controllers;

use App\Models\{Motor, Post, Promo};

class SitemapController extends Controller
{
    public function __invoke()
    {
        $base = rtrim(config('app.url'), '/');
        $urls = [
            [$base.'/', now(), '1.0'],
            [$base.'/daftar-harga', now(), '0.9'],
            [$base.'/promo', now(), '0.8'],
            [$base.'/tentang-kami', now(), '0.7'],
            [$base.'/berita', now(), '0.7'],
            [$base.'/kontak', now(), '0.7'],
            [$base.'/kebijakan-privasi', now(), '0.3'],
        ];
        foreach (Motor::active()->get() as $m) $urls[] = [$base.'/motor/'.$m->slug, $m->updated_at, '0.8'];
        foreach (Post::published()->get() as $post) $urls[] = [$base.'/berita/'.$post->slug, $post->updated_at, '0.6'];
        foreach (Promo::active()->get() as $p) $urls[] = [$base.'/promo/'.$p->slug, $p->updated_at, '0.6'];

        $xml = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        foreach ($urls as [$loc, $mod, $prio]) {
            $xml .= '<url><loc>'.e($loc).'</loc><lastmod>'.$mod->toAtomString().'</lastmod><priority>'.$prio.'</priority></url>';
        }
        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }
}
