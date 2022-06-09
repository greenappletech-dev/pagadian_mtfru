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
                <li class="d-inline align-middle" style="font-size: 12px; color: #95a5a6">Annual Tax</li>
            </ul>
        </div>


        <div class="main-content">
            <div class="row mt-2 mb-2 d-flex justify-content-between">
                <div class="col-md-12 col-lg-12 col-xl-6">
                    <div class="filter_date d-flex justify-content-start" style="font-size: 14px;">
                        <p class="text-bold" style="margin: 9px 5px 0 0;">From: </p>
                        <input class="mr-2 p-2 rounded border form-control" type="date" style="font-size: 14px;">
                        <p class="text-bold" style="margin: 9px 5px 0 0;">To: </p>
                        <input class="mr-1 p-2 rounded border form-control" type="date" style="font-size: 14px;">
                        <button style="margin: 0; width: 120px; font-size: 14px;" class="btn-light rounded border-0 p-1 form-control" id="filter"><i class="fas fa-sync-alt"></i></button>
                    </div>
                </div>
                <div class="col-md-12 col-lg-12 col-xl-6">
                    <div class="d-flex justify-content-end">
                        <button style="margin: 0; width: 200px; font-size: 14px; background: rgba(255, 120, 31, 1); color: white;" class="btn rounded border-0 p-1 form-control" id="create" v-on:click="openModalToCreate">New</button>
                    </div>
                </div>
            </div>

            <div class="main-content-table">
                <div class="row">
                    <div class="col-md-12">
                        <v-client-table
                            :data="tableData"
                            :columns="columns"
                            :options="options">
                            <span slot="status" slot-scope="row">
                                {{ displayStatus(row.row.status) }}
                            </span>
                            <span slot="action" slot-scope="row">
                                <button class="btn btn-success"v-on:click="editRecord(row.row.id)"><i class="fas fa-edit mr-1"></i>Edit</button>
                                <button v-on:click="tagOrNumber(row.row.id)" class="btn btn-info d-inline-block"><i class="fas fa-check mr-1"></i>Tag OR Number</button>
                            </span>
                        </v-client-table>
                    </div>
                </div>
            </div>


            <!--- modal add ---->



            <div class="modal" id="create_modal" name="modal_create">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header d-flex align-items-center">
                            <h4 style="font-size: 15px; margin: 0">Create Annual Tax</h4>
                            <button style="border: none; background: none" data-dismiss="modal">
                                &times;
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="col-8">
                                <div class="card" v-if="adding" >
                                    <div class="card-header">
                                        <h4 style="font-size: 15px; margin: 0">Search Operator</h4>
                                    </div>
                                    <div class="card-body">

                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Search By</span>
                                            </div>
                                            <select class="form-control" v-model="filter_value" v-on:change="filterOnChange($event)">
                                                <option value="body_number">Body Number</option>
                                                <option value="operator">Operator</option>
                                            </select>
                                        </div>

                                        <div class="input-group mt-2 mb-2">
                                            <input type="text" class="form-control" id="search" v-model="search_value">
                                            <div class="input-group-append">
                                                <button class="btn btn-secondary" v-on:click="resultOperator"><i class="fa fa-search mr-2"></i>View Results</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <table class="mb-3">
                                            <tr>
                                                <th style="width: 80px">Name</th>
                                                <th style="width: 80px">:</th>
                                                <td>{{ this.operator_data.operator }}</td>
                                            </tr>
                                            <tr>
                                                <th style="width: 80px">Contact #</th>
                                                <th style="width: 80px">:</th>
                                                <td>{{ this.operator_data.tel_num }}</td>
                                            </tr>
                                        </table>

                                        <div class="row">
                                            <div class="col-6 mb-2">
                                                <label>Annual Tax Year</label>
                                                <select class="form-control" v-model="other_inc_value">
                                                    <option v-for="data in otherinc" v-bind:value="data.id">{{ data.inc_desc }}</option>
                                                </select>
                                            </div>

                                            <div class="col-md-12">
                                                <v-client-table
                                                    :data="tricycle_tableData"
                                                    :columns="tricycle_columns"
                                                    :options="tricycle_options">
                                                    <span slot="action" slot-scope="row">
                                                        <input type="checkbox" v-on:change="onTricyclesCheck(row, $event)" :checked="row.row.checked">
                                                    </span>
                                                </v-client-table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer d-flex justify-content-end">
                            <button v-if="adding" class="btn btn-primary" v-on:click="createRecord">Save</button>
                            <button v-else class="btn btn-primary" v-on:click="updateRecord">Update</button>
                            <button class="btn btn-light" data-dismiss="modal">Dismiss</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal" name="modal_result" id="operator_result">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header d-flex align-items-center">
                            <h4 style="font-size: 15px; margin: 0">Operator</h4>
                            <button style="border: none; background: none" data-dismiss="modal">
                                &times;
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <v-client-table
                                        :data="operator_tableData"
                                        :columns="operator_columns"
                                        :options="operator_options">
                                        <template slot="action" slot-scope="data">
                                            <button class="btn btn-secondary" v-on:click="selectOperator(data.index - 1)"><i class="fa fa-check mr-2"></i>Select Operator</button>
                                        </template>
                                    </v-client-table>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer d-flex justify-content-end">
                            <button class="btn btn-light" data-dismiss="modal">Dismiss</button>
                        </div>
                    </div>
                </div>
            </div>

<!--            <div class="modal" name="modal_result" id="tricycle_result">-->
<!--                <div class="modal-dialog modal-xl">-->
<!--                    <div class="modal-content">-->
<!--                        <div class="modal-header d-flex align-items-center">-->
<!--                            <h4 style="font-size: 15px; margin: 0">Tricycles</h4>-->
<!--                            <button style="border: none; background: none" data-dismiss="modal">-->
<!--                                &times;-->
<!--                            </button>-->
<!--                        </div>-->
<!--                        <div class="modal-body">-->
<!--                            <div class="row">-->
<!--                                <div class="col-md-12">-->
<!--                                    <v-client-table-->
<!--                                        :data="result_tricycle_tableData"-->
<!--                                        :columns="result_tricycle_columns"-->
<!--                                        :options="result_tricycle_options">-->

<!--                                        <span slot="action" slot-scope="data">-->
<!--                                            <input type="checkbox" v-on:change="onTricyclesCheck(data.index,data.row, $event)">-->
<!--                                        </span>-->

<!--                                    </v-client-table>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <div class="modal-footer d-flex justify-content-end">-->
<!--                            <button class="btn btn-secondary" v-on:click="selectTricycle" style="font-size: 12px"><i class="fa fa-check mr-3"></i>Add Tricycle</button>-->
<!--                            <button class="btn btn-light" data-dismiss="modal" style="font-size: 12px">Dismiss</button>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->




        </div>
    </div>
</template>

<script>

export default {

    props: ['annualtax', 'otherinc'],

    data() {
        return {
            columns: [ 'transaction_date', 'name', 'inc_desc', 'status', 'created_at', 'updated_at','action'],
            tableData: this.annualtax,
            options: {
                headings: {
                    transaction_date: 'Transaction Date',
                    name: 'Operator',
                    inc_desc: 'Tax year',
                    status: 'Status',
                    created_at: 'Created At',
                    updated_at: 'Updated At',
                    action: 'Action',
                },
                sortable: ['transact_date', 'body_number', 'full_name'],
                filterable: ['transact_date', 'body_number', 'full_name'],
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

            // Search Operator

            operator_columns: ['operator','body_number', 'no_of_unit', 'action'],
            operator_tableData: [],
            operator_options: {
                headings: {
                    operator: 'Operator',
                    body_number: 'Body Number',
                    no_of_unit: 'Number of Unit',
                    action: 'Action',
                },
                sortable: ['transact_date', 'body_number', 'full_name'],
                filterable: false,
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
            },


            // Select Tricycles

            // result_tricycle_columns: ['trnx_date', 'body_number',  'inc_desc','action'],
            // result_tricycle_tableData: [],
            // result_tricycle_options: {
            //     headings: {
            //         trnx_date: 'Payment Date',
            //         body_number: 'Body Number',
            //         inc_desc: 'Last Tax Year Paid',
            //         action: 'Action',
            //     },
            //     sortable: [],
            //     filterable: false,
            //     templates: {
            //         hol_date: function(h, row) {
            //             return row.hol_date !== null ? moment(row.hol_date).format('YYYY-MM-DD') : null;
            //         },
            //         transact_date: function(h, row) {
            //             return row.trnx_date !== null ? moment(row.trnx_date).format('MM/DD/YYYY') : null;
            //         },
            //         created_at: function(h, row) {
            //             return row.created_at !== null ? moment(row.created_at).format('YYYY-MM-DD hh:mm:ss') : null;
            //         },
            //         updated_at: function(h, row) {
            //             return row.updated_at !== null ? moment(row.updated_at).format('YYYY-MM-DD hh:mm:ss') : null;
            //         }
            //     },
            // },

            tricycle_columns: ['tricycle_id', 'body_number', 'make_type', 'engine_motor_no', 'chassis_no', 'plate_no', 'trnx_date', 'inc_desc', 'action'],
            tricycle_tableData: [],
            tricycle_options: {
                headings: {
                    body_number: 'Body Number',
                    make_type: 'Make Type',
                    engine_motor_no: 'Engine #',
                    chassis_no: 'Chassis #',
                    plate_no: 'Plate #',
                    trnx_date: 'Trans Date',
                    inc_desc: 'Last Annual Tax Paid',
                    action: 'Select',
                },
                sortable: [],
                filterable: false,
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
                texts: {
                    count: ''
                }
            },

            errors: [],

            err_msg: '',
            err: false,
            suc_msg: '',
            suc: false,

            loader: false,
            adding: false,
            print: false,

            other_inc_value: '',
            search_value: '',
            filter_value: '',
            mtop_tax: [],
            operator_data: [],
            tricycle_selected: [],


        }
    },
    methods: {

        displayStatus(status) {

            if(status === '1') {
                return 'FOR REVIEW';
            }

            if(status === '2') {
                return 'FOR PAYMENT';
            }

            if(status === '3') {
                return 'FOR APPROVAL';
            }

            if(status === '4') {
                return 'APPROVED';
            }

        },

        errorHandler(errors){

            console.log(errors);
            let error_handler = [];
            $.each(errors, function(key, value) {
                error_handler.push(value);
            });
            return error_handler;
        },

        returnSuccess(response) {
            $('#create_modal').modal('hide');
            this.suc = true;
            this.suc_msg = response.data.message;
        },

        returnFailed(error) {
            if(error.response.data.err_msg) {
                this.err = true;
                this.err_msg = error.response.data.err_msg;
                $('#create_modal').modal('hide');
                return;
            }
            this.errors = [];
            this.errors = this.errorHandler(error.response.data.errors);
        },

        closeMessageBox() {
            location.reload();
        },

        openModalToCreate(){
            this.adding = true;
            this.other_inc_value = '';
            this.search_value = '';
            this.filter_value = '';
            this.operator_data = [];
            this.tricycle_tableData = [];
            $('#create_modal').modal('show');
        },

        filterOnChange(e) {

            let placeholder = '';

            if(this.filter_value === 'body_number')
            {
                placeholder = 'Search Data By Body Number';
            }
            else
            {
                placeholder = 'Search Data By Operators Name (Last, First Middle)';
            }


            $('#search').attr('placeholder', placeholder);

        },

        resultOperator() {


            if(this.filter_value.length === 0)
            {
                alert('Search By is Required');
                return;
            }

            if(this.search_value.length === 0)
            {
                alert('Search Value is Required');
                return;
            }


            axios.get('annual_tax/search_operator/' + this.filter_value + '/' + this.search_value)
            .then(response => {
                this.operator_tableData = response.data.data;
                $('#operator_result').modal('show');
            })
        },

        selectOperator(id) {
            this.operator_data = this.operator_tableData[id];

            /* also get all the tricycles data under this operator */

            axios.get('annual_tax/get_tricycles/' + this.operator_data.id)
                .then(response => {
                    this.tricycle_tableData = response.data.data;
                })

            $('#operator_result').modal('hide');
        },

        // searchTricycle() {
        //
        //     if(this.filter_value.length === 0)
        //     {
        //         alert('Search By is Required');
        //         return;
        //     }
        //
        //     if(this.search_value.length === 0)
        //     {
        //         alert('Search Value is Required');
        //         return;
        //     }
        //
        //
        //     if(this.operator_data.length === 0)
        //     {
        //         alert('Must select Operator');
        //         return;
        //     }
        //
        //
        //     axios.get('annual_tax/get_tricycles/' + this.operator_data.id)
        //     .then(response => {
        //         this.result_tricycle_tableData = response.data.data;
        //     })
        //
        //     $('#tricycle_result').modal('show');
        //
        // },

        onTricyclesCheck(row, e) {
            this.tricycle_tableData[row.index - 1]['checked'] = e.target.checked;
        },

        // arraySearch(arr, val) {
        //     for(let i = 0; i < arr.length; i++)
        //     {
        //         if(arr[i].id === val)
        //         {
        //             return i;
        //         }
        //     }
        // },

        // selectTricycle() {
        //
        //     this.tricycle_tableData = [];
        //
        //     const dataArr = [];
        //
        //     for(let i = 0; i < this.tricycle_selected.length; i++)
        //     {
        //         dataArr[i] = {
        //             id : this.tricycle_selected[i].id,
        //             body_number : this.tricycle_selected[i].body_number,
        //             make_type : this.tricycle_selected[i].make_type,
        //             engine_motor_no : this.tricycle_selected[i].engine_motor_no,
        //             chassis_no : this.tricycle_selected[i].chassis_no,
        //             plate_no : this.tricycle_selected[i].plate_no,
        //             trnx_date : this.tricycle_selected[i].trnx_date,
        //             inc_desc : this.tricycle_selected[i].inc_desc
        //         };
        //     }
        //
        //     this.tricycle_tableData = dataArr;
        //     $('#tricycle_result').modal('hide');
        // },

        createRecord() {

            if(this.tricycle_tableData.length === 0)
            {
                alert('Must add Tricycle');
                return false;
            }

            let other_inc_id = this.other_inc_value;
            let checkCount = 0;
            let exitBool = false;
            let message = '';

            this.tricycle_tableData.map(function(item, key) {

                if(item.checked === true)
                {
                    checkCount++;
                }


                if(item.checked === true && item.otherinc_id === other_inc_id)
                {
                    message = 'The Selected Tricycle is Already Paid in the Selected Tax Year in row number ' + parseInt(key + 1);
                    exitBool = true;
                }

            });

            if(exitBool)
            {
                alert(message);
                return false;
            }

            if(checkCount === 0)
            {
                alert('Select Tricycle for Payment');
                return false;
            }


            axios.post('annual_tax/store', {
                operator_id : this.operator_data.id,
                otherinc_id : this.other_inc_value,
                tricycle_details : this.tricycle_tableData,
            }).then(response => {
                this.returnSuccess(response);
            }).catch(error => {
                if(error.response.data.invalid_msg.length > 0)
                {
                    alert('Tricycle Already Existing in Other Transaction');
                }
            });
        },

        editRecord(id)
        {
            this.adding = false;

            axios.get('annual_tax/edit/' + id)
            .then(response => {
                this.mtop_tax = response.data.data;
                this.operator_data = response.data.operator_data;
                this.other_inc_value = response.data.data.otherinc_id;
                this.tricycle_tableData = response.data.annual_details;
                $('#create_modal').modal('show');
            });

        },


        updateRecord() {

            if(this.tricycle_tableData.length === 0)
            {
                alert('Must add Tricycle');
                return false;
            }

            let other_inc_id = this.other_inc_value;
            let checkCount = 0;
            let exitBool = false;
            let message = '';

            this.tricycle_tableData.map(function(item, key) {

                if(item.checked === true)
                {
                    checkCount++;
                }


                if(item.checked === true && item.otherinc_id === other_inc_id)
                {
                    message = 'The Selected Tricycle is Already Paid in the Selected Tax Year in row number ' + parseInt(key + 1);
                    exitBool = true;
                }

            });

            if(exitBool)
            {
                alert(message);
                return false;
            }

            if(checkCount === 0)
            {
                alert('Select Tricycle for Payment');
                return false;
            }


            axios.patch('annual_tax/update', {
                id: this.mtop_tax.id,
                operator_id : this.operator_data.id,
                otherinc_id : this.other_inc_value,
                tricycle_details : this.tricycle_tableData,
            }).then(response => {
                this.returnSuccess(response);
            });
        },

        tagOrNumber(id) {
            this.tagOR = true;
            this.validity_update = false;
            this.applicationIdValue = id;
            $('#tag_or_modal').modal('show');
        },

        searchOR() {
            if (!this.or_no || this.or_no === '')
            {
                alert('OR Number is Required');
                return;
            }

            axios.get('mtop/or_finder/' + this.or_no)
                .then(response => {
                    this.orDetailsTableData = response.data.data;
                })
        },

        submitOrTag() {
            // axios.patch('mtop/tagOR', {
            //     or_no: this.orDetailsTableData[0].id,
            //     application_id : this.applicationIdValue
            // }).then(response => {
            //     this.suc = true;
            //     this.suc_msg = response.data.message;
            //
            //     $('#tag_or_modal').modal('hide');
            // })
            //     .catch(error => {
            //         this.err = true;
            //         this.err_msg = error.response.data.err_msg;
            //     })
            //     .finally(()=> this.loader = false);
        }

    },

    mounted() {

    }
}
</script>

<style scoped>
.table td, .table th {
    vertical-align: middle;
    font-size: 14px;
}

.table td button {
    font-size: 14px;
}
</style>
