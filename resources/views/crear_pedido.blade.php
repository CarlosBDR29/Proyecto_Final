<!DOCTYPE html>
<html>

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js','resources/css/crearPedido.css'])
    <title>Crear Pedido</title>
</head>

<body>
    <div id="app">
        <div class="container">
            <h1>Nuevo Pedido</h1>
            <form method="POST" action="{{ route('pedidos.guardar') }}">
                @csrf

                <input type="hidden" name="proveedor" value="{{ $proveedor->Id_Prove }}">
                <p><strong>Proveedor:</strong> {{ $proveedor->Nombre_Prove }}</p>


                <label class="productos-label">Productos:</label>
                <agregar-productos :productos='@json($productos)'></agregar-productos>

                <div class="clearfix">
                    <button type="submit" class="submit-btn">Guardar Pedido</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>