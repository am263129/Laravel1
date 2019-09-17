import Vue from 'vue';

const module = {
    state: {
        sort: {},
        show_series_details_page: null,
        show_search_page: false,
        get_path_show_page: null,
        show_overview_page: false
    },

    mutations: {

        SET_SORT_BY(state, {trending, genre}) {
            state.sort = {trending, genre}
        },

        SHOW_SERIES_DETAILS_PAGE(state, show) {
            if(show) {
                state.show_series_details_page = true;
            }else {
                state.show_series_details_page = false;
            }
        },

        SHOW_SEARCH_PAGE(state) {
            if(state.show_search_page) {
                document.getElementById("root").classList.remove("xs221");
                state.show_search_page = false;
            }else{
                document.getElementById("root").className = "xs221";
                state.show_search_page = true;
            }
        },

        SET_SHOW_PATH_PATH(state, path) {
            state.get_path_show_page = path;
        },

        SET_OVERVIEW_PAGE(state, bool) {
            state.show_overview_page = bool;
        }

    },

    getter: {}
};
export default module;