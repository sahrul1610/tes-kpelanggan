<?php
namespace Database\Factories;

use App\Models\KeluhanStatusHis;
use App\Models\KeluhanPelanggan;
use Illuminate\Database\Eloquent\Factories\Factory;

class KeluhanStatusHisFactory extends Factory
{
    // ...
    protected $model = KeluhanStatusHis::class;

    public function definition()
    {
        return [
            'keluhan_id' => KeluhanPelanggan::factory(),
            'status_keluhan' => $this->faker->randomElement([0, 1, 2]),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
