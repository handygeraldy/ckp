<?php

namespace App\Models\simtk;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KegiatanTim extends Model
{
    use HasFactory, Uuids;
    protected $guarded = ['id'];
}
