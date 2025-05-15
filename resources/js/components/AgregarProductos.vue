<template>
  <div>
    <div v-for="(producto, index) in productosSeleccionados" :key="index" class="producto-item"
      style="display: flex; gap: 20px; margin-bottom: 15px;">

      <div style="flex: 1;">
        <label style="display: block; font-weight: bold; margin-bottom: 5px;">Nombre:</label>
        <select v-model="producto.id" @change="actualizarPrecio(index)" :name="'productos[]'" required style="width: 100%;">
          <option disabled value="">Seleccione un producto</option>
          <option v-for="pro in productos" :key="pro.Id_Pro" :value="pro.Id_Pro">
            {{ pro.Nombre_Pro }}
          </option>
        </select>
      </div>

      <div style="flex: 1;">
        <label style="display: block; font-weight: bold; margin-bottom: 5px;">Cantidad:</label>
        <input type="number" v-model="producto.cantidad" :name="'cantidades[]'" min="1" required style="width: 100%;" />
      </div>

      <div style="flex: 1;">
        <label style="display: block; font-weight: bold; margin-bottom: 5px;">Precio unidad (€):</label>
        <input type="number" step="0.01" min="0" v-model="producto.precio" :name="'precios[]'" required style="width: 100%;" readonly />
      </div>
    </div>

    <button type="button" @click="agregarProducto" class="agregar-btn" style="margin-top: 10px;">
      Agregar otro producto
    </button>
  </div>
</template>

<script>
export default {
  props: {
    productos: Array
  },
  data() {
    return {
      productosSeleccionados: [
        { id: '', cantidad: 1, precio: 0 }
      ]
    };
  },
  methods: {
    agregarProducto() {
      this.productosSeleccionados.push({ id: '', cantidad: 1, precio: 0 });
    },
    actualizarPrecio(index) {
      const productoId = this.productosSeleccionados[index].id;
      const producto = this.productos.find(p => p.Id_Pro === productoId);
      if (producto) {
        this.productosSeleccionados[index].precio = producto.Precio_Unidad_Prove;
      } else {
        this.productosSeleccionados[index].precio = 0;
      }
    }
  }
}
</script>
