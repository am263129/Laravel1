import Vue from 'vue';
import router from '../../routes';
const alertify = require('alertify.js');

const module = {
    state: {
        data: [],
        data_search: null,
        button_loading: false,
        spinner_loading: false,
    },
    actions: {

        /**
         * Get all SERIES
         * 
         * @param {*} commit object 
         * @param id int id 
         */
        GET_ALL_ACTORS({commit}) {
            commit('SPINER_ACTOR_LOAD');
            axios.get('/api/admin/get/actors').then(response => {
                // if status code 200
                if(response.status === 200){                    
                    commit('SET_ACTORS', response.data.data);                    
               }
            }).finally(()=>{
                commit('SPINER_ACTOR_CLEAN');
            });
        },


        /**
         * Get actors by pagination
         * 
         * @param {*} commit object 
         * @param id int id 
         */

        GET_ACTORS_PAGINATION({commit}, path_url) {
            commit('SPINER_ACTOR_LOAD');
            axios.get(path_url).then(response => {
                if(response.status === 200){                    
                    commit('SET_ACTORS', response.data.data);                    
               }
            }).finally(()=>{
                commit('SPINER_ACTOR_CLEAN');
            });
        },

        /**
         * Delete SERIES
         * 
         * @param {*} id  uuid
         * @param {*} key int
         */
        DELETE_ACTOR({commit}, {id, key}) {
            axios.delete('/api/admin/delete/actor/' + id).then(response => {
              if (response.status === 200) {
                alertify.logPosition('top right');
                alertify.success(response.data.message);  
                commit('DELETE_ACTOR', key);
              }
            },error => {
                alertify.logPosition('top right');
                alertify.error(error.response.data.message);
            });
        },

        /**
         * Search SERIES
         *
         * @param {string} string query
         */

        GET_ACTORS_SEARCH({commit}, query) {
            commit('SPINER_ACTOR_LOAD');
            axios.post('/api/admin/get/actors/search', {query: query} ).then(response => {
                // if status code 200
                if(response.status === 200){
                    commit('SET_SEARCH_ACTORS', response.data.data);                    
               }
            }).finally(()=>{
                commit('SPINER_ACTOR_CLEAN');
            });
        },

    },

    mutations: {

        SET_ACTORS(state, data) {
            state.data = data;
        },

        SET_SEARCH_ACTORS(state, data) {
            state.data_search = data;
        },
        CLEAN_SEARCH_ACTORS(state) {
            state.data_search = null;
        },

        CLEAR_SEARCH(state) {
            state.data_search = null;
        },

        DELETE_ACTOR(state, key) {
            state.data.actors.data.splice(key, 1);
        },

        SPINER_ACTOR_LOAD(state) {
            state.spinner_loading = true;
        },

        SPINER_ACTOR_CLEAN(state) {
            state.spinner_loading = false;
        }
    },
    getters: {}
};
export default module;