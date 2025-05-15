<!DOCTYPE html>
<html>
<head>
    <title>Mis Almacenes</title>
    @vite(['resources/js/app.js', 'resources/css/almacenes.css'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div id="app">

        <Navbar></Navbar>

        <almacenes-component :almacenes="{{ Js::from($almacenes) }}"></almacenes-component>
    </div>
</body>
</html>
