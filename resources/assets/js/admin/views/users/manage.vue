<template>
  <div>
    <div class="k1_manage_table">
      <h5 class="title p-2">Users</h5>
      <div class="col-12 row">
        <div class="col-8">
          <div class="group-btn">
            <button class="btn btn-sm btn-warning" @click="ALL">All</button>
            <button class="btn btn-sm btn-success" @click="ACTIVITY">Activity</button>
            <button class="btn btn-sm btn-danger" @click="INACTIVITY">Inactivity</button>
            <router-link
              class="btn btn-sm btn-warning mr-3"
              role="button"
              :to="{name: 'create-user'}"
            >Create New User</router-link>
          </div>
        </div>
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

      <div class="spinner-load" v-if="spinner_loading">
        <Loader></Loader>
      </div>

      <!-- END spinner load -->

      <div class="m-2" v-if="!spinner_loading">
        <div
          class="modal fade"
          id="CreateUserModal"
          tabindex="-1"
          role="dialog"
          aria-labelledby="CreateUserModalLabel"
          aria-hidden="true"
        >
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-body">
                <div class="spinner-load" v-if="invoice_spinner_loading">
                  <Loader></Loader>
                </div>

                <!-- END Spinner Load -->

                <div v-if="invoices !== null && !invoice_spinner_loading">
                  <div class="form-group">
                    <div class="col-12">
                      <small>Next plan</small>
                      <h6 v-if="invoices.name === 'Monthly'">
                        {{invoices.name}} for
                        ${{invoices.amount}}/mo
                      </h6>
                      <h6 v-if="invoices.name === 'Yearly'">
                        {{invoices.name}} for
                        ${{invoices.amount}}/y
                      </h6>
                      <hr />
                    </div>
                  </div>
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>Description</th>
                        <th>Start period</th>
                        <th>End period</th>
                        <th>Total</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(item,key) in invoices.invoices" :key="key">
                        <td>Cinemarine service</td>
                        <td>{{item.start}}</td>
                        <td>{{item.end}}</td>
                        <td>${{item.total/100}}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="modal-footer">
                <button
                  type="button"
                  class="btn btn-sm btn-warning"
                  data-dismiss="modal"
                  @click="invoices = null"
                >Close</button>
              </div>
            </div>
          </div>
        </div>

        <!-- END Modal -->

        <div class="text-center" v-if="data.users === null">
          <h4>No result were found</h4>
        </div>

        <div v-else>
          <div v-if="Object.keys(data_search).length  > 0  && data_search.users !== null ">
            <div class="table-responsive mt-2" id="users-manage">
              <div class="table table-hover">
                <thead>
                  <th>#ID</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Status</th>
                  <th>Created</th>
                  <th>Updated</th>
                  <th>Control</th>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in data_search.users" :key="index">
                    <td>{{item.id}}</td>
                    <td>{{item.name}}</td>
                    <td>{{item.email}}</td>
                    <td>
                      <span class="badge badge-warning" v-if="item.confirmed === 0">Unconfirmed mail</span>
                      <span
                        class="badge badge-success"
                        v-if="new Date(item.period_end) > new Date()"
                      >Activity</span>
                      <span
                        class="badge badge-danger"
                        v-if="new Date(item.period_end) < new Date()"
                      >Inactivity</span>
                    </td>
                    <td>{{item.created_at}}</td>
                    <td>{{item.updated_at}}</td>

                    <td>
                      <div class="btn-group" role="group">
                        <router-link
                          role="button"
                          class="btn btn-sm btn-warning btn-sm mr-2"
                          :to="{name: 'edit-user',params:{id:item.id}}"
                        >Edit</router-link>                        
                        <router-link
                          role="button"
                          class="btn btn-sm btn-warning btn-sm mr-2"
                          :to="{name: 'withdrawals'}"
                        >Withdrawals</router-link>
                        <button
                          class="btn btn-sm btn-danger btn-sm mr-2"
                          @click="DELETE(item.id,index)"
                        >Delete</button>

                        <button
                          class="btn btn-sm btn-danger btn-sm mr-2"
                          data-toggle="modal"
                          data-target="#CreateUserModal"
                          @click="BILLING(item.id)"
                          v-if="item.braintree_id != null && item.card_brand != null"
                        >Billing</button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </div>
            </div>
          </div>

          <div v-if="data_search.users === null || Object.keys(data_search).length === 0">
            <div class="table-responsive mt-2" id="users-manage">
              <div class="table table-hover">
                <thead>
                  <th>#ID</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Status</th>
                  <th>Points</th>
                  <th>Created</th>
                  <th>Updated</th>
                  <th>Control</th>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in data.users.data" :key="index">
                    <td>{{item.id}}</td>

                    <td>{{item.name}}</td>
                    <td>{{item.email}}</td>
                    <td>
                      <span class="badge badge-warning" v-if="item.confirmed === 0">Unconfirmed mail</span>
                      <span v-if="item.period_end == null">
                        <span class="badge badge-success">Active (non-payment)</span>
                      </span>
                      <span v-else>
                        <span
                          class="badge badge-success"
                          v-if="new Date(item.period_end) > new Date()"
                        >Active</span>
                        <span
                          class="badge badge-danger"
                          v-if="new Date(item.period_end) < new Date()"
                        >Inactive</span>
                      </span>
                    </td>
                    <td>{{item.points}}</td>
                    <td>{{item.created_at}}</td>
                    <td>{{item.updated_at}}</td>

                    <td>
                      <div class="btn-group" role="group">
                        <router-link
                          role="button"
                          class="btn btn-sm btn-warning btn-sm mr-2"
                          :to="{name: 'edit-user',params:{id:item.id}}"
                        >Edit</router-link>
                        <router-link
                          role="button"
                          class="btn btn-sm btn-warning btn-sm mr-2"
                          :to="{name: 'withdrawals'}"
                        >All Withdrawals</router-link>
                        <button
                          class="btn btn-sm btn-danger btn-sm mr-2"
                          @click="DELETE(item.id,index)"
                          :id="item.id"
                        >Delete</button>
                        <button
                          class="btn btn-sm btn-danger mr-2"
                          data-toggle="modal"
                          data-target="#CreateUserModal"
                          @click="BILLING(item.id)"
                          v-if="item.braintree_id != null && item.card_brand != null"
                        >Billing</button>
                        <button
                          class="btn btn-sm btn-secondary mr-2"
                          v-if="item.braintree_id == null && item.card_brand == null"
                          data-toggle="tooltip"
                          data-placement="top"
                          title="The user have not billing because the payment gateway is disabled"
                          disabled
                        >Billing</button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </div>
            </div>

            <nav aria-label="pagination" class="m-4 p-1">
              <ul class="pagination">
                <li class="page-item" id="end">
                  <a class="page-link" @click="PAGINATION('/api/admin/get/users')">Begin</a>
                </li>
                <li
                  class="page-item"
                  id="prev"
                  :class="{disabled: data.users.prev_page_url === null}"
                >
                  <a class="page-link" @click="PAGINATION(data.users.prev_page_url)">Previous</a>
                </li>
                <li class="page-item">
                  <a class="page-link">{{data.users.current_page}}/{{data.users.last_page}}</a>
                </li>
                <li
                  class="page-item"
                  id="next"
                  :class="{disabled: data.users.next_page_url === null}"
                >
                  <a class="page-link" @click="PAGINATION(data.users.next_page_url)">Next</a>
                </li>
                <li class="page-item" id="end">
                  <a
                    class="page-link"
                    @click="PAGINATION('/api/admin/get/users?page='+data.users.last_page)"
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
  </div>
</template>

<script>
import { mapState } from "vuex";
import Loader from "../components/loader";
const alertify = require("alertify.js");

export default {
  name: "users-manage",
  data() {
    return {
      date: new Date(),
      search_query: null
    };
  },
  components: {
    Loader
  },
  computed: mapState({
    data: state => state.users.data,
    invoices: state => state.users.invoices,
    data_search: state => state.users.data_search,
    button_loading: state => state.users.button_loading,
    spinner_loading: state => state.users.spinner_loading,
    invoice_spinner_loading: state => state.users.invoice_spinner_loading
  }),

  created() {
    this.$store.commit("CLEAN_SEARCH_USERS");
    this.$store.dispatch("GET_ALL_USERS");
  },

  methods: {
    PAGINATION(path_url) {
      this.$store.dispatch("GET_ALL_USERS_BY_PAGINATION", path_url);
    },
    SHOW_WITHDRAWALS(id) {
      this.$router.push({ name: "users-withdrawals" });
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
      this.$store.dispatch("GET_ALL_USERS");
    },

    INACTIVITY() {
      this.$store.dispatch("GET_INACTIVITY_USERS");
    },

    ACTIVITY() {
      this.$store.dispatch("GET_ACTIVITY_USERS");
    },

    SEARCH() {
      this.$store.dispatch("GET_USERS_SEARCH", this.search_query);
    },

    BILLING(id) {
      this.$store.dispatch("GET_USERS_BILLING", id);
    }
  }
};
</script>
