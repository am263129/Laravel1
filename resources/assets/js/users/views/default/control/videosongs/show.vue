<template>
    <div>

        <div class="spinner-load" v-if="loading">
            <div class="hidden-md-up phone">
                <div id="main">

                    <span class="spinner"></span>

                </div>
            </div>

            <div class="hidden-sm-down other">
                <div id="main">

                    <span class="spinner"></span>

                </div>
            </div>
        </div>

        <div class="show_item" v-if="data.videosong != null ">

            <collection-modal @hideModalCollectionCancel="HIDE_COLLECTION_MODAL_CANCEL"
                              @hideModalCollectionSave="HIDE_COLLECTION_MODAL_SAVE"
                              :id="collection.id" :poster="collection.poster" :name="collection.name"
                              :type="collection.type" :index="collection.index"></collection-modal>


            <!-- END Collection component -->

            <div class="exit-icon" @click="$Helper.back()">
                <exit-button></exit-button>
            </div>

            <!-- Exit Button -->

            <div class="show_item__body p-md-5">

                <div class="show_item__header p-4 p-md-5 p-lg-5 p-xl-5 ">

                    <div class="row">

                        <!-- END Back -->

                        <div class="header__collection ml-2">


                            <div class="add" v-if="! data.videosong.is_favorite && data.videosong.cloud == 'local'"
                                 @click.prevent="SHOW_COLLECTION_MODAL(data.videosong.id, '/storage/backdrops/600_' + data.videosong.backdrop, data.videosong.name, 'videosong')">

                                <img src="/themes/default/img/infavor.svg" alt="favor" width="100%">
                                <p>{{$t('show.my_collection')}}</p>
                            </div>

                            <div class="add" v-if="! data.videosong.is_favorite && data.videosong.cloud == 'aws'"
                                 @click.prevent="SHOW_COLLECTION_MODAL(data.videosong.id, md_backdrop + data.videosong.backdrop, data.videosong.name, 'videosong')">

                                <img src="/themes/default/img/infavor.svg" alt="favor" width="100%">
                                <p>{{$t('show.my_collection')}}</p>
                            </div>



                            <div class="remove" v-if="data.videosong.is_favorite"
                                 @click.prevent="DELETE_FROM_COLLECTION(data.videosong.id, 'videosong')">

                                <img src="/themes/default/img/favor.svg" alt="favor" width="100%" @click.prevent="DELETE_FROM_COLLECTION(item.id, 'videosong', index)" >

                                <p>{{$t('show.my_collection')}}</p>

                            </div>

                        </div>


                        <!-- END My Collection -->

                        <div class="header__like ml-4">

                            <div class="add" v-if="! data.videosong.is_like"
                                 @click.prevent="ADD_NEW_LIKE(data.videosong.id, 'videosong', 'add')">

                                <img src="/themes/default/img/dislike.svg" alt="dislike" width="100%">

                                <p>{{$t('show.like')}}</p>
                            </div>


                            <div class="remove" v-if="data.videosong.is_like"
                                 @click.prevent="ADD_NEW_LIKE(data.videosong.id, 'videosong', 'delete')">
                                <img src="/themes/default/img/like.svg" alt="like" width="100%">

                                <p>{{$t('show.like')}}</p>

                            </div>

                        </div>

                    </div>


                </div>


                <!-- END Control Panel -->


                <div class="hidden-sm-down body__background-sm-up">
                    <img :src="'/storage/backdrops/original_' + data.videosong.backdrop" :alt="data.videosong.name" class="backdrop" width="100%" v-if="data.videosong.cloud === 'local'">
                    <img :src="lg_backdrop + data.videosong.backdrop" :alt="data.videosong.name" class="backdrop" width="100%" v-if="data.videosong.cloud === 'aws'">

                </div>

                <div class="hidden-md-up body__background-sm-down">
                    <img :src="'/storage/backdrops/original_' + data.videosong.backdrop" :alt="data.videosong.name" class="backdrop" width="100%" v-if="data.videosong.cloud === 'local'">
                    <img :src="lg_backdrop + data.videosong.backdrop" :alt="data.videosong.name" class="backdrop" width="100%" v-if="data.videosong.cloud === 'aws'">


                    <router-link :to="{name: 'videosong-player', params: {id: data.videosong.id}}">
                        <div class="play hidden-md-up">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px"
                                    viewBox="0 0 294.843 294.843" style="enable-background:new 0 0 294.843 294.843;" xml:space="preserve"
                                    width="75px" class="play-big-svg">
                                    <g>
                                        <g>
                                            <path d="M278.527,79.946c-10.324-20.023-25.38-37.704-43.538-51.132c-2.665-1.97-6.421-1.407-8.392,1.257s-1.407,6.421,1.257,8.392   c16.687,12.34,30.521,28.586,40.008,46.983c9.94,19.277,14.98,40.128,14.98,61.976c0,74.671-60.75,135.421-135.421,135.421   S12,222.093,12,147.421S72.75,12,147.421,12c3.313,0,6-2.687,6-6s-2.687-6-6-6C66.133,0,0,66.133,0,147.421   s66.133,147.421,147.421,147.421s147.421-66.133,147.421-147.421C294.842,123.977,289.201,100.645,278.527,79.946z"
                                                data-original="#000000" class="active-path" data-old_color="#ffffff" fill="#ffffff"
                                            />
                                            <path d="M109.699,78.969c-1.876,1.067-3.035,3.059-3.035,5.216v131.674c0,3.314,2.687,6,6,6s6-2.686,6-6V94.74l88.833,52.883   l-65.324,42.087c-2.785,1.795-3.589,5.508-1.794,8.293c1.796,2.786,5.508,3.59,8.294,1.794l73.465-47.333   c1.746-1.125,2.786-3.073,2.749-5.15c-0.037-2.077-1.145-3.987-2.93-5.05L115.733,79.029   C113.877,77.926,111.575,77.902,109.699,78.969z"
                                                data-original="#000000" class="active-path" data-old_color="#ffffff" fill="#ffffff"
                                            />
                                        </g>
                                    </g>
                                </svg>
                        </div>
                    </router-link>


                </div>

                <!-- END Background image -->


                <div class="col-12 show_item__overview">
                    <div class="row">

                        <div class="col-5 col-md-3 col-xl-2 mt-3 poster-sm-down">
                            <div class="poster">
                                <progressive-img :src="'/storage/posters/600_' + data.videosong.poster"
                                                 placeholder="/themes/default/img/loader-image.png"
                                                 :alt="data.videosong.name"
                                                 width="100%"
                                                 :aspect-ratio="1.5"
                                                 :blur="0" v-if="data.videosong.cloud === 'local'"/>
                                <progressive-img :src="md_poster + data.videosong.poster"
                                                 placeholder="/themes/default/img/loader-image.png"
                                                 :alt="data.videosong.name"
                                                 width="100%"
                                                 :aspect-ratio="1.5"
                                                 :blur="0" v-if="data.videosong.cloud === 'aws'"/>
                            </div>
                        </div>

                        <div class="col-12 col-md-8 mt-3 content-sm-down">

                            <div class="__title">
                                <div class="col-12 p-0">
                                    <h5>
                                        <strong>{{data.videosong.name}}</strong>
                                        <strong class="age-rating">{{data.videosong.age}}</strong>
                                    </h5>

                                    <div class="year-genre mb-2">

                                        <p class="genre">{{data.videosong.genre}}</p>
                                        <span class="dot">|</span>
                                        <p class="year">{{data.videosong.year}}</p>
                                        <span class="dot">|</span>
                                        <p class="time">{{data.videosong.runtime}}</p>
                                    </div>

                                    <!-- END Name -->
                                    <p>{{data.videosong.overview}}</p>
                                    <!-- END Overview -->
                                </div>
                            </div>

                            <div class="__more-details">

                                <div class="col-12 p-0 rate">

                                    <h1>{{data.videosong.rate}}
                                        <small>/10</small>
                                    </h1>

                                </div>

                                <!-- END Rate -->

                            </div>


                            <div class="col-12 p-0 mt-5 hidden-xs-down __btn-control">
                                <div class="btn-group">
                                    <router-link :to="{name: 'videosong-player', params: {id: data.videosong.id}}"
                                                 class="btn btn-md-up btn-primary btn-play" role="button">
                                        Watch Movie
                                    </router-link>
                                    <button class="btn btn-md-up btn-primary btn-trailer ml-4"
                                            @click="showplyrmodal = true" v-if="data.videosong.trailer !== null">
                                        Watch Trailer
                                    </button>
                                </div>
                            </div>

                            <div class="col-12 p-0 mt-5 hidden-sm-up __btn-control">
                                <div class="btn-group">
                                    <router-link :to="{name: 'videosong-player', params: {id: data.videosong.id}}"
                                                 class="btn btn-primary btn-play" role="button">
                                        Watch Movie
                                    </router-link>
                                    <button class="btn btn-primary btn-trailer ml-4" @click="showplyrmodal = true"
                                            v-if="data.videosong.trailer !== null">
                                        Watch Trailer
                                    </button>
                                </div>
                            </div>

                            <div class="col-12 p-0 mt-5">
                                <div class="row">


                                    <div class="col-12 col-md-8 p-1" >
                                        <div class="__cast" v-if="data.casts !== null" >
                                            <h3>
                                                <strong>{{$t('show.cast')}}</strong>
                                            </h3>
                                            <div class="block ml-sm-2 m-1" v-for="(item, index) in data.casts"
                                                 :key="index">
                                                <router-link :to="{name: 'cast', params: {id: item.id}}">
                                                    <div class="image" @mouseover="castShow = item.id">
                                                        <img :src="item.image" :alt="item.name" width="100%" v-if="data.videosong.cloud === 'local'"/>
                                                        <img :src="sm_cast + item.image" :alt="item.name" width="100%" v-if="data.videosong.cloud === 'aws'"/>

                                                        <div class="ovarlay" v-if="castShow === item.id">
                                                            <p class="noselect">{{item.name}}</p>
                                                        </div>
                                                    </div>
                                                </router-link>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-12 col-md-4 hidden-lg-down" v-if="data.similar !== null">
                                        <div class="similar">
                                            <h3>
                                                <strong>{{$t('show.similar')}}</strong>
                                            </h3>
                                            <div class="row">
                                                <div class="col-6 p-md-3" v-for="(item, index) in data.similar"
                                                     :key="index">

                                                    <div class="image"
                                                         @click="SIMILAR_SHOW(item.id)">
                                                        <progressive-img :src="'/storage/posters/300_' + item.poster"
                                                                         placeholder="/themes/default/img/loader-image.png"
                                                                         :alt="item.name"
                                                                         width="100%" v-if="item.cloud === 'local' "/>
                                                        <progressive-img :src="sm_poster + item.poster"
                                                                         placeholder="/themes/default/img/loader-image.png"
                                                                         :alt="item.name"
                                                                         width="100%" v-if="item.cloud === 'aws' "/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>


                        </div>


                    </div>
                </div>


                <!-- END Overview -->

                <div class="col-12 col-md-8 col-lg-6 offset-md-2 offset-lg-3 trailer" :class="{ show: showplyrmodal}">
                        <div class="exit">
                            <img data-v-b12df990="" src="/themes/default/img/exit-icon.svg" alt="exit icon" width="100%" @click="showplyrmodal = false">
                        </div>
                        <div class="plyr" data-type="youtube" :data-video-id="data.videosong.trailer"></div>
                </div>


                <!-- END Trailer Modal -->

            </div>

        </div>
    </div>
</template>
<script>

    import {
        mapState
    } from "vuex";
    import collection from "../collection/new.vue";
    import exitButton from "../utils/exit-button.vue";
    const plyr = require("plyr");
    require("plyr/dist/plyr.css");
    export default {
        name: "videosong-show",

        data() {
            return {
                animation: false,
                castShow: null,
                showplyrmodal: false,
                collection: {
                    id: null,
                    poster: null,
                    name: null,
                    type: null,
                    index: null
                }
            };
        },

        components: {
            "collection-modal": collection,
            "exit-button": exitButton
        },

        computed: mapState({
            data: state => state.videos.show,
            loading: state => state.videos.loading,
            showSearchPageEvent: state => state.event.show_search_page
        }),

        beforeDestroy() {
            this.$store.commit("CLEAR_VIDEOSONG_SHOW_DATA");
        },

        mounted() {
            if (this.$auth.isAuthenticated()) {
                this.$store.dispatch("GET_VIDEOSONG_DETAILS", this.$route.params.id);
            } else {
                this.$store.dispatch("GET_GHOST_MOVIE_DETAILS", this.$route.params.id);
            }
        },

        watch: {
            data() {

                // Runtime format
                if (this.data.videosong.runtime <= 60) {
                    this.data.videosong.runtime = this.data.videosong.runtime + "m";
                } else if (this.data.videosong.runtime >= 60) {
                    const minutes = this.data.videosong.runtime % 60;
                    const hours = Math.floor(this.data.videosong.runtime / 60);
                    this.data.videosong.runtime = hours + "h " + minutes + "m";
                }

                // Replice special characters
                this.data.videosong.genre = this.data.videosong.genre.replace(/-/g, ", ");

                // Set title
                document.title = this.data.videosong.name;

            },
            showplyrmodal() {
                // Run plyr to show trailer
                setTimeout(() => {
                    plyr.setup(".plyr");
                }, 100);
            },
            showSearchPageEvent() {
                if (!this.showSearchPageEvent) {
                    this.$store.commit("SHOW_SEARCH_PAGE");
                }
            }
        },

        methods: {
            SIMILAR_SHOW(id) {
                if (this.$auth.isAuthenticated()) {
                    this.$store.dispatch("GET_MOVIE_DETAILS", id);
                } else {
                    this.$router.push({
                        name: 'login'
                    })
                }
            },

            // Show modal of collection
            SHOW_COLLECTION_MODAL(id, poster, name, type) {
                if (this.$auth.isAuthenticated()) {
                    this.collection.id = id;
                    this.collection.poster = poster;
                    this.collection.name = name;
                    this.collection.type = type;
                } else {
                    this.$router.push({
                        name: 'login'
                    })
                }
            },

            // Hide modal of collection
            HIDE_COLLECTION_MODAL_CANCEL() {
                if (this.$auth.isAuthenticated()) {
                    this.collection.id = null;
                } else {
                    this.$router.push({
                        name: 'login'
                    })
                }
            },

            // Hide modal and update array
            HIDE_COLLECTION_MODAL_SAVE() {
                if (this.$auth.isAuthenticated()) {
                    this.collection.id = null;
                    this.data.videosong.is_favorite = true;
                } else {
                    this.$router.push({
                        name: 'login'
                    })
                }
            },

            // Delete mvoie or series from data array
            DELETE_FROM_COLLECTION(id, type, index) {
                if (this.$auth.isAuthenticated()) {
                    this.data.videosong.is_favorite = false;

                    this.$store.dispatch("DELETE_FROM_COLLECTION", {
                        id,
                        type
                    });
                } else {
                    this.$router.push({
                        name: 'login'
                    })
                }
            },

            // Add new like or delete it
            // Params type1 detected if add or delete
            ADD_NEW_LIKE(id, type, type1) {
                if (this.$auth.isAuthenticated()) {
                    if (type1 === "add") {
                        // Add true to data array
                        this.data.videosong.is_like = true;
                        this.$store.dispatch("ADD_LIKE", {
                            id,
                            type
                        });
                    } else {
                        // Add false to data array
                        this.data.videosong.is_like = false;

                        this.$store.dispatch("ADD_LIKE", {
                            id,
                            type
                        });
                    }
                } else {
                    this.$router.push({
                        name: 'login'
                    })
                }
            }
        }
    };
</script>
