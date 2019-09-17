import Vue from 'vue';
import Vuex from 'vuex';
import home from './modules/home';
import player from './modules/player';
import auth from './modules/auth';
import casts from './modules/casts';
import search from './modules/search';
import collections from './modules/collections';
import movies from './modules/movies';
import series from './modules/series';
import kids from './modules/kids';
import tv from './modules/tv';
import event from './modules/event';
import videos from './modules/videos';

// GHost
import ghost_home from './modules/ghost/home';
import referral from './modules/referral';
import ghost_movies from './modules/ghost/movies';
import ghost_series from './modules/ghost/series';
import ghost_kids from './modules/ghost/kids';
import ghost_tv from './modules/ghost/tv';
import ghost_cast from './modules/ghost/casts';
import ghost_search from './modules/ghost/search';

Vue.use(Vuex);

export default new Vuex.Store({
    namespaced: true,
    strict: false,
    modules: {
        auth,
        home,
        player,
        casts,
        search,
        collections,
        movies,
        series,
        kids,
        tv,
        ghost_home,
        ghost_movies,
        ghost_series,
        ghost_kids,
        ghost_tv,
        ghost_cast,
        ghost_search,
        event,
        referral,
        videos
    }
});
