Vue = require('vue');

Vue.component('report-form', require('./components/ReportForm.vue').default);
Vue.component('trend-chart', require('./components/TrendChart.vue').default);

const app = new Vue({
    el: '#app'
});
