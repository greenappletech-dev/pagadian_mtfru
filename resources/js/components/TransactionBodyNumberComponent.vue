<template>
    <div class="main-container p-4">
        <!-- Loader -->
        <div style="position: absolute; top: 0; left: 0; z-index: 1000; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.2);" v-if="loader">
            <div style="position: fixed; top: 450px; left: 55%; transform:translate(-50%, -70%)">
                <img src="public/loader/loader.gif" alt="loader">
            </div>
        </div>

        <!-- Message Box -->
        <div style="position: absolute; top: 0; left: 0; z-index: 1000; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.2);" v-if="err || suc">
            <div style="position: fixed; top: 450px; left: 55%; transform:translate(-50%, -70%)">
                <div style="width: 500px; background: #F2F4F4;" class="p-3">
                    <div class="message-header p-2 border-bottom d-flex justify-content-between" style="background: #F2F4F4;">
                        <h3 style="margin: 0; font-size: 15px; font-weight: bold" v-if="err">Error Message</h3>
                        <h3 style="margin: 0; font-size: 15px; font-weight: bold" v-if="suc">Success</h3>
                    </div>

                    <div class="message_box p-2 d-flex justify-content-between" style="position: relative" v-if="err">
<img style="width: 80px" class="mt-1 ml-1" src="/image/icons/warning.png" alt="error">
                        <h1 style="font-size: 17px; position: absolute; top: 20px; left: 115px;">{{ err_msg }}</h1>
                    </div>

                    <div class="message_box p-2 d-flex justify-content-between" style="position: relative" v-if="suc">
<img style="width: 80px" class="mt-1 ml-1" src="/image/icons/success.png" alt="success">
                        <h1 style="font-size: 17px; position: absolute; top: 20px; left: 115px;">{{ suc_msg }}</h1>
                    </div>

                    <div class="" style="position: absolute; bottom:20px; right:15px">
                        <button v-on:click="closeMessageBox" style="width: 100px;" class="btn btn-primary p-2">Ok</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="card">
                <div class="card-header text-white" style="background-color: #fd7e14;">
                    <h4 class="mb-0">Transaction Search</h4>
                </div>
                
                <div class="card-body">
                    <!-- Search Section -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Search Operator/Tricycle</label>
                                <div class="input-group">
                                    <select class="form-control" v-on:change="onChangeSearchOption" v-model="filter_value">
                                        <option value="operator">Operator</option>
                                        <option value="body_number">Body Number</option>
                                    </select>
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-light">Search By</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Search Value</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="search_value" v-model="search_value" placeholder="Enter search term">
                                    <div class="input-group-append">
                                        <button class="btn search-btn" v-on:click="searchOperator" :disabled="!search_value">
                                            <i class="fa fa-search mr-2"></i>Search
                                        </button>
                                        <button class="btn btn-outline-secondary" v-on:click="resetSearch" v-if="search_value">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Transaction List Section -->
                    <div class="mt-4">
                        <h5 class="font-weight-bold border-bottom pb-2 mb-3" style="color: #6c757d; border-bottom: 1px solid #dee2e6;">Transaction List</h5>
                        <div class="table-responsive">
                            <v-client-table
                                :data="tableData"
                                :options="options"
                                :columns="columns">
                            </v-client-table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Operator Search Modal -->
        <div class="modal" id="modal-operator">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header text-white" style="background-color: #fd7e14;">
                        <div class="w-100 d-flex justify-content-between align-items-center">
                            <h4 style="font-size: 18px; margin: 0;">Operator Result</h4>
                            <button class="border-0 text-white" data-dismiss="modal" style="background: none; font-size: 1.5rem;">&times;</button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <v-client-table
                            :data="tableData_operator"
                            :options="options_operator"
                            :columns="columns_operator">
                            <div slot="empty-results" class="empty-results">
                                <i class="fas fa-search fa-3x mb-3" style="color: #6c757d;"></i>
                                <p class="h5">No Transaction Found</p>
                                <p class="text-muted">Try adjusting your search criteria</p>
                            </div>
                            <template slot="action" slot-scope="row">
                                <button class="btn btn-info btn-sm" v-on:click="selectOperator(row.index - 1)">
                                    <i class="fa fa-check mr-2"></i>Select
                                </button>
                            </template>
                        </v-client-table>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal">Close</button>
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
            columns: ['body_number', 'trnx_date', 'or_number', 'ln_amnt','trans_type', 'full_name', 'inc_desc'],
            tableData: [],
            options: {
                headings: {
                    body_number: 'Body Number',
                    trnx_date: 'Date of OR',
                    or_number: 'OR Number',
                    ln_amnt: 'Amount',
                    trans_type: 'Transaction Type',
                    full_name: 'Name of Operator',
                    inc_desc: 'Charge',
                },
                sortable: ['body_number', 'trnx_date', 'or_number', 'inc_desc', 'operator', 'mtfrb_case_no'],
                filterable: false,
                templates: {
                    trnx_date: function(h, row) {
                        return row.trnx_date !== null ? moment(row.trnx_date).format('YYYY-MM-DD') : null;
                    },
                },
                texts: {
                    filter: 'Search:',
                    count: '',
                },
            },

            columns_operator: ['id', 'full_name', 'action'],
            tableData_operator: [],
            options_operator: {
                headings: {
                    id: 'ID',
                    full_name: 'Name',
                    action: 'Action',
                },
                sortable: ['operator'],
                filterable: false,
                templates: {
                    trnx_date: function(h, row) {
                        return row.trnx_date !== null ? moment(row.trnx_date).format('YYYY-MM-DD') : null;
                    },
                },
                texts: {
                    filter: 'Search:',
                },
            },

            //dropdowns
            errors: [],
            filters: [],
            operator_data: [],
            search_value: '',
            filter_value: 'operator',

            err_msg: '',
            err: false,
            suc_msg: '',
            suc: false,

            loader: false,
            adding: false,
        }
    },
    methods: {
        formatPrice(value) {
            let val = (value/1).toFixed(2).replace(',', '.')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },

        errorHandler(errors) {
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
        resetSearch(){
            this.search_value = '';
            this.tableData = [];
        },

        onChangeSearchOption(e) {
            let placeholder = '';
            placeholder = e.target.value === 'body_number' ? 'Enter body number' : 'Enter operator name';
            this.tableData = [];
            this.search_value = '';
            this.operator_data = '';
            $('#search_value').attr('placeholder', placeholder);
        },

        searchOperator() {
            if (!this.search_value) {
                this.err = true;
                this.err_msg = 'Please enter a search term';
                return;
            }

            if (this.filter_value === 'body_number') {
                axios.get('tax_report/get_data/' + this.filter_value + '/' + this.search_value)
                    .then(response => {
                        let operatorArr = {
                            full_name: response.data.data[0].full_name
                        };
                        this.operator_data = operatorArr;
                        this.tableData = response.data.data;
                    });
            } else if (this.filter_value === 'operator') {
                axios.get('tax_report/search_operator/' + this.search_value)
                    .then(response => {
                        this.tableData_operator = response.data.data;
                        if (this.tableData_operator.length > 0) {
                            $('#modal-operator').modal('show');
                        } else {
                            this.err = true;
                            this.err_msg = 'No operators found with that name';
                        }
                    });
            }
        },

        selectOperator(rowindex) {
            this.operator_data = this.tableData_operator[rowindex];
            let search_data = this.filter_value === 'body_number' ? this.search_value : this.operator_data.id;

            axios.get('tax_report/get_data/' + this.filter_value + '/' + search_data)
                .then(response => {
                    this.tableData = response.data.data;
                });

            $('#modal-operator').modal('hide');
        },
    },

    mounted() {
        this.filter_value = 'operator';
        this.onChangeSearchOption({target: {value: 'operator'}});
    }
}
</script>

<style scoped>
.main-container {
    margin-top: 80px;
    display: flex;
    justify-content: center;
}

.card {
    width: 100%;
    max-width: 1400px;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    border: none;
}

.card-header {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    background-color: #fd7e14 !important;
}

.search-btn{
    background-color: #28a745;
    border-color: #28a745;
}

.search-btn:hover{
    background-color: #218838;
    border-color: #1e7e34;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.form-control, .input-group-text {
    font-size: 0.875rem;
}

.btn {
    font-size: 0.875rem;
    padding: 0.375rem 0.75rem;
}

.table-responsive {
    min-height: 300px;
}

.VueTables__table {
    width: 100% !important;
}

.VueTables__heading {
    font-weight: 600 !important;
    background-color: #f8f9fa !important;
    color: #495057 !important;
}

.VueTables__table tr:nth-child(even){
    background-color: #f8f9fa;
}

.VueTables__no-results{
    text-align: center !important;
    padding: 2rem !important;
    color: #6c757d !important;
    font-size: 1.1 rem !important;
}

:empty-results{
    min-height: 200px;
    display: flex; 
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.btn, .form-control{
    transition: all 0.2s ease;
}

.VueTables__table th {
    border-top: none !important;
}
</style>