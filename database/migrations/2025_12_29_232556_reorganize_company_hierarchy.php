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
        // 1. Create 'sucursales' table
        if (!Schema::hasTable('sucursales')) {
            Schema::create('sucursales', function (Blueprint $table) {
                $table->id();
                $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');
                $table->string('nombre');
                $table->string('direccion')->nullable();
                $table->timestamps();
            });
        }

        // 2. Rename 'sitios' to 'unidades'
        if (Schema::hasTable('sitios')) {
            Schema::rename('sitios', 'unidades');
        }
        
        if (Schema::hasTable('unidades') && !Schema::hasColumn('unidades', 'sucursal_id')) {
            Schema::table('unidades', function (Blueprint $table) {
                $table->foreignId('sucursal_id')->nullable()->after('id')->constrained('sucursales')->onDelete('cascade');
            });
        }

        // 3. Rename 'granjas' to 'areas'
        if (Schema::hasTable('granjas')) {
            Schema::rename('granjas', 'areas');
        }

        if (Schema::hasTable('areas') && !Schema::hasColumn('areas', 'unidad_id')) {
            Schema::table('areas', function (Blueprint $table) {
                $table->foreignId('unidad_id')->nullable()->after('id')->constrained('unidades')->onDelete('cascade');
            });
        }

        // 4. Update 'naves' foreign key
        if (Schema::hasTable('naves') && Schema::hasColumn('naves', 'granja_id')) {
            Schema::table('naves', function (Blueprint $table) {
                $table->renameColumn('granja_id', 'area_id');
            });
        }

        // 5. Update pivot tables
        if (Schema::hasTable('granja_especializacion')) {
            Schema::rename('granja_especializacion', 'area_especializacion');
        }
        if (Schema::hasTable('area_especializacion') && Schema::hasColumn('area_especializacion', 'granja_id')) {
            Schema::table('area_especializacion', function (Blueprint $table) {
                $table->renameColumn('granja_id', 'area_id');
            });
        }

        if (Schema::hasTable('especie_granja')) {
            Schema::rename('especie_granja', 'area_especie');
        }
        if (Schema::hasTable('area_especie') && Schema::hasColumn('area_especie', 'granja_id')) {
            Schema::table('area_especie', function (Blueprint $table) {
                $table->renameColumn('granja_id', 'area_id');
            });
        }

        // 6. Update 'users' table
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'granja_id')) {
                $table->renameColumn('granja_id', 'area_id');
            }
            if (Schema::hasColumn('users', 'sitio_id')) {
                $table->renameColumn('sitio_id', 'unidad_id');
            }
            if (!Schema::hasColumn('users', 'sucursal_id')) {
                $table->foreignId('sucursal_id')->nullable()->constrained('sucursales')->onDelete('set null');
            }
        });

        // 7. Update 'animals' table columns
        if (Schema::hasTable('animals') && Schema::hasColumn('animals', 'seccion_id')) {
            Schema::table('animals', function (Blueprint $table) {
                $table->renameColumn('seccion_id', 'area_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['users_sucursal_id_foreign']);
            $table->dropColumn('sucursal_id');
            $table->renameColumn('area_id', 'granja_id');
            $table->renameColumn('unidad_id', 'sitio_id');
        });

        Schema::table('area_especie', function (Blueprint $table) {
            $table->renameColumn('area_id', 'granja_id');
        });
        Schema::rename('area_especie', 'especie_granja');

        Schema::table('area_especializacion', function (Blueprint $table) {
            $table->renameColumn('area_id', 'granja_id');
        });
        Schema::rename('area_especializacion', 'granja_especializacion');

        Schema::table('naves', function (Blueprint $table) {
            $table->renameColumn('area_id', 'granja_id');
        });

        Schema::table('areas', function (Blueprint $table) {
            $table->dropForeign(['areas_unidad_id_foreign']);
            $table->dropColumn('unidad_id');
        });
        Schema::rename('areas', 'granjas');

        Schema::table('unidades', function (Blueprint $table) {
            $table->dropForeign(['unidades_sucursal_id_foreign']);
            $table->dropColumn('sucursal_id');
        });
        Schema::rename('unidades', 'sitios');

        Schema::dropIfExists('sucursales');
    }
};
