<template>
<div>

    <div class="k1_manage_table">

        <h5 class="title p-2">Movies</h5>
        <div class="m-2" id="manage-panel">

            <div class="float-left mt-3" v-if="showGroupButton">
                <div class="button-group">
                    <button class="btn btn-sm btn-warning"  @click="AVAILABLE_IT()">Available  / Unavailable</button>
                    <button class="btn btn-sm btn-warning" @click="DELETE()">Delete</button>
                </div>
            </div>
            <ul class="nav nav-tabs">
                <li class="col col-md-4 offset-md-8">
                    <div id="search">
                        <input class="form-control mr-sm-2" type="text" v-model="query" placeholder="Search" @keyup="SEARCH">
                        </div>
                </li>
            </ul>
        </div>

        <!-- END Control Panel -->

        <div class="spinner-load" v-if="spinner_loading">

            <Loader></Loader>

        </div>

        <!-- END spinner load -->

        <div v-if="!spinner_loading">

            <div class="subtitles-modal">
                <div class="modal fade" id="GetSubtitleModal" tabindex="-1" role="dialog" aria-labelledby="GetSubtitleModalLabel" aria-hidden="true">
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
                                    <input type="file" id="subtitle" name="subtitle" multiple size="10" @change="SHOW_FILES_INFO('subtitle','subtitleFileDetails')"
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
                                                    viewBox="0 0 384 384" style="enable-background:new 0 0 384 384;" xml:space="preserve"
                                                    width="50px" class="">
                                                    <g>
                                                        <g>
                                                            <g>
                                                                <path d="M341.333,21.333H42.667C19.093,21.333,0,40.427,0,64v256c0,23.573,19.093,42.667,42.667,42.667h298.667    C364.907,362.667,384,343.573,384,320V64C384,40.427,364.907,21.333,341.333,21.333z M170.667,170.667h-32V160H96v64h42.667    v-10.667h32v21.333c0,11.733-9.493,21.333-21.333,21.333h-64C73.493,256,64,246.4,64,234.667v-85.333    C64,137.6,73.493,128,85.333,128h64c11.84,0,21.333,9.6,21.333,21.333V170.667z M320,170.667h-32V160h-42.667v64H288v-10.667h32    v21.333C320,246.4,310.507,256,298.667,256h-64c-11.84,0-21.333-9.6-21.333-21.333v-85.333c0-11.733,9.493-21.333,21.333-21.333    h64c11.84,0,21.333,9.6,21.333,21.333V170.667z"
                                                                    data-original="#000000" class="active-path" data-old_color="#00A6F9"
                                                                    fill="#00A6F9" />
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
                                        <div v-if="percentSubtitleUpload !== 100" class="progress-bar" role="progressbar" :style="{width: percentSubtitleUpload + '%', height:'6px' }" :aria-valuenow="percentSubtitleUpload" aria-valuemin="0" aria-valuemax="100"></div>
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
                                                        <button v-if="!button_loading" class="btn btn-sm btn-danger btn-sm mr-2" @click="DELETE_SUBTITLE(item.id,index)">Delete
                                                            </button>
                                                        <button v-if="button_loading === item.id" class="btn btn-sm btn-danger btn-sm mr-2" disabled>
                                                                <i id="btn-progress"></i> Loading
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
                                <button type="button" class="btn btn-sm btn-warning" :class="{disabled: disable_button}" @click="UPLOAD_SUBTITLE()">Upload Subtitle
                                    </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- END Subtitle Modal -->

            <div class="text-center" v-if="data.movies === null">
                <h4>No result were found</h4>
            </div>

            <div v-else>
                <div class="mt-2" v-if="Object.keys(data_search).length  > 0  && data_search.movies !== null ">
                    <div class="table table-hover">
                        <thead>
                            <th style="text-align: center;"><input type="checkbox" id="select-all" @click="ADD_ALL_TO_MULITSELECT()"></th>
                            <th>Cover</th>
                            <th>Name</th>
                            <th>Year</th>
                            <th>Cloud</th>
                            <th>Status</th>
                            <th>Category</th>
                            <th>Created date</th>
                            <th>Updated date</th>
                            <th>Control</th>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in data_search.movies" :key="index">
                                <td style="text-align: center;"><input type="checkbox" :id="item.id" @click="ADD_TO_MULITSELECT(item.id, index)"></td>
                                <td v-if="item.m_cloud == 'local'">
                                    <img :src="'/storage/posters/300_' + item.poster" :alt="item.name" width="40">
                                    </td>
                                <td v-if="item.m_cloud == 'aws' ">
                                    <img :src=" md_poster + item.poster" :alt="item.name" width="40">
                                    </td>

                                <td>{{item.name}}</td>
                                <td>{{item.year}}</td>
                                <td v-if="item.m_cloud == 'local'">
                                    Local Server
                                </td>
                                <td v-if="item.m_cloud == 'aws' ">
                                    AWS S3
                                </td>
                                <td v-if="item.show">Available</td>
                                <td v-else>Unavailable</td>
                                <td>{{item.category}}</td>
                                <td>{{item.created_at}}</td>
                                <td>{{item.updated_at}}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <router-link class="btn btn-sm btn-warning btn-sm mr-2" :to="{name:'analysis-movie', params: {id:item.id}}" role="button">Analysis
                                        </router-link>
                                        <button class="btn btn-sm btn-warning btn-sm mr-2" @click="ADD_TO_TOP(item.id,index)" v-if="item.movie_id !== item.id"
                                                :id="item.id">
                                                Top
                                            </button>
                                        <button class="btn btn-sm btn-warning btn-sm mr-2" @click="GET_SUBTITLE(item.id,index)" data-toggle="modal" data-target="#GetSubtitleModal">Get Subtitles
                                            </button>
                                        <router-link class="btn btn-sm btn-warning btn-sm mr-2" :to="{name:'movie-edit', params: {id:item.id}}" role="button">Edit
                                        </router-link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </div>
                </div>

                <!-- END Default Table -->

                <div class="table-responsive mt-2" v-if="data_search.movies === null || Object.keys(data_search).length === 0">
                    <div class="table table-hover">
                        <thead>
                            <th style="text-align: center;"><input type="checkbox" id="select-all" @click="ADD_ALL_TO_MULITSELECT()"></th>
                            <th>Cover</th>
                            <th>Name</th>
                            <th>Year</th>
                            <th>Cloud</th>
                            <th>Status</th>
                            <th>Category</th>
                            <th>Created date</th>
                            <th>Updated date</th>
                            <th>Control</th>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in data.movies.data" :key="index">
                                <td style="text-align: center;"><input type="checkbox" :id="item.id" @click="ADD_TO_MULITSELECT(item.id, index)"></td>
                                <td v-if="item.m_cloud == 'local'">
                                    <img :src="'/storage/posters/300_' + item.poster" :alt="item.name" width="40">
                                    </td>
                                <td v-if="item.m_cloud == 'aws' ">
                                    <img :src=" md_poster + item.poster" :alt="item.name" width="40">
                                    </td>
                                <td>{{item.name}}</td>
                                <td>{{item.year}}</td>
                                <td v-if="item.m_cloud == 'local'">
                                    Local Server
                                </td>
                                <td v-if="item.m_cloud == 'aws' ">
                                    AWS S3
                                </td>
                                <td v-if="item.show">Available</td>
                                <td v-else>Unavailable</td>
                                <td>{{item.category}}</td>
                                <td>{{item.created_at}}</td>
                                <td>{{item.updated_at}}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <router-link class="btn btn-sm btn-warning btn-sm mr-2" :to="{name:'analysis-movie', params: {id:item.id}}" role="button">Analysis
                                        </router-link>
                                        <button class="btn btn-sm btn-warning btn-sm mr-2" @click="ADD_TO_TOP(item.id,index)" v-if="item.movie_id !== item.id"
                                                :id="item.id">Top
                                            </button>
                                        <button class="btn btn-sm btn-warning btn-sm mr-2" @click="GET_SUBTITLE(item.id,index)" data-toggle="modal" data-target="#GetSubtitleModal">Get Subtitles
                                            </button>
                                        <router-link class="btn btn-sm btn-warning btn-sm mr-2" :to="{name:'movie-edit', params: {id:item.id}}" role="button">Edit
                                        </router-link>

                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </div>
                </div>

                <nav aria-label="pagination" class="m-4 p-1" v-if="data_search.movies === null || Object.keys(data_search).length === 0">
                    <ul class="pagination ">
                        <li class="page-item" id="end">
                            <a class="page-link" @click="PAGINATION('/api/admin/get/movies')">Begin</a>
                        </li>
                        <li class="page-item" id="prev" :class="{disabled: data.movies.prev_page_url === null}">
                            <a class="page-link" @click="PAGINATION(data.movies.prev_page_url)">Previous</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link">{{data.movies.current_page}}/{{data.movies.last_page}}</a>
                        </li>
                        <li class="page-item" id="next" :class="{disabled: data.movies.next_page_url === null}">
                            <a class="page-link" @click="PAGINATION(data.movies.next_page_url)">Next</a>
                        </li>
                        <li class="page-item" id="end">
                            <a class="page-link" @click="PAGINATION('/api/admin/get/movies?page='+data.movies.last_page)">End</a>
                        </li>
                    </ul>
                </nav>

                <!-- END Pagination  -->

            </div>
        </div>
    </div>
</div>
</template>

<script>
import {
    mapState
} from "vuex";
import Loader from "../components/loader";
export default {
    data() {
        return {
            query: "",
            show_subtitle_modal: false,
            percentSubtitleUpload: 0,
            subtitle_video: false,
            subtitle_movie_id: null,
            error_message_subtitle: "",
            success_message_subtitle: "",
            disable_button: false,
            multi_select: [],
            showGroupButton: false
        };
    },

    components: {
        Loader
    },

    computed: mapState({
        data: state => state.movies.data,
        data_search: state => state.movies.data_search,
        button_loading: state => state.movies.button_loading,
        spinner_loading: state => state.movies.spinner_loading,
        subtitles: state => state.subtitles.data,
        subtitle_spinner_loading: state => state.subtitles.spinner_loading
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
        this.$store.commit("CLEAN_SEARCH_MOVIES");
        this.$store.dispatch("GET_MOVIES");
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
                    this.$store.dispatch("DELETE_MOVIE", this.multi_select);
                }
            });
        },

        PAGINATION(path_url) {
            this.$store.dispatch("GET_MOVIES_PAGINATION", path_url);
        },

        SEARCH() {
            if (this.query.length > 0) {
                this.$store.dispatch("GET_MOVIE_SEARCH", this.query);
            } else {
                this.data_search.movies = null;
            }
        },

        ADD_TO_TOP(id, key) {
            this.$store.dispatch("ADD_MOVIE_TO_TOP", {
                id,
                key
            });
        },

        GET_SUBTITLE(id, key) {
            this.show_subtitle_modal = true;
            this.subtitle_movie_id = id;
            this.$store.dispatch("GET_MOVIE_SUBTITLES", id);
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

            axios.post("/api/admin/new/movie/moviesubtitle", formData, progress).then(
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
            axios
                .post("/api/admin/update/movie/available", {
                    list: this.multi_select
                })
                .then(
                    response => {
                        if (response.status === 200) {
                            for (let i = 0; i < response.data.list.length; i++) {
                                this.data.movies.data[response.data.list[i].key].show =
                                    response.data.list[i].show;
                            }
                        }
                    },
                    error => {
                        console.log("error in available movie ");
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
                for (let i = 0; i < this.data.movies.data.length; i++) {
                    this.multi_select.push({
                        id: this.data.movies.data[i].id,
                        key: i
                    });
                    document.getElementById(this.data.movies.data[i].id).checked = true;
                }
            }
        }
    }
};
</script>
