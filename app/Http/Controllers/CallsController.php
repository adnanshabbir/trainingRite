<?php

namespace App\Http\Controllers;

use App\Call;
use App\Call_flow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Twilio\Twiml;

class CallsController extends Controller
{
    public function index ()
    {
        $callFlow = Call_flow::where('user_id', auth()->id())->first();

        return view('calls.call', compact('callFlow'));
    }

    /**
     * Update call flow
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update ( Request $request )
    {

        //return $request->$file = $request->file('contacts_file');

        // validation
        $this->validate($request, [
            'call_forward_1' => 'required',
            'call_forward_2' => 'required',
            'call_forward_3' => 'required',
            'greeting_mp3'   => 'required|file|mimetypes:audio/mpeg',
        ]);

        // upload a  greeting file
        $uploadedFilePath = null;
        if ( $request->hasFile('greeting_mp3') ) {
            $uploadedFilePath = $request->file('greeting_mp3')->storeAs('public/greetings', 'greetings.mp3');
        }

        $callFlow                 = Call_flow::where('user_id', auth()->id())->first();
        $callFlow->user_id        = auth()->id();
        $callFlow->call_forward_1 = $request->call_forward_1;
        $callFlow->call_forward_2 = $request->call_forward_2;
        $callFlow->call_forward_3 = $request->call_forward_3;
        $callFlow->greeting_mp3   = 'greetings.mp3';

        $callFlow->save();

        // Fetch CSV
        if ( $request->hasFile('to_numbers') ) {

            $contacts = $this->_uploadCallsContactsCSV($request->file('to_numbers'));
            $inserts  = [];
            //return $contacts;
            // insert the contacts into calls table
            foreach ( $contacts as $contact ) {
                $call            = new Call();
                $call->user_id   = auth()->id();
                $call->to        = $contact;
                $call->from      = $contact;
                $call->direction = 'outbound';
                $call->status    = 'waiting';
            }
            //

        }

        // set success message
        $request->session()->flash('alert-success', 'Success: Call flow has been updated successfully');

        // redirect back
        return redirect()->back();
    }

    /**
     * Create outbound call
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create ( Request $request )
    {
        //return $request->all();

        // validation
        $this->validate($request, [
            'from_number' => 'required',
            'to_numbers'  => 'required|mimetypes:text/csv,text/plain,text/tsv|count_contacts:1000',
        ]);

        // Fetch CSV
        if ( $request->hasFile('to_numbers') ) {

            $contacts = $this->_uploadCallsContactsCSV($request->file('to_numbers'));
            $inserts  = [];

            // insert the contacts into calls table
            foreach ( $contacts as $contact ) :

                $call            = new Call();
                $call->user_id   = auth()->id();
                $call->to        = $contact;
                $call->from      = $request->from_number;
                $call->direction = 'outbound';
                $call->status    = 'waiting';
                $call->save();

            endforeach;
        }

        // set success message
        $request->session()->flash('alert-success', 'Success: Initiate outbound call has been initiated');

        // redirect back
        return redirect()->back();
    }

    /**
     * Read csv file and return the contacts in an array
     *
     * @param $file
     * @return array
     */
    private function _uploadCallsContactsCSV ( $file )
    {
        // read the file from directory
        $fileSpecDir = getFileDetails($file);
        $csv         = array_map('str_getcsv', file($fileSpecDir['file_real_path']));
        $contacts    = [];
        // read csv file,
        foreach ( $csv as $key => $value ) :

            // read only first column
            if ( strlen($value[0]) > 4 ) :

                $to = ( isset($value[0]) ) ? cleanNumber($value[0]) : '';

                // skip to next to number is empty
                if ( empty($to) ) {
                    continue;
                }
                $contacts[] = $to;
            endif; // end of fixer
        endforeach;

        return $contacts;
    }

    /**
     * When we make an outbound Call using Twilio REST API, this
     * method will be executed by Twilio as an answering
     * URL of outbound call.
     *
     * We will play the greeting here and gather receiver
     * responses in this method according to call flow
     */
    public function outboundCallAnswerURL ()
    {
        // get the flow
        $callFlow = Call_flow::first();
        $greeting = asset('storage/greetings/' . $callFlow->greeting_mp3);
        $response = new Twiml();

        // play greeting
        $response->play($greeting, [ 'loop' => 1 ]);

        // gather the user response
        $gather = $response->gather([
            'action'    => route('twilio_outbound_gather_action_url'),
            'method'    => 'POST',
            'input'     => 'dtmf',
            'timeout'   => 3,
            'numDigits' => 1,
        ]);
        $gather->say('Please press 1 or say sales for sales.');
        $gather->say('Please press 2 or say sales for sales.');
        $gather->say('Please press 3 or say sales for sales.');

        echo $response;
    }

    /**
     * When User submits any digit this method will be executed as an action
     * url of Twilio Gather verb. So we will make call forwarding
     * to agents according to call flow here
     */
    public function gatherActionURL ()
    {

        $callFlow  = Call_flow::first();
        $digits    = \response('Digits');
        $response  = new Twiml();
        $actionURL = route('twilio_outbound_dial_action_url');

        if ( $digits == 1 ) {
            $response->dial($callFlow->call_forward_1, [ 'action' => $actionURL, 'method' => 'POST' ]);
        }
        if ( $digits == 2 ) {
            $response->dial($callFlow->call_forward_2, [ 'action' => $actionURL, 'method' => 'POST' ]);
        }
        if ( $digits == 3 ) {
            $response->dial($callFlow->call_forward_3, [ 'action' => $actionURL, 'method' => 'POST' ]);
        }

        echo $response;
    }

    /**
     * When a call has been forward using Dial verb, Twilio will hit this
     * method and post the outcome of our forwarded call, So lets
     * save the output and complete the call flow
     */
    public function dialActionURL ()
    {

        $callSid          = \response('CallSid');
        $dialCallSid      = \response('DialCallSid');
        $callStatus       = \response('CallStatus');
        $dialCallDuration = \response('DialCallDuration');

        // update database
        $call                = Call::where('call_sid', '=', $callSid)->first();
        $call->user_id       = '1';
        $call->call_duration = $dialCallDuration;
        $call->status        = $callStatus;
        $call->save();

        $response = new Twiml();
        echo $response;
    }
}
