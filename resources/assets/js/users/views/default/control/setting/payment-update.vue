<template>
    <div>

        <div class="settings">

            <div class="row">

                <div class="exit-icon" @click="$Helper.home()">
                    <exit-button></exit-button>
                </div>

                <!-- EXIT -->


                <div class="col-12 col-sm-8 offset-sm-2 p-sm-3 mt-5 h-100">
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
                    <div class="form-group">
                        <div class="col-12">
                            <h4 v-if="info.card_brand === 'Visa'">
                                <i class="fa fa-cc-visa" aria-hidden="true"></i> ************{{info.card_number}}</h4>
                            <hr>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-12">
                            <label>{{$t('setting.credit_card_or_debit')}}</label>

                            <div id="dropin-wrapper">
                                <div id="checkout-message"></div>
                                <div id="dropin-container"></div>
                            </div>

                        </div>
                    </div>
                    <small class="is-danger">{{error}}</small>
                    <br>
                    <div class="form-group">
                        <div class="col-12">
                            <button v-if="!update_loading" class="btn btn-warning" ref="submit"
                                    @click="UPDATE">{{$t('setting.update')}}
                            </button>
                            <button v-if="update_loading" class="btn btn-warning" disabled>
                                <i id="btn-progress"></i> Loading
                            </button>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <div class="col-12">
                            <b>{{$t('setting.cancel_memebrship')}}</b>
                            <br>
                            <small>{{$t('setting.cancel_note')}}</small>
                            <br>
                            <button v-show="!button_loading"
                                    v-if="payment_update.subscription_status === 'Active' && payment_update.cancel"
                                    class="btn btn-sm btn-danger mt-2" @click="CANCEL">
                                {{$t('setting.cancel_memebrship')}}
                            </button>
                            <button v-show="!button_loading"
                                    v-if=" payment_update.subscription_status === 'Active' && !payment_update.cancel "
                                    class="btn btn-sm btn-success mt-2" @click="RESUME">
                                {{$t('setting.resume_memebrship')}}
                            </button>
                            <button class="btn btn-sm btn-danger m-2" v-if="button_loading" disabled>
                                <i id="btn-progress"></i> Loading
                            </button>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</template>

<script>
    const alertify = require("alertify.js");
    import {
        mapState
    } from "vuex";

    import dropIn from 'braintree-web-drop-in'

    import exitButton from '../utils/exit-button.vue';

    export default {

        data() {
            return {
                showModelError: false,
                success: false,
                complete: false,
                number: false,
                expiry: false,
                cvc: false,
                vise_brand: null,
                visa_number: null,
                error: null,
                resume: false,
                update_loading: false,
            };
        },

        components: {
            'exit-button': exitButton
        },

        computed: mapState({
            loading: state => state.auth.loading,
            info: state => state.auth.items,
            payment_update: state => state.auth.payment_update,
            button_loading: state => state.auth.button_loading,
        }),

        mounted() {

            if (this.$auth.isAuthenticated()) {
                this.$store.dispatch("GET_PAYMENT");
            }

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
                    vm.update_loading = true;

                    instance.requestPaymentMethod(function (requestPaymentMethodErr, payload) {

                        if (requestPaymentMethodErr) {
                            vm.error = requestPaymentMethodErr.message;
                            vm.update_loading = false;
                            return
                        } else {
                            // Send request
                            vm.error = '';
                            vm.UPDATE(payload.nonce);
                        }

                    });
                });
            });


        },

        methods: {
            UPDATE(token) {
                axios
                    .post("/api/v1/update/profile/payment/update", {
                        token: token
                    })
                    .then(
                        res => {
                            if (res.data.status === "success") {
                                alertify.logPosition("top right");
                                alertify.success("Successful update");
                                this.update_loading = false;
                            }
                        },
                        error => {
                            if (error.response.status === 500) {
                                this.error = error.response.data.message;
                                this.update_loading = false;
                            }
                        });
            },
            CANCEL() {
                this.$store.dispatch("CANCEL_MEMBERSHIP");
            },
            RESUME() {
                this.$store.dispatch("RESUME_MEMBERSHIP");
            },
        }
    };
</script>
