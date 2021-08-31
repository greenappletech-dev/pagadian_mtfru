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
                <li class="d-inline align-middle" style="font-size: 12px">FVR Data</li>
                <li class="d-inline align-middle mr-1 ml-1" style="font-size: 5px; color: #95a5a6;"><i class="fas fa-circle"></i></li>
                <li class="d-inline align-middle" style="color: #95a5a6; font-size: 12px">Banca</li>
            </ul>
        </div>

        <div class="main-content">
            <h2 class="d-inline align-middle">Banca</h2>
            <div class="row mt-3 mb-3">
                <div class="col-6">
                    <button v-on:click="openToCreate" class="d-inline align-middle p-2 pl-4 pr-4 rounded border-0" style="background: rgba(255, 120, 31, 1); color: #fff; font-size: 14px; box-shadow: 2px 2px 2px rgba(165, 165, 165, 1)">Add New Banca</button>
                </div>

                <div class="col-6 text-right">
                    <a class="p-2 pl-4 pr-4 rounded" style="background: #1abc9c; font-size: 14px; box-shadow: 2px 2px 2px rgba(165, 165, 165, 1); color: #fff; text-decoration: none;" v-on:click="exportExcel">Export to Excel</a>
<!--                    <a class="p-2 pl-4 pr-4 rounded" style="background: #1abc9c; font-size: 14px; box-shadow: 2px 2px 2px rgba(165, 165, 165, 1); color: #fff; text-decoration: none;" v-on:click="pdfModal">Print to PDF</a>-->
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
                            <span slot="actions" slot-scope="{row}">
                                <button v-on:click="openToEdit(row.id)" class="btn btn-success mb-2"><i class="fas fa-edit mr-1"></i>Edit</button>
                                <button v-on:click="destroyRecord(row.id)" class="btn btn-danger mb-2"><i class="fas fa-trash mr-1"></i>Delete</button>
                            </span>
                        </v-client-table>
                    </div>
                </div>
            </div>
        </div>

        <!--        modal window    -->

        <div class="modal fade" id="create-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">

                        <h5 class="modal-title" id="exampleModalLabel" v-if="adding">Add Banca</h5>
                        <h5 class="modal-title" id="exampleModalLabel" v-else>Edit Banca</h5>

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
                            <select id="paper-size" class="form-control">
                                <option>Letter</option>
                                <option>Legal</option>
                                <option>A4</option>
                            </select>

                            <label for="paper-orientation">Orientation</label>
                            <select id="paper-orientation" class="form-control">
                                <option>Portrait</option>
                                <option>Landscape</option>
                            </select>
                        </div>

                        <div class="form-group" v-else>

                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="body_number">Body Number</label>
                                    <input type="text" class="form-control" id="body_number">

                                    <label for="name">Boat Name</label>
                                    <input type="text" class="form-control" id="name">

                                    <label for="color">Boat Color</label>
                                    <input type="text" class="form-control" id="color">

                                    <label for="length">Length</label>
                                    <input type="text" class="form-control" id="length">

                                    <label for="width">Width</label>
                                    <input type="text" class="form-control" id="width">

                                    <label for="depth">Depth</label>
                                    <input type="text" class="form-control" id="depth">

                                    <div class="row">
                                        <div class="col-6 form-group">
                                            <label for="gross">Gross Tonnage</label>
                                            <input type="text" class="form-control" id="gross">
                                        </div>

                                        <div class="col-6 form-group">
                                            <label for="net">Net Tonnage</label>
                                            <input type="text" class="form-control" id="net">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <label for="make_type">Make Type</label>
                                    <input type="text" class="form-control" id="make_type">

                                    <label for="hoursepower">Horsepower</label>
                                    <input type="text" class="form-control" id="hoursepower">

                                    <label for="engine_serial">Engine Serial</label>
                                    <input type="text" class="form-control" id="engine_serial">

                                    <label for="no_cylinder">Number of Cylinder</label>
                                    <input type="text" class="form-control" id="no_cylinder">
                                </div>


                            </div>

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
            columns: ['operator', 'body_number', 'name', 'color', 'length', 'width', 'dept', 'make_type', 'engine_motor_no', 'boat_type','created_at', 'updated_at','actions'],
            tableData: [],
            options: {
                headings: {
                    operator: 'Operator',
                    body_number: 'Body Number',
                    name: 'Boat Name',
                    color: 'Boat Color',
                    length: 'Length',
                    width: 'Width',
                    dept: 'Depth',
                    make_type: 'Model/Make',
                    engine_motor_no: 'Engine Serial',
                    boat_type: 'Boat Type',
                    created_at: 'Created At',
                    updated_at: 'Updated At',
                    action: 'Action',
                },
                sortable: ['operator', 'body_number', 'name'],
                filterable: ['operator', 'body_number', 'name'],
                templates: {
                    hol_date: function(h, row) {
                        return row.hol_date !== null ? moment(row.hol_date).format('YYYY-MM-DD') : null;
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

            //dropdowns
            errors: [],
            operatorTableData: [],
            operatorNameTableData: [],

            //values
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

        initialData() {
            this.tableData = [];
            axios.get('banca/getdata').then(response => {
                this.tableData = response.data.bancas;
            })
        },

        openToCreate() {
            window.location.href = 'banca/entry';
        },

        openToEdit(id) {
            window.location.href = 'banca/edit/' + id;
        },

        destroyRecord(id) {
            let confirmBox = confirm("Do you really want to delete this record?");
            if(confirmBox === true){
                this.loader = true;
                axios.get('banca/destroy/' + id)
                .then(response => {
                    this.returnSuccess(response);
                })
                .catch(error => {
                    this.returnFailed(error);
                })
                .finally(()=> this.loader = false);
            }
        },

        pdfModal() {
            this.print = true;
            this.errors = [];
            $('#create-modal').modal('show');
        },

        // pdfPrint() {
        //     window.open('tricycle/pdf/' + this.paperSize + '/' + this.paperOrientation);
        //     $('#create-modal').modal('hide');
        //     this.print = false;
        // },

        exportExcel(){

            window.open('banca/export');
        },
    },

    mounted() {
        this.initialData();
    }
}
</script>
