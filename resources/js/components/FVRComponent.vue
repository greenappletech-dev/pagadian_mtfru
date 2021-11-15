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
                <li class="d-inline align-middle" style="font-size: 12px; color: #95a5a6">FVR Application</li>
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
                                <button v-if="row.status === 1" v-on:click="openModalForPayment(row.application_id)" class="btn btn-info d-inline-block"><i class="fas fa-check mr-1"></i>Proceed To Payment</button>
                                <button v-if="row.status === 2" v-on:click="approveApplication(row.application_id)" class="btn btn-warning d-inline-block"><i class="fas fa-check mr-1"></i>Approve</button>
                                <button v-on:click="openModalToPrint(row.application_id, row.status)" class="btn btn-primary d-inline-block"><i class="fas fa-print mr-1"></i> Print</button>
                                <button v-if="row.status !== 3" v-on:click="openToEdit(row.application_id)" class="btn btn-success d-inline-block"><i class="fas fa-edit mr-1"></i> Edit</button>
                                <button v-if="row.status !== 3" v-on:click="destroyRecord(row.application_id)" class="btn btn-danger d-inline-block"><i class="fas fa-trash mr-1"></i>Delete</button>
                            </span>
                        </v-client-table>
                    </div>
                </div>
            </div>
        </div>

        <!--        modal window    -->

        <div class="modal fade" id="print-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">

                        <h5 class="modal-title" id="exampleModalLabel" v-if="payment">Enter OR Number</h5>
                        <h5 class="modal-title" id="exampleModalLabel" v-else>Print Forms</h5>

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
                                <option value="0">AFFIDAVIT OF OWNERSHIP</option>
                                <option value="1">MBOL APPLICATION</option>
                                <option value="2">OTHER DOCUMENTS</option>
                            </select>
                        </div>

                        <div class="form-group" v-else>

                            <div class="form-group" v-if="payment">
                                <div v-if="orGroupA">
                                    <label for="or_no">OR Number A:</label>
                                    <input type="text" class="form-control" id="or_no" v-model="ORNumber" maxlength="20">
                                </div>

                                <div v-if="orGroupB">
                                    <label for="or_no">OR Number B:</label>
                                    <input type="text" class="form-control" id="or_no" v-model="ORNumber2" maxlength="20">
                                </div>

                                <div v-if="orGroupC">
                                    <label for="or_no">OR Number C:</label>
                                    <input type="text" class="form-control" id="or_no" v-model="ORNumber3" maxlength="20">
                                </div>

                                <label for="or_date">OR Date:</label>
                                <input type="date" class="form-control" id="or_date" v-model="ORDate">
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

                    </div>
                    <div class="modal-footer">
                        <button v-if="print" @click="printApplicationForm" type="button" class="rounded pl-3 pr-3 pt-2 pb-2 border-0" style="width: 100px; font-size: 14px; background: #1abc9c; color: #fff;">Print</button>
                        <div v-else>
                            <button v-if="payment" @click="processPayment" type="button" class="rounded pl-3 pr-3 pt-2 pb-2 border-0" style="width: 140px; font-size: 14px; background: #1abc9c; color: #fff;">Save Payment</button>
                            <button v-else @click="printReport" type="button" class="rounded pl-3 pr-3 pt-2 pb-2 border-0" style="width: 100px; font-size: 14px; background: #1abc9c; color: #fff;">Print</button>
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
            columns: [ 'body_number', 'transact_date', 'full_name', 'status', 'created_at', 'updated_at','action'],
            tableData: [],
            options: {
                headings: {
                    body_number: 'Body Number',
                    transact_date: 'Transaction Date',
                    full_name: 'Operator',
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
            ORNumber: null,
            ORNumber2: null,
            ORNumber3: null,
            ORDate: null,

            err_msg: '',
            err: false,
            suc_msg: '',
            suc: false,

            orGroupA: false,
            orGroupB: false,
            orGroupC: false,
            payment: false,
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
                return 'FOR PAYMENT';
            }

            if(status === 2) {
                return 'FOR APPROVAL';
            }

            if(status === 3) {
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

        returnSuccess(response) {
            $('#print-modal').modal('hide');
            this.suc = true;
            this.suc_msg = response.data.message;
        },

        returnFailed(error) {
            if(error.response.data.err_msg) {
                this.err = true;
                this.err_msg = error.response.data.err_msg;
                $('#print-modal').modal('hide');
                return;
            }
            this.errors = [];
            this.errors = this.errorHandler(error.response.data.errors);
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
            this.payment = false;
            $('#print-modal').modal('show');
        },

        printReport() {
            let optional_route = this.barangayCodeValue === '' ? '/null' : '/'  + this.barangayCodeValue;
            window.open('fvr/pdf/' + this.paperSize + '/' + this.paperOrientation + '/' + this.fromDateValue + '/' + this.toDateValue + optional_route);
            $('#create-modal').modal('hide');
        },

        printApplicationForm() {
            if(parseInt(this.formToPrint) <= parseInt(this.statusValue)) {
                window.open('fvr/pdf_application/' + this.applicationIdValue + '/' + this.formToPrint);
                this.print = false;
                this.formToPrint = '';
                $('#print-modal').modal('hide');
                setTimeout(function(){ $('#filter').click(); }  , 1000);
            }
            else
            {
                if(parseInt(this.formToPrint) === 2) {
                    this.errors = this.errorHandler({ invalid: ['This Transaction is not yet Paid'] });
                }
            }
        },

        openModalForPayment(id) {
            this.applicationIdValue = id;
            this.orGroupA = false;
            this.orGroupB = false;
            this.orGroupC = false;
            this.errors = [];

            axios.get('fvr/checkorgroup/' + id).then(response => {
                response.data.or_group.forEach((item) => {
                    this.fetchOrGroup(item.or_group, item.count);
                })
            });
        },

        fetchOrGroup(group, count) {

            if(group === "A" && count > 0) {
                this.orGroupA = true;
            }

            if(group === "B" && count > 0) {
                this.orGroupB = true;
            }

            if(group === "C" && count > 0) {
                this.orGroupC = true;
            }

            this.payment = true;
            this.print = false;
            $('#print-modal').modal('show');
        },

        processPayment() {
            let confirmBox = confirm('Are you sure you want to save this Transaction?');

            if(confirmBox === true) {
                this.loader = true;

                axios.patch('fvr/payment', {
                    id: this.applicationIdValue,
                    or_number: this.ORNumber,
                    or_number_2: this.ORNumber2,
                    or_number_3: this.ORNumber3,
                    or_group_a: this.orGroupA,
                    or_group_b: this.orGroupB,
                    or_group_c: this.orGroupC,
                    or_date: this.ORDate,
                }).then(response => {
                    this.returnSuccess(response);
                })
                .catch(error => {
                    this.returnFailed(error);
                })
                .finally(() => this.loader = false);
            }
        },

        approveApplication(id) {
            let confirmBox = confirm("Approve this Application?");
            if(confirmBox === true)
            {
                this.loader = true;
                axios.patch('fvr/approve/' + id,{
                    // application_id: id
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
            axios.get('fvr/getdata')
                .then(response => {
                    this.barangayCodeTableData = response.data.barangays;
                });
        },

        getDataFiltered() {
            this.loader = true;
            this.tableData = [];
            let optional_route = this.barangayCodeValue === '' ? '/null' : '/'  + this.barangayCodeValue;

            axios.get('fvr/getdata_filtered/' + this.fromDateValue + '/' + this.toDateValue + optional_route)
            .then(response => {
                this.tableData = response.data.fvr_applications;
            })
            .finally(()=> this.loader = false);
        },

        destroyRecord(id) {
            let confirmBox = confirm("Do you really want to delete this application?");

            if(confirmBox === true){

                this.loader = true;

                axios.get('fvr/destroy/' + id)
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
            window.location.href = 'fvr_entry';
        },

        openToEdit(id) {
            window.location.href = 'fvr_edit/' + id;
        },

        openViewRenewal() {
            window.location.href = 'fvr_renewal';
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
