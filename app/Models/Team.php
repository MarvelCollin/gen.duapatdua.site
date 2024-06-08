<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    public function bpprojectDetails()
    {
        return $this->hasMany(BpprojectDetail::class);
    }

    public function teamDetails()
    {
        return $this->hasMany(TeamDetail::class);
    }
}
