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
        Schema::create('system_configs', function (Blueprint $table) {
            $table->id();
            $table->text('name')->nullable();
            $table->text('description')->nullable();
            $table->text('street')->nullable();
            $table->text('city')->nullable();
            $table->string('country_code', 5)->nullable();
            $table->string('time_zone', 125)->nullable();
            $table->string('email', 125)->nullable();
            $table->string('currency', 65)->nullable();
            $table->string('website', 125)->nullable();
            $table->text('logo')->nullable();
            $table->string('lat', 50)->nullable();
            $table->string('lon', 50)->nullable();
            $table->text('welcome_message')->nullable();
            $table->integer('max_idle')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_configs');
    }
};
