<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Rechercher l'utilisateur par adresse e-mail
        $user = User::where('email', $request->email)->first();

        if ($user) {
            // Admin
            if ($user->choices !== 'voter')
                return redirect('/dashboard');

            // Voteur
            elseif($user->choices ==='voter'){
                if($user->statut === 'done')
                    return redirect('confirm');
                else
                    return redirect('home');
            }

            // Super Admin
            else
                dd("BBB");
        }

        // Rediriger vers la page d'accueil par défaut si aucune condition n'est remplie
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
