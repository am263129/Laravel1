<template>
  <div class="register">
    <div class="col-12 signup">
      <div class="exit-button" @click="EXIT">
        <exit-button></exit-button>
      </div>

      <!-- END back -->

      <div class="col-12 col-md-8 offset-md-2 p-md-4 signup-form">
        <div class="col-8 offset-2">
          <div class="steps hidden-xs-down">
            <div class="step-1">
              <div class="circle-1 active-circle">
                <span>1</span>
              </div>
              <strong>{{$t('register.choose_plan')}}</strong>

              <div class="line-1 active-line">
                <svg width="160" height="140" xmlns="http://www.w3.org/2000/svg" version="1.1">
                  <line
                    x1="40"
                    x2="180"
                    y1="100"
                    y2="100"
                    stroke="url(#grecya)"
                    stroke-width="3"
                    stroke-linecap="round"
                  />
                </svg>
              </div>
            </div>
            <div class="step-2">
              <div class="circle-2 active-circle">
                <span>2</span>
              </div>
              <strong>{{$t('register.signup')}}</strong>
              <div class="line-2">
                <svg width="160" height="140" xmlns="http://www.w3.org/2000/svg" version="1.1">
                  <line
                    x1="40"
                    x2="180"
                    y1="100"
                    y2="100"
                    stroke="url(#grecya)"
                    stroke-width="3"
                    stroke-linecap="round"
                  />
                </svg>
              </div>
            </div>
            <div class="step-3">
              <div class="circle-3">
                <span>3</span>
              </div>
              <strong>{{$t('register.payment')}}</strong>
            </div>
          </div>
        </div>
        <div class="title mt-sm-5">
          <h3>{{$t('register.steptwo')}}</h3>
        </div>

        <div class="col-lg-10 offset-lg-1">
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

          <!-- <div class="form-group">
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
          </div>-->

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
                @click="NEXT"
              >{{$t('register.signup')}}</button>
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
export default {
  name: "register",

  data() {
    return {
      name: "",
      email: "",
      password: "",
      confirm: "",
      msgShow: false,
      msg: "",
      button_loading: false,
      phone: ""
    };
  },

  components: {
    "exit-button": exitButton
  },

  mounted() {
    window.location = "/app/signup";
    if (this.$Helper.getIntGatewayStatus("int_gateway")) {
      // this.$router.push({
      //   name: "signup-non-payment",
      //   params: this.$router.params
      // });
    }

    if (
      localStorage.getItem("_plan") === undefined ||
      localStorage.getItem("_plan") === null
    ) {
      this.$router.push({
        name: "plan"
      });
    }
  },

  methods: {
    NEXT() {
      this.$validator.validateAll().then(result => {
        if (result) {
          this.button_loading = true;
          axios
            .post("/api/v1/create/register", {
              name: this.name,
              email: this.email,
              password: this.password,
              password_confirmation: this.confirm,
              phone: this.phone
            })
            .then(
              response => {
                if (response.status === 200) {
                  localStorage.setItem(
                    "user_data",
                    JSON.stringify(response.data)
                  );
                  // mark referral

                  // Login
                  var data = {
                    grant_type: "password",
                    client_id: Vue.helper.client_id(),
                    client_secret: Vue.helper.client_secret(),
                    username: this.email,
                    password: this.password,
                    scope: ""
                  };

                  return;
                  axios.post("/oauth/token", data).then(res => {
                    this.$auth.setToken(
                      res.data.access_token,
                      res.data.expires_in,
                      response.data.username,
                      response.data.email,
                      "en",
                      "payment_step",
                      response.data.user_id
                    );
                    this.button_loading = false;

                    if (this.$router.params.ref) {
                      axios
                        .post(
                          "/api/v1/create/app/referral",
                          {
                            referral_id: this.$router.params.ref
                          },
                          {
                            headers: {
                              Authorization: "Bearer " + res.data.access_token
                            }
                          }
                        )
                        .then(res => {
                          console.warn(res);
                        })
                        .catch(err => {
                          alert(err);
                        });
                    }
                    // window.location.href = "/app/signup/payment";
                  });
                }
              },
              error => {
                this.loading = false;
                this.msgShow = true;
                this.msg = error.response.data.message;
                this.button_loading = false;
              }
            );
        }
      });
    },
    EXIT() {
      this.$router.go(-1);
    }
  }
};
</script>
