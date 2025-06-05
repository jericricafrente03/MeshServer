<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->foreignId('room_id')
                ->nullable() // Allow null in case a device isn't assigned to a room initially
                ->constrained('rooms') // Link the room_id to the rooms table
                ->onDelete('set null'); // If a room is deleted, set room_id to null for related devices
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->dropForeign(['room_id']); // Drop the foreign key constraint
            $table->dropColumn('room_id'); // Drop the room_id column
        });
    }
};
