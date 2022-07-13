<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Satker extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    

    public function pimpinan()
    {
        return $this->belongsTo(User::class, 'pimpinan_id', 'id');
    }
}