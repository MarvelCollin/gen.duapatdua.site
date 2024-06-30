<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyTask extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function trainee(){
        return $this->belongsTo(Trainee::class, 'trainee_id');
    }
}
