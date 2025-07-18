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
                <li class="d-inline align-middle" style="font-size: 12px">Reports</li>
                <li class="d-inline align-middle mr-1 ml-1" style="font-size: 5px; color: #95a5a6;"><i class="fas fa-circle"></i></li>
                <li class="d-inline align-middle" style="font-size: 12px">Annual Tax</li>
            </ul>
        </div>

        <div class="main-content">
            <div class="card">
                <div class="card-header">
                    Search Option
                </div>
                <div class="card-body">
                    <div class="row">
                        <!--                <div class="col-12 col-xl-6 mb-2">-->
                        <!--                    <label style="font-size: 14px; margin: 0" for="year">Body Number</label>-->
                        <!--                    <div style="position: relative;" class="input-group">-->
                        <!--                        <input type="text" id="year" maxlength="4" class="form-control text-uppercase" v-model="filters.body_number">-->
                        <!--                        <div class="input-group-append">-->
                        <!--                            <button v-on:click="filter" style="margin: 0; width: 50px; font-size: 14px;" class="btn-info rounded-right border-0 p-1">-->
                        <!--                                <i class="fas fa-sync-alt"></i>-->
                        <!--                            </button>-->
                        <!--                        </div>-->
                        <!--                    </div>-->
                        <!--                </div>-->

<!--                        <div class="col-12 mb-2">-->
<!--                            <label>Search Option</label>-->
<!--                            <select class="form-control" v-model="filters.type">-->
<!--                                <option value="operator">Operator</option>-->
<!--                                <option value="body_no">Body Number</option>-->
<!--                            </select>-->
<!--                        </div>-->

                        <div class="col-12 mb-2">
                            <label>Operator</label>
                            <div style="position: relative;" class="input-group">
                                <input type="text" class="form-control text-uppercase" v-model="operator" readonly="readonly">
                                <div class="input-group-append">
                                    <button v-on:click="openModalOperatorSearch" style="margin: 0; width: 40px;" class="btn-info rounded-right border-0 p-1">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mb-2">
                            <label>Tax year</label>
                            <input type="number" class="form-control" v-model="taxyear">
                        </div>

                        <div class="col-12 mb-2" v-if="filters.type === 'body_no'">
                            <label>Body Number</label>
                            <div style="position: relative;" class="input-group">
                                <input type="text" maxlength="4" class="form-control text-uppercase" v-model="filters.body_number">
                                <div class="input-group-append">
                                    <button v-on:click="filter" style="margin: 0;" class="btn-info rounded-right border-0 p-1">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-12 text-right">
                            <a class="p-2 pl-4 pr-4 rounded" style="cursor: pointer; background: rgba(255, 120, 31, 1); font-size: 14px; color: #fff; text-decoration: none;" v-on:click="filter">Filter Data</a>
                            <a class="p-2 pl-4 pr-4 rounded" style="cursor: pointer; background: #1abc9c; font-size: 14px; color: #fff; text-decoration: none;" v-on:click="exportExcel">Export to Excel</a>
                            <a class="p-2 pl-4 pr-4 rounded" style="cursor: pointer; background: #1abc9c; font-size: 14px; color: #fff; text-decoration: none;" v-on:click="pdfModal">Print to PDF</a>
                        </div>
                    </div>
                </div>
            </div>

            <!--   Table     -->
            <div class="main-content-table" style="padding-top: 11px">
                <div class="row">
                    <div class="col-md-12">
                        <v-client-table
                            :data="tableData"
                            :columns="columns"
                            :options="options">
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
                        <h5 class="modal-title" v-if="operator_search">Search Operator</h5>
                        <h5 class="modal-title" v-else>Print Forms</h5>
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

                        <div class="form-group" v-else>
                            <div style="position: relative;" class="input-group mb-2">
                                <input type="text" class="form-control text-uppercase" v-model="filters.operator_name">
                                <div class="input-group-append">
                                    <button v-on:click="findOperator" style="margin: 0; width: 40px;" class="btn-info rounded-right border-0 p-1">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>
                            </div>
                            <v-client-table
                                :data="tableData_operator"
                                :columns="columns_operator"
                                :options="options_operator">
                                <span slot="action" slot-scope="{row}">
                                    <button v-on:click="selectOperator(row.taxpayer_id, row.operator)" class="btn btn-primary mb-2" style="font-size: 12px">Select</button>
                                </span>
                            </v-client-table>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button v-if="print" @click="pdfPrint" type="button" class="rounded pl-3 pr-3 pt-2 pb-2 border-0" style="width: 100px; font-size: 14px; background: #1abc9c; color: #fff;">Print</button>
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
            columns: ['body_number', 'trnx_date', 'or_number', 'inc_desc', 'amount', 'operator','mtfrb_case_no', 'validity_date'],
            tableData: [],
            options: {
                headings: {
                    body_number         :       'Body Number',
                    trnx_date           :       'OR Date',
                    or_number           :       'OR Number',
                    inc_desc            :       'Charge',
                    amount              :       'Amount',
                    operator            :       'Operator',
                    mtfrb_case_no       :       'MTFRB Case #',
                    validity_date       :       'Validity Date'

                },
                sortable: ['body_number', 'trnx_date', 'or_number', 'inc_desc', 'operator', 'mtfrb_case_no'],
                filterable: false,
                templates: {
                    trnx_date: function(h, row) {
                        return row.trnx_date !== null ? moment(row.trnx_date).format('YYYY-MM-DD') : null;
                    },
                },
                texts : {
                    filter: 'Search:',
                },
            },

            columns_operator: ['operator', 'action'],
            tableData_operator: [],
            options_operator: {
                headings: {
                    operator           :       'Name',
                    action             :       'Action',
                },
                sortable: ['operator'],
                filterable: false,
                templates: {
                    trnx_date: function(h, row) {
                        return row.trnx_date !== null ? moment(row.trnx_date).format('YYYY-MM-DD') : null;
                    },
                },
                texts : {
                    filter: 'Search:',
                },

            },

            //dropdowns
            errors: [],
            filters: [],
            operator_search: false,
            operator: '',
            operator_id: '',
            taxyear: '',

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
            this.err = false;
            this.suc = false;
            this.adding = false;
            this.print = false;
            this.err_msg = '';
            this.suc_msg = '';
        },

        filter() {

            /* optional filters */

            if(!this.operator_id)
            {
                this.err_msg = 'Select Operator First!';
                this.err = true;
                return;
            }
            //
            // if(!this.taxyear)
            // {
            //     this.err_msg = 'Please select Tax year!';
            //     this.err = true;
            //     return;
            // }


            axios.get('mtop_charges_list/filter_operator/' + this.operator_id)
            .then(response => {
                if(response.data.charges.length === 0) {
                    this.err_msg = 'No record found!';
                    this.err = true;
                    return;
                }

                this.tableData = response.data.charges;

            })
            .finally(() => {
                this.loader = false;
            });
        },

        openModalOperatorSearch() {
            this.operator_search = true;
            $('#create-modal').modal('show');
        },

        findOperator() {
            axios.get('mtop_changes_list/search/' + this.filters.operator_name).then(response => {
                this.tableData_operator = response.data.operator;
            });
        },

        selectOperator(id, name) {
            this.operator_id = id;
            this.operator = name;
            $('#create-modal').modal('hide');
        },

        pdfModal() {
            this.print = true;
            this.errors = [];
            $('#create-modal').modal('show');
        },

        pdfPrint() {
            window.open('mtop_charges_list/pdf/' +  this.operator_id + '/' + this.taxyear + '/' +  this.paperSize + '/' + this.paperOrientation);
            $('#create-modal').modal('hide');
            this.print = false;
        },

        exportExcel(){
            window.open('mtop_charges_list/export/' +  this.operator_id + '/' + this.taxyear);
        },
    },

    mounted() {

    }
}
</script>
