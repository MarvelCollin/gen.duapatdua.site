<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\BpprojectTeam;
use App\Models\BpprojectDetail;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::with('bpprojectTeams.bpprojectDetail')->get();
        $bpprojectTeams = BpprojectTeam::all();
        return view('bp.bpproject', compact('teams', 'bpprojectTeams'));
    }

    public function create()
    {
        return view('bp.createteam');
    }

    public function store(Request $request)
    {
        $team = Team::create($request->all());

        return redirect()->back();  
    }

    public function edit($id)
    {
        $team = Team::with('bpprojectTeams.bpprojectDetail')->findOrFail($id);

        return redirect()->back();  

    }

    public function update(Request $request, $id)
{
    $validatedData = $request->validate([
        'team_name' => 'required|string|max:255',
        'head_trainee' => 'required|string|max:255',
        'trainees' => 'required|string',
        'bpprojectTeams' => 'nullable|array', 
        'bpprojectTeams.*.project_team_id' => 'required|exists:bpproject_teams,id', 
        'bpprojectTeams.*.some_field' => 'required|string', 
    ]);

    $team = Team::findOrFail($id);

    $team->update($validatedData);

    if (!empty($validatedData['bpprojectTeams'])) {
        foreach ($validatedData['bpprojectTeams'] as $projectTeamId => $projectTeamData) {
            $projectTeam = BpprojectTeam::findOrFail($projectTeamId);

            $projectTeam->update($projectTeamData);
        }
    }

    return redirect()->back();
}


    public function destroy($id)
    {
        $team = Team::findOrFail($id);
        $team->delete();

        return redirect()->back();  

     }
}

