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
            <div class="col-6 p-0">
                <div class="card">
                    <div class="card-body">
                        <label>Search Operator/Tricycle</label>
                        <div class="input-group">
                            <select class="form-control" v-on:change="onChangeSearchOption" v-model="filter_value">
                                <option value="operator">Operator</option>
                                <option value="body_number">Body Number</option>
                            </select>
                            <div class="input-group-append">
                                <span class="input-group-text" style="font-size: 12px">Search By</span>
                            </div>
                        </div>

                        <input type="text" class="form-control mt-2" id="search_value" v-model="search_value">

                        <div class="w-100 mt-2 text-right">
                            <button class="btn btn-secondary" style="font-size: 13px" v-on:click="searchOperator"><i class="fa fa-search mr-2"></i>View Result</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 p-0">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Operator Name</label>
                            <input class="form-control" type="text" disabled v-bind:value="operator_data.full_name">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 p-0">
                <div class="card">
                    <div class="card-header">
                        <h4 style="font-size: 18px; margin: 0;">Annual Tax Transaction List</h4>
                    </div>
                    <div class="card-body">
                        <v-client-table
                            :data = "tableData"
                            :options = "options"
                            :columns = "columns">
                        </v-client-table>
                        <div class="w-100 text-right">
                            <button class="btn btn-info" style="font-size: 13px" v-on:click="openModalPdf">Print Filter</button>
                            <button class="btn btn-info" style="font-size: 13px" v-on:click="downloadExcel">Export to Excel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- operator search --->

        <div class="modal" id="modal-operator">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="w-100 d-flex justify-content-between align-items-center">
                            <h4 style="font-size: 18px; margin: 0;">Operator Result</h4>
                            <button class="border-0" data-dismiss="modal" style="background: none">&times;</button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <v-client-table
                            :data="tableData_operator"
                            :options="options_operator"
                            :columns="columns_operator">
                            <template slot="action" slot-scope="row">
                                <button class="btn btn-info" style="font-size: 13px" v-on:click="selectOperator(row.index - 1)"><i class="fa fa-check mr-2"></i>Select</button>
                            </template>
                        </v-client-table>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" style="font-size: 13px"><i class="fa fa-check mr-2"></i> Select Operator</button>
                    </div>
                </div>
            </div>
        </div>


        <!--- print --->
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
                        <div class="form-group">
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
                        <button v-on:click="printPdf" type="button" class="rounded pl-3 pr-3 pt-2 pb-2 border-0" style="width: 100px; font-size: 14px; background: #1abc9c; color: #fff;">Print</button>
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
            columns: ['body_number', 'trnx_date', 'or_number', 'inc_desc', 'ln_amnt'],
            tableData: [],
            options: {
                headings: {
                    body_number         :       'Body Number',
                    trnx_date           :       'OR Date',
                    or_number           :       'OR Number',
                    inc_desc            :       'Charge',
                    ln_amnt             :       'Amount',
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
                    count: '',
                },
            },

            columns_operator: ['id','full_name', 'action'],
            tableData_operator: [],
            options_operator: {
                headings: {
                    id                 :       'ID',
                    full_name          :       'Name',
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
            operator_data: [],
            search_value: '',
            filter_value: '',

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

        onChangeSearchOption(e) {
            let placeholder = '';
            placeholder = e.target.value === 'body_number' ? 'Search by Body Number' : 'Search by Operator';
            this.tableData = [];
            this.search_value = '';
            this.operator_data = '';
            $('#search_value').attr('placeholder', placeholder);
        },

        searchOperator() {

            if(this.filter_value === 'body_number')
            {
                axios.get('tax_report/get_data/'+this.filter_value+'/' + this.search_value)
                    .then(response => {

                        let operatorArr = {
                            full_name: response.data.data[0].full_name
                        };

                        this.operator_data = operatorArr;
                        this.tableData = response.data.data;
                    });
            }
            else if (this.filter_value === 'operator')
            {
                axios.get('tax_report/search_operator/' + this.search_value)
                    .then(response => {
                        this.tableData_operator = response.data.data;
                    });

                $('#modal-operator').modal('show');
            }
        },

        selectOperator(rowindex) {

            this.operator_data = this.tableData_operator[rowindex];
            let search_data = this.filter_value === 'body_number' ? this.search_value : this.operator_data.id;


            axios.get('tax_report/get_data/'+this.filter_value+'/' + search_data)
                .then(response => {
                    this.tableData = response.data.data;
                });


            $('#modal-operator').modal('hide');
        },

        openModalPdf() {
            $('#print-modal').modal('show');
        },

        printPdf() {
            let search_data = this.filter_value === 'body_number' ? this.search_value : this.operator_data.id;
            window.open('tax_report/pdf/'+this.filter_value+'/' + search_data + '/' + this.paperSize + '/' + this.paperOrientation);
        },

        downloadExcel() {
            let search_data = this.filter_value === 'body_number' ? this.search_value : this.operator_data.id;
            window.location.href = 'tax_report/export/'+this.filter_value+'/' + search_data;
        }




    },

    mounted() {

    }
}
</script>
