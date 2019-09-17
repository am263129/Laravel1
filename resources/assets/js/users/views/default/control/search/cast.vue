<template>
    <div>

        <div class="col-12 cast-page mt-4 p-2 p-sm-2 p-lg-2">


            <collection-modal @hideModalCollectionCancel="HIDE_COLLECTION_MODAL_CANCEL" @hideModalCollectionSave="HIDE_COLLECTION_MODAL_SAVE"
                :id="collection.id" :poster="collection.poster" :name="collection.name" :type="collection.type" :index="collection.index"></collection-modal>

            <!-- END Collection component -->


            <div class="exit-button" @click="$router.push({name: 'discover'})">

                <svg version="1.1" class="sm-exit-svg" width="100%" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                    x="0px" y="0px" viewBox="0 0 511.999 511.999" style="enable-background:new 0 0 511.999 511.999;" xml:space="preserve">
                    <circle style="fill:#E21B1B;" cx="255.999" cy="255.999" r="255.999" />
                    <g>
                        <rect x="244.002" y="120.008" transform="matrix(0.7071 -0.7071 0.7071 0.7071 -106.0397 256.0022)" style="fill:#FFFFFF;" width="24"
                            height="271.988" />
                        <rect x="120.008" y="244.007" transform="matrix(0.7071 -0.7071 0.7071 0.7071 -106.0428 256.0035)" style="fill:#FFFFFF;" width="271.988"
                            height="24" />
                    </g>
                </svg>

            </div>

            <!-- Back Button -->


            <div class="cast-page__cast">
                <div class="col-8 col-sm-12 hidden-xs-down ipad-up text-center">
                    <div class="image">
                        <img :src="data.cast.image" :alt="data.cast.name" width="100%" v-if="data.cast.cloud === 'local'">
                        <img :src="md_cast + data.cast.image" :alt="data.cast.name" width="100%" v-if="data.cast.cloud === 'aws'">

                    </div>
                    <h4 class="mt-3">
                        <strong>{{data.cast.name}}</strong>
                    </h4>
                </div>
                <div class="col-8 col-sm-12 hidden-sm-up phone  text-center">
                    <div class="image">
                        <img :src="data.cast.image" :alt="data.cast.name" width="100%" v-if="data.cast.cloud === 'local'">
                        <img :src="md_cast + data.cast.image" :alt="data.cast.name" width="100%" v-if="data.cast.cloud === 'aws'">
                    </div>
                    <h4 class="mt-3">
                        <strong>{{data.cast.name}}</strong>
                    </h4>
                </div>
            </div>


            <hr>

            <!--  End Cast Details  -->

            <div class="col-12 mt-5" v-if="data.filmography !== null">
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




                <carousel navigationPrevLabel='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" viewBox="0 0 129 129" enable-background="new 0 0 129 129" width="100%" class="arrow-right-svg"><g><g>
                                                            <path d="m88.6,121.3c0.8,0.8 1.8,1.2 2.9,1.2s2.1-0.4 2.9-1.2c1.6-1.6 1.6-4.2 0-5.8l-51-51 51-51c1.6-1.6 1.6-4.2 0-5.8s-4.2-1.6-5.8,0l-54,53.9c-1.6,1.6-1.6,4.2 0,5.8l54,53.9z" data-original="#000000" class="active-path" data-old_color="#ffffff" fill="#ffffff"/>
                                                          </g></g> </svg>' navigationNextLabel='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" viewBox="0 0 129 129" enable-background="new 0 0 129 129" width="100%" class="arrow-left-svg"><g transform="matrix(-1 1.22465e-16 -1.22465e-16 -1 129 129)"><g>
                                                            <path d="m88.6,121.3c0.8,0.8 1.8,1.2 2.9,1.2s2.1-0.4 2.9-1.2c1.6-1.6 1.6-4.2 0-5.8l-51-51 51-51c1.6-1.6 1.6-4.2 0-5.8s-4.2-1.6-5.8,0l-54,53.9c-1.6,1.6-1.6,4.2 0,5.8l54,53.9z" data-original="#000000" class="active-path" data-old_color="#ffffff" fill="#ffffff"/>
                                                          </g></g> </svg>' :navigationEnabled="true" :paginationEnabled="false"
                    :autoplay="false" :scrollPerPage="true" easing="linear" :perPageCustom="[[220,1], [520,2],[768, 3], [1024,4]]">

                    <slide class="ml-1 mt-4 mb-4 animation" v-for="(item, index) in data.filmography" :key="index">

                        <div v-if="item.type === 'movie'">

                            <div class="poster" @mouseover="ACTIVE_SLELECTED_MOVIE(item.id )">

                                <router-link :to="{name: 'show-movie', params: {id: item.id}}">

                                    <div class="poster__backdrop-image">


                                        <img :src="'/storage/backdrops/600_' + item.backdrop" :alt="item.name" width="100%" v-if="item.cloud === 'local'">
                                        <img :src="md_backdrop + item.backdrop" :alt="item.name" width="100%" v-if="item.cloud === 'aws'">

                                        <div class="poster__backdrop_overlay-info" v-if="active_movie === item.id">

                                            <div class="header pull-right">


                                                <div class="mt-0">

                                                    <div class="poster__add-collection-icon text-right"
                                                         v-if="! item.is_favorite">

                                                        <img src="/themes/default/img/infavor.svg" alt="favor" width="100%"  @click.prevent="SHOW_COLLECTION_MODAL(item.id, '/storage/backdrops/600_' + item.backdrop, item.name, 'movie', index)"  v-if="item.cloud == 'local'">
                                                        <img src="/themes/default/img/infavor.svg" alt="favor" width="100%"  @click.prevent="SHOW_COLLECTION_MODAL(item.id, md_backdrop + item.backdrop, item.name, 'movie', index)" v-if="item.cloud == 'aws'" >

                                                    </div>

                                                    <div class="poster__remove-collection-icon text-right"
                                                         v-if="item.is_favorite">

                                                        <img src="/themes/default/img/favor.svg" alt="favor" width="100%" @click.prevent="DELETE_FROM_COLLECTION(item.id, 'movie', index)" >

                                                    </div>


                                                </div>


                                                <!-- END My List -->


                                                <div class="mt-1">

                                                    <div class="poster__add-like-icon text-right"
                                                         v-if="! item.is_like">

                                                        <img src="/themes/default/img/dislike.svg" alt="dislike" width="100%" @click.prevent="ADD_NEW_LIKE(item.id, 'movie', index, 'add')">

                                                    </div>


                                                    <div class="poster__remove-like-icon  text-right"
                                                         v-if="item.is_like">

                                                        <img src="/themes/default/img/like.svg" alt="like" width="100%" @click.prevent="ADD_NEW_LIKE(item.id, 'movie', index, 'delete')">

                                                    </div>

                                                </div>

                                                <!-- END Likes -->


                                            </div>
                                            <!-- END Header -->


                                            <div class="body text-center">


                                                <div class="play">


                                                    <router-link :to="{name: 'movie-player', params: {id: item.id}}" v-if="item.player == 'local' || item.player == 'link' ">

                                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px"
                                                            viewBox="0 0 294.843 294.843" style="enable-background:new 0 0 294.843 294.843;"
                                                            xml:space="preserve" width="100%" class="play-svg">
                                                            <g>
                                                                <g>
                                                                    <path d="M278.527,79.946c-10.324-20.023-25.38-37.704-43.538-51.132c-2.665-1.97-6.421-1.407-8.392,1.257s-1.407,6.421,1.257,8.392   c16.687,12.34,30.521,28.586,40.008,46.983c9.94,19.277,14.98,40.128,14.98,61.976c0,74.671-60.75,135.421-135.421,135.421   S12,222.093,12,147.421S72.75,12,147.421,12c3.313,0,6-2.687,6-6s-2.687-6-6-6C66.133,0,0,66.133,0,147.421   s66.133,147.421,147.421,147.421s147.421-66.133,147.421-147.421C294.842,123.977,289.201,100.645,278.527,79.946z"
                                                                        data-original="#000000" class="active-path" data-old_color="#ffffff"
                                                                        fill="#ffffff" />
                                                                    <path d="M109.699,78.969c-1.876,1.067-3.035,3.059-3.035,5.216v131.674c0,3.314,2.687,6,6,6s6-2.686,6-6V94.74l88.833,52.883   l-65.324,42.087c-2.785,1.795-3.589,5.508-1.794,8.293c1.796,2.786,5.508,3.59,8.294,1.794l73.465-47.333   c1.746-1.125,2.786-3.073,2.749-5.15c-0.037-2.077-1.145-3.987-2.93-5.05L115.733,79.029   C113.877,77.926,111.575,77.902,109.699,78.969z"
                                                                        data-original="#000000" class="active-path" data-old_color="#ffffff"
                                                                        fill="#ffffff" />
                                                                </g>
                                                            </g>
                                                        </svg>
                                                    </router-link>


                                                    <router-link :to="{name: 'movie-player-embed', params: {id: item.id}}" v-if="item.player == 'embed'  ">

                                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px"
                                                            viewBox="0 0 294.843 294.843" style="enable-background:new 0 0 294.843 294.843;"
                                                            xml:space="preserve" width="100%" class="play-svg">
                                                            <g>
                                                                <g>
                                                                    <path d="M278.527,79.946c-10.324-20.023-25.38-37.704-43.538-51.132c-2.665-1.97-6.421-1.407-8.392,1.257s-1.407,6.421,1.257,8.392   c16.687,12.34,30.521,28.586,40.008,46.983c9.94,19.277,14.98,40.128,14.98,61.976c0,74.671-60.75,135.421-135.421,135.421   S12,222.093,12,147.421S72.75,12,147.421,12c3.313,0,6-2.687,6-6s-2.687-6-6-6C66.133,0,0,66.133,0,147.421   s66.133,147.421,147.421,147.421s147.421-66.133,147.421-147.421C294.842,123.977,289.201,100.645,278.527,79.946z"
                                                                        data-original="#000000" class="active-path" data-old_color="#ffffff"
                                                                        fill="#ffffff" />
                                                                    <path d="M109.699,78.969c-1.876,1.067-3.035,3.059-3.035,5.216v131.674c0,3.314,2.687,6,6,6s6-2.686,6-6V94.74l88.833,52.883   l-65.324,42.087c-2.785,1.795-3.589,5.508-1.794,8.293c1.796,2.786,5.508,3.59,8.294,1.794l73.465-47.333   c1.746-1.125,2.786-3.073,2.749-5.15c-0.037-2.077-1.145-3.987-2.93-5.05L115.733,79.029   C113.877,77.926,111.575,77.902,109.699,78.969z"
                                                                        data-original="#000000" class="active-path" data-old_color="#ffffff"
                                                                        fill="#ffffff" />
                                                                </g>
                                                            </g>
                                                        </svg>
                                                    </router-link>

                                                </div>

                                            </div>


                                            <div class="poster__footer">
                                                <div class="poster__title mt-2">
                                                    <b> {{item.name}} </b>
                                                </div>


                                            </div>

                                        </div>

                                    </div>

                                </router-link>

                            </div>

                            <!-- END Poster -->

                            <div class="progress" v-if="item.current_time !== null && $auth.isAuthenticated() === 'active' ">
                                <div class="progress-bar" role="progressbar" :style="{width: (item.current_time/item.duration_time)*100 +'%'}" :aria-valuenow="(item.current_time/item.duration_time)*100"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>

                        </div>

                        <!-- END Movie -->


                        <div v-if="item.type === 'series'">

                            <div class="poster" @mouseover="ACTIVE_SLELECTED_SERIES(item.id )">
                                <router-link :to="{name: 'show-series', params: {id: item.id}}">

                                    <div class="poster__backdrop-image">

                                        <img :src="'/storage/backdrops/600_' + item.backdrop" :alt="item.name" width="100%" v-if="item.cloud === 'local'">
                                        <img :src="md_backdrop + item.backdrop" :alt="item.name" width="100%" v-if="item.cloud === 'aws'">

                                        <div class="poster__backdrop_overlay-info" v-if="active_series === item.id">

                                            <div class="header pull-right">


                                                <div class="mt-0">

                                                    <div class="poster__add-collection-icon text-right"
                                                         v-if="! item.is_favorite">

                                                        <img src="/themes/default/img/infavor.svg" alt="favor" width="100%"  @click.prevent="SHOW_COLLECTION_MODAL(item.id, '/storage/backdrops/600_' + item.backdrop, item.name, 'search', index)"  v-if="item.cloud == 'local'">
                                                        <img src="/themes/default/img/infavor.svg" alt="favor" width="100%"  @click.prevent="SHOW_COLLECTION_MODAL(item.id, md_backdrop + item.backdrop, item.name, 'search', index)" v-if="item.cloud == 'aws'" >

                                                    </div>

                                                    <div class="poster__remove-collection-icon text-right"
                                                         v-if="item.is_favorite">

                                                        <img src="/themes/default/img/favor.svg" alt="favor" width="100%" @click.prevent="DELETE_FROM_COLLECTION(item.id, 'search', index)" >

                                                    </div>


                                                </div>


                                                <!-- END My List -->


                                                <div class="mt-1">

                                                    <div class="poster__add-like-icon text-right"
                                                         v-if="! item.is_like">

                                                        <img src="/themes/default/img/dislike.svg" alt="dislike" width="100%" @click.prevent="ADD_NEW_LIKE(item.id, 'search', index, 'add')">

                                                    </div>


                                                    <div class="poster__remove-like-icon  text-right"
                                                         v-if="item.is_like">

                                                        <img src="/themes/default/img/like.svg" alt="like" width="100%" @click.prevent="ADD_NEW_LIKE(item.id, 'search', index, 'delete')">

                                                    </div>

                                                </div>

                                                <!-- END Likes -->



                                            </div>
                                            <!-- END Header -->


                                            <div class="body text-center">


                                                <div class="play">

                                                    <div v-if="item.already_episode !== 0 && item.already_episode !== null">


                                                        <router-link :to="{name: 'series-player-current', params: {series_id: item.id}}" v-if="item.player == 'default' ">

                                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px"
                                                                viewBox="0 0 294.843 294.843" style="enable-background:new 0 0 294.843 294.843;"
                                                                xml:space="preserve" width="100%" class="play-svg">
                                                                <g>
                                                                    <g>
                                                                        <path d="M278.527,79.946c-10.324-20.023-25.38-37.704-43.538-51.132c-2.665-1.97-6.421-1.407-8.392,1.257s-1.407,6.421,1.257,8.392   c16.687,12.34,30.521,28.586,40.008,46.983c9.94,19.277,14.98,40.128,14.98,61.976c0,74.671-60.75,135.421-135.421,135.421   S12,222.093,12,147.421S72.75,12,147.421,12c3.313,0,6-2.687,6-6s-2.687-6-6-6C66.133,0,0,66.133,0,147.421   s66.133,147.421,147.421,147.421s147.421-66.133,147.421-147.421C294.842,123.977,289.201,100.645,278.527,79.946z"
                                                                            data-original="#000000" class="active-path" data-old_color="#ffffff"
                                                                            fill="#ffffff" />
                                                                        <path d="M109.699,78.969c-1.876,1.067-3.035,3.059-3.035,5.216v131.674c0,3.314,2.687,6,6,6s6-2.686,6-6V94.74l88.833,52.883   l-65.324,42.087c-2.785,1.795-3.589,5.508-1.794,8.293c1.796,2.786,5.508,3.59,8.294,1.794l73.465-47.333   c1.746-1.125,2.786-3.073,2.749-5.15c-0.037-2.077-1.145-3.987-2.93-5.05L115.733,79.029   C113.877,77.926,111.575,77.902,109.699,78.969z"
                                                                            data-original="#000000" class="active-path" data-old_color="#ffffff"
                                                                            fill="#ffffff" />
                                                                    </g>
                                                                </g>
                                                            </svg>
                                                        </router-link>


                                                        <router-link :to="{name: 'series-player-embed-current', params: {series_id: item.id}}" v-if="item.player == 'embed'  ">

                                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px"
                                                                viewBox="0 0 294.843 294.843" style="enable-background:new 0 0 294.843 294.843;"
                                                                xml:space="preserve" width="100%" class="play-svg">
                                                                <g>
                                                                    <g>
                                                                        <path d="M278.527,79.946c-10.324-20.023-25.38-37.704-43.538-51.132c-2.665-1.97-6.421-1.407-8.392,1.257s-1.407,6.421,1.257,8.392   c16.687,12.34,30.521,28.586,40.008,46.983c9.94,19.277,14.98,40.128,14.98,61.976c0,74.671-60.75,135.421-135.421,135.421   S12,222.093,12,147.421S72.75,12,147.421,12c3.313,0,6-2.687,6-6s-2.687-6-6-6C66.133,0,0,66.133,0,147.421   s66.133,147.421,147.421,147.421s147.421-66.133,147.421-147.421C294.842,123.977,289.201,100.645,278.527,79.946z"
                                                                            data-original="#000000" class="active-path" data-old_color="#ffffff"
                                                                            fill="#ffffff" />
                                                                        <path d="M109.699,78.969c-1.876,1.067-3.035,3.059-3.035,5.216v131.674c0,3.314,2.687,6,6,6s6-2.686,6-6V94.74l88.833,52.883   l-65.324,42.087c-2.785,1.795-3.589,5.508-1.794,8.293c1.796,2.786,5.508,3.59,8.294,1.794l73.465-47.333   c1.746-1.125,2.786-3.073,2.749-5.15c-0.037-2.077-1.145-3.987-2.93-5.05L115.733,79.029   C113.877,77.926,111.575,77.902,109.699,78.969z"
                                                                            data-original="#000000" class="active-path" data-old_color="#ffffff"
                                                                            fill="#ffffff" />
                                                                    </g>
                                                                </g>
                                                            </svg>
                                                        </router-link>

                                                    </div>

                                                    <div v-else>
                                                        <h3>
                                                            <strong>{{$t('show.soon')}}</strong>
                                                        </h3>
                                                    </div>

                                                </div>

                                            </div>


                                            <div class="poster__footer">
                                                <div class="poster__title mt-2">
                                                    <b> {{item.name}} </b>
                                                </div>
                                            </div>

                                            <!-- END Ovarlay -->

                                        </div>

                                    </div>

                                </router-link>

                            </div>

                            <!-- END Poster -->

                            <div class="progress" v-if="item.current_time !== null && $auth.isAuthenticated() === 'active' ">
                                <div class="progress-bar" role="progressbar" :style="{width: (item.current_time/item.duration_time)*100 +'%'}" :aria-valuenow="(item.current_time/item.duration_time)*100"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>


                        </div>


                    </slide>

                </carousel>



                <!-- END Series -->
                <!-- END Kids List -->

            </div>

            <div v-else>
                <div class="mt-5 text-center notfound">

                    <h4>
                        <notfound></notfound>
                        <strong>{{$t('home.sorry_no_result')}}</strong>

                    </h4>
                </div>
            </div>

            <!--  Filmography End -->

        </div>


    </div>
</template>
<script>
    import {
        mapState
    } from "vuex";
    import collection from '../collection/new.vue';
    import {
        Carousel,
        Slide
    } from "vue-carousel";
    import notfound from "../utils/notfound"

    export default {
        name: "cast",

        data() {
            return {
                active_series: null,
                active_movie: null,
                show_item: null,
                collection: {
                    id: null,
                    poster: null,
                    name: null,
                    type: null,
                    index: null,
                }
            };
        },

        computed: mapState({
            data: state => state.casts.data,
            loading: state => state.casts.loading,
            showSearchPageEvent: state => state.event.show_search_page
        }),

        components: {
            Carousel,
            Slide,
            notfound,
            'collection-modal': collection
        },

        beforeRouteUpdate(to, from, next) {
            this.name = to.params.name;
            next()
        },

        mounted() {
            if (this.$auth.isAuthenticated()) {
                this.$store.dispatch("GET_CAST_DETAILS", this.$route.params.id);

            } else {
                this.$store.dispatch("GET_GHOST_CAST_DETAILS", this.$route.params.id);
            }


        },
        methods: {

            // Show modal of collection
            SHOW_COLLECTION_MODAL(id, backdrop, name, type, index) {
                this.collection.id = id;
                this.collection.poster = backdrop;
                this.collection.name = name;
                this.collection.type = type;
                this.collection.index = index;
            },

            // Hide modal of collection
            HIDE_COLLECTION_MODAL_CANCEL() {
                this.collection.id = null;
            },

            // Hide modal and update array
            HIDE_COLLECTION_MODAL_SAVE() {
                this.collection.id = null;
                this.data.filmography[this.collection.index].is_favorite = true;
            },

            // Delete mvoie or series from data array
            DELETE_FROM_COLLECTION(id, type, index) {
                this.data.filmography[index].is_favorite = false;

                this.$store.dispatch('DELETE_FROM_COLLECTION', {
                    id,
                    type
                })
            },


            // Add new like or delete it
            // Params type1 detected if add or delete
            ADD_NEW_LIKE(id, type, index, type1) {
                if (type1 === 'add') {
                    // Add true to data array
                    this.data.filmography[index].is_like = true;
                    this.$store.dispatch('ADD_LIKE', {
                        id,
                        type
                    })

                } else {
                    // Add false to data array
                    this.data.filmography[index].is_like = false;

                    this.$store.dispatch('ADD_LIKE', {
                        id,
                        type
                    })
                }

            },

            ACTIVE_SLELECTED_SERIES(id) {
                this.active_movie = null;
                this.active_series = id;

            },
            ACTIVE_SLELECTED_MOVIE(id) {
                this.active_series = null;
                this.active_movie = id;
            },

            GET_MOVIE(id) {
                this.show_item = id;
                this.$router.push({
                    name: 'show-movie-cast-page',
                    params: {
                        id: id
                    }
                });
                location.href = '#show-scroll';
            },

            GET_SERIES(id) {
                this.show_item = id;
                this.$router.push({
                    name: 'show-series-cast-page',
                    params: {
                        id: id
                    }
                });
                location.href = '#show-scroll';
            }


        }
    }
</script>
