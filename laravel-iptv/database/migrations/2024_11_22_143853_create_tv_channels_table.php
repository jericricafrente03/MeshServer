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
        Schema::create('tv_channels', function (Blueprint $table) {
            $table->id();
            $table->string('channel', 50)->nullable();
            $table->string('name', 50)->index(); // varchar(50) and indexed
            $table->text('description')->nullable();
            $table->text('channel_uri')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->unsignedSmallInteger('order_no');
            $table->text('img_uri')->nullable();
            $table->text('img_thumbnail_uri')->nullable();
            $table->unsignedTinyInteger('is_enable')->length(1)->default(1);
            $table->timestamps();

            $table->foreign('category_id')
                ->references('id')
                ->on('tv_channel_categories')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tv_channels');
    }
};
