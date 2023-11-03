<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignUpFormRequest;
use Domain\Auth\Contracts\RegisterNewUserContract;
use Domain\Auth\DTOs\NewUserDTO;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SignUpController extends Controller
{
    public function page(): View
    {
        return view('auth.sign-up');
    }

    public function handle(SignUpFormRequest $request, RegisterNewUserContract $action): RedirectResponse
    {
        // Try/catch in real project

        $user = $action(NewUserDTO::fromRequest($request));

//        $user = $action(NewUserDTO::make(...$request->only(['name', 'email', 'password'])));

        auth()->login($user);

        return redirect()->intended(route('home'));
    }
}
