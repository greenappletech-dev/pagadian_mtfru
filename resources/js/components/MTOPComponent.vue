<style>
    .table td, .table th {
        vertical-align: middle;
        font-size: 14px;
    }

    .table td button {
        font-size: 14px;
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
                <li class="d-inline align-middle" style="font-size: 12px">Application</li>
                <li class="d-inline align-middle mr-1 ml-1" style="font-size: 5px; color: #95a5a6;"><i class="fas fa-circle"></i></li>
                <li class="d-inline align-middle" style="font-size: 12px; color: #95a5a6">MTOP Application</li>
            </ul>
        </div>

        <div class="main-content">
            <h2 class="d-inline align-middle" style="font-size: 17px;">Application Filters</h2>

            <div class="row mt-2 mb-2 d-flex justify-content-between">

                <div class="col-md-12 col-lg-12 col-xl-6">
                    <div class="filter_date d-flex justify-content-start" style="font-size: 14px;">
                        <p class="text-bold" style="margin: 9px 5px 0 0;">From: </p>
                        <input class="mr-2 p-2 rounded border form-control" type="date" style="font-size: 14px;" v-model="fromDateValue">
                        <p class="text-bold" style="margin: 9px 5px 0 0;">To: </p>
                        <input class="mr-1 p-2 rounded border form-control" type="date" style="font-size: 14px;" v-model="toDateValue">
                        <button style="margin: 0; width: 120px; font-size: 14px;" class="btn-light rounded border-0 p-1 form-control" id="filter" v-on:click="getDataFiltered"><i class="fas fa-sync-alt"></i></button>
                    </div>

                    <div class="filter_barangay mt-1 d-flex justify-content-start">
                        <select class="d-inline-block rounded border p-2 form-control" v-model="barangayCodeValue" style="font-size: 14px;">
                            <option value="">Select Barangay (Optional)</option>
                            <option v-for="barangay in barangayCodeTableData" v-bind:value="barangay.id">{{ barangay.brgy_code + '-' + barangay.brgy_desc }}</option>
                        </select>
                    </div>
                </div>

                <div class="mt-1 col-md-12 col-lg-12 col-xl-3">
                    <div class="d-flex justify-content-start">
                        <button v-on:click="openToCreate" class="p-2 pl-4 mr-1 pr-4 rounded border-0 d-inline-block form-control" style="background: rgba(255, 120, 31, 1); color: #fff; font-size: 14px; box-shadow: 2px 2px 2px rgba(165, 165, 165, 1)">New Application</button>
                        <button v-on:click="openModalToPrintReport" class="p-2 pl-4 pr-4 rounded border-0 d-inline-block form-control" style="background: #1abc9c; color: #fff; font-size: 14px; box-shadow: 2px 2px 2px rgba(165, 165, 165, 1)">Print Report</button>
                    </div>

                    <button v-on:click="openViewRenewal" class="p-2 pl-4 pr-4 mt-1 rounded border-0 form-control" style="font-size: 14px; color: #fff; background: #1abc9c; box-shadow: 2px 2px 2px rgba(165, 165, 165, 1)">View Renewal</button>
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
                            <span slot="status" slot-scope="{row}">
                                {{ displayStatus(row.status) }}
                            </span>
                            <span slot="action" slot-scope="{row}">
                                <button v-if="row.status === 1" v-on:click="approveForPayment(row.application_id)" class="btn btn-info d-inline-block"><i class="fas fa-check mr-1"></i>Proceed To Payment</button>
                                <button v-if="row.status === 3" v-on:click="approveApplication(row.application_id)" class="btn btn-warning d-inline-block"><i class="fas fa-check mr-1"></i>Approve</button>
                                <button v-on:click="openModalToPrint(row.application_id, row.status)" class="btn btn-primary d-inline-block"><i class="fas fa-print mr-1"></i> Print</button>
                                <button v-if="row.status !== 4" v-on:click="openToEdit(row.application_id)" class="btn btn-success d-inline-block"><i class="fas fa-edit mr-1"></i> Edit</button>
                                <button v-if="row.status !== 4" v-on:click="destroyRecord(row.application_id)" class="btn btn-danger d-inline-block"><i class="fas fa-trash mr-1"></i>Delete</button>
                            </span>
                        </v-client-table>
                    </div>
                </div>
            </div>
        </div>\

        <!--        modal window    -->

        <div class="modal fade" id="print-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">

                        <h5 class="modal-title" id="exampleModalLabel">Print Forms</h5>

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
                            <label for="forms">Select Form:</label>
                            <select id="forms" class="form-control" v-model="formToPrint">
                                <option value="1">MTFRB APPLICATION FORM</option>
                                <option value="3">RECEIPT OF APPLICATION</option>
                                <option value="4">MOTORIZED TRICYCLE OPERATOR'S PERMIT</option>
                            </select>
                        </div>

                        <div class="form-group" v-else>
                            <label for="paper-size">Paper Size</label>
                            <select id="paper-size" class="form-control" v-model="paperSize">
                                <option>Letter</option>
                                <option>Legal</option>
                                <option>A4</option>
                            </select>

                            <label for="paper-orientation">Orientation</label>
                            <select id="paper-orientation" class="form-control" v-model="paperOrientation">
                                <option>Portrait</option>
                                <option>Landscape</option>
                            </select>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button v-if="print" @click="printApplicationForm" type="button" class="rounded pl-3 pr-3 pt-2 pb-2 border-0" style="width: 100px; font-size: 14px; background: #1abc9c; color: #fff;">Print</button>
                        <button v-else @click="printReport" type="button" class="rounded pl-3 pr-3 pt-2 pb-2 border-0" style="width: 100px; font-size: 14px; background: #1abc9c; color: #fff;">Print</button>
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
            columns: [ 'mtfrb_case_no', 'transact_date', 'full_name', 'body_number', 'status', 'created_at', 'updated_at','action'],
            tableData: [],
            options: {
                headings: {
                    mtfrb_case_no: 'MTFRB #',
                    transact_date: 'Transaction Date',
                    full_name: 'Operator',
                    body_number: 'Body Number',
                    status: 'Status',
                    created_at: 'Created At',
                    updated_at: 'Updated At',
                    action: 'Action',
                },
                sortable: ['mtfrb_case_no', 'transact_date'],
                filterable: ['mtfrb_case_no', 'body_number'],
                templates: {
                    hol_date: function(h, row) {
                        return row.hol_date !== null ? moment(row.hol_date).format('YYYY-MM-DD') : null;
                    },
                    transact_date: function(h, row) {
                        return row.transact_date !== null ? moment(row.transact_date).format('MM-DD-YYYY') : null;
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
            barangayCodeTableData: [],
            tricycleTableData: [],


            //values
            newRenewalValue: false,
            changeUnitValue: false,
            droppingValue: false,
            transactionTotals: false,
            applicationIdValue: null,
            transactionDate: null,
            operatorValue: null,
            addressValue: null,
            MTFRBCaseValue: null,
            toDateValue: null,
            fromDateValue: null,
            tricycleValue: null,
            bodyNumberValue: null,
            makeTypeValue: null,
            engineMotorNo: null,
            plateNoValue: null,
            searchedValue: null,
            formToPrint: null,
            barangayCodeValue: '',
            statusValue: '',


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

        displayStatus(status) {
            if(status === 1) {
                return 'FOR REVIEW';
            }

            if(status === 2) {
                return 'FOR PAYMENT';
            }

            if(status === 3) {
                return 'FOR APPROVAL';
            }

            if(status === 4) {
                return 'APPROVED';
            }
        },

         convertDateFormat($date) {
            let d = new Date($date);

            let month = d.getMonth() + 1;
            let day = d.getDate();
            let year = d.getFullYear();

            if(month.toString().length < 2) {
                month = '0' + month;
            }

            if(day.toString().length < 2) {
                day = '0' + day;
            }

            return [year, month, day].join('-');
        },

        formatPrice(value) {
            let val = (value/1).toFixed(2).replace(',', '.')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },

        errorHandler(errors){
            let error_handler = [];
            $.each(errors, function(key, value) {
                error_handler.push(value);
            });
            return error_handler;
        },

        closeMessageBox() {
            $('#filter').click();
            this.err_msg = '';
            this.suc_msg = '';
            this.suc = false;
            this.err = false;
        },

        openModalToPrint(id, status) {
            this.statusValue = status;
            this.applicationIdValue = id;
            this.print = true;
            this.errors = [];
            $('#print-modal').modal('show');
        },

        openModalToPrintReport() {
            this.print = false;
            $('#print-modal').modal('show');
        },

        printReport() {
            let optional_route = this.barangayCodeValue === '' ? '/null' : '/'  + this.barangayCodeValue;
            window.open('mtop/pdf/' + this.paperSize + '/' + this.paperOrientation + '/' + this.fromDateValue + '/' + this.toDateValue + optional_route);
            $('#create-modal').modal('hide');
        },

        printApplicationForm() {
            if(parseInt(this.formToPrint) <= parseInt(this.statusValue)) {
                window.open('mtop/pdf_application/' + this.applicationIdValue + '/' + this.formToPrint);
                this.print = false;
                this.formToPrint = '';
                $('#print-modal').modal('hide');
                setTimeout(function(){ $('#filter').click(); }  , 1000);
            }
            else
            {
                if(parseInt(this.formToPrint) === 3) {
                    this.errors = this.errorHandler({ invalid: ['This Transaction is not yet Paid'] });
                }

                if(parseInt(this.formToPrint) === 4) {
                    this.errors = this.errorHandler({ invalid: ['This Transaction is not yet Approved'] });
                }
            }
        },

        approveForPayment(id) {
            let confirmBox = confirm("Proceed to Payment?");
            if(confirmBox === true)
            {
                this.loader = true;
                axios.patch('mtop/approve/' + id + '/payment', {
                    application_id: id
                })
                    .then(response => {
                        this.suc = true;
                        this.suc_msg = response.data.message;
                    })
                    .catch(error => {
                        this.err = true;
                        this.err_msg = error.response.data.err_msg;
                    })
                    .finally(() => this.loader = false);
            }
        },

        approveApplication(id) {
            let confirmBox = confirm("Approve this Application?");
            if(confirmBox === true)
            {
                this.loader = true;
                axios.patch('mtop/approve/' + id + '/approval', {
                    application_id: id
                })
                .then(response => {
                    this.suc = true;
                    this.suc_msg = response.data.message;
                })
                .catch(error => {
                    this.err = true;
                    this.err_msg = error.response.data.err_msg;
                })
                .finally(() => this.loader = false);
            }
        },

        initialData() {
            axios.get('mtop/getdata')
                .then(response => {
                    this.barangayCodeTableData = response.data.barangays;
                });
        },

        getDataFiltered() {
            this.loader = true;
            this.tableData = [];
            let optional_route = this.barangayCodeValue === '' ? '/null' : '/'  + this.barangayCodeValue;

            axios.get('mtop/getdata_filtered/' + this.fromDateValue + '/' + this.toDateValue + optional_route)
            .then(response => {
                this.tableData = response.data.mtop_applications;
            })
            .finally(()=> this.loader = false);
        },

        destroyRecord(id) {
            let confirmBox = confirm("Do you really want to delete this application?");

            if(confirmBox === true){

                this.loader = true;

                axios.get('mtop/destroy/' + id)
                .then(response => {
                    this.suc = true;
                    this.suc_msg = response.data.message;
                })
                .catch(error => {
                    this.err = true;
                    this.err_msg = error.response.data.err_msg;
                })
                .finally(()=> this.loader = false);
            }
        },

        openToCreate() {
            window.location.href = 'mtop_create';
        },

        openToEdit(id) {
            window.location.href = 'mtop_edit/' + id;
        },

        openViewRenewal() {
            window.location.href = 'mtop_renewal';
        },

    },

    mounted() {
        const d = new Date();
        let month = d.getMonth() + 1;
        let day = d.getDate();
        let year = d.getFullYear();
        const get_current_date = [month, day, year].join('/');

        this.fromDateValue = this.convertDateFormat('1/1/' + year);
        this.toDateValue = this.convertDateFormat(get_current_date);
        this.getDataFiltered();
        this.initialData();
    }
}
</script>
