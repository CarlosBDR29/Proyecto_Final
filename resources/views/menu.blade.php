<!DOCTYPE html>
<html>
<head>
    <title>Menú Principal</title>
    @vite(['resources/js/app.js', 'resources/css/menu.css'])
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>
<body>
    <div id="app">
        <Navbar></Navbar>
    </div>

    <div class="container menu-principal">
        <h1>Bienvenido al Menú Principal</h1>
        <ul class="menu-ul">
            <li>
                <a href="/almacenes">
                    <img src="{{ asset('imagenes_menu/estante.png') }}" alt="Almacenes">
                    Almacenes
                </a>
            </li>
            <li>
                <a href="/proveedores">
                    <img src="{{ asset('imagenes_menu/proveedor.png') }}" alt="Proveedores">
                    Proveedores
                </a>
            </li>
            <li>
                <a href="/todos_productos">
                    <img src="{{ asset('imagenes_menu/producto.png') }}" alt="Productos">
                    Productos
                </a>
            </li>
            <li>
                <a href="/pedidos">
                    <img src="{{ asset('imagenes_menu/pedido.png') }}" alt="Pedidos">
                    Pedidos
                </a>
            </li>
        </ul>
        <p id="logout"><a href="/logout">Cerrar sesión</a></p>
    </div>
</body>
</html>
