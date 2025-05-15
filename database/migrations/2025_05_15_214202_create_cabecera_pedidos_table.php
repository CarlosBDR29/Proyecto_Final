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
        Schema::create('cabecera_pedido', function (Blueprint $table) {
            $table->id('Id_Cabe');
            $table->date('Fecha_Ped')->nullable();
            $table->string('Estado', 45)->nullable();
            $table->unsignedBigInteger('Id_Prove');
            $table->unsignedBigInteger('Id_Usu');
            $table->timestamps();
    
            $table->foreign('Id_Prove')->references('Id_Prove')->on('proveedor')->onDelete('cascade');
            $table->foreign('Id_Usu')->references('Id_Usu')->on('usuario')->onDelete('cascade');
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cabecera_pedido');
    }
};
