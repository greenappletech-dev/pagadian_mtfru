<template>
    <div class="main-container p-4">
        <div style="position: absolute; top: 0; left: 0; z-index: 1000; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.2);" v-if="loader">
            <div style="position: fixed; top: 450px; left: 55%; transform:translate(-50%, -70%)">
                <img src="/loader/loader.gif" alt="loader">
            </div>
        </div>
        
        <div class="m-4 mt-4">
            <div class="content-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#" class="text-dark">Master Data</a>
                        </li>
                        <li class="breadcrumb-item active text-success" aria-current="page">System Setting</li>
                    </ol>
                </nav>
            </div>

            <div class="mb-1">
                <div class="d-flex justify-content-end filter">
                    <button class="btn btn-primary ml-2" @click="openAssignChargesModal">
                        <i class="fas fa-cog"></i> Assign Charges
                    </button>
                </div>
            </div>

            <!-- Transaction Type Management -->
            <div class="card mb-4">
                <div class="card-header"><b>Transaction Types</b></div>
                <div class="card-body">
                    <v-client-table :data="transactionTypes" :columns="transactionTypeColumns" :options="transactionTypeOptions">
                        <template slot="actions" slot-scope="props">
                            <button class="btn btn-sm btn-outline-primary mr-1" @click="editTransactionType(props.row)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" @click="deleteTransactionType(props.row)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </template>
                    </v-client-table>
                </div>
            </div>

            <!-- Charge Management -->
            <div class="card">
                <div class="card-header"><b>Charge List</b></div>
                <div class="card-body">
                    <v-client-table :data="charges" :columns="chargeColumns" :options="chargeOptions">
                        <template slot="actions" slot-scope="props">
                            <button class="btn btn-sm btn-outline-primary mr-1" @click="editAssignCharges(props.row)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" @click="deleteCharge(props.row)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </template>
                    </v-client-table>
                </div>
            </div>

            <!-- Assign Charges Modal -->
            <div class="modal fade" id="assign-charges-modal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header text-white" style="background-color: #fd7e14;">
                            <h5 class="modal-title">Assign Charges to Transaction Type</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Transaction Type</label>
                                <select v-model="selectedTransactionType" @change="loadChargesForType" class="form-control">
                                    <option value="" disabled>Select transaction type</option>
                                    <option v-for="type in transactionTypes" :value="type.id" :key="type.id">
                                        {{ type.name }}
                                    </option>
                                </select>
                            </div>

                            <div v-if="availableCharges.length > 0" class="mt-4">
                                <h5>Available Charges</h5>
                                <div class="charge-list-container">
                                    <div v-for="charge in availableCharges" :key="charge.id" 
                                         class="charge-item p-2 mb-2 border rounded d-flex justify-content-between align-items-center"
                                         :class="{'bg-light': isChargeSelected(charge.id)}">
                                        <div>
                                            <strong>{{ charge.name }}</strong>
                                            <div class="text-muted small">
                                                <template v-if="charge.name === 'Surcharge 25% (MTFRU)' || charge.name === 'Interest 2% (MTFRU)'">
                                                    <input type="number" class="form-control form-control-sm" style="width: 120px; display: inline-block;" v-model.number="charge.price" @input="updateChargePrice(charge)" min="0" step="0.01"/>
                                                </template>
                                                <template v-else>
                                                    {{ formatPrice(charge.price) }}
                                                </template>
                                            </div>
                                        </div>
                                        <button @click="toggleChargeSelection(charge)" 
                                                class="btn btn-sm"
                                                :class="isChargeSelected(charge.id) ? 'btn-danger' : 'btn-primary'">
                                            {{ isChargeSelected(charge.id) ? 'Remove' : 'Add' }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div v-else-if="selectedTransactionType" class="text-center py-4">
                                No charges available for this transaction type.
                            </div>
                        </div>
                        <div class="modal-footer d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="mt-2" style="font-size: 18px">Total/s: {{ formatPrice(transactionTotals) }}</h2>
                            </div>
                            <div>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" @click="saveChargeAssignment">
                                    Save Assignment
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add/Edit Charge Modal -->
            <div class="modal fade" id="charge-modal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header text-white" style="background-color: #fd7e14;">
                            <h5 class="modal-title">{{ isEditingCharge ? 'Edit Charge' : 'Add New Charge' }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Charge Name</label>
                                <input type="text" class="form-control" v-model="currentCharge.name">
                            </div>
                            <div class="form-group">
                                <label>Price</label>
                                <input type="number" step="0.01" class="form-control" v-model="currentCharge.price">
                            </div>
                        </div>
                        <div class="modal-footer d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="mt-2" style="font-size: 18px">Total/s: {{ formatPrice(transactionTotals) }}</h2>
                            </div>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" @click="saveCharge">
                                {{ isEditingCharge ? 'Update' : 'Save' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            loader: false,
            currentSystemSettingId: null,
            // Transaction Types
            transactionTypes: [],
            transactionTypeColumns: ['id', 'name',],
            transactionTypeOptions: {
                headings: {
                    id: 'ID',
                    name: 'Transaction Type',
                },
                sortable: ['id', 'name'],
                filterable: ['name']
            },
            
            // Charges
            charges: [],
            chargeColumns: ['id', 'transaction_type', 'charge_names', 'total_price', 'actions'],
            chargeOptions: {
                headings: {
                    id: 'ID',
                    transaction_type: 'Transaction Type',
                    charge_names: 'Charge Name',
                    total_price: 'Price',
                    actions: 'Actions'
                },
                sortable: ['id', 'transaction_type'],
                filterable: ['transaction_type', 'charge_name'],
                templates: {
                    total_price: function(h, row) {
                        return this.formatPrice(row.total_price);
                    }.bind(this)
                }
            },
            
            // Charge Assignment
            selectedTransactionType: '',
            availableCharges: [],
            selectedChargeIds: [],
            
            // Charge Management
            currentCharge: {
                id: null,
                name: '',
                price: 0,
                transaction_type: ''
            },
            isEditingCharge: false
        };
    },
    computed: {
        transactionTotals() {
            return this.selectedChargeIds.reduce((total, chargeId) => {
                const charge = this.availableCharges.find(c => c.id === chargeId);
                return total + (charge ? parseFloat(charge.price) : 0);
            }, 0);
        }
    },
    methods: {
        // Initialization
        init() {
            this.loadTransactionTypes();
            this.loadAllCharges();
        },
        getRecords() {
            axios.get('/setting/getrecords').then(response => {
                console.log(response.data);
                this.charges = response.data.data || [];
                this.loadChargesForType();
            })
        },

        updateChargePrice(charge){
            if(charge.price < 0) charge.price = 0;
        },
        
        // Data Loading
        loadTransactionTypes() {
            this.loader = true;
            axios.get('/setting/transaction_types')
                .then(response => {
                    this.transactionTypes = response.data;
                })
                .finally(() => {
                    this.loader = false;
                });
        },
        
        loadAllCharges() {
            this.loader = true;
            axios.get('/setting/all_charges')
                .then(response => {
                    this.charges = response.data;
                })
                .finally(() => {
                    this.loader = false;
                });
        },
        
        loadChargesForType() {
            if (!this.selectedTransactionType) return;
            
            this.loader = true;

            axios.get(`/setting/charges_transaction/${this.selectedTransactionType}`)
                .then(response => {
                    this.availableCharges = response.data;
                    this.selectedChargeIds = [];
                }).finally(() => {
                    this.loader = false;
                });
        },
        
        loadSelectedCharges() {
            axios.get(`/setting/charges/${this.selectedTransactionType}/selectedfor`)
                .then(response => {
                    this.selectedChargeIds = response.data.map(charge => charge.id);
                });
        },
        
        // Charge Assignment
        openAssignChargesModal() {
            this.selectedTransactionType = '';
            this.availableCharges = [];
            this.selectedChargeIds = [];
            this.currentSystemSettingId = null;
            $('#assign-charges-modal').modal('show');
        },
        
        isChargeSelected(chargeId) {
            return this.selectedChargeIds.includes(chargeId);
        },
        
        toggleChargeSelection(charge) {
            const index = this.selectedChargeIds.indexOf(charge.id);
            if (index === -1) {
                this.selectedChargeIds.push(charge.id);
            } else {
                this.selectedChargeIds.splice(index, 1);
            }
        },
        
        saveChargeAssignment() {
            // console.log('Saving charge assignment...', this.selectedTransactionType);

            // const selectedCharges = this.availableCharges.filter(charge =>
            //     this.selectedChargeIds.includes(charge.id)
            // );
            // console.log('Selected Charge Objects:', selectedCharges);
            const selectedCharges = this.availableCharges.filter(charge =>
                this.selectedChargeIds.includes(charge.id)
            );
            
            const payload = {
                transaction_type: this.selectedTransactionType,
                charges: selectedCharges,
                total_price: this.transactionTotals
            };

            if (this.currentSystemSettingId){
                payload.system_setting_id = this.currentSystemSettingId;
            }

            axios.post('/setting/assign_charges', payload) 
                .then(response => {
                this.$swal({
                    title: 'Success',
                    icon: 'success',    
                    text: response.data.message,
                    type: 'success'
                });
                $('#assign-charges-modal').modal('hide');
                this.getRecords();
                this.currentSystemSettingId = null;
            }).catch(error => {
                let message = 'An errror occured.';
                if(error.response && error.response.status === 422){
                    message = error.response.data.message;
                }
                this.$swal({
                    title: 'Error',
                    icon: 'error',
                    text: message,
                    type: 'error'
                })
            })
        },
        
        // Charge Management
        addNewCharge() {
            this.isEditingCharge = false;
            this.currentCharge = {
                id: null,
                name: '',
                price: 0,
                transaction_type: ''
            };
            $('#charge-modal').modal('show');
        },
        
        editCharge(charge) {
            this.isEditingCharge = true;
            this.currentCharge = {
                id: charge.id,
                name: charge.name,
                price: charge.price,
                transaction_type: charge.transaction_type || ''
            };
            $('#charge-modal').modal('show');
        },

        editAssignCharges(systemSetting){
            this.selectedTransactionType = systemSetting.transaction_type;
            this.currentSystemSettingId = systemSetting.id;

            $('#assign-charges-modal').modal('show');

            this.loader = true;
            axios.get(`/setting/charges_transaction/${this.selectedTransactionType}`)
            .then(response => {
                this.availableCharges = response.data;
                return axios.get(`/setting/charges/${systemSetting.id}/selected`);
            }).then (response => {
                this.selectedChargeIds = response.data.map(charge => charge.charge_id);

                response.data.forEach(savedCharge => {
                    const idx = this.availableCharges.findIndex(c => c.id === savedCharge.charge_id);
                    if (idx !== -1) {
                        this.availableCharges[idx].price = parseFloat(savedCharge.total_price);
                    }
                });
            }).finally(() => {
                this.loader = false;
            });
        },
        
        saveCharge() {
            if (!this.currentCharge.name || !this.currentCharge.price) {
                this.$swal({
                    title: 'Error',
                    icon: 'warning',
                    text: 'Please fill all required fields',
                    type: 'error'
                });
                return;
            }
            
            const url = this.isEditingCharge 
                ? `/setting/charges/${this.currentCharge.id}`
                : '/setting/charges';
                
            const method = this.isEditingCharge ? 'put' : 'post';
            
            this.loader = true;
            axios[method](url, this.currentCharge)
                .then(response => {
                    this.$swal({
                        title: 'Success',
                        icon: 'success',
                        text: response.data.message,
                        type: 'success'
                    });
                    $('#charge-modal').modal('hide');
                    this.loadAllCharges();
                })
                .catch(error => {
                    this.$swal({
                        title: 'Error',
                        text: error.response.data.message || 'Failed to save charge',
                        type: 'error'
                    });
                })
                .finally(() => {
                    this.loader = false;
                });
        },
        deleteCharge(charge) {
            this.$swal({
                title: 'Are you sure?',
                text: 'Are you sure you want to delete this charge?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.loader = true;
                    axios.delete(`/setting/remove_available_charges/${charge.id}`)
                        .then(response => {
                            this.$swal({
                                title: 'Success',
                                icon: 'success',
                                text: response.data.message,
                                type: 'success'
                            });
                            this.getRecords();
                        })
                        .catch(error => {
                            let message = 'Failed to delete charge';
                            if (error.response && error.response.status === 404) {
                                message = 'Charge not found or already deleted.';
                            }
                            this.$swal({
                                title: 'Error',
                                text: message,
                                icon: 'error'
                            });
                        })
                        .finally(() => {
                            this.loader = false;
                        });
        }
    });
        },
        
        
        // Transaction Type Management
        editTransactionType(type) {
            // Implement if needed
        },
        
        deleteTransactionType(type) {
            // Implement if needed
        },
        
        // Helpers
        formatPrice(value) {
            if (!value && value !== 0) return '₱0.00';
            return '₱' + parseFloat(value).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
    },
    mounted() {
        this.init();
        this.loadChargesForType();
        this.getRecords();
    }
};
</script>

<style scoped>
.main-container {
    position: relative;
    min-height: 100vh;
}

.content-breadcrumb {
    margin-bottom: 20px;
}

.breadcrumb {
    background-color: white;
    padding: 0.75rem 1rem;
    border-radius: 0.25rem;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: '>';
    color: #6c757d;
    padding: 0 0.5rem;
}

.filter p, .filter button {
    font-size: 14px;
}

.card {
    margin-bottom: 20px;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.card-header {
    font-weight: bold;
    background-color: #f8f9fa;
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}

.charge-list-container {
    max-height: 400px;
    overflow-y: auto;
}

.charge-item {
    transition: all 0.3s ease;
}

.charge-item:hover {
    background-color: #f8f9fa !important;
}

.VueTables__search-field {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.VueTables__search-field label {
    margin-bottom: 0;
    margin-right: 10px;
    white-space: nowrap;
}

.VueTables__limit-field {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.VueTables__limit-field label {
    margin-bottom: 0;
    margin-right: 10px;
    white-space: nowrap;
}
</style>