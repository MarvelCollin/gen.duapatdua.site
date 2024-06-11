<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bpproject;
use App\Models\Trainee;
use App\Models\BpprojectSubtitle;
use App\Models\BpprojectDetail;
use App\Models\BpprojectTeam;
use App\Models\Team;

class BpController extends Controller
{
    public function index()
    {
        $bpprojects = Bpproject::all();
        $bpprojectSubtitles = BpprojectSubtitle::all();
        $bpprojectDetails = BpprojectDetail::all();
        $bpprojectTeams = BpprojectTeam::all();
        $teams = Team::all();
        return view('bp.bpproject', compact('bpprojects', 'bpprojectSubtitles', 'bpprojectDetails', 'bpprojectTeams', 'teams'));
    }


    public function create()
    {
        return view('bpprojects.create');
    }

    public function store(Request $request)
    {
        $bpproject = Bpproject::create([
            'subject' => $request->subject,
            'bptitle' => $request->bptitle,
            'bpnotes' => '-',
        ]);

        $subtitles = $request->subtitles;

        $activeTrainees = Trainee::where('status', 'active')->get();

        foreach ($activeTrainees as $trainee) {
            $bpprojectDetail = BpprojectDetail::create([
                'bpproject_id' => $bpproject->id,
                'trainee_id' => $trainee->id,
            ]);

            foreach ($subtitles as $subtitle) {
                BpprojectSubtitle::create([
                    'bpproject_detail_id' => $bpprojectDetail->id,
                    'subtitle' => $subtitle,
                    'percentage' => '0',
                ]);
            }
        }

        foreach ($subtitles as $subtitle) {
            BpprojectTeam::create([
                'bpproject_id' => $bpproject->id,
                'subtitle' => $subtitle,
                'percentage' => '0',
                'team_id' => null,
                'external_trainee' => ' ',
                'notes' => '-'
            ]);
        }

        return redirect()->route('bpprojects.index')->with('success', 'BP Project created successfully!');
    }

    public function show($id)
    {
        $bpproject = Bpproject::findOrFail($id);
        $bpprojectDetails = BpprojectDetail::where('bpproject_id', $id)->with('subtitles', 'trainee')->get();

        return view('bp.bptrainee', compact('bpproject', 'bpprojectDetails'));
    }

    public function edit(Request $request, $id)
    {
        $bpproject = Bpproject::findOrFail($id);

        foreach ($request->subtitles as $subtitleId => $subtitleData) {
            $subtitle = BpprojectSubtitle::findOrFail($subtitleId);

            if (array_key_exists('subtitle', $subtitleData)) {
                $subtitle->subtitle = $subtitleData['subtitle'];
            }

            if (array_key_exists('percentage', $subtitleData)) {
                $subtitle->percentage = $subtitleData['percentage'];
            }

            $subtitle->save();
        }

        return redirect()->route('bpprojects.show', $id)->with('success', 'Subtitles updated successfully.');
    }

    public function update(Request $request, $id)
    {
        $bpproject = Bpproject::findOrFail($id);
        $bpproject->update([
            'subject' => $request->subject,
            'bptitle' => $request->bptitle,
            'bpnotes' => $request->bpnotes,
        ]);

        $subtitles = $request->subtitles;

        $activeTrainees = Trainee::where('status', 'active')->get();

        foreach ($activeTrainees as $trainee) {
            $bpprojectDetail = BpprojectDetail::updateOrCreate(
                ['bpproject_id' => $bpproject->id, 'trainee_id' => $trainee->id],
                ['bpproject_id' => $bpproject->id, 'trainee_id' => $trainee->id]
            );

            $existingSubtitles = $bpprojectDetail->subtitles()->pluck('subtitle')->toArray();
            $newSubtitles = array_diff($subtitles, $existingSubtitles);

            foreach ($newSubtitles as $subtitle) {
                BpprojectSubtitle::create([
                    'bpproject_detail_id' => $bpprojectDetail->id,
                    'subtitle' => $subtitle,
                    'percentage' => '0'
                ]);
            }

            BpprojectSubtitle::where('bpproject_detail_id', $bpprojectDetail->id)
                ->whereNotIn('subtitle', $subtitles)
                ->delete();
        }

        return redirect()->route('bpprojects.index')->with('success', 'BP Project updated successfully!');
    }

    public function showDetails($id)
    {
        $teams = Team::all();
        $bpProject = BpProject::findOrFail($id);
        $bpProjectTeams = BpProjectTeam::where('bpproject_id', $id)->get();
        return view('bp.bpteams', compact('teams', 'bpProject', 'bpProjectTeams'));
    }

    public function updateSubtitle(Request $request, $id)
    {
        $validatedData = $request->validate([
            'percentage' => 'required|string|in:0,25,50,75,100',
            'external_trainee' => 'nullable|string|max:255',
            'team_id' => 'nullable|exists:teams,id',
        ]);

        $projectTeam = BpprojectTeam::findOrFail($id);

        $projectTeam->update([
            'percentage' => $validatedData['percentage'],
            'external_trainee' => $validatedData['external_trainee'],
            'team_id' => $validatedData['team_id'],
            'notes' => $request->notes
        ]);

        return redirect()->back()->with('success', 'Project team updated successfully!');
    }
}
