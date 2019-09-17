<template>
    <div>
        <div class="spinner-load" v-if="spinner_loading">
            <Loader></Loader>
        </div>

        <div class="k1_manage_table" v-if="!spinner_loading">
            <h5 class="title p-2">{{ data.data.name }}</h5>
            <div class="m-2 p-2" id="manage-panel">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <router-link class="btn btn-md btn-warning" role="button" :to="{name: 'tv-manage'}">Manage
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link class="btn btn-md btn-warning" role="button" :to="{name: 'tv-create'}">Create
                        </router-link>
                    </li>
                </ul>
            </div>

            <div class="col-12">

                <div class="row">
                    <div class="col-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <div class="col-12 col-sm-12">
                                <label>Name</label>
                            </div>
                            <div class="col-12 col-sm-12">
                                <input class="form-control" v-model="data.data.name" type="text"
                                       placeholder="Channel name"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-12 col-sm-4">
                                <label>Logo</label>
                            </div>
                            <div class="col-12 col-sm-12">
                                <input type="file" id="image" name="image" v-validate="'image'"
                                       @change="readImage('image','imageFileImage')" class="inputfile">
                                <label id="backdropLabel" for="image">Choose a backdrop
                                    <br>
                                </label>
                                <img :src="'/storage/posters/' + data.data.image" id="imageFileImage" width="40%">
                                <span v-show="errors.has('image')" class="is-danger">{{ errors.first('image')}}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-12 col-sm-12">
                                <label>M3U8 OR RTMP
                                    <small class="form-text text-muted">External Link</small>
                                </label>
                            </div>
                            <div class="col-12 col-sm-12">
                                <input class="form-control" v-model="data.data.streaming_url" type="text"
                                       placeholder="HLS Tv Link"/>
                            </div>
                        </div>



                        <div class="form-group">

                            <div class="col-12">

                                <label for="category">Category</label>

                            </div>

                            <div class="col-12">

                                <select class="form-control" v-validate="'required'" name="category"
                                        v-model="data.data.category" id="category">

                                    <option v-for="(item, index) in categories_list.categories" :key="index"
                                            :value="item.id">

                                        {{item.name}}

                                    </option>

                                </select>

                                <span v-show="errors.has('category')"
                                      class=" is-danger">{{ errors.first('category') }}</span>

                            </div>

                        </div>


                        <div class="form-group">
                            <div class="col-12 col-sm-12">
                                <button class="btn btn-md btn-warning" @click="CHANNEL_DETAILS()">Upload</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="upload-modal" v-if="showProgress">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-body p-4">
                            <div class="channel" v-if="channel_details">
                                <div class="progress mt-2">
                                    <div v-if="percentChannelUpload !== 100" class="progress-bar" role="progressbar"
                                         :style="{width: percentChannelUpload + '%', height:'6px' }"
                                         :aria-valuenow="percentChannelUpload" aria-valuemin="0"
                                         aria-valuemax="100"></div>
                                    <div v-else>
                                        <i id="btn-progress"></i> Prepare
                                    </div>
                                </div>
                                <p class="is-danger">{{error_message_details}}</p>
                                <p class="is-success">{{success_message_details}}</p>
                                <hr>
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
    import Loader from "../components/loader";
    import {mapState} from "vuex";

    export default {
        data() {
            return {
                data: [],
                transcoding: "",
                genre: "",
                presets: [
                    {
                        Name: "HLS - 4000Kilorate - 1080P",
                        Resolution: "1080",
                        Container: "ts"
                    },
                    {
                        Name: "HLS - 2500Kilorate - 720P",
                        Resolution: "720",
                        Container: "ts"
                    },
                    {
                        Name: "HLS - 700Kilorate - 480P",
                        Resolution: "480",
                        Container: "ts"
                    },
                    {
                        Name: "HLS - 400Kilorate - 360P",
                        Resolution: "360",
                        Container: "ts"
                    }
                ],
                new_presets: "",
                showProgress: false,
                channel_details: false,
                percentChannelUpload: 0,
                percentVideoUpload: 0,
                error_message_details: "",
                success_message_details: "",
                error_message_video: "",
                success_message_video: "",
                spinner_loading: true
            };
        },
        components: {
            Loader
        },

        computed: mapState({
            categories_list: state => state.categories.data,
        }),


        created() {

            axios
                .get("/api/admin/get/channel/" + this.$route.params.id)
                .then(resposne => {
                    if (resposne.status === 200) {
                        this.data = resposne.data;
                        this.spinner_loading = false;
                    }
                });

            this.$store.dispatch('GET_CATEGORIES_BY_SORT', 'live');

        },
        methods: {
            CHANNEL_DETAILS() {
                const image = document.querySelector("#image");
                const formData = new FormData();
                formData.append("id", this.data.data.id);
                formData.append("name", this.data.data.name);
                formData.append("image", image.files[0]);
                formData.append("genre", this.data.data.genre);
                formData.append("resolution", this.data.data.streaming_resolutions);
                formData.append("transcoding", this.data.data.streaming_transcoding);
                formData.append("iptv_url", this.data.data.streaming_url);
                formData.append("category", this.data.data.category);

                // Progress
                this.showProgress = true;
                this.channel_details = true;
                const progress = {
                    headers: {
                        "content-type": "multipart/form-data"
                    },
                    onUploadProgress: progressEvent => {
                        this.percentChannelUpload = Math.round(
                            (progressEvent.loaded * 100.0) / progressEvent.total
                        );
                    }
                };
                // Store data
                axios.post("/api/admin/update/channel", formData, progress).then(
                    response => {
                        if (response.status === 200) {
                            this.success_message_api = response.data.message;
                            this.percentChannelUpload = 0;
                            this.showProgress = false;
                            alertify.logPosition("top right");
                            alertify.success(response.data.message);
                        }
                    },
                    error => {
                        this.error_message_api = error.response.data.message;
                    }
                );
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
                        srcImage.src = fr.result;
                    };
                    fr.readAsDataURL(files[0]);
                } else {
                    // Not supported
                    // fallback -- perhaps submit the input to an iframe and temporarily store
                    // them on the server until the user's session ends.
                }
            }
        }
    };
</script>
