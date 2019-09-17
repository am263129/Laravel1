import Vue from 'vue';
import VueRouter from 'vue-router';
import VeeValidate from 'vee-validate';


try {
    window.$ = window.jQuery = require('jquery');
    require('bootstrap');
} catch (e) {}


window.axios = require('axios');


Vue.use(VueRouter);
Vue.use(VeeValidate);

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

