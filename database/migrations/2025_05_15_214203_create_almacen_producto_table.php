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
        Schema::create('almacen_producto', function (Blueprint $table) {
            $table->unsignedBigInteger('Id_Alm');
            $table->unsignedBigInteger('Id_Pro');
            $table->integer('Stock');
            $table->timestamps();
    
            $table->primary(['Id_Alm', 'Id_Pro']);
            $table->foreign('Id_Alm')->references('Id_Alm')->on('almacen')->onDelete('cascade');
            $table->foreign('Id_Pro')->references('Id_Pro')->on('producto')->onDelete('cascade');
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('almacen_producto');
    }
};
