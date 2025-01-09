<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Asegurar que `tipos_usuarios` existe antes de crear `usuarios`
        if (!Schema::hasTable('tipos_usuarios')) {
            throw new Exception('La tabla tipos_usuarios no existe. Ejecute primero la migración de tipos_usuarios.');
        }

        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('user_id');
            $table->unsignedBigInteger('tipus_id')->default(1); // USER por defecto
            $table->string('user_nombre', 50);
            $table->string('user_apellido', 50);
            $table->string('user_email', 35)->unique();
            $table->string('user_password');
            $table->string('user_telefono', 10);
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
