<template>
    <div>
        <div class="loader" v-if="spinner_load">
            <loader></loader>
        </div>

        <div class="wolf-player" v-if="!spinner_load">
            <div class="report-modal" v-if="show_report">
                <div class="col-12 col-sm-8 col-lg-6 offset-sm-2 offset-lg-3" id="modal">
                    <div class="header">
                        <span id="close" @click="show_report = false">
                        <svg version="1.1" class="sm-exit-svg" width="100%" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                         viewBox="0 0 511.999 511.999" style="enable-background:new 0 0 511.999 511.999;" xml:space="preserve">
                            <circle style="fill:#E21B1B;" cx="255.999" cy="255.999" r="255.999"/>
                            <g>
                                    <rect x="244.002" y="120.008" transform="matrix(0.7071 -0.7071 0.7071 0.7071 -106.0397 256.0022)" style="fill:#FFFFFF;" width="24" height="271.988"/>
                                    <rect x="120.008" y="244.007" transform="matrix(0.7071 -0.7071 0.7071 0.7071 -106.0428 256.0035)" style="fill:#FFFFFF;" width="271.988" height="24"/>
                            </g>
                    </svg>
              </span>
                    </div>
                    <div class="body">
                        <h1>{{$t('report.what_happening')}}
                            <b style="color:dodgerblue">?</b>
                        </h1>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="custom-control-input" name="radio_group_1" v-validate="'required'"
                                       type="radio" value="1" v-model="report_problem_type">
                                <span class="custom-control-indicator"></span>
                                <h4>{{$t('report.labeling_problem')}}</h4>
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="custom-control-input" name="radio_group_1" type="radio" value="2"
                                       v-model="report_problem_type">
                                <span class="custom-control-indicator"></span>
                                <h4>{{$t('report.video_problem')}}</h4>
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="custom-control-input" name="radio_group_1" type="radio" value="3"
                                       v-model="report_problem_type">
                                <span class="custom-control-indicator"></span>
                                <h4>{{$t('report.sound_problem')}}</h4>
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="custom-control-input" name="radio_group_1" type="radio" value="4"
                                       v-model="report_problem_type">
                                <span class="custom-control-indicator"></span>
                                <h4>{{$t('report.caption_problem')}}</h4>
                            </label>
                        </div>
                        <span class="help is-danger" v-show="errors.has('radio_group_1')">Please Choose The Problem</span>

                        <div class="textarea-details">
                            <h3>{{$t('report.more_details')}}</h3>
                            <textarea v-model="report_details" :placeholder="$t('report.more_details')" cols="40" rows="6"></textarea>
                        </div>
                        <div class="my-2">
                            <button v-if="! report_button" class="btn btn-warning" @click="SEND_REPORT">
                                {{$t('report.send')}}
                            </button>
                            <button class="btn btn-warning" v-if="report_button"><i id="btn-progress"></i> Loading</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- END REPORT -->

            <div class="col-12 col-md-8 offset-md-2 mt-5">
                <div class="jw-player">
                    <div id="tvshow-player" style="position: relative;"></div>
                    <div class="firstLoader"
                         v-if="loadPlayerFirst"
                    >
                        <loader style="
                            position: absolute;
                            top: 50%;
                            left: 50%;
                            transform: translate(-50%, -50%);
                        "></loader>
                    </div>

                    <div class="changeLoader"
                         v-if="loadPlayerChange"
                    >
                        <loader style="
                            position: absolute;
                            top: 50%;
                            left: 50%;
                            transform: translate(-50%, -50%);
                        "></loader>
                    </div>
                </div>
                <playerShare v-bind:id="$route.params.id" type="tv" />


                <div class="episode-details d-inline mt-3" v-if="! spinner_load">
                    <div class="episode-name mt-1">
                        <div class="text d-inline">
                            <p> {{data.current_episode.name}}</p>
                        </div>
                        <div class="report" @click="show_report = true" v-if="$auth.isAuthenticated() ">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 486.463 486.463" style="enable-background:new 0 0 486.463 486.463;" xml:space="preserve" width="20px">
                            <g>
                                <g>
                                    <path d="M243.225,333.382c-13.6,0-25,11.4-25,25s11.4,25,25,25c13.1,0,25-11.4,24.4-24.4    C268.225,344.682,256.925,333.382,243.225,333.382z" fill="#a0a0a0"/>
                                    <path d="M474.625,421.982c15.7-27.1,15.8-59.4,0.2-86.4l-156.6-271.2c-15.5-27.3-43.5-43.5-74.9-43.5s-59.4,16.3-74.9,43.4    l-156.8,271.5c-15.6,27.3-15.5,59.8,0.3,86.9c15.6,26.8,43.5,42.9,74.7,42.9h312.8    C430.725,465.582,458.825,449.282,474.625,421.982z M440.625,402.382c-8.7,15-24.1,23.9-41.3,23.9h-312.8    c-17,0-32.3-8.7-40.8-23.4c-8.6-14.9-8.7-32.7-0.1-47.7l156.8-271.4c8.5-14.9,23.7-23.7,40.9-23.7c17.1,0,32.4,8.9,40.9,23.8    l156.7,271.4C449.325,369.882,449.225,387.482,440.625,402.382z" fill="#a0a0a0"/>
                                    <path d="M237.025,157.882c-11.9,3.4-19.3,14.2-19.3,27.3c0.6,7.9,1.1,15.9,1.7,23.8c1.7,30.1,3.4,59.6,5.1,89.7    c0.6,10.2,8.5,17.6,18.7,17.6c10.2,0,18.2-7.9,18.7-18.2c0-6.2,0-11.9,0.6-18.2c1.1-19.3,2.3-38.6,3.4-57.9    c0.6-12.5,1.7-25,2.3-37.5c0-4.5-0.6-8.5-2.3-12.5C260.825,160.782,248.925,155.082,237.025,157.882z" fill="#a0a0a0"/>
                                </g>
                            </g>
                        </svg>

                        </div>
                    </div>

                    <div class="list-info">

                        <ul class="list-unstyled">
                            <li>
                                {{data.series.name}}
                            </li>
                            <li class="split m-3">|</li>

                            <li class="episode-number">
                                S{{data.current_episode.season_number}} E{{data.current_episode.episode_number}}
                            </li>
                        </ul>

                        <div class="overview">
                            <p> {{data.current_episode.overview}}</p>
                        </div>
                    </div>
                </div>

                <div class="episode-list mb-5">

                    <div class="dropdown m-2" v-if="! spinner_load">
                        <button class="btn btn-sm btn-warning" data-toggle="dropdown" id="dropdownSettings" aria-haspopup="true" aria-expanded="false">{{$t('show.season')}} {{season_number_dropdown}}</button>
                        <div class="dropdown-menu" aria-labelledby="dropdownSettings">
                            <a class="dropdown-item" v-for="(item, index) in data.seasons" :key="index" v-if="data.current_episode.season_number === item.season_number">{{$t('show.season')}} {{item.season_number}}</a>
                            <a class="dropdown-item" v-for="(item, index) in data.seasons" :key="index" @click="GET_SEASON(item.season_number); season_number_dropdown = item.season_number" v-if="data.current_episode.season_number != item.season_number">{{$t('show.season')}} {{item.season_number}}</a>
                        </div>
                    </div>

                    <carousel class="list-carousel" navigationPrevLabel='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" viewBox="0 0 129 129" enable-background="new 0 0 129 129" width="100%" class="arrow-right-svg"><g><g>
                                                                <path d="m88.6,121.3c0.8,0.8 1.8,1.2 2.9,1.2s2.1-0.4 2.9-1.2c1.6-1.6 1.6-4.2 0-5.8l-51-51 51-51c1.6-1.6 1.6-4.2 0-5.8s-4.2-1.6-5.8,0l-54,53.9c-1.6,1.6-1.6,4.2 0,5.8l54,53.9z" data-original="#000000" class="active-path" data-old_color="#ffffff" fill="#ffffff"/>
                                                              </g></g> </svg>' navigationNextLabel='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" viewBox="0 0 129 129" enable-background="new 0 0 129 129" width="100%" class="arrow-left-svg"><g transform="matrix(-1 1.22465e-16 -1.22465e-16 -1 129 129)"><g>
                                                                <path d="m88.6,121.3c0.8,0.8 1.8,1.2 2.9,1.2s2.1-0.4 2.9-1.2c1.6-1.6 1.6-4.2 0-5.8l-51-51 51-51c1.6-1.6 1.6-4.2 0-5.8s-4.2-1.6-5.8,0l-54,53.9c-1.6,1.6-1.6,4.2 0,5.8l54,53.9z" data-original="#000000" class="active-path" data-old_color="#ffffff" fill="#ffffff"/>
                                                              </g></g> </svg>' :navigationEnabled="true" :paginationEnabled="false" :autoplay="true" :autoplayTimeout="5000" easing="ease-in-out" :perPageCustom="[[220,2], [420,3],[768, 4], [1024, 5]]">
                        <slide v-for="(item,index) in data.current_season" :key="index" class="col-6 col-md-4 col-lg-3 col-xl-2 m-2 animation ">

                            <div class="episode" @click="CHANGE_EPISODE(item.id, item.series_id)" style="cursor: pointer;">
                                <progressive-img :src="item.backdrop" :alt="item.name" width="100%" :blur="1" v-if="item.cloud === 'local' " />
                                <progressive-img :src="item.backdrop" :alt="item.name" width="100%" :blur="1" v-if="item.cloud === 'aws' " />

                                <div class="title mt-1">
                                    <p><strong>E {{item.episode_number}} | {{FOMRAT_DATE(item.created_at )}}</strong></p>
                                    <small class="text-muted">{{item.name}}</small>
                                </div>
                            </div>

                        </slide>
                    </carousel>

                </div>

            </div>
        </div>

    </div>
</template>

<script>
    const alertify = require("alertify.js");
    import {
        mapState
    } from "vuex";
    import {
        Carousel,
        Slide
    } from "vue-carousel";
    import loader from "../utils/loader"
    import playerShare from "./player-share";

    export default {

        name: "series-player",
        components: {
            Carousel,
            Slide,
            loader,
            playerShare
        },
        data() {
            return {
                episode_title: "",
                series_title: "",
                url_subtitle: "",
                seasonHide: true,
                active: "",
                activeSeason: "",
                report_problem_type: null,
                report_details: null,
                report_button: false,
                episode_changed: false,
                timeRequest: 150,
                show_report: false,
                loadPlayerFirst: true,
                loadPlayerChange: false,
                stop_player: false,
                season_number_dropdown: null,
                jwPlayer: null
            };
        },
        created() {
            console.warn(this.$route.query);
            if(this.$route.query.referral) {
                this.$store.dispatch("REFER", {id: this.$route.params.id, referral: this.$route.query.referral, type: 'series'});
            }

       },

        computed: mapState({
            data: state => state.player.series_data,
            spinner_load: state => state.player.player_spinner,
            season_playlist_active: state => state.player.season_playlist_active,
        }),

        mounted() {
            if (this.$route.params.episode_id !== undefined && this.$route.params.series_id !== undefined) {
                if (this.$auth.isAuthenticated()) {
                    this.$store.dispatch("LOAD_SERIES_PLAYER", {
                        episode_id: this.$route.params.episode_id,
                        series_id: this.$route.params.series_id
                    });
                }

            } else if (this.$route.params.series_id !== undefined) {
                // Run video
                if (this.$auth.isAuthenticated()) {
                    this.$store.dispatch("LOAD_SERIES_PLAYER", {
                        series_id: this.$route.params.series_id
                    });
                }

            } else {
                this.$route.push('404');
            }

        },

        watch: {

            data() {
                // dropdown season number
                this.season_number_dropdown = this.data.current_episode.season_number;

                this.loadPlayer = true;

                // Mute change on episode
                if(this.jwPlayer !== null) {
                    jwplayer().setMute(true);
                }

                if(! this.loadPlayerChange) {
                    this.loadPlayerFirst = true;
                }

                setTimeout(() => {
                    this.jwPlayer = jwplayer("tvshow-player").setup({
                            "playlist": this.data.playlist,
                            "cast": {},
                            "autostart": true
                        });

                    // Load custom video file on error
                    this.jwPlayer.on('error', () => {
                        this.jwPlayer.load({
                            file: "//content.jwplatform.com/videos/7RtXk3vl-52qL9xLP.mp4",
                            image: "//content.jwplatform.com/thumbs/7RtXk3vl-480.jpg"
                        });
                        this.jwPlayer.play();

                    });

                    this.jwPlayer.on('ready', () => {
                        if (this.episode_changed) {
                            jwplayer().playlistItem(0)
                            this.jwPlayer.play();
                        } else {
                            this.jwPlayer.play();
                        }

                        if (this.$auth.isAuthenticated()) {
                            if (this.data.current_episode.current_time != null) {
                                jwplayer().seek(this.data.current_episode.current_time);
                            }
                        }

                        // Check subtitle
                        if (localStorage.getItem('caption') !== "" && localStorage.getItem('caption') != "undefined") {

                            const parsedCaption = JSON.parse(localStorage.getItem('caption'));
                            jwplayer().setCaptions(parsedCaption);
                        }

                        this.loadPlayerFirst = false;
                        this.loadPlayerChange = false;
                        jwplayer().setMute(false);
                        this.jwPlayer.play();

                    });

                    if (this.$auth.isAuthenticated()) {
                        if (this.data.current_episode.current_time != null) {
                            jwplayer().seek(this.data.current_episode.current_time);
                        }

                        this.jwPlayer.on('time', () => {
                            if (jwplayer().getPosition().toFixed() == this.timeRequest) {
                                this.timeRequest = this.timeRequest + 150;
                                axios.post('/api/v1/create/watch/series/recently', {
                                    current_time: jwplayer().getPosition().toFixed(),
                                    duration_time: jwplayer().getDuration().toFixed(),
                                    episode_id: this.data.current_episode.id,
                                    series_id: this.data.current_episode.series_id
                                });
                            }
                        });

                    }

                    // OnSeek
                    this.jwPlayer.on('seek', () => {
                        this.timeRequest = parseInt(jwplayer().getPosition().toFixed()) + 150;
                    });

                }, 500);

            },

            show_report(value) {
                if(value) {
                    jwplayer().setMute(true);
                }else {
                    jwplayer().setMute(false);
                }
            }

        },

        methods: {

            CHANGE_SERIES(episode_id) {
                if (this.$auth.isAuthenticated()) {
                    this.$store.dispatch("LOAD_SERIES_PLAYER", {
                        episode_id: episode_id,
                    });
                }
            },

            SEND_REPORT() {
                this.$validator.validateAll().then(result => {
                    if (result) {
                        this.report_button = true;
                        axios
                            .post("/api/v1/create/report/series", {
                                type: this.report_problem_type,
                                details: this.report_details,
                                episode_id: this.data.current_episode.id,
                                series_id: this.data.current_episode.series_id
                            })
                            .then(
                                res => {
                                    if (res.data.status === "success") {
                                        this.report_button = false;
                                        this.$store.commit("CLOSE_REPORT");
                                        jwplayer().setMute(true);
                                        alertify.logPosition("top right");
                                        alertify.success("Successful Send, our team will check it soon");
                                    }
                                },
                                error => {
                                    //
                                }
                            );
                    }
                });
            },

            // When Colse video re-play video
            CLOSE_REPORT() {
                jwplayer().setMute(false);
                this.$store.commit('CLOSE_REPORT')
            },

            CHANGE_EPISODE(episode_id, series_id) {
                this.episode_changed = true;
                this.loadPlayerChange = true;
                if (this.$auth.isAuthenticated()) {
                    this.$store.dispatch("LOAD_SERIES_PLAYER", {
                        episode_id: episode_id,
                        series_id: series_id
                    });
                }

            },

            FOMRAT_DATE(date) {
                var date = new Date(date.replace(/-/g, '/'));
                var monthNames = [
                    "January", "February", "March",
                    "April", "May", "June", "July",
                    "August", "September", "October",
                    "November", "December"
                ];

                var day = date.getDate();
                var monthIndex = date.getMonth();
                var year = date.getFullYear();

                return day + ' ' + monthNames[monthIndex] + ' ' + year;
            },

            GET_SEASON(season_number) {
                axios.post('/api/v1/get/series/season', {
                    series_id: this.data.current_episode.series_id,
                    season_number: season_number
                })
                    .then(response => {
                            if (response.status == 200) {
                                this.data.current_season = response.data.data;
                            }
                        },
                        error => {
                            console.log('error in request')
                        })
            }
        }
    };
</script>
