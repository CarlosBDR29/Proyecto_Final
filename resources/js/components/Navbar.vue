<template>
  <div>
    <div class="navbar-wrapper" ref="navbarWrapper">
      <nav class="navbar navbar-expand-lg custom-navbar">
        <div class="container-fluid">


          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item custom-nav-item"><a class="nav-link" href="/menu">Menu</a></li>
              <li class="nav-item custom-nav-item"><a class="nav-link" href="/almacenes">Almacenes</a></li>
              <li class="nav-item custom-nav-item"><a class="nav-link" href="/todos_productos">Productos</a></li>
              <li class="nav-item custom-nav-item"><a class="nav-link" href="/pedidos">Pedidos</a></li>
              <li class="nav-item custom-nav-item"><a class="nav-link" href="/proveedores">Proveedores</a></li>
            </ul>
            <a href="/logout" class="btn btn-outline-light">Cerrar sesión</a>
          </div>
        </div>
      </nav>
    </div>
  </div>
</template>

<script>

export default {
  name: 'Navbar',
  mounted() {
    const navbarHeight = this.$refs.navbarWrapper.offsetHeight;
    document.body.style.marginTop = navbarHeight + 'px';
  }
}
</script>

<style scoped>
.navbar-wrapper {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  z-index: 9999;
}

.custom-navbar {
  background-color: #022d57 !important;
  /* Azul más oscuro */
  color: white !important;
  width: 100%;
  padding: 10px 20px !important;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3) !important;
}

.custom-navbar .navbar-nav .custom-nav-item {
  margin-right: 15px !important;
  /* Asegura espacio entre los items */
}

.custom-navbar .navbar-nav .custom-nav-item .nav-link {
  color: white !important;
  /* Establece el color del texto */
  font-weight: 600 !important;
  /* Aumenta el peso de la fuente */
  text-transform: uppercase !important;
  /* Hace que el texto esté en mayúsculas */
  padding: 10px 15px !important;
  /* Asegura el padding adecuado para cada enlace */
}

.custom-navbar .navbar-nav .custom-nav-item .nav-link:hover {
  text-decoration: underline !important;
  color: #FFD700 !important;
  /* Color dorado al pasar el ratón */
}


.custom-navbar .btn-outline-light:hover {
  background-color: white !important;
  color: #03274b !important;
}
</style>