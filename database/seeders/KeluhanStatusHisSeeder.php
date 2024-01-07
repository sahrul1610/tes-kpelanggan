<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KeluhanStatusHisSeeder extends Seeder
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
            DB::table('keluhan_status_his')->insert([
                'keluhan_id' => $i,
                'status_keluhan' => '0', // 0:Received
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
