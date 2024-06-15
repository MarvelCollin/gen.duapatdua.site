<?php

namespace App\Http\Controllers;

use App\Models\Rundown;
use Illuminate\Http\Request;
use App\Models\RundownDetail;

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

        return view('home', compact('latestRundown', 'rundownDetails'));
    }
}
