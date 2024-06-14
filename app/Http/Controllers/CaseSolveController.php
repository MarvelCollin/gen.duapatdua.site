<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CaseSolve;
use App\Models\Trainee;
use App\Models\CaseSubtitle;
use App\Models\CaseSolveDetail;
use Illuminate\Support\Facades\DB;

class CaseSolveController extends Controller
{
    public function index()
    {
        $caseSolves = CaseSolve::all();
        $caseSubtitles = CaseSubtitle::all();
        $caseSolveDetails = CaseSolveDetail::all();
        return view('casesolve', compact('caseSolves', 'caseSubtitles', 'caseSolveDetails'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:case_solves',
            'subject' => 'required',
            'session' => 'required',
            'subtitles' => 'required|array',
            'subtitles.*' => 'required|string',
        ]);

        DB::beginTransaction();
        
        try {
            $caseSolve = CaseSolve::create([
                'title' => $request->title,
                'subject' => $request->subject,
                'session' => $request->session,
            ]);

            $subtitles = $request->subtitles;
            $activeTrainees = Trainee::where('status', 'active')->get();

            foreach ($activeTrainees as $trainee) {
                $caseSolveDetail = CaseSolveDetail::create([
                    'case_solve_id' => $caseSolve->id,
                    'trainee_id' => $trainee->id,
                ]);

                foreach ($subtitles as $subtitle) {
                    CaseSubtitle::create([
                        'case_solve_detail_id' => $caseSolveDetail->id,
                        'subtitle' => $subtitle,
                        'percentage' => '0'
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('casesolve.index')->with('success', 'Case solvenya berhasil dibuat kak !');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Sepertinya lagi error, boleh kabarin angkatannya ya kak' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $caseSolve = CaseSolve::findOrFail($id);
        $caseSolveDetails = CaseSolveDetail::where('case_solve_id', $id)->with('caseSubtitles')->get();

        return view('progress', compact('caseSolve', 'caseSolveDetails'));
    }

    public function edit(Request $request, $id)
    {
        $caseSolve = CaseSolve::findOrFail($id);

        DB::beginTransaction();
        
        try {
            foreach ($request->subtitles as $subtitleId => $subtitleData) {
                $subtitle = CaseSubtitle::findOrFail($subtitleId);

                if (array_key_exists('subtitle', $subtitleData)) {
                    $subtitle->subtitle = $subtitleData['subtitle'];
                }

                if (array_key_exists('percentage', $subtitleData)) {
                    $subtitle->percentage = $subtitleData['percentage'];
                }

                $subtitle->save();
            }

            DB::commit();
            return redirect()->route('casesolve.show', $id)->with('success', 'Berhasil update subtitlenya kak!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Sepertinya lagi error, boleh kabarin angkatannya ya kak' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $caseSolve = CaseSolve::findOrFail($id);

        $request->validate([
            'title' => 'required',
            'subject' => 'required',
            'session' => 'required',
            'subtitles' => 'required|array',
            'subtitles.*' => 'required|string',
        ]);

        DB::beginTransaction();
        
        try {
            $caseSolve->update([
                'title' => $request->title,
                'subject' => $request->subject,
                'session' => $request->session,
            ]);

            $subtitles = $request->subtitles;
            $activeTrainees = Trainee::where('status', 'active')->get();

            foreach ($activeTrainees as $trainee) {
                $caseSolveDetail = CaseSolveDetail::updateOrCreate(
                    ['case_solve_id' => $caseSolve->id, 'trainee_id' => $trainee->id],
                    ['case_solve_id' => $caseSolve->id, 'trainee_id' => $trainee->id]
                );

                $existingSubtitles = $caseSolveDetail->caseSubtitles()->pluck('subtitle')->toArray();
                $newSubtitles = array_diff($subtitles, $existingSubtitles);

                foreach ($newSubtitles as $subtitle) {
                    CaseSubtitle::create([
                        'case_solve_detail_id' => $caseSolveDetail->id,
                        'subtitle' => $subtitle,
                        'percentage' => '0'
                    ]);
                }

                CaseSubtitle::where('case_solve_detail_id', $caseSolveDetail->id)
                    ->whereNotIn('subtitle', $subtitles)
                    ->delete();
            }

            DB::commit();
            return redirect()->route('casesolve.index')->with('success', 'Berhasil update Case Solvenya Kak !!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Sepertinya lagi error, boleh kabarin angkatannya ya kak' . $e->getMessage());
        }
    }
}
