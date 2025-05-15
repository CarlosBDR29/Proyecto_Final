<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->session()->has('usuario_id')) {
            return redirect('/login')->with('error', 'Debes iniciar sesión primero.');
        }

        return view('menu');
    }
}
