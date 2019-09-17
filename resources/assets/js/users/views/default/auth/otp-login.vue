<template>
  <div class="register">
    <div class="background-image"></div>

    <transition name="slide-fade">
      <div
        class="login col-12 col-md-6 col-lg-6 col-xl-4 offset-md-5 offset-lg-5 offset-xl-7"
        v-show="loginShow"
      >
        <div class="login-box">
          <div v-if="!showOTPForm" class="col-12 login-form" @keyup.enter="loginWithOtp">
            <div class="logo">
              <img src="/images/logo.png" alt="logo" width="100%" />
            </div>

            <div class="form-group">
              <div class="col-12">
                <div class="input-group">
                  <div class="input-group-addon icon"></div>

                  <input
                    class="form-control"
                    name="phone"
                    v-validate="'required|max:12'"
                    :class="{'input': true, 'input-danger': errors.has('phone') }"
                    type="text"
                    v-model="phone"
                    placeholder="Phone Number"
                    autocomplete="off"
                  />
                </div>
                <span v-show="errors.has('phone')" class="text-danger">{{errors.first('phone')}}</span>
              </div>
            </div>
            <div class="form-group" v-show="error">
              <div class="col-12">
                <div class="text-danger">Something went wrong</div>
              </div>
            </div>

            <div class="form-group">
              <div class="col-12 col-md-6 offset-md-3">
                <button
                  id="myDiv"
                  class="btn btn-warning"
                  @click="loginWithOtp"
                  v-if="!button_loading"
                >Send OTP</button>
                <button
                  ref="myDiv"
                  class="btn btn-warning"
                  v-if="button_loading"
                >{{$t('register.loading')}}</button>
              </div>
            </div>
            <div class="form-group text-center dark-text">
              {{$t('register.create_accont')}}
              <router-link
                :to="{name: 'plan'}"
                v-if="!$Helper.getIntGatewayStatus('int_gateway')"
              >{{$t('register.signup')}}</router-link>
              <router-link
                :to="{name: 'signup-non-payment'}"
                v-if="$Helper.getIntGatewayStatus('int_gateway')"
              >{{$t('register.signup')}}</router-link>
            </div>
          </div>
          <div v-if="showOTPForm" class="col-12 login-form" @keyup.enter="verifyOTP">
            <div class="logo">
              <img src="/images/logo.png" alt="logo" widtfh="100%" />
            </div>

            <div class="col-12">
              <div class="form-group">
                <div class="input-group">
                  <input
                    class="form-control"
                    name="otp"
                    v-validate="'required|max:12'"
                    :class="{'input': true, 'input-danger': errors.has('otp') }"
                    type="text"
                    v-model="otp"
                    placeholder="Enter OTP"
                    autocomplete="off"
                  />
                </div>
                <span v-show="errors.has('otp')" class="text-danger">{{errors.first('otp')}}</span>
              </div>
              <div class="form-group">
                <div class="col-12 col-md-6 offset-md-0">
                  <button
                    id="myDiv"
                    class="btn btn-warning"
                    @click="verifyOTP"
                    v-if="!button_loading"
                  >Verify OTP</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import { mapState } from "vuex";
import firebase from "firebase";
var firebaseConfig = {
  apiKey: "AIzaSyD_YusDP4Od6tlJ6AB8ROTw9Qz5BpD9ZJ8",
  authDomain: "timeless-1.firebaseapp.com",
  databaseURL: "https://timeless-1.firebaseio.com",
  projectId: "timeless-1",
  storageBucket: "",
  messagingSenderId: "455482860004",
  appId: "1:455482860004:web:d7bf064623928c4c"
};
// Initialize Firebase
const firebaseApp = firebase.initializeApp(firebaseConfig);

export default {
  name: "otp-login",
  data() {
    return {
      phone: "",
      password: "",
      otp: "",
      loginShow: false,
      showOTPForm: false
    };
  },
  computed: mapState({
    error: state => state.auth.error,
    button_loading: state => state.auth.button_loading
  }),
  mounted() {
    setTimeout(() => {
      this.loginShow = true;
      const self = this;
      // Start Firebase invisible reCAPTCHA verifier
      window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier("myDiv", {
        size: "invisible",
        callback: () => {
          // reCAPTCHA solved, allow signInWithPhoneNumber.
          this.loginWithOtp();
        },
        "expired-callback": function() {
          alert("reCAPTCHA expired");
        }
      });

      window.recaptchaVerifier.render().then(widgetId => {
        window.recaptchaWidgetId = widgetId;
      });
      window.recaptchaVerifier.render().then(widgetId => {
        window.recaptchaWidgetId = widgetId;
      });
    }, 200);
  },
  methods: {
    verifyOTP() {
      const result = window.confirmationResult.confirm(this.otp).then(res => {
        console.log("OTP verification result", res);

        axios
          .post("/api/v1/post/otp-login", { phone: this.phone })
          .then(res => {
            Vue.auth.setToken(
              response.data.access_token,
              response.data.expires_in,
              check.data.name,
              check.data.email,
              check.data.language,
              "active",
              check.data.user_id
            );
            router.go("/");
          });
      });
      console.log("OTP verification result", result);
    },
    loginWithOtp() {
      console.log("otp verify", this.phone);
      console.log("otp verify", document.getElementById(this.$refs.myDiv));

      firebaseApp
        .auth()
        .signInWithPhoneNumber("+91" + this.phone, window.recaptchaVerifier)
        .then(confirmationResult => {
          console.log(confirmationResult);
          this.showOTPForm = true;
          window.confirmationResult = confirmationResult;
          // SMS sent. Prompt user to type the code from the message, then sign the
          // user in with confirmationResult.confirm(code).
          window.confirmationResult = confirmationResult;
        })
        .catch(function(error) {
          console.log(error);
          // Error; SMS not sent
          // ...
        });
    }
  }
};
</script>
