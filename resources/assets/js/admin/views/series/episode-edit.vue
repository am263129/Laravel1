<template>
    <div class="episode-edit">

        <div class="spinner-load" v-if="spinner_loading">
            <Loader></Loader>
       </div>

        <div class="k1_manage_table" v-if="!spinner_loading">

            <h5 class="title p-2">{{data.name}}</h5>

            <div class="col-12">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-6">

                        <div class="col-12 col-sm-12 ">
                            <div class="form-group">
                                <label>Name</label>
                                <input v-validate="'required|max:30'" name="name" class="form-control" v-model="data.name" type="text" placeholder="Name"
                                />
                                <span v-show="errors.has('name')" class=" is-danger">{{ errors.first('name') }}</span>
                            </div>
                        </div>

                        <div class="col-12 col-sm-12 ">
                            <div class="form-group">
                                <label>Overview</label>
                                <textarea v-validate="'required|max:255'" name="overview" class="form-control" v-model="data.overview" type="text" placeholder="Overview"
                                />

                                <span v-show="errors.has('overview')" class=" is-danger">{{ errors.first('overview') }}</span>
                            </div>
                        </div>


                        <div class="col-12 col-sm-12 ">
                            <div class="form-group">
                                <label for="Session">Session</label>
                                <select class="form-control" v-model="data.season_number" id="Session">
                                    <option v-for="(list, index) in listSession" :key="index">{{list}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-sm-12">
                            <div class="form-group">
                                <label for="Episode">Episode</label>
                                <select class="form-control" v-model="data.episode_number" id="Episode">
                                    <option v-for="(list, index) in listEpiosde" :key="index">{{list}}</option>
                                </select>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-12 col-sm-4">
                                <label>Backdrop</label>
                            </div>
                            <div class="col-12 col-sm-12">
                                <input type="file" id="backdrop" name="backdrop" v-validate="'image'" @change="readImage('backdrop','backdropFileImage')"
                                    class="inputfile" multiple>
                                <label id="backdropLabel" for="backdrop">Choose a backdrop
                                    <br>
                                </label>
                                <img :src="'/storage/backdrops/300_' + data.backdrop" id="backdropFileImage" width="100%" v-if="data.cloud == 'local'">
                                <img :src="md_backdrop + data.backdrop" :alt="data.backdrop"  id="backdropFileImage" width="100%" v-if="data.cloud == 'aws' ">
                                <span v-show="errors.has('backdrop')"
                                      class="is-danger">{{ errors.first('backdrop')}}</span>
                                <span v-show="errors.has('backdrop')" class="is-danger">{{ errors.first('backdrop')}}</span>
                            </div>
                        </div>

                    </div>

                    <div class="col-12 col-md-6">

                        <h4>Videos</h4>
                        <div id="accordion resolution-videos">
                            <div class="card" v-for="(item, index) in data.videos" :key="index">
                                <div class="card-header" :id="'heading' + index">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link" data-toggle="collapse" :data-target="'#collapse' + index" aria-expanded="true" :aria-controls="'collapse' + index">
                                            Resolution
                                            <span v-if="item.resolution === '320'">320P</span>
                                            <span v-if="item.resolution === '480'">480P</span>
                                            <span v-if="item.resolution === '720'">720P</span>
                                            <span v-if="item.resolution === '1080'">1080P</span>
                                        </button>
                                    </h5>
                                </div>

                                <div :id="'collapse'+ index" class="collapse show" :aria-labelledby="'heading' + index" data-parent="#accordion">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <video width="100%" controls>
                                                <source :src="item.video_url" type="video/mp4"> Your browser does not support the video tag.
                                            </video>
                                        </div>
                                        <div class="form-group">
                                            <label for="link" class="m-1">Video Link</label>
                                            <input type="text" name="link" class="form-control" id="link" v-model="item.video_url">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>


                <div class="col-12 col-sm-6 mt-2">
                    <div class="form-group">
                        <button class="btn btn-md btn-warning" @click="MOVIEDB_API()">Update</button>
                    </div>
                </div>

            </div>
            <div class="upload-modal" v-if="showProgress">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-body p-4">

                            <div class="episodeApi" v-if="episodeApi">
                                <div class="progress mt-2">
                                    <div v-if="percentEpisodeApi !== 100" class="progress-bar" role="progressbar" :style="{width: percentEpisodeApi + '%', height:'6px' }"
                                        :aria-valuenow="percentEpisodeApi" aria-valuemin="0" aria-valuemax="100"></div>
                                    <div v-else>
                                        <i id="btn-progress"></i> Prepare
                                    </div>
                                </div>
                                <p class="is-danger">{{error_message_api}}</p>
                                <p class="is-success">{{success_message_api}}</p>
                                <hr>
                            </div>

                            <!-- END moviepai -->

                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</template>


<script>
import { mapState } from "vuex";
const alertify = require("alertify.js");
import Loader from "../components/loader";
export default {
  data() {
    return {
      id: "",
      video_link: [],
      showProgress: false,
      percentEpisodeApi: 0,
      name: "",
      overview: "",
      season: "",
      episode: "",
      listSession: [],
      listEpiosde: [],
      episodeApi: false,
      error_message_api: "",
      success_message_api: ""
    };
  },
  components: {
    Loader
  },
  computed: mapState({
    data: state => state.series.episode_data,
    button_loading: state => state.series.button_loading,
    spinner_loading: state => state.series.spinner_loading
  }),
  created() {
    this.$store.dispatch("GET_EPISODE_DETAILS", this.$route.params.id);

    for (var i = 1; i < 50; i++) {
      this.listSession.push(i);
    }
    for (var i = 1; i < 2000; i++) {
      this.listEpiosde.push(i);
    }
  },

  methods: {
    MOVIEDB_API() {
      const backdrop = document.querySelector("#backdrop");
      const formData = new FormData();
      formData.append("id", this.$route.params.id);
      formData.append("name", this.data.name);
      formData.append("overview", this.data.overview);
      formData.append("season_number", this.data.season_number);
      formData.append("episode_number", this.data.episode_number);
      formData.append("video_url", this.data.video_url);
      formData.append("videos", JSON.stringify(this.data.videos));
      formData.append("backdrop", backdrop.files[0]);

      const progress = {
        headers: {
          "content-type": "multipart/form-data"
        },
        onUploadProgress: progressEvent => {
          this.percentEpisodeApi = Math.round(
            (progressEvent.loaded * 100.0) / progressEvent.total
          );
        }
      };
      // Store data
      this.$validator.validateAll().then(result => {
        if (result) {
          this.showProgress = true;
          this.episodeApi = true;
          axios
            .post("/api/admin/update/series/episode", formData, progress)
            .then(
              response => {
                if (response.status === 200) {
                  this.success_message_api = response.data.message;
                  this.showProgress = false;
                  alertify.logPosition("top right");
                  alertify.success("Successful upload");
                  this.$router.go(-1);
                }
              },
              error => {
                this.error_message_api = error.response.data.message;
                this.showProgress = false;
              }
            );
        }
      });
    },

    readImage(id, outImage) {
      var img = document.getElementById(id);
      var tgt = img.target || window.event.srcElement,
        files = tgt.files;

      // FileReader support
      if (FileReader && files && files.length) {
        var fr = new FileReader();
        fr.onload = function() {
          var srcImage = document.getElementById(outImage);
          srcImage.src = fr.result;
        };
        fr.readAsDataURL(files[0]);
      } else {
        // Not supported
        // fallback -- perhaps submit the input to an iframe and temporarily store
        // them on the server until the user's session ends.
      }
    }
  }
};
</script>
