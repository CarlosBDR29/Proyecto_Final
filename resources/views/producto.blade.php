<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Producto</title>
    @vite(['resources/js/app.js', 'resources/css/producto.css'])
</head>

<body>
    <div class="container">
        <form action="{{ route('interior.almacen') }}" method="GET" class="volver-btn">
            <button type="submit">Volver al almacén</button>
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
                <p><strong>Stock:</strong> {{ $producto->Stock }}</p>
                <p><strong>Descripción:</strong> {{ $producto->Descripcion }}</p>
                <p><strong>Precio:</strong> {{ $producto->Precio }} €</p>

                <form action="{{ route('producto.aumentarStock') }}" method="POST">
                    @csrf
                    <input type="hidden" name="Id_Pro" value="{{ $producto->Id_Pro }}">
                    <input type="number" name="cantidad" min="1" required placeholder="Cantidad a aumentar">
                    <button type="submit">Aumentar Stock</button>
                </form>

                @if (session('error'))
                <div class="error">
                    {{ session('error') }}
                </div>
                @endif

                <form action="{{ route('producto.disminuirStock') }}" method="POST">
                    @csrf
                    <input type="hidden" name="Id_Pro" value="{{ $producto->Id_Pro }}">
                    <input type="number" name="cantidad" min="1" required placeholder="Cantidad a disminuir">
                    <button type="submit">Disminuir Stock</button>
                </form>

                <form action="{{ route('producto.moverStock') }}" method="POST">
                    @csrf
                    <input type="hidden" name="Id_Pro" value="{{ $producto->Id_Pro }}">
                    <input type="hidden" name="Id_Alm_Origen" value="{{ session('almacen_id') }}">

                    <select name="destino" required>
                        <option value="">Seleccionar almacén destino</option>
                        @foreach ($almacenesUsuario as $almacen)
                        @if ($almacen->Id_Alm != session('almacen_id'))
                        <option value="{{ $almacen->Id_Alm }}">{{ $almacen->Nombre_Alm }}</option>
                        @endif
                        @endforeach
                    </select>

                    <input type="number" name="cantidad" min="1" required placeholder="Cantidad a mover">
                    <button type="submit">Mover producto</button>
                </form>

                <form action="{{ route('producto.editar') }}" method="GET">
                    <button type="submit" class="btn-editar">Editar producto</button>
                </form>

                <form action="{{ route('producto.eliminar') }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres quitar este producto del almcen?');">
                    @csrf
                    <input type="hidden" name="Id_Pro" value="{{ $producto->Id_Pro }}">
                    <button type="submit" class="btn-eliminar">Quitar del Almacen</button>
                </form>
            </div>
        </div>
    </div>

</body>

</html>