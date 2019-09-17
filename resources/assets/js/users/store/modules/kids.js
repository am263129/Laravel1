import Vue from 'vue';

const module = {
    state: {
        data: [],
        loading: false,
    },
    actions: {

        /**
         * Get all last movies 
         * 
         * @param {any} {commit} 
         */
        GET_KIDS_LIST({ commit }) {
            commit('SPINER_LOAD_KIDS');
            axios.get('/api/v1/get/kids').then(response => {
                if (response.status === 200) {
                    const data = response.data.data;
                    commit('SET_KIDS_LIST', data);
                    commit('SPINER_CLEAN_KIDS');
                }
            });
        },


    },
    mutations: {

        /**
         * 
         * @param {*} state 
         * @param {*} data 
         */
        SET_KIDS_LIST(state, data) {
            state.data = data;
        },

        SPINER_LOAD_KIDS(state) {
            state.loading = true;
        },

        SPINER_CLEAN_KIDS(state) {
            state.loading = false;
        }
    },
    getters: {}
};
export default module;