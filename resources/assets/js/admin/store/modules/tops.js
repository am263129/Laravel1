import Vue from 'vue';
import router from '../../routes';
const alertify = require('alertify.js');

const module = {
    state: {
        data: [],
        button_loading: false,
        spinner_loading: false,
    },
    actions: {

        /**
         * Get all channels
         * 
         * @param {*} commit object 
         * @param id int id 
         */
        GET_TOPS({commit}) {
            commit('SPINER_LOAD');
            axios.get('/api/admin/get/top').then(response => {
                if(response.status === 200){                    
                    commit('SET_TOP', response.data.data);                
               }
            }).finally(()=>{
                commit('SPINER_CLEAN');
            });
        },

        /**
         * Delete channels
         * 
         * @param {*} id  uuid
         * @param {*} key int
         */
        DELETE_TOP({commit}, {id, key}) {
            commit('BUTTON_LOAD', id);
            axios.delete('/api/admin/delete/top/' + id).then(response => {
              if (response.status === 200) {
                alertify.logPosition('top right');
                alertify.success('Successful Delete');  
                commit('DELETE_TOP', key);                
              }
            },error => {
                alertify.logPosition('top right');
                alertify.error(error.response.data.message);              
            }).finally(()=>{
                commit('BUTTON_CLEAN');
            });
        },


    },
    mutations: {

        SET_TOP(state, data) {
            state.data = data;
        },

        DELETE_TOP(state, key) {
            state.data.top.splice(key, 1);
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