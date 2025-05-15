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
        Schema::create('linea_pedido', function (Blueprint $table) {
            $table->id('Id_Line');
            $table->integer('Cantidad');
            $table->float('Precio_Unidad', 10);
            $table->unsignedBigInteger('Id_Pro');
            $table->unsignedBigInteger('Id_Cabe');
            $table->timestamps();
    
            $table->foreign('Id_Pro')->references('Id_Pro')->on('producto')->onDelete('cascade');
            $table->foreign('Id_Cabe')->references('Id_Cabe')->on('cabecera_pedido')->onDelete('cascade');
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('linea_pedido');
    }
};
