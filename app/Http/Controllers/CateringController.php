<?php

namespace App\Http\Controllers;

use App\Models\Catering;
use App\Models\Trainee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CateringController extends Controller
{
    private $resetPassword = 'kevinganteng';
    public function show()
    {
        $caterings = Catering::all();
        $trainees = Trainee::where('status', 'active')->get();
        return view('catering.catering', compact('caterings', 'trainees'));
    }

    public function updateCatering(Request $request)
    {
        $traineeNumbers = $request->input('trainee_number');
        $bookCaterings = $request->input('bookCatering');
    
        foreach ($traineeNumbers as $index => $traineeNumber) {
            $trainee = Trainee::where('trainee_number', $traineeNumber)->first();
    
            if ($trainee) {
                $trainee->bookCatering = isset($bookCaterings[$traineeNumber]) ? 'accept' : 'decline';
                $trainee->save();
            }
        }
    
        return redirect()->back()->with('success', 'Catering preferences updated successfully.');
    }
    

    public function resetBookCatering(Request $request)
    {
        if ($request->input('reset_password') !== $this->resetPassword) {
            return redirect()->back()->with('error', 'Mending lu tanya pic catering.');
        }

        $caterings = Trainee::where('bookCatering', 'accept')->get();

        foreach ($caterings as $catering) {
            $catering->totalCatering = $catering->totalCatering + 1;
            $catering->save();
        }

        DB::table('trainees')->update(['bookCatering' => null]);
        return redirect()->back()->with('success', 'Recap Catering berhasil');
    }

    public function resetTotalCatering(Request $request)
    {
        if ($request->input('reset_password') !== $this->resetPassword) {
            return redirect()->back()->with('error', 'Mending lu tanya pic catering.');
        }

        DB::table('trainees')->update(['totalCatering' => 0]);
        return redirect()->back()->with('success', 'Reset catering data berhasil');
    }
}
