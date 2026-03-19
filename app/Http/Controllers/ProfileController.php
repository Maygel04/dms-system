<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{

    public function edit()
{
    return view('profile.edit');
}

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'contact_number' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'gender' => 'nullable|string|max:20'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->contact_number = $request->contact_number;
        $user->address = $request->address;
        $user->occupation = $request->occupation;
        $user->gender = $request->gender;

        $user->save();

        return back()->with('success','Profile updated successfully');
    }


    /* ================= PHOTO UPLOAD ================= */

    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $user = Auth::user();

        if ($request->hasFile('photo')) {

            $file = $request->file('photo');
            $filename = time().'.'.$file->getClientOriginalExtension();

            $file->move(public_path('profile_photos'), $filename);

            $user->photo = $filename;
            $user->save();
        }

        return back()->with('success','Profile photo uploaded successfully');
    }

    

}