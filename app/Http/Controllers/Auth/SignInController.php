<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignInFormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Support\SessionRegenerator;

class SignInController extends Controller
{
    public function page(): View
    {
        return view('auth.login');
    }

    public function handle(SignInFormRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        if (!auth()->validate($credentials)) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }

        SessionRegenerator::run(fn() => auth()->attempt($credentials));

//        if (!auth()->once($request->validated())) {
//            return back()->withErrors([
//                'email' => 'The provided credentials do not match our records.',
//            ])->onlyInput('email');
//        }
//
//        SessionRegenerator::run(fn () => auth()->login(
//            auth()->user()
//        ));

        return redirect()->intended(route('home'));
    }

    public function logOut(): RedirectResponse
    {
        SessionRegenerator::run(fn() => auth()->logout());

        return redirect()->route('home');
    }
}
