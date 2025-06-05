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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->index(); // varchar(50) and indexed
            $table->foreignId('category_id')
                ->constrained('room_categories')
                ->onDelete('cascade')
                ->index(); // Foreign key, indexed
            $table->string('floor_number', 50)->nullable(); // varchar(50), nullable
            $table->string('room_status', 50)->default('available'); // varchar(50), default value
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
