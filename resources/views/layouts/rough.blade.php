<?php

namespace App\Http\Controllers;

use App\Message;
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
        //dd($request->all());
        // validation
        $this->validate($request, [
            'from_numbers'  => 'required',
            'to_numbers'    => 'required|mimetypes:text/csv,text/plain,text/tsv',
            'template_body' => 'required',
        ]);

        $csvData     = [];
        //$fromNumbers = $request->from_numbers;
        $fromNumbers = [123123,444444];

        // Fetch CSV
        if ( $request->hasFile('to_numbers') ) {
            $csvData[] = $this->_fetchContactsCSV($request->file('to_numbers'));
        }


        // Make the csv chunks on the basis of from numbers
        //$contacts = $this->_makeContactsBatchInsertData($csvData, $fromNumbers, $request->template_body);


        $contacts       = [];
        $collection     = collect($csvData);
        $contactsChunks = $collection->chunk(2);
        $contactsChunks = $contactsChunks->toArray();
        return $contactsChunks;

        foreach ( $contactsChunks as $key => $value ) {
            foreach ( $value as $index => $item ) :
                $contacts[ $index ]['user_id']         = auth()->id();
                $contacts[ $index ]['campaign_id']     = auth()->id();
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



        return $contacts;
        // making chunks to get saved from prepared statement place holder error
        if ( count($contacts) > 1000 ) {
            foreach ( array_chunk($contacts, 1000) as $chunk ) {
                Message::insert($chunk);
            }
        } else {
            Message::insert($contacts);
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

    /**
     * Read and fetch contacts from uploaded CSV file
     *
     * @param $toNumbers
     * @return array
     */
    private function _fetchContactsCSV ( $toNumbers )
    {

        $contacts = [];
        // read the file from directory
        $fileSpecDir = $this->_getFileDetails($toNumbers);
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
                $contacts[] = $to;
            endif; // end of fixer
        endforeach;

        return $contacts;
    }

    /**
     * Make batch inserts of contact for database
     *
     * @param array $csvData
     * @param array $fromNumbers
     * @param string $template_body
     * @return array
     */
    private function _makeContactsBatchInsertData ( $csvData = [], $fromNumbers = [], $template_body = '' )
    {
        $contacts       = [];
        $collection     = collect($csvData);
        $contactsChunks = $collection->chunk(count($fromNumbers));
        $contactsChunks = $contactsChunks->toArray();

        foreach ( $contactsChunks as $key => $value ) {
            foreach ( $value as $index => $item ) :
                $contacts[ $index ]['user_id']         = auth()->id();
                $contacts[ $index ]['campaign_id']     = auth()->id();
                $contacts[ $index ]['from']            = $fromNumbers[ $key ];
                $contacts[ $index ]['to']              = $item;
                $contacts[ $index ]['body']            = $template_body;
                $contacts[ $index ]['customer_number'] = $item;
                $contacts[ $index ]['direction']       = 'outbound';
                $contacts[ $index ]['status']          = 'pending';
                $contacts[ $index ]['created_at']      = Carbon::now();
                $contacts[ $index ]['updated_at']      = Carbon::now();
            endforeach;
        }


        return $contacts;
    }
}
