<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trainee;
use Illuminate\Support\Facades\Storage;

class TraineeController extends Controller
{
    public function index()
    {
        $trainee = Trainee::all();
        return view('trainee.trainee', compact('trainee'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'trainee_number' => 'required|string|starts_with:T0',
            'name' => 'required|string',
            'degree' => 'required|string',
            'binusian' => 'required|string',
            'profile' => 'required|image'
        ]);

        if ($request->hasFile('profile')) {
            $imagePath = $request->file('profile')->store('uploads/trainee', 'public');
        } else {
            $imagePath = 'null.jpg';
        }

        Trainee::create([
            'trainee_number' => $request->trainee_number,
            'name' => $request->name,
            'degree' => $request->degree,
            'status' => 'active',
            'binusian' => $request->binusian,
            'profile' => $imagePath,
            'totalForum' => 0,
            'totalAcq' => 0
        ]);

        return redirect()->route('trainee.index')
            ->with('success', 'Trainee created successfully.');
    }
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'trainee_number' => 'required|string|starts_with:T0',
            'name' => 'required|string',
            'degree' => 'required|string',
            'binusian' => 'required|string',
            'profile' => 'image'
        ]);

        $trainee = Trainee::findOrFail($id);

        if ($request->hasFile('profile')) {
            $imagePath = $request->file('profile')->store('uploads/trainee', 'public');

            if ($trainee->profile !== 'null.jpg') {
                Storage::disk('public')->delete($trainee->profile);
            }

            $trainee->profile = $imagePath;
        }

        $trainee->trainee_number = $request->trainee_number;
        $trainee->name = $request->name;
        $trainee->degree = $request->degree;
        $trainee->binusian = $request->binusian;
        $trainee->status = $request->status;

        $trainee->save();

        return redirect()->route('trainee.index')
            ->with('success', 'Trainee updated successfully.');
    }


    public function destroy($id)
    {
        $trainee = Trainee::findOrFail($id);
        $trainee->delete();

        return redirect()->route('trainee.index')
            ->with('success', 'Trainee deleted successfully.');
    }


    public function showAcq()
    {
        $trainees = Trainee::all();

        return view('acq', compact('trainees'));
    }

    public function editTotalAcq(Request $request, $id)
    {
        $trainee = Trainee::findOrFail($id);
        $trainee->totalAcq = $request->input('totalAcq');
        $trainee->save();

        return redirect()->route('showAcq')
            ->with('success', 'Berhasil update kenalannya kak !');
    }
}
