<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Interior del Almacén</title>
    @vite(['resources/js/app.js','resources/css/almacen.css'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
</head>
<body>
    <div id="app">

        <Navbar></Navbar>

        <almacen-productos></almacen-productos>
    </div>
</body>
</html>
