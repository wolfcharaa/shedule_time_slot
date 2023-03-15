<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('user_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->date('date');
        });
        Schema::create('time_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
            $table->string('title', 100);
            $table->string('start_time');
            $table->string('end_time');
            $table->text('description');
        });

        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            /** резонно будет добавить функцию strftime()|gmstrftime() */
            $table->timestamps();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('user_type_id')->constrained();
            $table->integer('timezone');
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::drop('user_settings');
        Schema::drop('time_slots');
        Schema::drop('schedules');
        Schema::drop('user_types');
    }
};
