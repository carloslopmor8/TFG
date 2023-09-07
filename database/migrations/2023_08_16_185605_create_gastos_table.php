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
        Schema::create('gastos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('proyecto_id');
            $table->string('id_factura')->nullable();
            $table->string('nombre')->nullable();
            $table->longText('observacion')->nullable();
            $table->double('cantidad');
            $table->double('cantidad_iva')->nullable();
            $table->boolean('anadir_iva')->default(false);
            $table->timestamps();

            $table->foreign('proyecto_id')->references('id')->on('proyectos'); 

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gastos');
    }
};
