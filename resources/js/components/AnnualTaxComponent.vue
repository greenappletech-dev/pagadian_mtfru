<template>
    <div class="main-container p-4">

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
                        <input class="mr-2 p-2 rounded border form-control" type="date" style="font-size: 14px;" v-model="fromDate">
                        <p class="text-bold" style="margin: 9px 5px 0 0;">To: </p>
                        <input class="mr-1 p-2 rounded border form-control" type="date" style="font-size: 14px;" v-model="toDate">
                        <button style="margin: 0; width: 120px; font-size: 14px;" class="btn-light rounded border-0 p-1 form-control" id="filter" v-on:click="initialData"><i class="fas fa-sync-alt"></i></button>
                    </div>
                </div>
                <div class="col-md-12 col-lg-12 col-xl-6">
                    <div class="d-flex justify-content-end">
                        <button style="margin: 0; width: 200px; font-size: 14px; background: rgba(255, 120, 31, 1); color: white;" class="btn rounded border-0 p-1 form-control" id="create" v-on:click="newTransaction">New</button>
                    </div>
                </div>
            </div>


            <div class="col-12 mt-2">
                <v-client-table
                    :data="table_data"
                    :columns="table_data_columns"
                    :options="table_data_options">
                    <template slot="action" slot-scope="props">
                        <button class="btn btn-success" style="font-size:13px" v-on:click="editData(props)">Edit</button>
                        <button class="btn btn-danger" style="font-size: 13px" v-on:click="deleteData(props)">Delete</button>
                    </template>
                </v-client-table>
            </div>
        </div>


        <div class="modal" id="modal-new">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="w-100 d-flex justify-content-between">
                            <h5>Add Transaction</h5>
                            <button class="btn btn-success" style="font-size: 13px" v-on:click="saveData">Save Data</button>
                        </div>

                    </div>
                    <div class="modal-body">

                        <div class="row">


                            <!--           SEARCH BODY NUMBER             -->


                            <div class="col-12 mb-2" style="font-size: 13px;">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search Body Number..." v-model="search_value" :disabled="is_edit">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" style="font-size: 13px;" v-on:click="searchOperator" :disabled="is_edit"><i class="fa fa-search" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                            </div>


                            <!--          DISPLAY TRICYCLE INFORMATION AND LAST ANNUAL TAX PAID              -->


                            <div class="col-6" style="font-size: 13px;">

                                <label>Transaction Date</label>
                                <input type="date" class="form-control" v-model="data_values.transaction_date">
                                <div class="text-danger" style="font-size: 13px" v-if="errors.transaction_date">{{ errors.transaction_date[0] }}</div>

                                <label>Operator Name</label>
                                <input type="text" class="form-control" v-model="data_values.full_name" readonly>

                                <label>Address</label>
                                <input type="text" class="form-control" v-model="data_values.address" readonly>

                                <label>Previous Annual Tax Paid</label>
                                <input type="text" class="form-control" v-model="data_values.annual_tax" readonly>

                            </div>



                            <div class="col-6" style="font-size: 13px;">

                                <label>Body Number</label>
                                <input type="text" class="form-control" v-model="data_values.body_number" readonly>

                                <label>Make Type</label>
                                <input type="text" class="form-control" v-model="data_values.make_type" readonly>

                                <label>Engine Number</label>
                                <input type="text" class="form-control" v-model="data_values.engine_motor_no" readonly>

                                <label>Chassis Number</label>
                                <input type="text" class="form-control" v-model="data_values.chassis_no" readonly>

                                <label>Plate Number</label>
                                <input type="text" class="form-control" v-model="data_values.plate_no" readonly>

                            </div>


                            <!--            CHARGES TABLE                -->


                            <div class="col-12 d-flex justify-content-between mt-3">
                                <div>
                                    <label>Totals:</label>
                                    <label id="totals">{{ totalCharges }}</label>
                                </div>
                                <button class="btn btn-primary" style="font-size: 13px;" v-on:click="addCharges">Add Charges</button>
                            </div>

                            <div class="col-12 mt-2" style="font-size: 13px;">
                                <v-client-table
                                    :data="charges_data"
                                    :columns="charges_data_columns"
                                    :options="charges_data_options">
                                    <template slot="action" slot-scope="props">
                                        <button class="btn btn-danger" v-on:click="removeCharges(props)">Remove</button>
                                    </template>
                                </v-client-table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal" id="modal-charges-list">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5>Select Charges</h5>
                    </div>
                    <div class="modal-body" style="font-size: 12px;">
                        <v-client-table
                            :data="charges_list_data"
                            :columns="charges_list_data_columns"
                            :options="charges_list_data_options">
                            <template slot="action" slot-scope="props" v-if="!props.row.selected">
                                <button class="btn btn-primary" style="font-size: 13px" v-on:click="selectCharges(props)">Select</button>
                            </template>
                        </v-client-table>
                    </div>
                </div>
            </div>
        </div>




    </div>
</template>

<script>

export default {

    props : ['otherinc'],

    data() {

        return {
            data_values : [],
            table_data : [],
            table_data_columns : ['transaction_date', 'body_number', 'operator', 'make_type', 'engine_motor_no', 'chassis_no', 'plate_no', 'status', 'action'],
            table_data_options : {
                headings : {
                    transaction_date : 'Transaction Date',
                    body_number : 'Body #',
                    operator : 'Operator',
                    make_type : 'Make Type',
                    engine_motor_no : 'Engine #',
                    chassis_no : 'Chassis #',
                    plate_no : 'Plate #',
                    status : 'Status',
                    action : 'Action',
                },
                filterable : false,
                sortable: [],
            },

            charges_data : [],
            charges_data_columns : ['inc_desc', 'price',  'action'],
            charges_data_options : {
                headings : {
                    inc_desc: 'Charge',
                    price: 'Price',
                    action: 'Action',
                },
                sortable: [],
                filterable : false,
            },

            charges_list_data : this.otherinc,
            charges_list_data_columns : ['inc_desc', 'price',  'action'],
            charges_list_data_options : {
                headings : {
                    inc_desc: 'Charge',
                    price: 'Price',
                    action: 'Select',
                },
                sortable: [],
                filterable : false,
            },

            is_edit : false,
            search_value : '',
            errors : [],
            fromDate : '',
            toDate : '',
        }
    },

    computed : {

        totalCharges() {

            let totals = 0;

            for(const key in Object.keys(this.charges_data)) {
                totals += parseFloat(this.charges_data[key].price)
            }

            return totals;
        }

    },

    methods : {

        initialData()
        {
            axios.get('annual_tax/initialData/' + this.fromDate + '/' + this.toDate).then(response => {
                this.table_data = response.data.data;
            })
        },

        newTransaction() {
            this.is_edit = false;
            this.data_values = [];
            this.charges_data = [];
            this.errors = [];
            $('#modal-new').modal('show');
        },

        searchOperator() {
            axios.get('annual_tax/search_tricycle/' + this.search_value).then(response => {
                this.data_values = response.data.data;
                this.charges_data = response.data.default_charges;
            })
        },

        addCharges() {
            $('#modal-charges-list').modal('show');
        },

        selectCharges(props) {

            let tableValue = this.charges_list_data[props.index -1];


            let obj = {
                id : tableValue.id,
                inc_desc : tableValue.inc_desc,
                price : tableValue.price,
            };

            this.charges_data.push(obj);

            $('#modal-charges-list').modal('hide');

        },

        removeCharges(props) {
            this.charges_data.splice(props.index - 1, 1);
        },

        saveData() {

            let message = '';

            if(this.data_values.length === 0)
            {
                message = 'Please Select Operator';
            }
            else if (this.charges_data.length === 0)
            {
                message = 'Please Add Charges';
            }

            if(message !== '')
            {
                alert(message);
                return false;
            }

            let dataToStore = {
                transaction_date : this.data_values.transaction_date,
                operator_id : this.data_values.operator_id,
                tricycle_id : this.data_values.tricycle_id,
                address : this.data_values.address,
                annual_tax : this.data_values.annual_tax,
                body_number : this.data_values.body_number,
                make_type : this.data_values.make_type,
                engine_motor_no : this.data_values.engine_motor_no,
                chassis_no : this.data_values.chassis_no,
                plate_no : this.data_values.plate_no,
                charges : this.charges_data,
            };

            console.log(dataToStore);


            if(typeof(this.data_values.id) !== 'undefined')
            {
                dataToStore.id = this.data_values.id;
            }


            axios.post('annual_tax/store', dataToStore).then(response => {
                if(response.status === 200)
                {
                    if(!alert('Data Successfully Saved!'))
                    {
                        location.reload();
                    }
                }
            }).catch(errors => {

                if(errors.response.status === 422)
                {
                    this.errors = errors.response.data.errors;
                }
                else
                {
                    alert('Something when wrong');
                }

            })

        },

        editData(props) {

            axios.get('annual_tax/edit/' + props.row.id).then(response => {
                if(response.status === 200)
                {
                    this.is_edit = true;
                    this.data_values = response.data.data;
                    this.charges_data = response.data.charges;
                    $('#modal-new').modal('show');
                }
            });

        },

        deleteData(props) {

            if(confirm('Are you sure to delete this record?'))
            {
                axios.get('annual_tax/destroy/' + props.row.id).then(response => {

                    if(response.status === 200) {
                        if(!alert('Record Deleted')) {
                            location.reload();
                        }
                    }

                })
            }
        },

        getDates() {
            const d = new Date();
            let month = d.getMonth() + 1;
            let day = d.getDate();
            let year = d.getFullYear();
            let firstAndToday = [];

            return firstAndToday = {
                first_date_of_year : [year, '01', '01'].join('-') ,
                date_today : [year, month, day].join('-')
            };

        },



    },

    mounted() {

        let getDateRange = this.getDates();
        this.fromDate = getDateRange.first_date_of_year;
        this.toDate = getDateRange.date_today;
        this.initialData();

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
