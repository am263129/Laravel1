<template>
    <div>
       <div class="spinner-load" v-if="spinner_loading">
            <Loader></Loader>
       </div>

        <!-- END spinner load -->
        <div class="k1_manage_table" v-if="!spinner_loading">
            <h5 class="title p-2">Live Tv</h5>

            <div class="m-2 p-2" id="manage-panel">
                <ul class="nav nav-tabs">
                 <li class="nav-item">
                        <router-link class="btn btn-md btn-warning" role="button" :to="{name: 'tv-manage'}">Manage</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link class="btn btn-md btn-warning" role="button" :to="{name: 'tv-create'}">Create</router-link>
                    </li>
                </ul>
            </div>


            <div class="table-responsive mt-2" v-if="data.channels !== null">
                <div class="table table-hover">
                    <thead>
                        <th>Cover</th>
                        <th>Name</th>
                        <th>Streaming Status</th>
                        <th>Created date</th>
                        <th>Updated date</th>
                        <th>Control</th>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in data.channels" :key="index">
                            <td>
                                <img :src="'/storage/posters/' + item.image" :alt="item.name" width="40">
                            </td>
                            <td>{{item.name}}</td>
                            <td v-if="! item.streaming_status"> off </td>
                            <td v-else> ON </td>
                            <td>{{item.created_at}}</td>
                            <td>{{item.updated_at}}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <router-link class="btn btn-sm btn-warning btn-sm mr-2" role="buttton" :to="{ name:'tv-edit', params: {id: item.id}}">
                                        Edit
                                    </router-link>
                                    <button class="btn btn-sm btn-danger btn-sm mr-2" @click="DELETE(item.id,index)" :id="item.id">
                                        Delete
                                    </button>

                                   <button class="btn btn-sm btn-success btn-sm mr-2" @click="STREAMING_STATUS(item.id, index)" v-if="! item.streaming_status" :id="item.id">
                                        Start Streaming
                                    </button>

                                    <button class="btn btn-sm btn-danger btn-sm mr-2" @click="STREAMING_STATUS(item.id, index)" v-else :id="item.id">
                                        Stop Streaming
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </div>
            </div>
            <div v-else class="text-center">
                <h4>No result were found</h4>
            </div>

        </div>
    </div>
</template>

<script>
const alertify = require("alertify.js");
import { mapState } from "vuex";
import Loader from "../components/loader";

export default {
  data() {
    return {};
  },
  components: {
    Loader
  },
  computed: mapState({
    data: state => state.channels.data,
    button_loading: state => state.channels.button_loading,
    spinner_loading: state => state.channels.spinner_loading
  }),
  created() {
    this.$store.dispatch("GET_ALL_CHANNELS");
  },
  methods: {
    DELETE(id, key) {
      swal({
        title: "Are you sure to delete ?",
        icon: "warning",
        text: "All videos and subtitles will removed!",
        buttons: true,
        dangerMode: true
      }).then(willDelete => {
        if (willDelete) {
          this.$store.dispatch("DELETE_CHANNEL", {
            id,
            key
          });
        }
      });
    },

    STREAMING_STATUS(id, key) {
      this.$store.dispatch("STREAMING_STATUS", {
        id,
        key
      });
    }
  }
};
</script>