<?php

namespace App\Http\Controllers;

use App\Models\Brochure;
use Illuminate\Support\Facades\Storage;

class BrochureController extends Controller
{
    /** Buka PDF langsung di browser (tab baru). */
    public function show(Brochure $brochure)
    {
        return $this->serve($brochure, true);
    }

    /** Unduh PDF. */
    public function download(Brochure $brochure)
    {
        return $this->serve($brochure, false);
    }

    private function serve(Brochure $brochure, bool $inline)
    {
        $disk = Storage::disk('public');
        abort_unless($brochure->is_active && $disk->exists($brochure->file), 404);

        $headers = ['Content-Type' => 'application/pdf'];

        return $inline
            ? $disk->response($brochure->file, $brochure->download_name, $headers, 'inline')
            : $disk->download($brochure->file, $brochure->download_name, $headers);
    }
}
