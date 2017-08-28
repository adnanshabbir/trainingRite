<?php

namespace App\Http\Controllers;

use App\Message;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct ()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index ()
    {
        $totalInboundMessages  = Message::where('direction', 'inbound')->count();
        $totalOutboundMessages = Message::where('direction', 'outbound')->count();

        return view('dashboard', compact('totalInboundMessages', 'totalOutboundMessages'));
    }

    /**
     * Update Twilio account numbers sms urls
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateNumbersURL ()
    {
        $twilioNumbers = ( new TwilioController )->UpdateNumberProperties();

        if ( is_string($twilioNumbers) ) {

            // set error
            \request()->session()->flash('alert-danger', $twilioNumbers);

            // redirect back
            return redirect()->back();
        }

        // set success message
        \request()->session()->flash('alert-success', 'Success: URLS have been updated  successfully');

        // redirect back
        return redirect()->back();
    }
}
