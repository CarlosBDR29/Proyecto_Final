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
        $correo = $request->input('Correo');
        $contrasenya = $request->input('Contrasenya');
    
        $usuario = DB::table('Usuario')->where('Correo', $correo)->first();
    
        if ($usuario && Hash::check($contrasenya, $usuario->Contrasenya)) {
            session(['usuario_id' => $usuario->Id_Usu]);
            return redirect('/menu');
        }
    
        return back()->with('error', 'Correo o contraseña incorrectos');
    }

}
