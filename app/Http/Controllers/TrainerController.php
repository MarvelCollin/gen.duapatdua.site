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

    public function store(Request $request)
    {
        if ($request->hasFile('profile')) {
            $imagePath = $request->file('profile')->store('uploads/trainer', 'public');
        } else {
            $imagePath = 'null.jpg';
        }
        $subjects = implode(', ', $request->input('subject'));
        Trainer::create([
            'code' => $request->code,
            'name' => $request->name,
            'generation' => $request->generation,
            'position' => $request->position,
            'subject' => $subjects,
            'profile' => $imagePath
        ]);

        return redirect()->route('trainer.index')
            ->with('success', 'Trainer created successfully.');
    }

    public function update(Request $request, $id)
    {
        $trainer = Trainer::findOrFail($id);
        $subjects = implode(', ', $request->input('subject'));
        if ($request->hasFile('profile')) {
            $imagePath = $request->file('profile')->store('uploads/trainer', 'public');

            if ($trainer->profile !== 'null.jpg') {
                Storage::disk('public')->delete($trainer->profile);
            }

            $trainer->profile = $imagePath;
        }

        $trainer->code = $request->code;
        $trainer->name = $request->name;
        $trainer->generation = $request->generation;
        $trainer->position = $request->position;
        $trainer->subject = $subjects;

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
