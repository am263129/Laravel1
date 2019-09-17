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
         * Get all admin users
         * 
         * @param {*} commit object 
         * @param id int id 
         */
        GET_ALL_ADMINS({commit}) {
            commit('SPINER_LOAD');
            axios.get('/api/admin/get/settings/admins/users').then(response => {
                if(response.status === 200){                    
                    commit('SET_USERS', response.data.data);                    
               }
            }).finally(()=>{
                commit('SPINER_CLEAN');
            });
        },



        /**
         * Delete movie
         * 
         * @param {*} id  uuid
         * @param {*} key int
         */
        DELETE_ADMIN({commit}, {id, key}) {

            commit('BUTTON_LOAD', id);
            axios.delete('/api/admin/setting/delete/admin/user/' + id).then(response => {
              if (response.status === 200) {
                alertify.logPosition('top right');
                alertify.success(response.data.message);  
                commit('DELETE_ADMIN', key);                
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
        GET_ADMIN_DETAILS({commit}, id) {
            commit('SPINER_LOAD');
            axios.get('/api/admin/get/settings/user/details/'+ id).then(response => {
                if(response.status === 200){                    
                    commit('SET_USERS', response.data.data);                    
               }
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
        UPDATE_ADMIN_DETAILS({commit}, {id, name, email, password, permission}) {
            commit('BUTTON_LOAD');
            axios.post('/api/admin/update/settings/user', {
                id: id,
                name: name,
                email: email,
                password: password,
                permission: permission
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
         * Create user 
         * 
         * @param {*} commit object 
         * @param {int,string,email,string,password} object
         */
        CREATE_ADMIN_USER({commit}, {name, email, password, password_confirmation, permission}) {
            commit('BUTTON_LOAD');
            axios.post('/api/admin/new/settings/admin/user', {
                name: name,
                email: email,
                password: password,
                password_confirmation: password_confirmation,
                permission:permission
            }).then(response => {
                if (response.status === 200) {
                    alertify.logPosition("top right");
                    alertify.success(response.data.message);
                    router.go(-1);
                }
              }, error => {
                alertify.logPosition("top right");
                alertify.error(error.response.data.message);
                router.go(-1);
              }).finally(()=>{
                commit('BUTTON_CLEAN');
              });
          }
    },
    mutations: {

        SET_USERS(state, data) {
            state.data = data;
        },
       
        SET_SEARCH_USERS(state, data) {
            state.data_search = data;
        },

        SET_INVOICE(state, data) {
            state.invoices = data;
        },

        DELETE_ADMIN(state, key) {
            state.data.admins.splice(key, 1);
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

    },
    getters: {}
};
export default module;