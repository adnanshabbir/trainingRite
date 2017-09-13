<?php
// Front page
use App\Mail\DailyCallsLogs;

Route::get('/', function () {
    return redirect('login');
});

// Authentication routes
Auth::routes();

// Authenticated User Routes Group
Route::middleware('auth')->group(function () {

    // dashboard
    Route::get('/home', 'HomeController@index')->name('home');

    // Update Twilio Numbers SMS urls
    Route::get('/home/update/urls', 'HomeController@updateNumbersURL')->name('update_sms_urls');

    // Profile
    Route::get('/profile', 'ProfileController@show')->name('profile');
    Route::post('/profile', 'ProfileController@update')->name('update_profile');

    // Settings
    Route::get('/settings', 'SettingsController@show')->name('settings');
    Route::post('/settings', 'SettingsController@update')->name('update_settings');

    // Message Logs
    Route::get('/message-logs/inbound', 'MessagesController@indexInboundMessageLogs')->name('inbound_logs');
    Route::get('/message-logs/outbound', 'MessagesController@indexOutboundMessageLogs')->name('outbound_logs');

    // Send Bulk Messages
    Route::get('/send-messages', 'MessagesController@create')->name('create_bulk_messages');
    Route::post('/send-messages', 'MessagesController@store')->name('send_bulk_messages');

    // Ajax From Numbers
    Route::post('/numbers/from/fetch', 'MessagesController@fetchTwilioNumbers')->name('get_from_numbers');

    /**
     * Call flow
     */
    Route::get('/call-flow/set', 'CallsController@index')->name('set_call_flow');
    Route::post('/call-flow/update', 'CallsController@update')->name('update_call_flow');
    Route::post('/call-flow/create', 'CallsController@create')->name('create_outbound_call');

    /**
     * Calls logs
     */
    Route::get('call-logs/inbound', 'CallsController@indexInboundCallsLogs')->name('inbound_calls_logs');
    Route::get('call-logs/outbound', 'CallsController@indexOutboundCallsLogs')->name('outbound_calls_logs');
});

// Inbound Message URL
Route::post('/twilio/receive-sms', 'MessagesController@receiveMessage')->name('twilio_sms_url');

// Outbound REST API Call Answer URL
Route::post('/twilio/outbound-call/answer', 'CallsController@outboundCallAnswerURL')->name('twilio_outbound_call_url');

// Gather Action URL
Route::post('/twilio/outbound-call/gather/answer', 'CallsController@gatherActionURL')->name('twilio_outbound_gather_action_url');

Route::post('/twilio/outbound-call/dial/action-url', 'CallsController@dialActionURL')->name('twilio_outbound_dial_action_url');

Route::get('/test', function () {
    \Mail::to('adnan.shabbir@icloud.com')->send(new DailyCallsLogs);

    \Mail::to('adnan.shabbir@icloud.com')->send(new DailyCallsLogs);
});

Route::get('/basic', function () {
    //mail('adnan.shabbir@icloud.com','test','testing php mailer');
    //
    //Mail::send('emails.reminder', ['user' => $user], function ($m) use ($user) {
    //    $m->from('hello@app.com', 'Your Application');
    //
    //    $m->to($user->email, $user->name)->subject('Your Reminder!');
    //});
    //

    $title   = 'Test email';
    $content = 'testing email from php mail';
    Mail::send('emails.reports.test', [ 'title' => $title, 'content' => $content ], function ( $message ) {
        $message->from('adnan.shabbir@outlook.com', 'Adnan Shabbir Rao');
        $message->to('adnan.shabbir@icloud.com');
    });
});