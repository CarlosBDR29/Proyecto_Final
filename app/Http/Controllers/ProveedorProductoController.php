<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProveedorProductoController extends Controller
{

    public function editar(Request $request)
    {
        $id = $request->Id_Proveedor;

        $proveedor = Proveedor::findOrFail($id);
        $productos = Producto::where('Id_Usu', session('usuario_id'))->get();

        $productosAsignados = DB::table('Proveedor_Producto')
            ->where('Id_Prove', $id)
            ->pluck('Precio_Unidad_Prove', 'Id_Pro')
            ->toArray();

        return view('editar_productos_proveedor', compact('proveedor', 'productos', 'productosAsignados'));
    }


    public function guardar(Request $request)
    {
        $id = $request->Id_Proveedor;

        DB::table('Proveedor_Producto')->where('Id_Prove', $id)->delete();

        if ($request->productos) {
            foreach ($request->productos as $index => $productoId) {
                $precio = $request->precios[$index];
                DB::table('Proveedor_Producto')->insert([
                    'Id_Prove' => $id,
                    'Id_Pro' => $productoId,
                    'Precio_Unidad_Prove' => $precio
                ]);
            }
        }

        return redirect()->route('proveedores')->with('success', 'Productos actualizados.');
    }
}
