<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // IMPORTAR DB

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tipos_usuarios', function (Blueprint $table) {
            $table->id('tipus_id');
            $table->string('tipus_detalles', 50);
            $table->timestamps();
        });

        // Insertar los 3 tipos de usuario por defecto
        DB::table('tipos_usuarios')->insert([
            ['tipus_detalles' => 'USER'],
            ['tipus_detalles' => 'INVEST'],
            ['tipus_detalles' => 'TAX'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('tipos_usuarios');
    }
};
