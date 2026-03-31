<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogIt;
use Barryvdh\DomPDF\Facade\Pdf;

class Lookbook extends Controller
{
    public function index()
    {
        $all = LogIt::all();
        $stats = [
            'total' => $all->count(),
            'selesai' => $all->where('status', 'Selesai')->count(),
            'proses' => $all->where('status', 'Proses')->count(),
            'batal' => $all->where('status', 'Batal')->count(),
            'hardware' => $all->where('kategori', 'Hardware')->count(),
            'software' => $all->where('kategori', 'Software')->count(),
            'pembersihan' => $all->where('kategori', 'Pembersihan')->count(),
        ];

        return view('lookbook_index', compact('stats'));
    }

    public function dataTable(Request $request)
    {
        $query = LogIt::with('user')->orderBy('created_at', 'desc');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        $logs = $query->get();

        $hardwareParts = [
            'Motherboard',
            'Processor',
            'RAM',
            'PSU',
            'Kipas Prosesor',
            'Harddisk / SSD',
            'VGA Card',
            'Kabel Power / SATA',
            'Monitor',
            'Keyboard / Mouse',
            'Cek Jaringan',
            'Printer',
            'Upgrade CPU',
            'Lainnya',
        ];

        return view('lookbook_data', compact('logs', 'hardwareParts'));
    }

    public function getJson($id)
    {
        $log = LogIt::findOrFail($id);
        return response()->json($log);
    }

    public function create()
    {
        return redirect()->route('lookbook.index'); 
    }

    public function store(Request $request)
    {
        $log = new LogIt();
        $log->kategori = $request->kategori;

        if ($request->kategori == 'Hardware') {
            $log->item = is_array($request->item) ? implode(', ', $request->item) : $request->item;
            $log->aktivitas = $request->aktivitas;
        } elseif ($request->kategori == 'Software') {
            $log->item = $request->item;
            $log->aktivitas = $request->aktivitas;
        } elseif ($request->kategori == 'Pembersihan') {
            $log->unit = $request->unit;
            $log->aktivitas = $request->aktivitas;
        }

        $log->user_id = \Illuminate\Support\Facades\Auth::id();
        $log->status = $request->status;

        if ($request->hasFile('foto')) {
            $log->foto = $request->file('foto')->store('evidence', 'public');
        }

        $log->save();

        return redirect()->route('lookbook.data')->with('success', 'Data logbook berhasil ditambahkan.');
    }

    public function edit($id)
    {
        return redirect()->route('lookbook.index');
    }

    public function update(Request $request, $id)
    {
        $log = LogIt::find($id);
        $log->kategori = $request->kategori;

        if ($request->kategori == 'Hardware') {
            $log->item = is_array($request->item) ? implode(', ', $request->item) : $request->item;
            $log->aktivitas = $request->aktivitas;
            $log->unit = null;
            $log->ttd = null;
        } elseif ($request->kategori == 'Software') {
            $log->item = $request->item;
            $log->aktivitas = $request->aktivitas;
            $log->unit = null;
            $log->ttd = null;
        } elseif ($request->kategori == 'Pembersihan') {
            $log->unit = $request->unit;
            $log->aktivitas = $request->aktivitas;
            $log->item = null;
            if ($request->filled('ttd')) {
                $log->ttd = $request->ttd;
            }
        }

        $log->status = $request->status;

        if ($request->hasFile('foto')) {
            if ($log->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($log->foto)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($log->foto);
            }
            $log->foto = $request->file('foto')->store('evidence', 'public');
        }

        $log->save();

        return redirect()->route('lookbook.data')->with('success', 'Data logbook berhasil diupdate.');
    }

    public function destroy($id)
    {
        $log = LogIt::find($id);
        $log->delete();

        if (request()->ajax()) {
            return response()->json(['success' => 'Data berhasil dihapus']);
        }
        return redirect()->route('lookbook.data')->with('success', 'Data logbook berhasil dihapus.');
    }

    public function reportPdf(Request $request)
    {
        $query = LogIt::with('user')->orderBy('created_at', 'desc');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        $logs = $query->get();
        // Set paper area
        $pdf = Pdf::loadView('lookbook_pdf', compact('logs'))->setPaper('a4', 'landscape');
        return $pdf->download('laporan_logbook_it.pdf');
    }

    public function signForm($id)
    {
        $log = LogIt::findOrFail($id);
        return view('lookbook_sign_mobile', compact('log'));
    }

    public function signSave(Request $request, $id)
    {
        $log = LogIt::findOrFail($id);
        if ($request->filled('ttd')) {
            $log->ttd = $request->ttd;
            $log->save();
        }
        return response()->json(['success' => true]);
    }
}
