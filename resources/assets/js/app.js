import Vue from 'vue'
import VueRouter from 'vue-router'
import StatusIndicator from 'vue-status-indicator';
import Vuetify from 'vuetify'
import VueSweetalert2 from 'vue-sweetalert2';
import 'vuetify/dist/vuetify.min.css'
import DatePicker from 'vue2-datepicker'
import Dialog from 'v-dialogs'
import VueQRCodeComponent from 'vue-qrcode-component'
import VueHtmlToPaper from 'vue-html-to-paper';
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css';
import _ from 'lodash'
import Vuelidate from 'vuelidate'

import WomenList from './components/CasesRegisteredOrPending.vue'
import WomanListNegative from './components/CasesNegative'
import WomanListPositive from "./components/CasesPositive";
import PatientListLabReceived from './components/CasesLabRecieved'
import CasesClosedRecovered from './components/CasesClosedRecovered'
import CasesClosedDeath from './components/CasesClosedDeath'
import LabPatientList from './components/LabCases'

import SelectYearMonth from './components/SelectYearMonth'
import VNepaliDatePicker from 'v-nepalidatepicker';

import JsonExcel from 'vue-json-excel'
import VueMask from 'v-mask'
Vue.use(VueMask);
Vue.use(VNepaliDatePicker);

Vue.component('downloadExcel', JsonExcel)

Vue.prototype.$userRole = document.querySelector("meta[name='user-role']").getAttribute('content');

Vue.use(Vuelidate)
Vue.use(StatusIndicator);
Vue.use(VueRouter);
Vue.use(DatePicker);
Vue.use(Vuetify);
Vue.use(VueSweetalert2);
Vue.use(Dialog);
Vue.component('qr-code', VueQRCodeComponent)
Vue.use(VueHtmlToPaper);
Vue.component('v-select', vSelect)

Vue.component('women-list', WomenList);
Vue.component('women-list-negative', WomanListNegative);
Vue.component('women-list-positive', WomanListPositive);
Vue.component('lab-patient-list', LabPatientList);
Vue.component('women-list-lab-received', PatientListLabReceived)
Vue.component('cases-closed-recovered', CasesClosedRecovered)
Vue.component('cases-closed-death', CasesClosedDeath)
Vue.component('vaccination-chart');

Vue.component('select-year-month', SelectYearMonth)

const app = new Vue({
    el: '#app'
});