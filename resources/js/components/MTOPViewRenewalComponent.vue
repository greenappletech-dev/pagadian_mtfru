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
                <li class="d-inline align-middle" style="font-size: 12px">MTFRU</li>
                <li class="d-inline align-middle mr-1 ml-1" style="font-size: 5px; color: #95a5a6;"><i class="fas fa-circle"></i></li>
                <li class="d-inline align-middle" style="font-size: 12px; color: #95a5a6">List of Renewals</li>
            </ul>
        </div>

        <div class="main-content">

            <h2 class="d-inline align-middle" style="font-size: 17px;">List of Renewals</h2>

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
                        <button v-on:click="exportExcel" class="p-2 pl-4 pr-4 rounded border-0 d-inline-block form-control" style="background: #1abc9c; color: #fff; font-size: 14px; box-shadow: 2px 2px 2px rgba(165, 165, 165, 1)">Export Excel</button>
                    </div>
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
                            <span slot="amount" slot-scope="{row}">{{ formatPrice(row.amount) }}</span>
                            <span slot="action" slot-scope="{row}">
                                <button class="btn btn-warning d-inline-block" v-on:click="storeForRenewal(row.application_id)"><i class="fas fa-check mr-1"></i>Renew</button>
                            </span>
                        </v-client-table>
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
            columns: [ 'mtfrb_case_no', 'transact_date', 'body_number', 'make_type', 'engine_motor_no', 'chassis_no', 'plate_no', 'full_name', 'address', 'barangay', 'approve_date', 'valid_until', 'or_no', 'amount', 'action'],
            tableData: [],
            options: {
                headings: {
                    mtfrb_case_no: 'MTFRB #',
                    transact_date: 'Application Date',
                    body_number: 'Body Number',
                    make_type: 'Make Type',
                    engine_motor_no: 'Engine Motor #',
                    chassis_no: 'Chassis #',
                    plate_no: 'Plate #',
                    full_name: 'Operator',
                    address: 'Address',
                    barangay: 'Barangay',
                    approve_date: 'Date Issued',
                    valid_until: 'Valid Until',
                    or_no: 'OR #',
                    amount: 'Amount',
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
                    approve_date: function(h, row) {
                        return row.transact_date !== null ? moment(row.approve_date).format('MM-DD-YYYY') : null;
                    },
                    valid_until: function(h, row) {
                        return row.transact_date !== null ? moment(row.valid_until).format('MM-DD-YYYY') : null;
                    },
                },
                texts : {
                    filter: 'Search:',
                },

            },

            barangayCodeTableData: [],

            barangayCodeValue: '',
            err_msg: '',
            err: false,
            suc_msg: '',
            suc: false,

            toDateValue: null,
            fromDateValue: null,

            loader: false,
            adding: false,
            print: false,
            paperSize: 'Letter',
            paperOrientation: 'Portrait',
        }
    },
    methods: {

        initialData() {
            axios.get('mtop_renewal/getdata').then(response => {
                this.barangayCodeTableData = response.data.barangays;
            });
        },

        formatPrice(value) {
            let val = (value/1).toFixed(2).replace(',', '.')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },

        openEntryForRenewal(id){
            console.log(id);
            // window.location.href = 'mtop_renewal/renew/' + id;
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

        getDataFiltered() {
            this.loader = true;
            this.tableData = [];
            let optional_route = this.barangayCodeValue === '' ? '/null' : '/'  + this.barangayCodeValue;
            axios.get('mtop_renewal/getdata_filtered/' + this.fromDateValue + '/' + this.toDateValue + optional_route)
            .then(response => {
                this.tableData = response.data.mtop_applications;
            })
            .finally(()=> this.loader = false);
        },

        storeForRenewal(id) {
            location.href = 'mtop_entry/renew/' + id;
        },

        exportExcel() {
            let optional_route = this.barangayCodeValue === '' ? '/null' : '/'  + this.barangayCodeValue;
            window.open('mtop_renewal/export/' + this.fromDateValue + '/' + this.toDateValue + optional_route);
        },

    },

    mounted() {
        this.initialData();
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
