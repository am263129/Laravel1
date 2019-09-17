<template>
    <div>
    
        <div class="spinner-load" v-if="spinner_loading">
            <Loader></Loader>
       </div>

     <div class="k1_manage_table" v-if="!spinner_loading">
      <h5 class="title p-2">Top</h5>

        <div class="table-responsive mt-2" v-if="data.top !== null" >
            <div class="table table-hover">
                <thead>
                <th>Cover</th>
                <th>Name</th>
                <th>Created date</th>
                <th>Control</th>
                </thead>
                <tbody>
                <tr v-for="(item, index) in data.top" :key="index">
                    <td v-if="item.cloud == 'local' ">
                        <img :src="'/storage/posters/300_' + item.poster" :alt="item.name" width="40">
                    </td>
                    <td v-if="item.cloud == 'aws' ">
                        <img :src="sm_poster + item.poster" :alt="item.name" width="40">
                    </td>
                    <td>{{item.name}}</td>
                    <td>{{item.created_at}}</td><td>
                        <div class="btn-group" role="group">
                            <button class="btn btn-sm btn-danger btn-sm mr-2"  @click="DELETE(item.id,index)" :id="item.id">
                                Delete
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
import Loader from "./components/loader";
export default {
  data() {
    return {};
  },

  computed: mapState({
    data: state => state.tops.data,
    button_loading: state => state.tops.button_loading,
    spinner_loading: state => state.tops.spinner_loading
  }),

  components: {
    Loader
  },

  created() {
    this.$store.dispatch("GET_TOPS");
  },

  methods: {
    DELETE(id, key) {
      swal({
        title: "Are you sure to delete ?",
        icon: "warning",
        buttons: true,
        dangerMode: true
      }).then(willDelete => {
        if (willDelete) {
          this.$store.dispatch("DELETE_TOP", { id, key });
        }
      });
    }
  }
};
</script>