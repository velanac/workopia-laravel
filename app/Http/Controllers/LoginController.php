<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginUserRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    // @desc Show login form
    // @route GET /login
    public function login(): View
    {
        return view('auth.login');
    }

    // @desc Login user
    // @route GET /login
    public function authenticate(LoginUserRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        // Attempt to authenticate user
        if (Auth::attempt($credentials)) {
            // Regenerate the session to prevent fixation attacks
            $request->session()->regenerate();

            return redirect()->intended(route('home'))->with('success', 'You are now logged in!');
        }

        // If auth fails, redirect back with error
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records'
        ])->onlyInput('email');
    }

    // @desc Logout user
    // @route GET /login
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerate();

        return redirect('/');
    }
}
