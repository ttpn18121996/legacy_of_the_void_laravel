<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class AuthController extends Controller
{
    private string $redirectTo = 'home';

    public function showLoginForm()
    {
        if (auth()->check()) {
            return to_route($this->redirectTo);
        }

        return view('login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required',
            'password' => 'required',
            'is_terminal' => 'nullable',
        ]);
        $data['email'] = str($data['email'])->append('@example.com')->lower()->toString();
        $credentials = Arr::only($data, ['email', 'password']);

        if (! auth()->attempt($credentials)) {
            return back()->withErrors([
                'email' => __('auth.failed'),
            ]);
        }

        if (! empty($data['is_terminal'])) {
            session()->put('is_terminal', 1);

            return to_route('terminal.main');
        }

        return to_route($this->redirectTo);
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
