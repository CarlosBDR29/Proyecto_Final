<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Crear Producto</title>
    @vite(['resources/js/app.js','resources/css/crearProducto.css'])
</head>

<body>
    <div class="container">
        <h1>Crear Producto</h1>
        <p>El Producto aparecera en Almacen Entrada</p>
        <form method="POST" action="/producto/guardar" enctype="multipart/form-data">
            @csrf
            <div>
                <label>Nombre:</label>
                <input type="text" name="Nombre_Pro" required>
            </div>
            <div>
                <label>Stock:</label>
                <input type="number" name="Stock" min="0" required>
            </div>
            <div>
                <label>Descripción:</label>
                <textarea name="Descripcion" required maxlength="495"></textarea>
            </div>
            <div>
                <label>Precio (€):</label>
                <input type="number" step="0.01" name="Precio" min="0" required>
            </div>
            <div>
                <label>Imagen:</label>
                <input type="file" name="Imagen" accept="image/*">
            </div>
            <button type="submit">Guardar</button>
        </form>
    </div>
</body>

</html>