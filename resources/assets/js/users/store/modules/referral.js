import Vue from 'vue';
import router from '../../packages/Routes';
import swal from 'sweetalert';
import {
  stat
} from 'fs';

const alertify = require('alertify.js');

const module = {
  state: {
    items: {},
    device_activity_data: {},
    support_request: {},
    payment_update: {},
    plan: {},
    error: null,
    loading: true,
    button_loading: false,
  },
  actions: {

    /**
     *  Send request to oauth to check if the email and password is correct or return 401 error
     *
     * @param {*} commit
     * @param {*} array Email And Password
     */
    REFER({
      commit
    }, {
      id,
      type,
      referral
    }) {
      // commit('BUTTON_LOAD');
      var data = {
        id: id,
        type: type,
        referral: referral
      };
      axios.post('/api/v1/create/app/referral', data)
        .then(response => {

          alert('We got response', response);
          console.warn(response);


        }, (error) => {
          if (error.response.status === 401) {
            alert('You must login');
            // commit('SET_ERROR', {
            //   'error': true
            // });
            // commit('BUTTON_CLEAR');
          }
        });
    },

  },

  mutations: {

    BUTTON_CLEAR(state) {
      state.button_loading = false;
    },
  },

}
export default module;