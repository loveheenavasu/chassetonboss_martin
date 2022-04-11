<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListingGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gmail_connection_groups', function (Blueprint $table) {
            $table->foreignId('groups_id');
            $table->foreignId('gmail_connection_id');
            $table->foreignId('event_id')->nullable();
            $table->string('sync_status')->default('no');
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
        Schema::dropIfExists('gmail_connection_groups');
    }
}
