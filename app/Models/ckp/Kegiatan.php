<?php

namespace App\Models\ckp;

use App\Models\Tim;
use App\Traits\Uuids;
use App\Models\Kredit;
use App\Models\Satuan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
