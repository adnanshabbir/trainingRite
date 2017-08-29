<?php

// Front page
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
    Route::post('numbers/from/fetch', 'MessagesController@fetchTwilioNumbers')->name('get_from_numbers');

});



// inbound message url
Route::get('/twilio/receive-sms', 'MessagesController@receiveMessage')->name('twilio_sms_url');
//Route::post('/twilio/receive-sms', 'MessagesController@receiveMessage')->name('twilio_sms_url');
