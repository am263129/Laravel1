<template>
    <div>
        <div class="dashboard">
            <div class="alert alert-warning" role="alert" v-if="show_alert_services">
                You have some problem, Please check it from here
            </div>

            <div class="white users-chart" v-if="chart_type === 1">
                <h5 class="title p-2">Dashboard</h5>

                <div class="row">
                    <div class="col-6">
                        <div class="button-section mt-3 ml-5">
                            <button class="btn btn-sm btn-warning ml-1" :class="{active: chart_type === 1}"
                                    @click="chart_type=1">Users
                            </button>
                            <button class="btn btn-sm btn-warning ml-1" :class="{active: chart_type === 2}"
                                    @click="chart_type=2">Top
                            </button>
                            <button class="btn btn-sm btn-warning ml-1" :class="{active: chart_type === 3}"
                                    @click="chart_type=3">Regions
                            </button>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="button-section float-right mt-3 ml-5">
                            <button class="btn btn-sm btn-warning ml-1"
                                    :class="{active: users_analysis_active === 'day'}"
                                    @click="users_analysis_active = 'day'">DAY
                            </button>
                            <button class="btn btn-sm btn-warning ml-1"
                                    :class="{active: users_analysis_active === 'month'}"
                                    @click="users_analysis_active = 'month'">MONTH
                            </button>
                            <button class="btn btn-sm btn-warning ml-1"
                                    :class="{active: users_analysis_active === 'year'}"
                                    @click="users_analysis_active = 'year'">YEAR
                            </button>
                        </div>
                    </div>

                </div>

                <hr>

                <div class="col-12 row" v-if="chart_type === 1 && users_analysis_active == 'day'">
                    <div class="col-12 col-sm-6 col-lg-6">

                        <div class="col-12 m-2 details">
                            <span class="header">Active User</span>
                            <users-day-chart :data="activeUserDay" :options="options" :height="150"></users-day-chart>
                        </div>

                    </div>

                    <div class="col-12 col-sm-6 col-lg-6">

                        <div class="col-12 m-2 details">
                            <span class="header">Inactive User</span>
                            <users-day-chart :data="inactiveUserDay" :options="options" :height="150"></users-day-chart>
                        </div>

                    </div>

                </div>

                <!-- END User Chart Day -->


                <div class="col-12 row" v-if="chart_type === 1 && users_analysis_active == 'month'">

                    <div class="col-12 col-sm-6 col-lg-6">

                        <div class="col-12 m-2 details">
                            <span class="header">Active User</span>
                            <users-month-chart :data="activeUserMonth" :options="options"
                                               :height="150"></users-month-chart>
                        </div>

                    </div>

                    <div class="col-12 col-sm-6 col-lg-6">

                        <div class="col-12 m-2 details">
                            <span class="header">Inactive User</span>
                            <users-month-chart :data="inactiveUserMonth" :options="options"
                                               :height="150"></users-month-chart>
                        </div>

                    </div>
                </div>

                <!-- END User Chart Month -->


                <div class="col-12 row" v-if="chart_type === 1 && users_analysis_active == 'year'">

                    <div class="col-12 col-sm-6 col-lg-6">

                        <div class="col-12 m-2 details">
                            <span class="header">Active User</span>
                            <users-year-chart :data="activeUserYear" :options="options"
                                              :height="150"></users-year-chart>
                        </div>

                    </div>

                    <div class="col-12 col-sm-6 col-lg-6">

                        <div class="col-12 m-2 details">
                            <span class="header">Inactive User</span>
                            <users-year-chart :data="inactiveUserYear" :options="options"
                                              :height="150"></users-year-chart>
                        </div>

                    </div>

                </div>

                <!-- END User Chart Year -->
            </div>

            <!-- END User Chart -->

            <div class="white top-chart" v-if="chart_type === 2">
                <div class="row">
                    <div class="col-6">
                        <div class="button-section mt-3 ml-5">
                            <button class="btn btn-sm btn-warning ml-1" :class="{active: chart_type === 1}"
                                    @click="chart_type=1">Users
                            </button>
                            <button class="btn btn-sm btn-warning ml-1" :class="{active: chart_type === 2}"
                                    @click="chart_type=2">Top
                            </button>
                            <button class="btn btn-sm btn-warning ml-1" :class="{active: chart_type === 3}"
                                    @click="chart_type=3">Regions
                            </button>

                        </div>
                    </div>

                    <div class="col-6">
                        <div class="button-section float-right mt-3 ml-5">
                            <button class="btn btn-sm btn-warning ml-1" :class="{active: top_analysis_active === 'day'}"
                                    @click="top_analysis_active = 'day'">DAY
                            </button>
                            <button class="btn btn-sm btn-warning ml-1"
                                    :class="{active: top_analysis_active === 'month'}"
                                    @click="top_analysis_active = 'month'">MONTH
                            </button>
                            <button class="btn btn-sm btn-warning ml-1"
                                    :class="{active: top_analysis_active === 'year'}"
                                    @click="top_analysis_active = 'year'">YEAR
                            </button>
                        </div>
                    </div>

                </div>

                <hr>
                <div class="col-12 row" v-if="chart_type === 2 && top_analysis_active == 'day'">
                    <div class="col-12">

                        <div class="col-12 m-2 details">
                            <span class="header">MOST WATCH MOVIES AND EPISODE (Hourly)</span>
                            <top-day-chart :data="topDay" :options="options" :height="150"></top-day-chart>
                        </div>

                    </div>

                </div>

                <!-- END Top Chart Day -->


                <div class="col-12 row" v-if="chart_type === 2 && top_analysis_active == 'month'">

                    <div class="col-12">

                        <div class="col-12 m-2 details">
                            <span class="header">MOST WATCH MOVIES AND EPISODE (Monthly)</span>
                            <top-month-chart :data="topMonth" :options="options" :height="150"></top-month-chart>
                        </div>

                    </div>
                </div>

                <!-- END Top Chart Month -->


                <div class="col-12 row" v-if="chart_type ===2 && top_analysis_active == 'year'">

                    <div class="col-12">

                        <div class="col-12 m-2 details">
                            <span class="header">MOST WATCH MOVIES AND EPISODE (Yearly)</span>
                            <users-year-chart :data="topYear" :options="options" :height="150"></users-year-chart>
                        </div>

                    </div>
                </div>
            </div>

            <!-- END Top Chart -->


            <div class="white col-12 " v-if="chart_type === 3">
                <div class="row">
                    <div class="col-6">
                        <div class="button-section mt-3 ml-5">
                            <button class="btn btn-sm btn-warning ml-1" :class="{active: chart_type === 1}"
                                    @click="chart_type=1">Users
                            </button>
                            <button class="btn btn-sm btn-warning ml-1" :class="{active: chart_type === 2}"
                                    @click="chart_type=2">Top
                            </button>
                            <button class="btn btn-sm btn-warning ml-1" :class="{active: chart_type === 3}"
                                    @click="chart_type=3">Regions
                            </button>

                        </div>
                    </div>

                </div>
                <div class="col-12">

                    <div class="col-12 m-2 details">
                        <span class="header">Regions Users</span>
                        <regions :data="regions" :options="options" :height="150"></regions>
                    </div>

                </div>

            </div>



            <div class="crms text-center offset-1 p-2" v-if="total != null">
                <div class="col-12 row">

                    <div class="col-12 col-sm-6 col-md-3 col-lg-2 mt-2 m-md-2 white card">
                        <div class="details">
                           <img src="/themes/default/img/admin/report.svg" alt="report" width="60px">
                            <h4 class="reports">{{total.reports}}</h4>
                            <span class="header">Reports</span>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-3 col-lg-2 mt-2 m-md-2 white card">
                        <div class="details">
                          <img src="/themes/default/img/admin/movie.svg" alt="report" width="60px">
                            <h4 class="movies">{{total.movies}}</h4>
                            <span class="header">Movies</span>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-3 col-lg-2 mt-2 m-md-2 white card">
                        <div class="details">
                           <img src="/themes/default/img/admin/series.svg" alt="series" width="60px">
                            <h4 class="series">{{total.series}}</h4>
                            <span class="header">Series</span>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3 col-lg-2 mt-2 m-md-2 white card">
                        <div class="details">
                            <img src="/themes/default/img/admin/tv.svg" alt="tv" width="60px">
                            <h4 class="episode">{{total.tvs}}</h4>
                            <span class="header">Live Streaming</span>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3 col-lg-2 mt-2 m-md-2 white card">
                        <div class="details">
                             <img src="/themes/default/img/admin/users.svg" alt="tv" width="60px">
                            <h4 class="users">{{total.users}}</h4>
                            <span class="header">Users</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 offset-1 top">
                <div class="row">
                    <div class="col-12 col-md-3 white m-2" v-if="top_all_time.users !== []">
                        <b class="title ml-3">Top users</b>
                        <ul class="list-group mt-2">
                            <li class="list-group-item d-flex justify-content-between align-items-center"
                                v-for="(item,index) in top_all_time.users" :key="index"
                                v-if="item.name !== null">
                                {{item.name}}
                                <span class="count">{{item.user_count}}</span>
                            </li>
                        </ul>
                    </div>
                    <div class="col-12 col-md-3 white m-2" v-if="top_all_time.movies !== []">
                        <b class="title ml-3">Top movies</b>
                        <ul class="list-group mt-2">
                            <li class="list-group-item d-flex justify-content-between align-items-center"
                                v-for="(item,index) in top_all_time.movies" :key="index"
                                v-if="item.m_name !== null">
                                 <router-link  :to="{name:'analysis-movie', params: {id:item.m_id}}"> {{item.m_name}} </router-link>
                                <span class="count">{{item.movie_count}}</span>
                            </li>
                        </ul>
                    </div>
                    <div class="col-12 col-md-4 white m-2" v-if="top_all_time.series !== []">
                        <b class="title ml-3">Top series</b>
                        <ul class="list-group mt-2">
                            <li class="list-group-item d-flex justify-content-between align-items-center"
                                v-for="(item,index) in top_all_time.series" :key="index"
                                v-if="item.t_name !== null">
                                
                                <router-link  :to="{name:'analysis-series', params: {id:item.t_id}}"> {{item.t_name}}                       
                                ({{item.name}}) </router-link>
        
                                <span class="count">{{item.series_count}}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>

</template>
<script>
    import usersday from "./charts/users.js";
    import usersmonth from "./charts/users.js";
    import usersyear from "./charts/users.js";
    import topday from "./charts/users.js";
    import topmonth from "./charts/users.js";
    import topyear from "./charts/users.js";
    import regions from "./charts/regions.js";

    export default {
        components: {
            "users-day-chart": usersday,
            "users-month-chart": usersmonth,
            "users-year-chart": usersyear,
            "top-day-chart": topday,
            "top-month-chart": topmonth,
            "top-year-chart": topyear,
            'regions': regions
        },
        data() {
            return {
                show_alert_services: false,
                show_chart: true,
                chart_type: 1,
                users_analysis_active: "day",
                top_analysis_active: "day",
                total: null,
                top_all_time: [],

                activeUserDay: {
                    perecent: {},
                    all: 0,
                    labels: [],
                    datasets: [
                        {
                            label: "Active Daily",
                            yAxisID: "Active",
                            backgroundColor: "rgba(3, 169, 244, 0.7)",
                            pointBackgroundColor: "#818181",
                            borderWidth: 1,
                            borderColor: "#818181",
                            pointBorderColor: "#fff",
                            //Data to be represented on y-axis
                            data: []
                        }
                    ]
                },

                inactiveUserDay: {
                    perecent: {},
                    all: 0,
                    labels: [],
                    datasets: [
                        {
                            label: "Inactive Daily",
                            yAxisID: "Active",
                            backgroundColor: "rgba(233, 30, 99, 0.7)",
                            pointBackgroundColor: "#818181",
                            borderWidth: 1,
                            borderColor: "#818181",
                            pointBorderColor: "#fff",
                            //Data to be represented on y-axis
                            data: []
                        }
                    ]
                },

                activeUserMonth: {
                    perecent: {},
                    all: 0,
                    labels: [],
                    datasets: [
                        {
                            label: "Active Monthly",
                            yAxisID: "Active",
                            backgroundColor: "rgba(3, 169, 244, 0.7)",
                            pointBackgroundColor: "#818181",
                            borderWidth: 1,
                            borderColor: "#818181",
                            pointBorderColor: "#fff",
                            //Data to be represented on y-axis
                            data: []
                        }
                    ]
                },

                inactiveUserMonth: {
                    perecent: {},
                    all: 0,
                    labels: [],
                    datasets: [
                        {
                            label: "Inactive Monthly",
                            yAxisID: "Active",
                            backgroundColor: "rgba(233, 30, 99, 0.7)",
                            pointBackgroundColor: "#818181",
                            borderWidth: 1,
                            borderColor: "#818181",
                            pointBorderColor: "#fff",
                            //Data to be represented on y-axis
                            data: []
                        }
                    ]
                },

                activeUserYear: {
                    perecent: {},
                    all: 0,
                    labels: [],
                    datasets: [
                        {
                            label: "Active Yearly",
                            yAxisID: "Active",
                            backgroundColor: "rgba(3, 169, 244, 0.7)",
                            pointBackgroundColor: "#818181",
                            borderWidth: 1,
                            borderColor: "#818181",
                            pointBorderColor: "#fff",
                            //Data to be represented on y-axis
                            data: []
                        }
                    ]
                },

                inactiveUserYear: {
                    perecent: {},
                    all: 0,
                    labels: [],
                    datasets: [
                        {
                            label: "Inactive Yearly",
                            yAxisID: "Active",
                            backgroundColor: "rgba(233, 30, 99, 0.7)",
                            pointBackgroundColor: "#818181",
                            borderWidth: 1,
                            borderColor: "#818181",
                            pointBorderColor: "#fff",
                            //Data to be represented on y-axis
                            data: []
                        }
                    ]
                },

                topDay: {
                    perecent: {},
                    all: 0,
                    labels: [],
                    datasets: [
                        {
                            label: "Top Day",
                            yAxisID: "Active",
                            backgroundColor: "rgba(233, 30, 99, 0.7)",
                            pointBackgroundColor: "#818181",
                            borderWidth: 1,
                            borderColor: "#818181",
                            pointBorderColor: "#fff",
                            //Data to be represented on y-axis
                            data: []
                        }
                    ]
                },

                topMonth: {
                    perecent: {},
                    all: 0,
                    labels: [],
                    datasets: [
                        {
                            label: "Top Month",
                            yAxisID: "Active",
                            backgroundColor: "rgba(3, 169, 244, 0.7)",
                            pointBackgroundColor: "#818181",
                            borderWidth: 1,
                            borderColor: "#818181",
                            pointBorderColor: "#fff",
                            //Data to be represented on y-axis
                            data: []
                        }
                    ]
                },

                topYear: {
                    perecent: {},
                    all: 0,
                    labels: [],
                    datasets: [
                        {
                            label: "Top Year",
                            yAxisID: "Active",
                            backgroundColor: "rgba(233, 30, 99, 0.7)",
                            pointBackgroundColor: "#818181",
                            borderWidth: 1,
                            borderColor: "#818181",
                            pointBorderColor: "#fff",
                            //Data to be represented on y-axis
                            data: []
                        }
                    ]
                },

                regions: {
                    perecent: {},
                    all: 0,
                    labels: [],
                    datasets: [
                        {
                            label: "Users",
                            yAxisID: "Active",
                            backgroundColor: "rgba(233, 30, 99, 0.7)",
                            pointBackgroundColor: "#818181",
                            borderWidth: 1,
                            borderColor: "#818181",
                            pointBorderColor: "#fff",
                            //Data to be represented on y-axis
                            data: []
                        }
                    ]
                },
                //Chart.js options that controls the appearance of the chart
                options: {
                    scales: {
                        yAxes: [
                            {
                                id: "Active",
                                type: "linear",
                                position: "left",
                                ticks: {
                                    beginAtZero: false,
                                    display: false
                                },
                                gridLines: {
                                    display: false
                                }
                            }
                        ],
                        xAxes: [
                            {
                                gridLines: {
                                    display: false
                                }
                            }
                        ],
                        zAxes: [
                            {
                                ticks: {
                                    display: false
                                }
                            }
                        ]
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
            axios.get("/api/admin/get/dashboard/analysis").then(response => {
                if (response.status === 200) {
                    // Active and Inactivity User
                    // Day
                    for (
                        let index = 0;
                        index < response.data.data.users.day.length;
                        index++
                    ) {
                        if (response.data.data.users.day[index].type === "active") {
                            //Active
                            this.activeUserDay.labels.push(
                                response.data.data.users.day[index].hour
                            );
                            this.activeUserDay.datasets[0].data.push(
                                response.data.data.users.day[index].number
                            );
                        } else {
                            //Inactive
                            this.inactiveUserDay.labels.push(
                                response.data.data.users.day[index].hour
                            );
                            this.inactiveUserDay.datasets[0].data.push(
                                response.data.data.users.day[index].number
                            );
                        }
                    }

                    //Month
                    for (
                        let index = 0;
                        index < response.data.data.users.month.length;
                        index++
                    ) {
                        if (response.data.data.users.month[index].type === "active") {
                            //Active
                            this.activeUserMonth.labels.push(
                                response.data.data.users.month[index].month
                            );
                            this.activeUserMonth.datasets[0].data.push(
                                response.data.data.users.month[index].number
                            );
                        } else {
                            //Inactive
                            this.inactiveUserMonth.labels.push(
                                response.data.data.users.month[index].month
                            );
                            this.inactiveUserMonth.datasets[0].data.push(
                                response.data.data.users.month[index].number
                            );
                        }
                    }

                    //Year
                    for (
                        let index = 0;
                        index < response.data.data.users.year.length;
                        index++
                    ) {
                        if (response.data.data.users.year[index].type === "active") {
                            //Active
                            this.activeUserYear.labels.push(
                                response.data.data.users.year[index].year
                            );
                            this.activeUserYear.datasets[0].data.push(
                                response.data.data.users.year[index].number
                            );
                        } else {
                            //Inactive
                            this.inactiveUserYear.labels.push(
                                response.data.data.users.year[index].year
                            );
                            this.inactiveUserYear.datasets[0].data.push(
                                response.data.data.users.year[index].number
                            );
                        }
                    }

                    // Top movie and series
                    // Day
                    for (
                        let index = 0;
                        index < response.data.data.top.day.length;
                        index++
                    ) {
                        this.topDay.labels.push(response.data.data.top.day[index].name);
                        this.topDay.datasets[0].data.push(
                            response.data.data.top.day[index].count
                        );
                    }

                    //Month
                    for (
                        let index = 0;
                        index < response.data.data.top.month.length;
                        index++
                    ) {
                        this.topMonth.labels.push(response.data.data.top.month[index].name);
                        this.topMonth.datasets[0].data.push(
                            response.data.data.top.month[index].count
                        );
                    }

                    //Year
                    for (let index = 0; index < response.data.data.top.year.length; index++) {
                        this.topYear.labels.push(response.data.data.top.year[index].name);
                        this.topYear.datasets[0].data.push(
                            response.data.data.top.year[index].count
                        );
                    }

                    // Regions
                    for (let index = 0; index < response.data.data.regions.length; index++) {
                        this.regions.labels.push(response.data.data.regions[index].country);
                        this.regions.datasets[0].data.push(
                            response.data.data.regions[index].number
                        );
                    }


                    this.total = response.data.data.count;
                    this.top_all_time = response.data.data.top_all_time;
                }
            });

        },

        methods: {}
    };
</script>