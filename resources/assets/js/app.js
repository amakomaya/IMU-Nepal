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

import WomenList from './components/Women.vue'
import  WomenEdit from './components/WomenEdit'
import WomanListNegative from './components/WomanListNegative'

import LabPatientList from './components/LabPatientList'

import BabyList from './components/BabyList.vue'
import BabyEdit from './components/BabyEdit.vue'

import QrCodeGenerate from './components/QrCodeGenerate.vue'

import SelectYearMonth from './components/SelectYearMonth'

import JsonExcel from 'vue-json-excel'
import VueMask from 'v-mask'
Vue.use(VueMask);

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
Vue.component('women-edit', WomenEdit);
Vue.component('women-list-negative', WomanListNegative);
Vue.component('lab-patient-list', LabPatientList);

Vue.component('baby-list', BabyList);
Vue.component('baby-edit', BabyEdit);

Vue.component('vaccination-chart');

Vue.component('select-year-month', SelectYearMonth)

Vue.component('qr-code-generate', QrCodeGenerate)

const app = new Vue({
    el: '#app'
});
