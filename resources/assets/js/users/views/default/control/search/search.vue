<template>
    <div v-if="$route.name ==='discover'    ||
                         $route.name ==='series'  ||
                         $route.name ==='collection' ||
                         $route.name ==='kids'    ||
                         $route.name ==='movies'    ||
                         $route.name ==='channels' ? true : false">

        <div class="col-12 search-page">

            <collection-modal @hideModalCollectionCancel="HIDE_COLLECTION_MODAL_CANCEL"
                              @hideModalCollectionSave="HIDE_COLLECTION_MODAL_SAVE"
                              :id="collection.id" :poster="collection.poster" :name="collection.name"
                              :type="collection.type" :index="collection.index"></collection-modal>

            <!-- END Collection component -->


            <div class="row">

                <div class="col-12 col-md-8 offset-md-2 search-page__search">
                    <div class="search-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 56.966 56.966"
                            style="enable-background:new 0 0 56.966 56.966;" xml:space="preserve" width="100%" class="sm-search-svg">
                            <path d="M55.146,51.887L41.588,37.786c3.486-4.144,5.396-9.358,5.396-14.786c0-12.682-10.318-23-23-23s-23,10.318-23,23  s10.318,23,23,23c4.761,0,9.298-1.436,13.177-4.162l13.661,14.208c0.571,0.593,1.339,0.92,2.162,0.92  c0.779,0,1.518-0.297,2.079-0.837C56.255,54.982,56.293,53.08,55.146,51.887z M23.984,6c9.374,0,17,7.626,17,17s-7.626,17-17,17  s-17-7.626-17-17S14.61,6,23.984,6z"
                                fill="#ffffff" />
                        </svg>
                    </div>
                    <input class="form-control mr-sm-2" type="text" :placeholder="$t('home.search')" v-model="search_query">
                </div>

                <!-- END Search input-->

            </div>


            <div class="p-sm-5 p-lg-5" v-if="data.data !== null || data.cast !== null">

                <div class="row search-page__cast ml-md-5 ml-lg-5 ml-md-5" v-if="data.cast !== null">
                    <div class="col-6 col-sm-4 col-md-3 col-lg-3 col-xl-2 mt-2" v-for="(item,index) in data.cast"
                         :key="index" v-if="item.type === 'casts'">
                        <div class="text-center">
                            <router-link :to="{name: 'cast', params: {id: item.id}}">
                                <div class="image" @mouseover="showCast = index">
                                    <img :src="item.image" :alt="item.name" width="100%" v-if="item.cloud === 'local' ">
                                    <img :src="sm_cast +  item.image" :alt="item.name" width="100%" v-if="item.cloud === 'aws' ">

                                </div>
                                <p class="mt-1">{{item.name}}</p>
                            </router-link>
                        </div>
                    </div>
                </div>


                <!-- END search cast -->


                <div class="col-12 mt-5 film-list" v-if="data.data !== null">
                    <div class="row">

                        <div class="col-6 col-md-4 col-lg-3 col-xl-1-5 col-xxl-1-5 p-2 p-md-4 animation"
                             v-for="(item, index) in data.data" :key="index">


                            <div v-if="item.type === 'movie'">

                                <transition name="slide-down-fade">

                                    <div class="animation-ani92X" v-show="showSlideUpAnimation">
                                        <div class="poster" @mouseover="ACTIVE_SLELECTED_MOVIE(item.id)">
                                            <router-link :to="{name: 'show-movie', params: {id: item.id}}">
                                                <div class="poster__backdrop-image">

                                                    <progressive-img :src="'/storage/posters/600_' + item.poster"
                                                                     placeholder="/themes/default/img/loader-image.png"
                                                                     :alt="item.name"
                                                                     width="100%"
                                                                     :aspect-ratio="1.5"
                                                                     :blur="0" v-if="item.cloud === 'local'"/>

                                                    <progressive-img :src="md_poster + item.poster"
                                                                     placeholder="/themes/default/img/loader-image.png"
                                                                     :alt="item.name"
                                                                     width="100%"
                                                                     :aspect-ratio="1.5"
                                                                     :blur="0" v-if="item.cloud === 'aws'"/>

                                                    <div class="poster__backdrop_overlay-info"
                                                         v-if="active_movie === item.id ">

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


                                                                <router-link
                                                                        :to="{name: 'movie-player', params: {id: item.id}}">

                                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px"
                                                                        viewBox="0 0 294.843 294.843" style="enable-background:new 0 0 294.843 294.843;"
                                                                        xml:space="preserve" width="100%" class="play-svg">
                                                                        <g>
                                                                            <g>
                                                                                <path d="M278.527,79.946c-10.324-20.023-25.38-37.704-43.538-51.132c-2.665-1.97-6.421-1.407-8.392,1.257s-1.407,6.421,1.257,8.392   c16.687,12.34,30.521,28.586,40.008,46.983c9.94,19.277,14.98,40.128,14.98,61.976c0,74.671-60.75,135.421-135.421,135.421   S12,222.093,12,147.421S72.75,12,147.421,12c3.313,0,6-2.687,6-6s-2.687-6-6-6C66.133,0,0,66.133,0,147.421   s66.133,147.421,147.421,147.421s147.421-66.133,147.421-147.421C294.842,123.977,289.201,100.645,278.527,79.946z"
                                                                                    data-original="#000000" class="active-path"
                                                                                    data-old_color="#ffffff" fill="#ffffff" />
                                                                                <path d="M109.699,78.969c-1.876,1.067-3.035,3.059-3.035,5.216v131.674c0,3.314,2.687,6,6,6s6-2.686,6-6V94.74l88.833,52.883   l-65.324,42.087c-2.785,1.795-3.589,5.508-1.794,8.293c1.796,2.786,5.508,3.59,8.294,1.794l73.465-47.333   c1.746-1.125,2.786-3.073,2.749-5.15c-0.037-2.077-1.145-3.987-2.93-5.05L115.733,79.029   C113.877,77.926,111.575,77.902,109.699,78.969z"
                                                                                    data-original="#000000" class="active-path"
                                                                                    data-old_color="#ffffff" fill="#ffffff" />
                                                                            </g>
                                                                        </g>
                                                                    </svg>
                                                                </router-link>


                                                            </div>

                                                        </div>

                                                    </div>


                                                </div>
                                            </router-link>
                                        </div>

                                        <div class="mt-2">
                                            <b> {{item.name}} </b> <br>
                                            <small class="text-muted">{{item.genre}}</small>
                                        </div>


                                        <div class="progress"
                                             v-if="item.current_time !== null && $auth.isAuthenticated() === 'active' ">
                                            <div class="progress-bar" role="progressbar"
                                                 :style="{width: (item.current_time/item.duration_time)*100 +'%'}"
                                                 :aria-valuenow="(item.current_time/item.duration_time)*100"
                                                 aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </transition>
                            </div>

                            <div v-if="item.type === 'series'">


                                <transition name="slide-down-fade">

                                    <div class="animation-ani92X" v-show="showSlideUpAnimation">
                                        <div class="poster" @mouseover="ACTIVE_SLELECTED_SERIES(item.id)">

                                            <router-link :to="{name: 'show-series', params: {id: item.id}}">
                                                <div class="poster__backdrop-image">


                                                    <progressive-img :src="'/storage/posters/600_' + item.poster"
                                                                     placeholder="/themes/default/img/loader-image.png"
                                                                     :alt="item.name"
                                                                     width="100%"
                                                                     :aspect-ratio="1.5"
                                                                     :blur="0" v-if="item.cloud === 'local'"/>

                                                    <progressive-img :src="md_poster + item.poster"
                                                                     placeholder="/themes/default/img/loader-image.png"
                                                                     :alt="item.name"
                                                                     width="100%"
                                                                     :aspect-ratio="1.5"
                                                                     :blur="0" v-if="item.cloud === 'aws'"/>

                                                    <transition name="fade">

                                                        <div class="poster__backdrop_overlay-info"
                                                             v-if="active_series === item.id ">

                                                            <div class="header pull-right">

                                                                <div class="mt-0">

                                                                    <div class="poster__add-collection-icon text-right"
                                                                         v-if="! item.is_favorite">

                                                                        <img src="/themes/default/img/infavor.svg" alt="favor" width="100%"  @click.prevent="SHOW_COLLECTION_MODAL(item.id, '/storage/backdrops/600_' + item.backdrop, item.name, 'series', index)"  v-if="item.cloud == 'local'">
                                                                        <img src="/themes/default/img/infavor.svg" alt="favor" width="100%"  @click.prevent="SHOW_COLLECTION_MODAL(item.id, md_backdrop + item.backdrop, item.name, 'series', index)" v-if="item.cloud == 'aws'" >

                                                                    </div>

                                                                    <div class="poster__remove-collection-icon text-right"
                                                                         v-if="item.is_favorite">

                                                                        <img src="/themes/default/img/favor.svg" alt="favor" width="100%" @click.prevent="DELETE_FROM_COLLECTION(item.id, 'series', index)" >

                                                                    </div>


                                                                </div>


                                                                <!-- END My List -->


                                                                <div class="mt-1">

                                                                    <div class="poster__add-like-icon text-right"
                                                                         v-if="! item.is_like">

                                                                        <img src="/themes/default/img/dislike.svg" alt="dislike" width="100%" @click.prevent="ADD_NEW_LIKE(item.id, 'series', index, 'add')">

                                                                    </div>


                                                                    <div class="poster__remove-like-icon  text-right"
                                                                         v-if="item.is_like">

                                                                        <img src="/themes/default/img/like.svg" alt="like" width="100%" @click.prevent="ADD_NEW_LIKE(item.id, 'series', index, 'delete')">

                                                                    </div>

                                                                </div>

                                                                <!-- END Likes -->


                                                            </div>
                                                            <!-- END Header -->


                                                            <div class="body text-center">


                                                                <div class="play">

                                                                    <div v-if="item.already_episode !== 0 && item.already_episode !== null">


                                                                        <router-link
                                                                                :to="{name: 'series-player', params: {series_id: item.id}}">

                                                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px"
                                                                            viewBox="0 0 294.843 294.843" style="enable-background:new 0 0 294.843 294.843;"
                                                                            xml:space="preserve" width="100%" class="play-svg">
                                                                            <g>
                                                                                <g>
                                                                                    <path d="M278.527,79.946c-10.324-20.023-25.38-37.704-43.538-51.132c-2.665-1.97-6.421-1.407-8.392,1.257s-1.407,6.421,1.257,8.392   c16.687,12.34,30.521,28.586,40.008,46.983c9.94,19.277,14.98,40.128,14.98,61.976c0,74.671-60.75,135.421-135.421,135.421   S12,222.093,12,147.421S72.75,12,147.421,12c3.313,0,6-2.687,6-6s-2.687-6-6-6C66.133,0,0,66.133,0,147.421   s66.133,147.421,147.421,147.421s147.421-66.133,147.421-147.421C294.842,123.977,289.201,100.645,278.527,79.946z"
                                                                                        data-original="#000000" class="active-path"
                                                                                        data-old_color="#ffffff" fill="#ffffff"
                                                                                    />
                                                                                    <path d="M109.699,78.969c-1.876,1.067-3.035,3.059-3.035,5.216v131.674c0,3.314,2.687,6,6,6s6-2.686,6-6V94.74l88.833,52.883   l-65.324,42.087c-2.785,1.795-3.589,5.508-1.794,8.293c1.796,2.786,5.508,3.59,8.294,1.794l73.465-47.333   c1.746-1.125,2.786-3.073,2.749-5.15c-0.037-2.077-1.145-3.987-2.93-5.05L115.733,79.029   C113.877,77.926,111.575,77.902,109.699,78.969z"
                                                                                        data-original="#000000" class="active-path"
                                                                                        data-old_color="#ffffff" fill="#ffffff"
                                                                                    />
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


                                                        </div>

                                                    </transition>

                                                </div>
                                            </router-link>

                                        </div>

                                        <!-- END Poster -->


                                        <div class="mt-2">
                                            <b> {{item.name}} </b> <br>
                                            <small class="text-muted">{{item.genre}}</small>
                                        </div>

                                        <div class="progress"
                                             v-if="item.current_time !== null && $auth.isAuthenticated() === 'active' ">
                                            <div class="progress-bar" role="progressbar"
                                                 :style="{width: (item.current_time/item.duration_time)*100 +'%'}"
                                                 :aria-valuenow="(item.current_time/item.duration_time)*100"
                                                 aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>

                                    </div>
                                </transition>
                            </div>


                        </div>

                    </div>


                </div>
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
        name: 'search',
        data() {
            return {
                search_query: '',
                active_series: null,
                active_movie: null,
                show_item: null,
                collection: {
                    id: null,
                    poster: null,
                    name: null,
                    type: null,
                    index: null,
                },
                showSlideUpAnimation: false

            };
        },

        components: {
            Carousel,
            Slide,
            notfound,
            'collection-modal': collection
        },

        computed: mapState({
            data: state => state.search.data,
            loading: state => state.search.loading
        }),

        watch: {
            search_query(val) {
                if (val !== '') {
                    if (this.$auth.isAuthenticated()) {
                        this.$store.dispatch("GET_SEARCH_LIST", val);
                    } else {
                        this.$store.dispatch("GET_GHOST_SEARCH_LIST", val);
                    }


                }
            }
        },

        mounted() {
            if (this.data.length == 0 || this.data === null) {

                if (this.$auth.isAuthenticated()) {
                    this.$store.dispatch("GET_SEARCH_LIST", this.$route.params.id);
                } else {
                    this.$store.dispatch("GET_GHOST_SEARCH_LIST", this.$route.params.id);
                }

            }
            setTimeout(() => {
                this.showSlideUpAnimation = true;
            }, 100);
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
                this.data.data[this.collection.index].is_favorite = true;
            },

            // Delete mvoie or series from data array
            DELETE_FROM_COLLECTION(id, type, index) {
                this.data.data[index].is_favorite = false;

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
                    this.data.data[index].is_like = true;
                    this.$store.dispatch('ADD_LIKE', {
                        id,
                        type
                    })

                } else {
                    // Add false to data array
                    this.data.data[index].is_like = false;

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
                    name: 'show-movie-search-page',
                    params: {
                        id: id
                    }
                })
                location.href = '#' + id
            },

            GET_SERIES(id) {
                this.show_item = id;
                this.$router.push({
                    name: 'show-series-search-page',
                    params: {
                        id: id
                    }
                })
                location.href = '#' + id
            },

        },
    };
</script>
