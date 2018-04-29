
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import Vuetify from 'vuetify'
import VueRouter from 'vue-router'
import VueYouTubeEmbed from 'vue-youtube-embed'
import { sync } from 'vuex-router-sync'
import store from './store'
import router from './router'
import Notifications from 'vue-notification'
import 'nprogress/nprogress.css'
import 'vuetify/dist/vuetify.min.css'

Vue.use(Vuetify);
Vue.use(VueRouter);
Vue.use(VueYouTubeEmbed);
Vue.use(Notifications);

sync(store, router)

// Global Vue Components
Vue.component('navbar', require('./components/Navbar'));
const app = new Vue({
    el: '#app',
    router,
    store
});
