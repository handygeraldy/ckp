<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fungsional extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    protected $guarded = ['id'];

    public function jafung()
    {
        return $this->belongsTo(Jafung::class);
    }
}
