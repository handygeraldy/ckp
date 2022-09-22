<?php

namespace App\Models\simtk;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kegiatan_Tim_User extends Model
{
    use HasFactory, Uuids;
    protected $guarded = ['id'];
}
