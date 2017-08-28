<?php

namespace App\Jobs;

use App\Campaign;
use App\Http\Controllers\TwilioController;
use App\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use PhpParser\Node\Stmt\Foreach_;

class SendMessages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $userId;

    public $campaignId;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

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
     * @param $campaignId
     */
    public function __construct ( $userId, $campaignId )
    {
        $this->userId     = $userId;
        $this->campaignId = $campaignId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle ()
    {

        // update campaign status
        Campaign::where('id', $this->campaignId)->update([ 'status' => 'processing' ]);

        // now fetch the campaign contacts
        $messages = Message::where('campaign_id', $this->campaignId)->where('direction', 'outbound')->where('status', 'pending')->get();

        $totalMessages  = count($messages);
        $messageCounter = 0;
        // now loop through the messages array and send message one by one
        foreach ( $messages as $message ) :

            // send message
            $response = ( new TwilioController () )->sendSMS($message->from, $message->to, $message->body);

            // skip to next if an exception occurred from Twilio
            if ( is_string($response) ) :
                continue;
            endif;

            // update database by Twilio response
            $sentMessage               = Message::find($message->id);
            $sentMessage->message_uuid = $response->sid;
            $sentMessage->status       = $response->status;
            $sentMessage->save();

            // rest for 2 seconds to avoid get flagged
            sleep(2);

            // update counter
            $messageCounter++;

            if ( $messageCounter == $totalMessages ):
                // update campaign to completed
                Campaign::where('id', $this->campaignId)->update([ 'status' => 'completed' ]);
            endif;

        endforeach;
    }
}
