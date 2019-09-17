<template>
    <div class="categories">
        <div class="spinner-load" v-if="spinner_loading">
            <Loader></Loader>
        </div>

        <!-- END spinner load -->
        <div class="k1_manage_table" v-if="!spinner_loading">
            <h5 class="title p-2">Categories</h5>

            <div class="m-2 p-2" id="manage-panel">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <router-link class="btn btn-md btn-warning" role="button" :to="{name: 'create-category'}">Create</router-link>
                    </li>
                </ul>
            </div>


            <div class="table-responsive mt-2" v-if="data.categories !== null">
                <div class="table table-hover">
                    <thead>
                    <th>Cover</th>
                    <th>Name</th>
                    <th>Section</th>
                    <th>Created date</th>
                    <th>Updated date</th>
                    <th>Control</th>
                    </thead>
                    <tbody>
                    <tr v-for="(item, index) in data.categories" :key="index">
                        <td><img :src="item.image" :alt="item.name" width="40"></td>
                        <td>{{item.name}}</td>
                        <td v-if="item.kind == 'movie'">Movies Page</td>
                        <td v-if="item.kind == 'videosong'">Video Songs Page</td>
                        <td v-if="item.kind == 'series'">TV Show Page</td>
                        <td v-if="item.kind == 'kids'">Kids Page</td>
                        <td v-if="item.kind == 'live'">Live TV Page</td>
                        <td>{{item.created_at}}</td>
                        <td>{{item.updated_at}}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <router-link class="btn btn-sm btn-warning btn-sm mr-2" role="buttton" :to="{ name:'edit-category', params: {id: item.id}}">
                                    Edit
                                </router-link>
                                <button class="btn btn-sm btn-danger btn-sm mr-2" @click="DELETE(item.id,index)" :id="item.id">
                                    Delete
                                </button>
                                <button class="btn btn-sm btn-danger btn-sm mr-2" @click="ACTIVE(item.id,index, 'active')" :id="item.id" v-if="! item.active">
                                    Active
                                </button>
                                <button class="btn btn-sm btn-danger btn-sm mr-2" @click="ACTIVE(item.id,index, 'active')" :id="item.id" v-if="item.active">
                                    Inactive
                                </button>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </div>
            </div>
            <div v-else class="text-center">
                <h4>No result were found</h4>
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
                kind: 'movie'
            };
        },
        components: {
            Loader
        },
        computed: mapState({
            data: state => state.categories.data,
            button_loading: state => state.categories.button_loading,
            spinner_loading: state => state.categories.spinner_loading
        }),
        created() {
            this.$store.dispatch("GET_ALL_CATEGORIES");
        },
        methods: {
            DELETE(id, key) {
                swal({
                    title: "Are you sure to delete ?",
                    icon: "warning",
                    text: "All videos and subtitles will removed!",
                    buttons: true,
                    dangerMode: true
                }).then(willDelete => {
                    if (willDelete) {
                        this.$store.dispatch("DELETE_CATEGORY", {
                            id,
                            key
                        });
                    }
                });
            },

            ACTIVE(id, key, type) {
                this.$store.dispatch("ACTIVE_CATEGORY", {id,type,key});
            }
        }
    };
</script>
