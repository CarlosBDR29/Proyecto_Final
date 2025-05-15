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
        Schema::create('usuario', function (Blueprint $table) {
            $table->id('Id_Usu');
            $table->string('Nombre_Usu', 145);
            $table->string('Correo', 145)->unique();
            $table->string('Contrasenya', 150);
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario');
    }
};
