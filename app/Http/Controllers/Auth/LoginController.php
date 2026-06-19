<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (auth()->user()->is_blocked) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Votre compte a été bloqué.',
                ])->withInput();
            }

            $intendedUrl = session('url.intended', route('home'));

            // Verify the intended URL is valid (not the login page itself)
            if ($intendedUrl === route('login') || str_contains($intendedUrl, '/login')) {
                $intendedUrl = route('home');
            }

            return redirect()->to($intendedUrl)->with('success', 'Bienvenue!');
        }

        return back()->withErrors([
            'email' => 'Les identifiants sont incorrects.',
        ])->withInput();
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }
}