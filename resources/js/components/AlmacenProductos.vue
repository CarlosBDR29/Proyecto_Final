<template>
    <div class="contenedor-principal">
        <h1 v-if="!cargando">Almacén: {{ almacen.Nombre_Alm }}</h1>

        <div v-if="cargando" class="loader"></div>

        <div v-if="!cargando">
            <div class="botones-superiores">
                <div class="buscar-grupo">
                    <input type="text" v-model="busqueda" placeholder="Buscar producto por nombre" />
                    <button @click="buscarProductos">Buscar</button>
                </div>
            </div>

            <div v-if="productos.length === 0" class="no-productos">
                <p>No hay productos en este almacén.</p>
            </div>

            <div v-else class="productos-box">
                <div class="grid-productos">
                    <div v-for="producto in productos" :key="producto.Id_Pro" class="producto-item">
                        <img :src="'/imagenes_productos/' + producto.Id_Pro + '.png'"
                             alt="Imagen del producto"
                             @error="e => e.target.src = '/imagenes_productos/SinImagen.png'" />
                        <div>
                            {{ producto.Nombre_Pro }} - Stock: {{ producto.Stock }}
                            <br>
                            <button @click="guardarYIr(producto.Id_Pro)" class="mt-2 p-1 bg-green-500 text-white rounded">
                                Ver detalles
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            almacen: {},
            productos: [],
            busqueda: '',
            cargando: true // <- nuevo estado
        };
    },
    methods: {
        async cargarDatos() {
            const res = await fetch('/almacen/datos');
            const data = await res.json();

            if (data.success) {
                this.almacen = data.almacen;
                this.productos = data.productos;
            }

            this.cargando = false; // <- ocultar loader
        },
        async buscarProductos() {
            this.cargando = true;
            const res = await fetch(`/almacen/buscar?nombre=${encodeURIComponent(this.busqueda)}`);
            const data = await res.json();

            if (data.success) {
                this.productos = data.productos;
            }
            this.cargando = false;
        },
        guardarYIr(id) {
            fetch('/producto/guardar-id', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ id: id })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = '/producto';
                    }
                });
        }
    },
    mounted() {
        this.cargarDatos();
    }
};
</script>
