import Vue from 'vue';
import router from '../../routes';
const alertify = require('alertify.js');

const module = {
    state: {
        data: [],
        data_search: {},
        button_loading: false,
        spinner_loading: false
    },
    actions: {
        /**
         * Get all movies
         *
         * @param {*} commit object
         * @param id int id
         */
        GET_MOVIES({ commit }) {
            commit('SPINER_LOAD');
            axios.get('/api/admin/get/movies').then(response => {
                if (response.status === 200) {
                    console.log(response.data.data);
                    commit('SET_MOVIES', response.data.data);                    
                }
            }).finally(()=>{
                commit('SPINER_CLEAN');
            });
        },

        GET_MOVIE({ commit }, id) {
            commit('SPINER_LOAD');
            axios.get('/api/admin/get/movie/' + id).then(
                response => {
                    if (response.status === 200) {
                        commit('SET_MOVIES', response.data.data);                        
                    }
                },
                error => {
                    alertify.logPosition('top right');
                    alertify.error(error.response.data.message);
                }
            ).finally(()=>{
                commit('SPINER_CLEAN');
            });
        },

        /**
         * Get movies by pagination
         *
         * @param {*} commit object
         * @param id int id
         */

        GET_MOVIES_PAGINATION({ commit }, path_url) {
            commit('SPINER_LOAD');
            axios.get(path_url).then(response => {
                // if status code 200
                if (response.status === 200) {
                    commit('SET_MOVIES', response.data.data);                    
                }
            }).finally(()=>{
                commit('SPINER_CLEAN');
            });;
        },

        /**
         * Delete movie
         *
         * @param {*} id  uuid
         * @param {*} key int
         */
        DELETE_MOVIE({ commit }, list) {
            commit('BUTTON_LOAD');            
            axios.post('/api/admin/delete/movie', { list: list }).then(
                response => {
                    if (response.status === 200) {
                        alertify.logPosition('top right');
                        alertify.success('Successful Delete');
                        commit('DELETE_MOVIE', list);                        
                    }
                },
                error => {
                    alertify.logPosition('top right');
                    alertify.error(error.response.data.message);                    
                }
            ).finally(()=>{
                commit('BUTTON_CLEAN');
            });;
        },

        /**
         * Search movie
         *
         * @param {string} string query
         */

        GET_MOVIE_SEARCH({ commit }, query) {
            commit('SPINER_LOAD');
            axios
                .post('/api/admin/get/movie/search', { query: query })
                .then(response => {
                    // if status code 200
                    if (response.status === 200) {
                        commit('SET_SEARCH_MOVIES', response.data.data);                        
                    }
                }).finally(()=>{
                    commit('SPINER_CLEAN');
                });
        },

        ADD_MOVIE_TO_TOP({ commit }, { id, key }) {
            axios.post('/api/admin/new/movie/top', { id: id }).then(
                response => {
                    if (response.status === 200) {
                        alertify.logPosition('top right');
                        alertify.success(response.data.message);
                        commit('ADD_MOVIE_TO_TOP', { id, key });
                    }
                },
                error => {
                    alertify.logPosition('top right');
                    alertify.error(error.response.data.message);
                }
            );
        }
    },
    mutations: {
        SET_MOVIES(state, data) {
            state.data = data;
        },

        SET_SEARCH_MOVIES(state, data) {
            state.data_search = data;
        },

        CLEAN_SEARCH_MOVIES(state) {
            state.data_search = {};
        },

        DELETE_MOVIE(state, list) {
            for (let i = 0; i < list.length; i++) {
                state.data.movies.data.splice(list[i].key, 1);
            }
        },

        ADD_MOVIE_TO_TOP(state, data) {
            state.data.movies.data[data.key].movie_id = data.id;
        },

        BUTTON_LOAD(state, data) {
            state.button_loading = data;
        },

        BUTTON_CLEAN(state) {
            state.button_loading = false;
        },

        SPINER_LOAD(state) {
            state.spinner_loading = true;
        },

        SPINER_CLEAN(state) {
            state.spinner_loading = false;
        }
    },
    getters: {}
};
export default module;
