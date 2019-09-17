<template>
<div class="analysis">

    <div class="spinner-load" v-if="spinner_loading">
        <Loader></Loader>
    </div>

    <div class="dashboard" v-if="!spinner_loading">

        <div class="change-date">
            <div class="dropdown">
                <button class="dropdown-toggle" type="button" id="dropdownNavbar" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size: 15px;font-weight: bold;color: #2996f3;text-transform: uppercase;">
                           {{analysis_date}}
            </button>
                <div class="dropdown-menu" aria-labelledby="dropdownNavbar">
                    <div class="dropdown-item" @click="analysis_date = 'Daily'">Daily</div>
                    <div class="dropdown-item" @click="analysis_date = 'Monthly'">Monthly</div>
                    <div class="dropdown-item" @click="analysis_date = 'Yearly'">Yearly</div>
                </div>
            </div>

        </div>

        <div class="col-12 row">
            <div class="col-12 col-sm-6 crms text-center p-2">
                <div class="row">
                    <div class="col-12 col-lg-6 mt-2">
                        <div class="details m-md-2 white">
                            <img src="/themes/default/img/admin/heart.svg" alt="Likes" width="60px">
                            <h4 class="likes" v-if="analysis_date == 'Daily'">{{like.day}}</h4>
                            <h4 class="likes" v-if="analysis_date == 'Monthly'">{{like.month}}</h4>
                            <h4 class="likes" v-if="analysis_date == 'Yearly'">{{like.year}}</h4>
                            <span class="header">Likes</span>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 mt-2">
                        <div class="details m-md-2 white">
                            <img src="/themes/default/img/admin/star.svg" alt="Favor" width="60px">
                            <h4 class="favor" v-if="analysis_date == 'Daily'">{{favor.day}}</h4>
                            <h4 class="favor" v-if="analysis_date == 'Monthly'">{{favor.month}}</h4>
                            <h4 class="favor" v-if="analysis_date == 'Yearly'">{{favor.year}}</h4>
                            <span class="header">Favor</span>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 mt-2">
                        <div class="details m-md-2 white">
                            <img src="/themes/default/img/admin/movie.svg" alt="views" width="60px">
                            <h4 class="favor">{{all_views}}</h4>
                            <span class="header">All Views</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6">
                <span class="title p-2">Views</span>
                <div class="details white m-2 p-2">

                    <div class="chart" v-if="analysis_date == 'Daily'">

                        <views-day-chart :data="watchMovieInDay" :options="options" :height="150" v-if="watchMovieInDay.datasets[0].data.length > 0"></views-day-chart>

                        <div class="no-views m-3 text-center" v-else>
                            <h3 class="title">There is no view in this day</h3>
                        </div>
                    </div>

                    <div class="chart" v-if="analysis_date == 'Monthly'">

                        <views-month-chart :data="watchMovieInMonth" :options="options" :height="150" v-if="watchMovieInMonth.datasets[0].data.length > 0"></views-month-chart>

                        <div class="no-views m-3 text-center" v-else>
                            <h3 class="title">There is no view in last month</h3>
                        </div>

                    </div>

                    <div class="chart" v-if="analysis_date == 'Yearly'">
                        <views-year-chart :data="watchMovieInYear" :options="options" :height="150" v-if="watchMovieInYear.datasets[0].data.length > 0"></views-year-chart>
                        <div class="no-views m-3 text-center" v-else>
                            <h3 class="title">There is no view in last year</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 mt-3">
                <div class="title p-2">Latest Views</div>
                <div class="table-responsive white" v-if="latest_views.length > 0">
                    <div class="table table-hover">
                        <thead>
                            <th>Username</th>
                            <th>View date</th>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in latest_views" :key="index">
                                <td>{{item.user_name}}</td>
                                <td>{{item.created_at}}</td>
                            </tr>
                        </tbody>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
</template>

<script>
import viewsinday from "../charts/regions.js";
import viewsinmonth from "../charts/regions.js";
import viewsinyear from "../charts/regions.js";
import Loader from "../components/loader";

export default {
    components: {
        "views-day-chart": viewsinday,
        "views-month-chart": viewsinmonth,
        "views-year-chart": viewsinyear,
        Loader
    },

    data() {
        return {
            show_alert_services: false,
            show_chart: true,
            analysis_date: "Daily",
            like: {},
            favor: {},
            all_views: null,
            latest_views: [],
            spinner_loading: true,
            watchMovieInDay: {
                perecent: {},
                all: 0,
                labels: [],
                datasets: [{
                    label: "Views In Year",
                    yAxisID: "Views",
                    backgroundColor: "rgba(3, 169, 244, 0.7)",
                    pointBackgroundColor: "#818181",
                    borderWidth: 1,
                    borderColor: "#818181",
                    pointBorderColor: "#fff",
                    //Data to be represented on y-axis
                    data: []
                }]
            },

            watchMovieInMonth: {
                perecent: {},
                all: 0,
                labels: [],
                datasets: [{
                    label: "Views In Year",
                    yAxisID: "Views",
                    backgroundColor: "rgba(3, 169, 244, 0.7)",
                    pointBackgroundColor: "#818181",
                    borderWidth: 1,
                    borderColor: "#818181",
                    pointBorderColor: "#fff",
                    //Data to be represented on y-axis
                    data: []
                }]
            },

            watchMovieInYear: {
                perecent: {},
                all: 0,
                labels: [],
                datasets: [{
                    label: "Views In Year",
                    yAxisID: "Views",
                    backgroundColor: "rgba(3, 169, 244, 0.7)",
                    pointBackgroundColor: "#818181",
                    borderWidth: 1,
                    borderColor: "#818181",
                    pointBorderColor: "#fff",
                    //Data to be represented on y-axis
                    data: []
                }]
            },

            //Chart.js options that controls the appearance of the chart
            options: {
                scales: {
                    yAxes: [{
                        id: "Views",
                        type: "linear",
                        position: "left",
                        ticks: {
                            beginAtZero: false,
                            display: false
                        },
                        gridLines: {
                            display: false
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            display: false
                        }
                    }],
                    zAxes: [{
                        ticks: {
                            display: false
                        }
                    }]
                },
                legend: {
                    display: false
                },
                responsive: true,
                maintainAspectRatio: false
            }
        };
    },

    mounted() {
        axios
            .get("/api/admin/analysis/movie/" + this.$route.params.id)
            .then(response => {
                if (response.status === 200) {
                    // Active and Inactivity User
                    // Day
                    for (
                        let index = 0; index < response.data.data.views.day.length; index++
                    ) {
                        //Active
                        this.watchMovieInDay.labels.push(
                            response.data.data.views.day[index].hour
                        );
                        this.watchMovieInDay.datasets[0].data.push(
                            response.data.data.views.day[index].number
                        );
                    }

                    //Month
                    for (
                        let index = 0; index < response.data.data.views.month.length; index++
                    ) {
                        this.watchMovieInMonth.labels.push(
                            response.data.data.views.month[index].month
                        );
                        this.watchMovieInMonth.datasets[0].data.push(
                            response.data.data.views.month[index].number
                        );
                    }

                    //Year
                    for (
                        let index = 0; index < response.data.data.views.year.length; index++
                    ) {
                        this.watchMovieInYear.labels.push(
                            response.data.data.views.year[index].year
                        );
                        this.watchMovieInYear.datasets[0].data.push(
                            response.data.data.views.year[index].number
                        );
                    }

                    this.like = response.data.data.like;
                    this.favor = response.data.data.favor;
                    this.latest_views = response.data.data.latest_views;
                    this.all_views = response.data.data.all_views;
                    this.spinner_loading = false;
                }
            });
    },

    methods: {}
};
</script>
