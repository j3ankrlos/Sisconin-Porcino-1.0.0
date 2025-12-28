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
        Schema::table('granjas', function (Blueprint $table) {
            $table->string('etapa')->nullable()->after('nombre'); // Sitio I, Sitio II, Sitio III
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('granjas', function (Blueprint $table) {
            $table->dropColumn('etapa');
        });
    }
};
