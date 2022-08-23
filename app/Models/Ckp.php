<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
class Ckp extends Model
{
    use HasFactory, Uuids;

    protected $guarded = ['id'];

    public function kegiatan()
    {
        return $this->hasMany(kegiatan::class);
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
