import Vue from 'vue';

const module = {
    state: {
        data: [],
        search_data: [],
        loading: false,
        searchLoading: false
    },
    actions: {

        /**
         * Get all channels
         * 
         * @param {*} param0 
         */
        GET_TV_LIST({
            commit
        }) {
            commit('SPINER_LOAD');
            axios.get('/api/v1/get/tv')
                .then((response) => {
                    if (response.status === 200) {
                        commit('SET_TV_LIST', response.data.data);
                        commit('SPINER_CLEAN');
                    }
                });

        },

        
        /**
         * Search  channels
         * 
         * @param {*} param0 
         */
        LOAD_SEARCH_TV_LIST({commit}, query) {
            commit('SEARCH_LOAD');
            axios.post('/api/v1/get/tv/search', {query: query})
                .then((response) => {
                    if (response.status === 200) {
                        commit('SET_SEARCH_TV_LIST', response.data.data);
                        commit('SEARCH_CLEAN');
                    }
                }, () => {
                    commit('SEARCH_CLEAN');

                });

        },
    },
    mutations: {
        SET_TV_LIST(state, data) {
            state.data = data;
        },

        SET_SEARCH_TV_LIST(state, data) {
            state.search_data = data;
        },

        SPINER_LOAD(state) {
            state.loading = true;
        },

        SPINER_CLEAN(state) {
            state.loading = false;
        },

        SEARCH_LOAD(state) {
            state.searchLoading = true;
        },

        SEARCH_CLEAN(state) {
            state.searchLoading = false;
        }
    },
};
export default module;