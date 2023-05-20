<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    public $timestamps = false;

    const ADMIN_PUSAT = 1;
    const ADMIN_PROV = 3;
    const ADMIN_KAB = 5;
    const KEPALA_SATKER = 8;
    const KETUA_TIM = 11;
    const STAFF = 14;
}
