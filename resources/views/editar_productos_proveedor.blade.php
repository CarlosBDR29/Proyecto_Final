<!DOCTYPE html>
<html>

<head>
    <title>Editar Productos del Proveedor</title>
    @vite(['resources/js/app.js', 'resources/css/app.css','resources/css/proveedorProductos.css'])
</head>

<body>
    <div class="container">
        <h1>Editar productos para: {{ $proveedor->Nombre_Prove }}</h1>

        <form method="POST" action="{{ route('proveedor.productos.guardar') }}">
            @csrf
            <input type="hidden" name="Id_Proveedor" value="{{ $proveedor->Id_Prove }}">


            <table>
                <thead>
                    <tr>
                        <th>Seleccionar</th>
                        <th>Nombre del Producto</th>
                        <th>Precio unidad (€)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productos as $producto)
                    @php
                    $seleccionado = array_key_exists($producto->Id_Pro, $productosAsignados);
                    $precio = $productosAsignados[$producto->Id_Pro] ?? 0;
                    @endphp
                    <tr>
                        <td>
                            <input type="checkbox" name="productos[]" value="{{ $producto->Id_Pro }}"
                                {{ $seleccionado ? 'checked' : '' }} onchange="togglePrecio(this)">
                        </td>
                        <td>{{ $producto->Nombre_Pro }}</td>
                        <td>
                            <input type="number" step="0.01" min="0" name="precios[]"
                                value="{{ $precio }}"
                                {{ $seleccionado ? '' : 'disabled' }}>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="acciones">
                <button type="submit">Guardar Cambios</button>
                <a href="{{ route('proveedores') }}" class="btn-volver">Volver</a>
            </div>
        </form>

    </div>

    <script>
        function togglePrecio(checkbox) {
            const input = checkbox.closest('tr').querySelector('input[type=number]');
            input.disabled = !checkbox.checked;
        }
    </script>
</body>

</html>