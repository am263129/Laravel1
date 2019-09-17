import Vue from 'vue';
import router from '../../routes';
const alertify = require('alertify.js');

const module = {
    state: {
        data: [],
        support_request: [],
        search_data: null,
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
        GET_ALL_SUPPORT_REQUEST({commit}) {
            commit('SPINER_LOAD');
            axios.get('/api/admin/get/support').then(response => {
                if(response.status === 200){  
                    commit('SET_ALL_REQUEST', response.data.data);
                    commit('SPINER_CLEAN');
               }
            }).finally(()=>{
                commit('SPINER_CLEAN');
            });
        },
       
       
        /**
         * Get open channels
         * 
         * @param {*} commit object 
         * @param id int id 
         */
        GET_OPEN_SUPPORT_REQUEST({commit}) {
          commit('SPINER_LOAD');
          axios.get('/api/admin/get/support/open').then(response => {
              if(response.status === 200){  
                  commit('SET_ALL_REQUEST', response.data.data);            
             }
          }).finally(()=>{
            commit('SPINER_CLEAN');
          });
      },

        /**
         * Get closed channels
         * 
         * @param {*} commit object 
         * @param id int id 
         */
        GET_CLOSED_SUPPORT_REQUEST({commit}) {
          commit('SPINER_LOAD');
          axios.get('/api/admin/get/support/closed').then(response => {
              if(response.status === 200){  
                  commit('SET_ALL_REQUEST', response.data.data);                  
             }
          }).finally(()=>{
            commit('SPINER_CLEAN');
          });
      },

        /**
         * Get all users by panginate
         * 
         * @param {*} commit object 
         * @param id int id 
         */
        GET_ALL_SUPPORT_REQUEST_BY_PAGINATION({commit}, path_url) {
          commit('SPINER_LOAD');
          axios.get(path_url).then(response => {
              if(response.status === 200){                    
                  commit('SET_USERS', response.data.data);                  
             }
          }).finally(()=>{
            commit('SPINER_CLEAN');
          });
      },


        /**
         * Get Supoort Request
         * 
         * @param {any} {commit } 
         */

        GET_SUPPORT_REQUEST({commit}, {id}) {
            commit('SPINER_LOAD');
            axios.get('/api/admin/get/support/request/' + id).then(response => {
                if (response.status === 200) {
                    commit('SET_SUPPORT_REQUEST', {
                        data: response.data.data,
                    });                    
                 }else if(response.status === 204) {
                    commit('SET_SUPPORT_REQUEST', {
                        data: null
                    });                    
                }
            }).finally(()=>{
                commit('SPINER_CLEAN');
            });
        },



        /**
         * Submit Supoort Reply
         * 
         * @param {any} {commit } 
         */

        SUBMIT_SUPPORT_REPLY({commit}, {id, reply}) {
            commit('BUTTON_LOAD', 'reply');
            axios.post('/api/admin/create/support/request/reply', {id: id, reply: reply}).then(response => {
                if (response.status === 200) {
                    commit('SET_SUPPORT_REPLY', {data: response.data.data});
                  }else if(response.status === 204) {
                    commit('SET_SUPPORT_REQUEST', {data: null});
                }                
            }).finally(()=>{
                commit('BUTTON_CLEAN');
            });
        },

               /**
         * Submit Supoort Reply
         * 
         * @param {any} {commit } 
         */

        UPDATE_SUPPORT_STATUS({commit}, {id}) {
            commit('BUTTON_LOAD', 'status');
            axios.put('/api/admin/update/support/request/status/' + id).then(response => {
                if (response.status === 200) {
                    commit('SET_SUPPORT_STATUS', {
                        data: response.data.data,
                    });
                    alertify.logPosition('top right');
                    alertify.success('Successful Close Request');                      
                 }                
            }).finally(()=>{
                commit('BUTTON_CLEAN');
            });
        },

        /**
         * Delete channels
         * 
         * @param {*} id  uuid
         * @param {*} key int
         */
        DELETE_SPPORT_REQUEST({commit}, {id, key}) {
            commit('BUTTON_LOAD', id);
            axios.delete('/api/admin/delete/support/request/' + id).then(response => {
              if (response.status === 200) {
                alertify.logPosition('top right');
                alertify.success('Successful Delete');  
                commit('DELETE_REQUEST', key);                
              }
            },error => {
                alertify.logPosition('top right');
                alertify.error(error.response.data.message);                
            }).finally(()=>{
                commit('BUTTON_CLEAN');
            });
        },

        /**
         * Get Supoort Request
         * 
         * @param {any} {commit } 
         */

        GET_SUPPORT_REQUEST_SEARCH({commit}, query) {
            commit('SPINER_LOAD');
            axios.post('/api/admin/get/support/search', {query: query}).then(response => {
                if (response.status === 200) {
                    commit('SET_SUPPORT_SEARCH_REQUEST', {
                        data: response.data.data
                    });                
                }
            }).finally(()=>{
                commit('SPINER_CLEAN');
            });
        },


    },
    mutations: {

        SET_ALL_REQUEST(state, data) {
            state.data = data;
        },

        SET_SUPPORT_SEARCH_REQUEST(state, data) {
            state.search_data = []
            state.search_data = data.data;
        },

        CLEAR_SUPPORT_SEARCH_DATA(state, data) {
            state.search_data = null;
        },

        SET_SUPPORT_REQUEST(state, data) {
            state.support_request = data;
        },

        SET_SUPPORT_REPLY(state, data) {
            if(state.support_request.data.reply === null ){
                state.support_request.data.reply = []; 
            }
            state.support_request.data.reply.push(data.data);
        },

        SET_SUPPORT_STATUS(state, data) {
            if(!data.data)
            state.support_request.data.request.status = 0;
            else 
            state.support_request.data.request.status = 1;
        },

        DELETE_REQUEST(state, key) {
            state.data.data.splice(key, 1);
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