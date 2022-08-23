<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class Kegiatan extends Model
{
    use HasFactory, Uuids;

    protected $guarded = ['id'];

    

    public function ckp()
    {
        return $this->belongsTo(Ckp::class);
    }

    public function tim()
    {
        return $this->belongsTo(Tim::class);
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }

    public function kredit()
    {
        return $this->belongsTo(Kredit::class);
    }
}
