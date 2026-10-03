<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use App\Models\Prospect;
use App\Models\Setting;
use App\Support\Helpers;
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
            'page'         => 'nullable|string|max:255',
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

        $page = $data['page'] ?? null;
        unset($data['page']);

        Prospect::create($data + ['source' => 'form']);

        // Pesan WhatsApp otomatis berisi data formulir, supaya sales langsung paham kebutuhan konsumen.
        $motor = ! empty($data['motor_id']) ? Motor::find($data['motor_id']) : null;
        $lines = ["Halo Dealer Motor Honda Garut, saya {$data['name']} dari {$data['address']}."];
        $lines[] = 'Keperluan: '.Prospect::PURPOSES[$data['purpose']];
        if ($motor) {
            $lines[] = 'Unit: '.$motor->full_name.(! empty($data['color_name']) ? ' warna '.$data['color_name'] : '');
        }
        if ($data['purpose'] === 'simulasi') {
            $lines[] = 'DP: '.Helpers::rp($data['dp']);
            $lines[] = 'Tenor: '.$data['tenor'].' bulan';
        }
        $lines[] = 'No. HP: '.$data['phone'];
        if ($page) $lines[] = 'Halaman: '.$page;

        $number = Setting::normalizeNumber(Setting::where('key', 'wa_number')->value('value'));

        return response()->json([
            'message' => 'Terima kasih! Data Anda sudah kami terima. Tim kami akan segera menghubungi Anda.',
            'url'     => 'https://wa.me/'.$number.'?text='.rawurlencode(implode("\n", $lines)),
        ]);
    }

    /** Klik tombol WhatsApp: simpan nama + alamat, lalu kembalikan link wa.me. */
    public function wa(Request $r)
    {
        $data = $r->validate([
            'name'    => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'phone'   => ['required', 'string', 'regex:/^[0-9+\-\s]{8,20}$/'],
            'page'    => 'nullable|string|max:255',
        ], [
            'required'    => ':attribute wajib diisi.',
            'phone.regex' => 'Format nomor WhatsApp tidak valid (contoh: 08123456789).',
        ], ['name' => 'Nama', 'address' => 'Alamat', 'phone' => 'Nomor WhatsApp']);

        Prospect::create([
            'source'  => 'whatsapp',
            'name'    => $data['name'],
            'address' => $data['address'],
            'phone'   => trim($data['phone']),
            'message' => $data['page'] ?? null,
        ]);

        $number = Setting::normalizeNumber(Setting::where('key', 'wa_number')->value('value'));
        $text = "Halo Dealer Motor Honda Garut, saya {$data['name']} dari {$data['address']}. Saya ingin bertanya mengenai motor Honda.";
        if (! empty($data['page'])) $text .= "\n\nHalaman: ".$data['page'];

        return response()->json(['url' => 'https://wa.me/'.$number.'?text='.rawurlencode($text)]);
    }
}
