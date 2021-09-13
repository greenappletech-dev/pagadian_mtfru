<style>
    #box-icon {

        position: absolute;
        top: 28px;
        right: 30px;
        font-size: 60px;
        color: #000;
        opacity: 0.3;
    }
</style>
<template>

    <div class="p-3">

        <div style="position: absolute; top: 0; left: 0; z-index: 1000; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.2);" v-if="loader">
            <div style="position: fixed; top: 450px; left: 55%; transform:translate(-50%, -70%)">
                <img src="public/loader/loader.gif" alt="loader">
            </div>
        </div>

        <div class="row mb-3 pt-2 pb-2 d-flex justify-content-between align-items-center">
            <div class="col-sm-10">
                <h3 style="font-size: 24px; font-weight: 600; padding: 0; margin: 0;">MTOP Dashboard</h3>
            </div>

            <div class="col-sm-2 text-right input-group">
                <input type="month" class="form-control" v-model="filtered_date">

                <div class="input-group-append">
                    <button v-on:click="filter" id="filter" style="margin: 0; width: 50px; font-size: 14px;" class="btn-info rounded-right border-0 p-1">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
                <div class="card text-white bg-info" style="width: 100%">
                    <div class="card-body">
                        <h5 class="card-title" style="font-weight: 600; font-size: 40px">{{ applications }}</h5>
                        <h5 class="card-text mt-2" style="font-size: 15px">Application</h5>
                        <i id="box-icon" class="fas fa-file-signature"></i>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <div class="card text-white bg-danger" style="width: 100%;">
                    <div class="card-body">
                        <h5 class="card-title" style="font-weight: 600; font-size: 40px">{{ pendings }}</h5>
                        <h5 class="card-text mt-2" style="font-size: 15px">Pending</h5>
                        <i id="box-icon" class="fas fa-pause"></i>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <div class="card text-white bg-warning" style="width: 100%">
                    <div class="card-body">
                        <h5 class="card-title" style="font-weight: 600; font-size: 40px">{{ payment }}</h5>
                        <h5 class="card-text mt-2" style="font-size: 15px">Paid</h5>
                        <i id="box-icon" class="fas fa-cash-register"></i>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <div class="card text-white bg-success" style="width: 100%">
                    <div class="card-body">
                        <h5 class="card-title" style="font-weight: 600; font-size: 40px">{{ completed }}</h5>
                        <h5 class="card-text mt-2" style="font-size: 15px">Completed</h5>
                        <i id="box-icon" class="fas fa-check"></i>
                    </div>
                </div>
            </div>
        </div>



        <!--    Chart    -->

        <div class="charts container-fluid p-0">

            <div class="row">
                <div class="col-12 col-sm-12 col-md-5">
                    <div class="card container-fluid">

                        <div class="card-header" style="width: 100%">
                            <h3 style="font-size: 16px; padding: 0; margin: 0">MTOP Monthy Revenue</h3>
                        </div>

                        <div class="card-body">
                            <h3 style="font-size: 25px; font-weight: 600">FY: {{ year }}</h3>
                            <column-chart thousands="," :stacked="true" :data="revenue"></column-chart>
                        </div>
                    </div>
                </div>


                <div class="col-12 col-sm-12 col-md-7">
                    <div class="card container-fluid">

                        <div class="card-header" style="width: 100%">
                            <h3 style="font-size: 16px; padding: 0; margin: 0">Daily Transactions</h3>
                        </div>

                        <div class="card-body" style="overflow: auto">
                            <h3 style="font-size: 25px; font-weight: 600">For the Month of {{ month }}</h3>
                            <column-chart thousands="," :stacked="true" :data="daily"></column-chart>
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
            loader: false,
            transaction: [],
            tableData: [],
            errors: [],
            revenue: [],
            daily: [],
            months : [
                'January',
                'February',
                'March',
                'April',
                'May',
                'June',
                'July',
                'August',
                'September',
                'October',
                'November',
                'December',
            ],

            filtered_date: '',
            month: '',
            year: '',

            applications: 0,
            pendings: 0,
            payment: 0,
            completed: 0,
        }
    },
    methods: {

        currentDate() {
            const current = new Date();
            const date = `${current.getMonth()+1}-${current.getFullYear()}`;
            return date;
        },


        transactionCount() {

            this.loader = true;

            axios.get('home/transactioncount/' + this.filtered_date)
            .then(response => {
                this.applications = response.data.application[0].total;
                this.pendings = response.data.pending[0].total;
                this.payment = response.data.payment[0].total;
                this.completed = response.data.completed[0].total;
            })

            axios.get('home/monthlyrevenue/' + this.year)
            .then(response => {
                this.revenue = response.data.revenue;
            })


            axios.get('home/dailytransactions/' + this.filtered_date)
            .then(response => {
                this.daily = response.data.daily;
            })
            .finally(
                () => this.loader = false
            );
        },

        filter() {
            const selected_date = new Date(this.filtered_date);
            this.month = this.months[selected_date.getMonth()];
            this.year = selected_date.getFullYear();
            this.transactionCount();
        },

    },
    mounted() {
        const current = new Date();
        const date = `${current.getFullYear()}-${('0'+(current.getMonth()+1)).slice(-2)}`;
        this.filtered_date = date;
        $('#filter').click();
    }
}
</script>
