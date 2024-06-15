<?php

namespace App\Http\Controllers;

use App\Models\Rundown;
use Illuminate\Http\Request;
use App\Models\RundownDetail;
use App\Models\CaseSolve;

class HomeController extends Controller
{
    public function index()
    {
        $latestRundown = Rundown::latest()->first();

        if ($latestRundown) {
            $rundownDetails = RundownDetail::where('rundown_id', $latestRundown->id)->get();
        } else {
            $rundownDetails = collect();
        }

        $currentTime = now();

        $twoHoursAgo = $currentTime->subHours(2);
        $latestCaseSolves = CaseSolve::where('created_at', '>=', $twoHoursAgo)->get();

        return view('home', compact('latestRundown', 'rundownDetails', 'latestCaseSolves'));
    }
}
