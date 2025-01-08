<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('user_id'); // Clave primaria autoincremental
            $table->enum('user_rol', ['USER', 'INVEST', 'TAX'])->default('USER'); // Rol por defecto: 'USER'
            $table->string('user_nombre', 50);
            $table->string('user_apellido', 50);
            $table->string('user_email', 50)->unique();
            $table->string('user_password');
            $table->string('user_telefono', 20)->nullable();
            $table->timestamp('user_creacion')->useCurrent();
            $table->timestamp('user_actualizacion')->nullable();
            $table->boolean('user_estado')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
