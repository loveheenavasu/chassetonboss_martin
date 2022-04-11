<?php
use App\Models\Event;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event', function (Blueprint $table) {
            $table->id();
            $table->foreignId('connection_id')->nullable();
            $table->foreignId('template_id');
            $table->string('connection_type');
            $table->string('name');
            $table->bigInteger('emails_count');
            $table->boolean('randomize_emails_order')->default(false);
            $table->string('timezone')->default('UTC');
            $table->enum('schedule', Event::schedules());
            $table->json('schedule_days')->nullable();
            $table->integer('schedule_weekday')->nullable();
            $table->integer('schedule_monthday')->nullable();
            $table->enum('schedule_time', Event::scheduleTimes());
            $table->integer('schedule_hour')->nullable();
            $table->integer('schedule_hour_from')->nullable();
            $table->integer('schedule_hour_to')->nullable();
            $table->enum('status', Event::statuses())->default(Event::STATUS_STOPPED);
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
        Schema::dropIfExists('event');
    }
}
