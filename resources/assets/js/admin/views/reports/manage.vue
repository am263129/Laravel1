<template>
<div>
    <div class="spinner-load" v-if="spinner_loading">
        <Loader></Loader>
    </div>
    <!-- END spinner load -->
    <div class="k1_manage_table" v-if="!spinner_loading">

        <h5 class="title p-2">Reports</h5>

        <div class="m-2">
            <div v-if="data.reports !== null">
                <div class="report-list">
                    <div v-for="(item, index) in data.reports.data" :key="index">
                        <div class="col-12 my-2" v-if="item.m_name  !== null" id="report-box">
                            <div class="row">
                                <div class="col-12 col-sm-2 col-lg-1">
                                    <img :src="'/storage/posters/300_' + item.m_poster" :alt="item.m_poster" width="100%">
                                    <div class="notification" v-if="item.report_not_readit">
                                        <span>{{item.report_not_readit}}</span>
                                    </div>
                                    </div>
                                    <div class="col-7 text-left">
                                        <ul>
                                            <li>
                                                <b>#ID:</b> {{item.id}}
                                            </li>
                                            <li>
                                                <b>Name:</b> {{item.m_name}}
                                            </li>
                                            <li>
                                                <b>Report type:</b>
                                                <span class="badge badge-primary">
                                                    <i class="fa fa-film fa-1x" aria-hidden="true"></i> Movie</span>
                                            </li>
                                            <li>
                                                <b>Reports number:</b>
                                                <span class="badge badge-primary"> {{item.movies_count}}</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-sm-3 col-lg-4">
                                        <div class="btn-group float-right" role="group">
                                            <router-link role="button" class="btn btn-sm btn-warning btn-sm mr-2" :to="{name: 'report-show',params: {id:item.id}}">Show
                                            </router-link>
                                            <button class="btn btn-sm btn-danger btn-sm mr-2" @click="DELETE(item.m_id,index)" :id="item.id">Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12" v-if="item.t_name !== null" id="report-box">
                                <div class="row">
                                    <div class="col-12 col-sm-3 col-md-3 col-lg-1">
                                        <img :src="'/storage/posters/300_' + item.t_poster" :alt="item.m_poster" width="100%">
                                    </div>
                                        <div class="col-12 col-sm-7 col-md-5 col-lg-7 text-left">
                                            <ul>
                                                <li>
                                                    <b>ID:</b> {{item.id}}
                                                </li>
                                                <li>
                                                    <b>Name:</b> {{item.t_name}}
                                                </li>
                                                <li>
                                                    <b>Report type:</b>
                                                    <span class="badge badge-primary">
                                                    <i class="fa fa-television fa-1x" aria-hidden="true"></i> Series</span>
                                                </li>
                                                <li>
                                                    <b>Reports number:</b>
                                                    <span class="badge badge-primary"> {{item.series_count}}</span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-12 col-sm-2 col-md-4 ">
                                            <div class="btn-group float-sm-right" role="group">
                                                <router-link role="button" class="btn btn-sm btn-warning btn-sm mr-2" :to="{name: 'report-show',params: {id:item.id}}">Show
                                                </router-link>
                                                <button class="btn btn-sm btn-danger btn-sm mr-2" @click="DELETE(item.t_id,index)" :id="item.id">Delete
                                            </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- END TABLE -->

                        <nav aria-label="pagination" class="m-4 p-1">
                            <ul class="pagination">
                                <li class="page-item" id="end">
                                    <a class="page-link" @click="PAGINATION('/api/admin/get/reports')">Begin</a>
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
                                    <a class="page-link" @click="PAGINATION('/api/admin/get/reports?page='+data.reports.last_page)">End</a>
                                </li>
                            </ul>
                        </nav>

                        <!-- END Pagination  -->
                    </div>

                    <div v-else>
                        <div class="text-center mt-5 mr-5">
                            <h2>Sorry no result were found</h2>
                        </div>
                    </div>

                </div>
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
    name: "report-manage",
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
        this.$store.dispatch("GET_ALL_REPORTS");
    },

    methods: {
        PAGINATION(path_url) {
            this.$store.dispatch("GET_REPORTS_PAGINATION", path_url);
        },

        SHOW_REPORT(key) {
            this.show_report_modal = true;
            this.report_item = this.items[key];
            axios.put("api/admin/reports/readit/" + this.items[key].id).then(
                res => {
                    if (res.data.status === "success") {
                        this.items[key].report_readit = 1;
                    }
                },
                error => {
                    alertify.logPosition("top right");
                    alertify.error("There is error in back-end");
                }
            );
        },
        DELETE(id, key) {
            swal({
                title: "Are you sure to delete ?",
                icon: "warning",
                buttons: true,
                dangerMode: true
            }).then(willDelete => {
                if (willDelete) {
                    this.$store.dispatch("DELETE_ALL_REPORTS", {
                        id,
                        key
                    });
                }
            });
        }
    }
};
</script>
