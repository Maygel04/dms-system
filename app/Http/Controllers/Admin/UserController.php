<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = DB::table('users')->orderByDesc('id')->get();
        return view('admin.users',compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:4',
            'role'=>'required'
        ]);

        DB::table('users')->insert([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'role'=>$request->role,
            'created_at'=>now()
        ]);

        return back()->with('success','User created');
    }
    public function toggleStatus($id)
{
    $user = \App\Models\User::findOrFail($id);

    // optional: dili pwede i-deactivate ang kaugalingon nga admin account
    if (auth()->id() == $user->id) {
        return back()->with('error', 'You cannot deactivate your own account.');
    }

    $user->is_active = $user->is_active ? 0 : 1;
    $user->save();

    $message = $user->is_active
        ? 'User activated successfully.'
        : 'User deactivated successfully.';

    return back()->with('success', $message);
}
}