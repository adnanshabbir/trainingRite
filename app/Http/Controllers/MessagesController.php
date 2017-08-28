<?php

namespace App\Http\Controllers;

use App\Campaign;
use App\Jobs\SendMessages;
use App\Message;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    /**
     * Display a listing of the inbound message logs resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexInboundMessageLogs ()
    {
        $inboundMessageLogs = Message::where('direction', 'inbound')->latest()->get();
        $counter            = 0;

        return view('messages.inbound_logs', compact('inboundMessageLogs', 'counter'));
    }

    /**
     * Display a listing of the outbound message logs resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexOutboundMessageLogs ()
    {
        $outboundMessageLogs = Message::where('direction', 'outbound')->latest()->get();
        $counter             = 0;

        return view('messages.outbound_logs', compact('outboundMessageLogs', 'counter'));
    }

    /**
     * Show the form for creating a new bulk messages campaign resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create ()
    {

        return view('messages.create_bulk_messages');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store ( Request $request )
    {

        // validation
        $this->validate($request, [
            'from_numbers'  => 'required',
            'to_numbers'    => 'required|mimetypes:text/csv,text/plain,text/tsv|count_contacts:1000',
            'template_body' => 'required',
        ]);

        $campaignType = ( null === $request->is_schedule ) ? 'manual' : 'automatic';
        $scheduleAt   = ( null === $request->is_schedule ) ? null : date('Y-m-d H:i:s', strtotime($request->schedule_at));

        // first save it as a campaign
        $campaign              = new Campaign();
        $campaign->user_id     = auth()->id();
        $campaign->type        = $campaignType;
        $campaign->status      = 'waiting';
        $campaign->schedule_at = $scheduleAt;
        $campaign->save();

        $campaignId = $campaign->id;

        $inserts     = [];
        $contacts    = [];
        $fromNumbers = $request->from_numbers;

        // Fetch CSV
        if ( $request->hasFile('to_numbers') ) {

            // read the file from directory
            $fileSpecDir = $this->_getFileDetails($request->file('to_numbers'));
            $csv         = array_map('str_getcsv', file($fileSpecDir['file_real_path']));
            // read csv file,
            foreach ( $csv as $key => $value ) :

                // read only first column
                if ( strlen($value[0]) > 4 ) :

                    $to = ( isset($value[0]) ) ? $this->_checkCountryCode($value[0]) : '';

                    // skip to next to number is empty
                    if ( empty($to) ) {
                        continue;
                    }
                    $inserts[] = $to;
                endif; // end of fixer
            endforeach;
        }

        // Make the csv chunks on the basis of from numbers
        if ( count($fromNumbers) > 1 ) {
            $collection     = collect($inserts);
            $contactsChunks = $collection->chunk(count($fromNumbers));
            $contactsChunks = $contactsChunks->toArray();
        } else {
            $contactsChunks[] = $inserts;
        }

        // making batches for insertion
        foreach ( $contactsChunks as $key => $value ) {
            foreach ( $value as $index => $item ) :
                $contacts[ $index ]['user_id']         = auth()->id();
                $contacts[ $index ]['campaign_id']     = $campaignId;
                $contacts[ $index ]['from']            = $fromNumbers[ $key ];
                $contacts[ $index ]['to']              = $item;
                $contacts[ $index ]['body']            = $request->template_body;
                $contacts[ $index ]['customer_number'] = $item;
                $contacts[ $index ]['direction']       = 'outbound';
                $contacts[ $index ]['status']          = 'pending';
                $contacts[ $index ]['created_at']      = Carbon::now();
                $contacts[ $index ]['updated_at']      = Carbon::now();
            endforeach;
        }

        // making chunks to get saved from prepared statement place holder error
        if ( count($contacts) > 1000 ) {
            foreach ( array_chunk($contacts, 1000) as $chunk ) {
                Message::insert($chunk);
            }
        } else {
            Message::insert($contacts);
        }

        // Send job to queue if its a manual campaign
        if ( $campaignType == 'manual' ) {

            $job = ( new SendMessages(auth()->id(), $campaignId) )->onQueue('send_messages');
            dispatch($job);
        }

        // set success message
        $request->session()->flash('alert-success', 'Success: Messages have been sent for processing successfully');

        // redirect back
        return redirect()->back();
    }

    /**
     * Get twilio numbers
     * An ajax method
     *
     * @return array
     */
    public function fetchTwilioNumbers ()
    {

        $twilioNumbers = ( new TwilioController )->listTwilioNumbers();
        $html          = '';

        foreach ( $twilioNumbers as $key => $value ) {

            $html .= '<option value="' . $value['phone_number'] . '">' . $value['phone_number'] . '</option>';
        }
        echo $html;
        exit;
    }

    /**
     * Get uploaded file details
     *
     * @param $file
     * @return array
     */
    private function _getFileDetails ( $file )
    {
        $file_details = [];
        //Display File Name
        $file_details['file_name'] = $file->getClientOriginalName();

        //Display File Extension
        $file_details['file_ext'] = $file->getClientOriginalExtension();

        //Display File Real Path
        $file_details['file_real_path'] = $file->getRealPath();

        return $file_details;
    }

    /**
     * Apply country code logic as per user selection and return number
     *
     * @param $phone
     * @return string
     */
    private function _checkCountryCode ( $phone )
    {
        $phone = str_replace(" ", "", $phone);
        $phone = str_replace("-", "", $phone);
        $phone = str_replace(")", "", $phone);
        $phone = str_replace("(", "", $phone);

        if ( strlen($phone) == 10 ) {
            // add the user choice
            return $phoneNumber = '+1' . $phone;
        }

        if ( strlen($phone) == 11 ) {
            // add the user choice
            return $phoneNumber = '+' . $phone;
        }

        // do nothing just return the number
        return $phone;
    }

    /**
     * Inbound sms url
     */
    public function receiveMessage ()
    {
        // save response into data
        $message = new Message();

        $message->user_id         = '1';
        $message->message_uuid    = \request('Sid');
        $message->to              = \request('To');
        $message->from            = \request('From');
        $message->body            = \request('Body');
        $message->customer_number = \request('From');
        $message->direction       = 'inbound';
        $message->status          = 'received';

        $message->save();
    }
}
