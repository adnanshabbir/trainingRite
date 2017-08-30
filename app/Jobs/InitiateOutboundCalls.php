<?php

namespace App\Jobs;

use App\Call;
use App\Call_flow;
use App\Http\Controllers\TwilioController;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class InitiateOutboundCalls implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $userId;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 80000;

    /**
     * Create a new job instance.
     *
     * @param $userId
     */
    public function __construct ( $userId )
    {
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle ()
    {
        // get all calls that have waiting status and update their
        // status to processing after fetching from database

        $calls = Call::where('status', '=', 'waiting')->get();

        // loop through the contacts made one by one outbound call
        foreach ( $calls as $call ) :

            $updateCall = call::find($call->id);
            $url        = route('twilio_outbound_call_url');
            $response   = ( new TwilioController )->makeOutboundCall($call->from, $call->to, $url);

            if ( is_string($response) ) :
                $updateCall->status = 'failed';
            else:
                $updateCall->call_sid = $response->sid;
                $updateCall->status   = $response->status;
            endif;

            $updateCall->save();

        endforeach;
    }
}
