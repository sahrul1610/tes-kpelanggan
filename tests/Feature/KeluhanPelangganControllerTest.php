<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\KeluhanPelanggan;
use App\Models\KeluhanStatusHis;

class KeluhanPelangganControllerTest extends TestCase
{
    use RefreshDatabase;
    public function testCreateKeluhan()
    {
        $keluhanData = [
            'nama' => 'John Doe',
            'email' => 'john.doe@example.com',
            'nomor_hp' => '081234567890',
            'status_keluhan' => 0,
            'keluhan' => 'This is a sample complaint.',
        ];

        $response = $this->post('/api/keluhan-pelanggan/add', $keluhanData);

        $response->assertStatus(201)
            ->assertJson(['nama' => $keluhanData['nama']])
            ->assertJson(['email' => $keluhanData['email']])
            ->assertJson(['nomor_hp' => $keluhanData['nomor_hp']])
            ->assertJson(['status_keluhan' => $keluhanData['status_keluhan']])
            ->assertJson(['keluhan' => $keluhanData['keluhan']]);

        $createdKeluhan = KeluhanPelanggan::where('nama', $keluhanData['nama'])->first();
        $this->assertNotNull($createdKeluhan);
        $this->assertEquals($keluhanData['email'], $createdKeluhan->email);
        $this->assertEquals($keluhanData['nomor_hp'], $createdKeluhan->nomor_hp);
        $this->assertEquals($keluhanData['status_keluhan'], $createdKeluhan->status_keluhan);
        $this->assertEquals($keluhanData['keluhan'], $createdKeluhan->keluhan);
    }

    public function testUpdateStatus()
    {
        $keluhan = KeluhanPelanggan::factory()->create();

        $response = $this->put("/api/keluhan-pelanggan/{$keluhan->id}/update-status", [
            'status_keluhan' => 1,
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Status keluhan berhasil diperbarui']);
        $updatedKeluhan = KeluhanPelanggan::find($keluhan->id);
        $this->assertEquals(1, $updatedKeluhan->status_keluhan);
        $latestHistory = KeluhanStatusHis::where('keluhan_id', $keluhan->id)->latest()->first();
        $this->assertEquals(1, $latestHistory->status_keluhan);
    }

    public function testDeleteStatus()
    {
        $keluhan = KeluhanPelanggan::factory()->create([
            'nomor_hp' => '081234567890',
        ]);
        KeluhanStatusHis::create([
            'keluhan_id' => $keluhan->id,
            'status_keluhan' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $response = $this->delete("/api/keluhan-pelanggan/{$keluhan->id}/delete-status");
        $response->assertStatus(200)
            ->assertJson(['message' => 'Status keluhan berhasil dihapus']);
        $latestHistory = KeluhanStatusHis::where('keluhan_id', $keluhan->id)->latest()->first();
        if ($latestHistory) {
            $this->assertNull($latestHistory->status_keluhan);
        }
    }


}
