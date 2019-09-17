<template>
  <div class="contact-us">
    <div class="exit-icon" @click="$Helper.home()">
      <exit-button></exit-button>
    </div>

    <!-- EXIT -->

    <div class="col-4">
      <a class="navbar-brand" href="/">
        <img src="/images/logo.png" alt="logo" width="50" />
      </a>
    </div>
    <div class="col-12 p-ms-5 p-lg-5 contact-inbox">
      <div class="title p-4">
        <h2>{{$t('footer.contactus')}}</h2>
      </div>

      <div class="row">
        <div class="col-12 col-lg-6">
          <div class="col-12">
            <div class="row">
              <div class="col-12 col-lg-6">
                <div class="form-group">
                  <div class="col-12">
                    <input
                      class="form-control"
                      name="first-name"
                      v-validate="'required|max:30'"
                      :class="{'input': true, 'input-danger': errors.has('first-name') }"
                      type="text"
                      :placeholder="$t('footer.first_name')"
                      v-model="first_name"
                    />
                    <span
                      v-show="errors.has('first-name')"
                      class="help is-danger"
                    >{{errors.first('first-name')}}</span>
                  </div>
                </div>
              </div>

              <div class="col-12 col-lg-6">
                <div class="form-group">
                  <div class="col-12">
                    <input
                      class="form-control"
                      name="last-name"
                      v-validate="'required|max:30'"
                      :class="{'input': true, 'input-danger': errors.has('last-name') }"
                      type="text"
                      :placeholder="$t('footer.last_name')"
                      v-model="last_name"
                    />
                    <span
                      v-show="errors.has('last-name')"
                      class="help is-danger"
                    >{{errors.first('last-name')}}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-12">
            <div class="row">
              <div class="col-12 col-lg-6">
                <div class="form-group">
                  <div class="col-12">
                    <input
                      class="form-control"
                      name="phone-number"
                      v-validate="'required|numeric|max:20'"
                      :class="{'input': true, 'input-danger': errors.has('phone-number') }"
                      type="text"
                      v-model="number"
                      :placeholder="$t('footer.phone_number')"
                    />
                    <span
                      v-show="errors.has('phone-number')"
                      class="help is-danger"
                    >{{errors.first('phone-number')}}</span>
                  </div>
                </div>
              </div>
              <div class="col-12 col-lg-6">
                <div class="form-group">
                  <div class="col-12">
                    <input
                      class="form-control"
                      name="email"
                      v-validate="'required|email|max:50'"
                      :class="{'input': true, 'input-danger': errors.has('email') }"
                      type="email"
                      v-model="email"
                      :placeholder="$t('footer.email')"
                    />
                    <span
                      v-show="errors.has('email')"
                      class="help is-danger"
                    >{{errors.first('email')}}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-12">
            <div class="form-group">
              <div class="col-12">
                <textarea
                  class="form-control"
                  :class="{'input': true, 'input-danger': errors.has('email') }"
                  v-validate="'required|max:200'"
                  name="message"
                  v-model="message"
                  type="text"
                  :placeholder="$t('footer.message')"
                ></textarea>
                <span
                  v-show="errors.has('message')"
                  class="help is-danger"
                >{{errors.first('message')}}</span>
              </div>
            </div>

            <div class="form-group">
              <div class="col-12">
                <button
                  class="btn btn-warning"
                  @click="SEND_MESSAGE"
                  v-if="!btn_loading"
                >{{$t('report.send')}}</button>
                <button class="btn btn-warning" disabled v-if="btn_loading">
                  <i id="btn-progress"></i>
                  {{$t('report.send')}}
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
const alertify = require("alertify.js");
import exitButton from "../utils/exit-button";

export default {
  data() {
    return {
      first_name: null,
      last_name: null,
      number: null,
      email: null,
      message: null,
      btn_loading: false
    };
  },

  components: {
    "exit-button": exitButton
  },

  methods: {
    SEND_MESSAGE() {
      this.btn_loading = true;
      this.$validator.validateAll().then(result => {
        if (result) {
          axios
            .post("/api/v1/new/contactus", {
              first_name: this.first_name,
              last_name: this.last_name,
              phone_number: this.number,
              email: this.email,
              message: this.message
            })
            .then(
              res => {
                if (res.status === 200) {
                  this.btn_loading = false;
                  alertify.logPosition("top right");
                  alertify.success(this.$t("footer.success_send"));
                } else {
                  this.btn_loading = false;
                  alertify.logPosition("top right");
                  alertify.error(this.$t("footer.tryanothertime"));
                }
              },
              err => {
                this.btn_loading = false;
              }
            );
        }
      });
    }
  }
};
</script>
