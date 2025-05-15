<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ProductoTotalController extends Controller
{

    public function redirigirTotal(Request $request)
    {
        $request->session()->put('producto_id', $request->input('id'));
        return redirect('/producto_Total');
    }

    public function verProductoTotal(Request $request)
    {
        $idPro = $request->Id_Pro;
        Session::put('producto_id', $idPro);
        return redirect('/producto_total');
    }

    public function mostrarTotal(Request $request)
    {
        $id = $request->session()->get('producto_id');
        Session::put('destino_producto', "ProductoTotal");

        $producto = DB::table('Producto')
            ->where('Id_Pro', $id)
            ->select('Id_Pro', 'Nombre_Pro', 'Descripcion', 'Precio')
            ->first();

        if (!$producto) {
            return redirect('/todos_productos')->with('error', 'Producto no encontrado.');
        }

        $stockPorAlmacen = DB::table('Almacen_Producto')
            ->join('Almacen', 'Almacen_Producto.Id_Alm', '=', 'Almacen.Id_Alm')
            ->where('Almacen_Producto.Id_Pro', $id)
            ->select('Almacen.Nombre_Alm', 'Almacen_Producto.Stock')
            ->get();

        $stockTotal = DB::table('Almacen_Producto')
            ->where('Id_Pro', $id)
            ->sum('Stock');

        return view('producto_total', [
            'producto' => $producto,
            'stockTotal' => $stockTotal,
            'stockPorAlmacen' => $stockPorAlmacen
        ]);
    }


    public function eliminarTotal(Request $request)
    {
        $request->validate([
            'Id_Pro' => 'required|integer',
        ]);

        DB::table('Almacen_Producto')
            ->where('Id_Pro', $request->Id_Pro)
            ->delete();

        DB::table('Producto')
            ->where('Id_Pro', $request->Id_Pro)
            ->delete();

        return redirect('/todos_productos');
    }
}
