<template>
    <div class="register">
        <div v-if="show">
            <div class="float-right">
                <h2 style="color:#F44336; cursor:pointer; position:fixed; top:0; right:10px; z-index:2;"
                    @click="$auth.destroyToken()">
                    <strong>{{$t('nav.logout')}}</strong>
                </h2>
            </div>


            <div class="col-12 payment">
                <div class="col-12 col-md-8 col-lg-8 offset-md-2 p-4 payment-form">
                    <div class="col-8 offset-2">
                        <div class="steps hidden-xs-down">
                            <div class="step-1">
                                <div class="circle-1 active-circle ">
                                    <span>1</span>
                                </div>
                                <strong>{{$t('register.choose_plan')}}</strong>

                                <div class="line-1 active-line">
                                    <svg width="160" height="140" xmlns="http://www.w3.org/2000/svg" version="1.1">
                                        <line x1="40" x2="180" y1="100" y2="100" stroke="url(#grecya)" stroke-width="3"
                                              stroke-linecap="round"></line>
                                    </svg>
                                </div>

                            </div>
                            <div class="step-2">
                                <div class="circle-2 active-circle">
                                    <span>2</span>
                                </div>
                                <strong>{{$t('register.signup')}} </strong>
                                <div class="line-2 active-line">
                                    <svg width="160" height="140" xmlns="http://www.w3.org/2000/svg" version="1.1">
                                        <line x1="40" x2="180" y1="100" y2="100" stroke="url(#grecya)" stroke-width="3"
                                              stroke-linecap="round"></line>
                                    </svg>
                                </div>
                            </div>
                            <div class="step-3">
                                <div class="circle-3 active-circle">
                                    <span>3</span>
                                </div>
                                <strong>{{$t('register.payment')}}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="title mt-sm-5">
                        <h3>{{$t('register.payment_message')}}</h3>
                    </div>

                    <div class="mb-5">
                        <button class="btn btn-sm btn-secondary" @click="show_plan = !show_plan">Change Plan</button>
                        <p class="text-danger mt-2">{{plan_message}}</p>
                    </div>

                    <transition name="fade">
                        <div class="plan-form payment-plan-form " v-if="show_plan">
                            <h3>Change plan</h3>
                            <div class="col-lg-12 text-center">
                                <div class="row m-2">
                                    <div class="col-12 col-sm-6 col mt-3 text-center" v-for="(item, index) in planList"
                                         :key="index" @click="plan = item.plan_id">
                                        <div class="card-plan" :class="{active_plan: plan === item.plan_id}">
                                            <h3>{{item.plan_name}}</h3>
                                            <h1>${{item.plan_amount}}
                                                <small>/mo</small>
                                            </h1>
                                            <i v-if="item.plan_trial !== null">{{item.plan_trial}} {{$t('register.day_free')}}</i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>
                        </div>
                    </transition>


                    <div class=" col-lg-10 offset-lg-1 ">

                        <div id="dropin-wrapper">
                            <div id="checkout-message"></div>
                            <div id="dropin-container"></div>
                        </div>

                        <small>{{$t('register.cancel_anytime')}}</small>
                        <br>
                        <small class="text-danger">{{error}}</small>
                        <br>
                        <div class="col-12 col-xl-6 offset-xl-3 mt-5">

                            <button v-show="!button_loading && !button_disabled"
                                    class="btn btn-warning mt-4 pay-with-stripe" ref="submit">{{$t('register.start_membership')}}
                            </button>
                            <button v-if="button_loading" class="btn btn-warning" disabled>
                                <i id="btn-progress"></i> {{$t('register.loading')}}
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div v-if="finish">


            <div class="col-12 payment">
                <div class="col-12 col-md-8 col-lg-8 offset-md-2 p-4 payment-form">
                    <h3>{{$t('register.welcome_to')}} {{$t('app_name')}}</h3>
                    <h5>{{$t('register.your')}} {{$t('app_name')}} {{$t('register.message_one')}}</h5>
                    <p>{{$t('register.cancel_before')}} {{trail_end}} {{$t('register.message_two')}}.</p>
                    <br>
                    <h4>{{$t('register.account_details')}}</h4>
                    <ul>
                        <li>{{$t('setting.name')}}: {{name}}</li>
                        <li>{{$t('setting.mail')}}: {{email}}</li>
                        <li>{{$t('register.payment_info')}}: ***********{{card_number}}</li>
                    </ul>
                    <div class="col-12 col-xl-6 offset-xl-3 mt-5">
                        <button class="btn btn-warning" @click="GO_HOME">{{$t('register.finish')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    import {
        mapState
    } from "vuex";

    import dropIn from 'braintree-web-drop-in'

    export default {
        data() {
            return {
                coupon: "",
                show: true,
                finish: false,
                show_plan: false,
                button_disabled: false,
                plan_message: "",
                error: null,
                complete: false,
                number: false,
                expiry: false,
                cvc: false,

                // info
                email: "",
                name: "",
                trail_end: "",
                plan: null,
                button_loading: false
            };
        },

        computed: mapState({
            planList: state => state.home.plan
        }),

        watch: {
            plan() {
                if (this.button_disabled && this.plan_message !== "" && this.plan !== null) {
                    this.button_disabled = false;
                    this.plan_message = "";
                }
            }
        },

        created() {
            if (localStorage.getItem("_plan") !== null) {
                this.plan = localStorage.getItem("_plan");
            } else {

            }

            this.$store.dispatch("GET_HOME_PLAN");

            // Check Users Status
            if (this.$auth.isAuthenticated() == "payment_step") {
                axios
                    .get("/api/v1/get/check/user")
                    .then(response => {
                        if (response.data.status !== "payment_step") {
                            this.$router.push({
                                name: "home"
                            });
                        } else {
                            this.show = true;
                            if (localStorage.getItem("_plan") == null) {
                                this.button_disabled = true;
                                this.plan_message = "You should choose plan";
                            }
                        }
                    })
                    .catch(error => {
                        if (error.response.status === 401) {
                            this.$auth.destroyToken();
                        }
                    });
            } else {
                this.$router.push({
                    name: "home"
                });
            }
        },

        mounted() {

            let vm = this;


            dropIn.create({
                // Insert your tokenization key here
                authorization: this.$Helper.sandbox_key(),
                container: '#dropin-container',
                paypal: {
                    flow: 'vault',
                    buttonStyle: {
                        color: 'blue',
                        shape: 'rect',
                        size: 'medium'
                    }
                },
                card: {
                    overrides: {
                        styles: {
                            //      backgroundColor: '#000000',
                            input: {
                                color: '#ffffff',
                                'font-size': '18px',
                                'font-weight': 'bold'
                            },

                            '.number': {
                                'font-family': 'monospace'
                                // Custom web fonts are not supported. Only use system installed fonts.
                            },
                            '.invalid': {
                                color: '#F44336'
                            },
                            ':focus': {
                                color: '#ffffff'
                            }

                        }
                    }
                }
            }, function (createErr, instance) {
                vm.$refs.submit.addEventListener('click', function () {

                    // Run load button
                    vm.button_loading = true;

                    instance.requestPaymentMethod(function (requestPaymentMethodErr, payload) {

                        if (requestPaymentMethodErr) {
                            vm.error = requestPaymentMethodErr.message;
                            vm.button_loading = false;
                            return
                        } else {
                            // Send request
                            vm.error = '';
                            vm.PAY(payload.nonce);
                        }

                    });
                });
            });

        },

        methods: {
            PAY(token) {
                axios
                    .post("/api/v1/register/payment", {
                        token: token,
                        plan: this.plan
                    })
                    .then(
                        res => {
                            if (res.data.status === "success") {
                                this.show = false;
                                this.button_loading = false;
                                this.email = res.data.email;
                                this.name = res.data.name;
                                this.trail_end = res.data.trail_end;
                                this.card_number = res.data.card_number;
                                this.card_brand = res.data.card_brand;
                                this.finish = true;
                                localStorage.removeItem("_plan");
                                this.$auth.setUpdate(null, null, null, "confirm_step");
                            }
                        },
                        error => {
                            if (error.response.status === 500) {
                                this.error = error.response.data.message;
                                this.button_loading = false;
                            }
                        });
            },


            LOG_OUT() {
                this.$store.dispatch("LOGOUT_AUTH");

            },

            GO_HOME() {
                this.$router.go("/app");
            }
        }
    };
</script>

<style scoped>
    .braintree-option {
        background-color: #03A9F4;
        border-color: transparent;
    }
</style>
