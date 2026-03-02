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
        Schema::table('users', function (Blueprint $table) {
            $table->string('tipo_residencia')->nullable()->after('contato');
            $table->string('seguranca')->nullable()->after('tipo_residencia');
            $table->string('outros_pets')->nullable()->after('seguranca');
            $table->string('criancas')->nullable()->after('outros_pets');
            $table->string('tempo_sozinho')->nullable()->after('criancas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['tipo_residencia', 'seguranca', 'outros_pets', 'criancas', 'tempo_sozinho']);
        });
    }
};
