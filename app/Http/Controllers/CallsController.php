<?php

namespace App\Http\Controllers;

use App\Call_flow;
use Illuminate\Http\Request;

class CallsController extends Controller
{
    public function index ()
    {
        $callFlow = Call_flow::where('user_id', auth()->id())->first();

        return view('call', compact('callFlow'));
    }

    /**
     * Update call flow
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update ( Request $request )
    {
        //return $request->all();

        // validation
        $this->validate($request, [
            'call_forward_1' => 'required',
            'call_forward_2' => 'required',
            'call_forward_3' => 'required',
            //'greeting_mp3'    => 'required|mimetypes:text/csv,text/plain,text/tsv|count_contacts:1000',
        ]);

        $callFlow                 = new Call_flow();
        $callFlow->user_id        = auth()->id();
        $callFlow->call_forward_1 = $request->call_forward_1;
        $callFlow->call_forward_2 = $request->call_forward_2;
        $callFlow->call_forward_3 = $request->call_forward_3;

        $callFlow->save();

        // set success message
        $request->session()->flash('alert-success', 'Success: Call flow has been updated successfully');

        // redirect back
        return redirect()->back();
    }
}
