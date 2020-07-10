Vue = require('vue');

Vue.component('report-form', require('./components/ReportForm.vue').default);
Vue.component('trend-chart', require('./components/TrendChart.vue').default);

const app = new Vue({
    el: '#app',

    data() {
        return {
            sidebarOpen: true,
            mobileNavOpen: false
        }
    },

    methods: {
        toggleSidebar(){
            this.sidebarOpen = !this.sidebarOpen
        },
        toggleMobileNav(){
            this.mobileNavOpen = !this.mobileNavOpen
        }
    }

});
