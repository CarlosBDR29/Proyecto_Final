<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlmacenController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->session()->has('usuario_id')) {
            return redirect('/login')->with('error', 'Debes iniciar sesión primero.');
        }

        $usuarioId = $request->session()->get('usuario_id');
        $almacenes = DB::table('Almacen')
            ->where('Id_Usu', $usuarioId)
            ->get();

        return view('almacenes', ['almacenes' => $almacenes]);
    }

    public function crearAlmacen(Request $request)
    {
        if (!$request->session()->has('usuario_id')) {
            return response()->json(['success' => false, 'message' => 'No estás logueado.'], 403);
        }

        $usuarioId = $request->session()->get('usuario_id');
        $nombre = $request->input('nombre');

        // Comprobar si ya existe un almacén con ese nombre para el mismo usuario
        $existe = DB::table('Almacen')
            ->where('Id_Usu', $usuarioId)
            ->where('Nombre_Alm', $nombre)
            ->exists();

        if ($existe) {
            return response()->json([
                'success' => false,
                'message' => 'Ya tienes un almacén con ese nombre.'
            ], 409);
        }

        // Crear nuevo almacén
        $almacenId = DB::table('Almacen')->insertGetId([
            'Nombre_Alm' => $nombre,
            'Id_Usu' => $usuarioId,
        ]);

        // Devolver el nuevo almacén creado
        $almacen = DB::table('Almacen')->where('Id_Alm', $almacenId)->first();

        return response()->json([
            'success' => true,
            'almacen' => $almacen
        ]);
    }


    public function entrarAlmacen(Request $request)
    {
        $id = $request->input('id');
        $usuarioId = $request->session()->get('usuario_id');

        $almacen = DB::table('Almacen')
            ->where('Id_Alm', $id)
            ->where('Id_Usu', $usuarioId)
            ->first();

        if ($almacen) {
            $request->session()->put('almacen_id', $id);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    public function editar(Request $request)
    {
        $id = $request->input('id');
        $nuevoNombre = $request->input('nombre');

        if (!$request->session()->has('usuario_id')) {
            return response()->json(['success' => false, 'message' => 'No autenticado']);
        }

        $usuarioId = $request->session()->get('usuario_id');

        $actualizado = DB::table('Almacen')
            ->where('Id_Alm', $id)
            ->where('Id_Usu', $usuarioId) // seguridad: solo editar tus propios almacenes
            ->update(['Nombre_Alm' => $nuevoNombre]);

        return response()->json(['success' => $actualizado > 0]);
    }

    public function eliminar(Request $request)
    {
        $id = $request->input('id');

        if (!$request->session()->has('usuario_id')) {
            return response()->json(['success' => false, 'message' => 'No autenticado']);
        }

        $usuarioId = $request->session()->get('usuario_id');

        // Verificar que el almacén pertenece al usuario
        $almacen = DB::table('Almacen')
            ->where('Id_Alm', $id)
            ->where('Id_Usu', $usuarioId)
            ->first();

        if (!$almacen) {
            return response()->json(['success' => false, 'message' => 'Almacén no encontrado o no autorizado']);
        }

        // Borrar las relaciones en Almacen_Producto
        DB::table('Almacen_Producto')
            ->where('Id_Alm', $id)
            ->delete();

        // Luego borrar el almacén
        $eliminado = DB::table('Almacen')
            ->where('Id_Alm', $id)
            ->delete();

        return response()->json(['success' => $eliminado > 0]);
    }
}
