<template>
    <div>

        <div class="spinner-load" v-if="spinner_loading">
            <Loader></Loader>
        </div>
        <!-- END spinner load -->

        <div class="k1_manage_table" v-if="!spinner_loading">
            <h5 class="title p-2">{{data.movie.name}}</h5>

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

            <div class="row">

                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <div class="col-2">
                            <label>Name</label>
                        </div>
                        <div class="col-12">
                            <input v-validate="'required|max:30'" name="name" class="form-control"
                                   v-model="data.movie.name" type="text" placeholder="Name"
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
                                   v-model="data.movie.year" type="text" placeholder="Years"
                            />
                            <span v-show="errors.has('year')" class="is-danger">{{ errors.first('year') }}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-2">
                            <label>Genres</label>
                        </div>
                        <div class="col-12">
                            <select multiple class="form-control" v-validate="'required'" name="genres"
                                    v-model="genres" id="exampleSelect2">
                                <option> Action</option>
                                <option> Adventure</option>
                                <option> Animation</option>
                                <option> Biography</option>
                                <option> Comedy</option>
                                <option> Crime</option>
                                <option> Documentary</option>
                                <option> Drama</option>
                                <option> Family</option>
                                <option> Fantasy</option>
                                <option> Film Noir</option>
                                <option> History</option>
                                <option> Horror</option>
                                <option> Music</option>
                                <option> Musical</option>
                                <option> Mystery</option>
                                <option> Romance</option>
                                <option> Sci-Fi</option>
                                <option> Short</option>
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
                                    v-model="data.movie.category" id="category">
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
                        <div class="col-2">
                            <label>Overview</label>
                        </div>
                        <div class="col-12">
                            <textarea name="overview" class="form-control"
                                      v-model="data.movie.overview" type="text" placeholder="Overview"> </textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-2">
                            <label>Runtime</label>
                        </div>
                        <div class="col-12">
                            <input v-validate="'required|decimal|max:10'" name="runtime" class="form-control"
                                   v-model="data.movie.runtime" type="text"
                                   placeholder="Runtime"/>
                            <span v-show="errors.has('runtime')" class="is-danger">{{ errors.first('runtime') }}
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-2">
                            <label>Rate</label>
                        </div>
                        <div class="col-12">
                            <input v-validate="'required|decimal|max:5'" name="rate" class="form-control"
                                   v-model="data.movie.rate" type="text" placeholder="Rate"
                            />
                            <span v-show="errors.has('rate')" class="is-danger">{{ errors.first('rate') }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-2">
                            <label>Trailer</label>
                        </div>
                        <div class="col-12">
                            <input v-validate="'url|max:300'" name="youtube" class="form-control"
                                   v-model="data.movie.youtube" type="text" placeholder="Trailer"
                            />
                            <span v-show="errors.has('youtube')" class="is-danger">{{ errors.first('youtube') }}
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-12">
                            <label for="age">Rating system</label>
                            <select class="form-control" v-validate="'required'" name="age" v-model="data.movie.age"
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

                    <div class="form-group">
                        <div class="col-12 col-sm-4">
                            <label>Poster</label>
                        </div>
                        <div class="col-12">
                            <input type="file" id="poster" name="poster" v-validate="'image'"
                                   @change="readImage('poster','posterFileImage')" class="inputfile">
                            <label id="posterLabel" for="poster">Choose a poster
                                <br>
                            </label>
                            <img :src="'/storage/posters/300_' + data.movie.poster" id="posterFileImage" width="40%"
                                 v-if="data.movie.cloud == 'local' || data.movie.cloud == null">
                            <img :src="md_poster + data.movie.poster" :alt="data.movie.poster" width="300px"
                                 v-if="data.movie.cloud == 'aws' ">

                            <span v-show="errors.has('poster')" class="is-danger">{{ errors.first('poster')}}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-12 col-sm-4">
                            <label>Backdrop</label>
                        </div>
                        <div class="col-12">
                            <input type="file" id="backdrop" name="backdrop" v-validate="'image'"
                                   @change="readImage('backdrop','backdropFileImage')"
                                   class="inputfile">
                            <label id="backdropLabel" for="backdrop">Choose a backdrop
                                <br>
                            </label>
                            <img :src="'/storage/backdrops/300_' + data.movie.backdrop" id="backdropFileImage"
                                 width="100%" v-if="data.movie.cloud == 'local' || data.movie.cloud == null">
                            <img :src="md_backdrop + data.movie.backdrop" :alt="data.movie.backdrop" width="100%"
                                 v-if="data.movie.cloud == 'aws' ">

                            <span v-show="errors.has('backdrop')"
                                  class="is-danger">{{ errors.first('backdrop')}}</span>
                        </div>
                    </div>

                </div>

                <div class="col-12 col-md-6 cast">
                    <h5 class="title p-2">Cast</h5>
                    <div class="row">
                        <div class="col-6 col-lg-4 col-xl-3 mt-4 text-center" v-for="(item, key) in data.cast"
                             :key="key">

                            <div class="image-cast">
                                <div class="delete-cast">
                                    <i class="fa fa-times-circle" @click="DELETE_ACTOR(key)"></i>
                                </div>
                                <div class="image">
                                    <img :src="item.image" :alt="item.name" width="100%"
                                         class="img-rounded" v-if="item.cloud == 'local'">
                                    <img :src="md_cast + item.image" :alt="item.name" width="100%"
                                         class="img-rounded" v-if="item.cloud == 'aws' ">
                                </div>
                                <p>{{item.name}}</p>
                            </div>

                        </div>

                        <div class="col-lg-3 col-sm-4 col-12 mt-4 text-center">

                            <div class="add-cast" data-toggle="modal" data-target="#GetActorModal">
                                <h3>ADD</h3>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div id="accordion resolution-videos" class="mt-4">
                        <h5 class="title p-2">Vidos</h5>
                        <div class="card" v-for="(item, index) in data.videos" :key="index">
                            <div class="card-header" :id="'heading' + index">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" data-toggle="collapse"
                                            :data-target="'#collapse' + index" aria-expanded="true"
                                            :aria-controls="'collapse' + index">
                                        Resolution <span v-if="item.resolution === '320'">320P</span>
                                        <span v-if="item.resolution === '480'">480P</span>
                                        <span v-if="item.resolution === '720'">720P</span>
                                        <span v-if="item.resolution === '1080'">1080P</span>
                                    </button>
                                </h5>
                            </div>

                            <div :id="'collapse'+ index" class="collapse show" :aria-labelledby="'heading' + index"
                                 data-parent="#accordion">
                                <div class="card-body">
                                    <div class="form-group video-player">
                                        <video width="100%" controls v-if="data.movie.type !== 'embed'">
                                            <source :src="item.video_url" type="application/x-mpegurl">
                                            Your browser does not support the video tag.
                                        </video>
                                        <iframe v-else width="100%" height="100%" :src="item.video_url"
                                                frameborder="0" allowfullscreen></iframe>
                                    </div>
                                    <div class="form-group">
                                        <label for="link" class="m-1">Video Link</label>
                                        <input type="text" name="link" class="form-control" id="link"
                                               v-model="item.video_url">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <!-- end #cast -->

                <div class="form-group m-3">
                    <div class="col-12 col-md-6">
                        <button class="btn btn-md btn-warning" @click="UPDATE(data.movie.id)">UPDATE</button>
                    </div>
                </div>

                <div class="upload-modal" v-if="showProgress">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-body p-4">

                                <div class="movieapi" v-if="movieedit">
                                    <div class="progress mt-2">
                                        <div class="progress-bar" role="progressbar"
                                             :style="{width: percentCompleted + '%', height:'6px' }"
                                             :aria-valuenow="percentCompleted" aria-valuemin="0"
                                             aria-valuemax="100"></div>
                                    </div>
                                    <p class="is-danger">{{error_message_edit}}</p>
                                    <p class="is-success">{{success_message_edit}}</p>
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
    import {
        mapState
    } from "vuex";
    import Loader from "../components/loader";

    export default {
        data() {
            return {
                genres: [],
                showProgress: false,
                movieedit: false,
                search_query: "",
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
            data: state => state.movies.data,
            data_actors: state => state.actors.data,
            data_actors_search: state => state.actors.data_search,
            button_loading: state => state.movies.button_loading,
            spinner_loading: state => state.movies.spinner_loading,
            spinner_loading_actor: state => state.actors.spinner_loading,
            categories_list: state => state.categories.data
        }),

        created() {
            this.$store.dispatch('GET_CATEGORIES_BY_SORT', 'movie');
        },

        watch: {
            search_query(val) {
                if (val !== "") {
                    this.$store.dispatch("GET_ACTORS_SEARCH", val);
                } else {
                    this.$store.commit("CLEAR_SEARCH");
                }
            },
            data() {
                this.genres = this.data.movie.genre.split(/[^\w\s]/g).map(String);
            }
        },

        mounted() {
            this.$store.dispatch("GET_MOVIE", this.$route.params.id);
            this.$store.dispatch("GET_ALL_ACTORS");
        },
        methods: {
            UPDATE(id) {
                const poster = document.querySelector("#poster");
                const backdrop = document.querySelector("#backdrop");
                const formData = new FormData();
                formData.append("id", this.data.movie.id);
                formData.append("name", this.data.movie.name);
                formData.append("year", this.data.movie.year);
                formData.append("age", this.data.movie.age);
                formData.append("genres", this.genres);
                formData.append("overview", this.data.movie.overview);
                formData.append("runtime", this.data.movie.runtime);
                formData.append("rate", this.data.movie.rate);
                formData.append("youtube", this.data.movie.youtube);
                formData.append("video_url", this.data.movie.video_url);
                formData.append("poster", poster.files[0]);
                formData.append("backdrop", backdrop.files[0]);
                formData.append("cast", JSON.stringify(this.data.cast));
                formData.append("videos", JSON.stringify(this.data.videos));
                formData.append("category", this.data.movie.category);

                this.$validator.validateAll().then(
                    result => {
                        if (result) {
                            this.showProgress = true;
                            this.movieedit = true;
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
                                .post("api/admin/update/movie", formData, progress)
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
