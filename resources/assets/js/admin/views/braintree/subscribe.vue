<template>
<div class="subscribe-braintree">

    <div class="spinner-load" v-if="loading">
        <Loader></Loader>
    </div>

    <div v-if="!loading">
        <div class="k1_manage_table" v-if="planList !== null">
            <h5 class="title p-2">Braintree Manage</h5>
            <div class="alert alert-warning" role="alert" v-if="planList.status === 'failed'">
                {{planList.message}}
            </div>
            <div class="col-12 my-3 p-2">
                <div class="group-btn">
                    <button class="btn btn-sm btn-success" @click="INACTIVE_PAYMENT_GATEWAY"
                            v-if="planList.payment_gateway_status"> Active Payment Gateway
                    </button>
                    <button class="btn btn-sm btn-danger" @click="INACTIVE_PAYMENT_GATEWAY"
                            v-if="!planList.payment_gateway_status"> Inactive Payment Gateway
                    </button>

                </div>
            </div>

            <div class="row p-2">
                <div class="col-12 col-md-4 col-xl-3 mt-2" v-for="(item, index) in planList.data" :key="index">

                    <div class="custom-card">

                        <div class="header">
                            <h5>{{planList.data[index]['plan'].name}}</h5>
                            <small class="text-muted">{{planList.data[index]['plan'].id}}</small>
                        </div>
                        <hr>
                        <div class="body">

                            <ul class="list-unstyled">

                                <li>
                                    <strong>Amount</strong>
                                    <br> {{planList.data[index]['plan'].price}}
                                </li>
                                <li>
                                    <strong>Currency</strong>
                                    <br> {{planList.data[index]['plan'].currencyIsoCode}}
                                </li>

                                <li v-if="item.trialPeriod == true">
                                    <strong>Trial</strong>
                                    <br> {{planList[index]['plan'].trialDuration}}
                                    {{planList[index]['plan'].trialDurationUnit}}
                                </li>

                                <li v-if="item.trialPeriod == false">
                                    <strong>Trial</strong>
                                    <br> No Trial Period
                                </li>
                            </ul>

                            <hr>

                            <button class="btn btn-sm btn-danger" v-if="planList.data[index].active && btn_loading !== index"
                                    @click="ACTIVE(index); btn_loading = index">Inactive
                            </button>
                            <button class="btn btn-sm btn-success"
                                    v-if="!planList.data[index].active && btn_loading !== index"
                                    @click="ACTIVE(index); btn_loading = index">Active
                            </button>
                            <button class="btn btn-sm btn-success" v-if="btn_loading === index" disabled><i
                                    class="fa fa-circle-o-notch fa-spin"></i> Loading
                            </button>

                        </div>
                    </div>

                </div>
            </div>

            <div class="p-3">
                <hr>
                <h6 class="text-muted">If you update the plan, click to inactive and active it again</h6>
            </div>
        </div>
        <div v-else class="text-center my-5">
            <h1>There is no plan found</h1>
        </div>
    </div>
</div>
</template>

<script>
const alertify = require("alertify.js");

export default {
    data() {
        return {
            planList: [],
            loading: true,
            btn_loading: false
        };
    },

    mounted() {
        axios.get("/api/admin/get/braintree/plans").then(response => {
            if (response.status === 200) {
                this.planList = response.data;
                this.loading = false;
            }
        });
    },

    methods: {
        ACTIVE(index) {
            this.btn_loading = true;
            axios
                .post("/api/admin/update/braintree/plans", {
                    plan_id: this.planList.data[index]['plan']["id"],
                    plan_name: this.planList.data[index]['plan']["name"],
                    plan_amount: this.planList.data[index]['plan']["price"],
                    plan_currency: this.planList.data[index]['plan']["currencyIsoCode"],
                    plan_trial: this.planList.data[index]['plan']["trialDuration"]
                })
                .then(
                    response => {
                        if (response.status === 200) {
                            if (response.data.type === "add") {
                                alertify.logPosition("top right");
                                alertify.success(response.data.message);
                                this.planList.data[index]["active"] = true;
                                this.btn_loading = false;
                            } else {
                                alertify.logPosition("top right");
                                alertify.success(response.data.message);
                                this.planList.data[index]["active"] = false;
                                this.btn_loading = false;
                            }
                        }
                    },
                    error => {
                        alertify.logPosition("top right");
                        alertify.success("Error in input data");
                        this.btn_loading = false;
                    }
                );
        },

        INACTIVE_PAYMENT_GATEWAY() {
            axios.get("/api/admin/update/braintree/payment/status").then(response => {
                if (response.status === 200) {
                    if (response.data.type == 'active') {
                        this.planList.payment_gateway_status = 1;
                    } else if (response.data.type == 'inactive') {
                        this.planList.payment_gateway_status = 0;
                    }
                    alertify.logPosition('top right');
                    alertify.success(response.data.message);
                }
            }, error => {
                alertify.logPosition('top right');
                alertify.error('Error to remove plan');
            });
        }
    }
};
</script>

<style scoped>
.custom-card {
    background-color: #f9fcfe;
    padding: 10px;
    padding-bottom: 20px;
    border-radius: 5px;
    font-size: 15px;
}
</style>
