<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): Response
    {
        $isFirstUser = $request->query('isFirstUser', false);
        return Inertia::render('Auth/Register', [
            'status' => session('status'),
            'title' => config('izy-admin-titles.register'),
            'isFirstUser' => $isFirstUser,
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'string|exists:roles,name', // Assicurati che il ruolo esista
        ]);

        $user = User::create([
            'name' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);
        event(new Registered($user));

        // Login dell'utente
        Auth::login($user);

        // Forza la scrittura nel DB PRIMA del redirect
        $request->session()->save();

        /*
        * utilizziamo Inertia::location per fare un redirect client-side
        * in modo da evitare di dover fare un redirect server-side
        * che comporterebbe il non aggiornamento dei valori
        * passati al client tramite Inertia::share
        * come ad esempio il nome dell'utente loggato
        */
        return Inertia::location(route('izy.admin'));
    }
}
