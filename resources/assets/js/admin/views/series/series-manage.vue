<template>
    <div>

        <div class="spinner-load" v-if="spinner_loading">
            <Loader></Loader>
        </div>

        <!-- END spinner load -->
        <div class="k1_manage_table" v-else>

            <div class="m-2 p-3" id="manage-panel">

                <ul class="nav nav-tabs">

                    <li class="nav-item">
                        <router-link class="btn btn-md btn-warning" role="button"
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

                    <li class="col offset-md-2 offset-lg-5">
                        <div id="search">
                            <input class="form-control mr-sm-2" type="text" placeholder="Search">
                        </div>
                    </li>
                </ul>

                <div class="mt-3" v-if="showGroupButton">
                    <div class="button-group">
                        <button class="btn btn-sm btn-warning" @click="AVAILABLE_IT()">Available / Unavailable</button>
                        <button class="btn btn-sm btn-warning" @click="DELETE()" v-if="!button_delete_loading">Delete
                        </button>
                        <button class="btn btn-sm btn-warning" v-if="button_delete_loading">Loading</button>
                    </div>
                </div>

            </div>

            <!-- END Control Panel -->


            <div v-if="data.season !== null ">

                <div class="subtitles-modal">
                    <div class="modal fade" id="GetSubtitleModal" tabindex="-1" role="dialog"
                         aria-labelledby="GetSubtitleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="GetSubtitleModalLabel">Subtitles</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">

                                    <div class="spinner-load" v-if="subtitle_spinner_loading">

                                        <Loader></Loader>

                                    </div>
                                    <!-- END Spinner -->

                                    <div class="col-12">
                                        <input type="file" id="subtitle" name="subtitle" multiple size="10"
                                               @change="SHOW_FILES_INFO('subtitle','subtitleFileDetails')"
                                               class="inputfile">
                                        <label id="subtitleLabel" for="subtitle">Add New Subtitles
                                            <br>
                                            <small>The name most be of the same language exm: English.srt</small>
                                            <p id="subtitleFileDetails"></p>
                                        </label>
                                    </div>

                                    <div class="subtitle-progress mt-1 p-4" v-if="subtitle_video">
                                        <div class="row">
                                            <div class="svg ml-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px"
                                              viewBox="0 0 384 384" style="enable-background:new 0 0 384 384;" xml:space="preserve" width="50px" class="">
                                              <g>
                                                <g>
                                                  <g>
                                                    <path d="M341.333,21.333H42.667C19.093,21.333,0,40.427,0,64v256c0,23.573,19.093,42.667,42.667,42.667h298.667    C364.907,362.667,384,343.573,384,320V64C384,40.427,364.907,21.333,341.333,21.333z M170.667,170.667h-32V160H96v64h42.667    v-10.667h32v21.333c0,11.733-9.493,21.333-21.333,21.333h-64C73.493,256,64,246.4,64,234.667v-85.333    C64,137.6,73.493,128,85.333,128h64c11.84,0,21.333,9.6,21.333,21.333V170.667z M320,170.667h-32V160h-42.667v64H288v-10.667h32    v21.333C320,246.4,310.507,256,298.667,256h-64c-11.84,0-21.333-9.6-21.333-21.333v-85.333c0-11.733,9.493-21.333,21.333-21.333    h64c11.84,0,21.333,9.6,21.333,21.333V170.667z"
                                                      data-original="#000000" class="active-path" data-old_color="#00A6F9" fill="#00A6F9" />
                                                  </g>
                                                </g>
                                              </g>
                                            </svg>

                                            </div>
                                            <div class="title">
                                                <h6>
                                                    <strong>Upload and convert srt </strong>
                                                </h6>
                                            </div>
                                        </div>
                                        <div class="progress">
                                            <div v-if="percentSubtitleUpload !== 100" class="progress-bar"
                                                 role="progressbar"
                                                 :style="{width: percentSubtitleUpload + '%', height:'6px' }"
                                                 :aria-valuenow="percentSubtitleUpload" aria-valuemin="0"
                                                 aria-valuemax="100"></div>
                                            <div v-else>
                                                <i id="btn-progress"></i> Prepare
                                            </div>
                                        </div>
                                        <p class="is-danger">{{error_message_subtitle}}</p>
                                        <p class="is-success">{{success_message_subtitle}}</p>
                                    </div>

                                    <hr>

                                    <!-- END Subttile Upload -->

                                    <div class="table-responsive" v-if="subtitles.subtitles !== null">
                                        <div class="table table-hover">
                                            <thead>
                                            <th>Name</th>
                                            <th>Language</th>
                                            <th>Created date</th>
                                            <th>Control</th>
                                            </thead>
                                            <tbody>
                                            <tr v-for="(item, index) in subtitles.subtitles" :key="index">
                                                <td>{{item.name}}</td>
                                                <td>{{item.language }}</td>
                                                <td>{{item.created_at}}</td>
                                                <td>
                                                    <div class="btn-group" role="group">

                                                        <button v-if="!button_loading"
                                                                class="btn btn-sm btn-danger btn-sm mr-2"
                                                                @click="DELETE_SUBTITLE(item.id,index)">Delete
                                                        </button>
                                                        <button v-if="button_loading === item.id"
                                                                class="btn btn-sm btn-danger btn-sm mr-2" disabled><i
                                                                class="fa fa-circle-o-notch fa-spin"></i> Loading
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </div>
                                    </div>

                                    <div v-else class="text-center">
                                        <h4>There is no subtitles</h4>
                                    </div>
                                </div>

                                <!-- END Table -->

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-sm btn-warning"
                                            :class="{disabled: disable_button}"
                                            @click="UPLOAD_SUBTITLE()">Upload Subtitle
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="table-responsive mt-2">
                    <div class="table table-hover">
                        <thead>
                        <th style="text-align: center;"><input type="checkbox" id="select-all"
                                                               @click="ADD_ALL_TO_MULITSELECT()"></th>
                        <th>Name</th>
                        <th>Session</th>
                        <th>Epiosde</th>
                        <th>Cloud</th>
                        <th>Status</th>
                        <th>Created date</th>
                        <th>Updated date</th>
                        <th>Control</th>
                        </thead>
                        <tbody>
                        <tr v-for="(item, index) in data.season.data" :key="index">
                            <td style="text-align: center;"><input type="checkbox" :id="item.id"
                                                                   @click="ADD_TO_MULITSELECT(item.id, index)"></td>
                            <td>{{item.name}}</td>
                            <td>Session {{item.season_number}}</td>
                            <td>Episode {{item.episode_number}}</td>
                            <td v-if="item.cloud == 'local'">
                                Local Server
                            </td>
                            <td v-if="item.cloud == 'aws' ">
                                AWS S3
                            </td>
                            <td v-if="item.show">Available</td>
                            <td v-else>Unavailable</td>
                            <td>{{item.created_at}}</td>
                            <td>{{item.updated_at}}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-warning btn-sm mr-2"
                                            @click="GET_SUBTITLE(item.id,index)" data-toggle="modal"
                                            data-target="#GetSubtitleModal">Get Subtitles
                                    </button>
                                    <router-link class="btn btn-sm btn-warning btn-sm mr-2" role="buttton"
                                                 :to="{ name:'episode_edit', params: {id: item.id}}">
                                        Edit
                                    </router-link>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </div>
                </div>

                <!-- END Table -->

                <nav aria-label="pagination" class="m-4 p-1">
                    <ul class="pagination ">
                        <li class="page-item" id="end">
                            <a class="page-link"
                               @click="PAGINATION('/api/admin/get/series/season/'+ $route.params.id )">Begin</a>
                        </li>
                        <li class="page-item" id="prev" :class="{disabled: data.season.prev_page_url === null}">
                            <a class="page-link" @click="PAGINATION(data.season.prev_page_url)">Previous</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link">{{data.season.current_page}}/{{data.season.last_page}}</a>
                        </li>
                        <li class="page-item" id="next" :class="{disabled: data.season.next_page_url === null}">
                            <a class="page-link" @click="PAGINATION(data.season.next_page_url)">Next</a>
                        </li>
                        <li class="page-item" id="end">
                            <a class="page-link"
                               @click="PAGINATION('/api/admin/get/series/season/'+ $route.params.id +'?page='+data.season.last_page)">End</a>
                        </li>
                    </ul>
                </nav>

                <!-- END Nav -->

            </div>
            <div v-else>
                <div class="text-center mt-5 mr-5">
                    <h4>Sorry no result were found</h4>
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
    import Loader from "../components/loader";

    export default {
        data() {
            return {
                show_subtitle_modal: false,
                percentSubtitleUpload: 0,
                subtitle_video: false,
                subtitle_movie_id: null,
                error_message_subtitle: "",
                success_message_subtitle: "",
                disable_button: false,
                btn_delete: "",
                multi_select: [],
                showGroupButton: false
            };
        },

        components: {
            Loader
        },

        computed: mapState({
            data: state => state.series.data,
            button_loading: state => state.series.button_loading,
            spinner_loading: state => state.series.spinner_loading,
            subtitles: state => state.subtitles.data,
            subtitle_spinner_loading: state => state.subtitles.spinner_loading,
            button_delete_loading: state => state.button_delete_loading
        }),

        watch: {
            multi_select(item) {
                if (this.multi_select.length > 0) {
                    this.showGroupButton = true;
                } else {
                    this.showGroupButton = false;
                    document.getElementById("select-all").checked = false;
                }
            }
        },

        created() {
            this.$store.dispatch("GET_ALL_SEASON", this.$route.params.id);
        },

        methods: {
            DELETE(id, key) {
                swal({
                    title: "Are you sure to delete ?",
                    icon: "warning",
                    text: "All videos and subtitles will removed!",
                    buttons: true,
                    dangerMode: true
                }).then(willDelete => {
                    if (willDelete) {
                        this.$store.dispatch("DELETE_EPISODE", this.multi_select);
                    }
                });
            },

            PAGINATION(path_url) {
                this.$store.dispatch("GET_SERIES_PAGINATION", path_url);
            },

            GET_SUBTITLE(id, key) {
                this.show_subtitle_modal = true;
                this.subtitle_movie_id = id;
                this.$store.dispatch("GET_EPISODE_SUBTITLES", id);
            },

            UPLOAD_SUBTITLE(id) {
                const formData = new FormData();
                const sub = document.getElementById("subtitle").files.length;
                for (var x = 0; x < sub; x++) {
                    formData.append(
                        "subtitleUpload[]",
                        document.getElementById("subtitle").files[x]
                    );
                }

                formData.append("id", this.subtitle_movie_id);

                // Progress
                this.subtitle_video = true;
                this.disable_button = true;
                const progress = {
                    headers: {
                        "content-type": "multipart/form-data"
                    },
                    onUploadProgress: progressEvent => {
                        this.percentSubtitleUpload = Math.round(
                            (progressEvent.loaded * 100.0) / progressEvent.total
                        );
                    }
                };

                axios
                    .post("/api/admin/new/series/episode/subtitle", formData, progress)
                    .then(
                        response => {
                            if (response.status === 200) {
                                this.success_message_subtitle = response.data.message;
                                if (this.subtitles.subtitles != null) {
                                    this.subtitles.subtitles.push(response.data.data);
                                } else {
                                    this.subtitles.subtitles = [];
                                    this.subtitles.subtitles[0] = response.data.data;
                                }
                                setTimeout(() => {
                                    this.subtitle_video = false;
                                    this.disable_button = false;
                                }, 500);
                            }
                        },
                        error => {
                            this.disable_button = false;
                            this.error_message_subtitle = error.response.data.message;
                            setTimeout(() => {
                                this.subtitle_video = false;
                            }, 1500);
                        }
                    );
            },

            DELETE_SUBTITLE(id, key) {
                swal({
                    title: "Are you sure to delete ?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true
                }).then(willDelete => {
                    if (willDelete) {
                        this.$store.dispatch("DELETE_SUBTITLE", {
                            id,
                            key
                        });
                    }
                });
            },

            AVAILABLE_IT(id, index) {
                axios.post("/api/admin/update/series/episode/available", {
                    list: this.multi_select
                }).then(
                    response => {
                        if (response.status === 200) {
                            for (let i = 0; i < response.data.list.length; i++) {
                                this.data.season.data[response.data.list[i].key].show = response.data.list[i].show;
                            }
                        }
                    },
                    error => {
                        console.log("error in available this episode ");
                    }
                );
            },

            SHOW_FILES_INFO(idFiles, idDetails) {
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

            ADD_TO_MULITSELECT(item, key) {
                if (this.multi_select.length > 0) {
                    for (let i = 0; i < this.multi_select.length; i++) {
                        if (this.multi_select[i].id == item) {
                            this.multi_select.splice(i, 1);
                            return;
                        }
                    }
                    this.multi_select.push({
                        id: item,
                        key: key
                    });
                } else {
                    this.multi_select.push({
                        id: item,
                        key: key
                    });
                }
            },

            ADD_ALL_TO_MULITSELECT() {
                if (this.multi_select.length > 0) {
                    for (let i = 0; i < this.multi_select.length; i++) {
                        document.getElementById(this.multi_select[i].id).checked = false;
                    }
                    this.multi_select = [];
                } else {
                    for (let i = 0; i < this.data.season.data.length; i++) {
                        this.multi_select.push({
                            id: this.data.season.data[i].id,
                            key: i
                        });
                        document.getElementById(this.data.season.data[i].id).checked = true;
                    }
                }
            }
        }
    };
</script>
