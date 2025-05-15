<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CrearProductoController extends Controller
{
    public function formulario(Request $request)
    {
        if (!$request->session()->has('usuario_id')) {
            return redirect('/login');
        }

        return view('crear_producto');
    }

    public function guardar(Request $request)
    {
        $usuarioId = $request->session()->get('usuario_id');
    
        $validated = $request->validate([
            'Nombre_Pro' => 'required|string|max:255',
            'Stock' => 'required|integer|min:0',
            'Descripcion' => 'required|string',
            'Precio' => 'required|numeric|min:0',
            'Imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
    
        // Buscar el almacén "Almacén Entrada" del usuario
        $almacenEntrada = DB::table('Almacen')
            ->where('Id_Usu', $usuarioId)
            ->where('Nombre_Alm', 'Almacén Entrada')
            ->first();
    
        if (!$almacenEntrada) {
            return redirect()->back()->with('error', 'No se encontró el almacén "Almacén Entrada" para este usuario.');
        }
    
        // Insertar el producto y obtener su ID
        $id = DB::table('Producto')->insertGetId([
            'Nombre_Pro' => $validated['Nombre_Pro'],
            'Descripcion' => $validated['Descripcion'],
            'Precio' => $validated['Precio'],
            'Id_Usu' => $usuarioId
        ]);
    
        // Insertar el stock inicial en Almacen_Producto
        DB::table('Almacen_Producto')->insert([
            'Id_Alm' => $almacenEntrada->Id_Alm,
            'Id_Pro' => $id,
            'Stock' => $validated['Stock']
        ]);
    
        // Guardar la imagen si se subió
        if ($request->hasFile('Imagen')) {
            $nombreArchivo = $id . '.png';
            $request->file('Imagen')->move(public_path('imagenes_productos'), $nombreArchivo);
        }
    
        return redirect('/todos_productos');
    }
    
    
}

