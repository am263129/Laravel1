<template>
    <div>
        <div class="settings">

            <div class="row">

                <div class="exit-icon" @click="$Helper.home()">
                    <exit-button></exit-button>
                </div>


                <!-- EXIT -->


            <div class="col-12 col-sm-8 offset-sm-2 p-sm-3 mt-5 h-100">

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


                <div class="title">
                    <p>{{$t('setting.viewing_history')}}</p>
                </div>
                <hr>
                <table class="table table-inverse">
                    <thead>
                        <tr>
                            <th>{{$t('setting.name')}}</th>
                            <th>{{$t('setting.watch_date')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item,key) in viewing_history.data" :key="key">
                            <td v-if="item.type === 'movie'">
                           <router-link :to="{name: 'show-movie', params:{id: item.id }}">
                            {{item.name}}
                            </router-link>
                            </td>
                            <td v-if="item.type === 'series'">
                             <router-link :to="{name: 'show-series', params:{id: item.id }}">
                              {{ item.name + ' ('+ $t('show.episode ') + item.episode_number + ')' }}
                             </router-link>
                             </td>
                            <td>{{item.created_at}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>

</template>

<script>
    import {
        mapState
    } from "vuex";
    import exitButton from '../utils/exit-button.vue';
    const alertify = require("alertify.js");
    export default {
        data() {
            return {
                showModelError: false,
                error: null
            };
        },

        components: {
            'exit-button': exitButton
        },

        computed: mapState({
            loading: state => state.auth.loading,
            viewing_history: state => state.auth.items
        }),

        mounted() {
                this.$store.dispatch("GET_VIEWING_HISTORY");
        },
    }
</script>

<style scoped>
.table-inverse a {
    color: #2196F3 !important;
}
</style>
