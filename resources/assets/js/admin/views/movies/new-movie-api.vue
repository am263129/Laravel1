<template>
    <div>
        <div class="k1_manage_table">
            <div class="title p-2">API Upload</div>
            <div class="col-12">


                <div class="row">
                    <div class="col-12 col-lg-6">


                        <div class="form-group">
                            <div class="col-12">
                                <label>Choose Cloud </label>
                            </div>
                            <div class="col-12 cloud-upload">
                                <div class="row">
                                    <div class="col-12 col-sm-6 image" @click="cloud_type = 'local'">
                                        <div class="local-cloud-logo" :class="{active: cloud_type === 'local'}">
                                            <img src="/themes/default/img/local-cloud.svg" alt="local-cloud"
                                                 width="90px">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 image" @click="cloud_type = 'aws'">
                                        <div class="aws-cloud-logo" :class="{active: cloud_type === 'aws'}">
                                            <img src="/themes/default/img/aws-cloud.svg" alt="aws-cloud" width="120px">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>


                        <div v-if="cloud_type">


                            <div class="form-group">
                                <div class="col-12">
                                    <label>Movie ID </label>
                                </div>
                                <div class="col-12">
                                    <input class="form-control" name="id" v-model="id" v-validate="'required|numeric'"
                                           type="text" placeholder="Movie id"/>
                                    <span v-show="errors.has('id')" class="is-danger">{{ errors.first('id') }}</span>

                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-12">
                                    <label for="age">Choose way of upload</label>
                                    <select class="form-control" v-validate="'required'" name="upload_type"
                                            v-model="upload_type_is" id="upload_type">
                                        <option value="transcoding">Upload & Transcoding</option>
                                        <option value="externalUrl"> Upload External Link</option>
                                    </select>
                                    <span v-show="errors.has('upload_type')" class=" is-danger">{{ errors.first('upload_type') }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-12">
                                    <label for="category">Category</label>
                                </div>
                                <div class="col-12">

                                    <select class="form-control" v-validate="'required'" name="category"
                                            v-model="category" id="category">
                                        <option v-for="(item, index) in categories_list.categories" :key="index"
                                                :value="item.id">
                                            {{item.name}}
                                        </option>
                                    </select>
                                    <span v-show="errors.has('category')" class=" is-danger">{{ errors.first('category') }}</span>
                                </div>
                            </div>

                            <transition name="menu-popover">
                                <div class="transcoding-section" v-show="upload_type_is == 'transcoding' ">

                                    <div class="form-group" v-if="upload_type_is == 'transcoding'">
                                        <div class="col-12 col-sm-2">
                                            <label>Movie</label>
                                        </div>
                                        <div class="col-12">
                                            <input type="file" id="video" @change="infoShow('video','videoFileDetails')"
                                                   class="inputfile">
                                            <label id="videoLabel" for="video">Choose a movie video
                                                <br>
                                                <p id="videoFileDetails"></p>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group" v-if="upload_type_is == 'transcoding'">
                                        <div class="col-12">
                                            <label for="presets">Select Presets</label>
                                            <select multiple class="form-control" id="presets" v-validate="'required'"
                                                    v-model="new_presets" style="height:200px;">
                                                <option v-for="(item, index) in presets" :key="index" :value="item">
                                                    {{item.Name}}
                                                </option>
                                            </select>
                                            <span v-show="errors.has('presets')" class=" is-danger">{{ errors.first('presets') }}</span>
                                        </div>
                                    </div>
                                </div>

                            </transition>

                            <div class="form-group" v-if="upload_type_is">
                                <div class="col-12">
                                    <label for="age">Rating system</label>
                                    <select class="form-control" v-validate="'required'" name="age" v-model="age"
                                            id="age">
                                        <option>G</option>
                                        <option>PG</option>
                                        <option>PG-13</option>
                                        <option>R</option>
                                        <option>X</option>
                                    </select>
                                    <span v-show="errors.has('age')" class=" is-danger">{{ errors.first('age') }}</span>
                                </div>
                            </div>

                            <transition name="menu-popover">
                                <div class="external-link" v-show="upload_type_is == 'externalUrl'">

                                    <div class="form-group" v-if="upload_type_is == 'externalUrl'">
                                        <div class="col-12">
                                            <label>Movie
                                                <small class="form-text text-muted">External Link (360p)</small>
                                            </label>
                                        </div>
                                        <div class="col-12">
                                            <input class="form-control" v-model="video_link[0]" type="text"
                                                   placeholder="320P Movie"/>
                                        </div>
                                    </div>

                                    <div class="form-group" v-if="upload_type_is == 'externalUrl'">
                                        <div class="col-12">
                                            <label>Movie
                                                <small class="form-text text-muted">External Link (480p)</small>
                                            </label>
                                        </div>
                                        <div class="col-12">
                                            <input class="form-control" v-model="video_link[1]" type="text"
                                                   placeholder="480P Movie"/>
                                        </div>
                                    </div>


                                    <div class="form-group" v-if="upload_type_is == 'externalUrl'">
                                        <div class="col-12">
                                            <label>Movie
                                                <small class="form-text text-muted">External Link (720p)</small>
                                            </label>
                                        </div>
                                        <div class="col-12">
                                            <input class="form-control" v-model="video_link[2]" type="text"
                                                   placeholder="720P Movie"/>
                                        </div>
                                    </div>


                                    <div class="form-group" v-if="upload_type_is == 'externalUrl'">
                                        <div class="col-12">
                                            <label>Movie
                                                <small class="form-text text-muted">External Link (1080p)</small>
                                            </label>
                                        </div>
                                        <div class="col-12">
                                            <input class="form-control" v-model="video_link[3]" type="text"
                                                   placeholder="1080P Movie"/>
                                        </div>
                                    </div>

                                    <div class="form-group" v-if="upload_type_is == 'externalUrl'">
                                        <div class="col-12">
                                            <label>Movie
                                                <small class="form-text text-muted">External Link (4k)</small>
                                            </label>
                                        </div>
                                        <div class="col-12">
                                            <input class="form-control" v-model="video_link[4]" type="text"
                                                   placeholder="4k Movie"/>
                                        </div>
                                    </div>

                                </div>
                            </transition>


                        </div>


                    </div>


                </div>

                <div class="form-group" v-if="cloud_type">
                    <div class="col-12">
                        <button class="btn btn-md btn-warning" v-if="!disabled_button" @click="MOVIEDB_API(id)">Upload
                        </button>
                        <button class="btn btn-md btn-warning" v-if="disabled_button" disabled>Loading</button>
                    </div>
                </div>


            </div>

        </div>
    </div>
</template>

<script>
    const alertify = require("alertify.js");
    import {mapState} from "vuex";

    export default {
        data() {
            return {
                id: "",
                video_link: [],
                embed: "",
                presets: [
                    {
                        Name: "HLS - 16000Kilorate - 4K",
                        Resolution: "4k",
                        Container: "ts"
                    },
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
                        Name: "HLS - 400Kilorate - 320P",
                        Resolution: "320",
                        Container: "ts"
                    },
                    {
                        Name: "Generic - 4K",
                        Resolution: "4k",
                        Container: "mp4"
                    },
                    {
                        Name: "Generic - 1080P",
                        Resolution: "1080",
                        Container: "mp4"
                    },
                    {
                        Name: "Generic - 720P",
                        Resolution: "720",
                        Container: "mp4"
                    },
                    {
                        Name: "Generic - 480P",
                        Resolution: "480",
                        Container: "mp4"
                    },
                    {
                        Name: "Generic - 320P",
                        Resolution: "320",
                        Container: "mp4"
                    }
                ],
                new_presets: [],
                age: null,
                upload_type_is: false,
                disabled_button: false,
                upload_data: {
                    id: null,
                    api: {
                        show: false,
                        progress: 0,
                        success_message: null,
                        error_message: null
                    },
                    upload: {
                        show: false,
                        progress: 0,
                        success_message: null,
                        error_message: null,
                        message: null
                    },
                    subtitle: {
                        progress: 0,
                        success_message: null,
                        error_message: null
                    }
                },
                uploadFormData: new FormData(),
                apiFormData: new FormData(),
                cloud_type: false,
                category: null
            };
        },

        computed: mapState({
            countUploadData: state => state.event.data_count,
            uploadData: state => state.event.upload_data,
            categories_list: state => state.categories.data
        }),

        created() {
            this.$store.dispatch('GET_CATEGORIES_BY_SORT', 'movie');
        },

        mounted() {
            // Listen for the 'NewBlogPost' event in the 'team.1' private channel
            Echo.channel("progress").listen("EventTrigger", payload => {
                if (payload.progress.progress < 2) {
                    this.$store.commit("UPDATE_PROGRESS_DATA", {
                        key: this.countUploadData,
                        id: payload.progress.tmdb_id,
                        parameter: "message",
                        object: "upload",
                        value: payload.progress.message
                    });
                    this.$store.commit("UPDATE_UPLOAD_PROGRESS_DATA", {
                        key: this.countUploadData,
                        data: this.uploadData[this.countUploadData]
                    });
                }

                this.$store.commit("UPDATE_PROGRESS_DATA", {
                    key: this.countUploadData,
                    id: payload.progress.tmdb_id,
                    parameter: "progress",
                    object: "upload",
                    value: payload.progress.progress
                });

                this.$store.commit("UPDATE_UPLOAD_PROGRESS_DATA", {
                    key: this.countUploadData,
                    data: this.uploadData[this.countUploadData]
                });
            });
        },

        methods: {
            MOVIEDB_API() {
                // Check count of upload data
                this.$store.commit("COUNT_UPLOAD_PROGRESS");

                // Upload video form data
                if (this.upload_type_is == "transcoding") {
                    const video = document.querySelector("#video");
                    this.uploadFormData.append("video", video.files[0]);
                    this.uploadFormData.append(
                        "resolution",
                        JSON.stringify(this.new_presets)
                    );
                } else if (this.upload_type_is == "externalUrl") {
                    if (this.video_link.length > 0) {
                        this.uploadFormData.append(
                            "video_link",
                            JSON.stringify(this.video_link)
                        );
                    } else {
                        this.uploadFormData.append("video_link", "empty");
                    }
                } else {
                    this.uploadFormData.append("embed", this.embed);
                }

                // API form data
                this.apiFormData.append("id", this.id);
                this.apiFormData.append("age", this.age);
                this.apiFormData.append("category", this.category);
                // Cloud Type
                this.apiFormData.append("cloud_type", this.cloud_type);

                // Store data
                this.$validator.validateAll().then(result => {
                    if (result) {
                        this.disabled_button = true;

                        this.upload_data.api.show = true;
                        this.upload_data.id = this.id;

                        this.$store.commit("SET_PROGRESS_DATA", this.upload_data);
                        this.$store.commit(
                            "SET_UPLOAD_PROGRESS",
                            this.uploadData[this.countUploadData]
                        );
                        this.$store.commit("UPDATE_UPLOAD_PROGRESS_DATA", {
                            key: this.countUploadData,
                            data: this.uploadData[this.countUploadData]
                        });

                        axios.post("/api/admin/new/movie/movieapi", this.apiFormData).then(
                            response => {
                                if (response.status === 200) {
                                    this.$store.commit("UPDATE_PROGRESS_DATA", {
                                        key: this.countUploadData,
                                        parameter: "success_message",
                                        object: "api",
                                        value: response.data.message
                                    });
                                    this.$store.commit("UPDATE_PROGRESS_DATA", {
                                        key: this.countUploadData,
                                        parameter: "progress",
                                        object: "api",
                                        value: 100
                                    });
                                    this.$store.commit("UPDATE_UPLOAD_PROGRESS_DATA", {
                                        key: this.countUploadData,
                                        data: this.uploadData[this.countUploadData]
                                    });

                                    this.MOVIEVIDEO_S3(response.data.id);

                                    this.$router.push({
                                        name: "movies-manage"
                                    });
                                }
                            },
                            error => {
                                this.$store.commit("UPDATE_PROGRESS_DATA", {
                                    key: this.countUploadData,
                                    parameter: "error_message",
                                    object: "api",
                                    value: error.response.data.message
                                });
                                this.$store.commit("UPDATE_UPLOAD_PROGRESS_DATA", {
                                    key: this.countUploadData,
                                    data: this.uploadData[this.countUploadData]
                                });

                                this.disabled_button = false;
                            }
                        );
                    }
                });
            },

            MOVIEVIDEO_S3(id) {
                this.uploadFormData.append("id", id);
                this.uploadFormData.append("tmdb_id", this.id);

                // Cloud Type
                this.uploadFormData.append("cloud_type", this.cloud_type);

                this.upload_data.upload.show = true;
                this.$store.commit("UPDATE_PROGRESS_DATA", {
                    key: this.countUploadData,
                    parameter: "show",
                    object: "upload",
                    value: true
                });

                // Progress
                const progress = {
                    headers: {
                        "content-type": "multipart/form-data"
                    },
                    onUploadProgress: progressEvent => {
                        this.upload_data.upload.progress = Math.round(
                            (progressEvent.loaded * 100.0) / progressEvent.total
                        );

                        this.$store.commit("UPDATE_PROGRESS_DATA", {
                            key: this.countUploadData,
                            parameter: "progress",
                            object: "upload",
                            value: this.upload_data.upload.progress
                        });

                        this.$store.commit("UPDATE_UPLOAD_PROGRESS_DATA", {
                            key: this.countUploadData,
                            data: this.uploadData[this.countUploadData]
                        });
                    }
                };
                // Store data
                axios
                    .post("/api/admin/new/movie/movievideo", this.uploadFormData, progress)
                    .then(
                        response => {
                            if (response.status === 200) {
                                this.$store.commit("UPDATE_PROGRESS_DATA", {
                                    key: this.countUploadData,
                                    parameter: "success_message",
                                    object: "upload",
                                    value: response.data.message
                                });

                                this.$store.commit("UPDATE_UPLOAD_PROGRESS_DATA", {
                                    key: this.countUploadData,
                                    data: this.uploadData[this.countUploadData]
                                });

                                alertify.logPosition("top right");
                                alertify.success("Successful upload");
                                setTimeout(() => {
                                    this.showProgress = false;
                                }, 500);
                            }
                        },
                        error => {
                            this.$store.commit("UPDATE_PROGRESS_DATA", {
                                key: this.countUploadData,
                                parameter: "error_message",
                                object: "upload",
                                value: error.response.data.message
                            });
                            this.$store.commit("UPDATE_UPLOAD_PROGRESS_DATA", {
                                key: this.countUploadData,
                                data: this.uploadData[this.countUploadData]
                            });
                        }
                    );
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
            }
        }
    };
</script>
