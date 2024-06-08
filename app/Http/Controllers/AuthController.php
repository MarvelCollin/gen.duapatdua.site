<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

        if ($password === '242gacor') {
            session(['authenticated' => true]);
            return redirect()->route('home')->with('success', 'Login successful!');
        }

        return back()->withErrors(['password' => 'Incorrect password. Please try again.']);
    }

    public function logout()
    {
        session()->forget('authenticated');
        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}
