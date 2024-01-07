<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeluhanPelanggan extends Model
{
    use HasFactory;
    protected $table = "keluhan_pelanggan";

    protected $guarded = [''];

    public $timestamps = false;

}
