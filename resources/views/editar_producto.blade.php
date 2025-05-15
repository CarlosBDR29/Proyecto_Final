<!DOCTYPE html>
<html>

<head>
    <title>Editar producto</title>
    @vite(['resources/js/app.js','resources/css/editarProducto.css'])

</head>

<body>
    <div class="container">
        <h1>Editar producto</h1>

        @php
        $rutaImagen = public_path("imagenes_productos/{$producto->Id_Pro}.png");
        @endphp

        @if (file_exists($rutaImagen))
        <div class="imagen-actual">
            <p><strong>Imagen actual:</strong></p>
            <img src="{{ asset("imagenes_productos/{$producto->Id_Pro}.png") }}" alt="Imagen actual del producto">
        </div>
        @else
        <p><strong>No hay imagen actual para este producto.</strong></p>
        @endif

        <form action="{{ route('producto.actualizar') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="Id_Pro" value="{{ $producto->Id_Pro }}">

            <label>Nombre:</label>
            <input type="text" name="Nombre_Pro" value="{{ $producto->Nombre_Pro }}" required>

            <label>Descripción:</label>
            <textarea name="Descripcion" required maxlength="495">{{ $producto->Descripcion }}</textarea>


            <label>Precio (€):</label>
            <input type="number" name="Precio" step="0.01" value="{{ $producto->Precio }}" min="0" required>

            <label>Cambiar imagen:</label>
            <input type="file" name="imagen" accept="image/*">

            <button type="submit" class="btn-guardar">Guardar cambios</button>
        </form>

    </div>
</body>

</html>