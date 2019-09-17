<template>
    <div>
        <div class="settings">

            <div class="row">

                <div class="exit-icon" @click="$Helper.home()">
                    <exit-button></exit-button>
                </div>

                <!-- EXIT -->


                <div class="col-12 col-sm-8 offset-sm-2 p-sm-3 mt-5 h-100">
                    <div class="language">
                        <div class="title">
                            <p>{{$t('setting.select_language')}}</p>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6 col-sm-4 col-lg-3 col-xl-2 mt-4 p-md-1 text-center">
                                <div class="settings__flag" :class="{settings__active:active === 'ar'}" @click="SET_LANGUAGE('ar')">
                                    <img src="/themes/default/img/flags/saudi-arabia.svg" alt="iraq flag" class="flag-svg" width="100%">

                                    <br>
                                    <strong>Arabic</strong>
                                </div>
                            </div>
                            <div class="col-6 col-sm-4 col-lg-3 col-xl-2 mt-4 p-md-1 text-center ">

                                <div class="settings__flag" :class="{settings__active:active === 'en'}" @click="SET_LANGUAGE('en')">
                                    <img src="/themes/default/img/flags/usa.svg" alt="usa flag" class="flag-svg" width="100%">
                                    <strong>English</strong>
                                </div>

                            </div>
                            <div class="col-6 col-sm-4 col-lg-3 col-xl-2 mt-4 p-md-1 text-center">
                                <div class="settings__flag" :class="{settings__active:active === 'fr'}" @click="SET_LANGUAGE('fr')">
                                    <img src="/themes/default/img/flags/france.svg" alt="france flag" class="flag-svg" width="100%">
                                    <strong>French</strong>
                                </div>
                            </div>
                            <div class="col-6 col-sm-4 col-lg-3 col-xl-2 mt-4 p-md-1 text-center">
                                <div class="settings__flag" :class="{settings__active:active === 'de'}" @click="SET_LANGUAGE('de')">
                                    <img src="/themes/default/img/flags/german.svg" alt="german flag" class="flag-svg" width="100%">

                                    <strong>German</strong>
                                </div>
                            </div>
                            <div class="col-6 col-sm-4 col-lg-3 col-xl-2 mt-4 p-md-1 text-center">
                                <div class="settings__flag" :class="{settings__active:active === 'he'}" @click="SET_LANGUAGE('he')">
                                    <img src="/themes/default/img/flags/israel.svg" alt="israel flag" class="flag-svg" width="100%">

                                    <br>
                                    <strong>Hebrew</strong>
                                </div>
                            </div>
                            <div class="col-6 col-sm-4 col-lg-3 col-xl-2 mt-4 p-md-1 text-center">
                                <div class="settings__flag" :class="{settings__active:active === 'in'}" @click="SET_LANGUAGE('in')">
                                    <img src="/themes/default/img/flags/india.svg" alt="india flag" class="flag-svg" width="100%">

                                    <br>
                                    <strong>Hindi</strong>
                                </div>
                            </div>

                            <div class="col-6 col-sm-4 col-lg-3 col-xl-2 mt-4 p-md-1 text-center">
                                <div class="settings__flag" :class="{settings__active:active === 'ja'}" @click="SET_LANGUAGE('ja')">
                                    <img src="/themes/default/img/flags/japan.svg" alt="japan flag" class="flag-svg" width="100%">
                                    <br>
                                    <strong>Japanese</strong>
                                </div>
                            </div>
                            <div class="col-6 col-sm-4 col-lg-3 col-xl-2 mt-4 p-md-1 text-center">
                                <div class="settings__flag" :class="{settings__active:active === 'es'}" @click="SET_LANGUAGE('es')">
                                    <img src="/themes/default/img/flags/spanish.svg" alt="spanish flag" class="flag-svg" width="100%">

                                    <strong>Spanish</strong>
                                </div>
                            </div>

                            <div class="col-6 col-sm-4 col-lg-3 col-xl-2 mt-4 p-md-1 text-center">
                                <div class="settings__flag" :class="{settings__active:active === 'sw'}" @click="SET_LANGUAGE('sw')">

                                    <img src="/themes/default/img/flags/swedish.svg" alt="swedish flag" class="flag-svg" width="100%">

                                    <strong>Swedish</strong>
                                </div>
                            </div>
                            <div class="col-6 col-sm-4 col-lg-3 col-xl-2 mt-4 p-md-1 text-center">
                                <div class="settings__flag" :class="{settings__active:active === 'tr'}" @click="SET_LANGUAGE('tr')">

                                    <img src="/themes/default/img/flags/turkey.svg" alt="turkey flag" class="flag-svg" width="100%">

                                   <strong >Turkish</strong>
                                    <div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import exitButton from '../utils/exit-button.vue';

    export default {
        data() {
            return {
                active: null,
            }
        },

        components: {
            'exit-button': exitButton,
        },

        mounted() {
            const lang = localStorage.getItem('language');
            if (lang !== null) {
                this.$i18n.locale = lang;
                this.active = lang;
            } else {
                this.active = 'en';
                this.$i18n.locale = 'en';
            }
        },
        methods: {
            SET_LANGUAGE(lang) {
                this.$i18n.locale = lang;
                this.active = lang;
                localStorage.setItem('language', lang);
                this.$store.dispatch("SET_LANGUAGE", lang);
            },
        }
    };
</script>