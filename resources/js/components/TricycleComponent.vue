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
                <li class="d-inline align-middle" style="color: #95a5a6; font-size: 12px">Tricycle</li>
            </ul>
        </div>

        <div class="main-content">
            <h2 class="d-inline align-middle">Tricycle</h2>
            <div class="row mt-3 mb-3">
                <div class="col-6">
                    <button v-on:click="openModalToCreate" class="d-inline align-middle p-2 pl-4 pr-4 rounded border-0" style="background: rgba(255, 120, 31, 1); color: #fff; font-size: 14px; box-shadow: 2px 2px 2px rgba(165, 165, 165, 1)">Add New Tricycle</button>
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
                                <button v-on:click="printStab(row.id)" class="btn btn-primary d-inline-block mb-2"><i class="fas fa-print mr-2"></i>Print Stab</button>
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

                        <h5 class="modal-title" id="exampleModalLabel" v-if="adding">Add Tricycle</h5>
                        <h5 class="modal-title" id="exampleModalLabel" v-else>Edit Tricycle</h5>

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
                            <label for="find">Find Operator</label>
                            <div class="d-flex justify-content-between" style="position: relative;">
                                <input type="text" id="find" v-model="searchedValue" class="form-control pr-4" placeholder="Search Name, Business Name" style="text-transform: uppercase">
                                <button @click="findOperator" class="pl-3 pr-3 border-0 rounded-right" style="position: absolute; top: 0; right: 0; height: 100%; background: rgba(206, 206, 206, 1)"><i class="fas fa-search"></i></button>
                            </div>

                            <label for="operator">Operator</label>
                            <select id="operator" class="form-control" v-model="operatorValue">
                                <option v-for="operator in operatorTableData" v-bind:value="operator.taxpayer_id">{{ operator.operator }}</option>
                            </select>

                            <label for="body_number">Body Number</label>
                            <input type="text" style="text-transform: uppercase" v-model="bodyNumberValue" id="body_number" class="form-control">

                            <label for="make_type">Make/Type</label>
                            <input type="text" style="text-transform: uppercase" v-model="makeTypeValue" id="make_type" class="form-control">

                            <label for="engine_no">Engine No</label>
                            <input type="text" style="text-transform: uppercase" v-model="engineNoValue" id="engine_no" class="form-control">

                            <label for="chassis_no">Chassis No</label>
                            <input type="text" style="text-transform: uppercase" v-model="chassisNoValue" id="chassis_no" class="form-control">

                            <label for="engine_no">Plate No</label>
                            <input type="text" style="text-transform: uppercase" v-model="plateNoValue" id="plate_no" class="form-control">

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
    </div>
</template>

<script>

export default {

    data() {
        return {
            columns: ['operator', 'body_number', 'make_type','engine_motor_no','chassis_no', 'plate_no', 'created_at', 'updated_at','actions'],
            tableData: [],
            options: {
                headings: {
                    operator: 'Operator',
                    body_number: 'Body Number',
                    make_type: 'Make/Type',
                    engine_motor_no: 'Engine No',
                    chassis_no: 'Chassis No',
                    plate_no: 'Plate No',
                    created_at: 'Created At',
                    updated_at: 'Updated At',
                    action: 'Action',
                },
                sortable: ['operator', 'body_number'],
                filterable: ['operator', 'body_number'],
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
            operatorTableData: [],
            operatorNameTableData: [],

            tricycleIdValue: null,
            searchedValue: null,
            operatorValue: null,
            bodyNumberValue: null,
            makeTypeValue: null,
            engineNoValue: null,
            chassisNoValue: null,
            plateNoValue: null,

            //values


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

            console.log(errors);
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
            this.err_msg = '';
            this.suc_msg = '';
            this.initialData();
        },

        clearInput() {
            this.errors = [];
            this.tricycleIdValue = null;
            this.searchedValue = null;
            this.operatorValue = null;
            this.bodyNumberValue = null;
            this.makeTypeValue = null;
            this.engineNoValue = null;
            this.chassisNoValue = null;
            this.plateNoValue = null;
        },

        initialData() {
            this.tableData = [];
            axios.get('tricycle/getdata').then(response => {
                this.tableData = response.data.tricycles;
                // this.bodyNumberValue = response.data.body_number;
            })
        },

        findOperator() {
            axios.get('tricycle/search/'+ this.searchedValue.toUpperCase()).then(response => {
                this.operatorTableData = response.data.operators;
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

        printStab(id) {
            window.open('tricycle/print/' + id);
        },

        storeRecord() {
            this.loader = true;

            axios.post('tricycle/store', {
                operator_id: this.operatorValue,
                body_number: this.bodyNumberValue,
                make_type: this.makeTypeValue,
                engine_motor_no: this.engineNoValue,
                chassis_no: this.chassisNoValue,
                plate_no: this.plateNoValue,
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
            this.clearInput();
            axios.get('tricycle/edit/' + id).then(response => {
                this.tricycleIdValue = response.data.tricycle.id;
                this.operatorTableData = [response.data.operator];
                this.operatorValue = response.data.tricycle.taxpayer_id;
                this.bodyNumberValue = response.data.tricycle.body_number;
                this.makeTypeValue = response.data.tricycle.make_type;
                this.engineNoValue = response.data.tricycle.engine_motor_no;
                this.chassisNoValue = response.data.tricycle.chassis_no;
                this.plateNoValue = response.data.tricycle.plate_no;

                $('#create-modal').modal('show');
                this.adding = false;
            });
        },

        updateRecord(){
            this.loader = true;
            axios.patch('tricycle/update/' + this.tricycleIdValue, {
                operator_id: this.operatorValue,
                body_number: this.bodyNumberValue,
                make_type: this.makeTypeValue,
                engine_motor_no: this.engineNoValue,
                chassis_no: this.chassisNoValue,
                plate_no: this.plateNoValue,
            })
            .then(response => {
                this.returnSuccess(response);
            })
            .catch(error => {
                this.returnFailed(error);
            })
            .finally(()=> this.loader = false);
        },

        destroyRecord(id) {
            let confirmBox = confirm("Do you really want to delete this record?");
            if(confirmBox === true){
                this.loader = true;
                axios.get('tricycle/destory/' + id)
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
    },

    mounted() {
        this.initialData();
    }
}
</script>
