import Vue from 'vue';
import swal from 'sweetalert';
import router from '../../packages/Routes';
const alertify = require('alertify.js');


const module = {
    state: {
        data: [],
        collections: [],
        loading: false,
    },
    actions: {

        /**
         * GET All collection name and id of the user
         * 
         * @param {any} {commit} 
         */
        
        GET_ALL_COLLECTION({commit}) {
            commit('COLLECTION_SPINER_LOAD');
            axios.get('/api/v1/get/collection').then((response) => {
                if (response.status === 200) {
                    commit('SET_ALL_COLLECTIONS', response.data.data);
                    commit('COLLECTION_SPINER_CLEAN');
                }
            }, (error) => {
                //  error
                alertify.logPosition('bottom center');
                alertify.error(error.response.data.message);
                commit('COLLECTION_SPINER_CLEAN');
            });
        },

        /**
         * GET All movie and series in collection
         * 
         * @param {any}
         * @param id the id of collection
         */

        GET_COLLECTION({ commit }, id) {
            commit('COLLECTION_SPINER_LOAD');
            axios.get('/api/v1/get/collection/' + id ).then((response) => {
                if (response.status === 200) {
                    commit('SET_COLLECTION', response.data.data);
                    commit('COLLECTION_SPINER_CLEAN');
                }
            }, error => {
                if(error.response.status === 400){
                    router.push({name: '404'});
                }else{
                    router.push('/');
                }
            });
        },


        /**
         * Add new collection or add movie and series in already collection
         * 
         * @param {any} { commit } 
         * @param {integer, string, integer, string} { id, new_collection, already_collection, type} 
         */
        
        ADD_NEW_TO_COLLECTION({ commit }, { id, new_collection, already_collection, type}) {
            commit('COLLECTION_SPINER_LOAD');
            axios.post('/api/v1/create/item/collection', { id, new_collection, already_collection, type }).then((response) => {
                if (response.status === 200) {
                    alertify.logPosition('bottom center');
                    alertify.success(response.data.message);
                    commit('COLLECTION_SPINER_CLEAN');
                    if(response.data.data !== null){
                        commit('ADD_NEW_COLLECTION', response.data.data);
                    }
                }
            }, (error) => {
                //  error
                alertify.logPosition('bottom center');
                alertify.error(error.response.data.message);
            });
        },


        /**
         * Update collection name
         * 
         * @param {any} { commit } 
         * @param {int, string} { id,name} 
         */

        EDIT_COLLECTION({ commit }, { id, name}) {
            axios.post('/api/v1/update/collection', { id, name }).then((response) => {
                if (response.status === 200) {
                    alertify.logPosition('bottom center');
                    alertify.success(response.data.message);
                }
            }, (error) => {
                //  error
                alertify.logPosition('bottom center');
                alertify.error(error.response.data.message);
            });
        },

          /**
            * Delete collection
            * 
            * @param {any} { commit } 
            * @param {int, string} { id, type } 
            */
        DELETE_COLLECTION({ commit }, id) {
            axios.post('/api/v1/delete/collection', {id: id}).then((response) => {
                if (response.status === 200) {
                    alertify.logPosition('bottom center');
                    alertify.success(response.data.message);
                    router.push({name: 'discover'});
                    commit('DELETE_COLLECTIN_FROM_OBJECT', id);
                }
            }, (error) => {
                if(error.response.status === 404){
                    router.push({name: '404'});
                }else{
                    alertify.logPosition('bottom center');
                    alertify.error(error.response.data.message);              
                }
            });
        },
        

            /**
             * Delete Movies and Series form collection
             * 
             * @param {any} { commit } 
             * @param {integer, string} { id, type } 
             */
        DELETE_FROM_COLLECTION({ commit }, { id, type }) {
            commit('COLLECTION_SPINER_LOAD');
            axios.post('/api/v1/delete/item/collection', { id, type }).then((response) => {
                if (response.status === 200) {
                    alertify.logPosition('bottom center');
                    alertify.success(response.data.message);
                    commit('COLLECTION_SPINER_CLEAN');
                }
            }, (error) => {
                if(error.response.status === 404){
                    router.push({name: '404'});
                }else{
                    alertify.logPosition('bottom center');
                    alertify.error(error.response.data.message);              
                }
            });
        },

         /**
           * Add like to movie or series and if already marked will delete it
           * 
           * @param {any} { commit } 
           * @param {integer, string} { id, type} 
           */

        ADD_LIKE({ commit }, { id, type }) {
            axios.post('/api/v1/create/like', { id, type }).then((response) => {
                if (response.status === 200) {

                    // No need to notice
                    // You can add any notice using alertify.js

                }
            }, (error) => {
                if(error.response.status === 404){
                    router.push({name: '404'});
                }else{
                    router.push('/');
                }
            });
        },

    },
    mutations: {

        SET_ALL_COLLECTIONS(state, data) {
            state.collections = data;
        },
        
        SET_COLLECTION(state, data){
            state.data = data;
        },

        DELETE_COLLECTIN_FROM_OBJECT(state, data) {
            state.collections.find(function(value, index) {
                if(value.id == data) {
                    state.collections.splice(index, 1);
                }
            });


        },
        
        ADD_NEW_COLLECTION(state, data) {
            state.collections.push(data);
        }, 

        COLLECTION_SPINER_LOAD(state) {
            state.collection_loading = true;
        },

        COLLECTION_SPINER_CLEAN(state) {
            state.collection_loading = false;
        }
    },
    getters: {}
};
export default module;