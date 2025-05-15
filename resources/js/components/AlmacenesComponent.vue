<template>
  <div>
    <h1>Mis Almacenes</h1>

    <!-- Campo de búsqueda -->
    <div class="form-container">
      <input v-model="searchQuery" placeholder="Buscar almacén por nombre" />
      <button @click="buscarAlmacenes">Buscar</button>
    </div>

    <!-- Mostrar lista de almacenes filtrada -->
    <div class="div-lista">
      <ul v-if="almacenes.length > 0">
        <li class="li-almacen" v-for="almacen in filteredAlmacenes" :key="almacen.Id_Alm">
          <span class="nombre-almacen">{{ almacen.Nombre_Alm }}</span>
          <div v-if="editarId === almacen.Id_Alm">
            <input v-model="nuevoNombre" placeholder="Nuevo nombre" />
            <button @click="guardarNombre(almacen.Id_Alm)">Guardar</button>
            <button @click="cancelarEdicion">Cancelar</button>
          </div>
          <div v-else>
            <button @click="entrarAlmacen(almacen.Id_Alm)">Entrar</button>

            <!-- Solo mostrar editar y eliminar si NO es "Almacén Entrada" -->
            <template v-if="almacen.Nombre_Alm !== 'Almacén Entrada'">
              <button @click="editarAlmacen(almacen)">Editar</button>
              <button @click="eliminarAlmacen(almacen.Id_Alm)">Eliminar</button>
            </template>
          </div>
        </li>
      </ul>

      <p v-else class="visto-no-almacenes">No tienes almacenes.</p>

    </div>

    <!-- Formulario para crear nuevo almacén -->
    <div class="form-container-create">
      <input v-model="nuevoAlmacen" placeholder="Nombre del almacén" />
      <button @click="crearAlmacen">Crear Almacén</button>
    </div>
  </div>
</template>




<script>
export default {
  props: {
    almacenes: Array
  },
  data() {
    return {
      editarId: null,
      nuevoNombre: '',
      nuevoAlmacen: '',  // Variable para almacenar el nombre del nuevo almacén
      searchQuery: '',   // Variable para almacenar la consulta de búsqueda
    }
  },
  computed: {
    filteredAlmacenes() {
      // Filtrar los almacenes según la búsqueda
      return this.almacenes.filter(almacen =>
        almacen.Nombre_Alm.toLowerCase().includes(this.searchQuery.toLowerCase())
      );
    }
  },
  methods: {
    async crearAlmacen() {
      if (!this.nuevoAlmacen.trim()) {
        alert('Por favor, ingresa un nombre para el almacén.');
        return;
      }

      try {
        const response = await fetch('/almacenes', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          },
          body: JSON.stringify({ nombre: this.nuevoAlmacen })
        });

        const data = await response.json();

        if (data.success) {
          this.almacenes.push(data.almacen);
          this.nuevoAlmacen = '';
          this.searchQuery = '';
          window.location.reload();
        } else {
          // Mostrar mensaje personalizado desde el backend
          alert(data.message || 'Hubo un problema al crear el almacén.');
        }
      } catch (error) {
        console.error('Error:', error);
        alert('Error al conectar con el servidor.');
      }
    },
    buscarAlmacenes() {
      // Esto simplemente actualiza el listado cuando se presiona el botón
      // El filtrado ya se hace de manera reactiva con `filteredAlmacenes`
    },
    async entrarAlmacen(id) {
      try {
        await fetch('/almacenes/entrar', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          },
          body: JSON.stringify({ id })
        });

        window.location.href = '/almacen';
      } catch (error) {
        console.error('Error al entrar al almacén:', error);
      }
    },
    editarAlmacen(almacen) {
      this.editarId = almacen.Id_Alm;
      this.nuevoNombre = almacen.Nombre_Alm;
    },

    cancelarEdicion() {
      this.editarId = null;
      this.nuevoNombre = '';
    },

    async guardarNombre(id) {
      if (!this.nuevoNombre.trim()) {
        alert('El nombre no puede estar vacío.');
        return;
      }

      try {
        const response = await fetch('/almacenes/editar', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          },
          body: JSON.stringify({ id: id, nombre: this.nuevoNombre })
        });

        const data = await response.json();

        if (data.success) {
          window.location.reload(); // refresca para mostrar el nuevo nombre
        } else {
          alert('No se pudo actualizar el almacén.');
        }
      } catch (error) {
        console.error(error);
        alert('Error al conectar con el servidor.');
      }
    },
    async eliminarAlmacen(id) {
      if (!confirm('¿Estás seguro de que quieres eliminar este almacén?\nSe eliminara tambien los productos de su interior.')) {
        return;
      }

      try {
        const response = await fetch('/almacenes/eliminar', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          },
          body: JSON.stringify({ id })
        });

        const data = await response.json();

        if (data.success) {
          window.location.reload(); // o puedes recargar la lista sin recargar toda la página si prefieres
        } else {
          alert('No se pudo eliminar el almacén.');
        }
      } catch (error) {
        console.error(error);
        alert('Error al conectar con el servidor.');
      }
    }



  }
}
</script>