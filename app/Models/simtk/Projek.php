<?php

namespace App\Models\simtk;

use App\Models\PeriodeTim;
use App\Models\simtk\KegiatanTim;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projek extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function kegiatan_tim()
    {
        return $this->hasMany(KegiatanTim::class);
    }

    public function tim()
    {
        return $this->belongsTo(Tim::class, 'tim_id', 'id');
    }
    public function periodetim()
    {
        return $this->belongsTo(PeriodeTim::class, 'periode_tim_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'ketua_id', 'id');
    }
}
