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
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('identification_number')->unique();
        $table->string('name');
        $table->string('last_name');
        $table->string('email')->unique()->nullable();
        $table->string('phone_number')->nullable();
        $table->string('location')->nullable();
        $table->string('password');
        $table->enum('type', ['patient', 'professional']);
        $table->boolean('first_time_login')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
