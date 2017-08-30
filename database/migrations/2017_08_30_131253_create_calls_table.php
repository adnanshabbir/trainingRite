<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up ()
    {
        Schema::create('calls', function ( Blueprint $table ) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('call_sid')->nullable();
            $table->string('to', 15)->nullable();
            $table->string('from', 15)->nullable();
            $table->string('direction', 15)->nullable();
            $table->time('call_duration')->nullable();
            $table->string('status', 25)->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('to');
            $table->index('from');
            $table->index('call_sid');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down ()
    {
        Schema::dropIfExists('calls');
    }
}
