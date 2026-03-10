<?php
// app/Http/Middleware/SuperAdminGlobalMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminGlobalMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (!Auth::user()->isSuperAdminGlobal()) {
            abort(403, 'Accès réservé au super administrateur global.');
        }

        return $next($request);
    }
}