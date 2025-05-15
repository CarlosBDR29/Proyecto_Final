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
        Schema::create('proveedor', function (Blueprint $table) {
            $table->id('Id_Prove');
            $table->string('Nombre_Prove', 45);
            $table->string('CIF', 45);
            $table->string('Telefono', 45)->nullable();
            $table->string('Direccion', 145)->nullable();
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
        Schema::dropIfExists('proveedor');
    }
};
