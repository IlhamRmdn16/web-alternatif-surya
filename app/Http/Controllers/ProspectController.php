<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use App\Models\Prospect;
use App\Models\SalesContact;
use App\Models\Setting;
use App\Support\Helpers;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProspectController extends Controller
{
    /** Formulir Konsultasi Pembelian (halaman detail motor): simpan prospek lalu kembalikan link WhatsApp sales pilihan. */
    public function store(Request $r)
    {
        $r->merge(['dp' => preg_replace('/\D/', '', (string) $r->input('dp')) ?: null]);

        $data = $r->validate([
            'sales_id'   => ['required', 'integer', Rule::exists('sales_contacts', 'id')->where('is_active', true)],
            'name'       => 'required|string|max:100',
            'phone'      => ['required', 'string', 'regex:/^[0-9+\-\s]{8,20}$/'],
            'purpose'    => ['required', Rule::in(array_keys(Prospect::PURPOSES))],
            'motor_id'   => 'nullable|exists:motors,id',
            'color_name' => 'nullable|string|max:60',
            'dp'         => 'required_if:purpose,simulasi|nullable|numeric|min:0',
            'tenor'      => ['required_if:purpose,simulasi', 'nullable', Rule::in(Prospect::TENORS)],
            'page'       => 'nullable|string|max:255',
        ], [
            'required'          => ':attribute wajib diisi.',
            'required_if'       => ':attribute wajib diisi untuk simulasi kredit.',
            'phone.regex'       => 'Format nomor HP tidak valid.',
            'sales_id.required' => 'Pilih sales counter yang akan dihubungi.',
            'sales_id.exists'   => 'Sales counter tidak tersedia, silakan pilih yang lain.',
        ], [
            'name' => 'Nama', 'phone' => 'No. HP', 'purpose' => 'Keperluan', 'dp' => 'Nominal DP', 'tenor' => 'Tenor',
        ]);

        $sales = SalesContact::findOrFail($data['sales_id']);
        $motor = ! empty($data['motor_id']) ? Motor::find($data['motor_id']) : null;
        $simulasi = $data['purpose'] === 'simulasi';

        Prospect::create([
            'source'       => 'form',
            'name'         => $data['name'],
            'phone'        => trim($data['phone']),
            'purpose'      => $data['purpose'],
            'motor_id'     => $motor?->id,
            'variant_name' => $motor?->variant,
            'color_name'   => $data['color_name'] ?? null,
            'dp'           => $simulasi ? $data['dp'] : null,
            'tenor'        => $simulasi ? $data['tenor'] : null,
            'sales_name'   => $sales->name,
        ]);

        // Pesan WhatsApp otomatis berisi data formulir, supaya sales langsung paham kebutuhan konsumen.
        $lines = ["Halo {$sales->name}, saya {$data['name']}."];
        $lines[] = 'Keperluan: '.Prospect::PURPOSES[$data['purpose']];
        if ($motor) {
            $lines[] = 'Unit: '.$motor->full_name.(! empty($data['color_name']) ? ' warna '.$data['color_name'] : '');
        }
        if ($simulasi) {
            $lines[] = 'DP: '.Helpers::rp($data['dp']);
            $lines[] = 'Tenor: '.$data['tenor'].' bulan';
        }
        $lines[] = 'No. HP: '.$data['phone'];
        if (! empty($data['page'])) $lines[] = 'Halaman: '.$data['page'];

        return response()->json([
            'message' => 'Terima kasih! Data Anda sudah kami terima.',
            'url'     => 'https://wa.me/'.$sales->wa_number.'?text='.rawurlencode(implode("\n", $lines)),
        ]);
    }

    /** Tombol WhatsApp (semua halaman): simpan nama + nomor, lalu kembalikan link WhatsApp sales counter / call center. */
    public function wa(Request $r)
    {
        $data = $r->validate([
            'name'        => 'required|string|max:100',
            'phone'       => ['required', 'string', 'regex:/^[0-9+\-\s]{8,20}$/'],
            'sales_id'    => 'nullable|integer',
            'call_center' => 'nullable|boolean',
            'topic'       => 'nullable|string|max:150',
            'page'        => 'nullable|string|max:255',
        ], [
            'required'    => ':attribute wajib diisi.',
            'phone.regex' => 'Format nomor WhatsApp tidak valid (contoh: 08123456789).',
        ], ['name' => 'Nama', 'phone' => 'Nomor WhatsApp']);

        if ($r->boolean('call_center')) {
            $number = Setting::normalizeNumber(Setting::where('key', 'call_center')->value('value'));
            if ($number === '') {
                return response()->json(['message' => 'Nomor call center belum tersedia.'], 422);
            }
            $targetName = 'Call Center';
            $greeting = 'Halo Call Center Dealer Motor Honda Garut';
        } else {
            $sales = ! empty($data['sales_id']) ? SalesContact::where('is_active', true)->find($data['sales_id']) : null;
            if (! $sales) {
                return response()->json(['message' => 'Pilih sales counter yang akan dihubungi.'], 422);
            }
            $number = $sales->wa_number;
            $targetName = $sales->name;
            $greeting = "Halo {$sales->name}";
        }

        Prospect::create([
            'source'     => 'whatsapp',
            'name'       => $data['name'],
            'phone'      => trim($data['phone']),
            'message'    => $data['page'] ?? null,
            'sales_name' => $targetName,
        ]);

        $text = "{$greeting}, saya {$data['name']}. ".(! empty($data['topic']) ? 'Saya ingin bertanya mengenai: '.$data['topic'].'.' : 'Saya ingin bertanya mengenai motor Honda.');
        $text .= "\nNo. HP: ".$data['phone'];
        if (! empty($data['page'])) $text .= "\n\nHalaman: ".$data['page'];

        return response()->json(['url' => 'https://wa.me/'.$number.'?text='.rawurlencode($text)]);
    }
}
