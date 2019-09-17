import Vue from 'vue';
import router from '../../routes';
const alertify = require('alertify.js');

const module = {
    state: {
        data: [],
        invoices: [],
        data_search: {},
        button_loading: false,
        spinner_loading: false,
        invoice_spinner_loading: false,

    },
    actions: {

        /**
         * Get all users
         *
         * @param {*} commit object
         * @param id int id
         */
        GET_ALL_USERS({commit}) {
            commit('SPINER_LOAD');
            axios.get('/api/admin/get/users').then(response => {
                if(response.status === 200){
                    commit('SET_USERS', response.data.data);                    
               }
            }).finally(()=>{
                commit('SPINER_CLEAN');
                
            });
        },

        /**
         * Get all users
         *
         * @param {*} commit object
         * @param id int id
         */
        GET_INACTIVITY_USERS({commit}) {
            commit('SPINER_LOAD');
            axios.get('/api/admin/get/users/inactivity').then(response => {
                if(response.status === 200){
                    commit('SET_USERS', response.data.data);              
               }
            }).finally(()=>{
                commit('SPINER_CLEAN');                
            });
        },

     /**
         * Get activity users
         *
         * @param {*} commit object
         * @param id int id
         */
        GET_ACTIVITY_USERS({commit}) {
            commit('SPINER_LOAD');
            axios.get('/api/admin/get/users/activity').then(response => {
                if(response.status === 200){
                    commit('SET_USERS', response.data.data);                    
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
        GET_ALL_USERS_BY_PAGINATION({commit}, path_url) {
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
         * Search users
         *
         * @param {string} string query
         */

        GET_USERS_SEARCH({commit}, query) {
            commit('SPINER_LOAD');
            axios.post('/api/admin/get/users/search', {query: query} ).then(response => {
                if(response.status === 200){
                    commit('SET_SEARCH_USERS', response.data.data);                    
               }
            }).finally(()=>{
                commit('SPINER_CLEAN');                
            });
        },


        /**
         * Get Billing Details
         *
         * @param {*} id  uuid
         * @param {*} key int
         */
        GET_USERS_BILLING({commit}, id) {
            commit('INVOICE_SPINER_LOAD');
            axios.post('/api/admin/get/users/invoice', {id:id}).then(response => {
              if (response.status === 200) {
                commit('SET_INVOICE', response.data.data);                
              }
            },error => {
                alertify.logPosition('top right');
                alertify.error(error.response.data.message);
            }).finally(()=>{
                commit('INVOICE_SPINER_CLEAN');                
            });
        },


        /**
         * Delete user
         *
         * @param {*} id  uuid
         * @param {*} key int
         */
        DELETE_USER({commit}, {id, key}) {
            commit('BUTTON_LOAD', id);
            axios.delete('/api/admin/delete/users/' + id).then(response => {
              if (response.status === 200) {
                alertify.logPosition('top right');
                alertify.success(response.data.message);
                commit('DELETE_USER', key);                
              }
            },error => {
                alertify.logPosition('top right');
                alertify.error(error.response.data.message);                
            }).finally(()=>{
                commit('BUTTON_CLEAN');
            });
        },


        /**
         * Get user details
         *
         * @param {*} commit object
         * @param id int id
         */
        GET_USER_DETAILS({commit}, id) {
            commit('SPINER_LOAD');
            axios.get('/api/admin/get/user/details/'+ id).then(response => {
                if(response.status === 200){
                    commit('SET_USERS', response.data.data);                    
               }
            }, error => {
                alertify.logPosition("top right");
                alertify.error(error.response.data.message);
            }).finally(()=>{
                commit('SPINER_CLEAN');
            });
        },

        /**
         * Update user
         *
         * @param {*} commit object
         * @param {int,string,email,string,password} object
         */
        UPDATE_USER_DETAILS({commit}, {id, name, email, phone, stripe_id, password, subscribe}) {
            commit('BUTTON_LOAD');
            axios.post('/api/admin/update/user', {
                id: id,
                name: name,
                email: email,
                phone: phone,
                stripe_id: stripe_id,
                password: password,
                subscribe: subscribe

              }).then(response => {
                if (response.status === 200) {
                    alertify.logPosition("top right");
                    alertify.success(response.data.message);                    
                }
              }, error => {
                alertify.logPosition("top right");
                alertify.error(error.response.data.message);                
            }).finally(()=>{
                commit('BUTTON_CLEAN');
            });
          },

        /**
         * Update user
         *
         * @param {*} commit object
         * @param {int,string,email,string,password} object
         */
        CREATE_NEW_USER({commit}, {name, email, phone, password, subscribe}) {
            commit('BUTTON_LOAD');
            axios.post('/api/admin/create/user', {
                name: name,
                email: email,
                phone: phone,
                password: password,
                subscribe: subscribe,
            }).then(response => {
                if (response.status === 200) {
                    alertify.logPosition("top right");
                    alertify.success(response.data.message);                    
                    router.go('1');
                }
              }, error => {
                alertify.logPosition("top right");
                alertify.error(error.response.data.message);                
            }).finally(()=>{
                commit('BUTTON_CLEAN');
            });
          },
    },
    mutations: {

        SET_USERS(state, data) {
            state.data = data;
        },

        SET_SEARCH_USERS(state, data) {
            state.data_search = data;
        },
        CLEAN_SEARCH_USERS(state) {
            state.data_search = {};
        },
        SET_INVOICE(state, data) {
            state.invoices = data;
        },

        DELETE_USER(state, key) {
            state.data.users.data.splice(key, 1);
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
        },

        INVOICE_SPINER_LOAD(state) {
            state.invoice_spinner_loading = true;
        },

        INVOICE_SPINER_CLEAN(state) {
            state.invoice_spinner_loading = false;
        }
    },
    getters: {}
};
export default module;
