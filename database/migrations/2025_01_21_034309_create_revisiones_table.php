<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('revisiones', function (Blueprint $table) {
            $table->id();
            $table->integer('esp_id');
            $table->foreign('esp_id')
                  ->references('esp_id')
                  ->on('tax_especies')
                  ->onDelete('cascade');
            $table->enum('estado', ['pendiente', 'aprobado', 'rechazado'])->default('pendiente');
            $table->text('comentario')->nullable();
            // Actualizamos para usar user_id en lugar de id
            $table->integer('user_id');
            $table->foreign('user_id')
                  ->references('user_id')  // Cambiado a user_id
                  ->on('usuarios')
                  ->onDelete('cascade');
            $table->integer('taxonomo_id')->nullable();
            $table->foreign('taxonomo_id')
                  ->references('user_id')  // Cambiado a user_id
                  ->on('usuarios')
                  ->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('revisiones');
    }
};