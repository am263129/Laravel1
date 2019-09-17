<template>
  <div>
    <transition name="fade">
      <div class="settings">
        <div
          class="modal fade"
          v-show="show_modal"
          v-if="show_modal"
          tabindex="-1"
          role="dialog"
          aria-labelledby="checkModalLabel"
          id="checkModal"
          aria-hidden="true"
          style="z-index:100000000;"
        >
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5
                  class="modal-title"
                  id="checkModalLabel"
                >{{$t('setting.security_message_header')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label class="col-12 control-label">
                    <p>{{$t('setting.security_message_body')}}</p>
                    {{$t('setting.password')}}
                  </label>
                  <div class="col-12">
                    <input
                      class="form-control"
                      type="password"
                      name="current"
                      v-validate="'min:8|required'"
                      :class="{'input': true, 'input-danger': errors.has('current') }"
                      v-model="current_password"
                      :placeholder="$t('setting.password')"
                    />
                    <span
                      v-show="errors.has('password')"
                      class="help text-danger"
                    >{{errors.first('password')}}</span>
                  </div>
                </div>
                <div class="form-group" v-if="error !== null">
                  <div class="col-12">
                    <span class="text-danger">{{error}}</span>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button
                  type="button"
                  class="btn btn-warning close-btn"
                  data-dismiss="modal"
                >{{$t('setting.cancel')}}</button>
                <button
                  type="button"
                  class="btn btn-warning"
                  @click="UPDATE_DETAILS"
                >{{$t('setting.submit')}}</button>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="exit-icon" @click="$Helper.home()">
            <exit-button></exit-button>
          </div>
          <!-- EXIT -->

          <div class="col-12 col-sm-8 offset-sm-2 p-sm-3 mt-5 h-100">
            <div class="title">
              <p>{{$t('setting.referral')}}</p>
            </div>
            <b>
              Referral Url:
              <input
                @click="copyTextToClipboard()"
                style="backgroundColor: rgba(0,0,0,0.4); border:none; color: white"
                width="100%"
                v-model="referral_url"
                disabled="true"
              />
              <button class="btn btn-warning" @click="copyTextToClipboard">Copy</button>
            </b>
            <hr />
            <div class="col-sm-10 col-lg-8 offset-sm-2">
              <div class="text-left profile-details">
                <table class="table table-condensed">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Referral</th>
                      <th>Points</th>
                    </tr>
                  </thead>
                  <tbody>
                    <template v-for="(item, index) in referrals">
                      <tr :key="index">
                        <td>{{item.name}}</td>
                        <td>{{item.email}}</td>

                      </tr>
                    </template>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import exitButton from "../utils/exit-button.vue";
import * as _ from "lodash";
import md5 from "md5";

export default {
  data() {
    return {
      name: null,
      email: null,
      current_password: null,
      success: null,
      error: null,
      show_modal: true,
      media: [],
      referral_url: '',
      referrals: []
    };
  },

  components: {
    "exit-button": exitButton
  },

  created() {
    this.referral_url = window.location.origin+"/app/signup?ref="+this.hash(this.$auth.getUserInfo('email'))
    const { data } = axios.get("/api/v1/get/app/referral").then(res => {
      console.warn(res.data);
      this.referrals = res.data;
    });
  },

  beforeRouteUpdate(to, from, next) {
    this.name = to.params.name;
    next();
  },

  methods: {
    UPDATE_DETAILS() {
      this.$validator.validateAll().then(result => {
        if (result) {
          axios
            .post("/api/v1/update/profile/details", {
              current_password: this.current_password,
              name: this.name,
              email: this.email
            })
            .then(
              response => {
                if (response.status === 200) {
                  localStorage.setItem("name", this.name);
                  localStorage.setItem("email", this.email);
                  this.success = response.data.message;
                  this.error = null;

                  // close modal
                  document.getElementsByClassName("close")[0].click();
                }
              },
              error => {
                this.error = error.response.data.message;
                this.success = null;
              }
            );
        }
      });
    },
    hash(s) {
      return md5(s);
    },
    copyTextToClipboard() {
      var textArea = document.createElement("textarea");
      textArea.value = this.referral_url;
      document.body.appendChild(textArea);
      textArea.focus();
      textArea.select();
      swal("Copied to clipboard");

      try {
        var successful = document.execCommand("copy");
        var msg = successful ? "successful" : "unsuccessful";
        console.log("Fallback: Copying text command was " + msg);
      } catch (err) {
        console.error("Fallback: Oops, unable to copy", err);
      }

      document.body.removeChild(textArea);
    }
  }
};
</script>