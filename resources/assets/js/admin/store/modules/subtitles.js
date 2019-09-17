import Vue from 'vue';
import router from '../../routes';
const alertify = require('alertify.js');

const module = {
    state: {
        data: [],
        data_search: {},
        button_loading: false,
        spinner_loading: false,
    },
    actions: {

        /**
         * Get movie subtitle
         * 
         * @param {*} commit object 
         * @param id int id 
         */
        GET_MOVIE_SUBTITLES({commit}, id) {
            commit('SUBTITLE_SPINER_LOAD');
            axios.get('/api/admin/get/subtitles/movie/'+ id).then(response => {
                if(response.status === 200){                    
                    commit('SET_SUBTITLES', response.data.data);                    
               }
            }).finally(()=>{
                commit('SUBTITLE_SPINER_CLEAN');
            });
        },

        /**
         * Get episode subtitle
         * 
         * @param {*} commit object 
         * @param id int id 
         */
        GET_EPISODE_SUBTITLES({commit}, id) {
            commit('SUBTITLE_SPINER_LOAD');
            axios.get('/api/admin/get/subtitles/episode/'+ id).then(response => {
                if(response.status === 200){                    
                    commit('SET_SUBTITLES', response.data.data);                    
               }
            }).finally(()=>{
                commit('SUBTITLE_SPINER_CLEAN');
            });
        },


        /**
         * Delete movie
         * 
         * @param {*} id  uuid
         * @param {*} key int
         */
        DELETE_SUBTITLE({commit}, {id, key}) {
            commit('BUTTON_LOAD', id);
            axios.delete('/api/admin/delete/subtitle/' + id).then(response => {
              if (response.status === 200) {
                alertify.logPosition('top right');
                alertify.success('Successful Delete');  
                commit('DELETE_SUBTITLE', key);                
              }
            },error => {
                alertify.logPosition('top right');
                alertify.error(error.response.data.message);                
            }).finally(()=>{
                commit('BUTTON_CLEAN');
            });
        },

       
    },
    mutations: {

        SET_SUBTITLES(state, data) {
            state.data = data;
        },

        DELETE_SUBTITLE(state, key) {
            state.data.subtitles.splice(key, 1);
        },

        BUTTON_LOAD(state, data) {
            state.button_loading = data;
        },

        BUTTON_CLEAN(state) {
            state.button_loading = false;
        },

        SUBTITLE_SPINER_LOAD(state) {
            state.spinner_loading = true;
        },

        SUBTITLE_SPINER_CLEAN(state) {
            state.spinner_loading = false;
        }
    },
    getters: {}
};
export default module;