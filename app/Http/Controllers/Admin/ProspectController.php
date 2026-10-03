<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prospect;
use Illuminate\Http\Request;

class ProspectController extends Controller
{
    protected function filtered(Request $r)
    {
        return Prospect::with('motor')
            ->when($r->source, fn ($q, $v) => $q->where('source', $v))
            ->when($r->status, fn ($q, $v) => $q->where('status', $v))
            ->when($r->q, fn ($q, $v) => $q->where(fn ($w) => $w->where('name', 'like', "%$v%")->orWhere('phone', 'like', "%$v%")));
    }

    public function index(Request $r)
    {
        $prospects = $this->filtered($r)->latest()->paginate(20)->withQueryString();
        return view('admin.prospects.index', compact('prospects'));
    }

    /** Export CSV (mengikuti filter yang sedang dipilih). Pemisah titik koma agar langsung rapi di Excel Indonesia. */
    public function export(Request $r)
    {
        $rows = $this->filtered($r)->latest()->get();

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM agar Excel membaca UTF-8
            fputcsv($out, ['Tanggal', 'Sumber', 'Nama', 'Alamat', 'No. HP', 'Keperluan', 'Motor', 'Tipe', 'Warna', 'DP', 'Tenor (bulan)', 'Status', 'Catatan'], ';');
            foreach ($rows as $p) {
                fputcsv($out, [
                    $p->created_at->format('Y-m-d H:i'), $p->source === 'form' ? 'Form' : 'WhatsApp', $p->name, $p->address, $p->phone,
                    $p->purpose ? $p->purpose_label : '', $p->motor?->name, $p->variant_name, $p->color_name,
                    $p->dp, $p->tenor, Prospect::STATUSES[$p->status] ?? $p->status, $p->notes,
                ], ';');
            }
            fclose($out);
        }, 'prospek-'.now()->format('Ymd-His').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function show(Prospect $prospect)
    {
        $prospect->load('motor');
        return view('admin.prospects.show', compact('prospect'));
    }

    public function update(Request $r, Prospect $prospect)
    {
        $data = $r->validate([
            'status' => 'required|in:'.implode(',', array_keys(Prospect::STATUSES)),
            'notes'  => 'nullable|string',
        ]);
        $prospect->update($data);
        return back()->with('ok', 'Prospek diperbarui.');
    }

    public function destroy(Prospect $prospect)
    {
        $prospect->delete();
        return redirect()->route('admin.prospects.index')->with('ok', 'Prospek dihapus.');
    }
}
