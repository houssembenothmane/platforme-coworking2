<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required'    => 'L\'email est obligatoire.',
            'email.email'       => 'L\'email doit être valide.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min'      => 'Le mot de passe doit contenir au moins 6 caractères.',
        ]);

        if (auth('client')->attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();

            if (auth('client')->user()->isAdmin()) {
                return redirect('/admin/dashboard')
                    ->with('success', 'Bienvenue Admin !');
            }

            return redirect()->route('espaces.index')
                ->with('success', 'Bienvenue ' . auth('client')->user()->nom . ' !');
        }

        return back()->with('error', 'Email ou mot de passe incorrect.')->withInput();
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nom'      => 'required|min:2|max:80',
            'email'    => 'required|email|unique:clients,email',
            'password' => 'required|min:6|confirmed',
        ], [
            'nom.required'       => 'Le nom est obligatoire.',
            'nom.min'            => 'Le nom doit contenir au moins 2 caractères.',
            'email.required'     => 'L\'email est obligatoire.',
            'email.email'        => 'L\'email doit être valide.',
            'email.unique'       => 'Cet email est déjà utilisé.',
            'password.required'  => 'Le mot de passe est obligatoire.',
            'password.min'       => 'Le mot de passe doit contenir au moins 6 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ]);

        $client = Client::create([
            'nom'      => $request->nom,
            'email'    => $request->email,
            'password' => $request->password,
        ]);

        auth('client')->login($client);

        return redirect()->route('espaces.index')
            ->with('success', 'Compte créé avec succès ! Bienvenue ' . $client->nom . ' !');
    }

    public function logout(Request $request)
    {
        auth('client')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Vous êtes déconnecté.');
    }
}