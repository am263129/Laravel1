import Vue from 'vue';
import router from '../../packages/Routes';
import {
    stat
} from 'fs';
const module = {
    state: {
        tv_data: [],
        series_data: [],
        movie_data: [],
        videosong_data: [],
        player_spinner: true

        /* TV */

    },
    actions: {

        /**
         * Get movie vidoe,subtitle,suggestion
         *
         * @param {any} param
         * @param {any} id
         */

        LOAD_MOVIE_PLAYER({ commit}, id) {
            commit('SPINER_LOAD')
            axios.post('/api/v1/get/watch/movie', {
                movie_id: id
            }).then(response => {
                if (response.status === 200) {
                    commit('SET_MOVIE', {
                        data: response.data.data
                    });
                    commit('SPINER_CLEAN')
                }
            }, error => {
                // Show Sweetalert if there is problem
                swal({
                    icon: 'error',
                    title: 'There was a problem playing the video, we will fix it soon',
                    dangerMode: true,
                    button: 'Back',
                }).then(() => {
                    window.history.back();
                });
            });
        },

        LOAD_VIDEOSONG_PLAYER({ commit}, id) {
            commit('SPINER_LOAD')
            axios.post('/api/v1/get/watch/videosong', {
                videosong_id: id
            }).then(response => {
                if (response.status === 200) {
                    commit('SET_VIDEOSONG', {
                        data: response.data.data
                    });
                    commit('SPINER_CLEAN')
                }
            }, error => {
                // Show Sweetalert if there is problem
                swal({
                    icon: 'error',
                    title: 'There was a problem playing the video, we will fix it soon',
                    dangerMode: true,
                    button: 'Back',
                }).then(() => {
                    window.history.back();
                });
            });
        },




        /**
         *
         * @param {*}  commit
         * @param {uuid,string,uuid}  Array
         */
        LOAD_SERIES_PLAYER({commit}, {episode_id, series_id}) {
            axios.post('/api/v1/get/watch/series', {
                episode_id: episode_id,
                series_id: series_id
            })
                .then(response => {
                    if (response.data.status === 'success') {
                        commit('SET_SERIES', {
                            data: response.data.data
                        });
                        commit('SPINER_CLEAN')
                    }
                }, error => {
                    swal({
                        icon: 'error',
                        title: 'There was a problem playing the video, we will fix it soon',
                        dangerMode: true,
                        button: 'Back',
                    }).then(() => {
                        window.history.back();
                        videojs.dispose();
                    });
                });
        },


        /**
         *  Live Tv Videos
         *
         * @param commit
         * @param id
         * @constructor
         */
        LOAD_LIVE_TV({commit,dispatch}, id) {
            commit('SPINER_LOAD')
            axios.get('/api/v1/get/watch/tv/' + id)
                .then((res) => {
                    commit('SET_DATA_PLAYER_TV', res.data.data);
                    commit('SPINER_CLEAN')
                }, error => {
                    swal({
                        icon: 'error',
                        title: 'There was a problem playing the video, we will fix it soon',
                        dangerMode: true,
                        button: 'Back',
                    }).then(() => {
                        window.history.back();
                    });
                });
        },


    },
    mutations: {

        /**
         * This mutations to set all video details (video resolation,subtitle,suggestion) in videojs player
         *
         * @param {*} state
         * @param {*} data
         */
        SET_MOVIE(state, data) {
            state.movie_data = data.data;
        },
        
        /**
         * This mutations to set all video details (video resolation,subtitle,suggestion) in videojs player
         *
         * @param {*} state
         * @param {*} data
         */
        SET_VIDEOSONG(state, data) {
            state.videosong_data = data.data;
        },


        /**
         *
         * @param {*} state
         * @param {*} list
         */
        SET_SERIES(state, data) {
            state.series_data = data.data

        },


        SET_DATA_PLAYER_TV(state, data) {
            state.tv_data = data;
        },

        SET_DATA_PLAYER_NEWS(state, data) {
            state.news_data = data;
        },

        CLOSE_REPORT(state) {
            state.show_report = false;
        },

        // Spiner load
        SPINER_LOAD(state) {

            state.player_spinner = true;
        },

        SPINER_CLEAN(state) {
            state.player_spinner = false;
        }
    }
};
export default module;
