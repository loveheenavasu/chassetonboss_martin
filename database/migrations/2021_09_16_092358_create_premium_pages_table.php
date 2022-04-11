<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePremiumPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('premium_pages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('connection_id');
            $table->unsignedBigInteger('premium_template_id');
            $table->string('slug');
            $table->string('product_name')->nullable();
            $table->string('affiliate_link')->nullable();
            $table->string('header_text')->nullable();
            $table->string('hero_image')->nullable();
            $table->string('hero_text1')->nullable();
            $table->string('hero_text2')->nullable();
            $table->string('product_header')->nullable();
            $table->string('product_image1')->nullable();
            $table->string('product_image1_link')->nullable();
            $table->string('product_image2')->nullable();
            $table->string('product_image2_link')->nullable();
            $table->string('product_image3')->nullable();
            $table->string('product_image3_link')->nullable();
            $table->string('button')->nullable();
            $table->longText('custom_code')->nullable();
            $table->timestamps();
            $table->foreign('connection_id')
                ->references('id')->on('connections');
            $table->foreign('premium_template_id')
                ->references('id')->on('premium_templates');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('premium_pages');
    }
}
