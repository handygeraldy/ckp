<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodeTim extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function tim()
    {
        return $this->belongsTo(Tim::class, 'tim_id', 'id');
    }
}
