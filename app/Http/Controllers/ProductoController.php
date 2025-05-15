<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;


class ProductoController extends Controller
{
    public function redirigir(Request $request)
    {
        $request->session()->put('producto_id', $request->input('id'));
        return redirect('/producto');
    }

    public function verProducto(Request $request)
    {
        $idPro = $request->Id_Pro;
        Session::put('producto_id', $idPro);
        return redirect('/producto');
    }


    public function mostrar(Request $request)
    {
        $id = $request->session()->get('producto_id');
        $almacenId = $request->session()->get('almacen_id');
        Session::put('destino_producto', "Producto");
    
        if (!$id || !$almacenId) {
            return redirect('/almacen')->with('error', 'Producto o almacén no especificado.');
        }
    
        $producto = DB::table('Producto')
            ->join('Almacen_Producto', 'Producto.Id_Pro', '=', 'Almacen_Producto.Id_Pro')
            ->where('Producto.Id_Pro', $id)
            ->where('Almacen_Producto.Id_Alm', $almacenId)
            ->select('Producto.Id_Pro', 'Producto.Nombre_Pro', 'Producto.Descripcion', 'Producto.Precio', 'Almacen_Producto.Stock')
            ->first();
    
        if (!$producto) {
            return redirect('/almacen')->with('error', 'Producto no encontrado en este almacén.');
        }
    
        //Añadido: obtener los almacenes del usuario
        $usuarioId = session('usuario_id');
        $almacenesUsuario = DB::table('Almacen')
            ->where('Id_Usu', $usuarioId)
            ->get();
    
        //Pasa también $almacenesUsuario a la vista
        return view('producto', [
            'producto' => $producto,
            'almacenesUsuario' => $almacenesUsuario
        ]);
    }
    


    public function aumentarStock(Request $request)
    {
        $request->validate([
            'Id_Pro' => 'required|integer',
            'cantidad' => 'required|integer|min:1'
        ]);

        $almacenId = $request->session()->get('almacen_id');

        if (!$almacenId) {
            return redirect()->back()->with('error', 'No se encontró el almacén en sesión.');
        }

        $producto = DB::table('Almacen_Producto')
            ->where('Id_Pro', $request->Id_Pro)
            ->where('Id_Alm', $almacenId)
            ->first();

        if ($producto) {
            DB::table('Almacen_Producto')
                ->where('Id_Pro', $request->Id_Pro)
                ->where('Id_Alm', $almacenId)
                ->update(['Stock' => $producto->Stock + $request->cantidad]);
        }

        return redirect()->back();
    }


    public function disminuirStock(Request $request)
    {
        $request->validate([
            'Id_Pro' => 'required|integer',
            'cantidad' => 'required|integer|min:1'
        ]);

        $almacenId = $request->session()->get('almacen_id');

        if (!$almacenId) {
            return redirect()->back()->with('error', 'No se encontró el almacén en sesión.');
        }

        $producto = DB::table('Almacen_Producto')
            ->where('Id_Pro', $request->Id_Pro)
            ->where('Id_Alm', $almacenId)
            ->first();

        if ($producto) {
            if ($request->cantidad > $producto->Stock) {
                return redirect()->back()->with('error', 'No puedes disminuir más stock del que hay disponible.');
            }

            $nuevoStock = $producto->Stock - $request->cantidad;

            DB::table('Almacen_Producto')
                ->where('Id_Pro', $request->Id_Pro)
                ->where('Id_Alm', $almacenId)
                ->update(['Stock' => $nuevoStock]);
        }

        return redirect()->back();
    }


    public function eliminar(Request $request)
    {
        $request->validate([
            'Id_Pro' => 'required|integer',
        ]);

        $almacenId = $request->session()->get('almacen_id');

        DB::table('Almacen_Producto')
            ->where('Id_Pro', $request->Id_Pro)
            ->where('Id_Alm', $almacenId)
            ->delete();

        return redirect('/almacen');
    }

    public function editar(Request $request)
    {
        if (!$request->session()->has('producto_id')) {
            return redirect('/almacen')->with('error', 'No se ha seleccionado un producto.');
        }

        $producto = DB::table('Producto')->where('Id_Pro', session('producto_id'))->first();

        return view('editar_producto', ['producto' => $producto]);
    }

    public function actualizar(Request $request)
    {
        $request->validate([
            'Nombre_Pro' => 'required|string',
            'Descripcion' => 'nullable|string',
            'Precio' => 'required|numeric|min:0',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::table('Producto')
            ->where('Id_Pro', $request->Id_Pro)
            ->update([
                'Nombre_Pro' => $request->Nombre_Pro,
                'Descripcion' => $request->Descripcion,
                'Precio' => $request->Precio,
            ]);

        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $nombre = $request->Id_Pro . '.png';
            $imagen->move(public_path('imagenes_productos'), $nombre);
        }

        $destino = $request->session()->get('destino_producto');

        if ($destino == "ProductoTotal") {
            return redirect('/producto_total');
        }

        return redirect('/producto');
    }


    public function moverStock(Request $request)
    {
        $idUsuario = session('usuario_id');
        $idProducto = $request->input('Id_Pro');
        $idOrigen = $request->session()->get('almacen_id');
        $idDestino = $request->input('destino');
        $cantidad = intval($request->input('cantidad'));

        if ($idDestino == $idOrigen) {
            return back()->with('error', 'El almacén destino debe ser distinto al de origen.');
        }

        $stockOrigen = DB::table('Almacen_Producto')
            ->where('Id_Alm', $idOrigen)
            ->where('Id_Pro', $idProducto)
            ->value('Stock');

        if ($stockOrigen === null) {
            return back()->with('error', 'No se encontró el producto en el almacén de origen.');
        }

        if ($cantidad > $stockOrigen) {
            return back()->with('error', 'No puedes mover más cantidad de la que hay en stock.');
        }

        DB::transaction(function () use ($idOrigen, $idDestino, $idProducto, $cantidad) {
            // Restar stock en el almacén origen
            DB::table('Almacen_Producto')
                ->where('Id_Alm', $idOrigen)
                ->where('Id_Pro', $idProducto)
                ->decrement('Stock', $cantidad);

            // Añadir stock en el almacén destino
            $existeDestino = DB::table('Almacen_Producto')
                ->where('Id_Alm', $idDestino)
                ->where('Id_Pro', $idProducto)
                ->exists();

            if ($existeDestino) {
                DB::table('Almacen_Producto')
                    ->where('Id_Alm', $idDestino)
                    ->where('Id_Pro', $idProducto)
                    ->increment('Stock', $cantidad);
            } else {
                DB::table('Almacen_Producto')->insert([
                    'Id_Alm' => $idDestino,
                    'Id_Pro' => $idProducto,
                    'Stock' => $cantidad
                ]);
            }
        });

        return back()->with('success', 'Producto movido correctamente.');
    }
}
