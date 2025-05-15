<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    protected $table = 'Usuario';
    public $timestamps = false;

    protected $fillable = ['Correo', 'Contrasenya'];

    public function getAuthPassword()
    {
        return $this->Contrasenya;
    }
}
