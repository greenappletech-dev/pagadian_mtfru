<template>
    <div class="main-container p-4">
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

        <div class="user-button d-flex justify-content-between pt-3 pb-3">
            <ul class="breadcrumb-1 pl-0 m-0">
                <li class="d-inline align-middle" style="font-size: 12px">FVR DATA</li>
                <li class="d-inline align-middle mr-1 ml-1" style="font-size: 5px; color: #95a5a6;"><i class="fas fa-circle"></i></li>
                <li class="d-inline align-middle" style="color: #95a5a6; font-size: 12px">Boat Captain</li>
            </ul>
        </div>

        <div class="main-content">
            <h2 class="d-inline align-middle">Boat Captain</h2>
            <div class="row mt-3 mb-3">
                <div class="col-6">
                    <button v-on:click="openModalToCreate" class="d-inline align-middle p-2 pl-4 pr-4 rounded border-0" style="background: rgba(255, 120, 31, 1); color: #fff; font-size: 14px; box-shadow: 2px 2px 2px rgba(165, 165, 165, 1)">Add New Captain</button>
                </div>

                <div class="col-6 text-right">
                    <a class="p-2 pl-4 pr-4 rounded" style="background: #1abc9c; font-size: 14px; box-shadow: 2px 2px 2px rgba(165, 165, 165, 1); color: #fff; text-decoration: none;" v-on:click="exportExcel">Export to Excel</a>
                    <!--                    <a class="p-2 pl-4 pr-4 rounded" style="background: #1abc9c; font-size: 14px; box-shadow: 2px 2px 2px rgba(165, 165, 165, 1); color: #fff; text-decoration: none;" v-on:click="pdfModal">Print to PDF</a>-->
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
                                <button v-on:click="openModalToUpload(row.id)" class="btn btn-info mb-2"><i class="fas fa-upload mr-1"></i>Upload Image</button>
                                <button v-on:click="openModalToEdit(row.id)" class="btn btn-success mb-2"><i class="fas fa-edit mr-1"></i>Edit</button>
                                <button v-on:click="destroyRecord(row.id)" class="btn btn-danger mb-2"><i class="fas fa-trash mr-1"></i>Delete</button>
                            </span>
                        </v-client-table>
                    </div>
                </div>
            </div>
        </div>

        <!--        modal window    -->

        <div class="modal fade" id="create-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">

                        <h5 class="modal-title" id="exampleModalLabel" v-if="adding">Add Captain</h5>
                        <h5 class="modal-title" id="exampleModalLabel" v-else>Edit Captain</h5>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="max-height: 500px; overflow: auto;">
                        <div class="text-danger" v-if="errors.length > 0">
                            <ul>
                                <li v-for="error in errors">{{ error[0] }}</li>
                            </ul>
                        </div>

                        <div class="form-group" v-if="print">
                            <label for="paper-size">Paper Size</label>
                            <select id="paper-size" class="form-control">
                                <option>Letter</option>
                                <option>Legal</option>
                                <option>A4</option>
                            </select>

                            <label for="paper-orientation">Orientation</label>
                            <select id="paper-orientation" class="form-control">
                                <option>Portrait</option>
                                <option>Landscape</option>
                            </select>
                        </div>

                        <div class="form-group" v-else>

                            <label for="find">Find Body Number</label>
                            <div class="d-flex justify-content-between" style="position: relative;">
                                <input type="text" id="find" v-model="searchedValue" class="form-control pr-4" placeholder="Search Name, Business Name">
                                <button @click="find" class="pl-3 pr-3 border-0 rounded-right" style="position: absolute; top: 0; right: 0; height: 100%; background: rgba(206, 206, 206, 1)"><i class="fas fa-search"></i></button>
                            </div>

                            <div class="card mt-2" id="banca_info">
                                <div class="card-header m-0">
                                    <h3 style="font-size: 15px; font-weight: bold; margin: 0;">Banca Information</h3>
                                </div>

                                <div class="card-body" style="padding-top: 5px; padding-bottom: 5px;">
                                    <table style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th style="font-weight: normal; font-size: 14px">Operator</th>
                                                <th style="font-weight: normal; font-size: 14px">{{ operatorValue }}</th>
                                            </tr>
                                            <tr>
                                                <th style="font-weight: normal; font-size: 14px">Body Number</th>
                                                <th style="font-weight: normal; font-size: 14px">{{ bodyNumberValue }}</th>
                                            </tr>
                                            <tr>
                                                <th style="font-weight: normal; font-size: 14px">Boat Name</th>
                                                <th style="font-weight: normal; font-size: 14px">{{ boatNameValue }}</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>

                            <label for="last_name">Last Name</label>
                            <input type="text" style="text-transform: uppercase" v-model="lastNameValue" id="last_name" class="form-control">

                            <label for="first_name">First Name</label>
                            <input type="text" style="text-transform: uppercase" v-model="firstNameValue" id="first_name" class="form-control">

                            <label for="middle_name">Middle Name</label>
                            <input type="text" style="text-transform: uppercase" v-model="middleNameValue" id="middle_name" class="form-control">

                            <label for="license_no">License Number</label>
                            <input type="text" style="text-transform: uppercase" v-model="licenseNumberValue" id="license_no" class="form-control">

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button v-if="print" @click="pdfPrint" type="button" class="rounded pl-3 pr-3 pt-2 pb-2 border-0" style="width: 100px; font-size: 14px; background: #1abc9c; color: #fff;">Print</button>
                        <div v-else>
                            <button v-if="adding" @click="storeRecord" type="button" class="rounded pl-3 pr-3 pt-2 pb-2 border-0" style="width: 100px; font-size: 14px; background: #1abc9c; color: #fff;">Create</button>
                            <button v-else @click="updateRecord" type="button" class="rounded pl-3 pr-3 pt-2 pb-2 border-0" style="width: 100px; font-size: 14px; background: #1abc9c; color: #fff;">Update</button>
                        </div>
                        <button type="button" data-dismiss="modal" class="rounded pl-3 pr-3 pt-2 pb-2 border-0" style="width: 100px; font-size: 14px; background: #e74c3c; color: #fff;">Discard</button>
                    </div>
                </div>
            </div>
        </div>



        <!--    upload image modal ---->

        <form method="post" enctype="multipart/form-data" @submit="uploadImageFile">
            <div class="modal fade" id="upload-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Upload Image</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <input v-on:change="onChangeUpload" type="file" class="border rounded" accept="image/png, image/gif, image/jpeg">
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="rounded pl-3 pr-3 pt-2 pb-2 border-0" style="width: 100px; font-size: 14px; background: #1abc9c; color: #fff;">Upload</button>
                            <button type="button" data-dismiss="modal" class="rounded pl-3 pr-3 pt-2 pb-2 border-0" style="width: 100px; font-size: 14px; background: #e74c3c; color: #fff;">Discard</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>



    </div>
</template>

<script>

export default {

    data() {
        return {
            columns: ['body_number', 'full_name', 'license_number', 'created_at', 'updated_at','actions'],
            tableData: [],
            options: {
                headings: {
                    body_number: 'Body Number',
                    full_name: 'Captain Name',
                    license_number: 'License #',
                    created_at: 'Created At',
                    updated_at: 'Updated At',
                    action: 'Action',
                },
                sortable: ['body_number', 'full_name'],
                filterable: ['body_number', 'full_name'],
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
                },

            },

            //dropdowns
            errors: [],
            bancaTableData: [],

            //values
            searchedValue: null,
            bodyNumberValue: null,
            operatorValue: null,
            boatNameValue: null,
            bancaIdValue: null,

            boatCaptainId: null,
            lastNameValue: null,
            firstNameValue: null,
            middleNameValue: null,
            licenseNumberValue: null,
            uploadImage: null,
            imageFileValue: null,

            err_msg: '',
            err: false,
            suc_msg: '',
            suc: false,

            loader: false,
            adding: false,
            print: false,
            paperSize: 'Letter',
            paperOrientation: 'Portrait',
        }
    },
    methods: {

        errorHandler(errors){
            let error_handler = [];
            $.each(errors, function(key, value) {
                error_handler.push(value);
            });
            return error_handler;
        },

        clearInput() {
            this.searchedValue = '';
            this.bodyNumberValue = '',
            this.operatorValue = '',
            this.boatNameValue = '',
            this.bancaIdValue = '',
            this.boatCaptainId = '',
            this.lastNameValue = '',
            this.firstNameValue = '',
            this.middleNameValue = '',
            this.licenseNumberValue = '',
            this.errors = [];
        },

        closeMessageBox() {
            this.err = false;
            this.suc = false;
            this.adding = false;
            this.print = false;
            this.err_msg = '';
            this.suc_msg = '';
            this.errors = [];
            this.initialData();
        },

        openModalToUpload(id) {
            $('#upload-modal').modal('show');
            this.boatCaptainId = id;
        },

        onChangeUpload(e) {
            this.imageFileValue = e.target.files[0];
        },

        uploadImageFile(e) {

            e.preventDefault();

            const config = {
                headers: {
                    'content-type': 'multipart/form-data'
                }
            }

            let form_data = new FormData();
            form_data.append('image', this.imageFileValue);

            axios.post('captain/upload/' + this.boatCaptainId, form_data, config)
            .then(response => {
                this.returnSuccess(response);
            })
            .catch(error => {
                this.returnFailed(error);
            });

            $('#upload-modal').modal('hide');
        },

        initialData() {
            this.tableData = [];
            axios.get('captain/getrecord').then(response => {
                this.tableData = response.data.captains;
            })
        },

        find() {

            this.bodyNumberValue = '';
            this.operatorValue = '';
            this.boatNameValue = '';
            this.bancaIdValue = ''


            axios.get('captain/search/' + this.searchedValue)
            .then(response => {
                this.bodyNumberValue = response.data.banca.body_number;
                this.operatorValue = response.data.banca.operator;
                this.boatNameValue = response.data.banca.name;
                this.bancaIdValue = response.data.banca.id
            });
        },

        openModalToCreate() {
            this.clearInput();
            $('#create-modal').modal('show');
            this.adding = true;
        },

        returnSuccess(response) {
            $('#create-modal').modal('hide');
            this.suc = true;
            this.suc_msg = response.data.message;
        },

        returnFailed(error) {
            if(error.response.data.err_msg) {
                this.err = true;
                this.err_msg = error.response.data.err_msg;
                $('#create-modal').modal('hide');
                return;
            }
            this.errors = [];
            this.errors = this.errorHandler(error.response.data.errors);
        },

        openModalToEdit(id) {
            this.clearInput();
            axios.get('captain/edit/' + id)
                .then(response => {
                    this.bodyNumberValue = response.data.captain.body_number;
                    this.operatorValue = response.data.captain.operator;
                    this.boatNameValue = response.data.captain.name;
                    this.bancaIdValue = response.data.captain.banca_id;
                    this.lastNameValue = response.data.captain.last_name;
                    this.firstNameValue = response.data.captain.first_name;
                    this.middleNameValue = response.data.captain.middle_name;
                    this.licenseNumberValue = response.data.captain.license_number;
                    this.boatCaptainId = response.data.captain.id;
                    $('#create-modal').modal('show');
                    this.adding = false;
                });
        },

        storeRecord() {
            this.loader = true;
            axios.post('captain/store', {
                last_name: this.lastNameValue,
                first_name: this.firstNameValue,
                middle_name: this.middleNameValue,
                license_number: this.licenseNumberValue,
                banca_id: this.bancaIdValue,
            })
            .then(response => {
                this.returnSuccess(response);
            })
            .catch(error => {
                this.returnFailed(error);
            })
            .finally(
                () => this.loader = false
            );
        },

        updateRecord(){
            this.loader = true;
            axios.patch('captain/update', {
                id: this.boatCaptainId,
                last_name: this.lastNameValue,
                first_name: this.firstNameValue,
                middle_name: this.middleNameValue,
                license_number: this.licenseNumberValue,
                banca_id: this.bancaIdValue,
            })
            .then(response => {
                this.returnSuccess(response);
            })
            .catch(error => {
                this.returnFailed(error);
            })
            .finally(
                ()=> this.loader = false
            );
        },

        destroyRecord(id) {
            let confirmBox = confirm("Do you really want to delete this record?");
            if(confirmBox === true)
            {
                this.loader = true;

                axios.get('captain/destory/' + id)
                    .then(response => {
                        this.returnSuccess(response);
                    })
                    .catch(error => {
                        this.returnFailed(error);
                    })
                    .finally(()=> this.loader = false);
            }
        },

        exportExcel(){
            window.open('captain/export');
        },

    },

    mounted() {
        this.initialData();
    }
}
</script>
