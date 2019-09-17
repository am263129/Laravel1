import Vue from 'vue';
import router from '../../routes';
const alertify = require('alertify.js');

const module = {
    state: {
        data: [],
        data_search: {},
        button_loading: false,
        spinner_loading: false,
    },
    actions: {

        /**
         * Get all reports
         * 
         * @param {*} commit object 
         * @param id int id 
         */
        GET_ALL_REPORTS({commit}) {
            commit('SPINER_LOAD');
            axios.get('/api/admin/get/reports').then(response => {
                if(response.status === 200){                    
                    commit('SET_REPORTS', response.data.data);                    
               }
            }).finally(()=>{
                commit('SPINER_CLEAN');
            });
        },

        /**
         * Get reports by pagination
         * 
         * @param {*} commit object 
         * @param id int id 
         */

        GET_REPORTS_PAGINATION({commit}, path_url) {
            commit('SPINER_LOAD');
            axios.get(path_url).then(response => {
                if(response.status === 200){                    
                    commit('SET_REPORTS', response.data.data);              
               }
            }).finally(()=>{
                commit('SPINER_CLEAN');
            });
        },


        /**
         * Get reports of some movie/series
         * 
         * @param {*} commit object 
         * @param id int id 
         */

        GET_REPORT({commit}, id) {
            commit('SPINER_LOAD');
            axios.get('/api/admin/get/report/'+ id).then(response => {
                if(response.status === 200){                    
                    commit('SET_REPORTS', response.data.data);              
               }
            }).finally(()=>{
                commit('SPINER_CLEAN');
            });
        },

        /**
         * Get report of movie/seris by pagination
         * 
         * @param {*} commit object 
         * @param id int id 
         */

        GET_REPORT_PAGINATION({commit}, path_url) {
            commit('SPINER_LOAD');
            axios.get(path_url).then(response => {
                if(response.status === 200){                    
                    commit('SET_REPORTS', response.data.data);              
               }
            }).finally(()=>{
                commit('SPINER_CLEAN');
            });
        },

        /**
         * Delete one report of movie/series
         * 
         * @param {*}
         * @param {int,int} 
         */
        DELETE_REPORT({commit}, {id, key}) {
            axios.delete('/api/admin/delete/report/' + id).then(response => {
              if (response.status === 200) {
                alertify.logPosition('top right');
                alertify.success(response.data.message);  
                commit('DELETE_REPROT', key);
              }
            },error => {
                alertify.logPosition('top right');
                alertify.error(error.response.data.message);
            });
        },

        /**
         * Delete all reports of movie/series
         * 
         * @param {*}
         * @param {int,int} 
         */
        DELETE_ALL_REPORTS({commit}, {id, key}) {
            axios.delete('/api/admin/delete/all/reports/' + id).then(response => {
              if (response.status === 200) {
                alertify.logPosition('top right');
                alertify.success(response.data.message);  
                commit('DELETE_REPROT', key);
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

        GET_SERIES_SEARCH({commit}, query) {
            commit('SPINER_LOAD');
            axios.post('/api/admin/get/series/search', {query: query} ).then(response => {
                // if status code 200
                if(response.status === 200){                    
                    commit('SET_SEARCH_SERIES', response.data.data);              
               }
            }).finally(()=>{
                commit('SPINER_CLEAN');
            });
        },

    },

    mutations: {

        SET_REPORTS(state, data) {
            state.data = data;
        },

        SET_SEARCH_SERIES(state, data) {
            state.data_search = data;
        },

        DELETE_REPROT(state, key) {
            state.data.reports.data.splice(key, 1);
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