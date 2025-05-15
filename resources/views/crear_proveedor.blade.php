<!DOCTYPE html>
<html>

<head>
    <title>Crear Proveedor</title>
    @vite(['resources/js/app.js','resources/css/crearProveedor.css'])
</head>

<body>
    <div class="contenedor">
        <h1>Crear Nuevo Proveedor</h1>
        <form method="POST" action="{{ route('proveedores.guardar') }}">
            @csrf

            <label>Nombre:</label>
            <input type="text" name="Nombre_Prove" required>

            <label>CIF:</label>
            <input type="text" name="CIF" required>

            <label>Teléfono:</label>
            <input type="tel" name="Telefono" required pattern="[0-9]{9,15}" title="Introduce un número de teléfono válido (solo dígitos, entre 9 y 15)">


            <label>Dirección:</label>
            <input type="text" name="Direccion" required>

            <button type="submit">Crear</button>
            <a href="{{ route('proveedores') }}">Cancelar</a>
        </form>
    </div>
</body>

</html>