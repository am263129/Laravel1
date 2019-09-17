<template>

    <div class="show_series show_item" v-if="data.series != null ">

            <collection-modal @hideModalCollectionCancel="HIDE_COLLECTION_MODAL_CANCEL" @hideModalCollectionSave="HIDE_COLLECTION_MODAL_SAVE"
                :id="collection.id" :poster="collection.poster" :name="collection.name" :type="collection.type" :index="collection.index"></collection-modal>

            <!-- END Collection component -->

            <router-link class="exit-icon" :to="{name: 'series' }">
                <exit-button></exit-button>
            </router-link>

            <!-- Exit Button -->

            <div class="show_item__body p-md-5">

                <div class="show_item__header p-4 ml-2">

                    <div class="row">

                        <!-- END Back -->

                        <div class="header__collection ml-2">


                            <div class="add" v-if="! data.series.is_favorite && data.series.cloud == 'local'"
                                 @click.prevent="SHOW_COLLECTION_MODAL(data.series.id, '/storage/backdrops/600_' + data.series.backdrop, data.series.name, 'series')">

                                <img src="/themes/default/img/infavor.svg" alt="favor" width="100%">
                                <p>{{$t('show.my_collection')}}</p>
                            </div>

                            <div class="add" v-if="! data.series.is_favorite && data.series.cloud == 'aws'"
                                 @click.prevent="SHOW_COLLECTION_MODAL(data.series.id, md_backdrop + data.series.backdrop, data.series.name, 'series')">

                                <img src="/themes/default/img/infavor.svg" alt="favor" width="100%" >
                                <p>{{$t('show.my_collection')}}</p>
                            </div>



                            <div class="remove" v-if="data.series.is_favorite"
                                 @click.prevent="DELETE_FROM_COLLECTION(data.series.id, 'series')">

                                <img src="/themes/default/img/favor.svg" alt="favor" width="100%" @click.prevent="DELETE_FROM_COLLECTION(item.id, 'series', index)" >

                                <p>{{$t('show.my_collection')}}</p>

                            </div>

                        </div>




                        <!-- END My Collection -->

                        <div class="header__like ml-4">

                            <div class="add" v-if="! data.series.is_like"
                                 @click.prevent="ADD_NEW_LIKE(data.series.id, 'series', 'add')">

                                <img src="/themes/default/img/dislike.svg" alt="dislike" width="100%">

                                <p>{{$t('show.like')}}</p>
                            </div>


                            <div class="remove" v-if="data.series.is_like"
                                 @click.prevent="ADD_NEW_LIKE(data.series.id, 'series', 'delete')">
                                <img src="/themes/default/img/like.svg" alt="like" width="100%">

                                <p>{{$t('show.like')}}</p>

                            </div>

                        </div>

                    </div>


                </div>


                <!-- END Control Panel -->


                <div class="hidden-sm-down  body__background-sm-up">
                    <div v-if="show_season === 1">
                        <img :src="'/storage/backdrops/original_' + data.series.backdrop" :alt="data.series.name" class="backdrop" width="100%" v-if="data.series.cloud === 'local'">
                        <img :src="lg_backdrop + data.series.backdrop" :alt="data.series.name" class="backdrop" width="100%" v-if="data.series.cloud === 'aws'">

                    </div>

                    <div v-else>
                        <img :src="'/storage/backdrops/original_' + data.season[show_season][0].backdrop" :alt="data.series.name" class="backdrop" width="100%" v-if="data.series.cloud === 'local'">
                        <img :src="lg_backdrop + data.season[show_season][0].backdrop" :alt="data.series.name" class="backdrop" width="100%" v-if="data.series.cloud === 'aws'">

                    </div>
                </div>

                <div class="hidden-md-up  body__background-sm-down">
                    <div v-if="show_season === 1">
                        <img :src="'/storage/backdrops/original_' + data.series.backdrop" :alt="data.series.name" class="backdrop" width="100%" v-if="data.series.cloud === 'local'">
                        <img :src="lg_backdrop + data.series.backdrop" :alt="data.series.name" class="backdrop" width="100%" v-if="data.series.cloud === 'aws'">

                    </div>

                    <div v-else>
                        <img :src="'/storage/backdrops/original_' + data.season[show_season][0].backdrop" :alt="data.series.name" class="backdrop" width="100%" v-if="data.series.cloud === 'local'">
                        <img :src="lg_backdrop + data.season[show_season][0].backdrop" :alt="data.series.name" class="backdrop" width="100%" v-if="data.series.cloud === 'aws'">

                    </div>

                    <div v-if="data.season !== null">

                        <router-link :to="{name: 'series-player', params: {series_id: data.series.id}}" >
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
                    <div v-else>
                        <h1 style="position: absolute;left: 50%;top: 50%;transform: translate(-50%); color:#fff;">

                            <strong>S</strong>
                            <strong style="color:#03A9F4;">O</strong>
                            <strong>O</strong>
                            <strong style="color:#03A9F4;">N</strong>

                        </h1>
                    </div>
                </div>


                <!-- END Background image -->


                <div class="col-12 show_item__overview">
                    <div class="row">

                        <div class="col-5 col-md-3 col-xl-2 mt-3 poster-sm-down">
                            <div class="poster">
                                <progressive-img :src="'/storage/posters/600_' + data.series.poster"
                                                 placeholder="/themes/default/img/loader-image.png"
                                                 :alt="data.series.name"
                                                 width="100%"
                                                 :aspect-ratio="1.5"
                                                 :blur="0" v-if="data.series.cloud === 'local'"/>
                                <progressive-img :src="md_poster + data.series.poster"
                                                 placeholder="/themes/default/img/loader-image.png"
                                                 :alt="data.series.name"
                                                 width="100%"
                                                 :aspect-ratio="1.5"
                                                 :blur="0" v-if="data.series.cloud === 'aws'"/>
                            </div>
                        </div>

                        <div class="col-12 col-md-8 mt-3 content-sm-down">

                            <div class="__title">
                                <div class="col-12 p-0">
                                    <h5>
                                        <strong>{{data.series.name}}</strong>
                                        <strong class="age-rating">{{data.series.age}}</strong>
                                    </h5>

                                    <div class="year-genre mb-2">

                                        <p class="genre">{{data.series.genre}}</p>
                                        <span class="dot">|</span>
                                        <p class="year">{{data.series.year}}</p>
                                    </div>

                                    <!-- END Name -->
                                    <p>{{data.series.overview}}</p>
                                    <!-- END Overview -->
                                </div>
                            </div>

                            <div class="__more-details">

                                <div class="col-12 p-0 rate">

                                    <h1>{{data.series.rate}}
                                        <small>/10</small>
                                    </h1>

                                </div>

                                <!-- END Rate -->

                            </div>


                            <div class="col-12 p-0 mt-5 __btn-control" v-if="data.season !== null">
                                <div class="btn-group">
                                    <router-link :to="{name: 'series-player', params: {series_id: data.series.id}}" class="btn btn-primary btn-play" role="button">
                                        Watch Season 1 Ep 1
                                    </router-link>
                                </div>
                            </div>

                            <div class="col-12 p-0 mt-5">
                                <div class="row">


                                    <div class="col-12 col-md-8 p-1">
                                        <div class="__cast" v-if="data.casts !== null" >
                                            <h3>
                                                <strong>{{$t('show.cast')}}</strong>
                                            </h3>
                                            <div class="block ml-2 m-1" v-for="(item, index) in data.casts" :key="index">
                                                <router-link :to="{name: 'cast', params: {id: item.id}}">
                                                    <div class="image" @mouseover="castShow = item.id">
                                                        <img :src="item.image" :alt="item.name" width="100%" v-if="data.series.cloud === 'local'"/>
                                                        <img :src="sm_cast + item.image" :alt="item.name" width="100%" v-if="data.series.cloud === 'aws'"/>

                                                        <div class="ovarlay" v-if="castShow === item.id">
                                                            <p class="noselect">{{item.name}}</p>
                                                        </div>
                                                    </div>
                                                </router-link>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- END Cast -->
                            </div>
                        </div>
                    </div>



                    <!-- END Overview -->

                </div>
            </div>

            <div class="col-12 season">
                <div class="gradient"></div>

                <!-- END Cast -->
                <div class="col-12 season-list">
                    <div class="col-12 p-3 text-right hidden-md-up">
                        <div class="dropdown">
                            <button class="btn btn-circle btn-warning" id="dropdownTrendingButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{$t('show.season')+ show_season}}
                            </button>

                            <div class="dropdown-menu  dropdown-menu-right" aria-labelledby="dropdownTrendingButton">
                                <a class="dropdown-item" v-for="(item, index) in data.season" :key="index" :class="{active: show_season == index}" @click="show_season = index">{{$t('show.season') + index}}</a>
                            </div>

                        </div>
                    </div>

                    <div class="col-12 mt-2 p-0 episode" v-if="data.season !== null">
                        <div class="row">

                            <div class="col-md-1 season-list hidden-sm-down" v-if="data.season !== null">
                                <ul class="list-unstyled">
                                    <li v-for="(item, index) in data.season" :key="index">
                                        <div class="season_number" :class="{active: show_season == index}" @click="show_season = index">
                                            <strong class="noselect">S {{ + index}} </strong>
                                        </div>
                                    </li>

                                </ul>
                            </div>
                            <div class="col-12 col-md-11">
                                <div class="row">
                                    <div class="col-12 col-md-4 col-xl-3 col-xxl-1.5 mt-4 mb-4 p-xs-3 p-md-0" v-for="(item, index) in data.season[show_season]"
                                        :key="index">
                                        <div class="episode_contnet">
                                            <div class="backdrop">
                                                <router-link :to="{name: 'series-player-sp', params:{episode_id: item.id, series_id: data.series.id}}">
                                                    <progressive-img :src="'/storage/backdrops/600_' + item.backdrop"
                                                                     :alt="item.name"
                                                                     :blur="0"
                                                                     v-if="item.cloud == 'local'"
                                                    />
                                                    <progressive-img :src="md_backdrop + item.backdrop"
                                                                     :alt="item.name"
                                                                     :blur="0"
                                                                     v-if="item.cloud == 'aws'"
                                                    />

                                                    <div class="details ml-1">
                                                        <h1>
                                                            <strong> {{item.episode_number}}
                                                                <small class="epsiode_name">{{item.name}}</small>
                                                            </strong>
                                                        </h1>
                                                        <small>{{item.overview | truncate(150)}}</small>
                                                    </div>

                                                </router-link>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else>

                        <div class="mt-5 text-center notfound">
                            <h4>
                                <notfound></notfound>

                                <strong>{{$t('show.no_series')}}</strong>

                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

</template>
<script>

    import {
        Carousel,
        Slide
    } from "vue-carousel";
    import {
        mapState
    } from "vuex";
    import collection from "../collection/new.vue";
    import exitButton from "../utils/exit-button.vue";
    import notfound from "../utils/notfound";
    const plyr = require("plyr");
    require("plyr/dist/plyr.css");
    export default {
        name: "series-show",

        data() {
            return {
                castShow: null,
                show_season: 1,
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
            Carousel,
            Slide,
            notfound,
            'collection-modal': collection,
            'exit-button': exitButton
        },

        computed: mapState({
            data: state => state.series.show,
            loading: state => state.series.loading

        }),

        beforeDestroy() {
            this.$store.commit('CLEAR_SERIES_SHOW_DATA');
        },
        mounted() {
            if (this.$auth.isAuthenticated()) {
                this.$store.dispatch("GET_SERIES_DETAILS", this.$route.params.id);
            } else {
                this.$store.dispatch("GET_GHOST_SERIES_DETAILS", this.$route.params.id);
            }
        },
        watch: {
            data() {
                if (this.data.series.runtime <= 60) {
                    this.data.series.runtime = this.data.series.runtime + "m";
                } else if (this.data.series.runtime >= 60) {
                    const minutes = this.data.series.runtime % 60;
                    const hours = Math.floor(this.data.series.runtime / 60);
                    this.data.series.runtime = hours + "h " + minutes + "m";
                }
                // Replice special characters

                this.data.series.genre = this.data.series.genre.replace(/-/g, ", ");

                // Set season
                this.season = this.data.season;

                // Set title
                document.title =  this.data.series.name;

            },
        },

        methods: {
            SIMILAR_SHOW(id) {
                this.$store.dispatch("GET_SERIES_DETAILS", id);
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
                    this.data.series.is_favorite = true;
                } else {
                    this.$router.push({
                        name: 'login'
                    })
                }
            },

            // Delete mvoie or series from data array
            DELETE_FROM_COLLECTION(id, type, index) {
                if (this.$auth.isAuthenticated()) {
                    this.data.series.is_favorite = false;

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
                        this.data.series.is_like = true;
                        this.$store.dispatch("ADD_LIKE", {
                            id,
                            type
                        });
                    } else {
                        // Add false to data array
                        this.data.series.is_like = false;

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
        },

        filters: {
            // Cut word
            truncate(string, value) {
                return string.substring(0, value) + "..";
            }
        }
    };
</script>
