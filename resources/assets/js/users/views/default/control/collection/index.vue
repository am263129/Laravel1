<template>
    <div class="xjkax">

        <div class="col-12 collection-page p-2 p-sm-2 p-lg-2">

            <div class="loader" v-if="loading">
                <loader></loader>
            </div>

            <!-- END Loader -->


            <div class="colleciton-details mt-4">

                <div class="row">
                    <div class="col-12">

                        <div class="title col-12 col-sm-4 float-left">
                            <h4 @click="inputName = true" v-if="!inputName">
                                <strong>{{data.collection.name}}</strong>
                            </h4>
                            <div class="edit" v-if="inputName">
                                <input type="text" class="form-control" placeholder="New name"
                                       v-model="newCollectionName">
                                <button class="btn btn-sm btn-warning mt-2" @click="EditCollection">Edit</button>
                            </div>
                        </div>

                        <div class="delete col-4 float-right text-right">
                            <div @click="DeleteCollection">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"  class="delete-svg" x="0px" y="0px" width="100%" viewBox="0 0 408.483 408.483" style="enable-background:new 0 0 408.483 408.483;" xml:space="preserve" ><g><g>
                                <g>
                                    <path d="M87.748,388.784c0.461,11.01,9.521,19.699,20.539,19.699h191.911c11.018,0,20.078-8.689,20.539-19.699l13.705-289.316    H74.043L87.748,388.784z M247.655,171.329c0-4.61,3.738-8.349,8.35-8.349h13.355c4.609,0,8.35,3.738,8.35,8.349v165.293    c0,4.611-3.738,8.349-8.35,8.349h-13.355c-4.61,0-8.35-3.736-8.35-8.349V171.329z M189.216,171.329    c0-4.61,3.738-8.349,8.349-8.349h13.355c4.609,0,8.349,3.738,8.349,8.349v165.293c0,4.611-3.737,8.349-8.349,8.349h-13.355    c-4.61,0-8.349-3.736-8.349-8.349V171.329L189.216,171.329z M130.775,171.329c0-4.61,3.738-8.349,8.349-8.349h13.356    c4.61,0,8.349,3.738,8.349,8.349v165.293c0,4.611-3.738,8.349-8.349,8.349h-13.356c-4.61,0-8.349-3.736-8.349-8.349V171.329z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#E21B1B"/>
                                    <path d="M343.567,21.043h-88.535V4.305c0-2.377-1.927-4.305-4.305-4.305h-92.971c-2.377,0-4.304,1.928-4.304,4.305v16.737H64.916    c-7.125,0-12.9,5.776-12.9,12.901V74.47h304.451V33.944C356.467,26.819,350.692,21.043,343.567,21.043z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#E21B1B"/>
                                </g>
                            </g></g> </svg>
                            </div>

                        </div>

                    </div>
                </div>

            </div>

            <hr>


            <div class="col-12 mt-5 film-list" v-if="data.data !== null">

                <div class="row">

                    <div class="col-6 col-md-4 col-lg-3 col-xl-1-5 col-xxl-1-5 p-2 p-md-4 animation"
                         v-for="(item, index) in data.data"
                         :key="index">


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


                                                            <div class="poster__remove-collection-icon text-right"
                                                                 v-if="item.is_favorite">

                                                                <img src="/themes/default/img/favor.svg" alt="favor"
                                                                     width="100%"
                                                                     @click.prevent="DELETE_FROM_COLLECTION(item.id, 'movie', index)">

                                                            </div>


                                                        </div>


                                                        <!-- END My List -->


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

                                                </div>


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


                                                                <div class="poster__remove-collection-icon text-right"
                                                                     v-if="item.is_favorite">

                                                                    <img src="/themes/default/img/favor.svg" alt="favor"
                                                                         width="100%"
                                                                         @click.prevent="DELETE_FROM_COLLECTION(item.id, 'series', index)">

                                                                </div>


                                                            </div>


                                                            <!-- END My List -->


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


                                                    </div>

                                                </transition>

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
                    </div>

                </div>

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
    import {Carousel, Slide} from "vue-carousel";
    import loader from "../utils/loader"
    import notfound from "../utils/notfound"

    export default {
        name: "collection",
        components: {
            Carousel,
            Slide,
            loader,
            notfound
        },
        data() {
            return {
                active: null,
                active_movie: null,
                active_series: null,
                show_item: null,
                root_index_show: null,
                inputName: false,
                newCollectionName: '',
                showSlideUpAnimation: false

            };
        },

        beforeRouteUpdate(to, from, next) {
            this.name = to.params.name;
            next()
        },

        mounted() {
            this.$store.dispatch("GET_COLLECTION", this.$route.params.id);
            setTimeout(() => {
                this.showSlideUpAnimation = true;
            }, 100);
        },

        computed: mapState({
            data: state => state.collections.data,
            loading: state => state.collections.collection_loading
        }),

        watch: {
            '$route.params.id': function (id) {
                if (this.$route.name != 'show-movie-collection-page' && this.$route.name != 'show-series-collection-page') {
                    this.$store.dispatch("GET_COLLECTION", id);
                }
            },
            data() {
                this.newCollectionName = this.data.collection.name
            }
        },

        methods: {

            // Delete mvoie or series from data array
            DELETE_FROM_COLLECTION(id, type, index, rootindex) {
                this.data.data.splice(index, 1);
                this.$store.dispatch('DELETE_FROM_COLLECTION', {
                    id,
                    type
                })
            },

            EditCollection() {
                this.data.collection.name = this.newCollectionName;
                this.inputName = false;
                this.$store.dispatch('EDIT_COLLECTION', {
                    id: this.$route.params.id,
                    name: this.newCollectionName
                })
            },

            DeleteCollection() {
                this.$store.dispatch('DELETE_COLLECTION', this.$route.params.id)
            },

            ACTIVE_SLELECTED_MOVIE(id) {
                this.active_movie = id;
            },
            ACTIVE_SLELECTED_SERIES(id) {
                this.active_series = id;
            }
        }
    }
</script>
