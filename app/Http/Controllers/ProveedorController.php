<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProveedorController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->session()->has('usuario_id')) {
            return redirect('/login')->with('error', 'Debes iniciar sesión primero.');
        }

        $usuario_id = $request->session()->get('usuario_id');
        $busqueda = $request->input('buscar');

        $proveedores = DB::table('Proveedor')
            ->where('Id_Usu', $usuario_id)
            ->when($busqueda, function ($query, $busqueda) {
                return $query->where('Nombre_Prove', 'like', '%' . $busqueda . '%');
            })
            ->get();

        return view('proveedores', compact('proveedores', 'busqueda'));
    }

    public function crear(Request $request)
    {
        if (!$request->session()->has('usuario_id')) {
            return redirect('/login')->with('error', 'Debes iniciar sesión primero.');
        }

        return view('crear_proveedor');
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'Nombre_Prove' => 'required|string|max:255',
            'CIF' => 'required|string|max:50',
            'Telefono' => 'required|string|max:20',
            'Direccion' => 'required|string|max:255',
        ]);

        $usuario_id = $request->session()->get('usuario_id');

        DB::table('Proveedor')->insert([
            'Nombre_Prove' => $request->Nombre_Prove,
            'CIF' => $request->CIF,
            'Telefono' => $request->Telefono,
            'Direccion' => $request->Direccion,
            'Id_Usu' => $usuario_id,
        ]);

        return redirect()->route('proveedores')->with('success', 'Proveedor creado correctamente.');
    }

    public function formularioEditar(Request $request)
    {
        if (!$request->session()->has('usuario_id')) {
            return redirect('/login');
        }

        $usuario_id = $request->session()->get('usuario_id');
        $proveedor_id = $request->input('Id_Proveedor');

        $proveedor = DB::table('Proveedor')
            ->where('Id_Usu', $usuario_id)
            ->where('Id_Prove', $proveedor_id)
            ->first();

        if (!$proveedor) {
            return redirect('/proveedores')->with('error', 'Proveedor no encontrado.');
        }

        return view('editar_proveedor', ['proveedor' => $proveedor]);
    }



    public function guardarEdicion(Request $request)
    {
        $request->validate([
            'Id_Prove' => 'required|integer',
            'Nombre_Prove' => 'required|string',
            'CIF' => 'required|string',
            'Telefono' => 'required|string',
            'Direccion' => 'required|string',
        ]);

        DB::table('Proveedor')
            ->where('Id_Prove', $request->input('Id_Prove'))
            ->update([
                'Nombre_Prove' => $request->input('Nombre_Prove'),
                'CIF' => $request->input('CIF'),
                'Telefono' => $request->input('Telefono'),
                'Direccion' => $request->input('Direccion'),
            ]);

        return redirect('/proveedores')->with('success', 'Proveedor actualizado correctamente.');
    }

    public function borrar(Request $request)
    {
        if (!$request->session()->has('usuario_id')) {
            return redirect('/login');
        }

        $usuario_id = $request->session()->get('usuario_id');
        $proveedor_id = $request->input('Id_Proveedor');

        // Verificamos que el proveedor pertenezca al usuario
        $proveedor = DB::table('Proveedor')
            ->where('Id_Prove', $proveedor_id)
            ->where('Id_Usu', $usuario_id)
            ->first();

        if ($proveedor) {
            DB::beginTransaction();

            try {
                // 1. Borrar líneas de pedido relacionadas con las cabeceras del proveedor
                $cabeceras = DB::table('Cabecera_Pedido')
                    ->where('Id_Prove', $proveedor_id)
                    ->pluck('Id_Cabe');

                DB::table('Linea_Pedido')->whereIn('Id_Cabe', $cabeceras)->delete();

                // 2. Borrar las cabeceras de pedido del proveedor
                DB::table('Cabecera_Pedido')->where('Id_Prove', $proveedor_id)->delete();

                // 3. Borrar relaciones con productos
                DB::table('Proveedor_Producto')->where('Id_Prove', $proveedor_id)->delete();

                // 4. Borrar el proveedor
                DB::table('Proveedor')->where('Id_Prove', $proveedor_id)->delete();

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->withErrors(['error' => 'Error al eliminar proveedor: ' . $e->getMessage()]);
            }
        }

        return redirect('/proveedores');
    }
}
