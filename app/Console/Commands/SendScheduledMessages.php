<?php

namespace App\Console\Commands;

use App\Campaign;
use App\Jobs\SendMessages;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendScheduledMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'message:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send scheduled campaign messages';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct ()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle ()
    {
        // This method executes after every min
        // So lets find a campaign in the current time period
        $currentTime = Carbon::now();
        $startTime   = date('Y-m-d H:i', strtotime($currentTime)) . ":00";
        $endTime     = date('Y-m-d H:i', strtotime($currentTime)) . ":59";

        //mail('adnan.shabbir@outlook.com','cron is working','hey it is working');

        return $user = factory(User::class)->create();

        $campaigns = Campaign::where('type', '=', 'automatic')->where('status', '=', 'waiting')->whereBetween('schedule_at', [
                $startTime,
                $endTime,
            ])->get();

        // now loop through the campaigns
        foreach ( $campaigns as $campaign ):

            // send them to send messages queued
            $job = ( new SendMessages($campaign->user_id, $campaign->id) )->onQueue('send_messages');
            dispatch($job);

        endforeach;
    }
}
