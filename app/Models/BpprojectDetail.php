<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BpprojectDetail extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function bpproject()
    {
        return $this->belongsTo(Bpproject::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
