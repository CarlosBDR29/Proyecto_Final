<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    @vite(['resources/js/app.js', 'resources/css/login.css'])
</head>

<body>



    <form method="POST" action="/login">

        <div style="text-align: center; margin-top: 30px;">
            <img src="/imagenes_menu/logo.png" alt="Logo EasyStock" style="max-width: 200px;">
        </div>
        @if(session('error'))
        <p style="color:red">{{ session('error') }}</p>
        @endif

        @csrf
        <label for="Correo">Correo:</label>
        <input type="email" name="Correo" required>

        <label for="Contrasenya">Contraseña:</label>
        <input type="password" name="Contrasenya" required>

        <button type="submit">Iniciar sesión</button>

        <p><a href="/register">¿No tienes cuenta? Regístrate aquí</a></p>
    </form>

</body>


</html>