<?php

namespace App\Http\Controllers;

use App\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Show settings view with already saved settings
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show ()
    {
        $settings = auth()->user()->settings;

        return view('settings', compact('settings'));
    }

    /**
     * Update settings
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update ( Request $request )
    {

        // validate
        $this->validate($request, [
            'account_sid' => 'required',
            'auth_token'  => 'required',
        ]);

        // update authenticated user
        $settings = Setting::where('user_id', '=', auth()->id())->first();
        
        $settings->account_sid = $request->account_sid;
        $settings->auth_token  = $request->auth_token;

        $settings->save();

        // set success message
        $request->session()->flash('alert-success', 'Settings have been updated successfully!');

        // redirect back
        return redirect()->back();
    }
}
