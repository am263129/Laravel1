<template>
<div>
    <div class="spinner-load" v-if="spinner_loading">
        <Loader></Loader>
    </div>
    <div class="k1_manage_table file-manager" v-if="!spinner_loading">

        <!-- END spinner load -->

        <h5 class="title p-2">File Manager</h5>

        <div class="col-12 row">
            <div class="col-12 col-md-6">
                <div class="group-btn">
                    <button type="button" class="btn btn-sm btn-warning" @click="show_new_folder = true">Create New Folder</button>
                    <div class="upload-file btn btn-sm btn-warning">
                        Upload File
                        <input type="file" id="file-upload" name="file-upload" @change="UPLOAD_FILES" multiple/>		</div>
                    </div>
                </div>

                <div class="col-12 col-md-6 m-md-3" v-if="show_new_folder">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Folder Name" v-model="folder_name">
        </div>
                        <div class="group-btn">
                            <button type="button" class="btn btn-sm btn-success" @click="CREATE_NEW_FOLDER">Submit</button>
                            <button type="button" class="btn btn-sm btn-danger" @click="show_new_folder = false; folder_name = '' ">Cancel</button>
                        </div>
                    </div>

                </div>

                <div class="progress mt-2" style="height:15px" v-if="show_upload_progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" :style="{width: percentFileUpload + '%' }" aria-valuenow="0" :aria-valuemin="percentFileUpload" aria-valuemax="100">{{percentFileUpload}}%</div>
                </div>
                <hr>

                <div class="modal fade" id="GetFileInfoModal" tabindex="-1" role="dialog" aria-labelledby="GetFileInfoLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="GetFileInfoLabel">File Information</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
                            </div>
                            <div class="modal-body" v-if="info_data !== null">

                                <div class="col-12 m-3" v-if="info_data.m_id !== null && info_data.m_id !== undefined">
                                    <h4>{{info_data.m_name}}</h4>
                                    <p>{{info_data.m_desc}}</p>
                                    <hr>
                                    <h6 class="mt-3">Images</h6>
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <img :src="'/storage/posters/300_' + info_data.m_poster" width="100%">
                </div>
                                            <div class="col-12 col-md-6">
                                                <img :src="'/storage/backdrops/300_' + info_data.m_backdrop" width="100%">
                </div>
                                            </div>
                                            <h6 class="mt-3">Videos</h6>
                                            <hr>
                                            <video width="100%" controls>
                <source :src="info_data.video_url" type="video/mp4"> Your browser does not support the video tag.
              </video>
                                        </div>

                                        <div class="col-12 m-3" v-if="info_data.t_id !== null && info_data.t_id !== undefined">
                                            <h4>{{info_data.t_name}}</h4>
                                            <p>{{info_data.t_desc}}</p>
                                            <hr>
                                            <h6 class="mt-3">Images</h6>
                                            <div class="row">
                                                <div class="col-12 col-md-6">
                                                    <img :src="'/storage/posters/300_' + info_data.t_poster" width="100%">
                </div>
                                                    <div class="col-12 col-md-6">
                                                        <img :src="'/storage/backdrops/300_' + info_data.t_backdrop" width="100%">
                </div>
                                                    </div>
                                                    <h6 class="mt-3">Videos</h6>
                                                    <hr>
                                                    <video width="100%" controls>
                <source :src="info_data.video_url" type="video/mp4"> Your browser does not support the video tag.
              </video>
                                                </div>

                                                <div class="col-12 m-3" v-if="info_data.id !== null && info_data.id !== undefined">
                                                    <h4>{{info_data.name}}</h4>
                                                    <hr>
                                                    <h6 class="mt-3">Images</h6>
                                                    <div class="row">
                                                        <div class="col-12 col-md-6">
                                                            <img :src="'/storage/posters/' + info_data.image" width="100%">
                </div>
                                                        </div>
                                                        <div clas="col-12" v-if="info_data.video_url">
                                                            <h6 class="mt-3">Videos</h6>
                                                            <hr>
                                                            <video width="100%" controls>
                  <source :src="info_data.video_url" type="application/x-mpegURL"> Your browser does not support the video tag.
                </video>

                                                        </div>
                                                    </div>

                                                    <div class="col-12 m-3" v-if="info_data.c_id!== null && info_data.c_id !== undefined">
                                                        <h4>{{info_data.c_name}}</h4>
                                                        <hr>
                                                        <h6 class="mt-3">Images</h6>
                                                        <div class="row">
                                                            <div class="col-12 col-md-6">
                                                                <img :src="info_data.c_image" width="100%">
                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <h6 class="link-dir p-2">
                                            <span v-for="(item, index) in link" :key="index" @click="GO_TO_DIR(item)">{{item}}</span>
                                        </h6>
                                        <hr>
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-4 col-md-2 text-center folder" v-for="(item, index) in data.folder" :key="index">
                                                    <i class="fa fa-folder fa-5x" @click="GET_INSIDE_FOLDER(item.path, item.name)"></i>
                                                    <br>
                                                    <small v-if="show_rename_folder !== index">{{item.name}}</small>
                                                    <input type="text" class="form-control form-control-sm mt-2" placeholder="Folder Name" v-if="show_rename_folder === index"
            v-model="rename_folder">

                                                    <div class="control" v-if="show_rename_folder !== index">
                                                        <i class="fa fa-trash" @click="DELETE_FOLDER(item.path,index)"></i>
                                                        <i class="fa fa-edit" @click="show_rename_folder = index"></i>
                                                    </div>

                                                    <div class="group-btn mt-2" v-if="show_rename_folder === index">
                                                        <button type="button" class="btn btn-sm btn-outline-success" @click="RENAME_FOLDER(item.path, index)">Submit</button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger" @click="show_rename_folder = false; rename_folder = '' ">Cancel</button>
                                                    </div>
                                                </div>

                                                <!-- END Folder -->

                                                <div class="col-12 text-center folder" v-if="data.files !== null">
                                                    <table class="table table-sm">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">Name</th>
                                                                <th scope="col">Size</th>
                                                                <th scope="col">Date</th>
                                                                <th scope="col">Control</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-for="(item, index) in data.files" :key="index">
                                                                <td v-if="show_rename_file !== index && item.mime === 'jpg' || item.mime === 'jpeg' || item.mime === 'png'" @click="show_rename_file = index">
                                                                    <i class="fa fa-image fa-1x"></i>
                                                                    {{item.name}}
                                                                </td>
                                                                <td v-if="show_rename_file !== index && item.mime === 'mp4' || item.mime === 'm3u8'" @click="show_rename_file = index">
                                                                    <i class="fa fa-video fa-1x"></i>
                                                                    {{item.name}}
                                                                </td>
                                                                <td v-if="show_rename_file === index">
                                                                    <form class="form-inline">
                                                                        <div class="form-group mb-2">
                                                                            <input type="text" class="form-control form-control-sm mt-2" placeholder="Folder Name" v-model="rename_file">
                    </div>

                                                                            <div class="form-group ml-2">
                                                                                <button type="button" class="btn btn-sm btn-outline-success" @click="RENAME_FILE(item.path, index)">Submit</button>
                                                                            </div>
                                                                    </form>
                                                                </td>
                                                                <td>{{item.size / 1000 }} KB</td>
                                                                <td>{{item.date}}</td>
                                                                <td>
                                                                    <span class="control-btn" @click="DELETE_FOLDER_A_FILE(item.path, index, 'file')">Delete</span>
                                                                    <span class="control-btn">
                    <a :href="'/storage/' + item.path" style="color: #03A9F4;">Download</a>
                  </span>
                                                                    <span class="control-btn" @click="GET_FILE_INFO(item.path, item.name)" data-toggle="modal" data-target="#GetFileInfoModal">Info</span>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
</div>
</template>

<script>
const alertify = require("alertify.js");

export default {
    name: "file-manager",

    data() {
        return {
            data: [],
            link: ["/"],
            info_data: null,
            // Create folder
            show_new_folder: false,
            folder_name: "",
            // Rename Folder
            show_rename_folder: false,
            rename_folder: "",
            // Rename File
            show_rename_file: false,
            rename_file: "",
            // Upload Files
            show_upload_progress: false,
            percentFileUpload: 0,
            spinner_loading: false,
        };
    },

    mounted() {
        this.spinner_loading = true;
        axios
            .post("/api/admin/get/filemanager/folder", {
                link: '/'
            })
            .then(response => {
                if (response.status === 200) {
                    this.data = response.data.data;                    
                }
            }).finally(()=>{
                this.spinner_loading = false;
            });
    },

    methods: {
        GET_INSIDE_FOLDER(link, name) {
            this.spinner_loading = true;
            axios
                .post("/api/admin/get/filemanager/folder", {
                    link: link
                })
                .then(response => {
                    if (response.status === 200) {
                        this.data = response.data.data;
                        this.link.push(name);
                    }
                }).finally(()=>{
                    this.spinner_loading = false;
                });
        },

        GO_TO_DIR(link) {
            
            if (link !== this.link.slice(-1).pop()) {
                this.spinner_loading = true;
                this.link.length = this.link.indexOf(link);
                axios
                    .post("/api/admin/get/filemanager/folder", {
                        link: link
                    })
                    .then(response => {
                        if (response.status === 200) {
                            this.data = response.data.data;
                            if (link === "/") this.link = ["/"];
                            else this.link.push(link);                            
                        }
                    }).finally(()=>{
                        this.spinner_loading = false;
                    });
            }
        },

        DELETE_FOLDER_A_FILE(path, index, type) {
            axios
                .post("/api/admin/delete/filemanager", {
                    path: path,
                    type: type
                })
                .then(
                    response => {
                        if (response.status === 200) {
                            alertify.logPosition("top right");
                            alertify.success(response.data.message);
                            this.data.files.splice(index, 1);
                        }
                    },
                    error => {
                        alertify.logPosition("top right");
                        alertify.error("Error in delete");
                    }
                );
        },

        DOWNLOAD_FILE(path) {
            axios
                .post("/api/admin/download/filemanager", {
                    path: path
                })
                .then(
                    response => {
                        if (response.status === 200) {
                            alertify.logPosition("top right");
                            alertify.success(response.data.message);
                        }
                    },
                    error => {
                        alertify.logPosition("top right");
                        alertify.error("Error in delete");
                    }
                );
        },

        GET_FILE_INFO(path, name) {
            axios
                .post("/api/admin/get/filemanager/info", {
                    path: path,
                    name: name
                })
                .then(
                    response => {
                        if (response.status === 200) {
                            this.info_data = [];
                            this.info_data = response.data.data;
                        }
                    },
                    error => {
                        alertify.logPosition("top right");
                        alertify.error(error.response.data.message);
                    }
                );
        },

        CREATE_NEW_FOLDER() {
            let path = this.link.join("") + "/" + this.folder_name;
            axios
                .post("/api/admin/create/filemanager/folder", {
                    path: path
                })
                .then(
                    response => {
                        if (response.status === 200) {
                            alertify.logPosition("top right");
                            alertify.success(response.data.message);
                            if (this.data.folder === null) this.data.folder = [];
                            this.data.folder.push({
                                'path': path,
                                'name': path.substring(1)
                            });
                            this.show_new_folder = false;
                        }
                    },
                    error => {
                        alertify.logPosition("top right");
                        alertify.error(error.response.data.message);
                    }
                );
        },

        DELETE_FOLDER(path, index) {
            axios
                .post("/api/admin/delete/filemanager/folder", {
                    path: path
                })
                .then(
                    response => {
                        if (response.status === 200) {
                            alertify.logPosition("top right");
                            alertify.success(response.data.message);
                            this.data.folder.splice(index, 1);
                        }
                    },
                    error => {
                        alertify.logPosition("top right");
                        alertify.error(error.response.data.message);
                    }
                );
        },

        RENAME_FOLDER(old_name, index) {
            let new_name = this.link.join("") + "/" + this.rename_folder;
            axios
                .post("/api/admin/rename/filemanager/any", {
                    old_name: old_name,
                    new_name: new_name
                })
                .then(
                    response => {
                        if (response.status === 200) {
                            alertify.logPosition("top right");
                            alertify.success(response.data.message);
                            this.data.folder[index] = this.rename_folder;
                            this.show_rename_folder = false;
                        }
                    },
                    error => {
                        alertify.logPosition("top right");
                        alertify.error(error.response.data.message);
                    }
                );
        },

        RENAME_FILE(old_name, index) {
            let new_name = this.link.join("") + "/" + this.rename_file;
            axios
                .post("/api/admin/rename/filemanager/any", {
                    old_name: old_name,
                    new_name: new_name
                })
                .then(
                    response => {
                        if (response.status === 200) {
                            alertify.logPosition("top right");
                            alertify.success(response.data.message);
                            this.data.files[index].name = this.rename_file;
                            this.show_rename_file = false;
                        }
                    },
                    error => {
                        alertify.logPosition("top right");
                        alertify.error(error.response.data.message);
                    }
                );
        },

        UPLOAD_FILES() {
            const formData = new FormData();
            const files = document.getElementById("file-upload");
            for (let i = 0; i < files.files.length; i++) {
                formData.append("files[]", files.files[i]);
            }
            if (this.link.join("") === "/") {
                formData.append("path", "/");
            } else {
                formData.append("path", this.link.join(""));
            }
            // Progress
            this.show_upload_progress = true;
            const progress = {
                headers: {
                    "content-type": "multipart/form-data"
                },
                onUploadProgress: progressEvent => {
                    this.percentFileUpload = Math.round(
                        progressEvent.loaded * 100.0 / progressEvent.total
                    );
                }
            };
            axios
                .post("/api/admin/upload/filemanager/files", formData, progress)
                .then(
                    response => {
                        if (response.status === 200) {
                            alertify.logPosition("top right");
                            alertify.success(response.data.message);
                            this.show_upload_progress = false;
                            this.GET_INSIDE_FOLDER(this.link.join(''));
                        }
                    },
                    error => {
                        this.show_upload_progress = false;
                    }
                );
        }
    }
};
</script>
