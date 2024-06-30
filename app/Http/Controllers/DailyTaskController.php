<?php

namespace App\Http\Controllers;

use App\Models\DailyTask;
use App\Models\Trainee;
use Illuminate\Http\Request;

class DailyTaskController extends Controller
{
    public function show()
    {
        $dailyTasks = DailyTask::with('trainee')->get()->groupBy('trainee_id');
        $trainees = Trainee::where('status', 'active')->get();

        return view('dailytask', compact('dailyTasks', 'trainees'));
    }

    public function create(Request $request)
    {
     // Validate the request
     $request->validate([
        'tasks' => 'array',
        'tasks.*.task' => 'required|string|max:255',
    ]);

    // Retrieve all active trainees
    $trainees = Trainee::where('status', 'active')->get();

    // Remove selected tasks
    if ($request->has('remove_tasks')) {
        foreach ($request->remove_tasks as $taskId) {
            DailyTask::where('id', $taskId)->delete();
        }
    }

    // Add or update tasks
    foreach ($request->tasks as $taskData) {
        foreach ($trainees as $trainee) {
            if (isset($taskData['id'])) {
                // Update existing task
                $task = DailyTask::where('trainee_id', $trainee->id)
                                 ->where('id', $taskData['id'])
                                 ->first();
                if ($task) {
                    $task->update([
                        'task' => $taskData['task'],
                        'status' => isset($taskData['status']) ? 'completed' : 'pending',
                    ]);
                }
            } else {
                // Create new task
                DailyTask::create([
                    'trainee_id' => $trainee->id,
                    'task' => $taskData['task'],
                    'status' => isset($taskData['status']) ? 'completed' : 'pending',
                ]);
            }
        }
    }

    return redirect()->back()->with('success', 'Tasks updated successfully for all active trainees.');
    }
    
    

    public function update(Request $request, $id)
    {
        $dailyTask = DailyTask::findOrFail($id);

        foreach ($request->tasks as $taskData) {
            $task = DailyTask::where('trainee_id', $dailyTask->trainee_id)
                ->where('task', $taskData['task'])
                ->first();

            if ($task) {
                $task->update([
                    'status' => $taskData['status'],
                ]);
            }
        }

        return redirect()->route('dailytask.index')->with('success', 'Tasks updated successfully.');
    }
}
