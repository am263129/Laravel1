<template>
<div>
  <p
  @click="copyTextToClipboard"
  style=" 
    color: white;

    margin: 10;
    zIndex: 100,
    font-size: .6em;
    border: none;
    border-radius: 0.1em;">Share On: </p>

  <social-sharing v-bind:url="url"
                      title="Watch this interesting video"
                      inline-template>
  <div>
      <network network="email">
          <i class="fa fa-envelope fa-lg"></i> 
      </network>
      <network network="facebook">
        <i class="fa fa-facebook fa-lg"></i>
      </network>
      <network network="googleplus">
        <i class="fa fa-google-plus fa-lg"></i>
      </network>
      <network network="line">
        <i class="fa fa-line fa-lg"></i>
      </network>
      <network network="linkedin">
        <i class="fa fa-linkedin fa-lg"></i>
      </network>
      <network network="odnoklassniki">
        <i class="fa fa-odnoklassniki fa-lg"></i>
      </network>
      <network network="pinterest">
        <i class="fa fa-pinterest fa-lg"></i> 
      </network>
      <network network="reddit">
        <i class="fa fa-reddit fa-lg"></i> 
      </network>
      <network network="skype">
        <i class="fa fa-skype fa-lg"></i>
      </network>
      <network network="sms">
        <i class="fa fa-commenting-o fa-lg"></i> 
      </network>
      <network network="telegram">
        <i class="fa fa-telegram fa-lg"></i> 
      </network>
      <network network="twitter">
        <i class="fa fa-twitter fa-lg"></i> 
      </network>
      <network network="vk">
        <i class="fa fa-vk fa-lg"></i> 
      </network>
      <network network="weibo">
        <i class="fa fa-weibo fa-lg"></i> 
      </network> 
      <network network="whatsapp">
        <i class="fa fa-whatsapp fa-lg"></i>
      </network>
  </div>
</social-sharing>
</div>

</template>
<script>
const alertify = require("alertify.js");
import swal from "sweetalert";
import Vue from 'vue';
  import {
        mapState
    } from 'vuex';

export default {
  name: 'playerShare',
  props: {
   id: {
    required: true
  },
   type: {
     required: true
   }
   },
   data: function () {
     return {
       itemId: this.id,
       url: window.location +'?referral='+ Vue.auth.getUserInfo('user_id') + '&type=' +this.type,
       urlVisible: false,

     }
   },

   methods: {
     printData() {
       return;
       console.log(this.id, this.props);
       console.log({id: this.id, type: this.type});
       axios.post('/api/v1/create/app/referral', { id: this.id, type: this.type})
        .then(response => {

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
      copyTextToClipboard(text) {
        var textArea = document.createElement("textarea");
        textArea.value = this.url;
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        swal('Copied to clipboard');

        try {
          var successful = document.execCommand('copy');
          var msg = successful ? 'successful' : 'unsuccessful';
          console.log('Fallback: Copying text command was ' + msg);
        } catch (err) {
          console.error('Fallback: Oops, unable to copy', err);
        }

        document.body.removeChild(textArea);
    }
   }
}
</script>
