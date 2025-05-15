<!DOCTYPE html>
<html>

<head>
    <title>Registro</title>
    @vite(['resources/js/app.js', 'resources/css/register.css'])
</head>

<body>
    <h1>Registro de Usuario</h1>


    <form method="POST" action="/register">
        @if(session('error'))
        <p style="color:red">{{ session('error') }}</p>
        @endif

        @csrf
        <label for="Nombre">Nombre:</label>
        <input type="text" name="Nombre" required>

        <label for="Correo">Correo:</label>
        <input type="email" name="Correo" required>

        <label for="Contrasenya">Contraseña:</label>
        <input type="password" name="Contrasenya" required
            pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$"
            title="Debe tener al menos 8 caracteres, una letra y un número.">

        <button type="submit">Registrarse</button>

        <p><a href="/login">Ya tengo cuenta</a></p>
    </form>



</body>

</html>