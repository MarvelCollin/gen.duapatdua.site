<?php

namespace App\Http\Controllers;

use App\Models\Bpproject;
use App\Models\BpprojectDetail;
use Illuminate\Http\Request;

class BpController extends Controller
{
    public function index()
    {
        $bpprojects = Bpproject::all();
        $bpprojectDetails = BpprojectDetail::all();
        return view('bp.bpproject', compact('bpprojects', 'bpprojectDetails'));
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
        ]);

        $subtitles = $request->subtitles;

        foreach ($subtitles as $subtitle) {
            BpprojectDetail::create([
                'bpproject_id' => $bpproject->id,
                'title' => $subtitle,
                'team_id' => null
            ]);
        }

        return redirect()->route('bpprojects.index')->with('success', 'Bpproject created successfully!');
    }

    public function show($id)
    {
        $bpproject = Bpproject::findOrFail($id);
        $bpprojectDetails = BpprojectDetail::where('bpproject_id', $id)->get();

        return view('bpprojects.show', compact('bpproject', 'bpprojectDetails'));
    }

    public function edit($id)
    {
        $bpproject = Bpproject::findOrFail($id);
        return view('bpprojects.edit', compact('bpproject'));
    }

    public function update(Request $request, $id)
    {
        $bpproject = Bpproject::findOrFail($id);
        $bpproject->update([
            'subject' => $request->subject,
            'bptitle' => $request->bptitle,
        ]);

        return redirect()->route('bpprojects.index')->with('success', 'Bpproject updated successfully!');
    }

    public function destroy($id)
    {
        $bpproject = Bpproject::findOrFail($id);
        $bpproject->delete();

        return redirect()->route('bpprojects.index')->with('success', 'Bpproject deleted successfully!');
    }
}
