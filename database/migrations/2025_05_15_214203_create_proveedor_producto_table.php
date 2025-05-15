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
        Schema::create('proveedor_producto', function (Blueprint $table) {
            $table->unsignedBigInteger('Id_Prove');
            $table->unsignedBigInteger('Id_Pro');
            $table->float('Precio_Unidad_Prove', 10);
            $table->timestamps();
    
            $table->primary(['Id_Prove', 'Id_Pro']);
            $table->foreign('Id_Prove')->references('Id_Prove')->on('proveedor')->onDelete('cascade');
            $table->foreign('Id_Pro')->references('Id_Pro')->on('producto')->onDelete('cascade');
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proveedor_producto');
    }
};
