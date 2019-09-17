<template>
    <div class="privacy">


        <div class="exit-icon" @click="$Helper.home()">
            <exit-button></exit-button>
        </div>

        <!-- EXIT -->
        
        <div class="col-4">
            <a class="navbar-brand" href="/">
                <img src="/images/logo.png" alt="logo" width="50">
            </a>
        </div>
        <div class="col-12 p-5">
            <div class="title">
                <h2>{{$t('app_name')}} {{$t('footer.privacy')}}</h2>
                <hr>
            </div>
            <div v-if="data !== null">

                <div class="body" v-html="data.privacy"></div>
            </div>
        </div>
    </div>
</template>
<script>
    import {
        mapState
    } from "vuex";
    import exitButton from '../utils/exit-button';

    export default {
        components: {
            'exit-button': exitButton
        },

        computed: mapState({
            data: state => state.home.footer
        }),

        created() {
            if (this.data.length == 0 || this.data === null) {
                this.$store.dispatch("GET_HOME_FOOTER_DETAILS");
            }
        }
    };
</script>