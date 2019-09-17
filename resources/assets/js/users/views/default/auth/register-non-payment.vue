<template>
  <div class="register">
    <div class="col-12 signup">
      <div class="exit-button" @click="EXIT">
        <exit-button></exit-button>
      </div>

      <!-- END back -->

      <div class="col-12 col-md-8 offset-md-2 p-4 signup-form">
        <div v-if="!showOTPForm" class="col-lg-10 offset-lg-1">
          <div class="title">
            <h3>One Step!</h3>
          </div>
          <div class="form-group">
            <div class="col-12">
              <input
                class="form-control"
                type="name"
                name="name"
                v-validate="'required|alpha_spaces|min:6|max:24'"
                :class="{'input': true, 'input-danger': errors.has('name') }"
                v-model="name"
                :placeholder="$t('setting.name')"
                autocomplete="off"
              />
              <span v-show="errors.has('name')" class="text-danger">{{errors.first('name')}}</span>
            </div>
          </div>

          <div class="form-group">
            <div class="col-12">
              <input
                class="form-control"
                type="email"
                name="email"
                v-validate="'required|email|max:50'"
                :class="{'input': true, 'input-danger': errors.has('email') }"
                v-model="email"
                :placeholder="$t('setting.mail')"
                autocomplete="off"
              />
              <span v-show="errors.has('email')" class="text-danger">{{errors.first('email')}}</span>
            </div>
          </div>
          <div class="form-group">
            <div class="col-12">
              <input
                class="form-control"
                type="phone"
                name="phone"
                v-validate="'required|max:10'"
                :class="{'input': true, 'input-danger': errors.has('phone') }"
                v-model="phone"
                placeholder="Enter phone number"
                autocomplete="off"
              />
              <span v-show="errors.has('phone')" class="text-danger">{{errors.first('phone')}}</span>
            </div>
          </div>

          <div class="form-group">
            <div class="col-12">
              <input
                class="form-control"
                type="password"
                name="password"
                ref="passwordRef"
                v-validate="'min:8|required'"
                :class="{'input': true, 'input-danger': errors.has('password') }"
                v-model="password"
                :placeholder="$t('setting.password')"
              />
            </div>
          </div>

          <div class="form-group">
            <div class="col-12">
              <input
                class="form-control"
                type="password"
                name="password_confirmation"
                v-validate="'min:8|required|confirmed:passwordRef'"
                :class="{'input': true, 'input-danger': errors.has('password') }"
                v-model="confirm"
                :placeholder="$t('setting.password_confirm')"
                data-vv-as="password"
              />
              <span
                v-show="errors.has('password_confirmation')"
                class="text-danger"
              >{{ errors.first('password_confirmation') }}</span>
            </div>
          </div>

          <div class="form-group">
            <div class="col-12">
              <span v-show="msgShow" class="text-danger">{{msg}}</span>
            </div>
          </div>
          <div class="form-group">
            <div class="col-12">
              <p>
                {{$t('register.agree_role')}}
                <router-link :to="{name: 'terms'}" style="color:#3498db;">Terms Of Service</router-link>
                {{$t('register.and')}}
                <router-link :to="{name: 'privacy'}" style="color:#3498db;">Privacy Policy</router-link>
              </p>
            </div>
          </div>
          <div class="form-group">
            <div class="col-12 col-md-6 offset-md-3">
              <button
                v-if="!button_loading"
                class="btn btn-warning"
                id="myDiv"
                @click="sendOTP"
              >Send OTP</button>
              <button v-if="button_loading" class="btn btn-warning" disabled>
                <i id="btn-progress"></i>
                {{$t('register.loading')}}
              </button>
            </div>
          </div>
        </div>
        <div v-if="showOTPForm" class="col-lg-10 offset-lg-1">
          <div class="title">
            <h3>
              Please wait for OTP...
              <small>Should come in around 60 seconds..</small>
            </h3>
          </div>

          <div class="form-group">
            <div class="col-12">
              <input
                class="form-control"
                type="number"
                name="otp"
                v-validate="'required'"
                :class="{'input': true, 'input-danger': errors.has('otp') }"
                v-model="otp"
                placeholder="Enter OTP"
                autocomplete="off"
              />
              <span v-show="errors.has('otp')" class="text-danger">{{errors.first('otp')}}</span>
            </div>
          </div>
          <div class="form-group">
            <div class="col-12 col-md-6 offset-md-3">
              <button v-if="!button_loading" class="btn btn-warning" @click="verifyOTP">Verify OTP</button>
              <button v-if="button_loading" class="btn btn-warning" disabled>
                <i id="btn-progress"></i>
                {{$t('register.loading')}}
              </button>
            </div>
          </div>
          <div class="form-group">
            <div class="col-12 col-md-6 offset-md-3">
              <button v-if="!button_loading" class="btn btn-warning" @click="sendOTP">Resend OTP</button>
              <button v-if="button_loading" class="btn btn-warning" disabled>
                <i id="btn-progress"></i>
                {{$t('register.loading')}}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import exitButton from "../control/utils/exit-button.vue";
import Vue from "vue";
import firebase from "firebase";
import swal from "sweetalert";

// var firebaseConfig = {
//   apiKey: "AIzaSyD_YusDP4Od6tlJ6AB8ROTw9Qz5BpD9ZJ8",
//   authDomain: "timeless-1.firebaseapp.com",
//   databaseURL: "https://timeless-1.firebaseio.com",
//   projectId: "timeless-1",
//   storageBucket: "",
//   messagingSenderId: "455482860004",
//   appId: "1:455482860004:web:d7bf064623928c4c"
// };
// // Initialize Firebase
// const firebaseApp = firebase.initializeApp(firebaseConfig);

export default {
  name: "register",

  data() {
    return {
      name: "",
      email: "",
      phone: "",
      password: "",
      confirm: "",
      msgShow: false,
      msg: "",
      button_loading: false,
      otp: "",
      loginShow: false,
      showOTPForm: false
    };
  },

  components: {
    "exit-button": exitButton
  },

  mounted() {
    // if (!this.$Helper.getIntGatewayStatus("int_gateway")) {
    //   this.$route.push({
    //     name: "plan"
    //   });
    // }

    setTimeout(() => {
      this.loginShow = true;
      const self = this;
      // Start Firebase invisible reCAPTCHA verifier
      try {
        window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier(
          "myDiv",
          {
            size: "invisible",
            callback: () => {
              console.log("captch solved");
              // reCAPTCHA solved, allow signInWithPhoneNumber.
              this.loginWithOtp();
            },
            "expired-callback": function() {
              console.log("captch solved");

              alert("reCAPTCHA expired");
            }
          }
        );

        window.recaptchaVerifier.render().then(widgetId => {
          window.recaptchaWidgetId = widgetId;
        });
        window.recaptchaVerifier.render().then(widgetId => {
          window.recaptchaWidgetId = widgetId;
        });
      } catch (err) {
        console.warn("ERROR In Captcha", err);
      }
    }, 2000);
  },

  methods: {
    NEXT() {
      this.$validator
        .validateAll()
        .then(result => {
          if (result) {
            this.button_loading = true;
            axios
              .post("/api/v1/create/register", {
                name: this.name,
                email: this.email,
                phone: this.phone,
                password: this.password,
                password_confirmation: this.confirm
              })
              .then(
                response => {
                  // alert("go response", JSON.stringify(response));
                  if (response.status === 200) {
                    // Login
                    var data = {
                      grant_type: "password",
                      client_id: Vue.helper.client_id(),
                      client_secret: Vue.helper.client_secret(),
                      username: this.email,
                      password: this.password,
                      scope: ""
                    };

                    if (this.$route.query.ref) {
                      axios
                        .post("/api/v1/create/app/referral", {
                          referral_id: this.$route.query.ref,
                          email: this.email
                        })
                        .then(res => {
                          console.warn(res);
                        })
                        .catch(err => {
                          alert(err);
                        });
                    }

                    this.$store.dispatch("LOGIN", {
                      email: this.phone,
                      password: this.password
                    });

                    // axios.post("/oauth/token", data).then(res => {
                    //   alert(Object.keys(res.data));
                    //   this.$auth.setToken(
                    //     res.data.access_token,
                    //     res.data.expires_in,
                    //     response.data.username,
                    //     response.data.email,
                    //     "en",
                    //     "active",
                    //     response.data.id
                    //   );
                    //   this.button_loading = false;

                    //   window.location.href = "/app";
                    // });
                  }
                },
                error => {
                  swal("Oops!", error.response.data.message, "error");
                  this.loading = false;
                  this.msgShow = true;
                  this.msg = error.response.data.message;
                  this.button_loading = false;
                }
              )
              .catch(err => {
                swal("Oops!", err.message, "error");
              });
          }
        })
        .catch(err => {
          swal("Oops!", "Something went wrong!", "error");
        });
    },
    verifyOTP() {
      const nextCall = this.NEXT;
      const result = window.confirmationResult
        .confirm(this.otp)
        .then(res => {
          console.log("OTP verification result", res);

          nextCall();
        })
        .catch(err => {
          swal("Oops!", err.message, "error");
        });
      console.log("OTP verification result", result);
    },
    sendOTP() {
      console.log("otp verify", this.phone);
      console.log("otp verify", document.getElementById(this.$refs.myDiv));

      firebase
        .auth()
        .signInWithPhoneNumber("+91" + this.phone, window.recaptchaVerifier)
        .then(confirmationResult => {
          console.log(confirmationResult);
          this.showOTPForm = true;
          swal("OTP has been sent!");

          window.confirmationResult = confirmationResult;
          // SMS sent. Prompt user to type the code from the message, then sign the
          // user in with confirmationResult.confirm(code).
          window.confirmationResult = confirmationResult;
        })
        .catch(function(error) {
          console.log(error);
          swal("Oops!", error.message, "error");
          // Error; SMS not sent
          // ...
        });
    },
    EXIT() {
      this.$router.go(-1);
    }
  }
};
</script>
