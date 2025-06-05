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
        Schema::create('adb_files', function (Blueprint $table) {
            $table->id();
            $table->string('mac_address', 100)->nullable();
            $table->string('ip4_address', 50)->nullable();
            $table->string('room', 100)->nullable();
            $table->text('img_uri')->nullable();
            $table->string('type', 30)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adb_files');
    }
};
