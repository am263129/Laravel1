<template>
<div>
    <div class="k1_manage_table">
        <div class="title p-2">
            Create
        </div>
        <div class="container">
            <div class="col-12 my-3 p-2">
                <div class="group-btn">
                    <router-link class="btn btn-sm btn-warning" role="button" :to="{name: 'admins-users-manage'}">Manage</router-link>
                </div>
                <hr>
            </div>
            <!-- END LIST -->
            <div class="col-12 col-sm-8 col-lg-4 offset-sm-2 offset-lg-3" id="profile-setting">

                <div id="profile-details">

                    <div class="form-group">
                        <label class="col-8 control-label">Name</label>
                        <div class="col-12">
                            <input class="form-control" type="name" name="name" v-validate="'required|min:6|max:24'" :class="{'input': true, 'input-danger': errors.has('name') }"
                v-model="name" placeholder="Name">
                            <span v-show="errors.has('name')" class="help is-danger">{{errors.first('name')}}
              </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-8 control-label">E-mail</label>
                        <div class="col-12">
                            <input class="form-control" type="email" name="email" v-model="email" v-validate="'required|max:50'" :class="{'input': true, 'input-danger': errors.has('email') }"
                placeholder="E-mail">
                            <span v-show="errors.has('email')" class="help is-danger">{{errors.first('email')}}
              </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-8 control-label">Password</label>
                        <div class="col-12">
                            <input class="form-control" type="password" name="password-field" v-validate="'required|min:8'" :class="{'input': true, 'input-danger': errors.has('password') }"
                v-model="password" placeholder="Password">
                            <span v-show="errors.has('password-field')" class="help is-danger">{{errors.first('password-field')}}
              </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-12 control-label">Password confirm</label>
                        <div class="col-12">
                            <input class="form-control" type="password" name="confirm-field" v-validate="'required|min:8'" :class="{'input': true, 'input-danger': errors.has('password_confirmation') }"
                v-model="password_confirmation" placeholder="Password confirmation">
                            <span v-show="errors.has('confirm-field')" class="help is-danger">{{errors.first('confirm-field')}}
              </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-8 control-label">Permissions</label>
                        <div class="col-12">
                            <select class="custom-select" v-model="permission" name="permission" v-validate="'required'">
                <option value="1">Admin</option>
                <option value="2">Manager</option>
              </select>
                            <span v-show="errors.has('permission')" class="help is-danger">{{errors.first('permission')}}
              </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-12">
                            <button class="btn btn-md btn-warning" @click="CREATE">Create
              </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
</template>

<script>
const alertify = require("alertify.js");

export default {
    name: "edit-users-manage",
    data() {
        return {
            name: "",
            email: "",
            password: "",
            password_confirmation: "",
            permission: "",
            success: false,
            error: false
        };
    },

    methods: {
        CREATE() {
            this.$validator.validateAll().then(result => {
                if (result) {
                    this.$store.dispatch("CREATE_ADMIN_USER", {
                        name: this.name,
                        email: this.email,
                        password: this.password,
                        password_confirmation: this.password_confirmation,
                        permission: this.permission
                    });
                }
            });
        }
    }
};
</script>
