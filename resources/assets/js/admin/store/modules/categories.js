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
        GET_ALL_CATEGORIES({ commit }, kind) {
            commit('SPINER_LOAD');
            axios.get('/api/admin/get/categories').then(response => {
                if (response.status === 200) {
                    commit('SET_CATEGORIES', response.data.data);                    
                }
            }).finally(()=>{
                commit('SPINER_CLEAN');
            });
        },

        /**
         * Get all channels
         *
         * @param {*} commit object
         * @param id int id
         */
        GET_CATEGORIES_BY_SORT({ commit }, kind) {
            commit('SPINER_LOAD');
            axios.get('/api/admin/get/categories/sort/' + kind).then(response => {
                if (response.status === 200) {
                    commit('SET_CATEGORIES', response.data.data);               
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
        DELETE_CATEGORY({ commit }, { id, key }) {
            commit('BUTTON_LOAD', id);
            axios.delete('/api/admin/delete/category/' + id).then(response => {
                if (response.status === 200) {
                    alertify.logPosition('top right');
                    alertify.success('Successful Delete');
                    commit('DELETE_CATEGORY', key);                    
                }
            }, error => {
                alertify.logPosition('top right');
                alertify.error(error.response.data.message);                
            }).finally(()=>{
                commit('BUTTON_CLEAN');
            });
        },

        /**
         * Start and Stop Streaming Channels
         *
         * @param {*} id  uuid
         * @param {*} key int
         */
        ACTIVE_CATEGORY({ commit }, { id, type, key }) {
            commit('BUTTON_LOAD', id);
            axios.post('/api/admin/update/active/category', { id: id, type: type }).then(response => {
                if (response.status === 200) {

                    alertify.logPosition('top right');
                    alertify.success(response.data.message);
                    if (type == 'active') {
                        commit('ACTIVE_STATUS', {
                            'key': key,
                            'type': 'active',
                            'status': response.data.active
                        });
                    } else {
                        commit('ACTIVE_STATUS', {
                            'key': key,
                            'type': 'subscription',
                            'status': response.data.subscription
                        });

                    }                   
                }
            }, error => {
                alertify.logPosition('top right');
                alertify.error(error.response.data.message);                
            }).finally(()=>{
                commit('BUTTON_CLEAN');
            });
        },

    },
    mutations: {

        SET_CATEGORIES(state, data) {
            state.data = data;
        },

        DELETE_CATEGORY(state, key) {
            state.data.categories.splice(key, 1);
        },

        ACTIVE_STATUS(state, data) {
            if (data.type === 'active') {
                state.data.categories[data.key].active = data.status;
            } else {
                state.data.categories[data.key].show_in_sub = data.status;
            }
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
