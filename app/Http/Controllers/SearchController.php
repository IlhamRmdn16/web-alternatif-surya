<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use App\Support\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    /** Pecah kata kunci; kata "honda" diabaikan karena hampir semua motor Honda. */
    private function tokens(string $q): array
    {
        $words = preg_split('/\s+/', Str::lower(trim($q)), -1, PREG_SPLIT_NO_EMPTY);
        return array_values(array_filter($words, fn ($w) => $w !== 'honda'));
    }

    /** Setiap kata harus cocok dengan nama motor, tipe, atau jenis (urutan kata bebas). */
    private function query(array $tokens)
    {
        $q = Motor::active()->with(['category', 'colors']);
        foreach ($tokens as $w) {
            $like = '%'.addcslashes($w, '%_\\').'%';
            $q->where(fn ($x) => $x->where('name', 'like', $like)
                ->orWhere('variant', 'like', $like)
                ->orWhereHas('category', fn ($c) => $c->where('name', 'like', $like)));
        }
        return $q;
    }

    /** "Honda Beat  CBS" -> "beat cbs" (untuk membandingkan nama persis) */
    private function norm(string $text): string
    {
        return trim(preg_replace('/\s+/', ' ', Str::lower(str_ireplace('honda', ' ', $text))));
    }

    /** Saran pencarian (JSON) untuk kolom cari di navbar. */
    public function suggest(Request $r)
    {
        $tokens = $this->tokens((string) $r->query('q'));
        if (! $tokens) return response()->json(['results' => []]);

        $norm = implode(' ', $tokens);
        $motors = $this->query($tokens)->orderBy('name')->orderBy('price')->limit(30)->get()
            // nama yang diawali/persis kata kunci tampil lebih dulu
            ->sortBy(fn ($m) => str_starts_with($this->norm($m->name.' '.$m->variant), $norm) ? 0 : 1)
            ->take(8)->values();

        return response()->json(['results' => $motors->map(fn ($m) => [
            'name'     => $m->full_name,
            'category' => $m->category->name,
            'price'    => $m->lowest_offer['price'] > 0 ? Helpers::rp($m->lowest_offer['cash']) : 'Hubungi kami',
            'image'    => $m->cover_image ? asset('storage/'.$m->cover_image) : null,
            'url'      => route('motor.show', $m),
        ])->all()]);
    }

    /**
     * Tekan Enter di kolom cari: jika nama persis ketemu (mis. "beat cbs iss") atau hasilnya hanya satu,
     * langsung ke halaman detail motornya. Selain itu tampilkan hasil di Daftar Harga.
     */
    public function go(Request $r)
    {
        $raw = trim((string) $r->query('q'));
        $tokens = $this->tokens($raw);
        if (! $tokens) return redirect()->route('pricelist');

        $motors = $this->query($tokens)->orderBy('name')->orderBy('price')->get();
        $norm = implode(' ', $tokens);

        $exact = $motors->first(fn ($m) => $this->norm($m->name.' '.$m->variant) === $norm);
        if ($exact) return redirect()->route('motor.show', $exact);
        if ($motors->count() === 1) return redirect()->route('motor.show', $motors->first());

        return redirect()->route('pricelist', ['q' => $raw]);
    }
}
