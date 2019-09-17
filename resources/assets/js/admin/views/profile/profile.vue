<template>
<div class="profile">
    <div class="spinner-load" v-if="spinner_loading">
        <Loader></Loader>
    </div>

    <div class="k1_manage_table" v-if="!spinner_loading">
        <div class="container my-5">
            <div class="row">
                <div class="col-12 col-sm-3 col-lg-3">
                    <div class="list-group">
                        <router-link class="list-group-item list-group-item-action" :to="{name: 'profile'}">
                            Profile
                        </router-link>
                        <router-link class="list-group-item list-group-item-action" :to="{name: 'security'}" exact>
                            Security
                        </router-link>
                    </div>
                    <hr>
                    <b class="is-danger" style="cursor:pointer; margin-left:10px;" @click="LOGOUT">Logout</b>
                </div>

                <!-- END LIST -->

                <div class="col-12 col-sm-6 col-lg-4  mt-5" id="profile-setting">

                    <div id="avatar-img">
                        <img :src="$auth.getUserInfo('image')"
                                 onError="this.onerror=null;this.src='/images/avatar.png';" width="100%">

                        <label for="avatar-img-file">
                                <i class="fa fa-pencil" aria-hidden="true"></i>Change image</label>
                        <input type="file" id="avatar-img-file" name="avatar" class="inputfile"
                                   @change="changeImage" v-validate="'image'">
                        </div>
                        <div class="text-center">
                            <span v-show="errors.has('avatar')" class="is-danger">{{ errors.first('avatar')}}</span>
                        </div>
                        <!-- END avatar-img -->

                        <div id="profile-details">

                            <div class="form-group">
                                <label class="col-8 control-label">Name</label>
                                <div class="col-12">
                                    <input class="form-control" type="name" name="name"
                                           v-validate="'required|min:6|max:24'"
                                           :class="{'input': true, 'input-danger': errors.has('name') }"
                                           v-model="name" placeholder="Name">
                                    <span v-show="errors.has('name')" class="help is-danger">{{errors.first('name')}}
                                </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-8 control-label">E-mail</label>
                                <div class="col-12">
                                    <input class="form-control" type="email" name="email" v-model="email"
                                           v-validate="'required|max:50'"
                                           :class="{'input': true, 'input-danger': errors.has('email') }"
                                           placeholder="E-mail">
                                    <span v-show="errors.has('email')" class="help is-danger">{{errors.first('email')}}
                                </span>
                                </div>
                            </div>
                            <div class="form-group" v-if="success">
                                <div class="col-12">
                                    <span class="is-success">Successful change.</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-12">
                                    <button class="btn btn-sm btn-warning" @click="changeDetails">Update
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Loader from "../components/loader";

export default {
    data() {
        return {
            name: "",
            email: "",
            avatar_image: "",
            showModelError: false,
            success: false,
            spinner_loading: false,
        };
    },
    components: {
        Loader
    },
    created() {
        // Get all details
        axios.get("api/admin/profile").then(res => {
            this.avatar_image = res.data[0].image;
            this.name = res.data[0].name;
            this.email = res.data[0].email;
        });
    },
    methods: {
        changeImage() {
            this.spinner_loading = true;
            const formData = new FormData();
            const image = document.getElementById("avatar-img-file");
            formData.append("image", image.files[0]);
            axios.post("api/admin/profile/image", formData).then(
                res => {
                    if (res.data.status === "success") {
                        this.avatar_image = res.data.image;
                        localStorage.setItem("image", res.data.image);                        
                    }
                },
                error => {
                    alertify.logPosition("top right");
                    alertify.error("There is error in back-end");                    
                }
            ).finally(()=>{
                this.spinner_loading = false;
            });
        },
        changeDetails() {
            this.$validator.validateAll().then(result => {
                if (result) {
                    axios
                        .post("api/admin/profile/details", {
                            name: this.name,
                            email: this.email
                        })
                        .then(res => {
                            if (res.data.status === "success") {
                                this.success = true;
                                localStorage.setItem("name", this.name);
                            }
                        });
                }
            });
        },

        LOGOUT() {
            axios.post("admin/logout").then(res => {
                localStorage.removeItem('_a');
                location.reload();
            });
        }
    }
};
</script>
