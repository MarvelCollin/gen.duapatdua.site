<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rundown;
use App\Models\RundownDetail;

class RundownController extends Controller
{
    public function index()
    {
        $rundowns = Rundown::all();
        $rundownDetails = RundownDetail::all();
        return view('bp.bprundown', compact('rundowns', 'rundownDetails'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string',
            'day' => 'required|string',
            'start.*' => 'required|string',
            'end.*' => 'required|string',
            'activity.*' => 'required|string',
        ]);
    
        $rundown = Rundown::create([
            'subject' => $request->subject,
            'day' => $request->day,
        ]);
    
        $starts = is_array($request->start) ? $request->start : [$request->start];
        $ends = is_array($request->end) ? $request->end : [$request->end];
        $activities = is_array($request->activity) ? $request->activity : [$request->activity];
    
        foreach ($starts as $key => $start) {
            RundownDetail::create([
                'rundown_id' => $rundown->id,
                'start' => $starts[$key],
                'end' => $ends[$key],
                'activity' => $activities[$key],
            ]);
        }
    
        return redirect()->back()->with('success', 'Rundown created successfully.');
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'subject' => 'required|string',
            'day' => 'required|string',
            'edit_start.*' => 'required|string',
            'edit_end.*' => 'required|string',
            'edit_activity.*' => 'required|string',
        ]);
    
        $rundown = Rundown::findOrFail($id);
    
        $rundown->update([
            'subject' => $request->subject,
            'day' => $request->day,
        ]);

        if ($request->filled('edit_start')) {
            $rundown->details()->delete();
    
            foreach ($request->edit_start as $index => $start) {
                $rundown->details()->create([
                    'start' => $start,
                    'end' => $request->edit_end[$index],
                    'activity' => $request->edit_activity[$index],
                ]);
            }
        } else {
            $rundown->delete();
        }
    
        return redirect()->back()->with('success', 'Rundown updated successfully.');
    }
    
    
    
    
    public function destroy(Rundown $rundown)
    {
        $rundown->details()->delete();
        $rundown->delete();
        return redirect()->back()->with('success', 'Rundown deleted successfully.');
    }
}
