<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJournalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('journals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('iconType');
            $table->string('nameType');
            $table->string('image');
            $table->string('moodImage');
            $table->string('title');
            $table->string('subtitle');
            $table->string('description');
            $table->date('date')->nullable();
            $table->integer('dayDate')->nullable();
            $table->integer('monthDate')->nullable();
            $table->integer('yearDate')->nullable();
            $table->integer('hoursDate')->nullable();
            $table->integer('minutesDate')->nullable();
            $table->integer('secondsDate')->nullable();
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
        Schema::dropIfExists('journal');
    }
}
