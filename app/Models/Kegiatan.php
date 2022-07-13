<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use HasFactory;

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
