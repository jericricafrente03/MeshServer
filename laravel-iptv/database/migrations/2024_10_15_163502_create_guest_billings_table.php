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
        Schema::create('guest_billings', function (Blueprint $table) {
            $table->id();
            $table->dateTime('transaction_datetime');
            $table->string('room_number', 100);
            $table->integer('room_id');
            $table->string('category', 100);
            $table->string('item_name', 100);
            $table->integer('item_id');
            $table->integer('quantity');
            $table->decimal('unit_price',12,2);
            $table->text('refno');
            $table->string('status', 50);
            $table->integer('status_id');
            $table->string('user', 100)->default('STB');
            $table->integer('user_id');
            $table->text('reserve_no')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedTinyInteger('is_paid')->length(1)->default(0);
            $table->integer('room_assignment_id');
            $table->string('guest_name', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guest_billings');
    }
};
