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
                <li class="d-inline align-middle"  style="font-size: 12px">MTFRU</li>
                <li class="d-inline align-middle mr-1 ml-1" style="font-size: 5px; color: #95a5a6;"><i class="fas fa-circle"></i></li>
                <li class="d-inline align-middle" style="font-size: 12px; color: #95a5a6">System Parameters</li>
            </ul>
        </div>

        <div class="main-content">
            <div class="card mt-5" style="width: 600px; margin: 0 auto;">

                <div class="card-header">
                    <h4 style="font-size: 18px; margin: 0">System Parameters</h4>
                </div>

                <div class="card-body">
                        <label for="body_number">Body Numbers</label>

                        <div class="form-group d-flex justify-content-start" id="body_number">
                            <p class="text-bold" style="margin: 5px 10px 0 0;">From: </p>
                            <input type="text" class="form-control mr-3" id="body_number_from" v-model="bodyNumberFromValue">
                            <p class="text-bold" style="margin: 5px 10px 0 0;">To: </p>
                            <input type="text" class="form-control" id="body_number_to" v-model="bodyNumberToValue">
                        </div>

                        <button v-if="adding" class="form-control btn btn-primary d-inline-block" v-on:click="saveRecord">
                            <i class="fas fa-check mr-1"></i>Save
                        </button>

                        <button v-else class="form-control btn-success d-inline-block" v-on:click="editRecord">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </button>
                </div>

                <div class="card-footer">

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
            errors: [],
            barangayCodeTableData: [],

            //values
            bodyNumberToValue: null,
            bodyNumberFromValue: null,

            err_msg: null,
            suc_msg: null,
            adding: false,
            err: false,
            suc: false,
            loader: false,
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
            this.initialData();
        },

        initialData() {
            axios.get('parameter/getrecord')
            .then(response =>
            {
                this.bodyNumberFromValue = response.data.body_number_from;
                this.bodyNumberToValue = response.data.body_number_to;
            });
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

        editRecord() {
            this.adding = true;
            $('#body_number_from').attr('readonly', false);
            $('#body_number_to').attr('readonly', false);
        },

        saveRecord() {
            this.loader = true;

            axios.patch('parameter/update', {
                body_number_from: this.bodyNumberFromValue,
                body_number_to: this.bodyNumberToValue
            })
            .then(response =>
            {
                this.returnSuccess(response);
                this.adding = false;
                this.initialData();
                $('#body_number_from').attr('readonly', true);
                $('#body_number_to').attr('readonly', true);
            })
            .catch(error =>
            {
                this.returnFailed(error);

            })
            .finally(() => this.loader = false);
        },

    },

    mounted() {
        $('#body_number_from').attr('readonly', true);
        $('#body_number_to').attr('readonly', true);

        this.initialData();
    }
}
</script>
