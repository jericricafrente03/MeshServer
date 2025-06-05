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
        Schema::create('device_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 55);
            $table->text('description')->nullable();
            $table->smallInteger('order_no'); // smallint(5)
            $table->integer('theme_id')->nullable(); // int(11), nullable
            $table->integer('app_orientation')->default(1); // int(11), default 1
            $table->string('text_color', 20)->default('#33333f'); // default color for text
            $table->string('background_color', 20)->default('#bbbbbb'); // background color
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_categories');
    }
};
