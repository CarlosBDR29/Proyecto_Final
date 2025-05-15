<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InteriorAlmacenController extends Controller
{
    public function vista(Request $request)
    {
        if (!$request->session()->has('usuario_id') || !$request->session()->has('almacen_id')) {
            return redirect('/login');
        }

        return view('almacen');
    }

    public function datos(Request $request)
    {
        $usuarioId = $request->session()->get('usuario_id');
        $almacenId = $request->session()->get('almacen_id');

        // Comprobar que el almacén es del usuario
        $almacen = DB::table('Almacen')
            ->where('Id_Alm', $almacenId)
            ->where('Id_Usu', $usuarioId)
            ->first();

        if (!$almacen) {
            return response()->json(['success' => false]);
        }

        // Obtener productos asociados a este almacén con su stock
        $productos = DB::table('Almacen_Producto')
            ->join('Producto', 'Almacen_Producto.Id_Pro', '=', 'Producto.Id_Pro')
            ->where('Almacen_Producto.Id_Alm', $almacenId)
            ->select('Producto.Id_Pro', 'Producto.Nombre_Pro', 'Almacen_Producto.Stock')
            ->get();

        return response()->json([
            'success' => true,
            'almacen' => $almacen,
            'productos' => $productos
        ]);
    }


    public function buscar(Request $request)
    {
        $usuarioId = $request->session()->get('usuario_id');
        $almacenId = $request->session()->get('almacen_id');
        $nombre = $request->query('nombre');

        // Verificar que el almacén es del usuario
        $almacen = DB::table('Almacen')
            ->where('Id_Alm', $almacenId)
            ->where('Id_Usu', $usuarioId)
            ->first();

        if (!$almacen) {
            return response()->json(['success' => false]);
        }

        // Buscar productos asociados a este almacén cuyo nombre coincida
        $productos = DB::table('Almacen_Producto')
            ->join('Producto', 'Almacen_Producto.Id_Pro', '=', 'Producto.Id_Pro')
            ->where('Almacen_Producto.Id_Alm', $almacenId)
            ->where('Producto.Nombre_Pro', 'like', '%' . $nombre . '%')
            ->select('Producto.Id_Pro', 'Producto.Nombre_Pro', 'Almacen_Producto.Stock')
            ->get();

        return response()->json([
            'success' => true,
            'productos' => $productos
        ]);
    }

    public function guardarIdProducto(Request $request)
    {
        $request->session()->put('producto_id', $request->input('id'));
        return response()->json(['success' => true]);
    }
}
