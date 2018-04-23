
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import VueRouter from 'vue-router'
import router from './router'
import Notifications from 'vue-notification'
import 'nprogress/nprogress.css'

Vue.use(VueRouter);
Vue.use(Notifications);

// Global Vue Components
Vue.component('navbar', require('./components/Navbar'));
const app = new Vue({
    el: '#app',
    router
});
