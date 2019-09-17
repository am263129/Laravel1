<template>
    <div class="register">
        <div class="col-12 plan" v-if="show_page">


            <div class="exit-button" @click="EXIT">
                <exit-button></exit-button>
            </div>

            <!-- END back -->

            <transition name="slide-fade">

                <div class="col-12 col-md-8 col-lg-8 offset-md-2 p-sm-4 plan-form" v-show="planShow">
                    <div class="col-8 offset-2">
                        <div class="steps hidden-xs-down">
                            <div class="step-1">
                                <div class="circle-1 active-circle ">
                                    <span>1</span>
                                </div>
                                <strong>{{$t('register.choose_plan')}}</strong>

                                <div class="line-1">
                                    <svg width="160" height="140" xmlns="http://www.w3.org/2000/svg" version="1.1">
                                        <line x1="40" x2="180" y1="100" y2="100" stroke="url(#grecya)" stroke-width="3"
                                              stroke-linecap="round"></line>
                                    </svg>
                                </div>

                            </div>
                            <div class="step-2">
                                <div class="circle-2">
                                    <span>2</span>
                                </div>
                                <strong>{{$t('register.signup')}} </strong>
                                <div class="line-2">
                                    <svg width="160" height="140" xmlns="http://www.w3.org/2000/svg" version="1.1">
                                        <line x1="40" x2="180" y1="100" y2="100" stroke="url(#grecya)" stroke-width="3"
                                              stroke-linecap="round"></line>
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
                        <h3>{{$t('register.choose_plan_message')}}</h3>
                    </div>

                    <div class="col-lg-12 text-center">
                        <div class="row m-2">
                            <div class="col-12 col-sm-6 col mt-3 text-center" v-for="(item, index) in planList"
                                 :key="index" @click="plan = item.plan_id">
                                <div class="card-plan" :class="{active_plan: plan === item.plan_id}">
                                    <h3>{{item.plan_name}}</h3>
                                    <h1>${{item.plan_amount}}
                                        <small>/mo</small>
                                    </h1>
                                    <i v-if="item.plan_trial !== null">{{item.plan_trial}}
                                        {{$t('register.day_free')}}</i>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 offset-md-4 mt-5">
                            <button class="btn btn-warning" @click="NEXT" :class="{disabled: plan === 0}">
                                {{$t('register.continue')}}
                            </button>
                        </div>
                    </div>
                </div>
            </transition>


        </div>
    </div>

</template>
<script>
    import {
        mapState
    } from "vuex";
    import exitButton from "../control/utils/exit-button.vue";

    export default {

        data() {
            return {
                plan: 0,
                show_page: true,
                planShow: false,
            }
        },
        components: {
            'exit-button': exitButton
        },

        computed: mapState({
            planList: state => state.home.plan
        }),

        created() {

            if(this.$Helper.getIntGatewayStatus('int_gateway')) {
                this.$router.push({
                    name: 'signup-non-payment'
                });
            }

            this.$store.dispatch("GET_HOME_PLAN");
            setTimeout(() => {
                this.planShow = true;
            }, 200);
        },

        watch: {
            plan(val) {
                localStorage.setItem('_plan', this.plan);
            }
        },

        methods: {
            NEXT() {
                localStorage.setItem('_plan', this.plan);
                this.$router.push({
                    name: 'signup'
                });
            },
            EXIT() {
                this.$router.push({
                    name: 'discover'
                });
            }
        }

    }
</script>