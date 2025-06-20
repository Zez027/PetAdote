<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePetPhotosTable extends Migration
{
    public function up()
    {
        Schema::create('pet_photos', function (Blueprint $table) {
            $table->id();
            $table->string('foto');
            $table->unsignedBigInteger('pet_id');
            $table->foreign('pet_id')->references('id')->on('pets')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pet_photos');
    }
}
