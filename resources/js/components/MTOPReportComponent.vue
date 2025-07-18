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
                <li class="d-inline align-middle"  style="font-size: 12px">Reports</li>
                <li class="d-inline align-middle mr-1 ml-1" style="font-size: 5px; color: #95a5a6;"><i class="fas fa-circle"></i></li>
                <li class="d-inline align-middle" style="font-size: 12px; color: #95a5a6">MTOP Report</li>
            </ul>
        </div>

        <div class="main-content">
            <div class="card mt-5" style="width: 600px; margin: 0 auto;">

                <div class="card-header">
                    <h4 style="font-size: 18px; margin: 0">MTOP Reports</h4>
                </div>

                <div class="card-body">

                    <label for="report_type">Report Type</label>
                    <select id="report_type" class="form-control" v-model="report.type" v-on:change="selectChange">
                        <option value="1">MTOP Detailed Report - (Range)</option>
                        <option value="4">New Franchise Report - (Range)</option>
                        <option value="3">New Franchise Summary Per Month - (Range)</option>
                        <option value="2">Summary Report Per Make/Type</option>
                        <option value="5">Monthly Accomplishment Report</option>
                        <option value="6">List of Expired & For Expiration Franchise Report</option>
                    </select>

                        <div id="filter_options_1">

                            <div v-if="report.type !== '4' && report.type !== '5' && report.type !== '6' && report.type !== '2'">
                                <label for="barangay" class="mt-1">Specific Barangay</label>
                                    <select id="barangay" class="form-control" v-model="barangay.id">
                                        <option value=""></option>
                                        <option v-for="data in barangay"
                                                v-bind:value="data.id">
                                            {{ data.brgy_code + '-' + data.brgy_desc }}
                                        </option>
                                    </select>
                            </div>

                            <div class="row mt-1" v-if="report.type !== '6'">
                                <div class="col">
                                    <label for="from">From:</label>
                                    <input type="date" id="from" class="form-control" v-model="report.from">
                                </div>

                                <div class="col">
                                    <label for="to">To:</label>
                                    <input type="date" id="to" class="form-control" v-model="report.to">
                                </div>
                            </div>

                            <div class="row mt-1" v-if="report.type === '6'">
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="expiration" value="expired" id="expired" v-model="expiredFilter">
                                        <label class="form-check-label" for="expired">
                                            Expired Franchise
                                        </label>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="expiration" value="for_expiration" id="for_expiration" v-model="expiredFilter">
                                        <label class="form-check-label" for="for_expiration">
                                            For Expiration Franchise
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </div>

                </div>

                <div class="card-footer d-flex justify-content-end">
                    <button class="btn btn-info mr-1" style="font-size: 13px" v-on:click="exportReport"><i class="fas fa-file-excel mr-1"></i>Excel Report</button>
                    <button class="btn btn-info" style="font-size: 13px" v-on:click="openModalToPrint"><i class="fas fa-file-pdf mr-1"></i>Print PDF</button>
                </div>

            </div>
        </div>




        <!--        modal window    -->

        <div class="modal fade" id="print-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">

                        <h5 class="modal-title" id="exampleModalLabel">Print Forms</h5>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="max-height: 500px; overflow: auto;">
                        <div class="form-group">
                            <label for="paper-size">Paper Size</label>
                            <select id="paper-size" class="form-control" v-model="paperSize">
                                <option>Letter</option>
                                <option>Legal</option>
                                <option>A4</option>
                            </select>

                            <label for="paper-orientation">Orientation</label>
                            <select id="paper-orientation" class="form-control" v-model="paperOrientation">
                                <option>Portrait</option>
                                <option>Landscape</option>
                            </select>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button v-on:click="printPdf" type="button" class="rounded pl-3 pr-3 pt-2 pb-2 border-0" style="width: 100px; font-size: 14px; background: #1abc9c; color: #fff;">Print</button>
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
            //dropdowns
            barangay: [],
            report: [],

            errors: [],

            err_msg: null,
            suc_msg: null,
            err: false,
            suc: false,
            loader: false,
            expiredFilter: '',
            paperSize: 'Letter',
            paperOrientation: 'Portrait',

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
            this.print = false;
            this.err_msg = '';
            this.suc_msg = '';
        },

        selectChange(e)
        {
            // const wout_range = ['2'];
            // console.log(wout_range.indexOf(this.report.type));
            // if(wout_range.indexOf(this.report.type) < 0)
            // {
                $('#filter_options_1').show();
                return;
            // }


            this.report.from = null;
            this.report.to = null;
            this.barangay.id = null;
            $('#filter_options_1').hide();


        },

        initialData() {
            axios.get('mtop_report/getdata')
            .then(response =>
            {
                this.barangay = response.data.barangays;
            });

        },



        openModalToPrint() {

            console.log(this.expiredFilter);


            if(this.report.type == null)
            {

                this.err = true;
                return this.err_msg = 'Report Type is Required';

            }

            const wout_range = ['2'];

            if(this.report.type === '6')
            {
                if(this.expiredFilter === '')
                {
                    this.err = true;
                    return this.err_msg = 'Select Filter Option';
                }
            }
            else
            {
                if(wout_range.indexOf(this.report.type) < 0)
                {

                    if(this.report.from == null || this.report.to == null)
                    {

                        this.err = true;
                        return this.err_msg = 'Please Set Range';

                    }

                }
            }



            $('#print-modal').modal('show');

        },


        printPdf() {

            let optional_route = this.barangay.id == null || this.barangay.id === '' ? '/null' : '/'  + this.barangay.id;

            if(this.report.type === '6')
            {
                window.open('expired_franchise/' + this.expiredFilter + '/' + this.paperSize + '/' + this.paperOrientation);
            }
            else
            {
                window.open('mtop_report/pdf/'+ this.report.type + '/' + this.report.from + '/' + this.report.to + optional_route + '/' + this.paperSize + '/' + this.paperOrientation)
            }

        },



        exportReport() {


            if(this.report.type == null)
            {

                this.err = true;
                return this.err_msg = 'Report Type is Required';

            }

            // const wout_range = ['2'];

            if(this.report.type === '6')
            {
                if(this.expiredFilter === '')
                {
                    this.err = true;
                    return this.err_msg = 'Select Filter Option';
                }
            }
            else
            {
                // if(wout_range.indexOf(this.report.type) < 0)
                // {

                    if(this.report.from == null || this.report.to == null)
                    {

                        this.err = true;
                        return this.err_msg = 'Please Set Range';

                    }

                // }
            }

            let optional_route = this.barangay.id == null || this.barangay.id === '' ? '/null' : '/'  + this.barangay.id;

            if(this.report.type === '6')
            {
                window.open('expired_franchise_excel/' + this.expiredFilter);
            }
            else
            {
                window.open('mtop_report/export/'+ this.report.type + '/' + this.report.from + '/' + this.report.to + optional_route)
            }



        },


        returnSuccess(response) {
            this.suc = true;
            this.suc_msg = response.data.message;
            $('#create-modal').modal('hide');
        },

        returnFailed(error) {
            this.err = true;
            this.err_msg = error.response.data.err_msg;
            $('#create-modal').modal('hide');
        },

    },

    mounted() {
        $('#filter_options_1').hide();
        $('#filter_options_2').hide();

        this.initialData();
    }
}
</script>
