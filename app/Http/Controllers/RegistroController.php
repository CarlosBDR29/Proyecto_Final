<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegistroController extends Controller
{
    public function showRegisterForm()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        // Obtiene el correo, contraseña y nombre enviados desde el formulario de registro
        $correo = $request->input('Correo');
        $contrasenya = $request->input('Contrasenya');
        $nombre = $request->input('Nombre');
    
        // Verifica si ya existe un usuario con ese correo en la base de datos
        $existe = DB::table('Usuario')->where('Correo', $correo)->exists();
    
        // Si el correo ya está registrado, vuelve atrás con un mensaje de error
        if ($existe) {
            return back()->with('error', 'Este correo ya está registrado');
        }
    
        // Inserta el nuevo usuario en la tabla 'Usuario' y guarda el ID generado
        $id = DB::table('Usuario')->insertGetId([
            'Nombre_Usu' => $nombre,
            'Correo' => $correo,
            'Contrasenya' => Hash::make($contrasenya)
        ]);
    
        // Crea automáticamente un almacén llamado 'Almacén Entrada' asociado al nuevo usuario
        DB::table('Almacen')->insert([
            'Nombre_Alm' => 'Almacén Entrada',
            'Id_Usu' => $id
        ]);
    
        // Guarda el ID del usuario en la sesión para mantenerlo identificado
        session(['usuario_id' => $id]);
    
        return redirect('/menu');
    }
}
