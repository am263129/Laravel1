require('../bootstrap');
import Vue from 'vue';
import app from './views/app.vue';
import store from './store/store.js';
import router from './routes';
import VeeValidate from 'vee-validate';
import swal from 'sweetalert';
import Auth from './packages/Auth';
import Config from './packages/Config';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */
import Echo from "laravel-echo";

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '94e418fde4ca97cf07d6',
    cluster: 'ap2'
});

Vue.use(Auth);
Vue.use(Config);
Vue.use(VeeValidate);

window.onbeforeunload = function() {
    return "Data will be lost if you leave the page, are you sure?";
};


new Vue({
    el: '#administrator',
    store,
    router,
    swal,
    render: h => h(app)
});
