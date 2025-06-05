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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->text('mac_address')->nullable();
            $table->text('os_version')->nullable();
            $table->dateTime('time_activated');
            $table->string('current_status', 10)->nullable();
            $table->text('last_action')->nullable();
            $table->dateTime('time_last_action');
            $table->integer('property_id')->nullable();
            $table->text('api_id')->nullable();
            $table->text('ip4_address')->nullable();
            $table->unsignedInteger('activity_counter')->length(10)->default(0);
            $table->unsignedInteger('category_id')->length(7)->index()->default(0);
            $table->tinyInteger('checked')->length(1)->index()->default(0);
            $table->string('stp_app_version', 10)->nullable();
            $table->string('wifi_ssid', 25)->nullable();
            $table->string('wifi_password', 50)->nullable();
            $table->unsignedTinyInteger('usb')->length(1)->default(1);
            $table->unsignedTinyInteger('adb')->length(1)->default(0);
            $table->string('version', 10)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
