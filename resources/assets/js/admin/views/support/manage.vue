<template>
  <div>
        <div class="spinner-load" v-if="spinner_loading">
            <Loader></Loader>
       </div>

    <!-- END spinner load -->

    <div class="k1_manage_table" v-if="!spinner_loading">

      <h5 class="title p-2">Support</h5>
      <div class="col-12 row">
        <div class="col-6">
          <div class="group-btn">
            <button class="btn btn-sm btn-warning" @click="ALL">All</button>
            <button class="btn btn-sm btn-success" @click="OPEN">Open</button>
            <button class="btn btn-sm btn-danger" @click="CLOSE">Close</button>
          </div>
        </div>
        <div class="col-6 pull-right">
          <div class="pull-right" id="search">
            <input class="form-control mr-sm-2" type="text" v-model="query" placeholder="Search">
          </div>
        </div>
      </div>

      <!-- END Panel -->

      <div v-if="data.data !== null && search_data === null">

          <div class="table-responsive mt-2">
            <div class="table table-hover">
              <thead>
                <th>Request ID</th>
                <th>Subject</th>
                <th>From</th>
                <th>Status</th>
                <th>Created</th>
                <th>Control</th>
              </thead>
              <tbody>
                <tr v-for="(item, index) in data.data" :key="index">
                  <td>
                    <span class="badge badge-warning new-message" v-if="item.readit === 1 && item.last_reply === 'client'">New</span>
                    {{item.request_id}}
                  </td>
                  <td>{{item.subject}}</td>
                  <td>{{item.from}}</td>
                  <td>
                    <span class="badge badge-success" v-if="item.status === 1">Open</span>
                    <span class="badge badge-danger" v-else>Close</span>

                  </td>
                  <td>{{item.created_at}}</td>
                  <td>
                    <div class="btn-group" role="group">
                      <router-link role="button" class="btn btn-sm btn-warning btn-sm mr-2" :to="{name: 'support-request',params:{id:item.id}}">Show
                      </router-link>
                      <button class="btn btn-sm btn-danger btn-sm mr-2" @click="DELETE(item.id,index)">Delete
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </div>
          </div>



          <nav aria-label="pagination" class="m-4 p-1">
            <ul class="pagination ">
              <li class="page-item" id="end">
                <a class="page-link" @click="PAGINATION('/api/admin/get/support/request')">Begin</a>
              </li>
              <li class="page-item" id="prev" :class="{disabled: data.prev_page_url === null}">
                <a class="page-link" @click="PAGINATION(data.prev_page_url)">Previous</a>
              </li>
              <li class="page-item">
                <a class="page-link">{{data.current_page}}/{{data.last_page}}</a>
              </li>
              <li class="page-item" id="next" :class="{disabled: data.next_page_url === null}">
                <a class="page-link" @click="PAGINATION(data.next_page_url)">Next</a>
              </li>
              <li class="page-item" id="end">
                <a class="page-link" @click="PAGINATION('/api/admin/get/support/request?page='+data.last_page)">End</a>
              </li>
            </ul>
          </nav>
          <!-- END PAGINATE -->

      </div>

      <div v-if="data.data !== null && search_data !== null">



          <div class="table-responsive mt-2">
            <div class="table table-hover">
              <thead>
                <th>Request ID</th>
                <th>Subject</th>
                <th>From</th>
                <th>Status</th>
                <th>Created</th>
                <th>Control</th>
              </thead>
              <tbody>
                <tr v-for="(item, index) in search_data.data" :key="index">
                  <td>
                    <span class="badge badge-warning new-message" v-if="item.readit === 1 && item.last_reply === 'client'">New</span>
                    {{item.request_id}}
                  </td>
                  <td>{{item.subject}}</td>
                  <td>{{item.from}}</td>
                  <td>
                    <span class="badge badge-success" v-if="item.status === 1">Open</span>
                    <span class="badge badge-danger" v-else>Close</span>
                  </td>
                  <td>{{item.created_at}}</td>
                  <td>
                    <div class="btn-group" role="group">
                      <router-link role="button" class="btn btn-sm btn-warning btn-sm mr-2" :to="{name: 'support-request',params:{id:item.id}}">Show
                      </router-link>
                      <button class="btn btn-sm btn-danger btn-sm mr-2" @click="DELETE(item.id,index)">Delete
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </div>
          </div>



          <nav aria-label="pagination" class="m-4 p-1">
            <ul class="pagination ">
              <li class="page-item" id="end">
                <a class="page-link" @click="PAGINATION('/api/admin/get/support/request')">Begin</a>
              </li>
              <li class="page-item" id="prev" :class="{disabled: search_data.prev_page_url === null}">
                <a class="page-link" @click="PAGINATION(search_data.prev_page_url)">Previous</a>
              </li>
              <li class="page-item">
                <a class="page-link">{{search_data.current_page}}/{{search_data.last_page}}</a>
              </li>
              <li class="page-item" id="next" :class="{disabled: search_data.next_page_url === null}">
                <a class="page-link" @click="PAGINATION(search_data.next_page_url)">Next</a>
              </li>
              <li class="page-item" id="end">
                <a class="page-link" @click="PAGINATION('/api/admin/get/support/request?page=' + search_data.last_page)">End</a>
              </li>
            </ul>
          </nav>
          <!-- END PAGINATE -->


      </div>
     
      <!-- END TABLE -->

      <div class="text-center" v-if="data.data === null">
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
  name: "support-manage",
  data() {
    return {
      query: null
    };
  },

  components: {
    Loader
  },

  computed: mapState({
    data: state => state.support.data,
    search_data: state => state.support.search_data,
    button_loading: state => state.support.button_loading,
    spinner_loading: state => state.support.spinner_loading
  }),

  watch: {
    query(v) {
      if (v.length === 0) {
        this.$store.commit("CLEAR_SUPPORT_SEARCH_DATA");
      } else {
        this.$store.dispatch("GET_SUPPORT_REQUEST_SEARCH", this.query);
      }
    }
  },

  created() {
    this.$store.dispatch("GET_ALL_SUPPORT_REQUEST");
  },

  methods: {
    PAGINATION(path_url) {
      this.$store.dispatch("GET_ALL_SUPPORT_REQUEST_BY_PAGINATION", path_url);
    },

    DELETE(id, key) {
      swal({
        title: "Are you sure to delete ?",
        icon: "warning",
        buttons: true,
        dangerMode: true
      }).then(willDelete => {
        if (willDelete) {
          this.$store.dispatch("DELETE_SPPORT_REQUEST", {
            id,
            key
          });
        }
      });
    },

    ALL() {
      this.$store.dispatch("GET_ALL_SUPPORT_REQUEST");
    },

    OPEN() {
      this.$store.dispatch("GET_OPEN_SUPPORT_REQUEST");
    },

    CLOSE() {
      this.$store.dispatch("GET_CLOSED_SUPPORT_REQUEST");
    }
  }
};
</script>