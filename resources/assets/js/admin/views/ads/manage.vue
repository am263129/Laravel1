<template>
  <div>
    <div class="k1_manage_table">
      <h5 class="title p-2">User adElements</h5>
      <div class="col-12 row">
        <div class="col-8"></div>
        <div class="col-4 pull-right">
          <div class="pull-right" id="search">
            <input
              class="form-control mr-sm-2"
              type="text"
              v-model="search_query"
              placeholder="Search"

            />
          </div>
        </div>
      </div>

      <!-- END Panel -->

     


          <div class="table-responsive mt-2" id="adElements-manage">
            <div v-if="place" class="form">
              <div class="row">
              <div class="form-group col-2">
                <input class="form-control"  placeholder="Place" type="text" v-model="place" />
              </div>
              <div class="form-group col-2">
                <input class="form-control" placeholder="Root Class" type="text" v-model="root_class" />
              </div>
              <div class="form-group col-2">
                <input class="form-control" placeholder="INS Class" type="text" v-model="ins_class" />
              </div>
              <div class="form-group col-2">
                <input class="form-control" placeholder="AD Client" type="text" v-model="data_ad_client" />
              </div>
              <div class="form-group col-2">
                <input class="form-control" placeholder="Ad Slot" type="text" v-model="data_ad_slot" />
              </div>
              <div class="form-group col-2">
                <input class="form-control" placeholder="Layout Key" type="text" v-model="data_ad_layout_key" />
              </div>
              </div>
              <div class="row">
              <div class="form-group col-2">
                <input class="form-control" placeholder="AD Test" type="text" v-model="data_ad_test" />
              </div>
              <div class="form-group col-2">
                <input class="form-control" placeholder="AD Format" type="text" v-model="data_ad_format" />
              </div>
              <div class="form-group col-2">
                <input class="form-control" placeholder="Active" type="text" v-model="active" />
              </div>
              <div class="form-group col-2">
                <div class="checkbox">
                <label><input type="checkbox" v-model="data_ad_full_width_responsive">Full width</label>
            </div>
              </div>
              <div class="form-group col-2">



                <label><input class="form-control" placeholder="Personalized Ad" type="checkbox" v-model="is_non_personalized_ad" />Non personalize</label>
              </div>
              <div class="form-group col-2">
                <button class="btn btn-warning" @click="UPDATE_AD" >Update</button>
              </div>
              </div>
            </div>
            <div class="table table-hover">
              <thead>
                <th>#ID</th>
                <th>Place</th>
                <th>Root Class</th>
                  <th>INS Class</th>
                  <th>AD Client ID</th>
                  <th>AD Slot</th>
                  <th>Layout KEY</th>
                  <th>AD Test</th>
                  <th>AD Format</th>
                  <th>Full Width Responsive</th>
                  <th>Non personilized ad</th>
                  <th>actions</th>
                </thead>
              <tbody>
                <tr v-for="(item, index) in adElements" :key="index">
                  <td>{{item.id}}</td>
                  <td>{{item.place}}</td>
                  <td>{{item.root_class || ''}}</td>
                  <td>{{item.ins_class || ''}}</td>
                  <td>{{item.data_ad_client}}</td>
                  <td>{{item.data_ad_slot}}</td>
                  <td>{{item.data_ad_layout_key}}</td>
                  <td>{{item.data_ad_test}}</td>
                  <td>{{item.data_ad_format}}</td>
                  <td>{{item.data_ad_full_width_responsive}}</td>
                  <td>{{item.is_non_personalized_ad}}</td>
                  <td>
                    <span class="badge badge-warning" v-if="item.active && item.active === 0">Inactive</span>
                    <span class="badge badge-success" v-if="item.active && item.active === 1">Active</span>
                  </td>


                  <td>
                    <div class="btn-group" role="group">
                     
                      <button
                        class="btn btn-sm btn-danger btn-sm mr-2"
                        @click="SET_CURRENT(item, index)"
                        :id="item.id"
                      >Update</button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </div>
          </div>

          <nav aria-label="pagination" class="m-4 p-1">
            <ul class="pagination">
              <li class="page-item" id="end">
                <a class="page-link" @click="PAGINATION('/api/admin/get/ae')">Begin</a>
              </li>
              <li
                class="page-item"
                id="prev"
                :class="{disabled: adElements.prev_page_url === null}"
              >
                <a class="page-link" @click="PAGINATION(adElements.prev_page_url)">Previous</a>
              </li>
              <li class="page-item">
                <a class="page-link">{{adElements.current_page}}/{{adElements.last_page}}</a>
              </li>
              <li
                class="page-item"
                id="next"
                :class="{disabled: adElements.next_page_url === null}"
              >
                <a class="page-link" @click="PAGINATION(adElements.next_page_url)">Next</a>
              </li>
              <li class="page-item" id="end">
                <a
                  class="page-link"
                  @click="PAGINATION('/api/admin/get/ae?page='+adElements.last_page)"
                >End</a>
              </li>
            </ul>
          </nav>

          <!-- END PAGINATE -->
        </div>
      </div>

      <!-- END TABLE -->
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";
import Loader from "../components/loader";
import swal from 'sweetalert';
const alertify = require("alertify.js");


export default {
  name: "adElements-manage",
  data() {
    return {
      date: new Date(),
      spinner_loading: false,
      search_query: null,
      adElements: [],
      current_id: 0,
      current_index: -1,
      place: '',
      root_class:'',
      ins_class: '',
      data_ad_client: '',
      data_ad_slot: '',
      data_ad_layout_key: '',
      data_ad_test:'',
      data_ad_format: '',
      active: 1,
      data_ad_full_width_responsive: 0,
      is_non_personalized_ad: 0
    };
  },
  components: {
    Loader
  },
  mounted() {
    axios
      .get("/api/v1/get/ae")
      .then(res => {
        console.warn(res);
        this.adElements = res.data;
      })
      .catch(err => {
        alert(err);
      });
  },
  created() {
    // this.$store.commit("CLEAN_SEARCH_adElements");
    // this.$store.dispatch("GET_ALL_adElements");
  },

  methods: {
    SET_CURRENT(item, index) {
      this.current_index= index,

      this.current_id= item.id,
      this.place= item.place,
      this.root_class=item.root_class,
      this.ins_class= item.ins_class,
      this.data_ad_client= item.data_ad_client,
      this.data_ad_slot= item.data_ad_slot,
      this.data_ad_layout_key= item.data_ad_layout_key,
      this.data_ad_test=item.data_ad_test,
      this.data_ad_format= item.data_ad_format,
      this.active= item.active,
      this.data_ad_full_width_responsive= item.data_ad_full_width_responsive,
      this.is_non_personalized_ad= item.is_non_personalized_ad

      this.current = item
    },
    UPDATE_AD() {
      const {
        current_index,
        place,
        root_class,
        ins_class,
        data_ad_client,
        data_ad_slot,
        data_ad_layout_key,
        data_ad_test,
        data_ad_format,
        active,
        data_ad_full_width_responsive,
        is_non_personalized_ad} = this;

      axios.put('/api/v1/update/ae/'+this.current_id, {
        place,
        root_class,
        ins_class,
        data_ad_client,
        data_ad_slot,
        data_ad_layout_key,
        data_ad_test,
        data_ad_format,
        active,
        data_ad_full_width_responsive,
        is_non_personalized_ad
        }).then(res => {
        console.warn(res);  
        this.adElements[current_index] = res.data
        swal('Status changed successfully');
      })
    },
    PAGINATION(path_url) {
      // this.$store.dispatch("GET_ALL_adElements_BY_PAGINATION", path_url);
    },

    DELETE(id, key) {
      swal({
        title: "Are you sure to delete ?",
        icon: "warning",
        buttons: true,
        dangerMode: true
      }).then(willDelete => {
        if (willDelete) {
          //
        }
      });
    },

  }
};
</script>
