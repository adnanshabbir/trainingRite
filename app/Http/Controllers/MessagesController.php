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

        // explicit check: If From numbers are greater then To numbers then return with error
        $toNumbersCSVRows = count(file($request->to_numbers, FILE_SKIP_EMPTY_LINES));
        $fromNumbers      = $request->from_numbers;

        if ( count($fromNumbers) > $toNumbersCSVRows ) {


            // set success message
            $request->session()->flash('alert-danger', 'Error: To numbers must be greater then From numbers');

            // redirect back
            return redirect()->back();
        }

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
        $inserts    = [];
        $contacts   = [];

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
                    $contacts[] = $to;
                endif; // end of fixer
            endforeach;
        }

        $toNumCount   = count($contacts);
        $fromNumCount = count($fromNumbers);
        $reminder     = $toNumCount % $fromNumCount;
        $perNumText   = ( $toNumCount - $reminder ) / $fromNumCount;
        $index        = 0;

        //echo 'to numbers counts => ' . $toNumCount;echo "<br/>";
        //echo 'from numbers counts => ' . $fromNumCount;echo "<br/>";
        //echo 'Reminder => ' . $reminder;echo "<br/>";
        //echo 'Per number Text => ' . $perNumText;

        $toNumPartials = array_chunk($contacts, $perNumText);
        if ( empty($toNumPartials) ) {
            return null;
        }

        /**
         * To send bulk messages , we will store the messages in the campaign meta table
         * because we need to replace the template variables with the real data
         */
        foreach ( $toNumPartials as $key => $value ) :

            if ( count($value) != $perNumText ) :
                continue;
            endif;

            // setting source number ( from number)
            if ( isset($fromNumbers[ $key ]) ) :
                $src = $fromNumbers[ $key ];
            else:
                continue;
            endif;

            // making destination numbers
            foreach ( $value as $item => $contact ) {

                $inserts[ $index ]['user_id']         = auth()->id();
                $inserts[ $index ]['campaign_id']     = $campaignId;
                $inserts[ $index ]['from']            = $src;
                $inserts[ $index ]['to']              = $contact;
                $inserts[ $index ]['body']            = $request->template_body;
                $inserts[ $index ]['customer_number'] = $contact;
                $inserts[ $index ]['direction']       = 'outbound';
                $inserts[ $index ]['status']          = 'pending';
                $inserts[ $index ]['created_at']      = Carbon::now();
                $inserts[ $index ]['updated_at']      = Carbon::now();
                $index++;
            }
        endforeach;

        /**
         * If there is a reminder then we will generate another message
         * add its response to our main response array
         */
        if ( $reminder > 0 ) :

            // setting message parameters
            $lastNumbers = array_slice($contacts, -( $reminder ), $toNumCount, true);

            if ( ! empty($lastNumbers) ) {
                foreach ( $lastNumbers as $key => $contact ):

                    $sourceNumber                         = $fromNumbers[0];
                    $inserts[ $index ]['user_id']         = auth()->id();
                    $inserts[ $index ]['campaign_id']     = $campaignId;
                    $inserts[ $index ]['from']            = $sourceNumber;
                    $inserts[ $index ]['to']              = $contact;
                    $inserts[ $index ]['body']            = $request->template_body;
                    $inserts[ $index ]['customer_number'] = $contact;
                    $inserts[ $index ]['direction']       = 'outbound';
                    $inserts[ $index ]['status']          = 'pending';
                    $inserts[ $index ]['created_at']      = Carbon::now();
                    $inserts[ $index ]['updated_at']      = Carbon::now();
                    $index++;
                endforeach;
            }
        endif;

        // making chunks to get saved from prepared statement place holder error
        if ( count($inserts) > 1000 ) {
            foreach ( array_chunk($inserts, 1000) as $chunk ) {
                Message::insert($inserts);
            }
        } else {
            Message::insert($inserts);
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

        // debug
        //$this->_checkResponse('adnan.shabbir@outlook.com',\request()->all());
        //
        //// save response into data
        //$message = new Message();
        //
        //$message->user_id         = '1';
        //$message->message_uuid    = \request('SmsSid');
        //$message->to              = \request('To');
        //$message->from            = \request('From');
        //$message->body            = \request('Body');
        //$message->customer_number = \request('From');
        //$message->direction       = 'inbound';
        //$message->status          = 'received';
        //
        //$message->save();


        // should call xml and then end the flow
        header( 'Content-type: text/xml' );
        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        echo "<Response>";
        echo "</Response>";
        exit;
    }


    /**
     * Check the response
     *
     * @param $emailAdd
     * @param array $requestData
     * @return bool
     */
    private function _checkResponse ( $emailAdd, array $requestData )
    {
        $postData = '';
        foreach ( $requestData as $key => $val ) {
            $postData .= $key . " => " . $val . "\n \r";
        }

        return mail($emailAdd, "Response", $postData);
    }
}
