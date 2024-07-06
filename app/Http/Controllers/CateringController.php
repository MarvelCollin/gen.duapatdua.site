<?php

namespace App\Http\Controllers;

use App\Models\Catering;
use Illuminate\Http\Request;

class CateringController extends Controller
{
    public function show(){
        $caterings = Catering::all();

        return view('catering');
    }
}
