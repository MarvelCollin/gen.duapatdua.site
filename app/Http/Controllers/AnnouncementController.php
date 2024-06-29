<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function show(){
        $announcements = Announcement::class;

        return view('announcement');
    }

    public function create(){
        
    }
}
