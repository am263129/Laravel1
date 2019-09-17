<template>
    <div>

        <div class="settings">

        <div class="row">

            <div class="exit-icon" @click="$Helper.home()">
                <exit-button></exit-button>
            </div>

            <!-- EXIT -->



            <div class="col-12 col-sm-8 offset-sm-2 p-sm-3 mt-5 h-100">


                <div class="title">
                    <p>{{$t('setting.security')}}</p>
                </div>
                <hr>
                <div class="col-sm-10 col-lg-8 offset-sm-2">
                    <div class="form-group">
                        <label class="col-12 control-label">{{$t('setting.current')}}</label>
                        <div class="col-12">
                            <input class="form-control" type="password" name="current" v-validate="'min:8|required'" :class="{'input': true, 'input-danger': errors.has('current') }"
                                v-model="current" :placeholder="$t('setting.current')"  autocomplete="off">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-12 control-label">{{$t('setting.password')}}</label>
                        <div class="col-12">
                            <input class="form-control" type="password" name="confirm-field" v-validate="'min:8|required'" :class="{'input': true, 'input-danger': errors.has('password') }"
                                v-model="password" :placeholder="$t('setting.password')" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-12 control-label">{{$t('setting.password_confirm')}}</label>
                        <div class="col-12">
                            <input class="form-control" type="password" name="password" v-validate="'min:8|required|confirmed:confirm-field'" :class="{'input': true, 'input-danger': errors.has('password') }"
                                v-model="confirm" :placeholder="$t('setting.password_confirm')" data-vv-as="password" autocomplete="off">
                            <span v-show="errors.has('password')" class="help text-danger">{{ errors.first('password')}}</span>
                        </div>
                    </div>
                    <div class="form-group" v-if="success">
                        <div class="col-12">
                            <span class="text-success">{{$t('setting.successful_update')}}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-12">
                            <button class="btn btn-warning" @click="CHANGE_PASSWORD">{{$t('setting.update')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        </div>
    </div>
</template>

<script>

    import exitButton from '../utils/exit-button.vue';

    export default {
        data() {
            return {
                current: "",
                password: "",
                confirm: "",
                success: ""
            };
        },

        components: {
            'exit-button': exitButton
        },

        beforeRouteUpdate(to, from, next) {
            this.name = to.params.name;
            next()
        },

        methods: {
            CHANGE_PASSWORD() {
                this.$validator.validateAll().then(result => {
                    if (result) {
                        this.$store.dispatch("UPDATE_PASSWORD", {
                            password: this.password,
                            password_confirmation: this.confirm,
                            current_password: this.current,
                        });
                    }
                });
            }
        }
    };
</script>