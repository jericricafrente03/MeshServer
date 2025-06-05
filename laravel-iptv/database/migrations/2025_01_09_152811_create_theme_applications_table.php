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
        Schema::create('theme_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('theme_id');
            $table->unsignedBigInteger('application_id');
            $table->text('icon')->nullable();
            $table->text('active_icon')->nullable();
            $table->string('text_color', 20)->nullable();
            $table->string('active_text_color', 20)->nullable();
            $table->unsignedSmallInteger('order_no')->nullable();
            $table->unsignedTinyInteger('is_enable')->length(1)->default(0);
            $table->timestamps();

            $table->foreign('theme_id')
                ->references('id')
                ->on('themes')
                ->onDelete('cascade');
            $table->foreign('application_id')
                ->references('id')
                ->on('default_apps')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theme_applications');
    }
};
