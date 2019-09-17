<template>
    <div>
    <div class="k1_manage_table">
      <h5 class="title p-2">Create New User</h5>
        <div class="container my-5">
            <div class="row">

                <!-- END LIST -->

                <div class="col-12 col-sm-6 col-lg-6" id="profile-setting">

                    <div id="profile-details">
                        <div class="form-group">
                            <label class="col-8 control-label">Name</label>
                            <div class="col-12">
                                <input class="form-control" type="name" name="name" v-validate="'min:6|max:24'" :class="{'input': true, 'input-danger': errors.has('name') }"
                                    v-model="name" placeholder="Name">
                                <span v-show="errors.has('name')" class="help is-danger">{{errors.first('name')}}
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-8 control-label">E-mail</label>
                            <div class="col-12">
                                <input class="form-control" type="email" name="email" v-model="email" v-validate="'max:50'" :class="{'input': true, 'input-danger': errors.has('email') }"
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
                                <input class="form-control" type="password" name="confirm-field" v-validate="'min:8'" :class="{'input': true, 'input-danger': errors.has('password') }"
                                    v-model="password" placeholder="Password">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-8 control-label">Subscribe</label>
                            <div class="col-12 btn-group">

                              <div class="btn btn-sm btn-warning" :class="{active: subscribe === 'week'}" @click="subscribe = 'week'">Weekly</div>
                              <div class="btn btn-sm btn-warning" :class="{active: subscribe === 'month'}" @click="subscribe = 'month'">Monthly</div>
                              <div class="btn btn-sm btn-warning" :class="{active: subscribe === 'year'}" @click="subscribe = 'year'">Yearly</div>

                            </div>

                        </div>


                        <div class="form-group">
                            <div class="col-12">
                                <button v-if="! button_loading" class="btn btn-md btn-warning mt-2" @click="CREATE">Create</button>
                                <button v-if="button_loading" class="btn btn-md btn-warning m-2" disabled>
                                    <i id="btn-progress"></i> Loading</button>
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
const alertify = require("alertify.js");
import { mapState } from "vuex";

export default {
  data() {
    return {
      name: null,
      email: null,
      phone: null,
      password: null,
      subscribe: null
}
  },

  computed: mapState({
    data: state => state.users.data,
    button_loading: state => state.users.button_loading

  }),

  methods: {
    CREATE() {
      this.$validator.validateAll().then(result => {
        if (result) {
          this.$store.dispatch("CREATE_NEW_USER", {
            name: this.name,
            email: this.email,
            phone: this.phone,
            password: this.password,
            subscribe: this.subscribe,
        });
        }
      });
    }
  }
};
</script>
