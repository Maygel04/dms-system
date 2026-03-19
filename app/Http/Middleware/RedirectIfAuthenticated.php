<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if (Auth::check()) {

            $role = Auth::user()->role ?? null;

            if ($role == 'admin') {
                return redirect('/admin/dashboard');
            }

            if ($role == 'mpdo') {
                return redirect('/mpdo/dashboard');
            }

            if ($role == 'meo') {
                return redirect('/meo/dashboard');
            }

            if ($role == 'bfp') {
                return redirect('/bfp/dashboard');
            }

            if ($role == 'applicant') {
                return redirect('/applicant/dashboard');
            }

            // fallback
            return redirect('/');
        }

        return $next($request);
    }
}