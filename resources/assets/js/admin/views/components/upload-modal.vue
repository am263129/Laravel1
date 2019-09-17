<template>
    <div class="col-12 col-md-4 upload-modal" :class="{show: toggle, hide: !toggle}" v-if="showComponent">

        <div class="hide" v-if="toggle" @click="SHOW"></div>
        <div class="show" v-if="!toggle" @click="SHOW"></div>
        <span class="progress-bar-animated"></span>


        <div class="col-12" v-for="(item,index) in uploadData" v-if="uploadData.length > 0" :key="index">

            <div class="desc-upload">
                <h4>ID: {{item.id}}</h4>
                <hr>
            </div>


            <div class="content">

                <div class="api mt-1" v-if="item.api.show">
                    <div class="row">
                        <div class="svg api-tmdb-upload-icon ml-3"></div>
                        <div class="title">
                            <h6>
                                <strong>Get Details From TMBD API</strong>
                            </h6>
                        </div>
                    </div>
                    <div class="progress mt-4">
                        <div v-if="item.api.progress !== 100">
                            <i id="btn-progress"></i> Prepare
                        </div>
                        <div class="m-2" v-else>
                                <strong>Finished</strong>
                        </div>
                    </div>
                    <p class="is-danger" v-if="item.api.error_message !== null">{{item.api.error_message}}</p>
                    <p class="is-success" v-if="item.api.success_message !== null">{{item.api.success_message}}</p>
                </div>

                <!-- END moviepai -->

                <div class="upload mt-1" v-if="item.upload.show">
                    <div class="row">
                        <div class="svg upload-video-icon ml-3">


                        </div>
                        <div class="title">
                            <h6>
                                <strong>Upload Video</strong>
                            </h6>
                        </div>
                    </div>
                    <div class="progress mt-4">
                        <div v-if="item.upload.progress < 97" class="progress-bar" role="progressbar"
                             :style="{width: item.upload.progress + '%'}"
                             :aria-valuenow="item.upload.progress" aria-valuemin="0" aria-valuemax="100">
                            {{item.upload.progress}}
                        </div>
                        <div class="m-2" v-else>
                            <i id="btn-progress" v-if="!item.upload.success_message"></i> Transcoding & Uploading
                            <strong v-if="item.upload.success_message">Finished</strong>
                        </div>
                    </div>

                    <p class="progress-status">{{item.upload.message}}</p>
                    <p class="is-danger" v-if="item.upload.error_message !== null">{{item.upload.error_message}}</p>
                    <p class="is-success" v-if="item.upload.success_message !== null">
                        {{item.upload.success_message}}</p>
                    <hr>
                    <!--<p>{{upload_message}}</p>-->
                </div>


            </div>
        </div>

        <div class="empty" v-else>
            <h3>There is no upload in process</h3>
        </div>

    </div>
</template>


<script>

    import {
        mapState
    } from "vuex";

    export default {
        data() {
            return {
                toggle: false,
                showComponent: false,
            }
        },

        computed: mapState({
            uploadData: state => state.event.data,
        }),

        watch: {
            uploadData(){
                this.showComponent = true;
                this.toggle = true;
            }
        },

        methods: {
            SHOW() {
                this.toggle = !this.toggle;
            }
        }

    };

</script>