<template>
    <div class="new-series-api">
        <div class="k1_manage_table">
            <div class="title p-2">Series API Upload</div>

            <div class="col-12">

                <div class="form-group">
                    <div class="col-12">
                        <label>Choose Cloud </label>
                    </div>
                    <div class="col-12 col-md-8 cloud-upload">
                        <div class="row">
                            <div class="col-12 col-md-6 image" @click="cloud_type = 'local'">
                                <div class="local-cloud-logo"  :class="{active: cloud_type === 'local'}">
                                    <img src="/themes/default/img/local-cloud.svg" alt="local-cloud" width="90px">
                                </div>
                            </div>
                            <div class="col-12 col-md-6 image"  @click="cloud_type = 'aws'">
                                <div class="aws-cloud-logo" :class="{active: cloud_type === 'aws'}">
                                    <img src="/themes/default/img/aws-cloud.svg" alt="aws-cloud" width="120px">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>


                <div v-if="cloud_type">
                    <div class="form-group">
                    <div class="col-12 col-lg-12">
                        <label>ID</label>
                    </div>
                    <div class="col-12 col-sm-8 col-lg-4">
                        <input class="form-control" name="id" v-model="id" v-validate="'required|numeric'" type="text"
                               placeholder="Moviedb id"/>
                        <span v-show="errors.has('id')" class="is-danger">{{ errors.first('id') }}</span>
                    </div>
                </div>
                <div class="col-12 col-sm-8 col-lg-4">
                    <div class="form-group">
                        <label for="age">Rating system</label>
                        <select class="form-control" v-validate="'required'" name="age" v-model="age" id="age">
                            <option>G</option>
                            <option>PG</option>
                            <option>PG-13</option>
                            <option>R</option>
                            <option>X</option>
                        </select>
                    </div>
                    <span v-show="errors.has('age')" class=" is-danger">{{ errors.first('age') }}</span>
                </div>
                    <div class="col-12 col-sm-8 col-lg-4">
                    <div class="form-group">
                        <label for="category">Category</label>

                            <select class="form-control" v-validate="'required'" name="category" v-model="category" id="category">
                                <option v-for="(item, index) in categories_list.categories" :key="index" :value="item.id">
                                    {{item.name}}
                                </option>
                            </select>
                            <span v-show="errors.has('category')" class=" is-danger">{{ errors.first('category') }}</span>
                        </div>
                    </div>

                <div class="form-group">
                    <div class="col-6">
                        <button class="btn btn-md  btn-warning" v-if="!disabled_button" @click="SERIES_API()">Upload
                        </button>
                        <button class="btn btn-md  btn-warning" v-if="disabled_button" disabled>Loading</button>
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
                id: "",
                age: null,
                series_api: false,
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

        created(){
            this.$store.dispatch('GET_CATEGORIES_BY_SORT', 'series');
        },

        methods: {
            SERIES_API(id) {


                // Check count of upload data
                this.$store.commit('COUNT_UPLOAD_PROGRESS');

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

                        this.$store.commit('SET_PROGRESS_DATA', this.upload_data);
                        this.$store.commit('SET_UPLOAD_PROGRESS', this.uploadData[this.countUploadData]);
                        this.$store.commit('UPDATE_UPLOAD_PROGRESS_DATA', {
                            key: this.countUploadData,
                            data: this.uploadData[this.countUploadData]
                        });


                        axios
                            .post("/api/admin/new/series/moviedbapi", this.apiFormData)
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
            }
        }
    };
</script>
