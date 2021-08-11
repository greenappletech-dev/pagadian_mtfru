<template>
    <div class="main-container p-4">
        <!--LOADER AND MESSAGE BOX-->
        <div style="position: absolute; top: 0; left: 0; z-index: 1000; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.2);" v-if="loader">
            <div style="position: fixed; top: 450px; left: 55%; transform:translate(-50%, -70%)">
                <img src="public/loader/loader.gif" alt="loader">
            </div>
        </div>

        <div style="position: absolute; top: 0; left: 0; z-index: 1000; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.2);" v-if="err || suc">
            <div style="position: fixed; top: 450px; left: 55%; transform:translate(-50%, -70%)">
                <div style="width: 500px; background: #F2F4F4;" class="p-3">
                    <div class="message-header p-2 border-bottom d-flex justify-content-between" style="background: #F2F4F4;">
                        <h3 style="margin: 0; font-size: 15px; font-weight: bold" v-if="err">Error Message</h3>
                        <h3 style="margin: 0; font-size: 15px; font-weight: bold" v-if="suc">Success</h3>
                    </div>

                    <div class="message_box p-2 d-flex justify-content-between" style="position: relative" v-if="err">
                        <img style="width: 80px" class="mt-1 ml-1" src="public/image/icons/warning.png" alt="error">
                        <h1 style="font-size: 17px; position: absolute; top: 20px; left: 115px;">{{ err_msg }}</h1>
                    </div>

                    <div class="message_box p-2 d-flex justify-content-between" style="position: relative" v-if="suc">
                        <img style="width: 80px" class="mt-1 ml-1" src="public/image/icons/success.png" alt="error">
                        <h1 style="font-size: 17px; position: absolute; top: 20px; left: 115px;">{{ suc_msg }}</h1>
                    </div>

                    <div class="" style="position: absolute; bottom:20px; right:15px">
                        <button v-on:click="closeMessageBox" style="width: 100px;" class="btn btn-primary p-2">Ok</button>
                    </div>
                </div>
            </div>
        </div>

        <!--    ENDS HERE-->

        <div class="user-button d-flex justify-content-between pt-3 pb-3">
            <ul class="breadcrumb-1 pl-0 m-0">
                <li class="d-inline align-middle">MTFRU</li>
                <li class="d-inline align-middle mr-1 ml-1" style="font-size: 5px; color: #95a5a6;"><i class="fas fa-circle"></i></li>
                <li class="d-inline align-middle" style="color: #95a5a6">Users</li>
            </ul>
        </div>

        <div class="main-content">
            <h2 class="d-inline align-middle">Users</h2>
            <div class="row mt-3 mb-3">
                <div class="col-6">
                    <button v-on:click="openModalToCreateUser" class="d-inline align-middle p-2 pl-4 pr-4 rounded border-0" style="background: rgba(255, 120, 31, 1); color: #fff; font-size: 14px; box-shadow: 2px 2px 2px rgba(165, 165, 165, 1)">Add User</button>
                </div>
            </div>

            <!--   Table     -->
            <div class="main-content-table">
                <div class="row">
                    <div class="col-md-12">
                        <v-client-table
                            :data="tableData"
                            :columns="columns"
                            :options="options">
                            <span slot="actions" slot-scope="{row}">
                                <button v-on:click="openModalToUpdateUser(row.id)" class="btn btn-success mb-2"><i class="fas fa-edit mr-1"></i>Edit</button>
                                <button v-on:click="deleteRecord(row.id)" class="btn btn-danger mb-2"><i class="fas fa-trash mr-1"></i>Delete</button>
                            </span>
                        </v-client-table>
                    </div>
                </div>
            </div>
        </div>

        <!--        modal window-->
        <div class="modal fade" id="create-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">

                        <h5 class="modal-title" id="exampleModalLabel" v-if="print">Print Report</h5>

                        <div v-else>
                            <h5 class="modal-title" id="exampleModalLabel" v-if="adding">Add User</h5>
                            <h5 class="modal-title" id="exampleModalLabel" v-else>Edit User</h5>
                        </div>


                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <div class="text-danger" v-if="errors.length > 0">
                            <ul>
                                <li v-for="error in errors">{{error[0]}}</li>
                            </ul>
                        </div>

                        <div class="form-group" v-if="print">
                            <label for="paper-size">Paper Size</label>
                            <select v-model="paperSize" id="paper-size" class="form-control">
                                <option>Letter</option>
                                <option>Legal</option>
                                <option>A4</option>
                            </select>

                            <label for="paper-orientation">Orientation</label>
                            <select v-model="paperOrientation" id="paper-orientation" class="form-control">
                                <option>Portrait</option>
                                <option>Landscape</option>
                            </select>
                        </div>

                        <div class="form-group" v-else>
                            <label for="username">Username</label>
                            <input v-model = "user.name" type="text" class="form-control" id="username">

                            <label for="email">Email Address</label>
                            <input v-model = "user.email" type="email" class="form-control" id="email">

                            <div v-if="adding === true">
                                <label for="userpass">Password</label>
                                <input v-model = "user.password" type="password" class="form-control" id="userpass">

                                <label for="password_confirmation">Re-type Password</label>
                                <input v-model = "user.password_confirmation" type="password" class="form-control" id="password_confirmation">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button v-if="adding" @click="createRecord" type="button" class="rounded pl-3 pr-3 pt-2 pb-2 border-0" style="width: 100px; font-size: 14px; background: #1abc9c; color: #fff;">Create</button>
                        <button v-else @click="updateRecord" type="button" class="rounded pl-3 pr-3 pt-2 pb-2 border-0" style="width: 100px; font-size: 14px; background: #1abc9c; color: #fff;">Update</button>
                        <button type="button" data-dismiss="modal" class="rounded pl-3 pr-3 pt-2 pb-2 border-0" style="width: 100px; font-size: 14px; background: #e74c3c; color: #fff;">Discard</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            columns: ['id', 'name', 'email', 'created_at', 'updated_at', 'actions'],
            tableData: [],
            options: {
                headings: {
                    id: 'ID',
                    name: 'Name',
                    created_at: 'Created At',
                    updated_at: 'Updated At',
                    action: 'Action',
                },
                sortable: ['id', 'name'],
                filterable: ['id', 'name'],
                templates: {
                    hol_date: function(h, row) {
                        return row.hol_date !== null ? moment(row.hol_date).format('YYYY-MM-DD') : null;
                    },
                    created_at: function(h, row) {
                        return row.created_at !== null ? moment(row.created_at).format('YYYY-MM-DD hh:mm:ss') : null;
                    },
                    updated_at: function(h, row) {
                        return row.updated_at !== null ? moment(row.updated_at).format('YYYY-MM-DD hh:mm:ss') : null;
                    }
                },
                texts : {
                    filter: 'Search:',
                }
            },

            err_msg: '',
            suc_msg: '',
            err: false,
            suc: false,
            loader: false,

            url: './user/export',
            url_pdf: './user/pdf',
            errors: [],
            user: [],
            adding: false,
            print: false,
            paperSize: 'Letter',
            paperOrientation: 'Portrait',
        }
    },
    methods: {
        errorHandler(errors){
            const error_handler = [];

            $.each(errors, function(key, value) {
                error_handler.push(value);
            });

            return error_handler;
        },

        closeMessageBox() {
            this.err = false;
            this.suc = false;
            this.err_msg = '';
            this.suc_msg = '';
            this.user = [];
            this.getRecord();
        },

        getRecord() {

            this.user = [];
            this.errors = [];

            axios.get('users/getrecords')
            .then(response => {
                this.tableData = response.data.users;
            })
            .catch(
                error => {
                    this.err_msg = error.data.response.err_msg;
                    this.err = true;
            })
            .finally(
                () => this.loader = false
             );
        },

        openModalToCreateUser() {
            $('#create-modal').modal('show');
            this.user = [];
            this.adding = true;
        },

        createRecord() {

            this.loader = true;

            axios.post('users/store',
                {
                    name: this.user.name,
                    email: this.user.email,
                    password: this.user.password,
                    confirmation: this.user.password_confirmation
                })
                .then(
                    response => {
                        $('#create-modal').modal('hide');
                        this.suc_msg = response.data.message;
                        this.suc = true;
                })
                .catch(
                    error => {
                        this.errors = [];

                        if(error.response.data.errors) {
                            this.errors = this.errorHandler(error.response.data.errors);
                            this.loader = false;
                            return;
                        }

                        $('#create-modal').modal('hide');
                        this.err_msg = error.data.response.err_msg;
                        this.err_msg = true;
                        this.loader = false;

                })
                .finally(
                    () => this.loader = false
                );
        },

        openModalToUpdateUser(id) {
            axios.get('users/edit/' + id)
                .then(response => {
                    this.errors = [];
                    this.adding = false;
                    this.user.id = response.data.user.id;
                    this.user.name = response.data.user.name;
                    this.user.email = response.data.user.email;
                    $('#create-modal').modal('show');
                })
                .catch(error => {
                    this.err_msg = error.data.response.err_msg;
                    this.err_msg = true;
                })
        },

        updateRecord() {
            this.loader = true;
            axios.patch('users/update/' + this.user.id, {
                id: this.user.id,
                name: this.user.name,
                email: this.user.email
            })
            .then(
                response => {
                    $("#create-modal").modal("hide");
                    this.suc = true;
                    this.suc_msg = response.data.message;
            })
            .catch(
                error => {
                    this.errors = this.errorHandler(error.response.data.errors);
                    this.loader = false;
            })
            .finally(
                () => this.loader = false
            )
        },

        deleteRecord(id) {

            let confirmBox = confirm("Do you really want to delete this record?");
            if(confirmBox == true) {

                this.loader = true;

                axios.get('users/destroy/' + id)
                    .then(
                        response => {
                            this.suc = true;
                            this.suc_msg = response.data.message;
                    })
                    .catch(
                        error => {
                            this.err = true;
                            this.err_msg = response.data.err_msg;
                    })

                    .finally(
                        () => this.loader = false
                    )
            }

        }
    },

    mounted() {
        this.getRecord();
    }

}
</script>
