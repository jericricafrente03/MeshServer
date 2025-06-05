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
        Schema::table('hospitality_services', function (Blueprint $table) {
            $table->softDeletes();
            $table->unsignedSmallInteger('order_no')->nullable()->after('is_enable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hospitality_services', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn('order_no');
        });
    }
};
