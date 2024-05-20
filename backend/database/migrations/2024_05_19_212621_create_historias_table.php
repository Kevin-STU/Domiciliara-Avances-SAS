<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoriasTable extends Migration
{
    public function up()
    {
        Schema::create('historias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('professional_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->text('patient_info');
            $table->dateTime('date_time');
            $table->string('consecutive');
            $table->text('current_status')->nullable();
            $table->text('antecedents')->nullable();
            $table->text('final_evolution')->nullable();
            $table->text('professional_concept')->nullable();
            $table->text('recommendations')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('historias');
    }
};
