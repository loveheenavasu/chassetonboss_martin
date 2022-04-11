<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('connection_id');
            $table->unsignedBigInteger('template_id');
            $table->string('slug');
            $table->string('product')->nullable();
            $table->string('affiliate_link')->nullable();
            $table->string('name')->nullable();
//            $table->json('substitutions');
            $table->longText('custom_code')->nullable();
            $table->timestamps();

            $table->foreign('connection_id')
                ->references('id')->on('connections');
            $table->foreign('template_id')
                ->references('id')->on('templates');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages');
    }
}
