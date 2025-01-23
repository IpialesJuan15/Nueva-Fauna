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
        if (!Schema::hasColumn('tax_especies', 'esp_estado_valid')) {
            Schema::table('tax_especies', function (Blueprint $table) {
                $table->boolean('esp_estado_valid')->default(false);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
{
    if (Schema::hasColumn('tax_especies', 'esp_estado_valid')) {
        Schema::table('tax_especies', function (Blueprint $table) {
            $table->dropColumn('esp_estado_valid');
        });
    }
}
};
