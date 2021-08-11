<template>
    <div>

        <div style="position: absolute; top: 0; left: 0; z-index: 1000; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.1);" v-if="loader">
            <div style="position: absolute; top: 50%; left: 50%; transform:translate(-50%, -50%)">
                <img src="public/loader/loader.gif" alt="loader">
            </div>
        </div>
        
        <!--- <h2>Zipcode</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group d-flex justify-content-between">
                    <div>
                        <a v-on:click="pdfModal" class="btn btn-dark">Print to PDF</a>
                    </div>
                </div>
            </div>
        </div>--->
    </div>
</template>

<script>
export default {
    data() {
        return {
            loader: false,
            tableData: [],
            errors: [],
        }
    },
    methods: {
        pdfModal() {
            this.loader = true;
            axios({
                url: 'home/pdf',
                method: 'GET',
                responseType: 'blob',
                })
                .then(response => {
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', 'file.pdf');
                    document.body.appendChild(link);
                    link.click();
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                })
                .finally(() => this.loader = false);
        }
    },
    mounted() {

    }
}
</script>
