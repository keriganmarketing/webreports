Vue = require('vue');

Vue.component('report-form', require('./components/ReportForm.vue').default);
Vue.component('trend-chart', require('./components/TrendChart.vue').default);
Vue.component('trend-form', require('./components/TrendForm.vue').default);
Vue.component('trend-builder-form', require('./components/TrendBuilderForm.vue').default);

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
