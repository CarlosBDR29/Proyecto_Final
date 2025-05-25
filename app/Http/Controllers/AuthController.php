<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        // Obtiene el valor del campo 'Correo' del formulario enviado
        $correo = $request->input('Correo');
        
        // Obtiene el valor del campo 'Contrasenya' del formulario enviado
        $contrasenya = $request->input('Contrasenya');
    
        // Busca en la base de datos un usuario con ese correo
        $usuario = DB::table('Usuario')->where('Correo', $correo)->first();
    
        // Si se encontró el usuario y la contraseña es correcta (usando Hash::check para comparar con la contraseña encriptada)
        if ($usuario && Hash::check($contrasenya, $usuario->Contrasenya)) {

            // Guarda el ID del usuario en la sesión para mantenerlo identificado
            session(['usuario_id' => $usuario->Id_Usu]);

            // Redirige al menú principal de la aplicación
            return redirect('/menu');
        }

        // Si no se encontró el usuario o la contraseña es incorrecta, vuelve atrás con un mensaje de error
        return back()->with('error', 'Correo o contraseña incorrectos');
    }

}
