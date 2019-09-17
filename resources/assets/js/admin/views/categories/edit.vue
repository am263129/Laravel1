<template>
    <div class="create-category">

        <div class="spinner-load" v-if="spinner">

            <Loader></Loader>

        </div>

        <!-- END spinner load -->

        <div class="k1_manage_table" v-if="!spinner">
            <div class="m-2 p-2" id="manage-panel">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <router-link class="btn btn-md btn-warning" role="button" :to="{name: 'categories-categories'}">
                            Manage
                        </router-link>
                    </li>
                </ul>
            </div>

            <div class="col-12">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <div class="col-12">
                                <label>Name</label>
                            </div>
                            <div class="col-12">
                                <input class="form-control" v-model="data.name" type="text" placeholder="Category name"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-12">
                                <label>Image</label>
                            </div>

                            <div class="col-12">
                                <input type="file" id="image" name="image" v-validate="'image'"
                                       @change="readImage('image','imageFileImage')"
                                       class="inputfile">
                                <label id="backdropLabel" for="image">Choose a Image
                                    <br>
                                </label>
                                <img :src="md_categories + data.image" id="imageFileImage" width="200">
                                <span v-show="errors.has('backdrop')"
                                      class=" is-danger">{{ errors.first('image')}}</span>
                            </div>


                        </div>

                        <div class="form-group">
                            <div class="col-12">
                                <label for="category-section">Category section</label>
                                <select class="form-control" id="category-section" v-model="category_section">
                                    <option value="movie">Movies
                                    </option>
                                    <option value="videosong">Video Songs
                                    </option>                                    
                                    <option value="live">Live TV
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-12">
                        <button class="btn btn-md btn-warning" @click="UPDATE()" v-if="!loading">Update</button>
                        <button class="btn btn-md btn-warning" disabled v-else><i  class="fa fa-circle-o-notch fa-spin"></i>  Loading</button>

                    </div>
                </div>

            </div>

        </div>
    </div>
</template>

<script>
    const alertify = require("alertify.js");
    import Loader from "../components/loader";
    export default {
        data() {
            return {
                data: [],
                name: '',
                category_section: '',
                loading: false,
                spinner: true
            };
        },

            components: {
        Loader
    },
        created() {
            axios
                .get("/api/admin/get/category/" + this.$route.params.id)
                .then(resposne => {
                    if (resposne.status === 200) {
                        this.data = resposne.data.data;
                        this.category_section = resposne.data.data.kind;
                        this.spinner = false;
                    }
                });
        },

        methods: {
            UPDATE() {
                const image = document.querySelector("#image");
                const formData = new FormData();
                formData.append("name", this.data.name);
                formData.append("kind", this.category_section);
                if(image.files[0] !== undefined) {
                    formData.append("image", image.files[0]);
                }
                // Progress
                this.loading = true;
                const progress = {
                    headers: {
                        "content-type": "multipart/form-data"
                    }
                };
                // Store data
                this.$validator.validateAll().then(result => {
                    if (result) {
                        axios.post("/api/admin/update/category/" + this.$route.params.id, formData, progress).then(
                            response => {
                                if (response.status === 200) {
                                    this.loading = false;
                                    alertify.logPosition('top right');
                                    alertify.success(response.data.message);
                                    this.$router.push({name: 'categories-manage'})
                                }
                            },
                            error => {
                                this.error_message_api = error.response.data.message;
                                alertify.logPosition('top right');
                                alertify.error(error.response.data.message);
                                setTimeout(() => {
                                    this.loading = false;
                                }, 4000);

                            });
                    }
                });
            },


            infoShow(idFiles, idDetails) {
                var x = document.getElementById(idFiles);
                var txt = "";
                if ("files" in x) {
                    for (var i = 0; i < x.files.length; i++) {
                        txt += "<br><strong>" + (i + 1) + ". file</strong><br>";
                        var file = x.files[i];
                        if ("name" in file) {
                            txt += "name: " + file.name + "<br>";
                        }
                        if ("size" in file) {
                            if (file.size < 1048576)
                                txt += "size:" + (file.size / 1024).toFixed(3) + "KB<br>";
                        }
                    }
                }
                document.getElementById(idDetails).innerHTML = txt;
            },

            readImage(id, outImage) {
                var img = document.getElementById(id);
                var tgt = img.target || window.event.srcElement,
                    files = tgt.files;

                // FileReader support
                if (FileReader && files && files.length) {
                    var fr = new FileReader();
                    fr.onload = function () {
                        var srcImage = document.getElementById(outImage);
                        srcImage.style.display = "block";
                        srcImage.src = fr.result;
                    };
                    fr.readAsDataURL(files[0]);
                } else {
                    // Not supported
                    // fallback -- perhaps submit the input to an iframe and temporarily store
                    // them on the server until the user's session ends.
                }
            },
        }
    };
</script>
