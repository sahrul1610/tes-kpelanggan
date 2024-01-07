<?php
namespace App\Exports;

use App\Models\KeluhanPelanggan;

class KeluhanTextExport
{
    public function exportToText()
    {
        $keluhanList = KeluhanPelanggan::all();

        $data = '';

        // Tambahkan header
        $data .= "ID\tNama\tEmail\tNomor HP\tStatus Keluhan\tKeluhan\tCreated At\tUpdated At\n";

        // Tambahkan data
        foreach ($keluhanList as $keluhan) {
            $data .= "{$keluhan->id}\t{$keluhan->nama}\t{$keluhan->email}\t{$keluhan->nomor_hp}\t{$keluhan->status_keluhan}\t{$keluhan->keluhan}\t{$keluhan->created_at}\t{$keluhan->updated_at}\n";
        }

        return $data;
    }
}
