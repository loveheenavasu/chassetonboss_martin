<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLandingPagedConnections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('landing_paged_connections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('connection_id');
            $table->unsignedBigInteger('landing_page_id');
            // $table->foreignId('connection_id');
            // $table->foreignId('landing_page_id');
            $table->foreign('connection_id')
                ->references('id')->on('connections')->onDelete('cascade');;
            $table->foreign('landing_page_id')
                ->references('id')->on('landing_page')->onDelete('cascade');;
            $table->boolean('was_deployed');
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
        Schema::dropIfExists('landing_paged_connections');
    }
}
