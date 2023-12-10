<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSectionsPointsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('social_points')->default(0);
            $table->integer('career_points')->default(0);
            $table->integer('learn_points')->default(0);
            $table->integer('spirit_points')->default(0);
            $table->integer('health_points')->default(0);
            $table->integer('emotion_points')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('social_points');
            $table->dropColumn('career_points');
            $table->dropColumn('learn_points');
            $table->dropColumn('spirit_points');
            $table->dropColumn('health_points');
            $table->dropColumn('emotion_points');
        });
    }
}
