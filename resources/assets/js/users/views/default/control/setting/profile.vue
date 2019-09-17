<template>
    <div>
        <transition name="fade">
            <div class="settings">

                <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="checkModalLabel" id="checkModal"
                     aria-hidden="true" style="z-index:100000000;">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="checkModalLabel">{{$t('setting.security_message_header')}}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="col-12 control-label">
                                        <p>{{$t('setting.security_message_body')}}</p>
                                        {{$t('setting.password')}}
                                    </label>
                                    <div class="col-12">
                                        <input class="form-control" type="password" name="current"
                                               v-validate="'min:8|required'"
                                               :class="{'input': true, 'input-danger': errors.has('current') }"
                                               v-model="current_password" :placeholder="$t('setting.password')">
                                        <span v-show="errors.has('password')" class="help text-danger">{{errors.first('password')}}</span>

                                    </div>
                                </div>
                                <div class="form-group" v-if="error !== null">
                                    <div class="col-12">
                                        <span class="text-danger">{{error}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-warning close-btn" data-dismiss="modal">{{$t('setting.cancel')}}
                                </button>
                                <button type="button" class="btn btn-warning" @click="UPDATE_DETAILS">{{$t('setting.submit')}}</button>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="row">

                    <div class="exit-icon" @click="$Helper.home()">
                        <exit-button></exit-button>
                    </div>
                    <!-- EXIT -->

                    <div class="col-12 col-sm-8 offset-sm-2 p-sm-3 mt-5 h-100">

                        <div class="title">
                            <p>{{$t('setting.profile')}}</p>
                        </div>
                        <hr>
                        <div class="col-sm-10 col-lg-8 offset-sm-2">
                            <div class="text-left profile-details">

                                <div class="form-group">
                                    <label class="col-12 control-label">{{$t('setting.name')}}</label>
                                    <div class="col-12">
                                        <input class="form-control" type="name" name="name"
                                               v-validate="'required|min:6|max:24'"
                                               :class="{'input': true, 'input-danger': errors.has('name') }"
                                               v-model="name" :placeholder="$t('setting.name')">
                                        <span v-show="errors.has('name')" class="help text-danger">{{errors.first('name')}}
                                    </span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-12 control-label">{{$t('setting.mail')}}</label>
                                    <div class="col-12">
                                        <input class="form-control" type="email" name="email" v-model="email"
                                               v-validate="'required|max:50'"
                                               :class="{'input': true, 'input-danger': errors.has('email') }"
                                               :placeholder="$t('setting.mail')">
                                        <span v-show="errors.has('email')" class="help text-danger">{{errors.first('email')}}
                                    </span>
                                    </div>
                                </div>
                                <div class="form-group" v-if="success !== null">
                                    <div class="col-12">
                                        <span class="text-success">{{success}}</span>
                                    </div>
                                </div>
                                <div class="form-group" v-if="error !== null">
                                    <div class="col-12">
                                        <span class="text-danger">{{error}}</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-12">
                                        <button class="btn btn-warning" data-toggle="modal"
                                                data-target="#checkModal">{{$t('setting.update')}}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </transition>
    </div>
</template>

<script>
    import exitButton from '../utils/exit-button.vue';
    export default {
        data() {
            return {
                name: null,
                email: null,
                current_password: null,
                success: null,
                error: null,
                show_modal: false,
            };
        },

        components: {
            'exit-button': exitButton
        },

        created(){
            this.email = this.$auth.getUserInfo('email')
            this.name = this.$auth.getUserInfo('username')

        },

        beforeRouteUpdate(to, from, next) {
            this.name = to.params.name;
            next()
        },

        methods: {
            UPDATE_DETAILS() {
                this.$validator.validateAll().then(result => {
                    if (result) {
                        axios.post("/api/v1/update/profile/details", {
                            current_password: this.current_password,
                            name: this.name,
                            email: this.email
                        }).then(response => {
                            if (response.status === 200) {
                                localStorage.setItem("name", this.name);
                                localStorage.setItem("email", this.email);
                                this.success = response.data.message;
                                this.error = null;

                                // close modal
                                document.getElementsByClassName('close')[0].click();
                            }
                        }, error => {
                            this.error = error.response.data.message;
                            this.success = null;
                        });
                    }
                });
            },
        }
    };
</script>