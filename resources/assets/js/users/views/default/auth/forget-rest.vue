<template>
<div>

    <div class="register-background"></div>

    <div class="login p-1 p-sm-2 p-md-3 p-lg-5 p-xl-5">
        <div class="col-12 col-md-6 col-lg-6 offset-md-3 p-lg-5 pt-sm-5 p-3 login-form">
            <h3 class="ml-3"><strong>{{$t('register.password_reset')}}</strong></h3>

            <div class="form-group">
                <label class="col-8 control-label">{{$t('setting.password')}}</label>
                <div class="col-12">
                    <input class="form-control" type="password" name="password"
                                    ref="passwordRef"
                                        v-validate="'min:8|required'"
                                        :class="{'input': true, 'input-danger': errors.has('password') }" v-model="password"
                                        :placeholder="$t('setting.password')">
                        </div>
                </div>

                <div class="form-group">
                    <label class="col-8 control-label">{{$t('setting.password_confirm')}}</label>
                    <div class="col-12">
                        <input class="form-control" type="password" name="password_confirmation"
                                       v-validate="'min:8|required|confirmed:passwordRef'"
                                       :class="{'input': true, 'input-danger': errors.has('password') }" v-model="confirm"
                                       :placeholder="$t('setting.password_confirm')" data-vv-as="password">
                        <span v-show="errors.has('password_confirmation')"
                                      class="text-danger">{{ errors.first('password_confirmation') }}</span>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-12">
                        <button v-if="!button_loading" class="btn btn-warning" @click="changePassword">Continue</button>
                        <button v-if="button_loading" class="btn btn-warning" disabled>
                    <i id="btn-progress"></i> {{$t('register.loading')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            email: "",
            password: "",
            confirm: "",
            button_loading: false,
        };
    },

    created() {
        this.$store.dispatch('CHECK_FORGET_CODE', this.$route.params.code);
    },

    methods: {
        changePassword() {
            this.$validator.validateAll().then(result => {
                if (result) {
                    this.button_loading = true;
                    this.$store.dispatch('CHANGE_FORGET_PASSWORD', {
                        code: this.$route.params.code,
                        password: this.password,
                        password_confirmation: this.confirm
                    })
                }
            });
        }
    }
};
</script>
