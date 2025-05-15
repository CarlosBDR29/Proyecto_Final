<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'Producto';  // El nombre de tu tabla en la base de datos
    protected $primaryKey = 'Id_Pro';  // La clave primaria de la tabla

    protected $fillable = [
        'Nombre_Pro', 
        'Descripcion', 
        'Precio', 
        'Id_Alm'
    ];  // Los campos que puedes llenar masivamente


    
}
