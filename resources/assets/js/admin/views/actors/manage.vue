<template>
  <div>

    <div class="k1_manage_table">

      <h5 class="title p-2">Actors</h5>
      <div class="my-2 p-3">

        <div class="col-12 row">
          <div class="col-6">
            <div class="group-btn">
              <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#CreateActorModal">
                Create new actor
              </button>
            </div>
          </div>
          <div class="col-6 pull-right">
            <div class="pull-right" id="search">
              <input class="form-control mr-sm-2" type="text" v-model="search_query" placeholder="Search" @keyup="SEARCH">
            </div>
          </div>
        </div>
        <hr>


       <div class="spinner-load" v-if="spinner_loading">
            <Loader></Loader>
       </div>

        <!-- END spinner load -->

          <div class="modal fade" id="CreateActorModal" tabindex="-1" role="dialog" aria-labelledby="CreateActorModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="CreateActorModalLabel">New actor</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <div class="create-actor">
                    <div class="form-group col-md-12">
                      <label for="name">Name</label>
                      <input type="text" class="form-control" id="name" name="name" v-validate="'required|max:60'" v-model="new_actor_name" placeholder="Name">
                      <span v-show="errors.has('name')" class="is-danger">{{ errors.first('name') }}</span>
                    </div>
                    <div class="form-group col-md-12">
                      <div class="image-upload">
                      <input type="file" id="photo" name="photo" v-validate="'required|image'"
                             @change="readImage('photo','posterFileImage')" class="inputfile">
                      <label id="photoLabel" for="photo">Choose a poster
                        <br>
                      </label>
                      <span v-show="errors.has('photo')"
                            class=" is-danger">{{ errors.first('photo')}}</span>
                      </div>

                    </div>
                    <div class="form-group col-md-12">
                       <img src="" id="posterFileImage" width="200" style="display: none;">
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button v-if="!button_loading" type="button" class="btn btn-sm btn-warning" @click="CREATE_ACTOR()">Create</button>
                  <button v-if="button_loading" class="btn btn-sm btn-warning" disabled>
                    <i id="btn-progress"></i> Loading</button>
                </div>
              </div>
            </div>
          </div>

          <!-- Modal -->

        <div v-if="!spinner_loading">

        <div class="m-2" v-if="data.actors !== null">



          <div class="row" v-if="data_search === null">
            <div class="col-8 col-sm-3 col-lg-2 mt-2 actor" v-for="(item,index) in data.actors.data" :key="index">
              <div class="actor-image">
                  <img :src="item.image" :alt="item.name" width="100%" v-if="item.cloud == 'local'">
                  <img :src="md_cast + item.image" :alt="item.name" width="100%" v-if="item.cloud == 'aws'">
                <div class="control">
                  <div class="row">
                    <i class="fa fa-pencil ml-2" aria-hidden="true" @click="SHOW_EDIT(index)"></i>
                    <i class="fa fa-trash ml-2" aria-hidden="true" @click="DELETE(item.id,index)"></i>
                  </div>
                </div>
                <div class="image-upload" v-if="index === show_key">
                  <img :src="item.image" :id="'preview_'+item.id" width="100%" class="img-rounded">
                  <input type="file" class="inputfile" :name="'image_'+item.id" :id="'image_'+item.id" v-validate="'image'" @change="READ_IMAGE(item.id)">
                  <label :for="'image_'+item.id">
                    <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                  </label>
                </div>
                <div class="details">
                  <input type="text" v-model="item.name" v-validate="'required|max:60'" v-if="index === show_key">
                  <button v-if="index === show_key && !button_loading" class="btn btn-sm btn-warning" @click="UPDATE(item.id,index)">Update</button>
                  <button v-if="button_loading === item.id" class="btn btn-sm btn-warning" disabled>
                    <i id="btn-progress"></i> Loading</button>
                </div>
              </div>
               <p v-if="index !== show_key" class="mt-2">{{item.name}}</p>
              <span v-show="errors.has(item.name)" class=" is-danger">{{ errors.first(item.name) }}</span>
              <span v-show="errors.has('image_'+item.id)" class=" is-danger">{{ errors.first('image_'+item.id) }}</span>
            </div>

          </div>


          <div class="row" v-else>
            <div class="col-6 col-sm-4 col-lg-3 col-xl-2 mt-2 actor" v-for="(item,index) in data_search.actors" :key="index">
              <div class="actor-image">
                  <img :src="item.image" :alt="item.name" width="100%" v-if="item.cloud == 'local'">
                  <img :src="md_cast + item.image" :alt="item.name" width="100%" v-if="item.cloud == 'aws'">
                <div class="control">
                  <div class="row">
                    <i class="fa fa-pencil ml-2" aria-hidden="true" @click="SHOW_EDIT(index)"></i>
                    <i class="fa fa-trash ml-2" aria-hidden="true" @click="DELETE(item.id,index)"></i>
                  </div>
                </div>
                <div class="image-upload" v-if="index === show_key">
                  <img :src="item.image" :id="'preview_'+item.id" width="100%" class="img-rounded">
                  <input type="file" class="inputfile" :name="'image_'+item.id" :id="'image_'+item.id" v-validate="'image'" @change="READ_IMAGE(item.id)">
                  <label :for="'image_'+item.id">
                    <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                  </label>
                </div>
                <div class="details">
                  <input type="text" v-model="item.name" v-validate="'required|max:60'" v-if="index === show_key">
                  <button v-if="index === show_key && !button_loading" class="btn btn-sm btn-warning" @click="UPDATE(item.id,index)">Update</button>
                  <button v-if="button_loading === item.id" class="btn btn-sm btn-warning" disabled>
                    <i id="btn-progress"></i> Loading</button>
                </div>
              </div>
              <p v-if="index !== show_key" class="mt-2">{{item.name}}</p>
              <span v-show="errors.has(item.name)" class=" is-danger">{{ errors.first(item.name) }}</span>
              <span v-show="errors.has('image_'+item.id)" class=" is-danger">{{ errors.first('image_'+item.id) }}</span>
            </div>

            <div v-if="data_search === null ">

              <div class="text-center">
                <h4>No result were found</h4>
              </div>

            </div>

          </div>


          <nav aria-label="pagination" class="m-4 p-1" v-if="data.actors !== null && data_search === null">
            <ul class="pagination ">
              <li class="page-item" id="end">
                <a class="page-link" @click="PAGINATION('/api/admin/get/actors')">Begin</a>
              </li>
              <li class="page-item" id="prev" :class="{disabled: data.actors.prev_page_url === null}">
                <a class="page-link" @click="PAGINATION(data.actors.prev_page_url)">Previous</a>
              </li>
              <li class="page-item">
                <a class="page-link">{{data.actors.current_page}}/{{data.actors.last_page}}</a>
              </li>
              <li class="page-item" id="next" :class="{disabled: data.actors.next_page_url === null}">
                <a class="page-link" @click="PAGINATION(data.actors.next_page_url)">Next</a>
              </li>
              <li class="page-item" id="end">
                <a class="page-link" @click="PAGINATION('/api/admin/get/actors?page='+data.actors.last_page)">End</a>
              </li>
            </ul>
          </nav>

          <!-- END Pagination  -->

        </div>
        <div v-else>

          <div class="text-center">
            <h4>No result were found</h4>
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
import Loader from "../components/loader";

export default {
  data() {
    return {
      new_actor_name: "",
      show_key: null,
      button_loading: false,
      search_query: ""
    };
  },

  components: {
    Loader
  },

  computed: mapState({
    data: state => state.actors.data,
    data_search: state => state.actors.data_search,
    spinner_loading: state => state.actors.spinner_loading
  }),

  created() {
    this.$store.commit("CLEAN_SEARCH_ACTORS");
    this.$store.dispatch("GET_ALL_ACTORS");
  },

  methods: {
    PAGINATION(path_url) {
      this.$store.dispatch("GET_ACTORS_PAGINATION", path_url);
    },

    SHOW_EDIT(key) {
      if (this.show_key === key) {
        this.show_key = false;
      } else {
        this.show_key = key;
      }
    },

    UPDATE(id, key) {
      this.button_loading = id;
      const formData = new FormData();
      const image = document.getElementById("image_" + id).files[0];
      if (image === undefined) {
        formData.append("id", id);
        formData.append("name", this.data.actors.data[key].name);
      } else {
        formData.append("id", id);
        formData.append("image", image);
        formData.append("name", this.data.actors.data[key].name);
      }
      axios.post("/api/admin/update/actors/", formData).then(response => {
        if (response.status === 200) {
          this.data.actors.data[key].image = response.data.image;
          this.data.actors.data[key].name = response.data.name;
          this.show_key = false;
          this.button_loading = false;
          alertify.logPosition("top right");
          alertify.success(response.data.message);
        }
      });
    },

    READ_IMAGE(id) {
      var img = document.getElementById("image_" + id);
      var tgt = img.target || window.event.srcElement,
        files = tgt.files;

      // FileReader support
      if (FileReader && files && files.length) {
        var fr = new FileReader();
        fr.onload = function() {
          var srcImage = document.getElementById("preview_" + id);
          srcImage.style.display = "block";
          srcImage.src = fr.result;
        };
        fr.readAsDataURL(files[0]);
      } else {
        // Not supported
        // fallback -- perhaps submit the input to an iframe and temporarily store
        // them on the server until the user's session ends.
      }
    },

    CREATE_ACTOR() {
      this.button_loading = true;
      const formData = new FormData();
      const photo = document.getElementById("photo").files[0];
      formData.append("photo", photo);
      formData.append("name", this.new_actor_name);
      this.$validator.validateAll().then(result => {
        axios.post("/api/admin/new/actor", formData).then(
          response => {
            if (response.data.status === "success") {
              alertify.logPosition("top right");
              alertify.success(response.data.message);
              if(this.data.actors === null ) {
                  this.data.actors = {data: []};
                  this.data.actors.data.push(response.data.actor);
              }else {
                  this.data.actors.data.push(response.data.actor);
              }
              this.button_loading = false;
            }
          },
          error => {
            this.button_loading = false;
          }
        );
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
          this.$store.dispatch("DELETE_ACTOR", {
            id,
            key
          });
        }
      });
    },

    SEARCH() {
      if (this.search_query !== "") {
        this.$store.dispatch("GET_ACTORS_SEARCH", this.search_query);
      } else {
        this.$store.commit("CLEAR_SEARCH");
      }
    },

    readImage(id, outImage) {
      var img = document.getElementById(id);
      var tgt = img.target || window.event.srcElement,
        files = tgt.files;

      // FileReader support
      if (FileReader && files && files.length) {
        var fr = new FileReader();
        fr.onload = function() {
          var srcImage = document.getElementById(outImage);
          srcImage.style.display = "block";
          srcImage.src = fr.result;
        };
        fr.readAsDataURL(files[0]);
      } else {
        // Not supported
        // fallback -- perhaps submit the input to an iframe and temporarily store
        // them on the server until the user's session ends.
      }
    }
  }
};
</script>
