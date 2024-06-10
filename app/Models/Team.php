<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function bpprojectDetails()
    {
        return $this->hasMany(BpprojectDetail::class);
    }

    public function subtitles()
    {
        return $this->hasMany(BpprojectSubtitle::class, 'team_id');
    }

    public function bpprojectTeams()
{
    return $this->hasMany(BpprojectTeam::class);
}

}
