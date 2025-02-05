<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use App\Helpers\UserCheckHelper;
use App\Services\ConfigFallbackService;
use Illuminate\Support\Facades\Artisan;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register',[
            'status' => session('status'),
            'title' => config('izy-admin-titles.register')
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {   
        // Validazione dei dati
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'nullable|string|exists:roles,name', // Assicurati che il ruolo esista
        ]); 
        // Creazione del nuovo utente
        $user = User::create([
            'name' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        // Assegna il ruolo
        if (UserCheckHelper::adminRegistering()) {
            // Primo utente: assegna ruolo 'admin'
            $user->assignRole('admin');
            ConfigFallbackService::update('admin_registred', true);
        } else {
            // Altri utenti: assegna il ruolo passato
            if ($request->filled('role')) {
                $user->assignRole($request->role);
            }
        }    
        // Invia l'evento di registrazione
        event(new Registered($user));
        // Autentica l'utente
        Auth::login($user);
        // reindirizza alla pagina di amministrazione
        return redirect(route('izy.admin', absolute: false));
    }
    
}
