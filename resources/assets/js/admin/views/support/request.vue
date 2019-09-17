<template>
<div class="support-request">
    <div class="spinner-load" v-if="spinner_loading">
        <Loader></Loader>
    </div>
    <div class="support-request" v-if="!spinner_loading">

        <div class="row">

            <div class="col-12" v-if="request_data.data">
                <router-link class="exit-button" :to="{name: 'support-manage'}">
                    <svg version="1.1" class="sm-exit-svg" width="100%" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
              x="0px" y="0px" viewBox="0 0 511.999 511.999" style="enable-background:new 0 0 511.999 511.999;" xml:space="preserve">
              <circle style="fill:#E21B1B;" cx="255.999" cy="255.999" r="255.999" />
              <g>
                <rect x="244.002" y="120.008" transform="matrix(0.7071 -0.7071 0.7071 0.7071 -106.0397 256.0022)" style="fill:#FFFFFF;" width="24"
                  height="271.988" />
                <rect x="120.008" y="244.007" transform="matrix(0.7071 -0.7071 0.7071 0.7071 -106.0428 256.0035)" style="fill:#FFFFFF;" width="271.988"
                  height="24" />
              </g>
            </svg>
                </router-link>

                <!-- EXIT -->

                <div class="col-12 col-sm-8 col-lg-8 offset-sm-2 p-sm-3 mt-5 profile-edit h-100">
                    <div class="row">
                        <div class="col-8 text-left">
                            <h4>{{request_data.data.request.subject}} </h4>
                            <small>{{request_data.data.request.request_id}} </small>
                        </div>

                        <div class="col-4 text-right">
                            <span class="re-open"  v-if="request_data.data.request.status === 1 && !button_loading" @click="UPDATE_STATUS">Open</span>
                            <span class="re-close" v-if="request_data.data.request.status === 0 && !button_loading"  @click="UPDATE_STATUS">Close</span>
                            <span class="re-close" v-if="button_loading === 'status'">Loading</span>
                        </div>

                    </div>

                    <hr>

                    <div class="settings__message-box">

                        <div class="col-12">
                            <div class="col-12 mt-5 user">
                                <div class="float-left profile-image text-center">
                                    <img src="/themes/default/img/user.svg" alt="profile" width="50px">
                                    <p>Customer</p>
                                </div>
                                <div class="col-10 col-md-8">
                                    <div class="user-message">
                                        <p class="mt-1">{{request_data.data.request.details}}</p>
                                    </div>

                                    <div class="date">
                                        <p class="mt-1">{{request_data.data.request.created_at}}</p>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-12 mt-5" v-for="(item, index) in request_data.data.reply" :key="index">

                            <div class="col-12 mt-5 user" v-if="item.from === 'client'">
                                <div class="float-left profile-image text-center">
                                    <img src="/themes/default/img/user.svg" alt="profile" width="50px">
                                    <p>Customer</p>
                                </div>
                                <div class="col-10 col-md-8">
                                    <div class="user-message">
                                        <p class="mt-1">{{item.reply}}</p>
                                    </div>
                                    <div class="date">
                                        <p>{{item.created_at}}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="support col-12 mt-5" v-else>
                                <div class="float-left profile-image text-center">
                                    <img src="/themes/default/img/support.png" alt="profile" width="50px">
                                    <p class="mt-1">{{item.from}}</p>
                                </div>
                                <div class="col-10 col-md-8">
                                    <div class="support-message">
                                        <p class="mt-1">{{item.reply}}</p>
                                    </div>
                                    <div class="date">
                                        <p>{{item.created_at}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-12 mt-5 reply-box" v-if="request_data.data.request.status !== 0">
                        <hr>

                        <div class="form-group">
                            <textarea name="details" spellcheck="false" v-validate="'required|max:2000'" cols="50" rows="10" :class="{'input': true, 'text-danger': errors.has('details') }"
                  class="form-control" v-model="reply" placeholder="Provide a detailed description"></textarea>
                            <span v-show="errors.has('details')" class="text-danger">{{ errors.first('details') }}</span>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-md btn-warning" @click="SUBMIT_REPLY" v-if="!button_loading">SEND REPLY</button>
                            <button class="btn btn-md btn-warning" @click="SUBMIT_REPLY" v-if="button_loading === 'reply'" disabled>LOADING</button>
                        </div>
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
import Loader from "../components/loader";

export default {
    data() {
        return {
            subject: "",
            details: "",
            reply: ""
        };
    },

    components: {
        Loader
    },
    computed: mapState({
        spinner_loading: state => state.support.spinner_loading,
        button_loading: state => state.support.button_loading,
        request_data: state => state.support.support_request
    }),

    mounted() {
        this.$store.dispatch("GET_SUPPORT_REQUEST", {
            id: this.$route.params.id
        });
    },

    methods: {
        SUBMIT_REPLY() {
            this.$validator.validateAll().then(result => {
                if (result) {
                    this.$store.dispatch("SUBMIT_SUPPORT_REPLY", {
                        reply: this.reply,
                        id: this.$route.params.id
                    });
                }
            });
        },

        UPDATE_STATUS() {

            this.$store.dispatch("UPDATE_SUPPORT_STATUS", {
                id: this.$route.params.id,
            });
        },
    }
};
</script>
