import Vue from 'vue';

const module = {
    state: {
        data: [],
        footer: [],
        plan: [],
        actor_details: {},
        home_loading: false
    },
    actions: {
        // Get all movies form api /api/v1/movies
        GET_GHOST_HOME_LIST({ commit }) {

            commit('HOME_SPINER_LOAD');
            axios.get('/api/v1/ghost/get/discover').then(response => {
                if (response.status === 200) {
                    const list = response.data.data;
                    commit('SET_HOME_LIST', { list });
                    commit('HOME_SPINER_CLEAN');
                }
            });
        },

        /**
         * Get all last movies
         *
         * @param {any} {commit}
         */
        GET_GHOST_MOVIES_LIST({ commit }) {
            commit('SPINER_LOAD_MOVIES');
            axios.get('/api/v1/ghost/get/movies').then(response => {
                if (response.status === 200) {
                    const data = response.data.data;
                    commit('SET_MOVIES_LIST', data);
                    commit('SPINER_CLEAN_MOVIES');
                }
            });
        },
        /**
         * Get all last movies
         *
         * @param {any} {commit}
         */
        GET_GHOST_VIDEOSONGS_LIST({ commit }) {
            commit('SPINER_LOAD_VIDEOSONGS');
            axios.get('/api/v1/ghost/get/videosongs').then(response => {
                console.log(response);
                if (response.status === 200) {
                    const data = response.data.data;
                    commit('SET_VIDEOSONGS_LIST', data);
                    commit('SPINER_CLEAN_VIDEOSONGS');
                }
            });
        },

        /**
         * Get movies sort by trending or genre or twice
         *
         * @param {*} commit
         * @param {int,string} array {trending, genre}
         */
        GET_GHOST_MOVIES_SORT_BY_LIST({ commit }, { trending, genre }) {
            commit('SPINER_LOAD_MOVIES');
            axios
                .post('/api/v1/ghost/get/movies/sort', {
                    trending: trending,
                    genre: genre
                })
                .then(response => {
                    if (response.status === 200) {
                        const data = response.data.data;
                        commit('SET_MOVIES_LIST', data);
                        commit('SPINER_CLEAN_MOVIES');
                    }
                });
        },

        /**
         * Get movie details
         *
         * @param {*} commit
         * @param {*} id movie request
         */
        GET_GHOST_MOVIE_DETAILS({ commit }, id) {
            axios.get('/api/v1/ghost/get/movie/' + id).then(
                response => {
                    if (response.status === 200) {
                        const data = response.data.data;
                        commit('SET_MOVIE_DETAILS', data);
                    }
                },
                error => {
                    if (error.response.status === 404) {
                        router.push({ name: '404' });
                    } else {
                        router.push('/');
                    }
                }
            );
        }
    },
    mutations: {
        SET_HOME_LIST(state, list) {
            state.data = list.list;
        },

        SET_HOME_FOOTER_DETAILS(state, data) {
            state.footer = data;
        },

        SET_HOME_PLAN(state, data) {
            state.plan = data;
        },

        // Spiner load
        HOME_SPINER_LOAD(state) {
            state.home_loading = true;
        },

        HOME_SPINER_CLEAN(state) {
            state.home_loading = false;
        }
    },
    getters: {}
};
export default module;
