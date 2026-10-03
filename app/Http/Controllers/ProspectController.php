<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use App\Models\Prospect;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProspectController extends Controller
{
    /** Form konsultasi pembelian (dari halaman detail motor). */
    public function store(Request $r)
    {
        $r->merge(['dp' => preg_replace('/\D/', '', (string) $r->input('dp')) ?: null]);

        $data = $r->validate([
            'name'         => 'required|string|max:100',
            'address'      => 'required|string|max:255',
            'phone'        => ['required', 'string', 'regex:/^[0-9+\-\s]{8,20}$/'],
            'purpose'      => ['required', Rule::in(array_keys(Prospect::PURPOSES))],
            'motor_id'     => 'nullable|exists:motors,id',
            'variant_name' => 'nullable|string|max:100',
            'color_name'   => 'nullable|string|max:60',
            'color_name'   => 'nullable|string|max:60',
            'dp'           => 'required_if:purpose,simulasi|nullable|numeric|min:0',
            'tenor'        => ['required_if:purpose,simulasi', 'nullable', Rule::in(Prospect::TENORS)],
        ], [
            'required'    => ':attribute wajib diisi.',
            'required_if' => ':attribute wajib diisi untuk simulasi kredit.',
            'phone.regex' => 'Format nomor HP tidak valid.',
        ], [
            'name' => 'Nama', 'address' => 'Alamat', 'phone' => 'No. HP',
            'purpose' => 'Keperluan', 'dp' => 'Nominal DP', 'tenor' => 'Tenor',
        ]);

        if (! empty($data['motor_id'])) {
            $data['variant_name'] = Motor::whereKey($data['motor_id'])->value('variant');
        }

        if ($data['purpose'] !== 'simulasi') {
            $data['dp'] = null;
            $data['tenor'] = null;
        }

        Prospect::create($data + ['source' => 'form']);

        return response()->json(['message' => 'Terima kasih! Data Anda sudah kami terima. Tim kami akan segera menghubungi Anda.']);
    }

    /** Klik tombol WhatsApp: simpan nama + alamat, lalu kembalikan link wa.me. */
    public function wa(Request $r)
    {
        $data = $r->validate([
            'name'    => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'page'    => 'nullable|string|max:255',
        ], ['required' => ':attribute wajib diisi.'], ['name' => 'Nama', 'address' => 'Alamat']);

        Prospect::create([
            'source'  => 'whatsapp',
            'name'    => $data['name'],
            'address' => $data['address'],
            'message' => $data['page'] ?? null,
        ]);

        $number = Setting::normalizeNumber(Setting::where('key', 'wa_number')->value('value'));
        $text = "Halo Dealer Motor Honda Garut, saya {$data['name']} dari {$data['address']}. Saya ingin bertanya mengenai motor Honda.";
        if (! empty($data['page'])) $text .= "\n\nHalaman: ".$data['page'];

        return response()->json(['url' => 'https://wa.me/'.$number.'?text='.rawurlencode($text)]);
    }
}
