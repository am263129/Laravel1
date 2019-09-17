import Vue from 'vue';
import router from '../../routes';
const alertify = require('alertify.js');

const module = {
    state: {
        data: [],
        data_search: {},
        button_loading: false,
        spinner_loading: false,
        videosongs: []
    },
    actions: {
        /**
         * Get all videosongs
         *
         * @param {*} commit object
         * @param id int id
         */
        GET_VIDEOSONGS({ commit }) {
            commit('SPINER_LOAD');
            axios.get('/api/admin/get/videosong').then(response => {
                console.log(response);
                if (response.status === 200) {
                    commit('SET_VIDEOSONGS', response.data.data);                    
                }
            }).finally(()=>{
                commit('SPINER_CLEAN');
            });
        },

        GET_VIDEOSONG({ commit }, id) {
            commit('SPINER_LOAD');
            axios.get('/api/admin/get/videosong/' + id).then(
                response => {
                    if (response.status === 200) {
                        commit('SET_VIDEOSONGS', response.data.data);             
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
         * Get videosongs by pagination
         *
         * @param {*} commit object
         * @param id int id
         */

        GET_VIDEOSONGS_PAGINATION({ commit }, path_url) {
            commit('SPINER_LOAD');
            axios.get(path_url).then(response => {
                // if status code 200
                if (response.status === 200) {
                    commit('SET_VIDEOSONGS', response.data.data);                
                }
            }).finally(()=>{
                commit('SPINER_CLEAN');
            });
        },

        /**
         * Delete videosong
         *
         * @param {*} id  uuid
         * @param {*} key int
         */
        DELETE_VIDEOSONG({ commit }, list) {
            axios.post('/api/admin/delete/videosong', { list: list }).then(
                response => {
                    if (response.status === 200) {
                        alertify.logPosition('top right');
                        alertify.success('Successful Delete');
                        commit('DELETE_VIDEOSONG', list);                        
                    }
                },
                error => {
                    alertify.logPosition('top right');
                    alertify.error(error.response.data.message);                    
                }
            ).finally(()=>{
                commit('BUTTON_CLEAN');
            });
        },

        /**
         * Search videosong
         *
         * @param {string} string query
         */

        GET_VIDEOSONG_SEARCH({ commit }, query) {
            commit('SPINER_LOAD');
            axios
                .post('/api/admin/get/videosong/search', { query: query })
                .then(response => {
                    // if status code 200
                    if (response.status === 200) {
                        commit('SET_SEARCH_VIDEOSONGS', response.data.data);                        
                    }
                }).finally(()=>{
                    commit('SPINER_CLEAN');
                });
        },

        ADD_VIDEOSONG_TO_TOP({ commit }, { id, key }) {
            axios.post('/api/admin/new/videosong/top', { id: id }).then(
                response => {
                    if (response.status === 200) {
                        alertify.logPosition('top right');
                        alertify.success(response.data.message);
                        commit('ADD_VIDEOSONG_TO_TOP', { id, key });
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
        SET_VIDEOSONGS(state, data) {
            state.data = data;
        },

        SET_SEARCH_VIDEOSONGS(state, data) {
            state.data_search = data;
        },

        CLEAN_SEARCH_VIDEOSONGS(state) {
            state.data_search = {};
        },

        DELETE_VIDEOSONG(state, list) {
            for (let i = 0; i < list.length; i++) {
                state.data.videosongs.data.splice(list[i].key, 1);
            }
        },

        ADD_VIDEOSONG_TO_TOP(state, data) {
            state.data.videosongs.data[data.key].videosong_id = data.id;
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
