<style>
.main-container .main-content .card .card-body .row .operator-info {
    padding-right: 50px;
    border-right: 1px solid #b9bbbe;
}

.main-container .main-content .card .card-body .row .tricycle-info {
    padding-left: 50px;
}

.VueTables__limit-field {
    display: none;
}


@media (max-width:1000px) {
    .main-container .main-content .card .card-body .row .operator-info, .main-container .main-content .card .card-body .row .tricycle-info {
        padding: 0;
        border: none;
    }
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

                    <div class="message_box text-center p-3" v-if="err">
                        <h1 style="font-size: 17px;">{{ err_msg }}</h1>
                    </div>

                    <div class="message_box text-center p-3" v-if="suc">
                        <h1 style="font-size: 17px;">{{ suc_msg }}</h1>
                    </div>

                    <button v-on:click="closeMessageBox" class="form-control btn btn-primary p-2">Ok</button>
                </div>

            </div>

        </div>

        <div class="user-button d-flex justify-content-between pt-3 pb-3">
            <ul class="breadcrumb-1 pl-0 m-0">
                <li class="d-inline align-middle" style="font-size: 12px">MTFRU</li>
                <li class="d-inline align-middle mr-1 ml-1" style="font-size: 5px; color: #95a5a6;"><i class="fas fa-circle"></i></li>
                <li class="d-inline align-middle" style="font-size: 12px">MTOP</li>
                <li class="d-inline align-middle mr-1 ml-1" style="font-size: 5px; color: #95a5a6;"><i class="fas fa-circle"></i></li>
                <li class="d-inline align-middle" style="font-size: 12px; color: #95a5a6">New Application</li>
            </ul>
        </div>

        <div class="main-content text align-center">

            <div class="text-danger" v-if="errors.length > 0">
                <ul>
                    <li v-for="error in errors">{{ error[0] }}</li>
                </ul>
            </div>

            <div class="card" style="width: 100%">
                <div class="card-header">
                    <h2 style="font-size: 17px; margin: 0;">New Application</h2>
                    <button v-on:click="storeRecord" style="position: absolute; top: 7px; right: 20px; color: #fff; font-size: 14px; background: #357ec7" class="p-1 pl-3 pr-3 rounded border-0">Save Record</button>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="operator-info col-lg-6">

                            <label for="transaction_date">Transaction Date</label>
                            <input type="text" id="transaction_date" readonly="readonly" v-bind:value="currentDate()" class="form-control">

                            <label for="mrfrb_case_no">MTFRB Case #</label>
                            <input type="text" id="mrfrb_case_no" class="form-control" v-model="MTFRBCaseValue">

                            <label for="operator">Operator</label>
                            <div class="col-12 p-0">
                                <input v-model="operatorValue" readonly="readonly" id="operator" type="text" style="padding-right: 80px; padding-right: 80px;" class="form-control d-inline-block">
<!--                                <button v-on:click="openModalToSearch" style="position: absolute; top: 0; right: 0; height: 100%; width: 70px; font-size: 12px; background: #b9bbbe; border: 1px solid #b9bbbe;" class="rounded-right">Find</button>-->
                            </div>

                            <label for="address">Address</label>
                            <input type="text" id="address" class="form-control" v-model="addressValue" readonly="readonly">

                            <label for="barangay">Barangay</label>
                            <select class="d-inline-block rounded border p-2 form-control" id="barangay" v-model="barangayIdValue" style="font-size: 14px;" disabled="disabled">
                                <option value="">Select Barangay (Optional)</option>
                                <option v-for="barangay in barangayCodeTableData" v-bind:value="barangay.id">{{ barangay.brgy_code + '-' + barangay.brgy_desc }}</option>
                            </select>
                        </div>

                        <div class="tricycle-info col-lg-6">

                            <label for="body_number">Body Number</label>
                            <input type="text" id="body_number" class="form-control" readonly="readonly" v-model="bodyNumberValue">

                            <label for="make_type">Make Type</label>
                            <input type="text" id="make_type" class="form-control" readonly="readonly" v-model="makeTypeValue">

                            <label for="engine_no">Engine Motor #</label>
                            <input type="text" id="engine_no" class="form-control" readonly="readonly" v-model="engineMotorNo">

                            <label for="chassis_no">Chassis #</label>
                            <input type="text" id="chassis_no" class="form-control" readonly="readonly" v-model="chassisNoValue">

                            <label for="plate_no">Plate #</label>
                            <input type="text" id="plate_no" class="form-control" readonly="readonly" v-model="plateNoValue">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2 style="font-size: 17px; margin: 0;">Select Transaction Type</h2>
                </div>

                <div class="card-body">

                    <div class="card">
                        <div class="card-header d-flex justify-content-start">

                            <label
                                class="d-flex justify-content-start"
                                for="chk_renewal"
                                style="width: 100%;
                                height: 100%;
                                margin: 0">

                                <input
                                    type="checkbox"
                                    v-model="renewal"
                                    v-on:click="checkRenewal"
                                    style="display: none"
                                    id="chk_renewal">

                                <span
                                    style="position: relative;
                                    width: 20px;
                                    height: 20px;"
                                    class="border rounded mr-2">
                                        <i
                                            id="new_check_icon"
                                            class="fas fa-check"
                                            style="display: none;
                                            position: absolute;
                                            top: 50%;
                                            left: 55%;
                                            transform: translate(-50%, -50%);
                                            font-size: 15px;
                                            color: #3ae374;">
                                        </i>
                                </span>

                                <h2 style="font-size: 17px; margin: 0;">New/Renewal</h2>

                            </label>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header d-flex justify-content-start">

                            <label
                                class="d-flex justify-content-start"
                                for="chk_dropping"
                                style="width: 100%;
                                height: 100%;
                                margin: 0">

                                <input
                                    type="checkbox"
                                    v-model="droppingValue"
                                    v-on:click="checkDropping"
                                    style="display: none"
                                    id="chk_dropping">

                                <span
                                    style="position: relative;
                                    width: 20px;
                                    height: 20px;"
                                    class="border rounded mr-2">
                                <i
                                    id="drop_check_icon"
                                    class="fas fa-check"
                                    style="display: none;
                                    position: absolute;
                                    top: 50%;
                                    left: 55%;
                                    transform: translate(-50%, -50%);
                                    font-size: 15px;
                                    color: #3ae374;">
                                </i>
                        </span>

                                <h2 style="font-size: 17px; margin: 0;">Dropping</h2>

                            </label>
                        </div>

                        <div class="card-body" id="dropping_details" style="position: relative; display: none">
                            <label>Please Select New Operator</label>
                            <div class="form-group">
                                <label for="new_operator">Operator Name</label>
                                <input
                                    id="new_operator"
                                    type="text"
                                    class="form-control"
                                    v-model="newOperatorValue"
                                    readonly="readonly">

                                <label for="new_operator_address">Address</label>
                                <input
                                    id="new_operator_address"
                                    type="text"
                                    class="form-control"
                                    v-model="newOperatorAddressValue"
                                    readonly="readonly">

                                <label for="new_operator_barangay">Barangay</label>
                                <select
                                    class="d-inline-block rounded border p-2 form-control"
                                    id="new_operator_barangay"
                                    v-model="newOperatorBarangayIdValue"
                                    style="font-size: 14px;"
                                    disabled="disabled">

                                    <option value="">
                                        Select Barangay (Optional)
                                    </option>

                                    <option
                                        v-for="barangay in barangayCodeTableData"
                                        v-bind:value="barangay.id">
                                        {{ barangay.brgy_code + '-' + barangay.brgy_desc }}
                                    </option>

                                </select>

                                <button
                                    v-on:click="openModalToSearchNewOperator"
                                    type="button"
                                    style="position: absolute;
                                top: 10px;
                                right: 20px;
                                font-size: 14px;
                                color: #fff;
                                background: #357ec7"
                                    class="p-1 pl-3 pr-3 rounded border-0">
                                    Find New Operator
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header d-flex justify-content-start">
                            <label
                                class="d-flex justify-content-start"
                                for="chk_change_unit"
                                style="width: 100%;
                                height: 100%;
                                margin: 0">

                                <input type="checkbox"
                                       v-model="changeUnitValue"
                                       v-on:click="checkChangeUnit"
                                       style="display: none"
                                       id="chk_change_unit">


                                <span style="position: relative; width: 20px; height: 20px;" class="border rounded mr-2">
                                    <i
                                        id="change_unit_icon"
                                        class="fas fa-check"
                                        style="display: none;
                                        position: absolute;
                                        top: 50%;
                                        left: 55%;
                                        transform: translate(-50%, -50%);
                                        font-size: 15px;
                                        color: #3ae374;">
                                    </i>
                                </span>

                                <h2 style="font-size: 17px; margin: 0;">Change Unit</h2>

                            </label>
                        </div>

                        <div class="card-body" id="change_unit_details" style="display: none;">
                            <label>New Tricycle Details</label>
                            <div class="row">

                                <div class="col-6">
                                    <label for="new_make_type">Make Type</label>
                                    <input
                                        type="text"
                                        id="new_make_type"
                                        class="form-control"
                                        v-model="newMakeTypeValue">

                                    <label for="new_engine_no">Engine Motor #</label>
                                    <input
                                        type="text"
                                        id="new_engine_no"
                                        class="form-control"
                                        v-model="newEngineMotorNo">
                                </div>

                                <div class="col-6">
                                    <label for="new_chassis_no">Chassis #</label>
                                    <input
                                        type="text"
                                        id="new_chassis_no"
                                        class="form-control"
                                        v-model="newChassisNoValue">

                                    <label for="new_plate_no">Plate #</label>
                                    <input
                                        type="text"
                                        id="new_plate_no"
                                        class="form-control"
                                        v-model="newPlateNoValue">
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>


            <div class="card">
                <div class="card-header">
                    <h2 style="font-size: 17px; margin: 0;">Charges</h2>
                    <h2 class="mt-2" style="font-size: 18px">Total/s: {{ formatPrice(transactionTotals) }}</h2>
                    <button v-on:click="openModalToAddCharges" style="position: absolute; top: 7px; right: 20px; color: #fff; font-size: 14px; background: #357ec7" class="p-1 pl-3 pr-3 rounded border-0">Add Charges</button>
                </div>

                <div class="card-body">
                    <v-client-table
                        :data="selectedChargesTableData"
                        :columns="selected_charge_column"
                        :options="selected_charge_option">
                        <span slot="price" slot-scope="{row}">
                            {{ formatPrice(row.price) }}
                        </span>
                        <span slot="action" slot-scope="props">
                            <button v-on:click="removeCharges(props.index, props.row.price)" class="btn btn-danger mb-2" style="font-size: 12px">Remove</button>
                        </span>
                    </v-client-table>
                </div>
            </div>
        </div>

        <!--        modal window    -->
        <div class="modal fade" id="search-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel" v-if="addCharges">Add Charges</h5>
                        <h5 class="modal-title" id="exampleModalLabel" v-else>Search Operator</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="max-height: 500px; overflow: auto;">
                        <div v-if="addCharges">
                            <v-client-table style="font-size: 15px"
                                            :data="chargesTableData"
                                            :columns="charges_column"
                                            :options="charges_option">
                                    <span slot="price" slot-scope="{row}">
                                        {{ formatPrice(row.price) }}
                                    </span>
                                <span slot="action" slot-scope="{row}">
                                        <button v-on:click="selectCharges(row.id)" class="btn btn-primary mb-2" style="font-size: 12px">Select</button>
                                    </span>
                            </v-client-table>
                        </div>
                        <div v-else>
                            <div class="col-12 p-0 mb-3">

                                <div v-if="searchNewOperator" class="form-group">
                                        <input v-model="searchNewValue" type="text" style="padding-right: 80px; text-transform: uppercase" class="form-control d-inline-block" placeholder="Find New Operator">
                                    <button v-on:click="findNewOperator" style="position: absolute; top: 0; right: 0; height: 100%; width: 70px; background: #b9bbbe; border: 1px solid #b9bbbe;" class="rounded-right"><i class="fas fa-search"></i></button>
                                </div>

                                <div v-else class="form-group">
                                    <input v-model="searchedValue" type="text" style="padding-right: 80px; text-transform: uppercase" class="form-control d-inline-block" placeholder="Body Number">
                                    <button v-on:click="findOperator" style="position: absolute; top: 0; right: 0; height: 100%; width: 70px; background: #b9bbbe; border: 1px solid #b9bbbe;" class="rounded-right"><i class="fas fa-search"></i></button>
                                </div>

                            </div>

                            <v-client-table style="font-size: 15px"
                                            :data="operatorTableData"
                                            :columns="columns_search"
                                            :options="options_search">
                                <span slot="action" slot-scope="{row}">
                                    <button
                                        v-if="searchNewOperator"
                                        v-on:click="selectSearchedNewOperator(row.taxpayer_id)"
                                        class="btn btn-primary mb-2 form-control"
                                        style="font-size: 12px">
                                        Select
                                    </button>

                                    <button
                                        v-else
                                        v-on:click="selectSearchedValue(row.taxpayer_id)"
                                        class="btn btn-primary mb-2 form-control"
                                        style="font-size: 12px">
                                        Select
                                    </button>
                                </span>
                            </v-client-table>
                        </div>
                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>

export default {

    props: ['mtop_application', 'charges', 'total_charges', 'tricycle', 'mtfrb_case_no'],

    data() {
        return {
            charges_column: ['name','price', 'action'],
            chargesTableData: [],
            charges_option: {
                headings: {
                    name: 'Charge Name',
                    price: 'Price',
                    action: 'Action',
                },
                filterable: false,
                texts : {
                    filter: 'Search:',
                },
            },

            selected_charge_column: ['name','price', 'action'],
            selectedChargesTableData: [],
            selected_charge_option: {
                headings: {
                    name: 'Charge Name',
                    price: 'Price',
                    action: 'Action',
                },
                filterable: false,
                texts : {
                    filter: 'Search:',
                },
            },

            columns_search: ['taxpayer_id', 'operator', 'action'],
            operatorTableData: [],
            options_search: {
                headings: {
                    taxpayer_id: 'Id',
                    operator: 'Name',
                    action: 'Action',
                },
                filterable: false,
                sortable: [],
                texts : {
                    filter: 'Search:',
                },
            },

            //dropdowns
            errors: [],
            barangayCodeTableData: [],
            tricycleTableData: [],

            //values
            newRenewalValue: false,
            changeUnitValue: false,
            droppingValue: false,
            transactionTotals: null,
            addCharges: false,
            transactionDate: null,
            operatorIdValue: null,
            operatorValue: null,
            addressValue: null,
            MTFRBCaseValue: null,
            barangayIdValue: null,
            barangayCodeValue: null,
            barangayValue: null,
            tricycleValue: null,
            bodyNumberValue: null,
            makeTypeValue: null,
            engineMotorNo: null,
            plateNoValue: null,
            chassisNoValue: null,
            searchedValue: null,
            caseNumberValue: null,
            searchNewOperator: false,

            /* TRANSACTION TYPE */

            transactionType: this.mtop_application.transact_type.split(','),

            /* RENEWAL VALUES */
            renewal: false,

            /* DROPPING VALUES */
            searchNewValue: null,
            newOperator: false,
            newOperatorValue: null,
            newOperatorIdValue: null,
            newOperatorAddressValue: null,
            newOperatorBarangayIdValue: null,
            newOperatorBarangayCodeValue: null,
            newOperatorBarangayValue: null,

            /* CHANGE UNIT */
            changeUnit: false,
            newMakeTypeValue: null,
            newEngineMotorNo: null,
            newPlateNoValue: null,
            newChassisNoValue: null,

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

        compute(event) {
            event.target.checked
                ? this.transactionTotals += parseFloat(event.target.value)
                : this.transactionTotals -= parseFloat(event.target.value);
        },

        currentDate() {
            const current = new Date();
            const date = `${current.getMonth()+1}/${current.getDate()}/${current.getFullYear()}`;
            return date;
        },

        openModalToSearch() {
            this.addCharges = false;
            this.searchNewOperator = false;
            $('#search-modal').modal('show');
        },

        errorHandler(errors){
            let error_handler = [];
            $.each(errors, function(key, value) {
                error_handler.push(value);
            });
            return error_handler;
        },

        clearInputs() {
            this.errors = [];
            this.operatorTableData = [];
            this.selectedChargesTableData = [];
            this.newRenewalValue = false;
            this.changeUnitValue = false;
            this.droppingValue = false;
            this.transactionTotals = false;
            this.searchedValue = '';
            this.transactionDate = '';
            this.operatorNameValue = '';
            this.addressValue = '';
            this.MTFRBCaseValue = '';
            this.barangayCodeValue = '';
            this.tricycleValue = '';
            this.bodyNumberValue = '';
            this.makeTypeValue = '';
            this.engineMotorNo = '';
            this.plateNoValue = '';
            this.transactionTotals = '';
            this.chassisNoValue = '';
            this.operatorValue = '';
            this.barangayValue = '';
            this.barangayIdValue = '';
            this.err = false;
            this.err_msg = '';
            this.suc = false;
            this.suc_msg = '';
        },

        closeMessageBox() {
            this.initialData();
            this.clearInputs();
        },

        initialData() {
            this.tableData = [];
            axios.get('mtop/getdata')
                .then(response => {
                    this.chargesTableData = response.data.charges;

                    this.barangayCodeTableData = response.data.barangays;
                });

            if(this.mtop_application !== null) {
                this.renewalInitialData();
            }
        },

        renewalInitialData() {
            const date = new Date();
            this.transactionTotals = this.total_charges;
            this.MTOPApplicationIdValue = this.mtop_application.id;
            this.transactionDate = this.mtop_application.transact_date;
            this.operatorIdValue = this.mtop_application.taxpayer_id;
            this.operatorValue = this.mtop_application.full_name;
            this.addressValue = this.mtop_application.address;
            this.barangayIdValue = this.mtop_application.brgy_id;
            this.barangayCodeValue = this.mtop_application.brgy_code;
            this.tricycleValue = this.mtop_application.tricycle_id;
            this.bodyNumberValue = this.mtop_application.body_number;
            this.makeTypeValue = this.mtop_application.make_type;
            this.engineMotorNo = this.mtop_application.engine_motor_no;
            this.plateNoValue = this.mtop_application.plate_no;
            this.chassisNoValue = this.mtop_application.chassis_no;
            this.tricycleTableData = this.tricycle;
            this.selectedChargesTableData = this.charges;
            this.MTFRBCaseValue = 'PAG-' + date.getFullYear() + '-' + this.mtfrb_case_no;
        },

        findOperator() {
            axios.get('mtop/search/'+ this.searchedValue.toUpperCase()).then(response => {
                this.operatorTableData = response.data.operator;
            });
        },

        selectSearchedValue(id) {
            let operator, address, brgy_id, brgy_code, brgy_desc, taxpayer_id = '';
            this.operatorTableData.forEach(function(item, index) {
                if(parseInt(item['taxpayer_id']) === parseInt(id)) {
                    taxpayer_id = item['taxpayer_id'];
                    operator = item['full_name'];
                    address = item['address1'];
                    brgy_id = item['barangay_id'];
                    // brgy_code = item['brgy_code'];
                    // brgy_desc = item['brgy_desc'];
                    return true;
                }
            });

            axios.get('mtop/tricycle/' + id)
                .then(response => {
                    this.tricycleTableData = response.data.tricycles;
                    console.log(response.data.tricycles);
                })

            this.operatorIdValue = taxpayer_id;
            this.operatorValue = operator;
            this.addressValue = address;
            this.barangayIdValue = brgy_id;
            this.barangayCodeValue = brgy_code;
            $('#search-modal').modal('hide');
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

        fillTricycleInfo(event) {
            let bodyNumber , makeType , engineNo , chassisNo , plateNo;
            this.tricycleTableData.forEach(function(item, index) {
                if(parseInt(event.target.value) === parseInt(item['id'])) {
                    bodyNumber = item['body_number'];
                    makeType = item['make_type'];
                    engineNo = item['engine_motor_no'];
                    chassisNo = item['chassis_no'];
                    plateNo = item['plate_no'];
                    return true;
                }
            });

            this.bodyNumberValue = bodyNumber;
            this.makeTypeValue = makeType;
            this.engineMotorNo = engineNo;
            this.chassisNoValue = chassisNo;
            this.plateNoValue = plateNo;
        },

        openModalToAddCharges() {
            this.addCharges = true;
            $('#search-modal').modal('show');
        },

        selectCharges(id) {
            let arr = [];
            let total;
            this.chargesTableData.forEach(function(item, index) {
                if(parseInt(item['id']) === parseInt(id)) {
                    arr.push(item);
                    total = parseFloat(item['price']);
                }
            });
            this.transactionTotals += total;
            this.selectedChargesTableData.push(arr[0]);
            $('#search-modal').modal('hide');
        },

        removeCharges(id, price) {
            this.transactionTotals -= parseFloat(price);
            this.selectedChargesTableData.splice(id - 1,1);
        },

        storeRecord() {
            if(!this.renewal && !this.newOperator && !this.changeUnit) {
                this.err = true;
                this.err_msg = 'You Must Select Transaction';
                return;
            }

            if(this.selectedChargesTableData.length === 0) {
                this.err = true;
                this.err_msg = 'Must Select Charges';
                return;
            }

            this.loader = true;

            /* pass dropping object if the user trigger dropping */

            let dropping_details;

            if (this.newOperator) {

                dropping_details = {
                    new_operator_id: this.newOperatorIdValue,
                    new_address: this.newOperatorAddressValue,
                    new_barangay_id: this.newOperatorBarangayIdValue,
                };

            }

            /* pass new tricycle details if the use trigger change unit */

            let change_unit;

            if (this.changeUnit) {

                change_unit = {
                    new_make_type: this.newMakeTypeValue,
                    new_engine_motor_no: this.newEngineMotorNo,
                    new_chassis_no: this.newChassisNoValue,
                    new_plate_no: this.newPlateNoValue,
                };
            }

            /* validate if new information has values */

            axios.post('mtop/store', {
                transact_date: this.transactionDate,
                mtfrb_case_no: this.MTFRBCaseValue,
                taxpayer_id: this.operatorIdValue,
                address: this.addressValue,
                barangay_id: this.barangayIdValue,
                tricycle_id: this.tricycleValue,
                body_number: this.bodyNumberValue,
                make_type: this.makeTypeValue,
                engine_motor_no: this.engineMotorNo,
                chassis_no: this.chassisNoValue,
                plate_no: this.plateNoValue,
                charges: this.selectedChargesTableData,
                renewal: this.renewal,
                dropping: this.newOperator,
                change_unit: this.changeUnit,
                dropping_details: dropping_details,
                change_unit_details: change_unit,
            })
                .then(response => {

                    this.returnSuccess(response);

                })
                .catch(error => {

                    this.returnFailed(error);

                })
                .finally(() => this.loader = false);
        },

        /* NEW/RENEWAL */


        checkRenewal(e) {

            if(e.target.checked) {
                this.renewal = true;
                this.newOperator = false;

                $('#new_check_icon').show();
                $('#drop_check_icon').hide();
                $('#dropping_details').hide();

                this.newOperatorValue = '';
                this.newOperatorIdValue = '';
                this.newOperatorAddressValue = '';
                this.newOperatorBarangayValue = '';
                this.newOperatorBarangayIdValue = '';

                return;
            }

            this.renewal = false;
            $('#new_check_icon').hide();

        },


        /* END NEW/RENEWAL */


        /* DROPPING */


        checkDropping(e) {

            if (e.target.checked) {
                $('#dropping_details').show();
                $('#drop_check_icon').show();
                $('#new_check_icon').hide();
                this.newOperator = true;
                this.renewal = false;
                return;
            }

            this.newOperatorValue = '';
            this.newOperatorIdValue = '';
            this.newOperatorAddressValue = '';
            this.newOperatorBarangayValue = '';
            this.newOperatorBarangayIdValue = '';

            $('#drop_check_icon').hide();
            $('#dropping_details').hide();
            this.newOperator = false;

        },

        openModalToSearchNewOperator() {
            this.addCharges = false;
            this.searchNewOperator = true;
            this.operatorTableData = [];
            $('#search-modal').modal('show');
        },

        findNewOperator() {
            axios.get('mtop/search_new_operator/'+ this.searchNewValue.toUpperCase()).then(response => {
                this.operatorTableData = response.data.operator;
            });
        },

        selectSearchedNewOperator(id) {
            let operator, operator_id, address, barangay, barangay_id = '';

            this.operatorTableData.forEach(function(item, index) {
                if(parseInt(item['taxpayer_id']) === parseInt(id)) {
                    operator = item['operator'];
                    operator_id = item['taxpayer_id'];
                    address = item['address'];
                    barangay = item['brgy_code'] + '-' + item['brgy_desc'];
                    barangay_id = item['barangay_id'];
                }
            });

            this.newOperatorValue = operator;
            this.newOperatorIdValue = operator_id;
            this.newOperatorAddressValue = address;
            this.newOperatorBarangayValue = barangay;
            this.newOperatorBarangayIdValue = barangay_id;
            $('#search-modal').modal('hide');
        },


        /* END OF DROPPING */


        /* CHANGE UNIT */


        checkChangeUnit(e) {
            if (e.target.checked) {
                $('#change_unit_details').show();
                $('#change_unit_icon').show();
                this.changeUnit = true;
                return;
            }

            $('#change_unit_details').hide();
            $('#change_unit_icon').hide();
            this.changeUnit = false;
            this.newMakeTypeValue = '';
            this.newEngineMotorNo = '';
            this.newPlateNoValue = '';
            this.newChassisNoValue = '';
        },


        /* END OF CHANGE UNIT */

    },

    mounted() {
        this.initialData();

    }
}
</script>
