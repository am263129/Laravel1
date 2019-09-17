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
                        <small>{{$t('setting.next_plan')}}</small>
                        <h6 v-if="info.name === 'Monthly'">{{info.name}} for ${{info.amount}}/mo</h6>
                        <h6 v-if="info.name === 'Yearly'"> {{info.name}} for ${{info.amount}}/y</h6>
                        <hr>
                    </div>
                </div>
                <table class="table table-inverse">
                    <thead>
                        <tr>
                            <th>{{$t('setting.description')}}</th>
                            <th>{{$t('setting.start_period')}}</th>
                            <th>{{$t('setting.end_period')}}</th>
                            <th>{{$t('setting.total')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item,key) in info.invoices" :key="key">
                            <td>{{$t('app_name')}}rine service</td>
                            <td>{{item.start}}</td>
                            <td>{{item.end}}</td>
                            <td>${{item.total}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>

</template>

<script>
    import {
        mapState
    } from "vuex";
    import exitButton from '../utils/exit-button';
    const alertify = require("alertify.js");
    export default {

        data() {
            return {
                showModelError: false,
                error: null
            };
        },

        components: {
          'exit-button': exitButton
        },

        computed: mapState({
            loading: state => state.auth.loading,
            info: state => state.auth.items
        }),
        mounted() {
            if (this.$auth.isAuthenticated()) {
                this.$store.dispatch("GET_BILLING_DETAILS");
            }
        },
    }
</script>