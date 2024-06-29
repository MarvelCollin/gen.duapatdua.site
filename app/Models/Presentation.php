<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presentation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function trainee(){
        return $this->belongsTo(Trainee::class);
    }
}
