<style>

    input[type="file"] {
        width: 100%;
        font-size: 14px;
    }

    input::-webkit-file-upload-button {
        margin: 2px;
        margin-right: 10px;
        border: none;
        padding: 5px 10px;
    }

</style>
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
                <li class="d-inline align-middle" style="font-size: 12px">Master Data</li>
                <li class="d-inline align-middle mr-1 ml-1" style="font-size: 5px; color: #95a5a6;"><i class="fas fa-circle"></i></li>
                <li class="d-inline align-middle" style="color: #95a5a6; font-size: 12px">Operator</li>
            </ul>
        </div>

        <div class="main-content">


            <h2 class="d-inline align-middle">Operator</h2>


            <div class="row mt-3 mb-1">
                <div class="col-lg-6">
                    <button v-on:click="openModalToCreate" class="d-inline align-middle p-2 pl-4 pr-4 rounded border-0" style="background: rgba(255, 120, 31, 1); color: #fff; font-size: 14px; box-shadow: 2px 2px 2px rgba(165, 165, 165, 1)">Add New Operator</button>
                </div>

                <div class="col-lg-6 text-right">
<!--                    <a class="p-2 pl-4 pr-4 rounded" style="background: #1abc9c; font-size: 14px; box-shadow: 2px 2px 2px rgba(165, 165, 165, 1); color: #fff; text-decoration: none;" v-on:click="exportExcel">Export to Excel</a>-->
<!--                    <a class="p-2 pl-4 pr-4 rounded" style="background: #1abc9c; font-size: 14px; box-shadow: 2px 2px 2px rgba(165, 165, 165, 1); color: #fff; text-decoration: none;" v-on:click="pdfModal">Print to PDF</a>-->
                </div>
            </div>


            <div class="row mt-2 mb-2">
                <div class="col-lg-4">
                    <input type="text" v-model="searchedValue" class="form-control" placeholder="Search Operator By Name" style="text-transform: uppercase">
                    <button v-on:click="searchOperator" class="pl-3 pr-3 border-0 rounded-right" style="position: absolute; top: 0; right: 0; height: 100%; background: rgba(206, 206, 206, 1)"><i class="fas fa-search"></i></button>
                </div>
                <div class="col-lg-8 text-right" style="font-size: 13px;">
                    <p class="d-inline-block align-middle m-0">Page: </p>
                    <input class="d-inline-block align-middle text-center border rounded form-control" style="width: 80px" type="text" v-model="pageNumber">
                    <p class="d-inline-block align-middle m-0">of {{ totalNumberofRecord }}</p>
                    <button class="d-inline-block btn ml-3 form-control" style="background: #1abc9c; color:white; font-size: 13px; width: 50px" v-on:click="goToPageNumber">Go</button>
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
                                <button v-on:click="viewImage(row.id)" class="btn btn-info mb-2"><i class="fas fa-image mr-1"></i>View Image</button>
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
                        <h5 class="modal-title" id="exampleModalLabel" v-if="adding">Add Operator</h5>
                        <h5 class="modal-title" id="exampleModalLabel" v-if="!adding && !view_img">Edit Operator</h5>
                        <h5 class="modal-title" id="exampleModalLabel" v-if="view_img">Operator Image</h5>


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


                        <div class="form-group" v-if="view_img">
                            <img style="width: 100%; max-height: 500px" v-bind:src="img_location">
                        </div>

                        <div v-else>
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
                                <label for="last_name">Last Name</label>
                                <input type="text" id="last_name" class="form-control" v-model="lastNameValue">

                                <label for="first_name">First Name</label>
                                <input type="text" id="first_name" class="form-control" v-model=firstNameValue>

                                <label for="middle_name">Middle Name</label>
                                <input type="text" id="middle_name" class="form-control" v-model="middleNameValue">

                                <label for="suffix">Suffix</label>
                                <input type="text" id="suffix" class="form-control" v-model="suffixValue">

                                <label for="birth_date">Birth Date</label>
                                <input type="date" id="birth_date" class="form-control" v-model="birthdateValue">

                                <label for="sex">Sex</label>
                                <select id="sex" class="form-control" v-model="sexValue">
                                    <option value="F">Female</option>
                                    <option value="M">Male</option>
                                </select>

                                <label for="civ_stat">Civil Status</label>
                                <select id="civ_stat" class="form-control" v-model="civilStatusValue">
                                    <option value="SNL">SINGLE</option>
                                    <option value="MRD">MARRIED</option>
                                </select>

                                <label for="mobile">Mobile</label>
                                <input type="text" id="mobile" class="form-control" v-model="mobileValue">

                                <label for="email">Email</label>
                                <input type="email" id="email" class="form-control" v-model="emailValue">

                                <label for="brgy_code">Barangay Code</label>
                                <select id="brgy_code" class="form-control" v-model="barangayCodeValue">
                                    <option v-for="barangay in barangayCodeTableData" v-bind:value="barangay.brgy_code">{{ barangay.brgy_code + '-' + barangay.brgy_desc }}</option>
                                </select>

                                <label for="address">Address</label>
                                <input type="text" class="form-control" id="address" v-model="addressValue">

                            </div>
                        </div>


                    </div>

                    <div class="modal-footer" v-if="!view_img">
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

        <form method="post" enctype="multipart/form-data" @submit="uploadImageFile">
            <div class="modal fade" id="upload-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel" v-if="uploadImage">Upload Image</h5>
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
            columns: ['id','tax_id', 'full_name', 'birth_date','sex','civ_stat', 'mobile', 'email', 'brgy_desc', 'created_at', 'updated_at','actions'],
            tableData: [],
            options: {
                headings: {
                    id: 'ID',
                    tax_id: 'Tax ID',
                    full_name: 'Full Name',
                    birth_date: 'Birth Date',
                    sex: 'Sex',
                    civ_stat: 'Civil Status',
                    mobile: 'Mobile',
                    email: 'Email',
                    brgy_desc: 'Barangay',
                    created_at: 'Created At',
                    updated_at: 'Updated At',
                    action: 'Action',
                },
                sortable: ['id', 'tax_id', 'full_name'],
                filterable: false,
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
            barangayCodeTableData: [],


            //values
            operatorIdValue: null,
            lastNameValue: null,
            firstNameValue: null,
            middleNameValue: null,
            suffixValue: null,
            birthdateValue: null,
            sexValue: null,
            civilStatusValue: null,
            mobileValue: null,
            emailValue: null,
            barangayCodeValue: null,
            addressValue: null,
            totalNumberofRecord: null,
            pageNumber: 1,
            searchedValue: null,
            searched: false,
            uploadImage: true,
            imageFileValue: null,


            err_msg: null,
            suc_msg: null,
            err: false,
            suc: false,
            loader: false,
            adding: false,
            print: false,
            view_img: false,
            img_location: '',

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

        closeMessageBox() {
            this.err = false;
            this.suc = false;
            this.adding = false;
            this.print = false;
            this.view_img = false;
            this.err_msg = '';
            this.suc_msg = '';
            this.initialData();
        },

        openModalToUpload(id) {
            this.view_img = false;
            $('#upload-modal').modal('show');
            this.operatorIdValue = id;
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

            axios.post('operator/upload/' + this.operatorIdValue, form_data, config)
            .then(response => {
                this.returnSuccess(response);
            })
            .catch(error => {
                this.returnFailed(error);
            });

        },


        clearInputs() {
            this.errors = [];
            this.operatorIdValue = null;
            this.lastNameValue = null;
            this.firstNameValue = null;
            this.middleNameValue = null;
            this.suffixValue = null;
            this.birthdateValue = null;
            this.addressValue = null;
            this.sexValue = null;
            this.civilStatusValue = null;
            this.mobileValue = null;
            this.emailValue = null;
            this.barangayCodeValue = null;
        },

        initialData() {
            this.tableData = [];
            this.loader = true;
            this.searched = false;

            axios.get('operator/getdata?page=' + this.pageNumber)
            .then(response => {
                this.barangayCodeTableData = response.data.barangays;

                /* operators data and pagination */

                this.tableData = response.data.operators.data;
                this.totalNumberofRecord = response.data.operators.last_page;
            })
            .catch(error => {
                this.returnFailed(error);
            })
            .finally(() => this.loader = false)
        },

        searchOperator() {
            this.searched = true;
            axios.get('operator/search/' + this.searchedValue.toUpperCase() + '?page=' + this.pageNumber)
            .then(response => {
                this.tableData = response.data.operators.data;
                this.totalNumberofRecord = response.data.operators.last_page;
            })
        },

        goToPageNumber() {
            this.searched ? this.searchOperator() : this.initialData();
        },

        openModalToCreate() {
            this.clearInputs();
            $('#create-modal').modal('show');
            this.adding = true;
        },

        returnSuccess(response) {
            $('#upload-modal').modal('hide');
            $('#create-modal').modal('hide');
            this.suc = true;
            this.suc_msg = response.data.message;
        },

        returnFailed(error) {
            if(error.response.data.err_msg) {
                this.err = true;
                this.err_msg = error.response.data.err_msg;
                $('#create-modal').modal('hide');
                $('#upload-modal').modal('hide');
                return;
            }
            this.errors = [];
            this.errors = this.errorHandler(error.response.data.errors);
        },

        storeRecord() {
            this.loader = true;
            axios.post('operator/store', {
                last_name: this.lastNameValue,
                first_name: this.firstNameValue,
                mid_name: this.middleNameValue,
                suffix: this.suffixValue,
                birth_date: this.birthdateValue,
                sex: this.sexValue,
                civ_stat: this.civilStatusValue,
                mobile: this.mobileValue,
                email: this.emailValue,
                brgy_code: this.barangayCodeValue,
                address: this.addressValue,
            })
            .then(response => {
                this.returnSuccess(response);
            })
            .catch(error => {
                this.returnFailed(error);
            })
            .finally(() => this.loader = false)
        },

        openModalToEdit(id) {
            this.clearInputs();
            this.adding = false;
            this.view_img = false;
            axios.get('operator/edit/' + id)
            .then(response => {
                this.operatorIdValue = response.data.operator.id;
                this.lastNameValue = response.data.operator.last_name;
                this.firstNameValue = response.data.operator.first_name;
                this.middleNameValue = response.data.operator.mid_name;
                this.suffixValue = response.data.operator.suffix;
                this.birthdateValue = response.data.operator.birth_date;
                this.sexValue = response.data.operator.sex;
                this.civilStatusValue = response.data.operator.civ_stat;
                this.mobileValue = response.data.operator.mobile;
                this.emailValue = response.data.operator.email;
                this.barangayCodeValue = response.data.operator.brgy_code;
                this.addressValue = response.data.operator.address1;
                $('#create-modal').modal('show');
            });
        },

        updateRecord(){
            this.loader = true;
            axios.patch('operator/update/' + this.operatorIdValue, {
                last_name: this.lastNameValue,
                first_name: this.firstNameValue,
                mid_name: this.middleNameValue,
                suffix: this.suffixValue,
                birth_date: this.birthdateValue,
                sex: this.sexValue,
                civ_stat: this.civilStatusValue,
                mobile: this.mobileValue,
                email: this.emailValue,
                brgy_code: this.barangayCodeValue,
                address: this.addressValue,
            })
            .then(response => {
                this.returnSuccess(response);
            })
            .catch(error => {
                this.returnFailed(error);
            })
            .finally(() => this.loader = false)
        },

        destroyRecord(id) {
            let confirmBox = confirm("Do you really want to delete this record?");
            if(confirmBox === true){
                this.loader = true;
                axios.get('operator/destory/' + id)
                .then(response => {
                    this.returnSuccess(response);
                })
                .catch(error => {
                    this.returnFailed(error);
                })
                .finally(()=> this.loader = false);
            }
        },

        pdfModal() {
            this.view_img = false;
            this.print = true;
            this.errors = [];
            $('#create-modal').modal('show');
        },

        pdfPrint() {
            window.open('tricycle/pdf/' + this.paperSize + '/' + this.paperOrientation);
            $('#create-modal').modal('hide');
            this.print = false;
        },

        exportExcel(){
            window.open('tricycle/export');
        },

        viewImage(id) {
            this.view_img = true;
            this.adding = false;

            axios.get('operator/viewImage/' + id)
            .then(response => {
                $('#create-modal').modal('show');
                this.img_location = 'public/image/operator_image/' + response.data.image;
            }).catch(error => {
                this.returnFailed(error);
            })

        }
    },

    mounted() {
        this.initialData();
    }
}
</script>
