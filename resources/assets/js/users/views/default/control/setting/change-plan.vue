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
                    <div class="col-12 col-lg-12 settings__plan-form">

                        <div class="row m-2">
                            <div class="col-12 col-sm-6 col mt-3 text-center" v-for="(item, index) in planList"
                                 :key="index" @click="plan_id = item.plan_id">
                                <div class="settings__plan-content"
                                     :class="{'settings__active-plan': plan_id === item.plan_id}">
                                    <h3>{{item.plan_name}}</h3>
                                    <h1>${{item.plan_amount}}
                                        <small>/{{$t('setting.y')}}</small>
                                    </h1>
                                    <i v-if="item.plan_trial !== null">{{item.plan_trial}} days free</i>
                                </div>
                            </div>
                        </div>


                        <div class="col-8 col-sm-6 col-lg-4 offset-2 offset-sm-3 offset-lg-4  mt-5">
                            <button v-show="!button_loading" v-if="plan_id" class="btn btn-warning w-100"
                                    @click="CHANGE_PLAN">{{$t('setting.update')}}
                            </button>
                            <button v-show="button_loading" class="btn btn-warning" v-if="button_loading" disabled>
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
    import {
        mapState
    } from "vuex";
    import exitButton from '../utils/exit-button.vue';

    const alertify = require("alertify.js");
    export default {
        data() {
            return {
                error: null,
                plan_id: false
            };
        },

        computed: mapState({
            loading: state => state.auth.loading,
            button_loading: state => state.auth.button_loading,
            plan: state => state.auth.plan,
            planList: state => state.home.plan
        }),

        components: {
            'exit-button': exitButton,
        },

        created() {
            if (this.$auth.isAuthenticated()) {
                this.$store.dispatch("GET_PAYMENT");
                this.$store.dispatch("GET_HOME_PLAN");
            }
        },

        methods: {
            CHANGE_PLAN() {
                if (this.$auth.isAuthenticated()) {
                    this.$store.dispatch("CHANGE_PLAN", this.plan_id);
                }
            },
        }
    };
</script>