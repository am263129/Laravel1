import Vue from 'vue';
import router from '../../packages/Routes';

const module = {
    state: {
        data: [],
        show: [],
        loading: false
    },
    actions: {
        /**
         * Get all last videosongs
         *
         * @param {any} {commit}
         */
        GET_VIDEOSONGS_LIST({ commit }) {
            commit('SPINER_LOAD_VIDEOSONGS');
            axios.get('/api/v1/get/videosongs').then(response => {
                console.log(response);
                if (response.status === 200) {
                    const data = response.data.data;
                    commit('SET_VIDEOSONGS_LIST', data);
                    commit('SPINER_CLEAN_VIDEOSONGS');
                }
            });
        },

        /**
         * Get videosongs sort by trending or genre or twice
         *
         * @param {*} commit
         * @param {int,string} array {trending, genre}
         */
        GET_VIDEOSONGS_SORT_BY_LIST({ commit }, { trending, genre }) {
            commit('SPINER_LOAD_VIDEOSONGS');
            axios
                .post('/api/v1/get/videosongs/sort', {
                    trending: trending,
                    genre: genre
                })
                .then(response => {
                    if (response.status === 200) {
                        const data = response.data.data;
                        commit('SET_VIDEOSONGS_LIST', data);
                        commit('SPINER_CLEAN_VIDEOSONGS');
                    }
                });
        },

        /**
         * Get videosong details
         *
         * @param {*} commit
         * @param {*} id videosong request
         */
        GET_VIDEOSONG_DETAILS({ commit }, id) {
            axios.get('/api/v1/get/videosong/' + id).then(
                response => {
                    if (response.status === 200) {
                        const data = response.data.data;
                        commit('SET_VIDEOSONG_DETAILS', data);
                    }
                },
                error => {
                    if (error.response.status === 404) {
                        router.push({ name: '404' });
                    } else {
                        router.push('/');
                    }
                }
            );
        }
    },
    mutations: {
        SET_VIDEOSONGS_LIST(state, data) {
            state.data = data;
        },

        SET_VIDEOSONG_DETAILS(state, data) {
            state.show = data;
        },

        CLEAR_VIDEOSONG_SHOW_DATA(state) {
            state.show = [];
        },

        // Spiner load
        SPINER_LOAD_VIDEOSONGS(state) {
            state.loading = true;
        },

        SPINER_CLEAN_VIDEOSONGS(state) {
            state.loading = false;
        }
    },
    getters: {}
};
export default module;
