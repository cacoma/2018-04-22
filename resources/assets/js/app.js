
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

//alterei todo o arquivo pois nao estava inicializando corretamente as funcionalidades rcaziraghi 24042018
import Vue from 'vue';
// import BootstrapVue from 'bootstrap-vue';
import VueTheMask from 'vue-the-mask';
// import money from 'v-money';
import racaz from './javascript/js.js';
//import Errors from './javascript/classErrors.js';
import VueCharts from 'vue-chartjs';
import VueChartkick from 'vue-chartkick';
// import Chart from 'chart.js';
//import Puppeteer from 'puppeteer';

import { Card, Table, Form, FormInput, Button, Layout, Modal, Progress, Navbar, Nav, Dropdown, FormGroup, InputGroup, 
        FormSelect, Pagination, Tooltip, Alert } from 'bootstrap-vue/es/components';
// Add the plugins to Vue
Vue.use(Card);
Vue.use(Table);
Vue.use(Form);
Vue.use(FormInput);
Vue.use(FormGroup);
Vue.use(Button);
Vue.use(Layout);
Vue.use(Modal);
Vue.use(Progress);
Vue.use(Navbar);
Vue.use(Nav);
Vue.use(Dropdown);
Vue.use(InputGroup);
Vue.use(FormSelect);
Vue.use(Pagination);
Vue.use(Tooltip);
Vue.use(Alert);


// Vue.use(money, {
//   precision: 4
// });
Vue.use(VueTheMask);
// Vue.use(BootstrapVue);
Vue.use(racaz);
//Vue.use(puppeteer);
//Vue.use(Errors);
Vue.use(VueChartkick, {adapter: Chart});

require('./bootstrap');

window.Vue = require('vue');
window.moment = require('moment');
// window.Papa = require('papaparse');
window.fundsInfo = require('./javascript/getFundsInfo.js');

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
window.createFund = (item) => {
    window.events.$emit('createFund',item);
};
window.createTypeStocks = (item) => {
    window.events.$emit('createTypeStocks',item);
};
window.createTypeTreasuries = (item) => {
    window.events.$emit('createTypeTreasuries',item);
};
window.createTypeSecurities = (item) => {
    window.events.$emit('createTypeSecurities',item);
};
window.createTypeFunds = (item) => {
    window.events.$emit('createTypeFunds',item);
};
window.sellTypeStocks = (item) => {
    window.events.$emit('sellTypeStocks',item);
};
window.sellTypeTreasuries = (item) => {
    window.events.$emit('sellTypeTreasuries',item);
};
window.sellTypeSecurities = (item) => {
    window.events.$emit('sellTypeSecurities',item);
};
window.sellTypeFunds = (item) => {
    window.events.$emit('sellTypeFunds',item);
};
window.deleteconfirmation = (item) => {
    window.events.$emit('deleteconfirmation',item);
};
window.enlarge = (type, incoming) => {
    window.events.$emit('enlarge', type, incoming);
};
window.progressBarValue = (value) => {
    window.events.$emit('progressBarValue', value);
};
window.progressBar = (value) => {
    window.events.$emit('progressBar', value);
};

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('navbaradmin', require('./components/layouts/navbaradmin.vue'));
// Vue.component('homecarousel', require('./components/layouts/homecarousel.vue'));
//Vue.component('homechart', require('./components/layouts/homechart.vue'));
Vue.component('index', require('./components/index/index.vue'));
Vue.component('indexinvests', require('./components/index/indexInvests.vue'));
Vue.component('indexfund', require('./components/index/indexFund.vue'));
Vue.component('indexoperations', require('./components/index/indexOperations.vue'));


Vue.component('consolidatedinvests', require('./components/invests/consolidatedInvests.vue'));

Vue.component('createinvests', require('./components/invests/createInvests.vue'));

Vue.component('createinveststypestocks', require('./components/invests/createTypeStocks.vue'));
Vue.component('createinveststypetreasuries', require('./components/invests/createTypeTreasuries.vue'));
Vue.component('createinveststypesecurities', require('./components/invests/createTypeSecurities.vue'));
Vue.component('createinveststypefunds', require('./components/invests/createTypeFunds.vue'));


Vue.component('sellinveststypestocks', require('./components/sellInvests/sellTypeStocks.vue'));
Vue.component('sellinveststypetreasuries', require('./components/sellInvests/sellTypeTreasuries.vue'));
Vue.component('sellinveststypesecurities', require('./components/sellInvests/sellTypeSecurities.vue'));
Vue.component('sellinveststypefunds', require('./components/sellInvests/sellTypeFunds.vue'));


//Vue.component('createstock', require('./components/stocks/createStock.vue'));
Vue.component('create', require('./components/create/create.vue'));
Vue.component('createfund', require('./components/create/createFund.vue')); //vue especifico para cadastro(criacao) de fundos de investimento
// Vue.component('createquotes', require('./components/quotes/createquotes.vue'));
// Vue.component('massinsert', require('./components/quotes/massInsert.vue'));
Vue.component('flash', require('./components/layouts/flash.vue')); //componente proprio para lidar com mensagens
Vue.component('loading', require('./components/layouts/loading.vue')); //componente proprio para animacao de loading
Vue.component('deleteconfirmation', require('./components/layouts/deleteConfirmation.vue')); //componente proprio para confirmacao de exclusao e exclusao
Vue.component('homescreen', require('./components/layouts/homescreen.vue')); //componente para tela home
Vue.component('homechartint', require('./components/layouts/homechartint.vue')); //componente para tela home
Vue.component('homechart', require('./components/layouts/homechart.vue')); //componente para tela home
Vue.component('enlarge', require('./components/layouts/enlarge.vue')); //componente aumentar tamanho de itens selecionados
Vue.component('moldura', require('./components/layouts/moldura.vue')); //componente aumentar tamanho de itens selecionados
Vue.component('progressbar', require('./components/layouts/progressBar.vue')); //componente aumentar tamanho de itens selecionados
Vue.component('quantinput', require('./components/custom/quantInput.vue')); //componente aumentar tamanho de itens selecionados


//Vue.component('treasuryscrape', require('./components/scraping/treasuryscrape.vue')); //componente para fazer scrape dos valores do tesouro nacional

//https://forum.vuejs.org/t/eventhub-with-vueify/1375/3
const bus = new Vue();
Vue.prototype.$bus = bus;

const app = new Vue({
    el: '#app'
});
