<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trainer;
use Illuminate\Support\Facades\Storage;

class TrainerController extends Controller
{
    public function index()
    {
        $trainers = Trainer::all();
        return view('trainer.trainer', compact('trainers'));
    }

    public function quiz(){
        $trainers = Trainer::all();
        return view('trainer.trainer_quiz', compact('trainers'));
    }

    public function store(Request $request)
    {
        if ($request->hasFile('profile')) {
            $imagePath = $request->file('profile')->store('uploads/trainer', 'public');
        } else {
            $imagePath = 'null.jpg';
        }

        $subjects = implode(', ', $request->input('subject'));

        $generation = $request->generation;
        $position = $request->position;
        $binusian = $request->binusian;
        $degree = $request->degree;

        if ($request->generation == 'other') {
            $generation = $request->other_generation;
        }
        if ($request->position == 'other') {
            $position = $request->other_position;
        }
        if ($request->binusian == 'other') {
            $binusian = $request->other_binusian;
        }
        if ($request->degree == 'other') {
            $degree = $request->other_degree;
        }

        $trainer = Trainer::create([
            'code' => $request->code,
            'name' => $request->name,
            'generation' => $generation,
            'position' => $position,
            'subject' => $subjects,
            'profile' => $imagePath,
            'binusian' => $binusian,
            'degree' => $degree
        ]);
 

        return redirect()->route('trainer.index')
            ->with('success', 'Trainer created successfully.');
    }

    public function update(Request $request, $id)
    {
        // dd($request->generation);
        $trainer = Trainer::findOrFail($id);
        $subjects = implode(', ', $request->input('subject'));
        if ($request->hasFile('profile')) {
            $imagePath = $request->file('profile')->store('uploads/trainer', 'public');

            if ($trainer->profile !== 'null.jpg') {
                Storage::disk('public')->delete($trainer->profile);
            }

            $trainer->profile = $imagePath;
        }

        $generation = $request->generation;
        $position = $request->position;
        $binusian = $request->binusian;
        $degree = $request->degree;

        if ($generation == 'other') {
            $generation = $request->other_generation;
        }
        if ($request->position == 'other') {
            $position = $request->other_position;
        }
        if ($request->binusian == 'other') {
            $binusian = $request->other_binusian;
        }
        if ($request->degree === 'other') {
            $degree = $request->other_degree;
        }

        $trainer->code = $request->code;
        $trainer->name = $request->name;
        $trainer->generation = $generation;
        $trainer->position = $position;
        $trainer->subject = $subjects;
        $trainer->binusian = $binusian;
        $trainer->degree = $degree;

        $trainer->save();

        return redirect()->route('trainer.index')
            ->with('success', 'Trainer updated successfully.');
    }


    public function destroy($id)
    {
        $trainer = Trainer::findOrFail($id);
        $trainer->delete();

        return redirect()->route('trainer.index')
            ->with('success', 'Trainer deleted successfully.');
    }
}
