<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'email' => 'Les identifiants sont incorrects.',
            ])->withInput();
        }

        if ($user->role !== 'admin') {
            return back()->withErrors([
                'email' => 'Vous n\'avez pas accès au panel admin.',
            ])->withInput();
        }

        Auth::login($user);
        return redirect()->route('admin.dashboard')->with('success', 'Bienvenue sur le panel admin');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login')->with('success', 'Déconnexion réussie');
    }
}