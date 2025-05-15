<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\AlmacenController;
use App\Http\Controllers\InteriorAlmacenController;
use App\Http\Controllers\CrearProductoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProductoTotalController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\TodosProductosController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProveedorProductoController;





Route::post('/proveedor/productos/editar', [ProveedorProductoController::class, 'editar'])->name('proveedor.productos.editar');
Route::post('/proveedor/productos/guardar', [ProveedorProductoController::class, 'guardar'])->name('proveedor.productos.guardar');



Route::get('/pedidos/crear', [PedidoController::class, 'crear'])->name('pedidos.crear');
Route::post('/pedidos/guardar', [PedidoController::class, 'guardar'])->name('pedidos.guardar');
Route::post('/guardar-pedido-sesion', [PedidoController::class, 'guardarPedidoSesion'])->name('guardar_pedido_sesion');
Route::get('/contenido-pedido', [PedidoController::class, 'verContenido'])->name('contenido_pedido');
Route::delete('/pedidos/{id}', [PedidoController::class, 'borrar'])->name('pedidos.borrar');
Route::put('/pedidos/{id}/entregar', [PedidoController::class, 'marcarComoEntregado'])->name('pedidos.entregar');
Route::get('/pedidos', [PedidoController::class, 'index'])->name('pedidos');


Route::post('/producto_total/eliminar', [ProductoTotalController::class, 'eliminarTotal'])->name('productoTotal.eliminar');
Route::post('/ver_producto_total', [ProductoTotalController::class, 'verProductoTotal']);
Route::post('/producto_total', [ProductoTotalController::class, 'redirigirTotal']);
Route::get('/producto_total', [ProductoTotalController::class, 'mostrarTotal']);

Route::get('/todos_productos', [TodosProductosController::class, 'index'])->name('todos_productos');


Route::post('/proveedores/borrar', [ProveedorController::class, 'borrar'])->name('proveedor.borrar');
Route::post('/proveedores/editar', [ProveedorController::class, 'formularioEditar'])->name('proveedor.formularioEditar');
Route::post('/proveedores/guardar-edicion', [ProveedorController::class, 'guardarEdicion'])->name('proveedor.guardarEdicion');
Route::get('/proveedores/crear', [ProveedorController::class, 'crear'])->name('proveedores.crear');
Route::post('/proveedores/guardar', [ProveedorController::class, 'guardar'])->name('proveedores.guardar');
Route::get('/proveedores', [ProveedorController::class, 'index'])->name('proveedores');


Route::post('/producto/moverStock', [ProductoController::class, 'moverStock'])->name('producto.moverStock');
Route::post('/ver_producto', [ProductoController::class, 'verProducto']);
Route::get('/producto/editar', [ProductoController::class, 'editar'])->name('producto.editar');
Route::post('/producto/actualizar', [ProductoController::class, 'actualizar'])->name('producto.actualizar');
Route::post('/producto', [ProductoController::class, 'redirigir']);
Route::get('/producto', [ProductoController::class, 'mostrar']);
Route::post('/producto/aumentar-stock', [ProductoController::class, 'aumentarStock'])->name('producto.aumentarStock');
Route::post('/producto/disminuir-stock', [ProductoController::class, 'disminuirStock'])->name('producto.disminuirStock');
Route::post('/producto/eliminar', [ProductoController::class, 'eliminar'])->name('producto.eliminar');


Route::get('/producto/crear', [CrearProductoController::class, 'formulario']);
Route::post('/producto/guardar', [CrearProductoController::class, 'guardar']);

Route::post('/producto/guardar-id', [InteriorAlmacenController::class, 'guardarIdProducto']);
Route::get('/almacen/buscar', [InteriorAlmacenController::class, 'buscar']);
Route::get('/almacen', [InteriorAlmacenController::class, 'vista']);
Route::get('/almacen/datos', [InteriorAlmacenController::class, 'datos']);
Route::get('/almacen', [InteriorAlmacenController::class, 'vista'])->name('interior.almacen');


Route::get('/almacenes', [AlmacenController::class, 'index']);
Route::post('/almacenes', [AlmacenController::class, 'crearAlmacen']);
Route::post('/almacenes/entrar', [AlmacenController::class, 'entrarAlmacen']);
Route::post('/almacenes/editar', [AlmacenController::class, 'editar']);
Route::post('/almacenes/eliminar', [AlmacenController::class, 'eliminar']);


Route::get('/menu', [MenuController::class, 'index']);

Route::get('/register', [RegistroController::class, 'showRegisterForm']);
Route::post('/register', [RegistroController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);




Route::get('/menu', function () {
    return view('menu');
});

Route::get('/logout', function () {
    session()->flush();
    return redirect('/login');
});


Route::get('/', function () {
    return view('login');
});
