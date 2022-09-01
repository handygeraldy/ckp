<?php

namespace App\Models\ckp;

use App\Models\User;
use App\Traits\Uuids;
use App\Models\Satker;
use App\Models\ckp\Kegiatan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ckp extends Model
{
    use HasFactory, Uuids;

    protected $guarded = ['id'];

    public function kegiatan()
    {
        return $this->hasMany(Kegiatan::class);
    }

    public function satker()
    {
        return $this->belongsTo(Satker::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
