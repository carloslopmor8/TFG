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
       /*  Schema::create('proyecto_etiquetas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('proyecto_id');
            $table->string('etiqueta');
            $table->double('cantidad');
            $table->timestamps();

            $table->foreign('proyecto_id')->references('id')->on('proyectos'); 
        }); */
    }

    /**
     * Reverse the migrations.
     */
    /* public function down(): void
    {
        Schema::dropIfExists('proyecto_etiquetas');
    } */
};
