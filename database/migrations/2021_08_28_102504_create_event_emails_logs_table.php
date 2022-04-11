<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventEmailsLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_emails_logs', function (Blueprint $table) {
            $table->id();
            $table->string('email')->nullable();
            $table->string('status')->nullable();
            $table->string('type')->nullable();
            $table->string('event_name')->nullable();
            $table->string('event_id')->nullable();
            $table->string('timezone')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_emails_logs');
    }
}
