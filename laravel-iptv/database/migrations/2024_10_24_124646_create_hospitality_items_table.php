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
        Schema::create('hospitality_items', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->index(); // varchar(50) and indexed
            $table->text('description')->nullable();
            $table->decimal('unit_price',12,2)->default(0);
            $table->text('img_uri')->nullable();
            $table->text('img_thumbnail_uri')->nullable();
            $table->unsignedTinyInteger('is_enable')->length(1)->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospitality_items');
    }
};
