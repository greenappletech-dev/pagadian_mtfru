/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */


require('./bootstrap');

window.Vue = require('vue').default;

import moment from "moment";
window.moment = moment;

import { ClientTable } from 'vue-tables-2';
Vue.use(ClientTable, {}, false, 'bootstrap4')

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('home-component', require('./components/HomeComponent.vue').default);
Vue.component('tricycle-component', require('./components/TricycleComponent.vue').default);
Vue.component('operator-component', require('./components/OperatorComponent.vue').default);
Vue.component('mtop-component', require('./components/MTOPComponent.vue').default);
Vue.component('mtop_entry-component', require('./components/MTOPEntryComponent.vue').default);
Vue.component('mtop_edit-component', require('./components/MTOPEditComponent.vue').default);
Vue.component('charges-component', require('./components/ChargesComponent.vue').default);
Vue.component('user-component', require('./components/UserComponent.vue').default);
Vue.component('system-parameter-component', require('./components/SystemParameterComponent.vue').default);
Vue.component('old_new-component', require('./components/OldNewFranchiseComponent.vue').default);
Vue.component('view_renewal-component', require('./components/MTOPViewRenewalComponent.vue').default);
Vue.component('renewal-component', require('./components/MTOPRenewalComponent.vue').default);
Vue.component('driver-component', require('./components/DriverComponent.vue').default);

/* MTOP REPORTS */
Vue.component('master_list-component', require('./components/MasterListComponent.vue').default);
Vue.component('mtop_report-component', require('./components/MTOPReportComponent').default);
Vue.component('mtop_charge_list-component', require('./components/MTOPChargesListComponent').default);

/* FVR COMPONENT */
Vue.component('banca-component', require('./components/BancaComponent.vue').default);
Vue.component('banca_entry-component', require('./components/BancaEntryComponent').default);
Vue.component('banca_edit-component', require('./components/BancaEditComponent').default);
Vue.component('captain-component', require('./components/BoatCaptain.vue').default);
Vue.component('fvr-component', require('./components/FVRComponent.vue').default);
Vue.component('fvr_entry-component', require('./components/FVREntryComponent.vue').default);
Vue.component('fvr_edit-component', require('./components/FVREditComponent.vue').default);
Vue.component('fvr_view_renewal-component', require('./components/FVRViewRenewalComponent.vue').default);
Vue.component('fvr_renewal-component', require('./components/FVRRenewalComponent.vue').default);
Vue.component('boat_type-component', require('./components/BoatTypeComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
