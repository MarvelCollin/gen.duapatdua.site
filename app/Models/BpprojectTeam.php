<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BpprojectTeam extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function bpprojectDetail()
    {
        return $this->belongsTo(BpprojectDetail::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
