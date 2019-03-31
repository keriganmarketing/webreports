Vue = require('vue');

Vue.component('report-form', require('./components/ReportForm.vue'));

const app = new Vue({
    el: '#app'
});
