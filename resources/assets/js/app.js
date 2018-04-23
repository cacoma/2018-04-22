
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));

const app = new Vue({
    el: '#app'
});

// importar bibliotecas no app.js - Machado - 22/04/2018 as 21:46hs
import BootstrapVue from 'bootstrap-vue';
import moment from 'moment';
import Vue from 'vue';
import money from 'v-money';
import VueTheMask from 'vue-the-mask';

// Bootstrap and Bootstrap-vue css files - Machado - 22/04/2018 as 21:57hs
//import 'bootstrap/dist/css/bootstrap.css';
//import 'bootstrap-vue/dist/bootstrap-vue.css';

// register directive v-money and componente <money> - Machado - 22/04/2018 as 21:49hs
Vue.use(money,{precision: 4});
Vue.use(VueTheMask);
