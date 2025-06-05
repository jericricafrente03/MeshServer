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
        Schema::create('top_recommended_fnbs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fnb_id');
            $table->date('recommended_month');
            $table->integer('popularity_score');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('top_recommended_fnbs');
    }
};
