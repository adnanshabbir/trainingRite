<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up ()
    {
        Schema::create('messages', function ( Blueprint $table ) {

            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('campaign_id')->nullable();
            $table->string('message_uuid')->unique()->nullable();
            $table->string('to', 15);
            $table->string('from', 15);
            $table->text('body');
            $table->string('customer_number', 15)->nullable();
            $table->string('direction', 10);
            $table->string('status', 15);
            $table->timestamps();

            $table->index('user_id');
            $table->index('campaign_id');
            $table->index('customer_number');
            $table->index('to');
            $table->index('from');
            $table->index('direction');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down ()
    {
        Schema::dropIfExists('messages');
    }
}
