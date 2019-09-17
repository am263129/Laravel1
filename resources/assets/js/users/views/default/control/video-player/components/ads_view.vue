<template v-if="adElement.active">
  <div v-if="adVisible" style="
    z-index: 9999;
    width: 100%">
    <button style="float: right" class="fa fa-close" @click="hideSelf()" />

    <InArticleAdsense
      :root-class="adElement.root_class"
      :ins-class="adElement.ins_class"
      :data-ad-layout-key="adElement.data_ad_layout_key"
      :data-ad-test="adElement.data_ad_test"
      :data-ad-format="adElement.data_ad_format"
      :data-full-width-responsive="adElement.data_ad_full_width_responsive === 1"
      :is-non-personalized-ads="adElement.is_non_personalized_ad === 1"
      :data-ad-client="adElement.data_ad_client"
      :data-ad-slot="adElement.data_ad_slot"
    ></InArticleAdsense>
  </div>
</template>
<script>
const alertify = require("alertify.js");
import swal from "sweetalert";
import Ads from "vue-google-adsense";

Vue.use(require("vue-script2"));

Vue.use(Ads.Adsense);
Vue.use(Ads.InArticleAdsense);
Vue.use(Ads.InFeedAdsense);

import Vue from "vue";
import { mapState } from "vuex";

export default {
  name: "adsView",
  props: {
    id: {
      required: true
    },
    type: {
      required: true
    }
  },
  data: function() {
    return {
      itemId: this.id,
      url:
        window.location +
        "?referral=" +
        Vue.auth.getUserInfo("user_id") +
        "&type=" +
        this.type,
      urlVisible: false,
      adVisible: false,
      adElement: {}
    };
  },
  created() {
    axios.get("/api/v1/get/ae/" + this.type).then(res => {
      this.adElement = res.data;
      this.adVisible = true;
    });
  },
  methods: {
    hideSelf() {
      this.$data.adVisible = false;
    },

    copyTextToClipboard(text) {
      var textArea = document.createElement("textarea");
      textArea.value = this.url;
      document.body.appendChild(textArea);
      textArea.focus();
      textArea.select();
      swal("Copied to clipboard");

      try {
        var successful = document.execCommand("copy");
        var msg = successful ? "successful" : "unsuccessful";
        console.log("Fallback: Copying text command was " + msg);
      } catch (err) {
        console.error("Fallback: Oops, unable to copy", err);
      }

      document.body.removeChild(textArea);
    }
  }
};
</script>
