import Vue from 'vue';
import Swal from 'sweetalert2'
import ProgressBar from 'vuejs-progress-bar'


require('./bootstrap');

window.Vue = Vue;
window.Swal = Swal;

Vue.component('pagination',require('laravel-vue-pagination'));
//Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('product-component', require('./components/ProductComponent.vue').default);

window.Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 1000,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
  })
  
  

const app = new Vue({
    el: '#app',
    
});
