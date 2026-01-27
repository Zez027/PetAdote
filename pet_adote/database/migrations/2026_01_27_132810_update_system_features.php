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
        // 1. Adicionar campos de saúde na tabela 'pets'
        Schema::table('pets', function (Blueprint $table) {
            $table->boolean('vacinado')->default(false);
            $table->boolean('castrado')->default(false);
            $table->boolean('vermifugado')->default(false);
        });

        // 2. Tabela de Favoritos
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('pet_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // 3. Tabela de Pedidos de Adoção
        Schema::create('adoption_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Quem quer adotar
            $table->foreignId('pet_id')->constrained()->onDelete('cascade');  // Qual pet
            $table->enum('status', ['pendente', 'aprovado', 'rejeitado'])->default('pendente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
