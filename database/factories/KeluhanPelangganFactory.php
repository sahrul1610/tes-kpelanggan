<?php

namespace Database\Factories;

use App\Models\KeluhanPelanggan;
use Illuminate\Database\Eloquent\Factories\Factory;

class KeluhanPelangganFactory extends Factory
{
    protected $model = KeluhanPelanggan::class;

    public function definition()
    {
        return [
            'nama' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'nomor_hp' => "08877777",
            'status_keluhan' => $this->faker->randomElement([0, 1, 2]),
            'keluhan' => $this->faker->sentence,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
