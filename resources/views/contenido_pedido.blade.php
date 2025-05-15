<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Contenido del Pedido</title>
    @vite(['resources/js/app.js','resources/css/pedidoContenido.css'])
</head>

<body>
    <div class="contenedor">
        <h1>Pedido Nº {{ $cabecera->Id_Cabe }}</h1>

        <div class="fila-fecha-estado">
            <p><strong>Fecha:</strong> {{ $cabecera->Fecha_Ped }}</p>
            <p class="estado {{ $cabecera->Estado === 'Entregado' ? 'entregado' : 'pendiente' }}">
                {{ $cabecera->Estado }}
            </p>
        </div>

        <div class="seccion">
            <h3>Proveedor</h3>
            <div class="proveedor-grid">
                <div><strong>Nombre:</strong> {{ $proveedor->Nombre_Prove }}</div>
                <div><strong>CIF:</strong> {{ $proveedor->CIF }}</div>
                <div><strong>Teléfono:</strong> {{ $proveedor->Telefono }}</div>
                <div><strong>Dirección:</strong> {{ $proveedor->Direccion }}</div>
            </div>
        </div>

        <div class="seccion">
            <h3>Contenido del pedido</h3>

            @if($lineas->isEmpty())
                <p class="mensaje-vacio">No hay líneas en este pedido.</p>
            @else
                <table class="tabla-pedido">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario (€)</th>
                            <th>Total Línea (€)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lineas as $linea)
                            <tr>
                                <td>{{ $linea->Nombre_Pro }}</td>
                                <td>{{ $linea->Cantidad }}</td>
                                <td>{{ number_format($linea->Precio_Unidad, 2) }}</td>
                                <td>{{ number_format($linea->subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <h3 class="total">Total del Pedido: {{ number_format($total, 2) }} €</h3>
            @endif
        </div>

        <a href="{{ route('pedidos') }}" class="btn-volver">← Volver a pedidos</a>
    </div>
</body>

</html>
