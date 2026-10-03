<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Motor, Prospect, Promo};

class DashboardController extends Controller
{
    public function __invoke()
    {
        $stats = [
            'Prospek baru'      => Prospect::where('status', 'baru')->count(),
            'Prospek hari ini'  => Prospect::whereDate('created_at', today())->count(),
            'Total prospek'     => Prospect::count(),
            'Motor aktif'       => Motor::where('is_active', true)->count(),
            'Promo aktif'       => Promo::active()->count(),
        ];
        $latest = Prospect::with('motor')->latest()->take(8)->get();
        return view('admin.dashboard', compact('stats', 'latest'));
    }
}
