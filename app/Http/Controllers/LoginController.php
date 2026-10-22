<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Psr\Http\Message\ServerRequestInterface;

class LoginController extends Controller
{
    function showForm(): View
    {
        return view('auth.login', [
            'title' => 'Login',
        ]);
    }

    function authenticate(ServerRequestInterface $request): RedirectResponse
    {
        $validator = Validator::make(
            $request->getParsedBody() ?? [],
            [
                'email' => ['required', 'email'],
                'password' => ['required'],
            ],
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        if (!Auth::attempt($validator->safe()->only(['email', 'password']))) {
            return redirect()->back()->withInput()->withErrors([
                'credentials' => 'Email or password is incorrect.',
            ]);
        }

        // New session id after login (prevents session fixation).
        session()->regenerate();

        return redirect()
            ->intended(route('events.list'))
            ->with('status', 'Welcome, ' . Auth::user()->name . '.');
    }

    function logout(): RedirectResponse
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()
            ->route('events.list')
            ->with('status', 'You have logged out.');
    }
}
