require('../bootstrap');
import Vue from 'vue';
import Helper from './packages/Helper';
import VueProgressBar from 'vue-progressbar';
import store from './store/store.js';
import router from './packages/Routes';
import i18n from './packages/Language';
import Auth from './packages/Auth';
import Cloudfront from './packages/Cloudfront';

import VueProgressiveImage from 'vue-progressive-image';

Vue.use(Auth);
Vue.use(Cloudfront);
Vue.use(VueProgressiveImage);

// Get theme name to render a folder of theme 
Vue.use(Helper);

let themeName = Vue.helper.current_theme();
let themePath = require('./views/' + themeName + '/app.vue');

axios.defaults.headers.common ['Authorization'] = 'Bearer ' + Vue.auth.getUserInfo('token');

const options = {
    color: '#03A9F4',
    failedColor: '#F44336',
    thickness: '3px',
    transition: {
        speed: '6s',
        opacity: '1',
        termination: 500
    },
    autoRevert: true,
    location: 'top',
    inverse: false
};

Vue.use(VueProgressBar, options);

/// If is not auth step
router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.userNotAuth)) {
        if(Vue.auth.isAuthenticated() == 'active') {
            next({
                path: '/app'
            });
        }else if (Vue.auth.isAuthenticated() == 'payment_step') {
            next({
                path: '/app/signup/payment'
            });

        }else{
            next();
        }
    } else if (to.matched.some(record => record.meta.allAuth)) {
        if(Vue.auth.isAuthenticated() == 'active') {
            next();
        }else if (Vue.auth.isAuthenticated() == 'payment_step') {
            next({
                path: '/app/signup/payment'
            });

        }else{
            next();
        }
    }else if (to.matched.some(record => record.meta.userAuth)) {
        if(Vue.auth.isAuthenticated() == 'active') {
            next();
        }else if (Vue.auth.isAuthenticated() == 'payment_step') {
            next({
                path: '/app/signup/payment'
            });

        }else {
            next('/app/login');
        }
    }else if  (to.matched.some(record => record.meta.userPaymentAuth)) {
        if(Vue.auth.isAuthenticated() == 'payment_step') {
            next();
        } else {
            next({
                path: '/app'

            });
        }
    } else {
            next(); // make sure to always call next()!
    }
});


router.beforeEach((to, from, next) => {
    if (!to.matched.length) {
        next('/app/404');
    } else {
        next();
    }
});


// Title
router.beforeEach((to, from, next) => {
    // This goes through the matched routes from last to first, finding the closest route with a title.
    // eg. if we have /some/deep/nested/route and /some, /deep, and /nested have titles, nested's will be chosen.
    const nearestWithTitle = to.matched.slice().reverse().find(r => r.meta && r.meta.title);

    // Find the nearest route element with meta tags.
    const nearestWithMeta = to.matched.slice().reverse().find(r => r.meta && r.meta.metaTags);
    const previousNearestWithMeta = from.matched.slice().reverse().find(r => r.meta && r.meta.metaTags);

    // If a route with a title was found, set the document (page) title to that value.
    if(nearestWithTitle) document.title = nearestWithTitle.meta.title;

    // Remove any stale meta tags from the document using the key attribute we set below.
    Array.from(document.querySelectorAll('[data-vue-router-controlled]')).map(el => el.parentNode.removeChild(el));

    // Skip rendering meta tags if there are none.
    if(!nearestWithMeta) return next();

    // Turn the meta tag definitions into actual elements in the head.
    nearestWithMeta.meta.metaTags.map(tagDef => {
        const tag = document.createElement('meta');

        Object.keys(tagDef).forEach(key => {
            tag.setAttribute(key, tagDef[key]);
        });

        // We use this to track which meta tags we create, so we don't interfere with other ones.
        tag.setAttribute('data-vue-router-controlled', '');

        return tag;
    })
    // Add the meta tags to the document head.
        .forEach(tag => document.head.appendChild(tag));

    next();
  });


new Vue({
    el: '.' + themeName, //root
    i18n,
    store,
    router,
    render: h => h(themePath)
});