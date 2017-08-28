<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use Twilio\Exceptions\TwilioException;

class TwilioController extends Controller
{
    /**
     * List all numbers of a Twilio account
     *
     * @return array
     */
    public function listTwilioNumbers ()
    {
        $settings = auth()->user()->settings;
        $client   = new Client($settings->account_sid, $settings->auth_token);

        $phoneNumbers = [];
        $counter      = 0;
        // Loop over the list of numbers and echo a property for each one
        foreach ( $client->incomingPhoneNumbers->read() as $number ) {

            $phoneNumbers[ $counter ]['phone_number'] = $number->phoneNumber;
            $phoneNumbers[ $counter ]['phone_sid']    = $number->sid;
            $counter++;
        }

        return $phoneNumbers;
    }

    /**
     * Send a single message using REST API
     *
     * @param string $from
     * @param string $to
     * @param string $message
     * @return \Twilio\Rest\Api\V2010\Account\MessageInstance |TwilioException
     */
    public function sendSMS ( $from, $to, $message )
    {
        $settings = auth()->user()->settings;
        $client   = new Client($settings->account_sid, $settings->auth_token);

        try {
            $response = $client->messages->create($to, [
                'from' => $from,
                'body' => $message,
            ]);

            return $response;
        } catch ( Exception $e ) {
            return $e->getMessage();
        }
    }

    /**
     * Update number properties
     *
     * @return void|string
     */
    public function UpdateNumberProperties ()
    {

        $settings     = auth()->user()->settings;
        $client       = new Client($settings->account_sid, $settings->auth_token);
        $phoneNumbers = $this->listTwilioNumbers();

        try {
            foreach ( $phoneNumbers as $key => $value ) :
                $number = $client->incomingPhoneNumbers($value['phone_sid'])->update([
                    "smsUrl" => route('twilio_sms_url'),
                ]);
            endforeach;
        } catch ( Exception $e ) {
            return $e->getMessage();
        }
    }
}
