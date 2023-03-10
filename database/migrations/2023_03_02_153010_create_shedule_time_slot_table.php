<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('time_slots', function (Blueprint $table) {
            $table->id();
            $table->integer('schedule_id');
            $table->timestamps();
            $table->date('date');
            $table->string('start_time');
            $table->string('end_time');
        });

        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
        });
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            /** резонно будет добавить функцию strftime()|gmstrftime() */
            $table->timestamps();
            $table->integer('user_id');
            $table->integer('type_id');
            $table->integer('timezone');
        });
        Schema::create('user_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::drop('user_types');
        Schema::drop('user_settings');
        Schema::drop('schedules');
        Schema::drop('time_slots');
    }
};
