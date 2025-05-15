import { createApp } from 'vue'
import AlmacenesComponent from './components/AlmacenesComponent.vue'
import AlmacenProductos from './components/AlmacenProductos.vue'
import AgregarProductos from './components/AgregarProductos.vue'
import Navbar from './components/Navbar.vue'
import axios from 'axios'
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap'



axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

createApp({
    components: {
        AlmacenesComponent,
        AlmacenProductos,
        AgregarProductos,
        Navbar
    }
    
}).mount('#app')
