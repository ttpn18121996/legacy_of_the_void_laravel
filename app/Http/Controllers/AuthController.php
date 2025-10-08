<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        $data['email'] = str($data['email'])->append('@example.com')->lower()->toString();

        if (! auth()->attempt($data)) {
            return back()->withErrors([
                'email' => __('auth.failed'),
            ]);
        }

        return to_route('home');
    }

    public function logout(Request $request)
    {
        auth()->logout();

        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return to_route('login');
    }
}
