<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function show()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:8',
            'contact_number'=>'required',
            'address'=>'required',
            'gender'=>'required',
            'occupation'=>'required',
        ]);

        User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'contact_number'=>$request->contact_number,
            'address'=>$request->address,
            'gender'=>$request->gender,
            'occupation'=>$request->occupation,
            'role'=>'applicant'
        ]);

        return redirect('/login')->with('success','Registered');
    }
}
