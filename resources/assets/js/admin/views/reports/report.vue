<template>
<div>

    <div class="spinner-load" v-if="spinner_loading">
        <Loader></Loader>
    </div>

    <div class="k1_manage_table p-1" v-if="!spinner_loading">
        <div v-if="data.reports !== null">
            <h5 class="title p-2">{{data.reports.data[0].name}}</h5>

            <div class="report-modal" v-if="show_report_modal">
                <div class="col-12 col-sm-8 col-lg-10 offset-sm-2 offset-lg-1" id="modal">
                    <div class="header">
                        <span id="close" @click="show_report_modal = false">
              <i class="fa fa-times-circle-o" aria-hidden="true"></i>
            </span>
                    </div>
                    <div class="body">
                        <ul>
                            <li>
                                <b>Sender Name</b>
                                <br> {{report_item.username}}
                            </li>
                            <li>
                                <b>Report Type</b>
                                <br>
                                <span v-if="report_item.report_type === 1" class="badge badge-warning">Labeling Problem</span>
                                <span v-if="report_item.report_type === 2" class="badge badge-danger">Video Problem</span>
                                <span v-if="report_item.report_type === 3" class="badge badge-danger">Sound Problem</span>
                                <span v-if="report_item.report_type === 4" class="badge badge-warning">Captios Problem</span>
                            </li>
                            <li>
                                <b>Name</b>
                                <br> {{report_item.name}}
                            </li>

                            <li>
                                <b>Report Details</b>
                                <br> {{report_item.report_details}}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- END MODAL -->

            <div class="table-responsive mt-2">
                <div class="table table-hover">
                    <thead>
                        <th>#ID</th>
                        <th>Report Type</th>
                        <th>Where</th>
                        <th>Sender name</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Control</th>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in data.reports.data" :key="index">
                            <td>{{item.id}}</td>
                            <td v-if="item.report_type === 1">
                                <span class="badge badge-warning">Labeling Problem</span>
                            </td>
                            <td v-if="item.report_type === 2">
                                <span class="badge badge-danger">Video Problem</span>
                            </td>
                            <td v-if="item.report_type === 3">
                                <span class="badge badge-danger">Sound Problem</span>
                            </td>
                            <td v-if="item.report_type === 4">
                                <span class="badge badge-warning">Captios Problem</span>
                            </td>
                            <td v-if="item.type === 'series'">{{item.name+' S:'+item.season_number+' E:'+ item.episode_number}}</td>
                            <td v-if="item.type === 'movie'">{{item.name}}</td>
                            <td>{{item.username}}</td>
                            <td v-if="item.report_readit === 0">
                                <span class="badge badge-warning">See it</span>
                            </td>
                            <td v-if="item.report_readit === 1">
                                <span class="badge badge-success">Saw it</span>
                            </td>
                            <td>{{item.created_at}}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-warning btn-sm mr-2" @click="SHOW_REPORT(index)">Show</button>
                                    <button class="btn btn-sm btn-danger btn-sm mr-2" @click="DELETE(item.id,index)">Delete</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </div>
            </div>

            <!-- END TABLE -->

            <nav aria-label="pagination" class="m-4 p-1">
                <ul class="pagination">
                    <li class="page-item" id="end">
                        <a class="page-link" @click="PAGINATION('/api/admin/get/report/'+ $route.params.id)">Begin</a>
                    </li>
                    <li class="page-item" id="prev" :class="{disabled: data.reports.prev_page_url === null}">
                        <a class="page-link" @click="PAGINATION(data.reports.prev_page_url)">Previous</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link">{{data.reports.current_page}}/{{data.reports.last_page}}</a>
                    </li>
                    <li class="page-item" id="next" :class="{disabled: data.reports.next_page_url === null}">
                        <a class="page-link" @click="PAGINATION(data.reports.next_page_url)">Next</a>
                    </li>
                    <li class="page-item" id="end">
                        <a class="page-link" @click="PAGINATION('/api/admin/get/reports'+ $route.params.id +'?page='+data.reports.last_page)">End</a>
                    </li>
                </ul>
            </nav>
        </div>
        <!-- END PAGINATE -->
    </div>
</div>
</template>

<script>
const alertify = require("alertify.js");
import {
    mapState
} from "vuex";
import Loader from "../components/loader";

export default {
    data() {
        return {
            report_item: [],
            show_report_modal: false
        };
    },

    components: {
        Loader
    },

    computed: mapState({
        data: state => state.reports.data,
        data_search: state => state.reports.data_search,
        spinner_loading: state => state.reports.spinner_loading
    }),

    created() {
        this.$store.dispatch("GET_REPORT", this.$route.params.id);
    },

    methods: {
        PAGINATION(path_url) {
            this.$store.dispatch("GET_REPORT_PAGINATION", path_url);
        },

        SHOW_REPORT(key) {
            this.show_report_modal = true;
            this.report_item = this.data.reports.data[key];
            axios
                .put("/api/admin/new/report/readit/" + this.data.reports.data[key].id)
                .then(response => {
                    if (response.data.status === 200) {
                        this.data.reports.data[key].report_readit = 1;
                    }
                });
        },

        DELETE(id, key) {
            swal({
                title: "Are you sure to delete ?",
                icon: "warning",
                buttons: true,
                dangerMode: true
            }).then(willDelete => {
                if (willDelete) {
                    this.$store.dispatch("DELETE_REPORT", {
                        id,
                        key
                    });
                }
            });
        }
    }
};
</script>
