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
        Schema::create('adb_settings', function (Blueprint $table) {
            $table->id();
            $table->string('server_interface', 50)->nullable();
            $table->string('launcher_package_name', 100)->nullable();
            $table->string('server_ip_address', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adb_settings');
    }
};
