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
                <li class="d-inline align-middle" style="font-size: 12px">FVR Data</li>
                <li class="d-inline align-middle mr-1 ml-1" style="font-size: 5px; color: #95a5a6;"><i class="fas fa-circle"></i></li>
                <li class="d-inline align-middle" style="color: #95a5a6; font-size: 12px">Banca</li>
                <li class="d-inline align-middle mr-1 ml-1" style="font-size: 5px; color: #95a5a6;"><i class="fas fa-circle"></i></li>
                <li class="d-inline align-middle" style="color: #95a5a6; font-size: 12px">Add Banca</li>
            </ul>
        </div>

        <div class="main-content">
            <div class="card">
                <div class="card-header">
                    <h2 style="font-size: 17px; margin: 0;">Banca Information</h2>
                    <button v-on:click="storeRecord" style="position: absolute; top: 7px; right: 20px; color: #fff; font-size: 14px; background: #357ec7" class="p-1 pl-3 pr-3 rounded border-0">Save Record</button>
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


                            <label for="body_number">Body Number</label>
                            <input
                                v-model="bodyNumberValue"
                                type="text"
                                class="form-control"
                                id="body_number">

                            <label for="name">Boat Name</label>
                            <input
                                v-model="boatNameValue"
                                type="text"
                                class="form-control"
                                id="name">

                            <label for="color">Boat Color</label>
                            <input
                                v-model="boatColorValue"
                                type="text"
                                class="form-control"
                                id="color">

                            <label for="manning">Manning Crew</label>
                            <input
                                v-model="manningCrewValue"
                                type="text"
                                class="form-control"
                                id="manning">

                            <label for="gear">Fishing Gear</label>
                            <input
                                v-model="fishingGearValue"
                                type="text"
                                class="form-control"
                                id="gear">

                        </div>

                        <div class="col-lg-6">

                            <label for="boat_type">Boat Type</label>
                            <select id="boat_type" class="form-control" v-model="boatTypeValue">
                                <option v-for="type in boatTypeTabldeData" v-bind:value="type.id">{{ type.name }}</option>
                            </select>

                            <label for="length">Length</label>
                            <input
                                v-model="lengthValue"
                                type="text"
                                class="form-control"
                                id="length">

                            <label for="width">Width</label>
                            <input
                                v-model="widthValue"
                                type="text"
                                class="form-control"
                                id="width">

                            <label for="depth">Depth</label>
                            <input
                                v-model="depthValue"
                                type="text"
                                class="form-control"
                                id="depth">

                            <label for="gross">Gross Tonnage</label>
                            <input
                                v-model="grossValue"
                                type="text"
                                class="form-control"
                                id="gross">

                            <label for="net">Net Tonnage</label>
                            <input
                                v-model="netValue"
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
                            v-model="makeTypeValue"
                            type="text"
                            class="form-control"
                            id="make_type">

                        <label for="horsepower">Horsepower</label>
                        <input
                            v-model="horsePowerValue"
                            type="text"
                            class="form-control"
                            id="horsepower">

                        <label for="engine_serial">Engine Serial</label>
                        <input
                            v-model="engineSerialValue"
                            type="text"
                            class="form-control"
                            id="engine_serial">

                        <label for="no_cylinder">Number of Cylinder</label>
                        <input
                            v-model="cylinderValue"
                            type="text"
                            class="form-control"
                            id="no_cylinder">
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2 style="font-size: 17px; margin: 0;">Auxiliary Engine/s (optional)</h2>
                    <button v-on:click="openModalToAdd" style="position: absolute; top: 7px; right: 20px; color: #fff; font-size: 14px; background: #357ec7" class="p-1 pl-3 pr-3 rounded border-0">Add Auxiliary Engine</button>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <v-client-table
                                :data="auxiliaryTableData"
                                :columns="columns_aux"
                                :options="options_aux">
                                <span slot="actions" slot-scope="props">
                                    <button v-on:click="removeAuxiliary(props.index)" class="btn btn-danger mb-2">Remove</button>
                                </span>
                            </v-client-table>
                        </div>
                    </div>
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

                        <div class="form-group" v-if="add_engine">
                            <div class="form-group">
                                <label for="aux_make_type">Make Type</label>
                                <input
                                    v-model="auxMakeTypeValue"
                                    type="text"
                                    class="form-control"
                                    id="aux_make_type">

                                <label for="aux_horsepower">Horsepower</label>
                                <input
                                    v-model="auxHorsePowerValue"
                                    type="text"
                                    class="form-control"
                                    id="aux_horsepower">

                                <label for="aux_engine_serial">Engine Serial</label>
                                <input
                                    v-model="auxEngineSerialValue"
                                    type="text"
                                    class="form-control"
                                    id="aux_engine_serial">

                                <label for="aux_no_cylinder">Number of Cylinder</label>
                                <input
                                    v-model="auxCylinderValue"
                                    type="text"
                                    class="form-control"
                                    id="aux_no_cylinder">
                            </div>
                        </div>

                        <div class="form-group" v-else>
                            <div class="col-12 p-0 mb-3">
                                <div class="form-group">
                                    <input
                                        v-model="searchedValue"
                                        type="text"
                                        style="padding-right: 80px; text-transform: uppercase"
                                        class="form-control d-inline-block"
                                        placeholder="search last name, full name">

                                    <button v-on:click="findOperator"
                                            style="position: absolute;
                                            top: 0;
                                            right: 0;
                                            height: 100%;
                                            width: 70px;
                                            background: #b9bbbe;
                                            border: 1px solid #b9bbbe;"
                                            class="rounded-right">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>

                            </div>

                            <v-client-table style="font-size: 15px"
                                            :data="operatorTableData"
                                            :columns="columns_search"
                                            :options="options_search">
                                <span slot="action" slot-scope="{row}">
                                    <button
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

    data() {
        return {
            columns_aux: ['make_type','horsepower','engine_motor_no','cylinder','actions'],
            auxiliaryTableData: [],
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
            erros_aux: [],
            operatorNameTableData: [],
            boatTypeTabldeData: [],

            //values
            operatorValue: null,
            operatorIdValue: null,
            barangayCode: null,
            searchedValue: null,
            bodyNumberValue: null,
            boatNameValue: null,
            boatTypeValue: null,
            boatColorValue: null,
            fishingGearValue: null,
            manningCrewValue: null,
            lengthValue: null,
            widthValue: null,
            depthValue: null,
            grossValue: null,
            netValue: null,
            makeTypeValue: null,
            horsePowerValue: null,
            engineSerialValue: null,
            cylinderValue: null,
            auxMakeTypeValue: null,
            auxHorsePowerValue: null,
            auxEngineSerialValue: null,
            auxCylinderValue: null,

            err_msg: '',
            err: false,
            suc_msg: '',
            suc: false,

            loader: false,
            add_engine: false,
        }
    },
    methods: {

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

        clearInput() {
            this.errors = [];
            this.auxEngineSerialValue = '';
            this.auxHorsePowerValue = '';
            this.auxCylinderValue = '';
            this.auxMakeTypeValue = '';
        },

        initialData() {
            axios.get('banca/getdata').then(response => {
                this.boatTypeTabldeData = response.data.boat_types;
            })
        },

        openModalToSearch() {
            this.add_engine = false;
            this.operatorTableData = [];
            $('#search-modal').modal('show');
        },

        findOperator() {
            axios.get('banca/search/'+ this.searchedValue.toUpperCase()).then(response => {
                this.operatorTableData = response.data.operators;
            });
        },

        selectSearchedValue(id) {
            let operator = [];

            this.operatorTableData.forEach(function(item, index) {
                if(parseInt(item['taxpayer_id']) === parseInt(id)) {
                    operator.operator = item['operator'];
                    operator.operator_id = item['taxpayer_id'];
                }
            });

            this.operatorValue = operator.operator;
            this.operatorIdValue = operator.operator_id;
            $('#search-modal').modal('hide');
        },

        openModalToAdd() {
            this.clearInput();
            this.add_engine = true;
            $('#search-modal').modal('show');
        },

        addEngine() {
            let array = {
                make_type: this.auxMakeTypeValue,
                horsepower: this.auxHorsePowerValue,
                engine_motor_no: this.auxEngineSerialValue,
                cylinder: this.auxCylinderValue
            };

            this.auxiliaryTableData.push(array);

            this.add_engine = false;
            $('#search-modal').modal('hide');
        },

        removeAuxiliary(id) {
            this.auxiliaryTableData.splice(id - 1,1);
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

        storeRecord() {
            this.loader = true;
            axios.post('banca/store', {
                operator_id: this.operatorIdValue,
                name: this.boatNameValue,
                color: this.boatColorValue,
                length: this.lengthValue,
                width: this.widthValue,
                dept: this.depthValue,
                gross_tonnage: this.grossValue,
                net_tonnage: this.netValue,
                make_type: this.makeTypeValue,
                horsepower: this.horsePowerValue,
                engine_motor_no: this.engineSerialValue,
                cylinder: this.cylinderValue,
                boat_type_id: this.boatTypeValue,
                fishing_gear: this.fishingGearValue,
                manning_crew: this.manningCrewValue,
                body_number: this.bodyNumberValue,
                auxiliary_engine: this.auxiliaryTableData,
            })
            .then(response => {
                this.returnSuccess(response);
            })
            .catch(error => {
                this.returnFailed(error);
            })
            .finally(() => this.loader = false);
        },



    },

    mounted() {
        this.initialData();
    }
}
</script>
