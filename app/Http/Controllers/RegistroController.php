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
        $correo = $request->input('Correo');
        $contrasenya = $request->input('Contrasenya');
        $nombre = $request->input('Nombre');
    
        $existe = DB::table('Usuario')->where('Correo', $correo)->exists();
    
        if ($existe) {
            return back()->with('error', 'Este correo ya está registrado');
        }
    
        $id = DB::table('Usuario')->insertGetId([
            'Nombre_Usu' => $nombre,
            'Correo' => $correo,
            'Contrasenya' => Hash::make($contrasenya)
        ]);
    
        DB::table('Almacen')->insert([
            'Nombre_Alm' => 'Almacén Entrada',
            'Id_Usu' => $id
        ]);
    
        session(['usuario_id' => $id]);
    
        return redirect('/menu');
    }
}
