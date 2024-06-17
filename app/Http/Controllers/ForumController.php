<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Forum;
use App\Models\Trainee;
use Illuminate\Support\Facades\Hash;

class ForumController extends Controller
{
    public function show()
    {
        $forums = Forum::all();
        $trainee = Trainee::where('status', 'active')->get();

        return view('forum', compact('forums', 'trainee'));
    }

    public function shuffle(Request $request)
    {
        if (!Hash::check($request->password, bcrypt('242gacor'))) {
            return redirect()->back()->with('error', 'Incorrect password.');
        }
        $trainees = Trainee::all();
        $traineeCount = $trainees->count();

        $forums = Forum::whereIn('forum_status', ['unshuffle', 'no'])->get();
        $forums = $forums->shuffle();

        $forumsPerTrainee = $forums->count() / $traineeCount;
        $forumsAssignedCount = [];

        foreach ($forums as $index => $forum) {
            $traineeIndex = $index % $traineeCount;
            $trainee = $trainees[$traineeIndex];

            $forum->trainee_id = $trainee->id;
            $forum->forum_status = 'no';
            $forum->save();

            if (!isset($forumsAssignedCount[$traineeIndex])) {
                $forumsAssignedCount[$traineeIndex] = 1;
            } else {
                $forumsAssignedCount[$traineeIndex]++;
            }

            if ($forumsAssignedCount[$traineeIndex] > $forumsPerTrainee) {
                $minAssignedCount = min($forumsAssignedCount);
                $minTraineeIndex = array_search($minAssignedCount, $forumsAssignedCount);

                if ($traineeIndex != $minTraineeIndex) {
                    $forum->trainee_id = $trainees[$minTraineeIndex]->id;
                    $forum->save();

                    $forumsAssignedCount[$minTraineeIndex]++;
                    $forumsAssignedCount[$traineeIndex]--;
                }
            }
        }

        return redirect()->route('showForum');
    }

    public function store(Request $request)
    {
        $request->validate([
            'link' => 'required|unique:forums|max:255',
        ]);

        Forum::create([
            'link' => $request->link,
            'trainee_id' => null,
            'forum_status' => 'unshuffle'
        ]);

        return redirect()->route('showForum')
            ->with('success', 'Forum link created successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $forum = Forum::findOrFail($id);

        $currentStatus = $forum->forum_status;

        $trainee = Trainee::find($forum->trainee_id);

        $forum->forum_status = $request->has('forum_status') ? 'yes' : 'no';
        $forum->save();

        if ($currentStatus != $forum->forum_status) {
            $trainee->totalforum = Forum::where('trainee_id', $forum->trainee_id)
                ->where('forum_status', 'yes')
                ->count();

            $trainee->save();
        }

        return redirect()->route('showForum')
            ->with('success', 'Forum status updated successfully.');
    }

    public function updateLink(Request $request, $id)
    {
        $request->validate([
            'link' => 'required|url',
        ]);

        $forum = Forum::findOrFail($id);

        $forum->link = $request->input('link');
        $forum->save();

        return redirect()->back()->with('success', 'Forum link updated successfully');
    }

    public function deleteForum($id)
    {
        $forum = Forum::findOrFail($id);
        $traineeId = $forum->trainee_id;

        $forum->delete();

        $trainee = Trainee::find($traineeId);
        $trainee->totalforum = Forum::where('trainee_id', $traineeId)->where('forum_status', 'yes')->count();
        $trainee->save();

        return redirect()->back()->with('success', 'Forum deleted successfully.');
    }
}
