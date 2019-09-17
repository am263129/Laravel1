<template>
<div>

    <div class="spinner-load" v-if="spinner_loading">
        <Loader></Loader>
    </div>

    <div class="k1_manage_table" v-if="!spinner_loading">
        <h5 class="title p-2">{{ data.series.name }}</h5>

        <div class="m-2 p-3" id="manage-panel">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <router-link class="btn btn-md btn-warning" role="button" :to="{name:'series_manage_id', params:{id:this.$route.params.id}}">Manage
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link class="btn btn-md btn-warning ml-1" role="button" :to="{name:'new_series_episode', params:{id:this.$route.params.id}}">Episode API
                    </router-link>
                </li>

                <li class="nav-item">
                    <router-link class="btn btn-md btn-warning ml-1" role="button" :to="{name:'new_series_episode_custome', params:{id:this.$route.params.id}}">
                        Episode custom
                    </router-link>
                </li>

            </ul>
        </div>

        <!-- END Control Panel -->
        <div class="actor-modal">
            <div class="modal fade" id="GetActorModal" tabindex="-1" role="dialog" aria-labelledby="GetActorModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="GetActorModalLabel">Actors</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <div class="spinner-load" v-if="spinner_loading_actor">

                                <Loader></Loader>

                            </div>
                            <!-- END Spinner -->
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Search</label>
                                    <input name="search" class="form-control" v-model="search_query" type="text"
                                           placeholder="Search By Name"/>
                                </div>

                                <div class="col-12" v-if=" data_actors.actors !== null">

                                    <div v-if="data_actors_search === null" class="row">
                                        <div class="col-8 col-sm-3 mt-2 add-actor" v-for="(item,index) in data_actors.actors.data" :key="index" @click="ADD_ACTOR(index,'default')">

                                            <div class="image">
                                                <img :src="item.image" :id="'preview_'+item.id" width="100%"
                                                     class="img-rounded" v-if="item.cloud == 'local'">
                                                <img :src="md_cast + item.image" :id="'preview_'+item.id" width="100%"
                                                     class="img-rounded" v-if="item.cloud == 'aws' ">
                                                <p>{{item.name}}</p>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" v-else>

                                        <div class="col-8 col-sm-3 mt-2 add-actor" v-for="(item,index) in data_actors_search.actors" :key="index" @click="ADD_ACTOR(index, 'search')">

                                            <div class="image">
                                                <img :src="item.image" :id="'preview_'+item.id" width="100%"
                                                     class="img-rounded" v-if="item.cloud == 'local' ||  item.cloud == null">
                                                <img :src="md_cast + item.image" :id="'preview_'+item.id" width="100%"
                                                     class="img-rounded" v-if="item.cloud == 'aws' ">

                                                <p>{{item.name}}</p>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <div v-else>

                                    <div class="text-center">
                                        <h4>No result were found</h4>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Add Actor Model -->


            <div class="col-12">
                <div class="row">

                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <div class="col-2">
                                <label>Name</label>
                            </div>
                            <div class="col-12">
                                <input v-validate="'required|max:60'" name="name" class="form-control"
                                       v-model="data.series.name" type="text" placeholder="Name"
                                />
                                <span v-show="errors.has('name')" class="is-danger">{{ errors.first('name') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-2">
                                <label>Years</label>
                            </div>
                            <div class="col-12">
                                <input v-validate="'required|numeric|max:4'" name="year" class="form-control"
                                       v-model="data.series.year" type="text" placeholder="Years"
                                />
                                <span v-show="errors.has('year')" class="is-danger">{{ errors.first('year') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-2">
                                <label>Genres</label>
                            </div>
                            <div class="col-12">
                                <select multiple class="form-control" v-validate="'required'" name="genres" v-model="genres" id="exampleSelect2">
                                    <option>Adventure</option>
                                    <option>Animation</option>
                                    <option>Biography</option>
                                    <option>Comedy</option>
                                    <option>Crime</option>
                                    <option>Documentary</option>
                                    <option> Drama</option>
                                    <option>Family</option>
                                    <option>Fantasy</option>
                                    <option>Film Noir</option>
                                    <option>History</option>
                                    <option>Horror</option>
                                    <option>Music</option>
                                    <option>Musical</option>
                                    <option> Mystery</option>
                                    <option>Romance</option>
                                    <option> Sci-Fi</option>
                                    <option> Sport</option>
                                    <option> Superhero</option>
                                    <option> Thriller</option>
                                    <option> War</option>
                                    <option> Western</option>
                                </select>
                            </div>
                            <span v-show="errors.has('genres')" class="is-danger">{{ errors.first('genres') }}</span>
                        </div>
                        <div class="form-group">
                            <div class="col-12">
                                <label for="category">Category</label>
                            </div>
                            <div class="col-12">

                                <select class="form-control" v-validate="'required'" name="category"
                                        v-model="data.series.category" id="category">
                                    <option v-for="(item, index) in categories_list.categories" :key="index"
                                            :value="item.id">
                                        {{item.name}}
                                    </option>
                                </select>
                                <span v-show="errors.has('category')" class=" is-danger">{{ errors.first('category') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-2">
                                <label>Overview</label>
                            </div>
                            <div class="col-12">
                                <textarea v-validate="'required'" name="overview" class="form-control"
                                      v-model="data.series.overview" type="text" placeholder="Overview"
                            />
                                <span v-show="errors.has('overview')" class="is-danger">{{ errors.first('overview') }}
                            </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-2">
                                <label>Rate</label>
                            </div>
                            <div class="col-12">
                                <input v-validate="'required|decimal|max:5'" name="rate" class="form-control"
                                       v-model="data.series.rate" type="text" placeholder="Rate"
                                />
                                <span v-show="errors.has('rate')" class="is-danger">{{ errors.first('rate') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-12 col-sm-4">
                                <label>Poster</label>
                            </div>
                            <div class="col-12 col-sm-12">
                                <input type="file" id="poster" name="poster" v-validate="'image'"
                                       @change="readImage('poster','posterFileImage')" class="inputfile">
                                <label id="posterLabel" for="poster">Choose a poster
                                    <br>
                                </label>
                                <img :src="'/storage/posters/300_' + data.series.poster" id="posterFileImage" width="100%" v-if="data.series.cloud == 'local'">
                                <img :src="md_poster + data.series.poster" :alt="data.series.poster" id="posterFileImage" width="300px" v-if="data.series.cloud == 'aws' ">
                                <span v-show="errors.has('poster')" class="is-danger">{{ errors.first('poster')}}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-12 col-sm-4">
                                <label>Backdrop</label>
                            </div>
                            <div class="col-12 col-sm-12">
                                <input type="file" id="backdrop" name="backdrop" v-validate="'image'"
                                       @change="readImage('backdrop','backdropFileImage')"
                                       class="inputfile">
                                <label id="backdropLabel" for="backdrop">Choose a backdrop
                                    <br>
                                </label>
                                <img :src="'/storage/backdrops/300_' + data.series.backdrop" id="backdropFileImage" width="100%" v-if="data.series.cloud == 'local'">
                                <img :src="md_backdrop + data.series.backdrop" :alt="data.series.backdrop"  id="backdropFileImage" width="100%" v-if="data.series.cloud == 'aws' ">
                                <span v-show="errors.has('backdrop')"
                                      class="is-danger">{{ errors.first('backdrop')}}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 cast">
                        <h4>Cast</h4>
                        <div class="row">
                            <div class="col-lg-3 col-sm-4 col-12 mt-4 text-center" v-for="(item, key) in data.cast"
                                 :key="key">

                                <div class="image-cast">
                                    <div class="delete-cast">
                                        <i class="fa fa-times-circle" @click="DELETE_ACTOR(key)"></i>
                                    </div>
                                    <div class="image">
                                        <img :src=" item.image" :id="'preview_'+item.id" width="100%" class="img-rounded" v-if="item.cloud == 'local'">
                                        <img :src="md_cast + item.image" :id="'preview_'+item.id" width="100%" class="img-rounded" v-if="item.cloud == 'aws' ">
                                        <p>{{item.name}}</p>
                                    </div>
                                </div>

                            </div>

                            <div class="col-lg-3 col-sm-4 col-12 mt-4 text-center">

                                <div class="add-cast" data-toggle="modal"
                                     data-target="#GetActorModal">
                                    <h3>ADD</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- End Cast -->

                    <div class="col-12">
                        <div class="form-group">
                            <div class="col-6">
                                <button class="btn btn-md btn-warning" @click="UPDATE()">Update</button>
                            </div>
                        </div>
                    </div>

                    <div class="upload-modal" v-if="showProgress">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-body p-4">

                                    <div class="seriesapi" v-if="seriesedit">
                                        <div class="progress mt-2">
                                            <div class="progress-bar" role="progressbar"
                                                 :style="{width: percentCompleted + '%', height:'6px' }"
                                                 :aria-valuenow="percentCompleted"
                                                 aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <p class="is-danger">{{error_message_edit}}</p>
                                        <p class="is-success">{{success_message_edit}}</p>
                                        <hr>
                                    </div>

                                    <!-- END seriespai -->

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
const alertify = require("alertify.js");
import {
    mapState
} from "vuex";
import Loader from "../components/loader";
export default {
    data() {
        return {
            video_link: null,
            genres: [],
            search_query: "",
            showProgress: false,
            seriesedit: false,
            percentCompleted: 0,
            selectedCast3: null,
            error_message_edit: "",
            success_message_edit: ""
        };
    },

    components: {
        Loader
    },
    computed: mapState({
        data: state => state.series.data,
        data_actors: state => state.actors.data,
        data_actors_search: state => state.actors.data_search,
        button_loading: state => state.series.button_loading,
        spinner_loading: state => state.series.spinner_loading,
        spinner_loading_actor: state => state.actors.spinner_loading,
        categories_list: state => state.categories.data

    }),

    watch: {
        search_query(val) {
            if (val !== "") {
                this.$store.dispatch("GET_ACTORS_SEARCH", val);
            } else {
                this.$store.commit("CLEAR_SEARCH");
            }
        },
        data() {
            this.genres = this.data.series.genre.split(/[^\w\s]/g).map(String);
        }
    },

    mounted() {
        this.$store.dispatch("GET_SERIES", this.$route.params.id);
        this.$store.dispatch('GET_CATEGORIES_BY_SORT', 'series');
        this.$store.dispatch("GET_ALL_ACTORS");
    },
    methods: {
        UPDATE() {
            const poster = document.querySelector("#poster");
            const backdrop = document.querySelector("#backdrop");
            const formData = new FormData();
            formData.append("id", this.data.series.id);
            formData.append("name", this.data.series.name);
            formData.append("year", this.data.series.year);
            formData.append("genres", this.genres);
            formData.append("overview", this.data.series.overview);
            formData.append("rate", this.data.series.rate);
            formData.append("poster", poster.files[0]);
            formData.append("backdrop", backdrop.files[0]);
            formData.append("cast", JSON.stringify(this.data.cast));
            formData.append("category", this.data.series.category);

            this.$validator.validateAll().then(
                result => {
                    if (result) {
                        this.showProgress = true;
                        this.seriesedit = true;
                        var progress = {
                            headers: {
                                "content-type": "multipart/form-data"
                            },
                            onUploadProgress: progressEvent => {
                                this.percentCompleted = Math.round(
                                    (progressEvent.loaded * 100.0) / progressEvent.total
                                );
                            }
                        };
                        const _self = this;
                        axios
                            .post("api/admin/update/series", formData, progress)
                            .then(response => {
                                if (response.status === 200) {
                                    this.success_message_edit = response.data.message;
                                    setTimeout(() => {
                                        this.$router.go(-1);
                                    }, 2000);
                                }
                            });
                    }
                },
                error => {
                    this.error_message_edit = response.data.message;
                }
            );
        },

        ADD_ACTOR(key, type) {
            if (type === "default") {
                this.data.cast.push(this.data_actors.actors.data[key]);
                alertify.logPosition("top right");
                alertify.success(
                    this.data_actors.actors.data[key].name + " has been added to cast"
                );
            } else if (type === "search") {
                this.data.cast.push(this.data_actors_search.actors[key]);
                alertify.logPosition("top right");
                alertify.success(
                    this.data_actors_search.actors[key].name + " has been added to cast"
                );
            }
        },

        DELETE_ACTOR(key) {
            this.data.cast.splice(key, 1);
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
        }
    }
};
</script>
