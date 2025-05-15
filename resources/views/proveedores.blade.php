<!DOCTYPE html>
<html>

<head>
    <title>Proveedores</title>
    @vite(['resources/js/app.js','resources/css/proveedores.css'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>

    <div id="app">
        <Navbar></Navbar>
    </div>

    <div class="contenedor-principal">
        <h1>Mis Proveedores</h1>
        <div class="barra-superior">
            <form method="GET" action="{{ route('proveedores') }}">
                <input type="text" name="buscar" placeholder="Buscar por nombre" value="{{ $busqueda ?? '' }}">
                <button type="submit">Buscar</button>
            </form>

            <a href="{{ route('proveedores.crear') }}">
                <button>Crear Proveedor</button>
            </a>
        </div>

        <div class="contenedor-proveedores">
            @if($proveedores->isEmpty())
            <p>No tienes proveedores registrados.</p>
            @else
            @foreach($proveedores as $proveedor)
            <div class="proveedor">
                <div class="datos">
                    <div><strong>{{ $proveedor->Nombre_Prove }}</strong></div>
                    <div>CIF: {{ $proveedor->CIF }}</div>
                    <div>Teléfono: {{ $proveedor->Telefono }}</div>
                    <div>Dirección: {{ $proveedor->Direccion }}</div>
                </div>

                <div class="acciones">
                    <form method="POST" action="{{ route('proveedor.formularioEditar') }}">
                        @csrf
                        <input type="hidden" name="Id_Proveedor" value="{{ $proveedor->Id_Prove }}">
                        <button type="submit" class="bg-yellow-500">Editar</button>
                    </form>

                    <form method="POST" action="{{ route('proveedor.borrar') }}" onsubmit="return confirmarEliminacion();">
                        @csrf
                        <input type="hidden" name="Id_Proveedor" value="{{ $proveedor->Id_Prove }}">
                        <button type="submit" class="bg-red-500">Eliminar</button>
                    </form>

                    <form method="POST" action="{{ route('proveedor.productos.editar') }}">
                        @csrf
                        <input type="hidden" name="Id_Proveedor" value="{{ $proveedor->Id_Prove }}">
                        <button type="submit">Gestionar productos</button>
                    </form>


                </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>

</body>

<script>
    function confirmarEliminacion() {
        return confirm('¿Estás seguro de que quieres eliminar este proveedor? Se borrara todo lo relacionado a el tambien');
    }
</script>


</html>