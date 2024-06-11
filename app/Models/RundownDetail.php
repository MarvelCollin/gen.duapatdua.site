<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RundownDetail extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function rundown(){
        return $this->belongsTo(Rundown::class);
    }
}
