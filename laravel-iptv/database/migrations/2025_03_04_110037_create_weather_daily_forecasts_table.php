<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('weather_daily_forecasts', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date')->nullable();
            $table->string('description', 100)->nullable();
            $table->string('humidity', 100)->nullable();
            $table->string('icon', 100)->nullable();
            $table->float('wind_speed', 14, 2)->nullable();
            $table->dateTime('sunrise')->nullable();
            $table->dateTime('sunset')->nullable();
            $table->float('temp_day', 14, 2)->nullable();
            $table->float('temp_min', 14, 2)->nullable();
            $table->float('temp_max', 14, 2)->nullable();
            $table->float('temp_night', 14, 2)->nullable();
            $table->float('temp_eve', 14, 2)->nullable();
            $table->float('temp_morn', 14, 2)->nullable();
            $table->integer('pressure')->nullable();
            $table->float('dew_point', 14, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weather_daily_forecasts');
    }
};
