<template>
  <div class="register">
    <div class="background-image"></div>

    <div class="nav-list">
      <router-link :to="{name: 'discover'}">{{$t('register.discover')}}</router-link>
      <router-link :to="{name: 'privacy'}">{{$t('register.privacy')}}</router-link>
      <router-link :to="{name: 'about-us'}">{{$t('register.aboutus')}}</router-link>
      <router-link :to="{name: 'contact-us'}">{{$t('register.contactus')}}</router-link>
    </div>

    <transition name="slide-fade">
      <div
        class="login col-12 col-md-6 col-lg-6 col-xl-4 offset-md-5 offset-lg-5 offset-xl-7"
        v-show="loginShow"
      >
        <div class="login-box">
          <div class="col-12 login-form" @keyup.enter="login">
            <div class="logo">
              <img src="/images/logo.png" alt="logo" width="100%" />
            </div>

            <div class="form-group">
              <div class="col-12">
                <div class="input-group">
                  <div class="input-group-addon icon">
                    <svg
                      version="1.1"
                      xmlns="http://www.w3.org/2000/svg"
                      xmlns:xlink="http://www.w3.org/1999/xlink"
                      x="0px"
                      y="0px"
                      viewBox="0 0 483.3 483.3"
                      style="enable-background:new 0 0 483.3 483.3;"
                      xml:space="preserve"
                      width="30px"
                    >
                      <g>
                        <g>
                          <path
                            d="M424.3,57.75H59.1c-32.6,0-59.1,26.5-59.1,59.1v249.6c0,32.6,26.5,59.1,59.1,59.1h365.1c32.6,0,59.1-26.5,59.1-59.1
                                                v-249.5C483.4,84.35,456.9,57.75,424.3,57.75z M456.4,366.45c0,17.7-14.4,32.1-32.1,32.1H59.1c-17.7,0-32.1-14.4-32.1-32.1v-249.5
                                                c0-17.7,14.4-32.1,32.1-32.1h365.1c17.7,0,32.1,14.4,32.1,32.1v249.5H456.4z"
                            fill="#e7e7e7"
                          />
                          <path
                            d="M304.8,238.55l118.2-106c5.5-5,6-13.5,1-19.1c-5-5.5-13.5-6-19.1-1l-163,146.3l-31.8-28.4c-0.1-0.1-0.2-0.2-0.2-0.3
                                                c-0.7-0.7-1.4-1.3-2.2-1.9L78.3,112.35c-5.6-5-14.1-4.5-19.1,1.1c-5,5.6-4.5,14.1,1.1,19.1l119.6,106.9L60.8,350.95
                                                c-5.4,5.1-5.7,13.6-0.6,19.1c2.7,2.8,6.3,4.3,9.9,4.3c3.3,0,6.6-1.2,9.2-3.6l120.9-113.1l32.8,29.3c2.6,2.3,5.8,3.4,9,3.4
                                                c3.2,0,6.5-1.2,9-3.5l33.7-30.2l120.2,114.2c2.6,2.5,6,3.7,9.3,3.7c3.6,0,7.1-1.4,9.8-4.2c5.1-5.4,4.9-14-0.5-19.1L304.8,238.55z"
                            fill="#e7e7e7"
                          />
                        </g>
                      </g>
                    </svg>
                  </div>

                  <input
                    class="form-control"
                    name="email"
                    v-validate="'required'"
                    :class="{'input': true, 'input-danger': errors.has('email') }"
                    type="text"
                    v-model="email"
                    placeholder="Email or Phone"
                    autocomplete="off"
                  />
                </div>
                <span v-show="errors.has('email')" class="text-danger">{{errors.first('email')}}</span>
              </div>
            </div>
            <div class="form-group">
              <div class="col-12">
                <div class="input-group">
                  <div class="input-group-addon icon">
                    <svg
                      version="1.1"
                      id="Layer_1"
                      xmlns="http://www.w3.org/2000/svg"
                      xmlns:xlink="http://www.w3.org/1999/xlink"
                      x="0px"
                      y="0px"
                      viewBox="0 0 512 512"
                      style="enable-background:new 0 0 512 512;"
                      xml:space="preserve"
                      width="30px"
                    >
                      <g>
                        <g>
                          <path
                            d="M334.974,0c-95.419,0-173.049,77.63-173.049,173.049c0,21.213,3.769,41.827,11.211,61.403L7.672,399.928
                                                            c-2.365,2.366-3.694,5.573-3.694,8.917v90.544c0,6.965,5.646,12.611,12.611,12.611h74.616c3.341,0,6.545-1.325,8.91-3.686
                                                            l25.145-25.107c2.37-2.366,3.701-5.577,3.701-8.925v-30.876h30.837c6.965,0,12.611-5.646,12.611-12.611v-12.36h12.361
                                                            c6.964,0,12.611-5.646,12.611-12.611v-27.136h27.136c3.344,0,6.551-1.329,8.917-3.694l40.121-40.121
                                                            c19.579,7.449,40.196,11.223,61.417,11.223c95.419,0,173.049-77.63,173.049-173.049C508.022,77.63,430.393,0,334.974,0z
                                                            M334.974,320.874c-20.642,0-40.606-4.169-59.339-12.393c-4.844-2.126-10.299-0.956-13.871,2.525
                                                            c-0.039,0.037-0.077,0.067-0.115,0.106l-42.354,42.354h-34.523c-6.965,0-12.611,5.646-12.611,12.611v27.136H159.8
                                                            c-6.964,0-12.611,5.646-12.611,12.611v12.36h-30.838c-6.964,0-12.611,5.646-12.611,12.611v38.257l-17.753,17.725H29.202v-17.821
                                                            l154.141-154.14c4.433-4.433,4.433-11.619,0-16.051s-11.617-4.434-16.053,0L29.202,436.854V414.07l167.696-167.708
                                                            c0.038-0.038,0.067-0.073,0.102-0.11c3.482-3.569,4.656-9.024,2.53-13.872c-8.216-18.732-12.38-38.695-12.38-59.33
                                                            c0-81.512,66.315-147.827,147.827-147.827S482.802,91.537,482.802,173.05C482.8,254.56,416.484,320.874,334.974,320.874z"
                            fill="#e7e7e7"
                          />
                        </g>
                      </g>
                      <g>
                        <g>
                          <path
                            d="M387.638,73.144c-26.047,0-47.237,21.19-47.237,47.237s21.19,47.237,47.237,47.237s47.237-21.19,47.237-47.237
                                                            S413.686,73.144,387.638,73.144z M387.638,142.396c-12.139,0-22.015-9.876-22.015-22.015s9.876-22.015,22.015-22.015
                                                            s22.015,9.876,22.015,22.015S399.777,142.396,387.638,142.396z"
                            fill="#e7e7e7"
                          />
                        </g>
                      </g>
                    </svg>
                  </div>

                  <input
                    class="form-control"
                    type="password"
                    name="password"
                    v-validate="'required|min:6|max:100'"
                    :class="{'input': true, 'input-danger': errors.has('password') }"
                    v-model="password"
                    :placeholder="$t('setting.password')"
                    autocomplete="off"
                  />
                </div>
                <span
                  v-show="errors.has('password')"
                  class="text-danger"
                >{{errors.first('password')}}</span>
              </div>
            </div>
            <div class="form-group" v-show="error">
              <div class="col-12">
                <div class="text-danger">Username or password is incorrect</div>
              </div>
            </div>
            <div class="form-group form-inline">
              <div class="col-12 forget-password">
                <router-link :to="{name: 'forget_password'}">{{$t('register.forget_password')}}</router-link>
              </div>
            </div>
            <!-- <div class="form-group form-inline">
              <div class="col-12 forget-password">
                <router-link :to="{name: 'otp-login'}">Login with OTP</router-link>
              </div>
            </div>-->
            <div class="form-group">
              <div class="col-12 col-md-6 offset-md-3">
                <button
                  class="btn btn-warning"
                  @click="login"
                  v-if="!button_loading"
                >{{$t('register.login')}}</button>
                <button class="btn btn-warning" v-if="button_loading">
                  <i id="btn-progress"></i>
                  {{$t('register.loading')}}
                </button>
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
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import { mapState } from "vuex";

export default {
  data() {
    return {
      email: "",
      password: "",
      loginShow: false
    };
  },
  computed: mapState({
    error: state => state.auth.error,
    button_loading: state => state.auth.button_loading
  }),
  mounted() {
    setTimeout(() => {
      this.loginShow = true;
    }, 200);
  },
  methods: {
    login() {
      this.$validator.validateAll().then(result => {
        if (result) {
          this.$store.dispatch("LOGIN", {
            email: this.email,
            password: this.password
          });
        }
      });
    }
  }
};
</script>
