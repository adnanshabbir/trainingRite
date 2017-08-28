<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show ()
    {
        $profile = User::find(auth()->id());
        //return $profile;
        return view('profile', compact('profile'));
    }


    public function update ( Request $request )
    {

        $user     = User::find(auth()->id());
        $password = ( null === $request->password ) ? $user->password : \Hash::make($request->password);

        // validate user
        $this->validate($request, [
            'name'     => 'required|min:3|max:50',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
        ]);

        // update authenticated user
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->password = $password;
        if ( $user->user_type == 'customer' ) {
            $user->number = $request->number;
        }
        $user->save();

        // set success message
        $request->session()->flash('alert-success', 'Profile has been updated successfully!');

        // redirect back
        return redirect()->back();
    }
}
