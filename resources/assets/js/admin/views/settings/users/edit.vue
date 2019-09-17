<template>
<div class="edit-users">

    <div class="spinner-load" v-if="spinner_loading">
        <Loader></Loader>
    </div>

    <!-- END spinner load -->
    <div class="k1_manage_table" v-if="!spinner_loading">
        <div class="title p-2">
            Edit
        </div>
        <div class="container my-5">
            <div class="col-12 col-sm-6 col-lg-4 offset-sm-3 offset-lg-3" id="profile-setting">

                <div id="avatar-img">
                    <img :src="data.details.image" alt="numberone" id="avatar" width="100" onError="this.onerror=null;this.src='/images/avatar.png';">
                    <label for="avatar-img-file">
                        <i class="fa fa-pencil" aria-hidden="true"></i>Change image</label>
                    <input type="file" id="avatar-img-file" name="avatar" class="inputfile" @change="changeImage" v-validate="'image'">
                </div>
                    <div class="text-center">
                        <span v-show="errors.has('avatar')" class="is-danger">{{ errors.first('avatar')}}</span>
                    </div>

                    <!-- END avatar-img -->

                    <div id="profile-details">

                        <div class="form-group">
                            <label class="col-8 control-label">Name</label>
                            <div class="col-12">
                                <input class="form-control" type="name" name="name" v-validate="'min:6|max:24'" :class="{'input': true, 'input-danger': errors.has('name') }"
                                v-model="data.details.name" placeholder="Name">
                                <span v-show="errors.has('name')" class="help is-danger">{{errors.first('name')}}
                            </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-8 control-label">E-mail</label>
                            <div class="col-12">
                                <input class="form-control" type="email" name="email" v-model="data.details.email" v-validate="'max:50'" :class="{'input': true, 'input-danger': errors.has('email') }"
                                placeholder="E-mail">
                                <span v-show="errors.has('email')" class="help is-danger">{{errors.first('email')}}
                            </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-8 control-label">Password</label>
                            <div class="col-12">
                                <input class="form-control" type="password" name="confirm-field" v-validate="'min:8'" :class="{'input': true, 'input-danger': errors.has('password') }"
                                v-model="password" placeholder="Password">
                        </div>
                            </div>
                            <div class="form-group">
                                <label class="col-8 control-label">Permissions</label>
                                <div class="col-12">

                                    <select class="custom-select" v-model="data.details.role_id">
                                <option value="1">Admin</option>
                                <option value="2">Manager</option>
                            </select>
                                </div>
                            </div>
                            <div class="form-group" v-if="success">
                                <div class="col-12">
                                    <span class="is-success">Successful change.</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-12">
                                    <button class="btn btn-md btn-warning" @click="UPDATE_DETAILS">Update
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
import {
    mapState
} from "vuex";
import Loader from "../../components/loader";

export default {
    data() {
        return {
            password: null
        };
    },
    components: {
        Loader
    },
    computed: mapState({
        data: state => state.admins.data,
        button_loading: state => state.admins.button_loading
    }),

    created() {
        this.$store.dispatch("GET_ADMIN_DETAILS", this.$route.params.id);
    },

    methods: {
        changeImage() {
            const formData = new FormData();
            const image = document.getElementById("avatar-img-file");
            formData.append("image", image.files[0]);
            formData.append("id", this.$route.params.id);
            axios.post("api/admin/settings/users/image", formData).then(
                res => {
                    if (res.data.status === "success") {
                        this.avatar_image = res.data.image;
                    }
                },
                error => {
                    if (error.response.status === 401) {
                        this.error = 401;
                    }
                }
            );
        },

        UPDATE_DETAILS() {
            this.$validator.validateAll().then(result => {
                if (result) {
                    this.$store.dispatch("UPDATE_ADMIN_DETAILS", {
                        id: this.data.details.id,
                        name: this.data.details.name,
                        email: this.data.details.email,
                        password: this.password,
                        permission: this.data.details.role_id
                    });
                }
            });
        }
    }
};
</script>
