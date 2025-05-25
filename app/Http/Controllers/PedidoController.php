<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Cabecera_Pedido;
use App\Models\Linea_Pedido;


class PedidoController extends Controller
{

    public function index(Request $request)
    {
        if (!$request->session()->has('usuario_id')) {
            return redirect('/login')->with('error', 'Debes iniciar sesión primero.');
        }
    
        $usuarioId = Session::get('usuario_id');
    
        $fecha = $request->input('fecha');
        $estado = $request->input('estado');
    
        $query = DB::table('Cabecera_Pedido')
            ->where('Id_Usu', $usuarioId);
    
        if ($fecha) {
            $query->whereDate('Fecha_Ped', $fecha);
        }
    
        if ($estado) {
            $query->where('Estado', $estado);
        }
    
        $pedidos = $query->get();
    
        $proveedores = DB::table('Proveedor')
            ->where('Id_Usu', $usuarioId)
            ->get();
    
        return view('pedidos', [
            'pedidos' => $pedidos,
            'proveedores' => $proveedores
        ]);
    }
    



    public function marcarComoEntregado($id)
    {
        // Obtener el ID del usuario desde la sesión
        $usuarioId = session('usuario_id');

        // Obtener el ID del almacén "Almacén Entrada" del usuario
        $almacenEntrada = DB::table('Almacen')
            ->where('Id_Usu', $usuarioId)
            ->where('Nombre_Alm', 'Almacén Entrada')
            ->value('Id_Alm');

        if (!$almacenEntrada) {
            return redirect()->back()->with('error', 'No se encontró el almacén "Almacén Entrada" del usuario.');
        }

        // Obtener las líneas de pedido del pedido
        $lineas = DB::table('Linea_Pedido')
            ->where('Id_Cabe', $id)
            ->get();

        // Para cada línea, sumar la cantidad al stock del producto en ese almacén
        foreach ($lineas as $linea) {

            // Comprobar si ese producto ya existe en el almacén
            $existe = DB::table('Almacen_Producto')
                ->where('Id_Alm', $almacenEntrada)
                ->where('Id_Pro', $linea->Id_Pro)
                ->exists();

            if ($existe) {
                // Si ya existe, aumentar el stock
                DB::table('Almacen_Producto')
                    ->where('Id_Alm', $almacenEntrada)
                    ->where('Id_Pro', $linea->Id_Pro)
                    ->increment('Stock', $linea->Cantidad);
            } else {
                // Si no existe, insertar un nuevo registro con el stock inicial
                DB::table('Almacen_Producto')->insert([
                    'Id_Alm' => $almacenEntrada,
                    'Id_Pro' => $linea->Id_Pro,
                    'Stock' => $linea->Cantidad
                ]);
            }
        }

        // Marcar el pedido como entregado
        DB::table('Cabecera_Pedido')
            ->where('Id_Cabe', $id)
            ->update(['Estado' => 'Entregado']);

        return redirect()->back()->with('success', 'Pedido marcado como entregado y stock actualizado.');
    }


    public function borrar($id)
    {
        DB::table('Linea_Pedido')->where('Id_Cabe', $id)->delete();
        DB::table('Cabecera_Pedido')->where('Id_Cabe', $id)->delete();

        return redirect()->back()->with('success', 'Pedido eliminado correctamente.');
    }


    public function guardarPedidoSesion(Request $request)
    {
        session(['pedido_id' => $request->id_cabe]);
        return redirect()->route('contenido_pedido');
    }


    public function verContenido()
    {
        $id = session('pedido_id');

        if (!$id) {
            return redirect()->route('pedidos');
        }

        // Datos de la cabecera
        $cabecera = DB::table('Cabecera_Pedido')->where('Id_Cabe', $id)->first();

        // Obtener proveedor del pedido
        $proveedor = DB::table('Proveedor')->where('Id_Prove', $cabecera->Id_Prove)->first();

        // Datos de las líneas de pedido con producto
        $lineas = DB::table('Linea_Pedido')
            ->join('Producto', 'Linea_Pedido.Id_Pro', '=', 'Producto.Id_Pro')
            ->where('Linea_Pedido.Id_Cabe', $id)
            ->select('Producto.Nombre_Pro', 'Linea_Pedido.Precio_Unidad', 'Linea_Pedido.Cantidad')
            ->get();

        // Calcular total del pedido
        $total = 0;
        foreach ($lineas as $linea) {
            $linea->subtotal = $linea->Cantidad * $linea->Precio_Unidad;
            $total += $linea->subtotal;
        }

        return view('contenido_pedido', compact('cabecera', 'lineas', 'total', 'proveedor'));
    }


    public function crear(Request $request)
    {
        $usuarioId = session('usuario_id');
    
        $proveedorId = $request->input('proveedor');
    
        if (!$proveedorId) {
            return redirect()->route('pedidos')->with('error', 'No se ha seleccionado un proveedor.');
        }
    
        // Obtener el proveedor
        $proveedor = Proveedor::where('Id_Usu', $usuarioId)
            ->where('Id_Prove', $proveedorId)
            ->first();
    
        if (!$proveedor) {
            return redirect()->route('pedidos')->with('error', 'Proveedor no válido.');
        }
    
        // Obtener los productos del proveedor desde la tabla Proveedor_Producto
        $productos = DB::table('Proveedor_Producto')
            ->join('Producto', 'Proveedor_Producto.Id_Pro', '=', 'Producto.Id_Pro')
            ->where('Proveedor_Producto.Id_Prove', $proveedorId)
            ->select('Producto.*', 'Proveedor_Producto.Precio_Unidad_Prove')
            ->get();
    
        return view('crear_pedido', compact('productos', 'proveedor'));
    }
    



    public function guardar(Request $request)
    {
        $usuarioId = session('usuario_id');

        // Crear una nueva cabecera de pedido
        $cabecera = new Cabecera_Pedido();
        $cabecera->Fecha_Ped = now();
        $cabecera->Estado = 'Pendiente';
        $cabecera->Id_Prove = $request->proveedor;
        $cabecera->Id_Usu = $usuarioId;
        $cabecera->save();

        // Recoger los datos del formulario
        $productos = $request->input('productos', []);
        $cantidades = $request->input('cantidades', []);
        $precios = $request->input('precios', []);

        // Recorrer los productos y guardar las líneas del pedido
        foreach ($productos as $index => $idProducto) {
            if ($idProducto && isset($cantidades[$index]) && isset($precios[$index])) {
                $linea = new Linea_Pedido();
                $linea->Id_Pro = $idProducto;
                $linea->Cantidad = $cantidades[$index];
                $linea->Precio_Unidad = $precios[$index]; // nuevo campo
                $linea->Id_Cabe = $cabecera->Id_Cabe;
                $linea->save();
            }
        }

        return redirect()->route('pedidos')->with('success', 'Pedido creado correctamente.');
    }  

}
