<?php

namespace App\Http\Controllers;

use App\Models\DailyTask;
use Illuminate\Http\Request;

class DailyTaskController extends Controller
{
    public function show(){
        $dailyTasks = DailyTask::all();

        return view('dailytask', compact('dailyTasks'));
    }
}
