<template>
  <div>

    <div class="settings">

      <div class="row">

        <div class="spinner-load" v-if="loading">
          <div class="hidden-md-up phone">
            <div id="main">

              <span class="spinner"></span>

            </div>
          </div>

          <div class="hidden-sm-down other">
            <div id="main">

              <span class="spinner"></span>

            </div>
          </div>
        </div>

          <div class="exit-icon" @click="$Helper.home()">
              <exit-button></exit-button>
          </div>

        <!-- EXIT -->

        <div class="col-12 col-sm-8 col-lg-8 offset-sm-2 p-sm-3 mt-5 profile-edit h-100" v-if="Object.keys(request_data).length > 0">
                  <div class="row">
                   <div class="col-8 text-left">
                    <h4>{{request_data.data.request.subject}} </h4>
                   </div>

                  
                      <div class="col-4 text-right">
                        <span class="support-request re-open " v-if="request_data.data.request.status === 1">{{$t('setting.open')}}</span>
                        <span class="support-request re-close"  v-else>{{$t('setting.close')}}</span>
                      </div>

                </div>

          <hr>

          <div class="settings__message-box" v-if="request_data.data">
            
            <div  class="col-12">
            <div class="col-12 mt-5 user">
              <div class="float-left profile-image text-center">
                <img src="/themes/default/img/user.svg" alt="profile" width="50px">
                <p>You</p>
              </div>
              <div class="col-10 col-md-7">
                <div class="user-message">
                <p class="mt-1">{{request_data.data.request.details}}</p>
                </div>
                
                <div class="date">
                <p class="mt-1">{{request_data.data.request.created_at}}</p>
                  </div>
              </div>
              
            </div>
            </div>

            <div class="col-12 mt-5" v-for="(item, index) in request_data.data.reply" :key="index" >

              <div class="col-12 mt-5 user" v-if="item.from === 'client'">
                <div class="float-left profile-image text-center">
                  <img src="/themes/default/img/user.svg" alt="profile" width="50px">
                  <p>You</p>
                </div>
                <div class="col-10 col-md-7">
                  <div class="user-message">
                     <p class="mt-1">{{item.reply}}</p>
                  </div>
                   <div class="date">
                     <p>{{item.created_at}}</p>
                  </div>
                </div>
              </div>

              <div class="support col-12 mt-5"  v-else>
                <div class="float-left profile-image text-center">
                  <img src="/themes/default/img/support.png" alt="profile" width="50px">
                  <p class="mt-1">{{item.from}}</p>
                </div>
                <div class="col-10 col-md-7">
                   <div class="support-message">
                     <p class="mt-1">{{item.reply}}</p>
                  </div>
                   <div class="date">
                     <p>{{item.created_at}}</p>
                  </div>
                </div>
              </div>
            </div>


          </div>
          


          <div class="col-12 mt-5 reply-box" v-if="request_data.data.request.status !== 0">
            <hr>


            <div class="form-group">
              <textarea name="details" spellcheck="false" v-validate="'required|max:2000'" cols="50" rows="10" :class="{'input': true, 'text-danger': errors.has('details') }"
                class="form-control" v-model="reply" :placeholder="$t('setting.support_desc')"></textarea>
              <span v-show="errors.has('details')" class="text-danger">{{ errors.first('details') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-warning" @click="SUBMIT_REPLY" v-if="!button_loading">{{$t('setting.reply')}}</button>
                <button class="btn btn-warning" @click="SUBMIT_REPLY" v-if="button_loading" disabled>LOADING</button>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</template>

<script>
import exitButton from "../utils/exit-button.vue";
import { mapState } from "vuex";
export default {
  data() {
    return {
      subject: "",
      details: "",
      reply: ""
    };
  },

  components: {
    "exit-button": exitButton
  },

  computed: mapState({
    loading: state => state.auth.loading,
    button_loading: state => state.auth.button_loading,
    request_data: state => state.auth.support_request
  }),

  mounted() {
    this.$store.dispatch("GET_SUPPORT_REQUEST", { id: this.$route.params.id });
  },

  methods: {
    SUBMIT_REPLY() {
      this.$validator.validateAll().then(result => {
        if (result) {
          this.$store.dispatch("SUPPORT_REPLY", {
            reply: this.reply,
            id: this.$route.params.id
          });
        }
      });
    },

    SEND_REPLY() {
      alert("enter");
    }
  }
};
</script>