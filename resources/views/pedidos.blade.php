<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mis Pedidos</title>
    @vite(['resources/js/app.js','resources/css/pedidos.css'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>

    <div id="app">
        <Navbar></Navbar>
    </div>

    <div class="contenedor-pedidos">
        <h1>Mis Pedidos</h1>

        <div class="acciones-superiores">
            <form method="GET" action="{{ route('pedidos.crear') }}" style="display: flex; align-items: center; gap: 10px;">
                <label for="proveedor">Nuevo Pedido:</label>
                <select name="proveedor" class="select-proveedor" required>

                    <option value="">Selecciona un proveedor</option>
                    @foreach($proveedores as $proveedor)
                    <option value="{{ $proveedor->Id_Prove }}">{{ $proveedor->Nombre_Prove }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn-nuevo">+ Nuevo Pedido</button>
            </form>


            <form method="GET" action="{{ route('pedidos') }}" class="form-filtros">
                <label for="fecha">Fecha:</label>
                <input type="date" name="fecha" value="{{ request('fecha') }}">

                <label for="estado">Estado:</label>
                <select name="estado">
                    <option value="">Todos</option>
                    <option value="Pendiente" {{ request('estado') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="Entregado" {{ request('estado') == 'Entregado' ? 'selected' : '' }}>Entregado</option>
                </select>

                <button type="submit" class="btn-buscar">Buscar</button>
            </form>
        </div>

        @if ($pedidos->isEmpty())
        <p class="mensaje-vacio">No tienes pedidos.</p>
        @else
        <div class="lista-pedidos">
            @foreach ($pedidos as $pedido)
            <div class="pedido">
                <div class="datos">
                    <strong>Pedido Nº {{ $pedido->Id_Cabe }}</strong><br>
                    Fecha: {{ $pedido->Fecha_Ped }}<br>
                    Estado: <span class="{{ $pedido->Estado === 'Entregado' ? 'estado-entregado' : 'estado-pendiente' }}">{{ $pedido->Estado }}</span>
                </div>

                <div class="acciones">
                    @if($pedido->Estado !== 'Entregado')
                    <form action="{{ route('pedidos.entregar', $pedido->Id_Cabe) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" onclick="return confirm('¿Marcar este pedido como entregado?')" class="btn-entregar">
                            Marcar como Entregado
                        </button>
                    </form>
                    @else
                    <span class="entregado-ok">✓ Entregado</span>
                    @endif

                    <form action="{{ route('pedidos.borrar', $pedido->Id_Cabe) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('¿Estás seguro de borrar este pedido?')" class="btn-borrar">
                            Borrar
                        </button>
                    </form>

                    <form method="POST" action="{{ route('guardar_pedido_sesion') }}">
                        @csrf
                        <input type="hidden" name="id_cabe" value="{{ $pedido->Id_Cabe }}">
                        <button type="submit" class="btn-ver">Ver contenido</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</body>

</html>