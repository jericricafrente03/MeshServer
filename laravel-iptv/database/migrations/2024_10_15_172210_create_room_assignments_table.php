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
        Schema::create('room_assignments', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name', 100)->index()->nullable();
            $table->integer('customer_id')->index();
            $table->string('room_number', 100)->nullable();
            $table->integer('room_id');
            $table->dateTime('check_in');
            $table->dateTime('check_out')->nullable();
            $table->dateTime('s_check_out')->nullable();
            $table->string('reserve_no', 255)->index()->nullable();
            $table->unsignedTinyInteger('cs')->length(1)->nullable();
            $table->unsignedTinyInteger('gs')->length(1)->default(0);
            $table->unsignedTinyInteger('is_checkout')->length(1)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_assignments');
    }
};
