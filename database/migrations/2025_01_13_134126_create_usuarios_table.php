<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('user_nombre', 50);
            $table->string('user_apellido', 50);
            $table->string('user_email', 35)->unique();
            $table->string('password');
            $table->string('user_telefono', 10);
            $table->unsignedBigInteger('tipus_id')->default(1); // USER por defecto
            $table->boolean('user_estado')->default(true);
            $table->timestamps();

            // Definir la clave foránea
            $table->foreign('tipus_id')->references('tipus_id')->on('tipos_usuarios')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
