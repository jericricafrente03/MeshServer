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
        Schema::create('video_ads_rooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('video_ads_id');
            $table->unsignedBigInteger('room_id');
            $table->timestamps();

            $table->foreign('video_ads_id')
                ->references('id')
                ->on('video_ads')
                ->onDelete('cascade');
            $table->foreign('room_id')
                ->references('id')
                ->on('rooms')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_ads_rooms');
    }
};
