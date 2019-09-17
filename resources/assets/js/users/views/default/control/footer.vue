<template>
        <div class="col-12 mt-4 p-0 footer">
            <div class="row">
                <footer id="mainFooter">
                    <div class="footer-left">
                        <a class="vertical-logo-container" href="/">
                            <img src="/images/logo.png" alt="logo" width="100%">
                        </a>
                    </div>
                    <div class="footer-right mt-5 mt-sm-0">
                        <ul class="social-nav" v-if="data !== null">
                            <li class="icon-bubble">
                                <a :href="data.social_facebook" target="_blank">
                                    <svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                         viewBox="0 0 408.788 408.788" style="enable-background:new 0 0 408.788 408.788;" xml:space="preserve" width="30px">
                                    <path style="fill:#475993;" d="M353.701,0H55.087C24.665,0,0.002,24.662,0.002,55.085v298.616c0,30.423,24.662,55.085,55.085,55.085
                                        h147.275l0.251-146.078h-37.951c-4.932,0-8.935-3.988-8.954-8.92l-0.182-47.087c-0.019-4.959,3.996-8.989,8.955-8.989h37.882
                                        v-45.498c0-52.8,32.247-81.55,79.348-81.55h38.65c4.945,0,8.955,4.009,8.955,8.955v39.704c0,4.944-4.007,8.952-8.95,8.955
                                        l-23.719,0.011c-25.615,0-30.575,12.172-30.575,30.035v39.389h56.285c5.363,0,9.524,4.683,8.892,10.009l-5.581,47.087
                                        c-0.534,4.506-4.355,7.901-8.892,7.901h-50.453l-0.251,146.078h87.631c30.422,0,55.084-24.662,55.084-55.084V55.085
                                        C408.786,24.662,384.124,0,353.701,0z"/>
                                    </svg>

                                    <span class="hidden">{{$t('app_name')}} on Facebook</span>
                                </a>
                            </li>
                            <li class="icon-bubble">
                                <a :href="data.social_twitter" target="_blank">
                                    <svg version="1.1" width="30px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                             viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                                        <path style="fill:#03A9F4;" d="M512,97.248c-19.04,8.352-39.328,13.888-60.48,16.576c21.76-12.992,38.368-33.408,46.176-58.016
                                            c-20.288,12.096-42.688,20.64-66.56,25.408C411.872,60.704,384.416,48,354.464,48c-58.112,0-104.896,47.168-104.896,104.992
                                            c0,8.32,0.704,16.32,2.432,23.936c-87.264-4.256-164.48-46.08-216.352-109.792c-9.056,15.712-14.368,33.696-14.368,53.056
                                            c0,36.352,18.72,68.576,46.624,87.232c-16.864-0.32-33.408-5.216-47.424-12.928c0,0.32,0,0.736,0,1.152
                                            c0,51.008,36.384,93.376,84.096,103.136c-8.544,2.336-17.856,3.456-27.52,3.456c-6.72,0-13.504-0.384-19.872-1.792
                                            c13.6,41.568,52.192,72.128,98.08,73.12c-35.712,27.936-81.056,44.768-130.144,44.768c-8.608,0-16.864-0.384-25.12-1.44
                                            C46.496,446.88,101.6,464,161.024,464c193.152,0,298.752-160,298.752-298.688c0-4.64-0.16-9.12-0.384-13.568
                                            C480.224,136.96,497.728,118.496,512,97.248z"/>
                                        </svg>

                                    <span class="hidden">{{$t('app_name')}} on Twitter</span>
                                </a>
                            </li>
                            <li class="icon-bubble">
                                <a :href="data.social_instagram" target="_blank">

                                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                             viewBox="0 0 551.034 551.034" style="enable-background:new 0 0 551.034 551.034;" xml:space="preserve" width="30px">
                                        <g>

                                                <linearGradient id="SVGID_1_" gradientUnits="userSpaceOnUse" x1="275.517" y1="4.57" x2="275.517" y2="549.72" gradientTransform="matrix(1 0 0 -1 0 554)">
                                                <stop  offset="0" style="stop-color:#E09B3D"/>
                                                <stop  offset="0.3" style="stop-color:#C74C4D"/>
                                                <stop  offset="0.6" style="stop-color:#C21975"/>
                                                <stop  offset="1" style="stop-color:#7024C4"/>
                                            </linearGradient>
                                            <path style="fill:url(#SVGID_1_);" d="M386.878,0H164.156C73.64,0,0,73.64,0,164.156v222.722
                                                c0,90.516,73.64,164.156,164.156,164.156h222.722c90.516,0,164.156-73.64,164.156-164.156V164.156
                                                C551.033,73.64,477.393,0,386.878,0z M495.6,386.878c0,60.045-48.677,108.722-108.722,108.722H164.156
                                                c-60.045,0-108.722-48.677-108.722-108.722V164.156c0-60.046,48.677-108.722,108.722-108.722h222.722
                                                c60.045,0,108.722,48.676,108.722,108.722L495.6,386.878L495.6,386.878z"/>

                                                <linearGradient id="SVGID_2_" gradientUnits="userSpaceOnUse" x1="275.517" y1="4.57" x2="275.517" y2="549.72" gradientTransform="matrix(1 0 0 -1 0 554)">
                                                <stop  offset="0" style="stop-color:#E09B3D"/>
                                                <stop  offset="0.3" style="stop-color:#C74C4D"/>
                                                <stop  offset="0.6" style="stop-color:#C21975"/>
                                                <stop  offset="1" style="stop-color:#7024C4"/>
                                            </linearGradient>
                                            <path style="fill:url(#SVGID_2_);" d="M275.517,133C196.933,133,133,196.933,133,275.516s63.933,142.517,142.517,142.517
                                                S418.034,354.1,418.034,275.516S354.101,133,275.517,133z M275.517,362.6c-48.095,0-87.083-38.988-87.083-87.083
                                                s38.989-87.083,87.083-87.083c48.095,0,87.083,38.988,87.083,87.083C362.6,323.611,323.611,362.6,275.517,362.6z"/>

                                                <linearGradient id="SVGID_3_" gradientUnits="userSpaceOnUse" x1="418.31" y1="4.57" x2="418.31" y2="549.72" gradientTransform="matrix(1 0 0 -1 0 554)">
                                                <stop  offset="0" style="stop-color:#E09B3D"/>
                                                <stop  offset="0.3" style="stop-color:#C74C4D"/>
                                                <stop  offset="0.6" style="stop-color:#C21975"/>
                                                <stop  offset="1" style="stop-color:#7024C4"/>
                                            </linearGradient>
                                            <circle style="fill:url(#SVGID_3_);" cx="418.31" cy="134.07" r="34.15"/>
                                        </g>
                                        </svg>

                                    <span class="hidden">{{$t('app_name')}} on Instagram</span>
                                </a>
                            </li>
                        </ul>
                        <ul class="legal-nav">
                            <li>
                                <router-link :to="{name: 'about-us'}">{{$t('footer.aboutus')}}</router-link>
                            </li>
                            <li>
                                <router-link :to="{name: 'terms'}">{{$t('footer.terms')}}</router-link>
                            </li>
                            <li>
                                <router-link :to="{name: 'contact-us'}">{{$t('footer.contactus')}}</router-link>
                            </li>
                            <li>
                                <router-link :to="{name: 'privacy'}">{{$t('footer.privacy')}}</router-link>
                            </li>

                            <li class="mt-md-3">© 2018 {{$t('app_name')}}</li>
                        </ul>
                    </div>
                </footer>
            </div>
        </div>

</template>


<script>
    import {
        mapState
    } from "vuex";

    export default {

        computed: mapState({
            data: state => state.home.footer,
        }),

        created() {
            if (this.data === null) {
                this.$store.dispatch("GET_HOME_FOOTER_DETAILS");
            }
        },


    };
</script>