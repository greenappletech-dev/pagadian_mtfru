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
                        <input class="mr-2 p-2 rounded border form-control" type="date" style="font-size: 14px;" v-model="fromDateValue">
                        <p class="text-bold" style="margin: 9px 5px 0 0;">To: </p>
                        <input class="mr-1 p-2 rounded border form-control" type="date" style="font-size: 14px;" v-model="toDateValue">
                        <button style="margin: 0; width: 120px; font-size: 14px;" class="btn-light rounded border-0 p-1 form-control" id="filter"><i class="fas fa-sync-alt"></i></button>
                    </div>
                </div>
                <div class="col-md-12 col-lg-12 col-xl-6">
                    <div class="d-flex justify-content-end">
                        <button style="margin: 0; width: 200px; font-size: 14px; background: rgba(255, 120, 31, 1); color: white;" class="btn rounded border-0 p-1 form-control" v-on:click="openModalCreate" id="create">New</button>
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
                            <span slot="action" slot-scope="{row}">
                                <button v-if="row.status === 'PENDING'" class="btn btn-info mb-2" v-on:click="printStab(row.id)"><i class="fas fa-print mr-2"></i>Print Stab</button>
                                <button v-if="row.status === 'PENDING'" class="btn btn-danger mb-2" v-on:click="deleteStab(row.id)"><i class="fas fa-trash mr-2"></i>Delete Stab</button>
                            </span>
                        </v-client-table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="transaction-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">New Transaction</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="max-height: 500px; overflow: auto;">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Body Number" v-model="bodyNumber">
                            <div class="input-group-append">
                                <button style="width: 70px; background: #b9bbbe; border: 1px solid #b9bbbe;" class="rounded-right" v-on:click="searchBodyNumber"><i class="fas fa-search"></i></button>
                            </div>
                        </div>

                        <div class="tricycle_details" v-if="tricycleInfo.length !== 0">
                            <div class="row">
                                <div class="col-5 mb-2">
                                    Operator's Name:
                                </div>
                                <div class="col-7">
                                    <strong>{{ tricycleInfo.full_name }}</strong>
                                </div>

                                <div class="col-5 mb-2">
                                    Body Number:
                                </div>
                                <div class="col-7">
                                    <strong>{{ tricycleInfo.body_number }}</strong>
                                </div>

                                <div class="col-12 mb-2">
                                    <select id="annualtax" v-model="annualTaxValue" class="form-control">
                                        <option v-for="data in annualtax" v-bind:value="data.id">{{ data.inc_desc }}</option>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <button class="form-control btn btn-info" v-on:click="proceedToPayment">Proceed to Payment</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>

export default {

    props: ['annualtax'],

    data() {
        return {
            columns: [ 'transact_date', 'body_number', 'full_name', 'inc_desc', 'status', 'created_at', 'updated_at','action'],
            tableData: [],
            options: {
                headings: {
                    transact_date: 'Transaction Date',
                    body_number: 'Body Number',
                    full_name: 'Operator',
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

            errors: [],
            err_msg: '',
            err: false,
            suc_msg: '',
            suc: false,
            loader: false,

            fromDateValue: '',
            toDateValue: '',


            bodyNumber: '',
            annualTaxValue: this.annualtax[0].id,
            tricycleInfo: [],
        }
    },
    methods: {

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

        closeMessageBox() {
            this.err_msg = '';
            this.suc_msg = '';
            this.suc = false;
            this.err = false;
            location.reload();
        },

        returnSuccess(response) {
            $('#transaction-modal').modal('hide');
            this.suc = true;
            this.suc_msg = response.data.message;
        },

        returnFailed(error) {
            if(error.response.data.err_msg) {
                this.err = true;
                this.err_msg = error.response.data.err_msg;
                $('#transaction-modal').modal('hide');
                return;
            }
            this.errors = [];
            this.errors = this.errorHandler(error.response.data.errors);
        },

        getDataFiltered(){
            axios.get('annualtax/filter/' + this.fromDateValue + '/' + this.toDateValue)
            .then(response => {
                this.tableData = response.data.list;
            });
        },

        openModalCreate() {
            $('#transaction-modal').modal('show');
        },

        searchBodyNumber() {
            axios.get('searchBodyNumber/'  + this.bodyNumber)
                .then(response => {
                    this.tricycleInfo = response.data.data;
                })
                .catch(error => {
                    this.returnFailed(error);
                });
        },

        proceedToPayment() {
            axios.post('annualtax/save', {
                tricycle_id : this.tricycleInfo.id,
                otherinc_id : this.annualTaxValue
            }).then(response => {
                this.returnSuccess(response);
            }).catch(error => {
                this.returnFailed(error);
            });
        },

        printStab(id) {
            window.open('annualtax/stab/' + id);
        },

        deleteStab(id) {
            axios.get('annualtax/destroy/' + id)
            .then(response => {
                this.returnSuccess(response);
            })
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
