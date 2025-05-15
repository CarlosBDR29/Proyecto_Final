<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Proveedor</title>
    @vite(['resources/js/app.js','resources/css/editarProveedor.css'])
</head>
<body>
    <div class="editar-container">
        <h1>Editar Proveedor</h1>

        <form action="{{ route('proveedor.guardarEdicion') }}" method="POST" class="formulario">
            @csrf
            <input type="hidden" name="Id_Prove" value="{{ $proveedor->Id_Prove }}">

            <label>Nombre:</label>
            <input type="text" name="Nombre_Prove" value="{{ $proveedor->Nombre_Prove }}" required>

            <label>CIF:</label>
            <input type="text" name="CIF" value="{{ $proveedor->CIF }}" required>

            <label>Teléfono:</label>
            <input type="tel" name="Telefono" value="{{ $proveedor->Telefono }}" required pattern="[0-9]{9,15}" title="Introduce un número de teléfono válido (solo dígitos, entre 9 y 15)">

            <label>Dirección:</label>
            <input type="text" name="Direccion" value="{{ $proveedor->Direccion }}" required>

            <div class="botones">
                <button type="submit" class="guardar">Guardar cambios</button>
                <a href="/proveedores" class="volver">Volver a la lista</a>
            </div>
        </form>
    </div>
</body>
</html>
