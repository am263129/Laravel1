<template>
    <div>

        <div class="settings">

        <div class="row">


            <div class="spinner-load" v-if="loading">
                    <div class="hidden-md-up phone">
                        <div id="main">

                            <span class="spinner"></span>

                        </div>
                    </div>

                    <div class="hidden-sm-down other">
                        <div id="main">

                            <span class="spinner"></span>

                        </div>
                    </div>
                </div>

            <div class="exit-icon" @click="$Helper.home()">
                <exit-button></exit-button>
            </div>

            <!-- EXIT -->

            <!-- Modal -->
                <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="TicketModalLabel" id="TicketModal"
                     aria-hidden="true" style="z-index:100000000;">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="TicketModalLabel">{{$t('setting.submit_request')}}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                  <input type="text" name="subject" v-validate="'required|max:50'" :class="{'input': true, 'text-danger': errors.has('subject') }" class="form-control" v-model="subject" :placeholder="$t('setting.support_tell')">
                                  <span v-show="errors.has('subject')" class="text-danger">{{ errors.first('subject') }}</span>
                                </div>

                                <div class="form-group">
                                    <textarea name="details"  v-validate="'required|max:2000'" cols="50" rows="10" :class="{'input': true, 'text-danger': errors.has('details') }" class="form-control" v-model="details" :placeholder="$t('setting.support_desc')"></textarea>
                                    <span v-show="errors.has('details')" class="text-danger">{{ errors.first('details') }}</span>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-warning close-btn" data-dismiss="modal">{{$t('setting.cancel')}}
                                </button>
                                <button type="button" class="btn btn-warning" @click="SUBMIT_REQUEST">{{$t('setting.submit')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            <div class="col-12 col-sm-8 col-lg-8 offset-sm-2 p-sm-3 mt-5 profile-edit h-100">
        

                 <div class="row">
                   <div class="col-8 text-left">
                    <h3>{{$t('setting.support')}}</h3>
                   </div>

                  <div class="col-4 text-right">
                    <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#TicketModal">{{$t('setting.submit_request')}}</button>
                  </div>
                </div> 

                <hr>

             <table class="table table-inverse" v-if="request_data.data !== null">
                    <thead>
                        <tr>
                            <th>{{$t('setting.status')}}</th>
                            <th>{{$t('setting.subject')}}</th>
                            <th>{{$t('setting.date')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item,key) in request_data.data" :key="key" @click="GET_REQUEST(item.id)">
                            <td v-if="item.status === 1 || item.status"> <span class="support-request re-open">{{$t('setting.open')}}</span></td>
                            <td v-else><span class="support-request re-close">{{$t('setting.close')}}</span></td>
                            <td>{{item.subject}}</td>
                            <td>{{item.created_at}}</td>
                        </tr>

                    </tbody>
                </table>



            <div v-else>
                <div class="mt-5 text-center notfound">

                    <h4>
                        <notfound></notfound>

                        <strong>{{$t('home.sorry_no_result')}}</strong>

                    </h4>
                </div>
            </div>

            </div>
        </div>

        </div>
    </div>
</template>

<script>
import exitButton from "../utils/exit-button.vue";
    import {
        mapState
    } from "vuex";
import notfound from "../utils/notfound"

export default {
  data() {
    return {
      subject: "",
      details: ""
    };
  },

  components: {
    "exit-button": exitButton,
      notfound
  },

  computed: mapState({
    loading: state => state.auth.loading,
    request_data: state => state.auth.items
  }),

  mounted() {
    this.$store.dispatch("GET_ALL_SUPPORT_REQUEST");
  },

  methods: {
    SUBMIT_REQUEST() {
      this.$validator.validateAll().then(result => {
        if (result) {
          this.$store.dispatch("SUBMIT_SUPPORT_REQUEST", {
            details: this.details,
            subject: this.subject
          });
        }
      });
    },

    GET_REQUEST(id) {
      this.$router.push({name: 'support-request', params: {id: id}})
    }
  }
};
</script>