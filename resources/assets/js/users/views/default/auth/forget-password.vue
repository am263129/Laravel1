<template>
    <div>
        <div class="register-background"></div>


        <div class="login p-1 p-sm-2 p-md-3 p-lg-5 p-xl-5">
            <div class="col-12 col-md-6 col-lg-6 offset-md-3 p-lg-5 pt-sm-5 p-3 login-form">
                <div class="form-group">
                    <label class="col-12 control-label">
                        <h2>{{$t('register.password_reset')}}</h2>
                        {{$t('register.password_message')}}
                    </label>
                    <div class="col-12">
                        <input class="form-control" name="email" v-validate="'required|email|max:50'" :class="{'input': true, 'input-danger': errors.has('email') }"
                            type="email" v-model="email" :placeholder="$t('setting.mail')">
                        <span v-show="errors.has('email')" class="text-danger">{{errors.first('email')}}</span>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-12">
                        <button v-if="!button_loading" class="btn btn-warning" @click="CHECK_EMAIL">SEND</button>
                        <button v-if="button_loading" class="btn btn-warning" disabled>
                            <i id="btn-progress"></i>  {{$t('register.loading')}}</button>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-12">
                        <p> {{$t('register.password_help')}}
                            <router-link :to="{name: 'contact-us'}"> {{$t('app_name')}} {{$t('register.support')}}</router-link>
                        </p>
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

    export default {
        data() {
            return {
                email: '',
            }
        },

        computed: mapState({
            data: state => state.auth.data,
            button_loading: state => state.auth.button_loading
        }),
        methods: {
            CHECK_EMAIL() {
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        this.$store.dispatch('CHECK_EMAIL', this.email)
                    }
                })
            },

        }
    }
</script>