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
        return view('bp.bpteam', compact('teams', 'bpprojectTeams'));
    }

    public function show($id)
    {
        $team = Team::with('bpprojectTeams.bpprojectDetail')->findOrFail($id);

        return view('bp.bpteam', compact('team'));
    }

    public function create()
    {
        return view('bp.createteam');
    }

    public function store(Request $request)
    {
        $team = Team::create($request->all());

        return redirect()->route('teams.index')->with('success', 'Team created successfully!');
    }

    public function edit($id)
    {
        $team = Team::with('bpprojectTeams.bpprojectDetail')->findOrFail($id);

        return view('bp.editeam', compact('team'));
    }

    public function update(Request $request, $id)
    {
        $team = Team::findOrFail($id);
        $team->update($request->all());

        foreach ($request->bpprojectTeams as $projectTeamId => $projectTeamData) {
            $projectTeam = BpprojectTeam::findOrFail($projectTeamId);
            $projectTeam->update($projectTeamData);
        }

        return redirect()->route('teams.show', $id)->with('success', 'Team updated successfully!');
    }

    public function updateBpprojectTeam(Request $request, $id)
    {
        $bpprojectTeam = BpprojectTeam::findOrFail($id);
        $bpprojectTeam->update([
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Bpproject Team updated successfully!');
    }
}
