<template>
  <div>
    <transition name="fade">
      <div class="settings">
        <div class="row">
          <div class="exit-icon" @click="$Helper.home()">
            <exit-button></exit-button>
          </div>
          <div class="col-12 offset-2">
            <h2>Your Balance: {{balance}} Pts.</h2>
          </div>
  <p v-if="errors.length">
    <b>Please correct the following error(s):</b>
    <ul>
      <li :key="error" v-for="error in errors">{{ error }}</li>
    </ul>
  </p>
          <div class="col-6 col-sm-8 offset-sm-2 p-sm-3 mt-5 h-100">
            <div class="title">
              <p>My Withdrawals</p>
            </div>
          <button @click="showWForm = !showWForm" class="btn btn-warning btn-sm">+add</button>
            <div v-if="showWForm" class="col-12 offset-2">

      <table>
                <tr>
                  <td>
                    <input
                      class="form-control"
                      type="number"
                      name="withdrawalPoints"
                      v-validate="'required'"
                      :max="balance"
                      :class="{'input': true, 'input-danger': errors.has('withdrawalPoints') }"
                      v-model="withdrawalPoints"
                      placeholder="Withdrawal Points"
                    />
                    <span v-show="errors.has('withdrawalPoints')" class="text-danger">{{errors.first('withdrawalPoints')}}</span>
                  </td>

                  <td>
                    <select
                      v-validate="'required'"
                      name="withdrawalMethod"
                      @change="ON_METHOD_CHANGE"
                      v-model="withdrawalMethod"
                      class="form-control"
                      :class="{'input': true, 'input-danger': errors.has('withdrawalMethod') }"
                    >
                      <optgroup>
                        <template v-for="option in paymentMethods">
                          <option
                            :key="option.id"
                            v-bind:value="option.id"
                          >{{ option.number }} - {{option.type}}</option>
                        </template>
                      </optgroup>
                    </select>
                    <span v-show="errors.has('withdrawalMethod')" class="text-danger">{{errors.first('withdrawalMethod')}}</span>

                  </td>
                  <td>

                     <button
                      @click="CREATE_WITHDRAWAL"
                      class="btn btn-md btn-warning"
                    >Submit Withdrawal</button>
                  </td>
                </tr>
              </table>
        
            </div>
            <hr />
            <div class="col-sm-10 col-lg-8 offset-sm-2">
              <div class="text-left profile-details">

                       <table class="table table-condensed">
                  <thead>
                    <tr>
                      <th>Payment Method</th>
                      <th>Amount</th>
                      <th>Points Spent</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <template v-for="(item, index) in withdrawals">
                      <tr :key="index">
                        <td>{{item.payment_method.type}} - {{item.payment_method.number}}</td>
                        <td>{{item.amount}}</td>
                        <td>{{item.points_spent}}</td>
                        <td>
                         <span v-if="item.status === 'accepted'"
                          class="badge badge-success"
                        >Accepted</span>
                        <span
                          class="badge badge-warning"
                          v-if="item.status === 'pending'"
                        >Pending</span>
                        <span
                          class="badge badge-danger"
                          v-if="item.status === 'cancelled'"
                        >Cancelled</span>
                        </td>
                      </tr>
                    </template>
                  </tbody>
                </table>
                </form>
           
              </div>
            </div>
          </div>
          <div class="col-6 col-sm-8 offset-sm-2 p-sm-3 mt-5 h-100">
            <div class="title">
              <p>My Payment Methods</p>
              <button @click="showPMForm = !showPMForm" class="btn btn-warning btn-sm">+add</button>
            </div>

            <!-- EXIT -->
            <div v-if="showPMForm" class="col-12 offset-2">
              <table>
                <tr>
                  <td>
                    <select
                      v-validate="'required'"
                      name="type"
                      v-model="type"
                      class="form-control"
                      :class="{'input': true, 'input-danger': errors.has('type') }"
                    >
                      <optgroup>
                        <option>Paytm</option>
                        <option>Bank Acount</option>
                        <option>PayPal</option>
                      </optgroup>
                    </select>
                    <span v-show="errors.has('type')" class="text-danger">{{errors.first('type')}}</span>

                  </td>
                  <td>
                    <input
                      class="form-control"
                      type="text"
                      name="number"
                      v-validate="'required'"
                      :class="{'input': true, 'input-danger': errors.has('number') }"
                      v-model="number"
                      placeholder="Number"
                    />
                    <span v-show="errors.has('number')" class="text-danger">{{errors.first('number')}}</span>

                  </td>
                  <td>
                    <input
                      class="form-control input-group-sm"
                      type="text"
                      name="details"
                      v-validate="'required'"
                      :class="{'input': true, 'input-danger': errors.has('details') }"
                      v-model="details"
                      placeholder="Details"
                    />
                    <span v-show="errors.has('details')" class="text-danger">{{errors.first('details')}}</span>
                  </td>
                  <td>
                    <button
                      @click="CREATE_PAYMENT_METHOD"
                      class="btn btn-md btn-warning"
                    >Add Payment Method</button>
                  </td>
                </tr>
              </table>
              </form>
            </div>

            <hr />
            <div class="col-sm-10 col-lg-8 offset-sm-2">
              <div class="text-left profile-details">
                <table class="table table-condensed">
                  <thead>
                    <tr>
                      <th>Type</th>
                      <th>Number</th>
                      <th>Details</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <template v-for="(item, index) in paymentMethods">
                      <tr :key="index">
                        <td>{{item.type}}</td>
                        <td>{{item.number}}</td>
                        <td>{{item.details}}</td>
                        <td><button
                      @click="DELETE_PAYMENT_METHOD(item.id,index)"
                      class="btn btn-md btn-danger"
                    >Delete</button></td>
                      </tr>
                    </template>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import exitButton from "../utils/exit-button.vue";
import * as _ from "lodash";
import md5 from "md5";
import swal from 'sweetalert';

export default {
  data() {
    return {
      number: null,
      details: null,
      type: "Paytm",
      success: null,
      error: null,
      show_modal: false,
      paymentMethods: [],
      withdrawals: [],
      referral_url: "",
      updateId: null,
      balance: 0,
      withdrawalPoints: null,
      withdrawalMethod: null,
      showPMForm: false,
      showWForm: false
    };
  },

  components: {
    "exit-button": exitButton
  },

  created() {
    axios
      .get("/api/v1/get/payment-method")
      .then(res => {
        console.warn(res.data);
        this.paymentMethods = res.data;
      })
      .catch(err => {
        console.error(err);
      });

    axios
      .get("/api/v1/get/withdrawal/"+ this.$auth.getUserInfo("user_id"))
      .then(res => {
        console.warn(res.data);
        this.withdrawals = res.data;
      })
      .catch(err => {
        console.error(err);
      });
    axios
      .get("/api/v1/get/balance")
      .then(res => {
        console.warn(res.data);
        this.balance = res.data.balance;
      })
      .catch(err => {
        console.error(err);
      });
  },

  beforeRouteUpdate(to, from, next) {
    this.name = to.params.name;
    next();
  },

  methods: {
    ON_METHOD_CHANGE(value) {
      console.warn(value.target.value, this.withdrawalMethod);
    },
    CREATE_WITHDRAWAL() {

      axios.post("/api/v1/create/withdrawal", {
        points: this.withdrawalPoints,
        payment_method: this.withdrawalMethod
      }).then(res => {
        this.withdrawals.unshift(res.data);
      });
    },
    CREATE_PAYMENT_METHOD() {
      this.$validator.validateAll().then(result => {
        if (result) {
          axios
            .post("/api/v1/create/payment-method", {
              number: this.number,
              type: this.type,
              details: this.details
            })
            .then(
              response => {
                if (response.status === 201) {
                  console.log(response);
;
                  this.number = 0;
                  this.paymentMethods.unshift(response.data);
                  // this.paymentMethods = response.data;
                }
              },
              error => {
                this.error = error.response.data.message;
                this.success = null;
              }
            );
        }
      });
    },
    UPDATE_PAYMENT_METHOD() {
      this.$validator.validateAll().then(result => {
        if (result) {
          axios.get("/api/v1/update/payment-method/" + this.updateId, {}).then(
            response => {
              if (response.status === 200) {
                this.paymentMethods = response.data;
              }
            },
            error => {
              this.error = error.response.data.message;
              this.success = null;
            }
          );
        }
      });
    },
    DELETE_PAYMENT_METHOD(id, index) {
      axios.delete('/api/v1/delete/payment-method/'+id).then(res => {
        this.paymentMethods.splice(index, 1);
        swal('deleted');
      }).catch(err => {
        swal(err);
      })
    },
    hash(s) {
      return md5(s);
    },
    copyTextToClipboard() {
      var textArea = document.createElement("textarea");
      textArea.value = this.referral_url;
      document.body.appendChild(textArea);
      textArea.focus();
      textArea.select();
      swal("Copied to clipboard");

      try {
        var successful = document.execCommand("copy");
        var msg = successful ? "successful" : "unsuccessful";
        console.log("Fallback: Copying text command was " + msg);
      } catch (err) {
        console.error("Fallback: Oops, unable to copy", err);
      }

      document.body.removeChild(textArea);
    }
  }
};
</script>