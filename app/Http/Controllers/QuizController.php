<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trainee;

class QuizController extends Controller
{
    public function showTraineeQuiz()
    {
        $trainee = Trainee::all();
        return view('trainee.trainee_quiz', compact('trainee'));
    }
}
