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
                <li class="d-inline align-middle">MTFRU</li>
                <li class="d-inline align-middle mr-1 ml-1" style="font-size: 5px; color: #95a5a6;"><i class="fas fa-circle"></i></li>
                <li class="d-inline align-middle" style="color: #95a5a6">Charges</li>
            </ul>
        </div>

        <div class="main-content">
            <h2 class="d-inline align-middle">Charges</h2>
            <div class="row mt-3 mb-3">
                <div class="col-6">
                    <button v-on:click="openModalToCreate" class="d-inline align-middle p-2 pl-4 pr-4 rounded border-0" style="background: rgba(255, 120, 31, 1); color: #fff; font-size: 14px; box-shadow: 2px 2px 2px rgba(165, 165, 165, 1)">Add New Charges</button>
                </div>

                <div class="col-6 text-right">
<!--                    <a class="p-2 pl-4 pr-4 rounded" style="background: #1abc9c; font-size: 14px; box-shadow: 2px 2px 2px rgba(165, 165, 165, 1); color: #fff; text-decoration: none;" v-on:click="exportExcel">Export to Excel</a>-->
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
                            <span slot="price" slot-scope="{row}">{{ formatPrice(row.price) }}</span>
                            <span slot="actions" slot-scope="{row}">
                                <button v-on:click="openModalToEdit(row.id)" class="btn btn-success mb-2"><i class="fas fa-edit mr-1"></i>Edit</button>
                                <button v-on:click="destroyRecord(row.id)" class="btn btn-danger mb-2"><i class="fas fa-trash mr-1"></i>Delete</button>
                            </span>
                        </v-client-table>
                    </div>
                </div>
            </div>
        </div>

        <!--        modal window    -->

        <div class="modal fade" id="create-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">

                        <h5 class="modal-title" id="exampleModalLabel" v-if="adding">Add Charges</h5>
                        <h5 class="modal-title" id="exampleModalLabel" v-else>Edit Charges</h5>

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
                            <label for="name">Name</label>
                            <input type="text" id="name" class="form-control" v-model="chargeName">

                            <label for="price">Price</label>
                            <input type="text" id="price" class="form-control" v-model="priceValue">

                            <label class="label_transaction align-middle p-1 mt-1" for="dropping" style="margin: 0; width: 100%; background: #fff;">
                                <div class="d-flex justify-content-between">
                                    <p class="align-middle inline-block" style="margin: 0;">Set as Dropping Fee?</p>
                                    <input style="margin-top: 6px" type="checkbox" id="dropping" v-model="dropValue">
                                </div>
                            </label>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button v-if="print" @click="pdfPrint" type="button" class="rounded pl-3 pr-3 pt-2 pb-2 border-0" style="width: 100px; font-size: 14px; background: #1abc9c; color: #fff;">Print</button>
                        <div v-else>
                            <button v-if="adding" @click="storeRecord" type="button" class="rounded pl-3 pr-3 pt-2 pb-2 border-0" style="width: 100px; font-size: 14px; background: #1abc9c; color: #fff;">Create</button>
                            <button v-else @click="updateRecord" type="button" class="rounded pl-3 pr-3 pt-2 pb-2 border-0" style="width: 100px; font-size: 14px; background: #1abc9c; color: #fff;">Update</button>
                        </div>
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
            columns: ['id','name', 'price', 'created_at', 'updated_at','actions'],
            tableData: [],
            options: {
                headings: {
                    id: 'ID',
                    name: 'Name',
                    price: 'Price',
                    created_at: 'Created At',
                    updated_at: 'Updated At',
                    action: 'Action',
                },
                sortable: ['id', 'Name'],
                filterable: false,
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

            chargeIdValue: null,
            chargeName: null,
            priceValue: null,
            dropValue: null,

            err: false,
            suc: false,
            err_msg: false,
            suc_msg: false,
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

        clearInputs() {
            this.err = false;
            this.suc = false;
            this.adding = false;
            this.print = false;
            this.err_msg = '';
            this.suc_msg = '';
            this.errors = [];
            this.chargeIdValue = '';
            this.chargeName = '';
            this.priceValue = '';
            this.dropValue = '';
        },

        closeMessageBox() {
            this.clearInputs();
            this.initialData();
        },

        initialData() {
            this.tableData = [];
            axios.get('charges/getdata').then(response => {
                this.tableData = response.data.charges;
            })
        },

        openModalToCreate() {
            this.clearInputs();
            $('#create-modal').modal('show');
            this.adding = true;
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
            axios.post('charges/store', {
                name: this.chargeName,
                price: this.priceValue,
                IsDroppingChecked: this.dropValue,
            })
            .then(response => {
                this.returnSuccess(response);
            })
            .catch(error => {
                this.returnFailed(error);
            })
            .finally(() => this.loader = false)
        },

        openModalToEdit(id) {
            this.clearInputs();
            axios.get('charges/edit/' + id).then(response => {
                this.chargeIdValue = response.data.charge.id;
                this.chargeName = response.data.charge.name;
                this.priceValue = response.data.charge.price;
                this.dropValue = response.data.charge.drop_default;
                $('#create-modal').modal('show');
                this.adding = false;
            });
        },

        updateRecord(){
            this.loader = true;
            console.log(this.chargeName);
            axios.patch('charges/update/' + this.chargeIdValue, {
                name: this.chargeName,
                price: this.priceValue,
                IsDroppingChecked: this.dropValue,
            })
            .then(response => {
                this.returnSuccess(response);
            })
            .catch(error => {
                this.returnFailed(error);
            })
            .finally(()=> this.loader = false);
        },

        destroyRecord(id) {
            let confirmBox = confirm("Do you really want to delete this record?");
            if(confirmBox === true){
                this.loader = true;
                axios.get('charges/destory/' + id)
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
    }
}
</script>
