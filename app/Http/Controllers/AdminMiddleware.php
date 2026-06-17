<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si l'utilisateur est connecté et est admin
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté.');
        }

        if (auth()->user()->role !== 'admin') {
            abort(403, 'Accès non autorisé. Réservé aux administrateurs.');
        }

        return $next($request);
    }
}