<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class TodosProductosController extends Controller
{
    public function index()
    {
        $usuario_id = Session::get('usuario_id');

        if (!$usuario_id) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión');
        }

        $query = DB::table('Producto')
            ->leftJoin('Almacen_Producto', 'Producto.Id_Pro', '=', 'Almacen_Producto.Id_Pro')
            ->where('Producto.Id_Usu', $usuario_id)
            ->select(
                'Producto.Id_Pro',
                'Producto.Nombre_Pro',
                DB::raw('COALESCE(SUM(Almacen_Producto.Stock), 0) as Stock')
            )
            ->groupBy('Producto.Id_Pro', 'Producto.Nombre_Pro');

        if (request()->has('buscar') && request('buscar') != '') {
            $query->where('Producto.Nombre_Pro', 'like', '%' . request('buscar') . '%');
        }

        $productos = $query->get();

        return view('todos_productos', compact('productos'));
    }
}
