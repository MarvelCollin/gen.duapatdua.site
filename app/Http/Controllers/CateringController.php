<?php

namespace App\Http\Controllers;

use App\Models\Catering;
use App\Models\Trainee;
use Illuminate\Http\Request;

class CateringController extends Controller
{
    public function show()
    {
        $caterings = Catering::all();
        $trainees = Trainee::where('status', 'active')->get();
        return view('catering.catering', compact('caterings', 'trainees'));
    }

    public function updateCatering(Request $request) {
        $traineeNumbers = $request->input('trainee_number');
        $bookCaterings = $request->input('bookCatering');
    
        foreach ($traineeNumbers as $index => $traineeNumber) {
            $trainee = Trainee::where('trainee_number', $traineeNumber)->first();
            
            if ($trainee) {
                $trainee->bookCatering = $bookCaterings[$index];
                $trainee->save();
            }
        }
    
        return redirect()->back()->with('success', 'Catering preferences updated successfully.');
    }
}
