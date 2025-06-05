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
        Schema::create('default_apps', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->index();
            $table->string('method', 50);
            $table->unsignedTinyInteger('is_enable')->length(1)->default(0);
            $table->unsignedTinyInteger('is_enable_in_analytics')->length(1)->default(0);
            $table->string('color', 20)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('default_apps');
    }
};
