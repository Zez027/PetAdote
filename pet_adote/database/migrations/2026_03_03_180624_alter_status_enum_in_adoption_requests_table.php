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
        Schema::table('adoption_requests', function (Blueprint $table) {
            DB::statement("ALTER TABLE adoption_requests MODIFY COLUMN status ENUM('pendente', 'em_analise', 'aprovado', 'rejeitado') DEFAULT 'pendente'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('adoption_requests', function (Blueprint $table) {
            DB::statement("ALTER TABLE adoption_requests MODIFY COLUMN status ENUM('pendente', 'aprovado', 'rejeitado') DEFAULT 'pendente'");
        });
    }
};
