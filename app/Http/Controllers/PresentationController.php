<?php

namespace App\Http\Controllers;

use App\Models\Presentation;
use Illuminate\Http\Request;
use App\Models\Trainee;

class PresentationController extends Controller
{
    public function show(){
        $presentations = Presentation::with('trainee')->get();
        return view('presentation', compact('presentations'));
    }

    public function create(Request $request)
    {
        $trainee = Trainee::where('trainee_number', $request->trainee)->first();
    
        if (!$trainee) {
            return redirect()->back()->with('error', 'Trainee not found.');
        }
    
        Presentation::create([
            'trainee_id' => $trainee->id,
            'status' => $request->status,
            'subject' => $request->subject,
            'material' => $request->material,
            'comments' => $request->comments,
        ]);
    
        return redirect()->back()->with('success', 'Presentation created successfully.');
    }
    public function delete($id){
        $presentation = Presentation::findOrFail($id);
        $presentation->delete();

        return redirect()->back();
    }
}
