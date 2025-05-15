<!DOCTYPE html>
<html>

<head>
    <title>Todos los Productos</title>
    @vite(['resources/js/app.js','resources/css/todosProductos.css'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>

    <div id="app">
        <Navbar></Navbar>
    </div>

    <div class="container">
        <h1>Todos los Productos</h1>

        <div class="form-crear-wrapper">
            <form method="GET" action="{{ route('todos_productos') }}">
                <input type="text" name="buscar" placeholder="Buscar producto..." value="{{ request('buscar') }}">
                <button type="submit">Buscar</button>
            </form>

            <a href="/producto/crear" class="crear-btn">
                Crear nuevo producto
            </a>
        </div>

        @if ($productos->isEmpty())
        <p>No tienes productos registrados.</p>
        @else
        <div class="productos-wrapper">
            <ul>
                @foreach ($productos as $producto)
                <li class="li-productos">
                    <div class="producto-info">
                        <div>Producto: {{ $producto->Nombre_Pro }}</div>
                        <div>Stock Total: {{ $producto->Stock }}</div>
                    </div>
                    <form action="{{ url('/ver_producto_total') }}" method="POST" style="display: inline;">
                        @csrf
                        <input type="hidden" name="Id_Pro" value="{{ $producto->Id_Pro }}">
                        <button type="submit" class="ver-detalles-btn">Ver detalles</button>
                    </form>
                </li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
</body>

</html>