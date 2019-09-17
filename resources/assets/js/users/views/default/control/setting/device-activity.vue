<template>
    <div>
        <div class="settings">


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


            <div class="row">

                <div class="exit-icon" @click="$Helper.home()">
                    <exit-button></exit-button>
                </div>

                <!-- EXIT -->


                <div class="col-12 col-sm-8 offset-sm-2 p-sm-3 mt-5 h-100">

                    <div class="device-activity">
                        <div class="title">
                            <p>{{$t('setting.device_activity')}}</p>
                        </div>
                        <hr>
                        <table class="table table-inverse">

                            <tbody>
                                <tr v-for="(item,key) in device_activity_data.data" :key="key">

                                    <td class="device-on" v-if="item.status == 'on'">
                                        <p class="location">{{item.country}} / {{item.city}}</p>
                                        <span class="status">Active now</span>
                                    </td>
                                    <td class="device-off" v-if="item.status == 'off'">

                                        <p class="location">{{item.country}} / {{item.city}}</p>
                                        <span class="status"> Last Login - {{item.updated_at}}</span>

                                        <div class="end-session" @click="DELETE_SESSION(item.id, key)">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 174.239 174.239"
                                                style="enable-background:new 0 0 174.239 174.239;" xml:space="preserve" width="15px">
                                                <g>
                                                    <path d="M87.12,0C39.082,0,0,39.082,0,87.12s39.082,87.12,87.12,87.12s87.12-39.082,87.12-87.12S135.157,0,87.12,0z M87.12,159.305   c-39.802,0-72.185-32.383-72.185-72.185S47.318,14.935,87.12,14.935s72.185,32.383,72.185,72.185S126.921,159.305,87.12,159.305z"
                                                        fill="#ffffff" />
                                                    <path d="M120.83,53.414c-2.917-2.917-7.647-2.917-10.559,0L87.12,76.568L63.969,53.414c-2.917-2.917-7.642-2.917-10.559,0   s-2.917,7.642,0,10.559l23.151,23.153L53.409,110.28c-2.917,2.917-2.917,7.642,0,10.559c1.458,1.458,3.369,2.188,5.28,2.188   c1.911,0,3.824-0.729,5.28-2.188L87.12,97.686l23.151,23.153c1.458,1.458,3.369,2.188,5.28,2.188c1.911,0,3.821-0.729,5.28-2.188   c2.917-2.917,2.917-7.642,0-10.559L97.679,87.127l23.151-23.153C123.747,61.057,123.747,56.331,120.83,53.414z"
                                                        fill="#ffffff" />
                                                </g>
                                            </svg>
                                        </div>

                                    </td>
                                    <td class="device-failed" v-if="item.status == 'failed'">
                                        <p class="location">{{item.country}} / {{item.city}}</p>
                                        <span class="status">{{item.updated_at}} - Failed</span>

                                        <div class="end-session" @click="DELETE_SESSION(item.id, key)">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 174.239 174.239"
                                                style="enable-background:new 0 0 174.239 174.239;" xml:space="preserve" width="15px">
                                                <g>
                                                    <path d="M87.12,0C39.082,0,0,39.082,0,87.12s39.082,87.12,87.12,87.12s87.12-39.082,87.12-87.12S135.157,0,87.12,0z M87.12,159.305   c-39.802,0-72.185-32.383-72.185-72.185S47.318,14.935,87.12,14.935s72.185,32.383,72.185,72.185S126.921,159.305,87.12,159.305z"
                                                        fill="#ffffff" />
                                                    <path d="M120.83,53.414c-2.917-2.917-7.647-2.917-10.559,0L87.12,76.568L63.969,53.414c-2.917-2.917-7.642-2.917-10.559,0   s-2.917,7.642,0,10.559l23.151,23.153L53.409,110.28c-2.917,2.917-2.917,7.642,0,10.559c1.458,1.458,3.369,2.188,5.28,2.188   c1.911,0,3.824-0.729,5.28-2.188L87.12,97.686l23.151,23.153c1.458,1.458,3.369,2.188,5.28,2.188c1.911,0,3.821-0.729,5.28-2.188   c2.917-2.917,2.917-7.642,0-10.559L97.679,87.127l23.151-23.153C123.747,61.057,123.747,56.331,120.83,53.414z"
                                                        fill="#ffffff" />
                                                </g>
                                            </svg>
                                        </div>

                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
                showModelError: false,
                error: null
            };
        },

        components: {
            'exit-button': exitButton
        },

        computed: mapState({
            loading: state => state.auth.loading,
            device_activity_data: state => state.auth.device_activity_data
        }),

        mounted() {
            this.$store.dispatch("GET_DEVICE_ACTIVITY");
        },

        methods: {
            DELETE_SESSION(id, key) {
                this.$store.dispatch("DELETE_DEVICE_SESSION", {
                    id,
                    key
                });
            }
        }
    }
</script>

<style scoped>
    .table-inverse a {
        color: #2196F3 !important;
    }
</style>