<template>
  <div>
    <transition name="slide-fade">
    <div class="col-12 col-sm-6 col-md-8 col-lg-5 col-xl-4 collection-box" v-if="show" v-show="showBoxAnimation">
          <div class="collection-content p-md-0 p-lg-0">
            <div class="row">

              <div class="exit-icon" @click="CANCEL">
                <img src="/themes/default/img/exit-icon.svg" alt="exit icon">
              </div>

              <div class="col-12 image">
                <img :src="poster" :alt="name" width="100%">
                <div class="gradient"></div>

                <h3 class="title">
                  <strong>{{name}}</strong>
                </h3>

              </div>

              <!-- END Poster -->

              <div class="col-12 control">

                <div class="col-12">

                  <div class="collection-list" v-if="collections.length > 0">
                    <label class="text-muted ml-1">My Collection</label>
                    <br>

                    <button class="btn btn-outline-secondary ml-1" :class="{active: already_collection === item.id}" v-for="(item, index) in collections"
                      :key="index" @click="already_collection = item.id">{{item.name}} </button>

                  </div>
                </div>

                <div class="col-12 mt-4">


                  <input class="form-control" type="text" placeholder="New Collection" v-model="new_collection">

                  <p class="text-danger">{{message}}</p>

                </div>



                <div class="footer col-12 mb-3 mt-2 mt-sm-4 mt-md-4cancel-save">
                  <button v-if="!disable_button" type="button" class="btn btn-outline-secondary ml-1 " disabled>Save</button>
                  <button v-else type="button" class="btn btn-outline-secondary ml-1" @click="SAVE">Save</button>
                </div>

              </div>

            </div>
          </div>
    </div>
    </transition>
  </div>
</template>

<script>
  import {
    mapState
  } from "vuex";

  export default {
    props: ["id", "poster", "name", "type", "index"],

    data() {
      return {
        show: null,
        new_collection: null,
        already_collection: null,
        already_name: null,
        message: null,
        disable_button: false,
          showBoxAnimation: false
      };
    },

    watch: {
      already_collection() {
        if (this.already_collection !== null && this.already_collection !== "") {
          this.disable_button = true;
        } else {
          this.disable_button = false;

        }
      },
      new_collection() {
        if (this.new_collection !== null && this.new_collection !== "") {
          this.disable_button = true;
        } else {
          if (this.already_collection === null || this.already_collection === "") {
            this.disable_button = false;
          }
        }
      },
      id(val) {
        if (val !== null) {
          this.show = true;
          this.$store.dispatch("GET_ALL_COLLECTION");
        } else {
          this.show = false;
        }
      }
    },

    mounted() {
      this.new_collection = null;
      this.already_collection = null;
      this.already_name = null;

      setTimeout(() => {
          this.showBoxAnimation = true;
      }, 1000);
    },

    computed: mapState({
      collections: state => state.collections.collections
    }),

    methods: {

      CANCEL() {
        this.$emit("hideModalCollectionCancel", null);
      },

      SAVE() {
        this.$store.dispatch("ADD_NEW_TO_COLLECTION", {
          id: this.id,
          already_collection: this.already_collection,
          new_collection: this.new_collection,
          type: this.type
        });
        this.$emit("hideModalCollectionSave", null);
        this.already_collection = null;

      },

    }
  };
</script>