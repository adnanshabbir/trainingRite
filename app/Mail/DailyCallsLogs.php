<?php

namespace App\Mail;

use App\Call;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class DailyCallsLogs extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct ()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build ()
    {

        // set storage path
        $storagePath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . "public/reports";;

        // create the directory if it is not exists
        if ( ! \File::exists($storagePath) ) {
            Storage::makeDirectory('public/reports');
        }

        // get current day calls logs
        $carbon    = Carbon::now();
        $callsLogs = Call::select('call_sid', 'to', 'from', 'direction', 'call_duration', 'status', 'updated_at')->whereDay('created_at', $carbon->day)->get()->toArray();
        $fileName  = $carbon->toDayDateTimeString() . '.csv';

        // convert it into a csv file
        $fp = fopen($storagePath . '/' . $fileName, 'w');
        fputcsv($fp, [ 'Call SID', 'To Number', 'From Number', 'Direction', 'Call Duration', 'Status', 'TimeStamp' ]);
        foreach ( $callsLogs as $line ) {
            fputcsv($fp, $line, ',');
        }
        fclose($fp);

        $csvPath = asset('storage/reports/' . $fileName);

        return $this->markdown('emails.reports.calls_logs')->with('csvPath', $csvPath)->attach($storagePath . '/' . $fileName);
    }
}
