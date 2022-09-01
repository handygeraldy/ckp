<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Satker extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = ['id'];

    

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
