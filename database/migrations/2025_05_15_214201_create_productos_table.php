<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up():void
    {
        Schema::create('producto', function (Blueprint $table) {
            $table->id('Id_Pro');
            $table->string('Nombre_Pro', 145);
            $table->string('Descripcion', 500)->nullable();
            $table->float('Precio', 10)->nullable();
            $table->unsignedBigInteger('Id_Usu');
            $table->timestamps();
    
            $table->foreign('Id_Usu')->references('Id_Usu')->on('usuario')->onDelete('cascade');
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producto');
    }
};
