<template>
  <div>
    <div class="k1_manage_table">
      <h5 class="title p-2">User Withdrawals</h5>
      <div class="col-12 row">
        <div class="col-8"></div>
        <div class="col-4 pull-right">
          <div class="pull-right" id="search">
            <input
              class="form-control mr-sm-2"
              type="text"
              v-model="search_query"
              placeholder="Search"
              @keyup="SEARCH"
            />
          </div>
        </div>
      </div>

      <!-- END Panel -->

     

        <div>
          <div class="table-responsive mt-2" id="withdrawals-manage">
            <div class="table table-hover">
              <thead>
                <th>#ID</th>
                <th>User Name</th>
                <th>Email</th>                
                <th>Created At</th>
                <th>Amount</th>
                <th>Points to Spend</th>
                <th>Status</th>
                <th>Payment Method</th>
                <th>Control</th>
              </thead>
              <tbody>
                <tr v-for="(item, index) in withdrawals" :key="index">
                  <td>{{item.id}}</td>
                  <td>{{item.user.name}}</td>
                  <td>{{item.user.email}}</td>
                  <td>{{item.created_at}}</td>
                  <td>{{item.amount}}</td>
                  <td>{{item.points_spent}}</td>
                  <td>
                    <span class="badge badge-warning" v-if="item.confirmed === 0">Unconfirmed mail</span>
                    <span v-if="item.status === 'pending'">
                      <span class="badge badge-warning">Pending </span>
                    </span>
                    <span v-else>
                      <span
                        class="badge badge-success"
                        v-if="item.status === 'accepted'"
                      >Accepted</span>
                      <span
                        class="badge badge-danger"
                        v-if="item.status === 'cancelled'"
                      >Cancelled</span>
                    </span>
                  </td>

                  <td>{{item.payment_method.number}} -  {{item.payment_method.type}}</td>

                  <td>
                    <div class="btn-group" role="group">
                     
                      <button
                        class="btn btn-sm btn-danger btn-sm mr-2"
                        @click="MARK_STATUS(item,index, 'accepted')"
                        :id="item.id"
                      >Mark Paid</button>
                      <button
                        class="btn btn-sm btn-danger mr-2"
                        @click="MARK_STATUS(item, index,'cancelled')"
                      >Reject</button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </div>
          </div>

          <nav aria-label="pagination" class="m-4 p-1">
            <ul class="pagination">
              <li class="page-item" id="end">
                <a class="page-link" @click="PAGINATION('/api/admin/get/withdrawals')">Begin</a>
              </li>
              <li
                class="page-item"
                id="prev"
                :class="{disabled: withdrawals.prev_page_url === null}"
              >
                <a class="page-link" @click="PAGINATION(withdrawals.prev_page_url)">Previous</a>
              </li>
              <li class="page-item">
                <a class="page-link">{{withdrawals.current_page}}/{{withdrawals.last_page}}</a>
              </li>
              <li
                class="page-item"
                id="next"
                :class="{disabled: withdrawals.next_page_url === null}"
              >
                <a class="page-link" @click="PAGINATION(withdrawals.next_page_url)">Next</a>
              </li>
              <li class="page-item" id="end">
                <a
                  class="page-link"
                  @click="PAGINATION('/api/admin/get/withdrawals?page='+withdrawals.last_page)"
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
  name: "withdrawals-manage",
  data() {
    return {
      date: new Date(),
      spinner_loading: false,
      search_query: null,
      withdrawals: []
    };
  },
  components: {
    Loader
  },
  mounted() {
    axios
      .get("/api/v1/get/withdrawal")
      .then(res => {        
        this.withdrawals = res.data.data;
      })
      .catch(err => {
        alert(err);
      });
  },
  created() {
   // this.$store.commit("CLEAN_SEARCH_withdrawals");
   // this.$store.dispatch("GET_ALL_withdrawals");
  },

  methods: {
    MARK_STATUS(item, index, status) {
      axios.put('/api/v1/update/withdrawal/'+item.id, {
        status: status
      }).then(res => {
        console.warn(res);  
        this.withdrawals[index] = res.data
        swal('Status changed successfully');
      })
    },
    PAGINATION(path_url) {
      this.$store.dispatch("GET_ALL_withdrawals_BY_PAGINATION", path_url);
    },

    DELETE(id, key) {
      swal({
        title: "Are you sure to delete ?",
        icon: "warning",
        buttons: true,
        dangerMode: true
      }).then(willDelete => {
        if (willDelete) {
          this.$store.dispatch("DELETE_USER", {
            id,
            key
          });
        }
      });
    },

    ALL() {
      this.$store.dispatch("GET_ALL_withdrawals");
    },

    INACTIVITY() {
      this.$store.dispatch("GET_INACTIVITY_withdrawals");
    },

    ACTIVITY() {
      this.$store.dispatch("GET_ACTIVITY_withdrawals");
    },

    SEARCH() {
      this.$store.dispatch("GET_withdrawals_SEARCH", this.search_query);
    },

    BILLING(id) {
      this.$store.dispatch("GET_withdrawals_BILLING", id);
    }
  }
};
</script>
