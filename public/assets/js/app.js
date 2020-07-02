Vue = require('vue');

Vue.component('report-form', require('./components/ReportForm.vue').default);

const app = new Vue({
    el: '#app'
});
