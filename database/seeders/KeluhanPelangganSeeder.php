<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KeluhanPelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $numberOfRecords = 50;

        for ($i = 1; $i <= $numberOfRecords; $i++) {
            DB::table('keluhan_pelanggan')->insert([
                'nama' => 'Pelanggan' . $i,
                'email' => 'pelanggan' . $i . '@example.com',
                'nomor_hp' => '08123456789', // Change to actual numeric value
                'status_keluhan' => '0', // 0:Received
                'keluhan' => 'Keluhan panjang contoh ' . $i,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
