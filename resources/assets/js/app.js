
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

//alterei todo o arquivo pois nao estava inicializando corretamente as funcionalidades rcaziraghi 24042018
import Vue from 'vue';
import BootstrapVue from 'bootstrap-vue';
import VueTheMask from 'vue-the-mask';
import money from 'v-money';
import racaz from './javascript/js.js';
//import Errors from './javascript/classErrors.js';
import VueCharts from 'vue-chartjs';
import VueChartkick from 'vue-chartkick';
import Chart from 'chart.js'

Vue.use(money, {
  precision: 4
});
Vue.use(VueTheMask);
Vue.use(BootstrapVue);
Vue.use(racaz);
//Vue.use(Errors);
Vue.use(VueChartkick, {adapter: Chart})

require('./bootstrap');

window.Vue = require('vue');
window.moment = require('moment');

//para o componente proprio para lidar com mensagens, desta forma ativa no javascript
window.events = new Vue();

window.flash = function(message) {
    window.events.$emit('flash',message);
};
window.loadingon = () => {
    window.events.$emit('loadingon');
};
window.loadingoff = () => {
    window.events.$emit('loadingoff');
};
window.create = (item) => {
    window.events.$emit('create',item);
};
window.createTypeStocks = (item) => {
    window.events.$emit('createTypeStocks',item);
};
window.deleteconfirmation = (item) => {
    window.events.$emit('deleteconfirmation',item);
};
window.enlarge = (type, incoming) => {
    window.events.$emit('enlarge', type, incoming);
};

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('navbaradmin', require('./components/layouts/navbaradmin.vue'));
Vue.component('homecarousel', require('./components/layouts/homecarousel.vue'));
//Vue.component('homechart', require('./components/layouts/homechart.vue'));
Vue.component('index', require('./components/index.vue'));
Vue.component('createinvests', require('./components/invests/createInvests.vue'));
Vue.component('createinveststypestocks', require('./components/invests/createTypeStocks.vue'));
//Vue.component('createstock', require('./components/stocks/createStock.vue'));
Vue.component('create', require('./components/create.vue'));
Vue.component('createquotes', require('./components/quotes/createquotes.vue'));
Vue.component('massinsert', require('./components/quotes/massInsert.vue'));
Vue.component('flash', require('./components/layouts/flash.vue')); //componente proprio para lidar com mensagens
Vue.component('loading', require('./components/layouts/loading.vue')); //componente proprio para animacao de loading
Vue.component('deleteconfirmation', require('./components/layouts/deleteConfirmation.vue')); //componente proprio para confirmacao de exclusao e exclusao
Vue.component('homescreen', require('./components/layouts/homescreen.vue')); //componente para tela home
Vue.component('homechartint', require('./components/layouts/homechartint.vue')); //componente para tela home
Vue.component('homechart', require('./components/layouts/homechart.vue')); //componente para tela home
Vue.component('enlarge', require('./components/layouts/enlarge.vue')); //componente aumentar tamanho de itens selecionados
Vue.component('moldura', require('./components/layouts/moldura.vue')); //componente aumentar tamanho de itens selecionados

//https://forum.vuejs.org/t/eventhub-with-vueify/1375/3
const bus = new Vue()
Vue.prototype.$bus = bus

const app = new Vue({
    el: '#app'
});
