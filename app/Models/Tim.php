<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tim extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function satker()
    {
        return $this->belongsTo(Satker::class);
    }

    public function periodetim()
    {
        return $this->hasMany(PeriodeTim::class);
    }
}
