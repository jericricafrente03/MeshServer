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
        Schema::create('broadcast_messages', function (Blueprint $table) {
            $table->id();
            $table->text('img_uri')->nullable();
            $table->text('img_thumbnail_uri')->nullable();
            $table->integer('duration');
            $table->text('message');
            $table->unsignedTinyInteger('broadcast_type_id');
            $table->unsignedTinyInteger('type_id');
            $table->unsignedTinyInteger('device_category_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('broadcast_messages');
    }
};
