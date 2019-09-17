import Vue from 'vue';
import router from '../../packages/Routes';

const module = {
    state: {
        data: [],
        show: [],
        loading: false,
    },
    actions: {

        /**
         * Get all last movies 
         * 
         * @param {any} {commit} 
         */
        GET_SERIES_LIST({ commit }) {
            commit('SPINER_LOAD_SERIES');
            axios.get('/api/v1/get/series').then(response => {
                if (response.status === 200) {
                    const data = response.data.data;
                    commit('SET_SERIES_LIST', data);
                    commit('SPINER_CLEAN_SERIES');
                }
            });
        },

        /**
         * Get sort series by trending or genres and together
         * 
         * @param {*} commit 
         * @param {int,string} array {trending, genre} 
         */
        GET_SERIES_SORT_BY_LIST({ commit }, { trending, genre }) {
            commit('SPINER_LOAD_SERIES');
            axios.post('/api/v1/get/series/sort', {
                trending: trending,
                genre: genre
            }).then((response) => {
                if (response.status === 200) {
                    const data = response.data.data;
                    commit('SET_SERIES_LIST', data);
                    commit('SPINER_CLEAN_SERIES');
                }
            });
        },

        /**
         * Get movie details
         * 
         * @param {*} commit 
         * @param {*} id movie request 
         */
        GET_SERIES_DETAILS({ commit }, id) {
            commit('SPINER_LOAD_SERIES');
            axios.get('/api/v1/get/series/' + id).then((response) => {
                if (response.status === 200) {
                    const data = response.data.data;
                    commit('SET_SERIES_DETAILS', data);
                    commit('SPINER_CLEAN_SERIES');
                }
            }, error => {
                if(error.response.status === 404){
                    router.push({name: '404'});
                }else{
                    router.push('/');
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
        SET_SERIES_LIST(state, data) {
            state.data = data;
        },

        /**
         * 
         * @param {*} state 
         * @param {*} data 
         */
        SET_SERIES_DETAILS(state, data) {
            state.show = data;
        },


        CLEAR_SERIES_SHOW_DATA(state) {
            state.show = [];
        },

        SPINER_LOAD_SERIES(state) {
            state.loading = true;
        },

        SPINER_CLEAN_SERIES(state) {
            state.loading = false;
        }
    },
    getters: {}
};
export default module;