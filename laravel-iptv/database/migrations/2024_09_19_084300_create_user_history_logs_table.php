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
        Schema::create('user_history_logs', function (Blueprint $table) {
            $table->id();
            $table->text('activity')->nullable();
            $table->text('description')->nullable();
            $table->integer('user_id');
            $table->text('username');
            $table->text('ip_address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_history_logs');
    }
};
