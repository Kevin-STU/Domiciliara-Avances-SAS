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
        Schema::table('historias', function (Blueprint $table) {
            $table->boolean('assisted')->default(false)->after('recommendations');
            $table->string('firma')->nullable()->after('assisted');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('historias', function (Blueprint $table) {
            $table->dropColumn('assisted');
            $table->dropColumn('firma');
        });
    }
};
