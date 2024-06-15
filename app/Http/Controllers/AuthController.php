<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        $password = $request->input('password');
        $hashedPassword = bcrypt('242gacor');

        if (Hash::check($password, $hashedPassword)) {
            session(['authenticated' => true]);
            return redirect()->route('home')->with('success', 'Login successful!');
        }

        return back()->withErrors(['password' => 'Password salah, web ini khusus 24-2 saja kak...']);
    }


    public function logout()
    {
        session()->forget('authenticated');
        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}
