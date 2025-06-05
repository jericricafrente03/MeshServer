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
        Schema::create('video_ads', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('video_uri')->nullable();
            $table->unsignedTinyInteger('is_enable')->length(1)->default(1);
            $table->unsignedSmallInteger('order_no');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_ads');
    }
};
