<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeluhanStatusHis extends Model
{
    use HasFactory;
    protected $table = "keluhan_status_his";

    protected $guarded = [''];

    public $timestamps = false;
}
