<template>
<div class="transcoding">
    <div class="spinner-load" v-if="spinner_loading">
        <Loader></Loader>
    </div>

    <div class="k1_manage_table" v-if="!spinner_loading">

        <div class="col-12 my-3 p-2">
            <h5 class="title p-2">HLS Segment & Watermark </h5>

            <hr>
            <div class="col-12 col-md-8">

                <div class="form-group">
                    <div class="col-12 col-sm-4">
                        <label>Watermark</label>
                    </div>
                    <div class="col-12 col-sm-12">
                        <input type="file" id="watermark" name="watermark" v-validate="'image'"
                                   @change="readImage('watermark','watermarkFileImage')"
                                   class="inputfile">
                        <label id="watermarkLabel" for="watermark">Choose a backdrop
                                <br>
                            </label>
                        <img :src="'/storage/watermark/' + image" id="watermarkFileImage" width="200" v-if="image">
                        <img src="" id="watermarkFileImage" width="200" v-if="!image" style="display: none;">

                        <span v-show="errors.has('backdrop')"
                                  class=" is-danger">{{ errors.first('backdrop')}}</span>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-12 col-sm-4">
                        <label>Watermark position</label>
                    </div>

                    <div class="col-12 col-sm-12">
                        <select class="form-control" v-model="presets">
                                <option value="TopLeft">TopLeft</option>
                                <option value="TopRight">TopRight</option>
                                <option value="BottomLeft">BottomLeft</option>
                                <option value="BottomRight">BottomRight</option>
                            </select>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-12 col-sm-12">

                        <label for="segment_duration">Segment Duration</label>
                        <input type="text" id="segment_duration" class="form-control" placeholder="Segment Duration"
                               v-model="segment_duration">
                    </div>
                    </div>

                    <div class="form-group">
                        <div class="col-12 col-sm-12">
                            <button v-if="!button_loading" @click="UPDATE" class="btn btn-md btn-warning">Update
                            </button>
                            <button v-if="button_loading" class="btn btn-md btn-warning" disabled>
                                <i id="btn-progress"></i> Loading
                            </button>
                            <button @click="REMOVE" class="btn btn-md btn-danger">Remove watermark</button>

                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>
</template>

<script>
const alertify = require("alertify.js");
import Loader from "../../components/loader";

export default {
    name: "transcoder_video",
    data() {
        return {
            presets: "",
            segment_duration: "",
            image: false,
            button_loading: false,
            delete_loading: false,
            spinner_loading: true
        };
    },
    components: {
        Loader
    },
    created() {
        axios.get("/api/admin/get/settings/transcoder").then(resposne => {
            if (resposne.status === 200) {
                this.presets = resposne.data.data.watermark_position;
                this.segment_duration = resposne.data.data.segment_duration;
                if (resposne.data.data.watermark_url !== null) {
                    this.image = resposne.data.data.watermark_url;
                }
                this.spinner_loading = false;
            }
        });
    },
    methods: {
        UPDATE() {
            this.button_loading = true;

            const formData = new FormData();
            const watermark = document.querySelector("#watermark");
            formData.append("watermark", watermark.files[0]);
            formData.append("watermark_position", this.presets);
            formData.append("segment_duration", this.segment_duration);

            axios
                .post("/api/admin/update/settings/transcoder/watermark", formData)
                .then(
                    response => {
                        if (response.status === 200) {
                            alertify.logPosition("top right");
                            alertify.success(response.data.message);
                            this.button_loading = false;
                        }
                    },
                    error => {
                        alertify.logPosition("top right");
                        alertify.error(error.response.data.message);
                        this.button_loading = false;
                    }
                );
        },
        REMOVE() {
            axios.get("/api/admin/remove/settings/transcoder/watermark").then(
                response => {
                    if (response.status === 200) {
                        alertify.logPosition("top right");
                        alertify.success(response.data.message);
                    }
                },
                error => {
                    alertify.logPosition("top right");
                    alertify.error(error.response.data.message);
                }
            );
        },
        readImage(id, outImage) {
            var img = document.getElementById(id);
            var tgt = img.target || window.event.srcElement,
                files = tgt.files;

            // FileReader support
            if (FileReader && files && files.length) {
                var fr = new FileReader();
                fr.onload = function () {
                    var srcImage = document.getElementById(outImage);
                    srcImage.style.display = "block";
                    srcImage.src = fr.result;
                };
                fr.readAsDataURL(files[0]);
            } else {
                // Not supported
                // fallback -- perhaps submit the input to an iframe and temporarily store
                // them on the server until the user's session ends.
            }
        },
    }
};
</script>
