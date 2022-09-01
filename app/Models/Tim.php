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

    public function user()
    {
        return $this->belongsTo(User::class, 'ketua_id', 'id');
    }
}
