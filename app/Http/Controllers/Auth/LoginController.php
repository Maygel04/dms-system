<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    /* ================= SHOW LOGIN ================= */
    public function show()
    {
        return view('auth.login');
    }

    /* ================= LOGIN ================= */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            $user = Auth::user();

            /* ADMIN CHECK */
            if($user->email == 'admin@gmail.com'){
                return redirect('/admin/dashboard');
            }

            /* DEPARTMENT USERS */
            if($user->role == 'mpdo'){
                return redirect('/mpdo/dashboard');
            }

            if($user->role == 'meo'){
                return redirect('/meo/dashboard');
            }

            if($user->role == 'bfp'){
                return redirect('/bfp/dashboard');
            }

            /* APPLICANT */
            return redirect('/applicant/dashboard');

        }

        return back()->with('error','Invalid email or password.');
    }
}