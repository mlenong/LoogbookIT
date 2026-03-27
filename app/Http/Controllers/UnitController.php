<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::orderBy('nama', 'asc')->get();
        return view('unit_index', compact('units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|unique:units,nama',
            'keterangan' => 'nullable|string',
        ]);

        Unit::create([
            'nama' => $request->nama,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('units.index')->with('success', 'Gedung/Unit berhasil ditambahkan!');
    }

    public function show($id)
    {
        $unit = Unit::findOrFail($id);
        return response()->json($unit);
    }

    public function update(Request $request, $id)
    {
        $unit = Unit::findOrFail($id);
        
        $request->validate([
            'nama' => 'required|string|unique:units,nama,' . $unit->id,
            'keterangan' => 'nullable|string',
        ]);

        $unit->update([
            'nama' => $request->nama,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('units.index')->with('success', 'Data Unit berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $unit = Unit::findOrFail($id);
        $unit->delete();
        return response()->json(['success' => 'Unit berhasil dihapus!']);
    }

    // API For Select2
    public function getApi()
    {
        $units = Unit::orderBy('nama', 'asc')->get();
        $formatted = [];
        foreach($units as $unit) {
            $text = $unit->nama;
            if ($unit->keterangan) {
                $text .= ' (' . $unit->keterangan . ')';
            }
            $formatted[] = ['id' => $unit->nama, 'text' => $text];
        }
        return response()->json($formatted);
    }
}
