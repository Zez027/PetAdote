<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->string('pais')->nullable()->after('descricao');
            $table->string('estado')->nullable()->after('pais');
            $table->string('cidade')->nullable()->after('estado');
        });
    }

    public function down()
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->dropColumn(['pais', 'estado', 'cidade']);
        });
    }
};
