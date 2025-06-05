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
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->string('title', 10)->nullable();
            $table->string('firstname', 255)->index();
            $table->string('lastname', 255)->index();
            $table->date('birthdate')->nullable();
            $table->text('street1')->nullable();
            $table->text('street2')->nullable();
            $table->text('city')->nullable();
            $table->text('state_region')->nullable();
            $table->string('country_id', 11)->index()->nullable();
            $table->string('zip_code', 10)->nullable();
            $table->string('mobile_no', 20)->nullable();
            $table->string('landline_no', 20)->nullable();
            $table->text('email')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};
