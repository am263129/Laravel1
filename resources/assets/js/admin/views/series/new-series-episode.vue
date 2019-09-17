<template>
    <div>
        <div class="k1_manage_table">


            <div class="m-2 p-3" id="manage-panel">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <router-link class="btn btn-md btn-warning"  role="button"
                                     :to="{name:'series_manage_id', params:{id:this.$route.params.id}}">Manage
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link class="btn btn-md btn-warning ml-1" role="button"
                                     :to="{name:'new_series_episode', params:{id:this.$route.params.id}}">Episode API
                        </router-link>
                    </li>

                    <li class="nav-item">
                        <router-link class="btn btn-md btn-warning ml-1" role="button"
                                     :to="{name:'new_series_episode_custome', params:{id:this.$route.params.id}}">
                            Episode custom
                        </router-link>
                    </li>

                </ul>
            </div>


            <!-- END Manage Panel -->

            <div class="col-12">
                <div class="row">
                    <div class="col-12 col-lg-6">
                        <div class="form-group">
                            <div class="col-12">
                                <div class="alert alert-info" role="alert">
                                    <strong>Important ! </strong> <br>
                                   <ol>
                                       <li>
                                           Re-name the video to same episode number and you can upload multiple episode in same time. <br>
                                       </li>
                                       <li> You can upload one episode through External Link Re-stream.
                                       </li>
                                   </ol>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-12">
                                <label>Choose Cloud </label>
                            </div>
                            <div class="col-12 cloud-upload">
                                <div class="row">
                                    <div class="col-12 col-sm-6 image" @click="cloud_type = 'local'">
                                        <div class="local-cloud-logo"  :class="{active: cloud_type === 'local'}">
                                            <img src="/themes/default/img/local-cloud.svg" alt="local-cloud" width="90px">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 image"  @click="cloud_type = 'aws'">
                                        <div class="aws-cloud-logo" :class="{active: cloud_type === 'aws'}">
                                            <img src="/themes/default/img/aws-cloud.svg" alt="aws-cloud" width="120px">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>


                        <div v-if="cloud_type">

                        <div class="col-12 col-sm-12 ">
                            <div class="form-group">
                                <label for="Session">Session</label>
                                <select class="form-control" v-model="season" id="Session">
                                    <option v-for="(list, index) in listSession" :key="index">{{list}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-sm-12">
                            <div class="form-group">
                                <label for="Episode">Episode</label>
                                <select class="form-control" v-model="episode" id="Episode" multiple>
                                    <option v-for="(list, index) in listEpiosde" :key="index">{{list}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-12">
                                <label>Choose way of upload</label>
                                <br>
                                <small class="text-muted">If you upload External Link, choose 1 episode</small>
                                <select class="form-control" v-validate="'required'" name="upload_type"
                                        v-model="upload_type_is" id="upload_type">
                                    <option value="transcoding">Upload & Transcoding</option>
                                    <option value="externalUrl"> Upload External Link</option>
                                </select>
                                <span v-show="errors.has('upload_type')" class=" is-danger">{{ errors.first('upload_type') }}</span>
                            </div>
                        </div>

                        <transition name="menu-popover">
                            <div class="transcoding-section" v-show="upload_type_is == 'transcoding' ">

                                <div class="form-group" v-if="upload_type_is == 'transcoding'">
                                    <div class="col-12 col-sm-2">
                                        <label>Episode</label>
                                    </div>
                                    <div class="col-12">
                                        <input type="file" id="video" @change="infoShow('video','videoFileDetails')"
                                               class="inputfile">
                                        <label id="videoLabel" for="video">Choose a Episode video
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


                        <transition name="menu-popover">
                            <div class="external-link" v-show="upload_type_is == 'externalUrl'">

                                <div class="external-link" v-show="upload_type_is == 'externalUrl'">

                                    <div class="form-group" v-if="upload_type_is == 'externalUrl'">
                                        <div class="col-12">
                                            <label>Episode
                                                <small class="form-text text-muted">External Link (360p)</small>
                                            </label>
                                        </div>
                                        <div class="col-12">
                                            <input class="form-control" v-model="video_link[0]" type="text"
                                                   placeholder="320P Episode"/>
                                        </div>
                                    </div>

                                    <div class="form-group" v-if="upload_type_is == 'externalUrl'">
                                        <div class="col-12">
                                            <label>Episode
                                                <small class="form-text text-muted">External Link (480p)</small>
                                            </label>
                                        </div>
                                        <div class="col-12">
                                            <input class="form-control" v-model="video_link[1]" type="text"
                                                   placeholder="480P Episode"/>
                                        </div>
                                    </div>


                                    <div class="form-group" v-if="upload_type_is == 'externalUrl'">
                                        <div class="col-12">
                                            <label>Episode
                                                <small class="form-text text-muted">External Link (720p)</small>
                                            </label>
                                        </div>
                                        <div class="col-12">
                                            <input class="form-control" v-model="video_link[2]" type="text"
                                                   placeholder="720P Episode"/>
                                        </div>
                                    </div>


                                    <div class="form-group" v-if="upload_type_is == 'externalUrl'">
                                        <div class="col-12">
                                            <label>Episode
                                                <small class="form-text text-muted">External Link (1080p)</small>
                                            </label>
                                        </div>
                                        <div class="col-12">
                                            <input class="form-control" v-model="video_link[3]" type="text"
                                                   placeholder="1080P Episode"/>
                                        </div>
                                    </div>

                                    <div class="form-group" v-if="upload_type_is == 'externalUrl'">
                                        <div class="col-12">
                                            <label>Episode
                                                <small class="form-text text-muted">External Link (4k)</small>
                                            </label>
                                        </div>
                                        <div class="col-12">
                                            <input class="form-control" v-model="video_link[4]" type="text"
                                                   placeholder="4k Episode"/>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </transition>


                        <div class="col-12 col-sm-6 mt-2">
                            <div class="form-group">
                                <button class="btn btn-md btn-warning" v-if="!disabled_button" @click="MOVIEDB_API()">Upload
                                </button>
                                <button class="btn btn-md btn-warning" v-if="disabled_button" disabled>Loading</button>
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
    import {mapState} from "vuex";

    const alertify = require("alertify.js");
    export default {
        data() {
            return {
                id: "",
                video_link: [],
                embed: "",
                presets: [
                    {
                        'Name': 'HLS - 16000Kilorate - 4K',
                        'Resolution': '4k',
                        'Container': 'ts'
                    },
                    {
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
                        'Name': 'HLS - 400Kilorate - 320P',
                        'Resolution': '320',
                        'Container': 'ts'
                    },
                    {
                        'Name': 'Generic - 4K',
                        'Resolution': '4k',
                        'Container': 'mp4'
                    },
                    {
                        'Name': 'Generic - 1080P',
                        'Resolution': '1080',
                        'Container': 'mp4'
                    },
                    {
                        'Name': 'Generic - 720P',
                        'Resolution': '720',
                        'Container': 'mp4'
                    },
                    {
                        'Name': 'Generic - 480P',
                        'Resolution': '480',
                        'Container': 'mp4'
                    },
                    {
                        'Name': 'Generic - 320P',
                        'Resolution': '320',
                        'Container': 'mp4'
                    }
                ],
                new_presets: [],
                age: null,
                episode_name: "",
                season: "",
                episode: [],
                listSession: [],
                listEpiosde: [],
                upload_type_is: false,
                disabled_button: false,
                upload_data: {
                    id: null,
                    api: {
                        show: false,
                        progress: 0,
                        success_message: null,
                        error_message: null,
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
                        error_message: null,
                    }
                },
                uploadFormData: new FormData(),
                apiFormData: new FormData(),
                cloud_type: false
            };
        },
        computed: mapState({
            data: state => state.series.data,
            data_search: state => state.series.data_search,
            button_loading: state => state.series.button_loading,
            spinner_loading: state => state.series.spinner_loading,
            countUploadData: state => state.event.data_count,
            uploadData: state => state.event.upload_data
        }),


        created() {
            for (var i = 1; i < 50; i++) {
                this.listSession.push(i);
            }
            for (var i = 1; i < 2000; i++) {
                this.listEpiosde.push(i);
            }
        },

        mounted() {
            // Listen for the 'NewBlogPost' event in the 'team.1' private channel
            Echo.channel('progress').listen('EventTrigger', (payload) => {
                if(payload.progress.progress < 2) {
                    this.$store.commit('UPDATE_PROGRESS_DATA', {
                        key: this.countUploadData,
                        id: payload.progress.tmdb_id,
                        parameter: 'message',
                        object: 'upload',
                        value: payload.progress.message
                    });
                    this.$store.commit('UPDATE_UPLOAD_PROGRESS_DATA', {
                        key: this.countUploadData,
                        data: this.uploadData[this.countUploadData]
                    });
                }

                this.$store.commit('UPDATE_PROGRESS_DATA', {
                    key: this.countUploadData,
                    id: payload.progress.tmdb_id,
                    parameter: 'progress',
                    object: 'upload',
                    value: payload.progress.progress
                });

                this.$store.commit('UPDATE_UPLOAD_PROGRESS_DATA', {
                    key: this.countUploadData,
                    data: this.uploadData[this.countUploadData]
                });

            });
        },

        methods: {
            MOVIEDB_API() {
                this.apiFormData.append("season_number", this.season);
                this.apiFormData.append("episode_number", JSON.stringify(this.episode));
                this.apiFormData.append("series_id", this.$route.params.id);

                // Cloud Type
                this.apiFormData.append("cloud_type", this.cloud_type);

                // Check count of upload data
                this.$store.commit('COUNT_UPLOAD_PROGRESS');

                // Upload video form data
                if (this.upload_type_is == 'transcoding') {

                    const video = document.querySelector("#video");
                    for (let i = 0; i < video.files.length; i++) {
                        this.uploadFormData.append("video[]", video.files[i]);
                    }
                    this.uploadFormData.append("resolution", JSON.stringify(this.new_presets));

                } else if (this.upload_type_is == 'externalUrl') {

                    if (this.video_link.length > 0) {
                        this.uploadFormData.append("video_link", JSON.stringify(this.video_link));
                    } else {
                        this.uploadFormData.append("video_link", "empty");
                    }

                } else {
                    this.uploadFormData.append("embed", this.embed);
                }




                // Store data
                this.$validator.validateAll().then(result => {
                    if (result) {

                        this.disabled_button = true;
                        this.upload_data.api.show = true;
                        this.upload_data.id = this.$route.params.id;

                        this.$store.commit('SET_PROGRESS_DATA', this.upload_data);
                        this.$store.commit('SET_UPLOAD_PROGRESS', this.uploadData[this.countUploadData]);
                        this.$store.commit('UPDATE_UPLOAD_PROGRESS_DATA', {
                            key: this.countUploadData,
                            data: this.uploadData[this.countUploadData]
                        });


                        axios
                            .post("/api/admin/new/series/episode/details", this.apiFormData)
                            .then(
                                response => {
                                    if (response.status === 200) {
                                        this.$store.commit('UPDATE_PROGRESS_DATA', {
                                            key: this.countUploadData,
                                            parameter: 'success_message',
                                            object: 'api',
                                            value: response.data.message
                                        });
                                        this.$store.commit('UPDATE_PROGRESS_DATA', {
                                            key: this.countUploadData,
                                            parameter: 'progress',
                                            object: 'api',
                                            value: 100
                                        });
                                        this.$store.commit('UPDATE_UPLOAD_PROGRESS_DATA', {
                                            key: this.countUploadData,
                                            data: this.uploadData[this.countUploadData]
                                        });

                                        this.MOVIEVIDEO_S3({id: response.data.id, series_name: response.data.series_name});

                                        this.$router.back()
                                    }
                                },
                                error => {
                                    this.disabled_button = false;
                                    this.$store.commit('UPDATE_PROGRESS_DATA', {
                                        key: this.countUploadData,
                                        parameter: 'error_message',
                                        object: 'api',
                                        value: error.response.data.message
                                    });
                                    this.$store.commit('UPDATE_UPLOAD_PROGRESS_DATA', {
                                        key: this.countUploadData,
                                        data: this.uploadData[this.countUploadData]
                                    });

                                }
                            );
                    }
                });
            },

            MOVIEVIDEO_S3(data) {
                this.uploadFormData.append("id", JSON.stringify(data.id));
                this.uploadFormData.append("series_name", data.series_name);
                this.uploadFormData.append("series_id", this.$route.params.id);
                this.uploadFormData.append("tmdb_id", this.$route.params.id);
                this.upload_data.upload.show = true;
                // Cloud Type
                this.uploadFormData.append("cloud_type", this.cloud_type);

                this.$store.commit('UPDATE_PROGRESS_DATA', {
                    key: this.countUploadData,
                    parameter: 'show',
                    object: 'upload',
                    value: true,
                });


                const progress = {
                    headers: {
                        "content-type": "multipart/form-data"
                    },

                    onUploadProgress: progressEvent => {

                        this.upload_data.upload.progress = Math.round(
                            progressEvent.loaded * 100.0 / progressEvent.total
                        );

                        this.$store.commit('UPDATE_PROGRESS_DATA', {
                            key: this.countUploadData,
                            parameter: 'progress',
                            object: 'upload',
                            value: this.upload_data.upload.progress
                        });

                        this.$store.commit('UPDATE_UPLOAD_PROGRESS_DATA', {
                            key: this.countUploadData,
                            data: this.uploadData[this.countUploadData]
                        })

                    }
                };
                // Store data
                axios
                    .post("/api/admin/new/series/episode/video", this.uploadFormData, progress)
                    .then(
                        response => {
                            if (response.status === 200) {
                                this.$store.commit('UPDATE_PROGRESS_DATA', {
                                    key: this.countUploadData,
                                    parameter: 'success_message',
                                    object: 'upload',
                                    value: response.data.message
                                });

                                this.$store.commit('UPDATE_UPLOAD_PROGRESS_DATA', {
                                    key: this.countUploadData,
                                    data: this.uploadData[this.countUploadData]
                                });

                                alertify.logPosition("top right");
                                alertify.success("Successful upload");
                            }
                        },

                        error => {
                            this.disabled_button = false;
                            this.$store.commit('UPDATE_PROGRESS_DATA', {
                                key: this.countUploadData,
                                parameter: 'error_message',
                                object: 'upload',
                                value: error.response.data.message
                            });
                            this.$store.commit('UPDATE_UPLOAD_PROGRESS_DATA', {
                                key: this.countUploadData,
                                data: this.uploadData[this.countUploadData]
                            })
                        }
                    );
            },

            // Show file name and size
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
