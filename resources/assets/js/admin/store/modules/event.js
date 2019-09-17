import Vue from 'vue';
import router from '../../routes';

const alertify = require('alertify.js');

const module = {
    state: {
        data: [],
        upload_data: [],
        data_count: 0
    },
    mutations: {
        // Show Progress



        // API UPLOAD
        SET_UPLOAD_PROGRESS(state, data) {
            state.data.push(data);
        },


        // API UPLOAD
        COUNT_UPLOAD_PROGRESS(state) {
            state.data_count = state.data.length;
        },

        // UPDATE UPLOAD
        UPDATE_UPLOAD_PROGRESS_DATA(state, data) {
            state.data[data.key] = data.data;
        },


        // SET UPLOAD DATA
        SET_PROGRESS_DATA(state, data) {
            state.upload_data.push(data);
        },

        // SET UPLOAD DATA
        UPDATE_PROGRESS_DATA(state, data) {




            if(data.object == 'api') {
                if(data.parameter == 'progress') {
                    state.upload_data[data.key].api.progress = data.value;
                }else if(data.parameter == 'success_message') {
                    state.upload_data[data.key].api.success_message = data.value;

                }else if(data.parameter == 'error_message') {
                    state.upload_data[data.key].api.error_message = data.value;

                }else{
                    state.upload_data[data.key].api.show = data.value;
                }
            }else if (data.object == 'upload') {
                if(data.parameter == 'progress') {
                    let key =  Object.keys(state.upload_data).find(key => state.upload_data[key].id === data.id);
                    if(key !== undefined) {
                        state.upload_data[key].upload.progress = data.value;
                    }

                }else if(data.parameter == 'success_message') {
                    state.upload_data[data.key].upload.success_message = data.value;

                }else if(data.parameter == 'error_message') {
                    state.upload_data[data.key].upload.error_message = data.value;

                }else if(data.parameter == 'message') {
                    state.upload_data[data.key].upload.message = data.value;

                }else{
                    state.upload_data[data.key].upload.show = data.value;
                }
            }

        },

    },
    getters: {}
};
export default module;