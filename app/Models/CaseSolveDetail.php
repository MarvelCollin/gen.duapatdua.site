<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseSolveDetail extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function trainee()
    {
        return $this->belongsTo(Trainee::class);
    }

    public function caseSolve()
    {
        return $this->belongsTo(CaseSolve::class);
    }

    public function caseSubtitles()
    {
        return $this->hasMany(CaseSubtitle::class);
    }
}

