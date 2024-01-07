<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KeluhanPelanggan;
use App\Exports\KeluhanTextExport;
use App\Models\KeluhanStatusHis;
use Illuminate\Support\Facades\DB;
use App\Exports\KeluhanPelangganExport;
use Excel;

class KeluhanPelangganController extends Controller
{
    public function index()
    {
        $keluhanList = KeluhanPelanggan::orderBy('created_at', 'desc')->get();
        $keluhanList->transform(function ($keluhan) {
            $keluhan->status_keluhan_text = $this->getStatusText($keluhan->status_keluhan);
            return $keluhan;
        });
        return response()->json($keluhanList);

    }
    public function getStatusText($status)
    {
        switch ($status) {
            case 0:
                return 'Received';
            case 1:
                return 'In Process';
            case 2:
                return 'Done';
            default:
                return 'Unknown';
        }
    }

    public function create()
    {

    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|min:3|max:50',
            'email' => 'required|email',
            'nomor_hp' => 'nullable|numeric',
            'status_keluhan' => 'required|in:0,1,2',
            'keluhan' => 'required',
        ], [
            'nama.min' => 'Nama harus memiliki minimal 3 karakter.',
            'nama.max' => 'Nama tidak boleh lebih dari 50 karakter.',
            'nomor_hp.numeric' => 'Nomor HP harus berupa angka.',
            'keluhan.min' => 'Keluhan harus memiliki minimal 50 karakter.',
        ]);
        $validatedData['created_at'] = now();
        $validatedData['updated_at'] = now();

        $keluhan = KeluhanPelanggan::create($validatedData);
        KeluhanStatusHis::create([
            'keluhan_id' => $keluhan->id,
            'status_keluhan' => $request->status_keluhan,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        return response()->json($keluhan, 201);


    }


    public function edit($id)
    {

        $keluhan = KeluhanPelanggan::findOrFail($id);
        return response()->json($keluhan);
    }

    public function update(Request $request, $id)
    {

        $keluhan = KeluhanPelanggan::findOrFail($id);

        $validatedData = $request->validate([
            'nama' => 'required|min:3|max:50',
            'email' => 'required|email',
            'nomor_hp' => 'nullable|numeric',
            'status_keluhan' => 'required|in:0,1,2',
            'keluhan' => 'required',
        ], [
            'nama.min' => 'Nama harus memiliki minimal 3 karakter.',
            'nama.max' => 'Nama tidak boleh lebih dari 50 karakter.',
            'nomor_hp.numeric' => 'Nomor HP harus berupa angka.',
            'keluhan.min' => 'Keluhan harus memiliki minimal 50 karakter.',
        ]);

        $keluhan->update($validatedData);

        KeluhanStatusHis::create([
            'keluhan_id' => $keluhan->id,
            'status_keluhan' => $request->status_keluhan,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json($keluhan);
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            KeluhanStatusHis::where('keluhan_id', $id)->delete();
            $keluhan = KeluhanPelanggan::findOrFail($id);
            $keluhan->delete();
            DB::commit();

            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan saat menghapus keluhan.'], 500);
        }
    }

    public function history($id)
    {
        $keluhan = KeluhanPelanggan::findOrFail($id);
        $history = KeluhanStatusHis::where('keluhan_id', $id)->orderBy('updated_at', 'asc')->get();
    }

    public function export($format)
    {
        if ($format == 'txt') {
            $export = new KeluhanTextExport();
            $data = $export->exportToText();

            return response($data)
                ->header('Content-Type', 'text/plain')
                ->header('Content-Disposition', 'attachment; filename=keluhan_export.txt');
        } else {
            $fileName = 'keluhan_pelanggan_export.' . $format;

            return Excel::download(new KeluhanPelangganExport, $fileName);
        }
    }

    public function exportPdf()
    {
        $fileName = 'keluhan_pelanggan_export.pdf';

        return Excel::download(new KeluhanPelangganExport, $fileName, Writer::PDF);
    }

    public function updateStatus($id, Request $request)
    {
        $validatedData = $request->validate([
            'status_keluhan' => 'required|in:0,1,2',
        ]);

        try {
            DB::beginTransaction();

            $keluhan = KeluhanPelanggan::findOrFail($id);
            $keluhan->update(['status_keluhan' => $validatedData['status_keluhan']]);

            KeluhanStatusHis::create([
                'keluhan_id' => $keluhan->id,
                'status_keluhan' => $validatedData['status_keluhan'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return response()->json(['message' => 'Status keluhan berhasil diperbarui']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan saat memperbarui status keluhan.'], 500);
        }
    }

    public function deleteStatus($id)
    {
        try {
            DB::beginTransaction();

            $keluhan = KeluhanPelanggan::findOrFail($id);
            $keluhanStatus = KeluhanStatusHis::where('keluhan_id', $keluhan->id)->latest()->first();

            if ($keluhanStatus) {
                $keluhanStatus->delete();
            }

            DB::commit();

            return response()->json(['message' => 'Status keluhan berhasil dihapus']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan saat menghapus status keluhan.'], 500);
        }
    }

}
