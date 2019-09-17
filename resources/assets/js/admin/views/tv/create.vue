<template>
    <div>

        <div class="k1_manage_table">
            <div class="m-2 p-2" id="manage-panel">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <router-link class="btn btn-md btn-warning" role="button" :to="{name: 'tv-manage'}">Manage</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link class="btn btn-md btn-warning" role="button" :to="{name: 'tv-create'}">Create</router-link>
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
                                <input class="form-control" v-model="name" type="text" placeholder="Channel name" />
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-12">
                                <label>Logo</label>
                            </div>

                            <div class="col-12">
                                <input type="file" id="backdrop" name="backdrop" v-validate="'required|image'"
                                       @change="readImage('backdrop','imageFileImage')"
                                       class="inputfile">
                                <label id="backdropLabel" for="backdrop">Choose a backdrop
                                    <br>
                                </label>
                                <img src="" id="imageFileImage" width="200" style="display: none;">
                                <span v-show="errors.has('backdrop')"
                                      class=" is-danger">{{ errors.first('backdrop')}}</span>
                            </div>


                        </div>


                        <div class="form-group">
                            <div class="col-12">
                                <label for="category">Category</label>
                            </div>

                            <div class="col-12">
                                <select class="form-control" v-validate="'required'" name="category" v-model="category" id="category">
                                    <option v-for="(item, index) in categories_list.categories" :key="index" :value="item.id">
                                        {{item.name}}
                                    </option>
                                </select>
                                <span v-show="errors.has('category')" class=" is-danger">{{ errors.first('category') }}</span>

                            </div>

                        </div>
                        <div class="form-group">
                            <div class="col-12">
                                <label>M3U8 OR RTMP
                                    <small class="form-text text-muted">External Link</small>
                                </label>
                            </div>
                            <div class="col-12">
                                <input class="form-control" v-model="external_link" type="text" placeholder="HLS Tv Link" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-12">
                        <button class="btn btn-md btn-warning" @click="CHANNEL_DETAILS()">Upload</button>
                    </div>
                </div>

                <div class="upload-modal" v-if="showProgress">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-body p-4">

                                <div class="details_progress" v-if="details_progress">
                                    <div class="progress mt-2">
                                        <div v-if="percent_details_progress !== 100" class="progress-bar" role="progressbar" :style="{width: percent_details_progress + '%' }"
                                             :aria-valuenow="percent_details_progress" aria-valuemin="0" aria-valuemax="100">{{percent_details_progress}}</div>
                                        <div v-else>
                                            <i id="btn-progress"></i> Prepare
                                        </div>
                                    </div>
                                    <p class="is-danger">{{error_message_api}}</p>
                                    <p class="is-success">{{success_message_api}}</p>
                                    <hr>
                                </div>

                                <!-- END moviepai -->
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
    import { mapState } from "vuex";

    export default {
        data() {
            return {
                name: '',
                external_link: '',
                genre: '',
                showProgress: false,
                percent_details_progress: 0,
                percentChannelUpload: 0,
                details_progress: false,
                video_upload_progress: false,
                upload_status: 'Upload video',
                upload_message: '',
                error_message_api: '',
                success_message_api: '',
                presets: [{
                    'Name': 'HLS - 4000Kilorate - 1080P',
                    'Resolution': '1080',
                    'Container': 'ts'
                },
                    {
                        'Name': 'HLS - 2500Kilorate - 720P',
                        'Resolution': '720',
                        'Container': 'ts'
                    },
                    {
                        'Name': 'HLS - 700Kilorate - 480P',
                        'Resolution': '480',
                        'Container': 'ts'
                    },
                    {
                        'Name': 'HLS - 400Kilorate - 360P',
                        'Resolution': '360',
                        'Container': 'ts'
                    }
                ],
                new_presets: '',
                category: null

            };
        },

        mounted() {
            // Listen for the 'NewBlogPost' event in the 'team.1' private channel
            Echo.channel('progress').listen('EventTrigger', (payload) => {
                this.upload_status = 'FFmpegTranscoding Video';
                this.percentChannelUpload = payload.progress.progress;
                this.upload_message = payload.progress.message;
            });
        },
        computed: mapState({
            categories_list: state => state.categories.data,
        }),

        created() {
            this.$store.dispatch('GET_CATEGORIES_BY_SORT', 'live');
        },

        methods: {
            CHANNEL_DETAILS(name) {
                const image = document.querySelector("#backdrop");
                const formData = new FormData();
                formData.append("name", this.name);
                formData.append("genre", this.genre);
                formData.append("category", this.category);
                formData.append("image", image.files[0]);
                formData.append("iptv_url", this.external_link);
                // Progress
                this.showProgress = true;
                this.details_progress = true;
                const progress = {
                    headers: {
                        "content-type": "multipart/form-data"
                    },
                    onUploadProgress: progressEvent => {
                        this.percent_details_progress = Math.round(
                            progressEvent.loaded * 100.0 / progressEvent.total
                        );
                    }
                };
                // Store data
                this.$validator.validateAll().then(result => {
                    if (result) {
                        axios.post("/api/admin/new/channel/details", formData, progress).then(
                            response => {
                                if (response.status === 200) {
                                    this.success_message_api = response.data.message;
                                    this.showProgress = false;
                                    this.percent_details_progress = 100;
                                    this.$router.push({name: 'tv-manage'})
                                }
                            },
                            error => {
                                this.error_message_api = error.response.data.message;
                                setTimeout(() =>{
                                    this.showProgress = false;
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
