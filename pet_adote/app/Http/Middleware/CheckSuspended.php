<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSuspended
{
    public function handle(Request $request, Closure $next): Response
    {
        // Se está logado e a conta está suspensa
        if (Auth::check() && Auth::user()->is_suspended) {
            Auth::logout(); // Faz o logout forçado
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'Sua conta foi suspensa pela administração.');
        }

        return $next($request);
    }
}