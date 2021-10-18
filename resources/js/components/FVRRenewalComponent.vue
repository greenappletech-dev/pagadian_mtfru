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
                <li class="d-inline align-middle" style="color: #95a5a6; font-size: 12px">FVR Application</li>
                <li class="d-inline align-middle mr-1 ml-1" style="font-size: 5px; color: #95a5a6;"><i class="fas fa-circle"></i></li>
                <li class="d-inline align-middle" style="color: #95a5a6; font-size: 12px">New Transaction</li>
            </ul>
        </div>

        <div class="main-content">

            <div class="card">
                <div class="card-header">
                    <h2 style="font-size: 17px; margin: 0;">Banca Information</h2>
                    <button v-on:click="storeRecord" style="position: absolute; top: 7px; right: 20px; color: #fff; font-size: 14px; background: #357ec7" class="p-1 pl-3 pr-3 rounded border-0">Save Application</button>
                </div>

                <div class="card-body">

                    <div class="text-danger" v-if="errors.length > 0">
                        <ul>
                            <li v-for="error in errors">{{ error[0] }}</li>
                        </ul>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <label for="operator">Operator</label>
                            <div class="col-12 p-0">

                                <input
                                    v-model="banca.operator"
                                    readonly="readonly"
                                    id="operator"
                                    type="text"
                                    style="padding-right: 80px;"
                                    class="form-control d-inline-block">

<!--                                <button type="button"-->
<!--                                        v-on:click="openModalToSearch"-->
<!--                                        style="position: absolute; top: 0; right: 0; height: 100%; width: 70px; font-size: 12px; background: #b9bbbe; border: 1px solid #b9bbbe;"-->
<!--                                        class="rounded-right">-->
<!--                                    Find-->
<!--                                </button>-->

                            </div>


                            <label for="body_number">Body Number</label>
                            <input
                                v-model="banca.body_number"
                                type="text"
                                class="form-control"
                                id="body_number">

                            <label for="name">Boat Name</label>
                            <input
                                v-model="banca.boat_name"
                                type="text"
                                class="form-control"
                                id="name">

                            <label for="color">Boat Color</label>
                            <input
                                v-model="banca.boat_color"
                                type="text"
                                class="form-control"
                                id="color">

                            <label for="manning">Manning Crew</label>
                            <input
                                v-model="banca.manning_crew"
                                type="text"
                                class="form-control"
                                id="manning">

                            <label for="gear">Fishing Gear</label>
                            <input
                                v-model="banca.fishing_gear"
                                type="text"
                                class="form-control"
                                id="gear">

                        </div>

                        <div class="col-lg-6">

                            <label for="boat_type">Boat Type</label>
                            <select id="boat_type" class="form-control" v-model="banca.boat_type_id">
                                <option v-for="type in boatTypeTabldeData" v-bind:value="type.id">{{ type.name }}</option>
                            </select>

                            <label for="length">Length</label>
                            <input
                                v-model="banca.boat_length"
                                type="text"
                                class="form-control"
                                id="length">

                            <label for="width">Width</label>
                            <input
                                v-model="banca.width"
                                type="text"
                                class="form-control"
                                id="width">

                            <label for="depth">Depth</label>
                            <input
                                v-model="banca.dept"
                                type="text"
                                class="form-control"
                                id="depth">

                            <label for="gross">Gross Tonnage</label>
                            <input
                                v-model="banca.gross_tonnage"
                                type="text"
                                class="form-control"
                                id="gross">

                            <label for="net">Net Tonnage</label>
                            <input
                                v-model="banca.net_tonnage"
                                type="text"
                                class="form-control"
                                id="net">

                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2 style="font-size: 17px; margin: 0;">Engine Information</h2>
                </div>

                <div class="card-body">
                    <div class="row">
                        <label for="make_type">Make Type</label>
                        <input
                            v-model="banca.make_type"
                            type="text"
                            class="form-control"
                            id="make_type">

                        <label for="horsepower">Horsepower</label>
                        <input
                            v-model="banca.horsepower"
                            type="text"
                            class="form-control"
                            id="horsepower">

                        <label for="engine_serial">Engine Serial</label>
                        <input
                            v-model="banca.engine_motor_no"
                            type="text"
                            class="form-control"
                            id="engine_serial">

                        <label for="no_cylinder">Number of Cylinder</label>
                        <input
                            v-model="banca.cylinder"
                            type="text"
                            class="form-control"
                            id="no_cylinder">
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2 style="font-size: 17px; margin: 0;">Auxiliary Engine/s (optional)</h2>
                    <button v-on:click="openModalToAdd" id="btn_auxiliary" style="position: absolute; top: 7px; right: 20px; color: #fff; font-size: 14px; background: #357ec7" class="p-1 pl-3 pr-3 rounded border-0">Add Auxiliary Engine</button>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <v-client-table
                                :data="auxiliaryTableData"
                                :columns="columns_aux"
                                :options="options_aux">


                                <!----- EDITABLE TABLE  ----->

                                <span slot="make_type" slot-scope="props">
                                    <input type="text" class="form-control" v-model="auxiliary.make_type" v-if="edit_toggled && rowindex === props.index">
                                    <span v-else>{{ props.row.make_type }}</span>
                                </span>
                                <span slot="horsepower" slot-scope="props">
                                    <input type="text" class="form-control" v-model="auxiliary.horsepower" v-if="edit_toggled && rowindex === props.index">
                                    <span v-else>{{ props.row.horsepower }}</span>
                                </span>
                                <span slot="engine_motor_no" slot-scope="props">
                                    <input type="text" class="form-control" v-model="auxiliary.engine_motor_no" v-if="edit_toggled && rowindex === props.index">
                                    <span v-else>{{ props.row.engine_motor_no }}</span>
                                </span>
                                <span slot="cylinder" slot-scope="props">
                                    <input type="text" class="form-control" v-model="auxiliary.cylinder" v-if="edit_toggled && rowindex === props.index">
                                    <span v-else>{{ props.row.cylinder }}</span>
                                </span>


                                <!---- END OF EDITABLE TABLE ----->



                                <span slot="actions" slot-scope="props" v-if="changeUnitValue">
                                    <div class="d-inline-block">
                                        <button v-if="!edit_toggled" v-on:click="editAuxiliaryEngine(props.index, props.row.id)" class="btn btn-success mb-2"><i class="fas fa-edit mr-1"></i>Edit</button>
                                        <button v-else v-on:click="updateAuxiliaryEngine" class="btn btn-info mb-2"><i class="far fa-save mr-1"></i>Save</button>
                                    </div>

                                    <button v-on:click="removeAuxiliary(props.index, props.row.id)" class="btn btn-danger mb-2 d-inline-block"><i class="fas fa-trash mr-1"></i>Remove</button>
                                </span>
                            </v-client-table>
                        </div>
                    </div>
                </div>
            </div>



            <!---- TRANSACTION TYPE ----->


            <div class="card">
                <div class="card-header">
                    <h2 style="font-size: 17px; margin: 0;">Select Transaction Type</h2>
                </div>

                <div class="card-body">



                    <!--      FOR RENEWALS           -->




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






                    <!--         FOR DROPPING           -->





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
                                    v-model="new_operator.operator"
                                    readonly="readonly">

                                <label for="new_operator_address">Address</label>
                                <input
                                    id="new_operator_address"
                                    type="text"
                                    class="form-control"
                                    v-model="new_operator.address"
                                    readonly="readonly">

                                <label for="new_operator_barangay">Barangay</label>
                                <input
                                    id="new_operator_barangay"
                                    type="text"
                                    class="form-control"
                                    v-model="new_operator.barangay"
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






                    <!--        FOR CHANGE UNIT            -->






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
                    </div>



                </div>
            </div>




            <!------ CHARGES -------->



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
                    <select id="or_group" class="form-control mb-2" v-model="or_group" v-on:change="filterORGroup(or_group)">
                        <option value="A">Charge A</option>
                        <option value="B">Charge B</option>
                    </select>

                    <v-client-table
                        :data="filteredChargesTableData"
                        :columns="selected_charge_column"
                        :options="selected_charge_option">
                        <span slot="price" slot-scope="{row}">
                            {{ formatPrice(row.price) }}
                        </span>
                        <span slot="tot_amnt" slot-scope="{row}">
                            {{ formatPrice(row.tot_amnt) }}
                        </span>
                        <span slot="action" slot-scope="props">
                            <button v-on:click="removeCharges(props.index, props.row.tot_amnt)" class="btn btn-danger mb-2" style="font-size: 12px">Remove</button>
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

                        <h5 class="modal-title" id="exampleModalLabel">Search Operator</h5>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="max-height: 500px; padding-bottom: 0px; overflow: auto;">

                        <div class="text-danger" v-if="errors.length > 0">
                            <ul>
                                <li v-for="error in errors_aux">{{ error[0] }}</li>
                            </ul>
                        </div>

                        <div class="form group" v-if="addCharges">

                            <label for="qty">Quantity</label>
                            <input type="number" id="qty" class="form-control" v-model="qty">

                            <v-client-table style="font-size: 15px"
                                            :data="chargesTableData"
                                            :columns="charges_column"
                                            :options="charges_option">
                                    <span slot="price" slot-scope="{row}">
                                        <input v-if="row.price == 0" type="number" class="form-control" v-model="other_price">
                                        <span v-else>{{ formatPrice(row.price) }}</span>
                                    </span>
                                    <span slot="action" slot-scope="{row}">
                                        <button v-on:click="selectCharges(row.id)" class="btn btn-primary mb-2" style="font-size: 12px">Select</button>
                                    </span>
                            </v-client-table>
                        </div>

                        <div v-else>
                            <div class="form-group" v-if="add_engine">
                                <label for="aux_make_type">Make Type</label>
                                <input
                                    v-model="auxiliary.make_type"
                                    type="text"
                                    class="form-control"
                                    id="aux_make_type">

                                <label for="aux_horsepower">Horsepower</label>
                                <input
                                    v-model="auxiliary.horsepower"
                                    type="text"
                                    class="form-control"
                                    id="aux_horsepower">

                                <label for="aux_engine_serial">Engine Serial</label>
                                <input
                                    v-model="auxiliary.engine_motor_no"
                                    type="text"
                                    class="form-control"
                                    id="aux_engine_serial">

                                <label for="aux_no_cylinder">Number of Cylinder</label>
                                <input
                                    v-model="auxiliary.cylinder"
                                    type="text"
                                    class="form-control"
                                    id="aux_no_cylinder">
                            </div>

                            <div class="form-group" v-else>
                                <div class="col-12 p-0 mb-3">

                                    <div v-if="searchNewOperator" class="form-group">
                                        <input id="text_search" v-model="searchNewValue" type="text" style="padding-right: 80px; text-transform: uppercase" class="form-control d-inline-block" placeholder="Find New Operator">
                                        <button v-on:click="findNewOperator" style="position: absolute; top: 0; right: 0; height: 100%; width: 70px; background: #b9bbbe; border: 1px solid #b9bbbe;" class="rounded-right"><i class="fas fa-search"></i></button>
                                    </div>

                                    <div v-else class="form-group">
                                        <input id="text_search" v-model="searchedValue" type="text" style="padding-right: 80px; text-transform: uppercase" class="form-control d-inline-block" placeholder="Body Number">
                                        <button v-on:click="find" style="position: absolute; top: 0; right: 0; height: 100%; width: 70px; background: #b9bbbe; border: 1px solid #b9bbbe;" class="rounded-right"><i class="fas fa-search"></i></button>
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
                    </div>


                    <div class="modal-footer">
                        <div v-if="add_engine" class="form-group text-right">
                            <button v-on:click="addEngine" type="button" class="rounded pl-3 pr-3 pt-2 pb-2 border-0" style="width: 100px; font-size: 14px; background: #1abc9c; color: #fff;">Add</button>
                            <button type="button" data-dismiss="modal" class="rounded pl-3 pr-3 pt-2 pb-2 border-0" style="width: 100px; font-size: 14px; background: #e74c3c; color: #fff;">Discard</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {

    props: ['fvr_application', 'fvr_auxiliary_engine', 'fvr_application_charges', 'total_charges'],

    data() {
        return {
            columns_aux: ['make_type','horsepower','engine_motor_no','cylinder','actions'],
            auxiliaryTableData: this.fvr_auxiliary_engine,
            options_aux: {
                headings: {
                    make_type: 'Model/Make',
                    horsepower: 'Horsepower',
                    engine_motor_no: 'Engine Serial',
                    cylinder: 'Cylinder',
                    action: 'Action',
                },
                sortable: [],
                filterable: false,
                templates: {
                    hol_date: function(h, row) {
                        return row.hol_date !== null ? moment(row.hol_date).format('YYYY-MM-DD') : null;
                    },
                },
                texts : {
                    filter: 'Search:',
                },
            },

            columns_search: ['operator_id', 'operator', 'action'],
            operatorTableData: [],
            options_search: {
                headings: {
                    operator_id: 'Id',
                    operator: 'Name',
                    action: 'Action',
                },
                filterable: false,
                sortable: [],
                texts : {
                    filter: 'Search:',
                },
            },
            charges_column: ['name','price', 'action'],
            chargesTableData: [],
            charges_option: {
                headings: {
                    name: 'Charge Name',
                    price: 'Price',
                    action: 'Action',
                },
                filterable: ['name'],
                texts : {
                    filter: 'Search:',
                },
            },

            selected_charge_column: ['name','price', 'qty', 'tot_amnt','action'],
            selectedChargesTableData: this.fvr_application_charges,
            filteredChargesTableData: [],
            selected_charge_option: {
                headings: {
                    name: 'Charge Name',
                    price: 'Price',
                    qty: 'Quantity',
                    tot_amnt: 'Total Amount',
                    action: 'Action',
                },
                filterable: false,
                texts : {
                    filter: 'Search:',
                },
            },

            errors: [],
            erros_aux: [],
            operatorNameTableData: [],
            boatTypeTabldeData: [],
            banca: this.fvr_application,
            auxiliary: [],
            new_operator: [],
            qty: 1,
            or_group: 'A',

            searchedValue: null,
            searchNewValue: null,
            transactionTotals: this.total_charges,
            edit_toggled: null,
            rowindex: null,

            err: false,
            suc: false,
            err_msg: '',
            suc_msg: '',
            other_price: 0,

            searchNewOperator: false,
            droppingValue: false,
            changeUnitValue: false,
            addCharges: false,
            renewal: false,
            loader: false,
            add_engine: false,
        }
    },
    methods: {

        disableFields(bool) {
            $('input[type=text]').attr("readonly",bool);
            $('#boat_type').attr("readonly",bool);
            $('#btn_auxiliary').attr("disabled", bool);

            /* fix element attributes */

            $('#operator').attr("readonly", true);
            $('#text_search').attr("readonly",false);
            $('#new_operator').attr("readonly", true);
            $('#new_operator_address').attr("readonly", true);
            $('#new_operator_barangay').attr("readonly", true);
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

        closeMessageBox() {
            this.err = false;
            this.suc = false;
            this.adding = false;
            this.print = false;
            this.err_msg = '';
            this.suc_msg = '';
        },

        openModalToAddCharges() {
            this.qty = 1;
            this.addCharges = true;
            $('#search-modal').modal('show');
        },

        selectCharges(id) {
            let arr = [];
            let total;
            let compute_qty;
            let qty = this.qty;
            let price = this.other_price;
            let or_group = this.or_group;


            this.chargesTableData.forEach(function(item, index) {

                if(parseInt(item['id']) === parseInt(id)) {

                    if(item['price'] != 0) {
                        price = item['price'];
                    }

                    compute_qty = price * qty;

                    arr.push({
                        id: id,
                        name: item['name'],
                        price: price,
                        qty: qty,
                        tot_amnt: compute_qty,
                        or_group: or_group,
                    });

                    total = parseFloat(compute_qty);
                }

            });

            this.other_price = 0;
            this.transactionTotals += total;
            this.selectedChargesTableData.push(arr[0]);
            $('#search-modal').modal('hide');

            /* filter the data to display */
            this.filterORGroup(this.or_group);
        },

        removeCharges(id, tot_amnt) {
            this.transactionTotals -= parseFloat(tot_amnt);
            this.selectedChargesTableData.splice(id - 1,1);
            this.filterORGroup(this.or_group);
        },

        clearInput() {
            this.errors = [];
            this.banca =  [];
            this.auxiliary = [];
        },

        initialData() {
            axios.get('fvr/getdata').then(response => {
                this.boatTypeTabldeData = response.data.boat_types;
                this.chargesTableData = response.data.charges;
            })
        },

        openModalToSearch() {
            this.add_engine = false;
            this.addCharges = false;
            this.searchNewOperator = false;
            this.operatorTableData = [];
            $('#search-modal').modal('show');
        },

        find() {
            axios.get('fvr/search/'+ this.searchedValue.toUpperCase()).then(response => {

                console.log(response);
                this.operatorTableData = response.data.operator;
                this.auxiliaryTableData = response.data.auxiliary;
            });
        },

        selectSearchedValue(id) {
            let banca = [];

            this.operatorTableData.forEach(function(item, index) {
                if(parseInt(item['taxpayer_id']) === parseInt(id)) {

                    banca.id = item['id'];
                    banca.operator = item['operator'];
                    banca.taxpayer_id = item['taxpayer_id'];
                    banca.boat_name = item['name'];
                    banca.boat_color = item['color'];
                    banca.fishing_gear = item['fishing_gear'];
                    banca.boat_type_id = item['boat_type_id'];
                    banca.boat_length = Math.max(item['length']);
                    banca.width = Math.max(item['width']);
                    banca.dept = Math.max(item['dept']);
                    banca.gross_tonnage = Math.max(item['gross_tonnage']);
                    banca.net_tonnage = Math.max(item['net_tonnage']);
                    banca.make_type = item['make_type'];
                    banca.manning_crew = item['manning_crew'];
                    banca.horsepower = item['horsepower'];
                    banca.engine_motor_no = item['engine_motor_no'];
                    banca.cylinder = item['cylinder'];
                    banca.barangay_code = item['banca_code'];
                    banca.barangay_id = item['barangay_id'];
                    banca.address = item['address'];
                    banca.body_number = 'PAG-' + banca.barangay_code + '-MPB-' + item['body_number'];
                }
            });

            this.banca = banca;
            $('#search-modal').modal('hide');
        },

        openModalToAdd() {
            if(this.suc === true) {
                window.location.href = 'fvr';
            }

            this.err = false;
            this.err_msg = '';
        },

        addEngine() {
            let array = {
                id: this.auxiliary.id = 0,
                make_type: this.auxiliary.make_type,
                horsepower: this.auxiliary.horsepower,
                cylinder: this.auxiliary.cylinder,
                engine_motor_no: this.auxiliary.engine_motor_no
            };

            this.auxiliaryTableData.push(array);

            this.add_engine = false;
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

        /* RENEWALS */

        checkRenewal(e) {
            if(e.target.checked) {
                this.renewal = true;
                this.newOperator = false;

                $('#new_check_icon').show();
                $('#drop_check_icon').hide();
                $('#dropping_details').hide();
                return;
            }

            this.renewal = false;
            $('#new_check_icon').hide();
        },


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

            this.new_operator = [];

            $('#drop_check_icon').hide();
            $('#dropping_details').hide();
            this.newOperator = false;

        },

        openModalToSearchNewOperator() {
            this.searchNewOperator = true;
            this.addCharges = false;
            this.operatorTableData = [];
            $('#search-modal').modal('show');
        },

        findNewOperator() {
            axios.get('fvr/search_new_operator/'+ this.searchNewValue.toUpperCase()).then(response => {
                this.operatorTableData = response.data.operator;
            });
        },

        selectSearchedNewOperator(id) {
            let operator = [];

            this.operatorTableData.forEach(function(item, index) {
                if(parseInt(item['taxpayer_id']) === parseInt(id)) {
                    operator.operator = item['operator'];
                    operator.operator_id = item['taxpayer_id'];
                    operator.address = item['address'];
                    operator.barangay = item['brgy_code'] + '-' + item['brgy_desc'];
                    operator.barangay_id = item['barangay_id'];
                }
            });

            this.new_operator = operator;

            $('#search-modal').modal('hide');
        },


        /* END OF DROPPING */


        /* CHANGE UNIT */


        checkChangeUnit(e) {
            if (e.target.checked) {
                $('#change_unit_icon').show();
                this.changeUnit = true;
                this.disableFields(false);
                return;
            }

            $('#change_unit_icon').hide();
            this.changeUnit = false;
            this.disableFields(true);
        },

        updateAuxiliaryEngine() {
            this.auxiliaryTableData[this.rowindex-1].make_type = this.auxiliary.make_type;
            this.auxiliaryTableData[this.rowindex-1].horsepower = this.auxiliary.horsepower;
            this.auxiliaryTableData[this.rowindex-1].engine_motor_no = this.auxiliary.engine_motor_no;
            this.auxiliaryTableData[this.rowindex-1].cylinder = this.auxiliary.cylinder;
            this.edit_toggled = false;
        },

        editAuxiliaryEngine(index) {
            this.auxiliary.make_type = this.auxiliaryTableData[index-1].make_type;
            this.auxiliary.horsepower = this.auxiliaryTableData[index-1].horsepower;
            this.auxiliary.engine_motor_no = this.auxiliaryTableData[index-1].engine_motor_no;
            this.auxiliary.cylinder = this.auxiliaryTableData[index-1].cylinder;
            this.edit_toggled = true;
            this.rowindex = index;
        },

        removeAuxiliary(index) {
            this.auxiliaryTableData.splice(index - 1, 1);
        },


        storeRecord() {

            if(this.banca.length === 0) {
                this.err = true;
                this.err_msg = 'You Must Select Boat To Transact';
                return;
            }

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

            let dropping_details;

            if (this.newOperator) {

                dropping_details = {
                    new_operator_id: this.new_operator.operator_id,
                    new_address: this.new_operator.address,
                    new_barangay_id: this.new_operator.barangay_id,
                };

            }

            this.loader = true;
            axios.post('fvr/store', {
                taxpayer_id: this.banca.taxpayer_id,
                barangay_id: this.banca.barangay_id,
                banca_id: this.banca.banca_id,
                boat_name: this.banca.boat_name,
                boat_color: this.banca.boat_color,
                length: this.banca.boat_length,
                width: this.banca.width,
                dept: this.banca.dept,
                gross_tonnage: this.banca.gross_tonnage,
                net_tonnage: this.banca.net_tonnage,
                make_type: this.banca.make_type,
                horsepower: this.banca.horsepower,
                engine_motor_no: this.banca.engine_motor_no,
                cylinder: this.banca.cylinder,
                boat_type_id: this.banca.boat_type_id,
                fishing_gear: this.banca.fishing_gear,
                manning_crew: this.banca.manning_crew,
                body_number: this.banca.body_number,
                address: this.banca.address,
                auxiliary: this.auxiliaryTableData,
                charges: this.selectedChargesTableData,
                renewal: this.renewal,
                dropping: this.newOperator,
                change_unit: this.changeUnitValue,
                dropping_details: dropping_details,
            })
            .then(response => {
                this.returnSuccess(response);
            })
            .catch(error => {
                this.returnFailed(error);
            })
            .finally(
                () => this.loader = false
            );
        },

        filterORGroup(or_group) {
            this.filteredChargesTableData = this.selectedChargesTableData.filter(function(item) { return item.or_group === or_group; });
        }
    },

    mounted() {

        /* REMOVING LAST 6 CHARACTER THAT REPRESENT THE PAYMENT MONTH OF THE TRANSACTION */

        const date = new Date();
        let month = (date.getMonth() + 1).toString().padStart(2, "0");
        let year = date.getFullYear().toString().substr(-2);
        this.banca.body_number = this.fvr_application.body_number.slice(0, -6) + '-' + month + '-' + year;
        this.disableFields(true);
        this.initialData();
        this.filterORGroup('A');

        console.log(this.fvr_application_charges);
    }
}
</script>
