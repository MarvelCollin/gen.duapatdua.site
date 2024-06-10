<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bpproject;
use App\Models\Trainee;
use App\Models\BpprojectSubtitle;
use App\Models\BpprojectDetail;
use App\Models\BpprojectTeam;

class BpController extends Controller
{
    public function index()
    {
        $bpprojects = Bpproject::all();
        $bpprojectSubtitles = BpprojectSubtitle::all();
        $bpprojectDetails = BpprojectDetail::all();
        return view('bp.bpproject', compact('bpprojects', 'bpprojectSubtitles', 'bpprojectDetails'));
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
                    'percentage' => '0'
                ]);

                BpprojectTeam::create([
                    'bpproject_detail_id' => $bpprojectDetail->id,
                    'subtitle' => $subtitle,
                    'percentage' => '0',
                    'team_id' => null,
                    'notes' => '-'
                ]);
            }
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
}
