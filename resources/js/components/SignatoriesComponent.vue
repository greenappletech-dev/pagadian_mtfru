<template>
    <div class="main-container p-4">
        <!--LOADER AND MESSAGE BOX-->
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

        <!--    ENDS HERE-->

        <div class="user-button d-flex justify-content-between pt-3 pb-3">
            <ul class="breadcrumb-1 pl-0 m-0">
                <li class="d-inline align-middle" style="font-size: 12px">System</li>
                <li class="d-inline align-middle mr-1 ml-1" style="font-size: 5px; color: #95a5a6;"><i class="fas fa-circle"></i></li>
                <li class="d-inline align-middle" style="color: #95a5a6; font-size: 12px">Signatories</li>
            </ul>
        </div>

        <div class="main-content">
            <h2 class="d-inline align-middle">Signatories</h2>
            <div class="row mt-3 mb-3">
                <div class="col-6">
                    <button v-on:click="addSignatoriesForm" class="d-inline align-middle p-2 pl-4 pr-4 rounded border-0" style="background: rgba(255, 120, 31, 1); color: #fff; font-size: 14px; box-shadow: 2px 2px 2px rgba(165, 165, 165, 1)">Add Signatories</button>
                </div>
            </div>


        </div>

    </div>
</template>

<script>
export default {
    data() {
        return {
            err_msg: '',
            suc_msg: '',
            err: false,
            suc: false,
            loader: false,

            url: './user/export',
            url_pdf: './user/pdf',
            errors: [],
            user: [],
            adding: false,
            print: false,
            paperSize: 'Letter',
            paperOrientation: 'Portrait',
        }
    },
    methods: {
        errorHandler(errors){
            const error_handler = [];

            $.each(errors, function(key, value) {
                error_handler.push(value);
            });

            return error_handler;
        },

        addSignatoriesForm() {
            window.location.href = '/add-signatories';
        },

    },

    mounted() {
        // this.getRecord();
    }

}
</script>
