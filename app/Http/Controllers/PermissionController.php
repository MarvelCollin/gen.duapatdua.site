<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use Carbon\Carbon;


// Create the Permission record with the specified 'created_at' time


class PermissionController extends Controller
{
    public function show(){
        $permissions = Permission::all();
        
        return view('permission', compact('permissions'));
    }
    
    public function create(Request $request){
        $createdAt = Carbon::now()->addHours(14);

        Permission::create([
            'trainee_number' => $request->trainee_number,
            'reason' => $request->reason,
            'created_at' => $createdAt,
        ]);
        
        // dd($request->trainee_number);
        return redirect()->back();
    }

    public function remove($id){
        $permission = Permission::findOrFail($id);
        $permission->delete();
        return redirect()->back();
    }
}
