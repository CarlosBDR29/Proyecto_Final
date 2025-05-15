<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login'); // crea una vista login.blade.php
    }

    public function login(Request $request)
    {
        $credentials = [
            'Correo' => $request->input('Correo'),
            'password' => $request->input('Contrasenya'),
        ];
        
        $user = \App\Models\Usuario::where('Correo', $request->input('Correo'))->first();
        dd($user->getAuthPassword());

        if (Auth::attempt($credentials)) {
            return redirect()->intended('dashboard');
        } else {
            return back()->with('error', 'Correo o contraseña incorrectos');
        }

    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
