<template>
    <div class="edit-users">

        <div class="spinner-load" v-if="spinner_loading">
            <Loader></Loader>
        </div>


        <div class="k1_manage_table p-2" v-else>
            <div class="container my-5">
                <div class="row">

                    <!-- END LIST -->

                    <div class="col-12 col-sm-6 col-lg-6" id="profile-setting">

                        <div id="profile-details">
                            <div class="form-group">
                                <div class="col-12">
                                    <h4 v-if="data.card_brand !== null">
                                        <i class="fa fa-cc-visa" aria-hidden="true"></i>
                                        ************{{data.card_last_four}}
                                    </h4>
                                    <h6 v-if="data.braintree_id == null && data.card_brand == null">
                                        Subscription ends in : {{data.period_end}}
                                    </h6>
                                    <span class="badge badge-success" v-if="new Date(data.period_end) > new Date()">Active</span>
                                    <span class="badge badge-danger" v-if="new Date(data.period_end) < new Date()">Inactive</span>
                                    <hr>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-8 control-label">Name</label>
                                <div class="col-12">
                                    <input class="form-control" type="name" name="name" v-validate="'min:6|max:24'"
                                           :class="{'input': true, 'input-danger': errors.has('name') }"
                                           v-model="data.name" placeholder="Name">
                                    <span v-show="errors.has('name')" class="help is-danger">{{errors.first('name')}}
                                </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-8 control-label">E-mail</label>
                                <div class="col-12">
                                    <input class="form-control" type="email" name="email" v-model="data.email"
                                           v-validate="'max:50'"
                                           :class="{'input': true, 'input-danger': errors.has('email') }"
                                           placeholder="E-mail">
                                    <span v-show="errors.has('email')" class="help is-danger">{{errors.first('email')}}
                                </span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-8 control-label">Phone</label>
                                <div class="col-12">
                                    <input class="form-control" type="text" name="phone" v-model="data.phone"
                                           v-validate="'max:12'"
                                           :class="{'input': true, 'input-danger': errors.has('phone') }"
                                           placeholder="Phone Number">
                                    <span v-show="errors.has('phone')" class="help is-danger">{{errors.first('phone')}}
                                </span>
                                </div>
                            </div>



                            <div class="form-group">
                                <label class="col-8 control-label">Password</label>
                                <div class="col-12">
                                    <input class="form-control" type="password" name="confirm-field"
                                           v-validate="'min:8'"
                                           :class="{'input': true, 'input-danger': errors.has('password') }"
                                           v-model="password" placeholder="Password">
                                </div>
                            </div>

                            <div class="form-group" v-if="data.braintree_id == null && data.card_brand == null">
                                <label class="col-8 control-label">Subscribe</label>
                                <div class="col-12 btn-group">
                                    <div class="btn btn-sm btn-warning" :class="{active: subscribe === 'week'}"
                                         @click="subscribe = 'week'">Weekly
                                    </div>
                                    <div class="btn btn-sm btn-warning" :class="{active: subscribe === 'month'}"
                                         @click="subscribe = 'month'">Monthly
                                    </div>
                                    <div class="btn btn-sm btn-warning" :class="{active: subscribe === 'year'}"
                                         @click="subscribe = 'year'">Yearly
                                    </div>
                                </div>

                            </div>

                            <div class="form-group">
                                <div class="col-12">
                                    <button v-if="! button_loading" class="btn btn-md btn-warning mt-2" @click="UPDATE">
                                        Update
                                    </button>
                                    <button v-if="button_loading" class="btn btn-md btn-warning m-2" disabled>
                                        <i id="btn-progress"></i> Loading
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState} from "vuex";
    import Loader from "../components/loader";

    export default {
        data() {
            return {
                password: null,
                subscribe: null
            };
        },

        components: {
            Loader
        },

        computed: mapState({
            data: state => state.users.data,
            button_loading: state => state.users.button_loading,
            spinner_loading: state => state.users.spinner_loading

        }),

        created() {
            this.$store.dispatch("GET_USER_DETAILS", this.$route.params.id);
        },

        methods: {
            UPDATE() {
                this.$validator.validateAll().then(result => {
                    if (result) {
                        this.$store.dispatch("UPDATE_USER_DETAILS", {
                            id: this.data.id,
                            name: this.data.name,
                            email: this.data.email,
                            phone: this.data.phone,
                            braintree_id: this.data.braintree_id,
                            password: this.password,
                            subscribe: this.subscribe
                        });
                    }
                });
            }
        }
    };
</script>
