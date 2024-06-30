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
        $trainees = Trainee::where('status', 'active')->get();

        if ($request->has('remove_tasks')) {
            foreach ($request->remove_tasks as $taskId) {
                $taskToRemove = DailyTask::find($taskId);
                if ($taskToRemove) {
                    foreach ($trainees as $trainee) {
                        DailyTask::where('trainee_id', $trainee->id)
                            ->where('task', $taskToRemove->task)
                            ->delete();
                    }
                }
            }
        }

        foreach ($request->tasks as $taskData) {
            foreach ($trainees as $trainee) {
                if (isset($taskData['id'])) {
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
        $traineeId = $dailyTask->trainee_id;

        foreach ($request->tasks as $taskData) {
            $task = DailyTask::where('trainee_id', $traineeId)
                ->where('id', $taskData['id'])
                ->first();

            if ($task) {
                $task->update([
                    'status' => isset($taskData['status']) ? 'completed' : 'pending',
                ]);
            }
        }

        return redirect()->back();
    }

    public function resetTasks(Request $request)
    {
        $request->validate(['password' => 'required']);
        if ($request->password !== 'cium dulu') {
            return response()->json(['success' => false, 'message' => 'Invalid password']);
        }
    
        DailyTask::where('status', '!=', 'pending')->update(['status' => 'pending']);
        return response()->json(['success' => true]);
    }
    
}
