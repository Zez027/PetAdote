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
       Schema::create('reports', function (Blueprint $table) {
            $table->id();
            // ID do utilizador que está a fazer a denúncia
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Cria os campos reportable_id e reportable_type automaticamente
            $table->morphs('reportable'); 
            
            $table->string('reason'); // Ex: Venda de animal, Maus-tratos, etc.
            $table->text('description')->nullable();
            $table->enum('status', ['pendente', 'em_analise', 'resolvida', 'descartada'])->default('pendente');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
