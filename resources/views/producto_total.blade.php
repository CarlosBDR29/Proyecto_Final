<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Producto Total</title>
    @vite(['resources/js/app.js', 'resources/css/productoTotal.css'])
</head>

<body>
    <div class="container">
        <form action="{{ route('todos_productos') }}" method="GET" class="volver-btn">
            <button type="submit">Volver a Productos</button>
        </form>

        <div class="contenido-producto">
            @php
            $imagen = public_path("imagenes_productos/{$producto->Id_Pro}.png");
            @endphp

            <div class="imagen-container">
                @if(file_exists($imagen))
                <img src="{{ asset("imagenes_productos/{$producto->Id_Pro}.png") }}" alt="Imagen del producto">
                @else
                <img src="{{ asset("imagenes_productos/SinImagen.png") }}" alt=" Sin imagen del producto">
                @endif
            </div>

            <div class="info-producto">
                <h1>{{ $producto->Nombre_Pro }}</h1>

                <p><strong>Descripción:</strong> {{ $producto->Descripcion }}</p>
                <p><strong>Precio:</strong> {{ $producto->Precio }} €</p>
                <p><strong>Stock total:</strong> {{ $stockTotal }}</p>
                <div class="stock-por-almacen">
                    <h3>Stock por almacén:</h3>
                    <ul>
                        @forelse($stockPorAlmacen as $registro)
                        <li><strong>{{ $registro->Nombre_Alm }}:</strong> {{ $registro->Stock }} unidades</li>
                        @empty
                        <li>Este producto no está en ningún almacén.</li>
                        @endforelse
                    </ul>
                </div>


                <form action="{{ route('producto.editar') }}" method="GET">
                    <button type="submit" class="btn-editar">Editar producto</button>
                </form>

                <form action="{{ route('productoTotal.eliminar') }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este producto?');">
                    @csrf
                    <input type="hidden" name="Id_Pro" value="{{ $producto->Id_Pro }}">
                    <button type="submit" class="btn-eliminar">Eliminar producto</button>
                </form>

            </div>
        </div>
    </div>

</body>

</html>