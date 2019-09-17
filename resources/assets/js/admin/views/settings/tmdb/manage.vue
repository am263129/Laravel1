<template>
<div class="tmdb">
    <div class="spinner-load" v-if="spinner_loading">
        <Loader></Loader>
    </div>
    <div class="k1_manage_table" v-if="!spinner_loading">
        <div class="col-12 my-3 p-2">
            <h5 class="title p-2">TMDB Manage</h5>
            <hr>
            <div class="col-12 col-md-8">

                <div class="form-group">
                    <label>API</label>
                    <input type="text" name="api" class="form-control" placeholder="API" v-model="api"
                               v-validate="'required|max:32'">
                    <span v-show="errors.has('api')" class="is-danger">{{ errors.first('api')}}</span>

                </div>

                <div class="form-group">
                    <label>Language</label>
                    <select class="form-control" name="language" v-model="language" v-validate="'required|max:8'">
                            <option v-for="(item, index) in language_list" :key="index" :value="item">{{item}}</option>
                        </select>
                    <span v-show="errors.has('language')" class="is-danger">{{ errors.first('language')}}</span>

                </div>

                <div class="form-group">
                    <button v-if="!button_loading" @click="UPDATE" class="btn btn-md btn-warning">Update</button>
                    <button v-if="button_loading" class="btn btn-md btn-warning" disabled><i id="btn-progress"></i>
                            Loading
                        </button>
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
    name: "tmdb",
    data() {
        return {
            api: "",
            language: "",
            button_loading: false,
            delete_loading: false,
            spinner_loading: true,
            language_list: [
                "ar-AE",
                "ar-SA",
                "bg-BG",
                "bn-BD",
                "ca-ES",
                "ch-GU",
                "cs-CZ",
                "da-DK",
                "de-DE",
                "el-GR",
                "en-US",
                "eo-EO",
                "es-ES",
                "es-MX",
                "eu-ES",
                "fa-IR",
                "fi-FI",
                "fr-CA",
                "fr-FR",
                "he-IL",
                "hi-IN",
                "hu-HU",
                "id-ID",
                "it-IT",
                "ja-JP",
                "ka-GE",
                "kn-IN",
                "ko-KR",
                "lt-LT",
                "ml-IN",
                "nb-NO",
                "nl-NL",
                "no-NO",
                "pl-PL",
                "pt-BR",
                "pt-PT",
                "ro-RO",
                "ru-RU",
                "sk-SK",
                "sl-SI",
                "sr-RS",
                "sv-SE",
                "ta-IN",
                "te-IN",
                "th-TH",
                "tr-TR",
                "uk-UA",
                "vi-VN",
                "zh-CN",
                "zh-TW"
            ]
        };
    },
    components: {
        Loader
    },
    mounted() {
        axios.get("/api/admin/get/settings/tmdb").then(resposne => {
            if (resposne.data.data !== null) {
                this.api = resposne.data.data.api;
                this.language = resposne.data.data.language;
                this.spinner_loading = false;
            }
        });
    },
    methods: {
        UPDATE() {
            this.$validator.validateAll().then(result => {
                if (result) {
                    this.button_loading = true;
                    axios
                        .post("/api/admin/update/settings/tmdb", {
                            api: this.api,
                            language: this.language,
                        })
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
                }
            });
        }
    }
};
</script>
