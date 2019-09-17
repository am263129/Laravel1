<template>
    <div class="xjkax">

        <div class="loader" v-if="loading">
            <loader></loader>
        </div>

        <!-- END Loader -->

        <div class="col-12 live-page">

            <div class="carousel mt-1 m-3 mb-5" v-for="(item, rootindex) in data.channels" :key="rootindex" v-if="item.list.length > 0 ">
                <div class="genre ml-1">
                    <h3>
                        <strong>{{item.genre}}
                            <hr>
                        </strong>
                    </h3>
                </div>


                <carousel class="list-carousel" navigationPrevLabel='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" viewBox="0 0 129 129" enable-background="new 0 0 129 129" width="100%" class="arrow-right-svg"><g><g>
                                                            <path d="m88.6,121.3c0.8,0.8 1.8,1.2 2.9,1.2s2.1-0.4 2.9-1.2c1.6-1.6 1.6-4.2 0-5.8l-51-51 51-51c1.6-1.6 1.6-4.2 0-5.8s-4.2-1.6-5.8,0l-54,53.9c-1.6,1.6-1.6,4.2 0,5.8l54,53.9z" data-original="#000000" class="active-path" data-old_color="#ffffff" fill="#ffffff"/>
                                                          </g></g> </svg>' navigationNextLabel='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" viewBox="0 0 129 129" enable-background="new 0 0 129 129" width="100%" class="arrow-left-svg"><g transform="matrix(-1 1.22465e-16 -1.22465e-16 -1 129 129)"><g>
                                                            <path d="m88.6,121.3c0.8,0.8 1.8,1.2 2.9,1.2s2.1-0.4 2.9-1.2c1.6-1.6 1.6-4.2 0-5.8l-51-51 51-51c1.6-1.6 1.6-4.2 0-5.8s-4.2-1.6-5.8,0l-54,53.9c-1.6,1.6-1.6,4.2 0,5.8l54,53.9z" data-original="#000000" class="active-path" data-old_color="#ffffff" fill="#ffffff"/>
                                                          </g></g> </svg>' :navigationEnabled="true" :paginationEnabled="false"
                    :autoplay="false" :scrollPerPage="true" easing="linear" :perPageCustom="[[220,1], [520,3],[768, 5], [1024, 8], [1920, 9]]">

                    <slide class="m-2" v-for="(item, index) in data.channels[rootindex].list" :key="index">
                        <div class="animation-tv">
                            <router-link :to="{name: 'tv-player',params:{id: item.id}}">
                                <div class="tv-logo">
                                    <progressive-img :src="'/storage/posters/' + item.image" :alt="item.name" width="100%" />
                                </div>

                            </router-link>
                        </div>
                        <div class="title text-center mt-3">
                            {{item.name}}
                        </div>
                    </slide>
                </carousel>
            </div>
        </div>

        <div v-if="data.channels == null">
            <div class="mt-5 text-center notfound">

                <h4>
                    <notfound></notfound>
                    <strong>{{$t('home.sorry_no_result')}}</strong>

                </h4>
            </div>
        </div>


    </div>
</template>

<script>
    import {
        mapState
    } from "vuex";
    import {
        Carousel,
        Slide
    } from "vue-carousel";
    import loader from "../utils/loader"
    import notfound from "../utils/notfound"

    export default {
        components: {
            Carousel,
            Slide,
            loader,
            notfound
        },
        computed: mapState({
            data: state => state.tv.data,
            loading: state => state.tv.loading
        }),
        created() {
            if (this.data.length == 0 || this.data === null) {
                if (this.$auth.isAuthenticated()) {
                    this.$store.dispatch("GET_TV_LIST");
                } else {
                    this.$store.dispatch("GET_GHOST_TV_LIST");

                }
            }

        }
    };
</script>