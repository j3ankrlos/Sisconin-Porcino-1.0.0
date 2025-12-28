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
        Schema::create('sitios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('granja_id')->constrained('granjas')->onDelete('cascade');
            $table->string('nombre');
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('granja_id')->nullable()->constrained('granjas')->onDelete('set null');
            $table->foreignId('sitio_id')->nullable()->constrained('sitios')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['users_granja_id_foreign']);
            $table->dropForeign(['users_sitio_id_foreign']);
            $table->dropColumn(['granja_id', 'sitio_id']);
        });
        Schema::dropIfExists('sitios');
    }
};
