import Vue from 'vue';

const module = {
    state: {
        data: [],
        footer: [],
        plan: [],
        actor_details: {},
        home_loading: false,
    },
    actions: {


        // Get all movies form api /api/v1/movies
        GET_GHOST_HOME_LIST({ commit }) {
            commit('HOME_SPINER_LOAD');
            axios.get('/api/v1/ghost/get/discover').then((response) => {
                if (response.status === 200) {
                    const list = response.data.data;
                    commit('SET_HOME_LIST', {list});                    
                }
            }).finally(()=>{
                commit('HOME_SPINER_CLEAN');
            });
        },


       // Get all movies form api /api/v1/movies
       GET_HOME_PLAN({ commit }) {
        axios.get('/api/v1/get/app/plan').then((response) => {
            if (response.status === 200) {
                const data = response.data.data;
                commit('SET_HOME_PLAN', data);
            }
        });
    },

    },
    mutations: {

        SET_HOME_LIST(state, list) {
            state.data = list.list;
        },

        SET_HOME_FOOTER_DETAILS(state, data){
            state.footer = data;
        },

        SET_HOME_PLAN(state, data){
            state.plan = data;
        },

        // Spiner load
        HOME_SPINER_LOAD(state) {
            state.home_loading = true;
        },

        HOME_SPINER_CLEAN(state) {
            state.home_loading = false;
        }
    },
    getters: {}
};
export default module;