<template>
<div  v-if="$auth.isAuthenticated() === 'active' ">
  <div class="col-10 col-md-4 support ads-noticaiton" @click="GO_AND_HIDE(item.id,index)" v-for="(item, index) in data.support" :key="index" v-if="data.support !== null">
    <div class="row">
        <div class="col-3 image hidden-sm-down text-center">
          <img src="/themes/default/images/support.png" alt="support" width="100%">
        </div>

        <div class="col-10 col-md-9">
          <strong>{{item.subject}}</strong>
          <p>{{item.details}}</p>
        </div>
      </div>
  </div>
  </div>
</template>

<script>
  export default {
    name: 'ads-notifaction',

    data() {
      return {
        data: []
      }
    },

    mounted() {
      if(this.$auth.isAuthenticated()) {
        axios.get("/api/v1/get/notifcation").then(response => {
          if (response.status === 200) {
            this.data = response.data.data;
          }
        });
      }
    },

    methods: {
      GO_AND_HIDE(id,index) {
        this.$router.push({name: 'support-request', params: {id: id} });
        this.data.support.splice(index, 1);
      }
    }
  }
</script>


<style scoped>
  .ads-noticaiton {
    position: fixed;
    right: 10px;
    bottom: 40px;
    z-index: 1000000;
    background-color: #313131;
    padding: 10px;
    cursor: pointer;
  }

  .ads-noticaiton p {
    word-break: break-all;
  }
</style>