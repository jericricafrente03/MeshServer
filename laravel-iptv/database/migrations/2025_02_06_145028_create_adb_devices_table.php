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
        Schema::create('adb_devices', function (Blueprint $table) {
            $table->id();
            $table->text('ip4_address')->nullable();
            $table->text('mac_address')->nullable();
            $table->string('architecture', 50)->nullable();
            $table->string('model', 100)->nullable();
            $table->string('foreground', 50)->nullable();
            $table->string('launcher', 50)->nullable();
            $table->string('launcher_version', 50)->nullable();
            $table->string('room_name', 50)->nullable();
            $table->string('status', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adb_devices');
    }
};
