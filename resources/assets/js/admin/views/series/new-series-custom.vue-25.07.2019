<template>
    <div>
        <div class="k1_manage_table">
            <div class="title p-2">Series Custom Upload</div>


            <div class="col-12">

                <div class="form-group">
                    <div class="col-12">
                        <label>Choose Cloud </label>
                    </div>
                    <div class="col-12 col-md-8 cloud-upload">
                        <div class="row">
                            <div class="col-12 col-md-6 image" @click="cloud_type = 'local'">
                                <div class="local-cloud-logo" :class="{active: cloud_type === 'local'}">
                                    <img src="/themes/default/img/local-cloud.svg" alt="local-cloud" width="90px">
                                </div>
                            </div>
                            <div class="col-12 col-md-6 image" @click="cloud_type = 'aws'">
                                <div class="aws-cloud-logo" :class="{active: cloud_type === 'aws'}">
                                    <img src="/themes/default/img/aws-cloud.svg" alt="aws-cloud" width="120px">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>


                <div class="row">

                    <div class="col-12 col-lg-6">

                        <div v-if="cloud_type">

                            <div class="form-group">
                                <div class="col-12">
                                    <label>Name</label>
                                </div>
                                <div class="col-12">
                                    <input v-validate="'required|max:30'" name="name" class="form-control"
                                           v-model="name"
                                           type="text" placeholder="Name"/>
                                    <span v-show="errors.has('name')"
                                          class="is-danger">{{ errors.first('name') }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-12">
                                    <label>Years</label>
                                </div>
                                <div class="col-12">
                                    <input v-validate="'required|numeric|max:4'" name="year" class="form-control"
                                           v-model="year" type="text" placeholder="Years"
                                    />
                                    <span v-show="errors.has('year')"
                                          class="is-danger">{{ errors.first('year') }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-12">
                                    <label>Genres</label>
                                </div>
                                <div class="col-12">
                                    <div class="form-control-feedback" v-if="name === false">Name of movie.</div>
                                    <select multiple class="form-control" v-validate="'required'" name="genres"
                                            v-model="genres" id="exampleSelect2">
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
                                <span v-show="errors.has('genres')"
                                      class="is-danger">{{ errors.first('genres') }}</span>
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


                            <div class="form-group">
                                <div class="col-12">
                                    <label>Overview</label>
                                </div>
                                <div class="col-12">
                            <textarea v-validate="'required|max:2000'" name="overview" class="form-control"
                                      v-model="overview"
                                      type="text" placeholder="Overview"
                            />
                                    <span v-show="errors.has('overview')" class="is-danger">{{ errors.first('overview') }}
                            </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-12">
                                    <label>Rate
                                        <small>(From 1 - 10)</small>
                                    </label>
                                </div>
                                <div class="col-12">
                                    <input v-validate="'required|decimal:1|max:3'" name="rate" class="form-control"
                                           v-model="rate" type="text" placeholder="Rate"
                                    />
                                    <span v-show="errors.has('rate')"
                                          class="is-danger">{{ errors.first('rate') }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-12">
                                    <label>Trailer
                                        <small>(Youtube)</small>
                                    </label>
                                </div>
                                <div class="col-12">
                                    <input v-validate="'url|max:300'" name="youtube" class="form-control"
                                           v-model="youtube" type="text" placeholder="Trailer"
                                    />
                                    <span v-show="errors.has('youtube')" class="is-danger">{{ errors.first('youtube') }}
                            </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-12 col-sm-4">
                                    <label>Poster</label>
                                </div>
                                <div class="col-12 ">
                                    <input type="file" id="poster" name="poster" v-validate="'image|required'"
                                           @change="readImage('poster','posterFileImage')" class="inputfile">
                                    <label id="posterLabel" for="poster">Choose a poster
                                        <br>
                                    </label>
                                    <img src="" id="posterFileImage" width="40%" style="display: none;">
                                    <span v-show="errors.has('poster')" class="is-danger">{{ errors.first('poster') }}
                            </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-12 col-sm-4">
                                    <label>Backdrop</label>
                                </div>
                                <div class="col-12 ">
                                    <input type="file" id="backdrop" name="backdrop" v-validate="'image|required'"
                                           @change="readImage('backdrop','backdropFileImage')"
                                           class="inputfile">
                                    <label id="backdropLabel" for="backdrop">Choose a backdrop
                                        <br>
                                    </label>
                                    <img src="" id="backdropFileImage" width="100%" style="display: none;">
                                    <span v-show="errors.has('backdrop')"
                                          class="is-danger">{{ errors.first('backdrop')}}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-12 ">
                                    <label>Rating system</label>
                                    <select class="form-control" v-validate="'required'" name="age" v-model="age"
                                            id="age">
                                        <option>G</option>
                                        <option>PG</option>
                                        <option>PG-13</option>
                                        <option>R</option>
                                        <option>X</option>
                                    </select>
                                </div>
                                <span v-show="errors.has('age')" class=" is-danger">{{ errors.first('age') }}</span>
                            </div>

                        </div>

                    </div>


                    <div class="col-12 col-lg-6" v-if="cloud_type">
                        <form class="cover__form" id="form">
                            <div class="form-group">
                                <div class="col-12">
                                    <div class="alert alert-info" role="alert">
                                        <strong>If you upload ! </strong> Required to add name and image
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-12 col-sm-4">
                                    <label>Cast (1)</label>
                                </div>
                                <div class="col-12 ">
                                    <input v-validate="'max:50'" name="name1" class="form-control" v-model="cast1"
                                           type="text" placeholder="Name"/>
                                    <br>
                                    <span v-show="errors.has('name1')" class="is-danger">{{ errors.first('name4') }}
                                </span>

                                    <input type="file" id="cast1" name="cast4" v-validate="'image'"
                                           @change="readImage('cast1','cast1FileImage')" class="inputfile">
                                    <label id="cast1Label" for="cast1">Choose a image
                                        <br>
                                    </label>
                                    <img src="" id="cast1FileImage" width="200" style="display: none;">
                                    <span v-show="errors.has('cast1')"
                                          class="is-danger">{{ errors.first('cast1')}}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-12 col-sm-4">
                                    <label>Cast (2)</label>
                                </div>
                                <div class="col-12 ">
                                    <input v-validate="'max:50'" name="name2" class="form-control" v-model="cast2"
                                           type="text" placeholder="Name"/>
                                    <br>
                                    <span v-show="errors.has('name2')" class="is-danger">{{ errors.first('name2') }}
                                </span>


                                    <input type="file" id="cast2" name="cast2" v-validate="'image'"
                                           @change="readImage('cast2','cast2FileImage')" class="inputfile">
                                    <label id="cast2Label" for="cast2">Choose a image
                                        <br>
                                    </label>
                                    <img src="" id="cast2FileImage" width="200" style="display: none;">
                                    <span v-show="errors.has('cast2')"
                                          class="is-danger">{{ errors.first('cast2')}}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-12 col-sm-4">
                                    <label>Cast (3)</label>
                                </div>
                                <div class="col-12 ">
                                    <input v-validate="'max:50'" name="name3" class="form-control" v-model="cast3"
                                           type="text" placeholder="Name"/>
                                    <br>
                                    <span v-show="errors.has('name3')" class="is-danger">{{ errors.first('name3') }}
                                </span>


                                    <input type="file" id="cast3" name="cast3" v-validate="'image'"
                                           @change="readImage('cast3','cast3FileImage')" class="inputfile">
                                    <label id="cast3Label" for="cast3">Choose a image
                                        <br>
                                    </label>
                                    <img src="" id="cast3FileImage" width="200" style="display: none;">
                                    <span v-show="errors.has('cast3')"
                                          class="is-danger">{{ errors.first('cast3')}}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-12 col-sm-4">
                                    <label>Cast (4)</label>
                                </div>
                                <div class="col-12 ">
                                    <input v-validate="'max:50'" name="name4" class="form-control" v-model="cast4"
                                           type="text" placeholder="Name"/>
                                    <br>
                                    <span v-show="errors.has('name4')" class="is-danger">{{ errors.first('name4') }}
                                </span>


                                    <input type="file" id="cast4" name="cast4" v-validate="'image'"
                                           @change="readImage('cast4','cast4FileImage')" class="inputfile">
                                    <label id="cast4Label" for="cast4">Choose a image
                                        <br>
                                    </label>
                                    <img src="" id="cast4FileImage" width="200" style="display: none;">
                                    <span v-show="errors.has('cast4')"
                                          class="is-danger">{{ errors.first('cast4')}}</span>
                                </div>
                            </div>
                        </form>
                    </div>


                    <div class="col-12" v-if="cloud_type">
                        <div class="form-group">
                            <div class="col-12">
                                <button class="btn btn-md btn-warning" v-if="!disabled_button" @click="SERIES_CUSTOM()">
                                    Upload
                                </button>
                                <button class="btn btn-md btn-warning" v-if="disabled_button" disabled>Loading</button>
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


    export default {
        data() {
            return {
                name: "",
                year: "",
                genres: [],
                overview: "",
                runtime: "",
                rate: "",
                youtube: "",
                video_link: "",
                count: "",
                age: null,
                //Cast
                cast1: "",
                cast2: "",
                cast3: "",
                cast4: "",
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
            this.$store.dispatch('GET_CATEGORIES_BY_SORT', 'series');
        },

        methods: {
            SERIES_CUSTOM(name) {
                const fileInput1 = document.querySelector("#cast1");
                const fileInput2 = document.querySelector("#cast2");
                const fileInput3 = document.querySelector("#cast3");
                const fileInput4 = document.querySelector("#cast4");
                const poster = document.querySelector("#poster");
                const backdrop = document.querySelector("#backdrop");
                this.apiFormData.append("image1", fileInput1.files[0]);
                this.apiFormData.append("image2", fileInput2.files[0]);
                this.apiFormData.append("image3", fileInput3.files[0]);
                this.apiFormData.append("image4", fileInput4.files[0]);
                this.apiFormData.append("cast1", this.cast1);
                this.apiFormData.append("cast2", this.cast2);
                this.apiFormData.append("cast3", this.cast3);
                this.apiFormData.append("cast4", this.cast4);
                this.apiFormData.append("name", this.name);
                this.apiFormData.append("year", this.year);
                this.apiFormData.append("genres", this.genres);
                this.apiFormData.append("overview", this.overview);
                this.apiFormData.append("rate", this.rate);
                this.apiFormData.append("youtube", this.youtube);
                this.apiFormData.append("poster", poster.files[0]);
                this.apiFormData.append("backdrop", backdrop.files[0]);
                this.apiFormData.append("age", this.age);
                this.apiFormData.append("category", this.category);


                // Cloud Type
                this.apiFormData.append("cloud_type", this.cloud_type);


                // Check count of upload data
                this.$store.commit('COUNT_UPLOAD_PROGRESS');


                this.$validator.validateAll().then(result => {
                    if (result) {
                        this.disabled_button = true;

                        this.upload_data.api.show = true;
                        this.upload_data.id = this.name;

                        this.$store.commit('SET_PROGRESS_DATA', this.upload_data);
                        this.$store.commit('SET_UPLOAD_PROGRESS', this.uploadData[this.countUploadData]);
                        this.$store.commit('UPDATE_UPLOAD_PROGRESS_DATA', {
                            key: this.countUploadData,
                            data: this.uploadData[this.countUploadData]
                        });


                        axios.post("/api/admin/new/series/customupload", this.apiFormData)
                            .then(response => {
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


                                        setTimeout(() => {
                                            this.$router.push({
                                                name: "series-manage"
                                            });
                                            alertify.logPosition("top right");
                                            alertify.success("Successful upload");
                                        }, 1500);
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
