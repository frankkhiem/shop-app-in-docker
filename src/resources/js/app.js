/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

// import cac Vue UI 
import Vue from 'vue';
import VueSimpleAlert from "vue-simple-alert";
Vue.use(VueSimpleAlert);

import Toast from "vue-toastification";
// Import the CSS or use your own!
import "vue-toastification/dist/index.css";
Vue.use(Toast);


// đưa VueRouter vào
import router from './router';

// đưa VueX vào
import store from './store';

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('general-notification', require('./components/notifications/GetGeneralNotifications.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Truyền sự kiện kèm dữ liệu giữa các component đã được build
Vue.prototype.eventBus = new Vue();

const app = new Vue({
    el: '#app',
    router,
    store,
    created() {
        store.dispatch('fetchAPIGetUser');
    }
});
