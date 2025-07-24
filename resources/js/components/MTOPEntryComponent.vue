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

.charge-item {
    transition: all 0.2s;
    background-color: #f8f9fa;
    border-radius: 4px;
}
.charge-item:hover {
    background-color: #e9ecef;
}

.charge-list{
    max-height: 300px;
    overflow-y: auto;
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
                <li class="d-inline align-middle" style="font-size: 12px">Application</li>
                <li class="d-inline align-middle mr-1 ml-1" style="font-size: 5px; color: #95a5a6;"><i class="fas fa-circle"></i></li>
                <li class="d-inline align-middle" style="font-size: 12px">MTOP</li>
                <li class="d-inline align-middle mr-1 ml-1" style="font-size: 5px; color: #95a5a6;"><i class="fas fa-circle"></i></li>
                <li class="d-inline align-middle" style="font-size: 12px; color: #95a5a6">New Application</li>
            </ul>
        </div>

        <div class="main-content align-center">

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
                            <input
                                type="text"
                                id="transaction_date"
                                readonly="readonly"
                                v-bind:value="currentDate()"
                                class="form-control">

                            <label for="mrfrb_case_no">MTFRB Case #</label>
                            <input
                                type="text"
                                id="mrfrb_case_no"
                                class="form-control"
                                v-model="MTFRBCaseValue">
                            <label for="operator">Operator</label>
                            <div class="col-12 p-0">

                                <input
                                    v-model="operatorValue"
                                    readonly="readonly"
                                    id="operator"
                                    type="text"
                                    style="padding-right: 80px;"
                                    class="form-control d-inline-block">

                                <button type="button"
                                        v-on:click="openModalToSearch"
                                        style="position: absolute; top: 0; right: 0; height: 100%; width: 70px; font-size: 12px; background: #b9bbbe; border: 1px solid #b9bbbe;"
                                        class="rounded-right">
                                    Find
                                </button>

                            </div>

                            <label for="address">Address</label>
                            <input
                                type="text"
                                id="address"
                                class="form-control"
                                v-model="addressValue"
                                readonly="readonly">

                            <label for="barangay">Barangay</label>
                            <select
                                class="d-inline-block rounded border p-2 form-control"
                                id="barangay"
                                v-model="barangayIdValue"
                                style="font-size: 14px;"
                                disabled="disabled">

                                <option value="">Select Barangay (Optional)</option>
                                <option v-for="barangay in barangayCodeTableData"
                                        v-bind:value="barangay.id">
                                    {{ barangay.brgy_code + '-' + barangay.brgy_desc }}
                                </option>
                            </select>
                        </div>

                        <div class="tricycle-info col-lg-6">

                            <label for="body_number">Body Number</label>
                            <input
                                type="text"
                                id="body_number"
                                class="form-control"
                                readonly="readonly"
                                v-model="bodyNumberValue">

                            <label for="make_type">Make Type</label>
                            <input
                                type="text"
                                id="make_type"
                                class="form-control"
                                readonly="readonly"
                                v-model="makeTypeValue">

                            <label for="engine_no">Engine Motor #</label>
                            <input
                                type="text"
                                id="engine_no"
                                class="form-control"
                                readonly="readonly"
                                v-model="engineMotorNo">

                            <label for="chassis_no">Chassis #</label>
                            <input
                                type="text"
                                id="chassis_no"
                                class="form-control"
                                readonly="readonly"
                                v-model="chassisNoValue">

                            <label for="plate_no">Plate #</label>
                            <input
                                type="text"
                                id="plate_no"
                                class="form-control"
                                readonly="readonly"
                                v-model="plateNoValue">
                        </div>
                    </div>
                </div>
            </div>


            <div class="card">
                <div class="card-header">
                    <h2 style="font-size: 17px; margin: 0;">Select Transaction Type</h2>
                </div>

                <div class="card-body">

                    <!--- FOR NEW --->


                    <div class="card">
                        <div class="card-header d-flex justify-content-start">
                            <label class="d-flex justify-content-start" for="chk_new" style="width: 100%;
                            height: 100%;
                            margin: 0">
                                <input type="checkbox" v-model="newTransaction" v-on:click="checkNew" style="display: none" id="chk_new">
                                <span style="position: relative; width: 20px; height: 20px;" class="border rounded mr-2"><i id="new_check_icon" class="fas fa-check" v-if="new_check_icon" style="
                                        position: absolute;
                                        top: 50%;
                                        left: 55%;
                                        transform: translate(-50%, -50%);
                                        font-size: 15px;
                                        color: #3ae374;"></i></span>
                                <h2 style="font-size: 17px; margin: 0;">New</h2>
                            </label>
                        </div>
                    </div>


                    <!--         RENEWAL           -->

                    <div class="card">
                        <div class="card-header d-flex justify-content-start">
                            <label class="d-flex justify-content-start" for="chk_renewal" style="width: 100%;
                                height: 100%;
                                margin: 0">
                                <input type="checkbox" v-model="renewal" v-on:click="checkRenewal" style="display: none" id="chk_renewal">
                                <span style="position: relative; width: 20px; height: 20px;" class="border rounded mr-2"><i id="renew_check_icon" class="fas fa-check" v-if="renew_check_icon" style="
                                            position: absolute;
                                            top: 50%;
                                            left: 55%;
                                            transform: translate(-50%, -50%);
                                            font-size: 15px;
                                            color: #3ae374;"></i></span>
                                <h2 style="font-size: 17px; margin: 0;">Renewal</h2>
                            </label>
                        </div>
                    </div>

                    <!--        DROPPING            -->

                    <div class="card">
                        <div class="card-header d-flex justify-content-start">
                            <label class="d-flex justify-content-start" for="chk_dropping" style="width: 100%;
                        height: 100%;
                        margin: 0">
                                <input type="checkbox" v-model="droppingValue" v-on:click="checkDropping" style="display: none" id="chk_dropping">
                                <span style="position: relative;
                            width: 20px;
                            height: 20px;" class="border rounded mr-2">
                                    <i id="drop_check_icon" class="fas fa-check" style="
                                    position: absolute;
                                    top: 50%;
                                    left: 55%;
                                    transform: translate(-50%, -50%);
                                    font-size: 15px;
                                    color: #3ae374;" v-if="drop_check_icon"></i>
                                </span>
                                <h2 style="font-size: 17px; margin: 0;">Dropping</h2>
                            </label>
                        </div>

                        <div class="card-body" id="dropping_details" style="position: relative;" v-if="dropping_details">
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
                                <input
                                    id="new_operator_barangay"
                                    type="text"
                                    class="form-control"
                                    v-model="newOperatorBarangayValue"
                                    readonly="readonly">

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

                    <!--          CHANGE UNIT          -->

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
                                style="
                                position: absolute;
                                top: 50%;
                                left: 55%;
                                transform: translate(-50%, -50%);
                                font-size: 15px;
                                color: #3ae374;" v-if="change_unit_icon">
                            </i>
                        </span>

                                <h2 style="font-size: 17px; margin: 0;">Change Unit</h2>

                            </label>
                        </div>

                        <div class="card-body" id="change_unit_details" v-if="change_unit_details">
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
                    <button
                        type="button"
                        v-on:click="openModalToAddCharges"
                        style="position: absolute;
                        top: 7px;
                        right: 20px;
                        color: #fff;
                        font-size: 14px;
                        background: #357ec7"
                        class="p-1 pl-3 pr-3 rounded border-0">
                        Add Charges
                    </button>
                </div>

                <div class="card-body">
                    <label for="or_group">Select Charge Group:</label>
                    <select id="or_group" class="form-control mb-2" v-model="or_group">
                        <option value="A">Charge A</option>
                        <option value="B">Charge B</option>
                        <option value="C">Charge C</option>
                    </select>
                    
                    <div v-if="transactionType && !err">
                        <div v-if="selectedChargesTableData.length > 0" class="charge-list">
                            <div v-for="charge in selectedChargesTableData" :key="charge.id" class="charge-item mb-2 p-2 border rounded">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong> {{ charge.name }}</strong>
                                        <div class="text-muted small">{{ formatPrice(charge.price) }}</div>
                                    </div>
                                    <button @click="removeCharges(charge.id, charge.price)" class="btn btn-primary btn-sm">
                                        Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div v-else class="text-center py-3">
                            No charges availble for this transaction type.
                        </div>
                    </div>

                    <!-- Selected Charges Table-->
                    <v-client-table
                        v-if="selectedCharges.length > 0"
                        :data="selectedCharges"
                        :columns="selected_charge_column"
                        :options="selected_charge_option">
                        <span slot="price" slot-scope="{row}">
                            {{ formatPrice(row.price) }}
                        </span>
                        <span slot="action" slot-scope="{row}">
                            <button @click="removeCharges(row.id)" class="btn btn-danger sm">Remove</button>
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
                                    :data="availableChargesToAdd"
                                    :columns="charges_column"
                                    :options="charges_option">
                                    <span slot="price" slot-scope="{row}">
                                         <template v-if="row.name === 'Surcharge 25% (MTFRU)' || row.name === 'Interest 2% (MTFRU)'">
                                            <input type="number" class="form-control" 
                                                v-model.number="other_prices[row.name]" 
                                                @input="updateSpecialCharge(row.name, $event)"
                                                min="0" step="0.01"/>
                                        </template>
                                        <template v-else-if="row.price == 0">
                                            <input type="number" class="form-control" v-model.number="other_prices[row.id]" >
                                    </template>
                                        <span v-else>{{ formatPrice(row.price) }}</span>
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
    props: ['charges'],
    
    data() {
        
        return {
            charges_column: ['name','price', 'action'],
            // chargesTableData: this.charges,
            transactionType: null,
            selectedCharges: [],
            chargesTableData: [],
            other_prices: {},
            selectedChargesTableData: [],
            filteredChargesTableData: [],
            transactionType: null,
            selectedCharges: [],
            or_group:  'A',
            transactionTotals: 0,
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
            filteredChargesTableData: [],
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
            other_price: 0,
            // Charge Group
            or_group:'A',
            // show/hide
            new_check_icon:false,
            renew_check_icon:false,
            drop_check_icon:false,
            dropping_details:false,
            change_unit_icon:false,
            change_unit_details:false,
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

            /* NEW TRANSACTION */
            newTransaction: false,

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
            dropping: false,
            changUnit: false,

            loader: false,
            adding: false,
            print: false,
            paperSize: 'Letter',
            paperOrientation: 'Portrait',
        }
    },
    computed: {
        filteredCharges(){
            if(!this.transactionType) {
                return [];
            }
            return this.chargesTableData.filter(charge => {
                return this.chargesTableData;
            });
        },
        availableChargesToAdd(){
            const assignedIds = this.selectedChargesTableData.map(c => c.id);
            return this.chargesTableData.filter(c => !assignedIds.includes(c.id));
        },
    },
    methods: {
            selectCharges(id) {
                let selectedCharge = null;
                let price = 0;

                this.chargesTableData.forEach(charge => {
                    if(parseInt(charge.id) === parseInt(id)){
                if (charge.name === 'Surcharge 25% (MTFRU)' || charge.name === 'Interest 2% (MTFRU)') {
                    price = parseFloat(this.other_prices[charge.name] || 0); // Use charge name as key
                } else {
                    price = charge.price === 0 ? parseFloat(this.other_prices[charge.id] || 0) : charge.price;
                }

                selectedCharge = {
                    id: charge.id,
                    name: charge.name,
                    price: price,
                    or_group: this.or_group
                };
            }
        });

            if (selectedCharge){
                this.selectedChargesTableData.push(selectedCharge);

                this.transactionTotals += parseFloat(price);

                this.filterORGroup(this.or_group);

                $('#search-modal').modal('hide');
                this.$set(this.other_prices, id, '');
            }
        },
        updateSpecialCharge(chargeName, event) {
            
            const value = parseFloat(event.target.value)!=''?parseFloat(event.target.value) : 0;

            this.$set(this.other_prices, chargeName, value);    
    
            this.selectedChargesTableData = this.selectedChargesTableData.map(charge => {
                if (charge.name === chargeName) {
                    return { ...charge, price: value };
                }
                return charge;
            });
        },
        filterORGroup(group) {
            this.filteredChargesTableData = this.selectedChargesTableData.filter(charge => charge.or_group === group
            );
        },
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
            this.operatorTableData = [];
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
            this.newOperatorValue = '';
            this.newOperatorAddressValue = '';
            this.newOperatorBarangayValue = '';
            this.newMakeTypeValue = '';
            this.newEngineMotorNo = '';
            this.newChassisNoValue = '';
            this.newPlateNoValue = '';
            this.changeUnit = false;
            this.newOperator = false;
        },

        closeMessageBox() {
            if(this.suc === true) {
                window.location.href = 'mtop';
            }

            this.err = false;
            this.err_msg = '';
        },

        uploadImage(event) {
            this.imageLocation = event.target.files[0];
        },

        initialData() {
            this.loader = true;
            axios.get('/mtop/getdata')
            .then(response => {
                this.chargesTableData = response.data.charges || this.charges;
                this.barangayCodeTableData = response.data.barangays || [];
                const date = new Date();
                this.MTFRBCaseValue = 'PAG-' + date.getFullYear() + '-';
                this.loader = false;
            }).catch(error => {
                console.error('Error loading initial data:', error);
                this.chargesTableData = this.charges || [];
                this.barangayCodeTableData = [];
                this.loader = false;
                
                if(this.charges) {
                    this.err = true;
                    this.err_msg = 'Failed to load initial data. Please try again.';
                }
            });
        },

        findOperator() {
            axios.get('mtop/search/'+ this.searchedValue.toUpperCase()).then(response => {
                this.operatorTableData = response.data.operator;
                this.caseNumberValue = response.data.mtfrb_case_no;
            });
        },

        selectSearchedValue() {
            this.operatorIdValue = this.operatorTableData[0].taxpayer_id;
            this.operatorValue = this.operatorTableData[0].full_name;
            this.addressValue = this.operatorTableData[0].address1;
            this.barangayIdValue = this.operatorTableData[0].barangay_id;
            this.barangayCodeValue = this.operatorTableData[0].brgy_code;
            this.tricycleValue = this.operatorTableData[0].tricycle_id;
            this.bodyNumberValue = this.operatorTableData[0].body_number;
            this.makeTypeValue = this.operatorTableData[0].make_type;
            this.engineMotorNo = this.operatorTableData[0].engine_motor_no;
            this.chassisNoValue = this.operatorTableData[0].chassis_no;
            this.plateNoValue = this.operatorTableData[0].plate_no;

            /* SET BODY NUMBER AS MTFRB CASE NO */
            this.MTFRBCaseValue = this.MTFRBCaseValue +  this.caseNumberValue;
            $('#search-modal').modal('hide');
        },

        returnSuccess(response) {
            $('#create-modal').modal('hide');
            this.suc = true;
            this.suc_msg = response.data.message;
            this.initialData();
            this.clearInputs();
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

        removeCharges(id, price){
            const removeIndex = this.selectedChargesTableData.findIndex(
                charge => charge.id === id
            );

            if(removeIndex !== -1){
                this.$set(this.other_prices, id, '');
                this.selectedChargesTableData.splice(removeIndex, 1);
                this.transactionTotals -= parseFloat(price);
                this.filterORGroup(this.or_group);
            }
        },

        storeRecord() {

            // if(!this.renewal && !this.newOperator && !this.changeUnit && !this.newTransaction) {
            //     this.err = true;
            //     this.err_msg = 'You Must Select Transaction';
            //     return;
            // }

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
                transact_date           : this.transactionDate,
                mtfrb_case_no           : this.MTFRBCaseValue,
                taxpayer_id             : this.operatorIdValue,
                address                 : this.addressValue,
                barangay_id             : this.barangayIdValue,
                tricycle_id             : this.tricycleValue,
                body_number             : this.bodyNumberValue,
                make_type               : this.makeTypeValue,
                engine_motor_no         : this.engineMotorNo,
                chassis_no              : this.chassisNoValue,
                plate_no                : this.plateNoValue,
                charges                 : this.selectedChargesTableData,
                new                     : this.newTransaction,
                renewal                 : this.renewal,
                dropping                : this.newOperator,
                change_unit             : this.changeUnit,
                dropping_details        : dropping_details,
                change_unit_details     : change_unit,
            })
            .then(response => {

                this.returnSuccess(response);

            })
            .catch(error => {

                this.returnFailed(error);

            })
            .finally(() => this.loader = false);
        },


        /* NEW */

        checkNew(e)
        {
            this.resetTransactionTypes();
            if(e.target.checked)
            {
                this.new_check_icon = true;
                this.newTransaction = true;
                this.transactionType = 'new';
                this.selectedChargesTableData = [];
                this.transactionTotals = 0;

                axios.get(`/setting/assigned_charges/${this.transactionType}`)
                .then(response => {
                    this.selectedChargesTableData = response.data.filter(charge => charge.price > 0).map(charge => ({
                        id: charge.id,
                        name: charge.name,
                        price: charge.price,
                        or_group: this.or_group
                    }));
                    this.transactionTotals = this.selectedChargesTableData.reduce((sum, c) => sum + parseFloat(c.price), 0);
                    this.filterORGroup(this.or_group);
                });
            }
        },

        loadChargesForTransaction(callback) {
            if(!this.transactionType) return;
            
            axios.get(`/mtop/charges/${this.transactionType}`)
                .then(response => {
                    this.chargesTableData = response.data;
                    if(typeof callback === 'function') callback();
                }).catch(error => {
                    console.error('Error loading charges:', error);
                });
    
        },

        toggleChargeSelection(charge){
            const index = this.selectedCharges.findIndex(c => c.id === charge.id);

            if(index === -1) {
                this.selectedCharges.push({
                    id: charge.id,
                    name: charge.name,
                    price: charge.price,
                    or_group: this.or_group
                });
                this.transactionTotals += parseFloat(charge.price);
            }else{
                this.transactionTotals -= parseFloat(this.selectedCharges[index].price);
                this.selectedCharges.splice(index, 1);
            }
        },
        isChargeSelected(chargeId){
            return this.selectedCharges.some(c => c.id === chargeId);
        },
        removeCharge(chargeId){
            const index = this.selectedCharges.findIndex(c => c.id ===chargeId);
            if(index !== -1){
                this.transactionTotals -= parseFloat(this.selectedCharges[index].price);
                this.selectedCharges.splice(index, 1);
            }
        },



        /* RENEWAL */

        checkRenewal(e) {
            this.resetTransactionTypes();
            if(e.target.checked) {
               
                this.renew_check_icon = true;
                this.renewal = true;
                this.transactionType = 'renewal';
                this.selectedChargesTableData = [];
                this.transactionTotals = 0;
                
                axios.get(`/setting/assigned_charges/${this.transactionType}`)
                .then(response => {
                    this.selectedChargesTableData = response.data.filter(charge => charge.price > 0)
                    .map(charge => ({
                        id: charge.id,
                        name: charge.name,
                        price: charge.price,
                        or_group: this.or_group
                    }));
                    this.transactionTotals = this.selectedChargesTableData.reduce((sum, c) =>sum + parseFloat(c.price), 0);
                    this.filterORGroup(this.or_group);
                });
            }
        },

        resetTransactionTypes(){
            this.new_check_icon = false;
            this.renew_check_icon = false;
            this.drop_check_icon = false;
            this.change_unit_icon = false;

            this.newTransaction = false;
            this.renewal = false;
            this.droppingValue = false;
            this.changeUnitValue = false;

            this.dropping_details = false;
            this.change_unit_details = false;

        },


        /* END NEW/RENEWAL */


        /* DROPPING */


        checkDropping(e) {
        this.resetTransactionTypes();
        if (e.target.checked) {
            this.drop_check_icon = true;
            this.dropping_details = true;
            this.droppingValue = true;
            this.transactionType = 'dropping';
            this.selectedChargesTableData = [];
            this.transactionTotals = 0;

            axios.get(`/setting/assigned_charges/${this.transactionType}`)
                .then(response => {
                    this.selectedChargesTableData = response.data.filter(charge => charge.price > 0)
                    .map(charge => ({
                        id: charge.id,
                        name: charge.name,
                        price: charge.price,
                        or_group: this.or_group
                    }));
                    this.transactionTotals = this.selectedChargesTableData.reduce((sum, c) => sum + parseFloat(c.price), 0);
                    this.filterORGroup(this.or_group);
                });
        }
    },

    checkChangeUnit(e) {
        this.resetTransactionTypes();
        if (e.target.checked) {
            this.change_unit_icon = true;
            this.change_unit_details = true;
            this.changeUnitValue = true;
            this.transactionType = 'change_unit';
            this.selectedChargesTableData = [];
            this.transactionTotals = 0;

            axios.get(`/setting/assigned_charges/${this.transactionType}`)
                .then(response => {
                    this.selectedChargesTableData = response.data.filter(charge => charge.price > 0)
                    .map(charge => ({
                        id: charge.id,
                        name: charge.name,
                        price: charge.price,
                        or_group: this.or_group
                    }));
                    this.transactionTotals = this.selectedChargesTableData.reduce((sum, c) => sum + parseFloat(c.price), 0);
                    this.filterORGroup(this.or_group);
                });
        }
    },

        openModalToSearchNewOperator() {
            this.searchNewOperator = true;
            this.addCharges = false;
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


        /* END OF CHANGE UNIT */


        pdfModal() {
            this.print = true;
            this.errors = [];
            $('#create-modal').modal('show');
        },

        pdfPrint() {
            window.open('tricycle/pdf/' + this.paperSize + '/' + this.paperOrientation);
            $('#create-modal').modal('hide');
            this.print = false;
        },

        exportExcel(){
            window.open('tricycle/export');
        },
    },

    mounted() {
        this.initialData();
        this.chargesTableData = this.charges;
        this.filterORGroup(this.or_group);
    }
}
</script>
