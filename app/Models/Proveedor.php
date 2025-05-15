<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'Proveedor';
    protected $primaryKey = 'Id_Prove';
    public $timestamps = false;
}
