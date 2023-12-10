<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained('activities')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('mood_id')->constrained('moods')->cascadeOnDelete();
            $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete();
            $table->text('note')->nullable();
            $table->timestamp('form')->nullable();
            $table->timestamp('to')->nullable();
            $table->date('date')->nullable();
            $table->integer('dayDate')->nullable();
            $table->integer('monthDate')->nullable();
            $table->integer('yearDate')->nullable();
            $table->integer('hoursDate')->nullable();
            $table->integer('minutesDate')->nullable();
            $table->integer('secondsDate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_activities');
    }
}
