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
            // 1. Ensure granular location (Fix renaming if it happened)
            if (Schema::hasColumn('animals', 'area_id') && !Schema::hasColumn('animals', 'seccion_id')) {
                $table->renameColumn('area_id', 'seccion_id');
            }
            
            // 2. Add Tenant ID for easy scoping
            if (!Schema::hasColumn('animals', 'empresa_id')) {
                $table->foreignId('empresa_id')->nullable()->after('id')->constrained('empresas')->onDelete('cascade');
            }
        });

        // Also fix the relationship in animal_eventos if needed
        Schema::table('animal_eventos', function (Blueprint $table) {
             if (!Schema::hasColumn('animal_eventos', 'empresa_id')) {
                $table->foreignId('empresa_id')->nullable()->after('id')->constrained('empresas')->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('animals', function (Blueprint $table) {
            $table->dropForeign(['empresa_id']);
            $table->dropColumn('empresa_id');
            // Revert rename? Preferably not if seccion_id is correct.
        });
        
         Schema::table('animal_eventos', function (Blueprint $table) {
            $table->dropForeign(['empresa_id']);
            $table->dropColumn('empresa_id');
        });
    }
};
