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
        Schema::create('regular_messages', function (Blueprint $table) {
            $table->id();
            $table->string('from', 50)->index();
            $table->string('subject', 50)->index();
            $table->text('body');
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
        Schema::dropIfExists('regular_messages');
    }
};
