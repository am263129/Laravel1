<template>
  <div class="wolf-player">
    <div class="loader" v-if="spinner_load">
      <loader></loader>
    </div>

    <div class="col-12 col-md-8 offset-md-2 mt-5" v-if="!spinner_load">
      <div class="jw-player">
        <div
          class="jwplayer-loader"
          style="text-align: center;height: 70vh; background: #1d1d1d; position:relative;"
          v-if="loadPlayer"
        >
          <loader
            style="
                        position: absolute;
                        float: right;
                        margin: 20;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%);
                    "
          ></loader>
        </div>
        <div id="livetv-player"></div>
        <adsView v-bind:id="$route.params.id" type="tv" />
      </div>

      <div class="episode-details d-inline mt-3" v-if="! spinner_load">
        <div class="episode-name mt-1">
          <p>{{data.current_channel.name}}</p>
        </div>
      </div>

      <div class="episode-list mb-5">
        <carousel
          class="list-carousel"
          navigationPrevLabel="<svg 
          xmlns="http://www.w3.org/2000/svg" 
          xmlns:xlink="http://www.w3.org/1999/xlink" 
          version="1.1" 
          viewBox="0 0 129 129" 
          enable-background="new 0 0 129 129" 
          width="100%" 
          ><g>
              <path d="m88.6,121.3c0.8,0.8 1.8,1.2 2.9,1.2s2.1-0.4 2.9-1.2c1.6-1.6 1.6-4.2 0-5.8l-51-51 51-51c1.6-1.6 1.6-4.2 0-5.8s-4.2-1.6-5.8,0l-54,53.9c-1.6,1.6-1.6,4.2 0,5.8l54,53.9z" data-original="#000000" class="active-path" data-old_color="#ffffff" fill="#ffffff"/>
                                                              </g></svg>"
          navigationNextLabel="<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" 
          viewBox="0 0 129 129" enable-background="new 0 0 129 129" width="100%" class="arrow-left-svg"><g transform="matrix(-1 1.22465e-16 -1.22465e-16 -1 129 129)"><g>
                                                                <path d="m88.6,121.3c0.8,0.8 1.8,1.2 2.9,1.2s2.1-0.4 2.9-1.2c1.6-1.6 1.6-4.2 0-5.8l-51-51 51-51c1.6-1.6 1.6-4.2 0-5.8s-4.2-1.6-5.8,0l-54,53.9c-1.6,1.6-1.6,4.2 0,5.8l54,53.9z" data-original="#000000" class="active-path" data-old_color="#ffffff" fill="#ffffff"/>
                                                              </g></g></svg>"
          :navigationEnabled="true"
          :paginationEnabled="false"
          :autoplay="true"
          :autoplayTimeout="5000"
          easing="ease-in-out"
          :perPageCustom="[[220,2], [420,3],[768, 4], [1024, 5]]"
        >
          <slide
            v-for="(item,index) in data.suggestions"
            :key="index"
            class="col-6 col-md-4 col-lg-3 col-xl-2 m-2 animation"
          >
            <div class="episode" @click="CHANGE_CHANNEL(item)" style="cursor: pointer;">
              <progressive-img
                :src="'/storage/posters/' + item.image"
                :alt="item.name"
                width="100%"
                :blur="1"
              />

              <div class="title mt-1">
                <small class="text-muted">{{item.name}}</small>
              </div>
            </div>
          </slide>
        </carousel>
      </div>
    </div>
  </div>
</template>

<script>
const alertify = require("alertify.js");
require("social-share-button");
import { mapState } from "vuex";
import { Carousel, Slide } from "vue-carousel";
import adsView from "./components/ads_view";
import loader from "../utils/loader";

export default {
  name: "live-tv-player",
  components: {
    Carousel,
    Slide,
    loader,
    adsView
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
      loadPlayer: true,
      itemId: this.$route.params.id
    };
  },

  computed: mapState({
    data: state => state.player.tv_data,
    show_report: state => state.player.show_report,
    spinner_load: state => state.player.player_spinner,
    season_playlist_active: state => state.player.season_playlist_active
  }),

  mounted() {
    this.$store.dispatch("LOAD_LIVE_TV", this.$route.params.id);
  },

  watch: {
    data() {
      this.loadPlayer = true;
      setTimeout(() => {
        
        const player = jwplayer("livetv-player").setup({
          file: this.data.current_channel.streaming_url,
		  type: 'hls',
		  preload:'metadata',
          autostart: true,
          cast: {}
        });
        this.loadPlayer = false;

        // Load custom video file on error
        player.on("error", (data1) => {
			//if(data1.sourceError.response.code == 403){
				
			//	player.load({
			//		file: this.data.current_channel.streaming_url					
			//	});
			//	player.play();
			//}		    
          player.load({
            file: "//content.jwplatform.com/videos/7RtXk3vl-52qL9xLP.mp4",
            image: "//content.jwplatform.com/thumbs/7RtXk3vl-480.jpg"
          });
          player.play();
        });
      }, 500);
    }
  },
  created() {
    console.warn(this.$route.query);
    if (this.$route.query.referral) {
      this.$store.dispatch("REFER", {
        id: this.$route.params.id,
        referral: this.$route.query.referral,
        type: "tv"
      });
    }
  },

  methods: {
    SEND_REPORT() {
      this.$validator.validateAll().then(result => {
        if (result) {
          this.report_button = true;
          axios
            .post("/api/v1/create/report/series", {
              type: this.report_problem_type,
              details: this.report_details,
              episode_id: this.data.id,
              series_id: this.data.series_id
            })
            .then(
              res => {
                if (res.data.status === "success") {
                  this.report_button = false;
                  this.$store.commit("CLOSE_REPORT");
                  alertify.logPosition("top right");
                  alertify.success(
                    "Successful Send, our team will check it soon"
                  );
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
      this.$store.commit("CLOSE_REPORT");
    },

    CHANGE_CHANNEL(item) {
      this.data.current_channel = item;
      const player = jwplayer("livetv-player").setup({
        file: this.data.current_channel.streaming_url,
        autostart: true,
        cast: {}
      });

      // Load custom video file on error
      player.on("error", () => {
        player.load({
          file: "//content.jwplatform.com/videos/7RtXk3vl-52qL9xLP.mp4",
          image: "//content.jwplatform.com/thumbs/7RtXk3vl-480.jpg"
        });
        player.play();
      });
    },

    FOMRAT_DATE(date) {
      date = new Date(date);
      var monthNames = [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December"
      ];

      var day = date.getDate();
      var monthIndex = date.getMonth();
      var year = date.getFullYear();

      return day + " " + monthNames[monthIndex] + " " + year;
    }
  }
};
</script>

