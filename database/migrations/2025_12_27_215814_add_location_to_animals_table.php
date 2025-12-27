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
        Schema::table('animals', function (Blueprint $table) {
            $table->foreignId('seccion_id')->nullable()->after('especie_id')->constrained('secciones')->onDelete('set null');
            $table->integer('corral')->nullable()->after('seccion_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('animals', function (Blueprint $table) {
            $table->dropForeign(['seccion_id']);
            $table->dropColumn(['seccion_id', 'corral']);
        });
    }
};
