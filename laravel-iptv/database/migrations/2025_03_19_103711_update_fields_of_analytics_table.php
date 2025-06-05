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
        Schema::table('analytics', function (Blueprint $table) {
            $table->renameColumn('room', 'room_id'); // Rename column
            $table->string('category_key')->nullable()->change(); // Make category_key nullable
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('analytics', function (Blueprint $table) {
            $table->renameColumn('room_id', 'room'); // Revert column name
            $table->string('category_key')->nullable(false)->change(); // Revert nullable change
        });
    }
};
