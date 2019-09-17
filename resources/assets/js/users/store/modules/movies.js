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
        GET_MOVIES_LIST({
            commit
        }) {
            commit('SPINER_LOAD_MOVIES');
            axios.get('/api/v1/get/movies').then(response => {
                if (response.status === 200) {
                    const data = response.data.data;
                    commit('SET_MOVIES_LIST', data);
                    commit('SPINER_CLEAN_MOVIES');
                }
            });
        },

        /**
         * Get movies sort by trending or genre or twice
         * 
         * @param {*} commit 
         * @param {int,string} array {trending, genre} 
         */
        GET_MOVIES_SORT_BY_LIST({
            commit
        }, {
            trending,
            genre
        }) {
            commit('SPINER_LOAD_MOVIES');
            axios.post('/api/v1/get/movies/sort', {
                trending: trending,
                genre: genre
            }).then((response) => {
                if (response.status === 200) {
                    const data = response.data.data;
                    commit('SET_MOVIES_LIST', data);
                    commit('SPINER_CLEAN_MOVIES');
                }
            });
        },


        /**
         * Get movie details
         * 
         * @param {*} commit 
         * @param {*} id movie request 
         */
        GET_MOVIE_DETAILS({
            commit
        }, id) {
            axios.get('/api/v1/get/movie/' + id).then((response) => {
                if (response.status === 200) {
                    const data = response.data.data;
                    commit('SET_MOVIE_DETAILS', data);
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
        SET_MOVIES_LIST(state, data) {
            state.data = data;
            
        },

        SET_MOVIE_DETAILS(state, data) {
            state.show = data;
        },

        CLEAR_MOVIE_SHOW_DATA(state) {
            state.show = [];
        },

        // Spiner load
        SPINER_LOAD_MOVIES(state) {
            state.loading = true;
        },

        SPINER_CLEAN_MOVIES(state) {
            state.loading = false;
        }
    },
    getters: {}
};
export default module;