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
        Schema::create('weather_hourly_forecasts', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date')->nullable();
            $table->string('description', 100)->nullable();
            $table->string('humidity', 100)->nullable();
            $table->string('icon', 100)->nullable();
            $table->float('temp', 14, 2)->nullable();
            $table->float('wind_speed', 14, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weather_hourly_forecasts');
    }
};
