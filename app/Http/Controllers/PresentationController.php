<?php

namespace App\Http\Controllers;

use App\Models\Presentation;
use Illuminate\Http\Request;

class PresentationController extends Controller
{
    public function show(){
        $presentations = Presentation::all();
        return view('presentation', compact('presentations'));
    }
}
