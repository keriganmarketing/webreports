<template>
    <form ref="reportForm" v-on:submit.prevent="submitForm">

        <div class="row">
            <div class="col-12 col-md-4 mb-2 mb-md-0">
                <select id="company" class="custom-select" v-model="formData.company" >
                    <option value="">Select a Company</option>
                    <option 
                        v-for="company in orderedCompanies" 
                        :key="company.index" 
                        :value="company.id" 
                    >{{ company.name }}</option>
                </select>
            </div>
            <div class="col-12 col-md-3 mb-2 mb-md-0">
                <select id="month" class="custom-select" v-model="formData.from" >
                    <option value="">From</option>
                    <option v-for="date in startDates" :key="date.index" :value="date.value" >{{ date.label }}</option>
                </select>
            </div>
            <div class="col-12 col-md-3 mb-4 mb-md-0">
                <select id="year" class="custom-select" v-model="formData.to" >
                    <option value="">To</option>
                    <option v-for="date in endDates" :key="date.index" :value="date.value" >{{ date.label }}</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary" id="reportSubmit" >Build</button>
            </div>
        </div>
        <div 
            v-if="results.length > 0"
            class="results-pane bg-light shadow p-3 border mt-4" 
            style="max-height:300px; overflow-y: scroll" >
            <div v-for="result in results" :key="result.index">
                <div class="company-trend mb-2">
                    <p class="m-0"><strong>{{result.company.name}}</strong></p>
                    <p class="m-0 text-danger" v-if="result.error">ERROR! Check Permissions.</p>
                    <div v-if="result.data.length > 0" >
                        <div 
                            class="m-0" 
                            v-for="record in result.data" 
                            :key="record.key"
                        >
                            <p class="m-0 text-success" v-if="record.updated == true">{{ prettyifyDate(record.date) }} has been updated</p>
                            <p class="m-0 text-dark" v-else >{{ prettyifyDate(record.date) }} is already up-to-date</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</template>

<script>
    import _ from 'lodash';
    import moment from 'moment';
    import axios from 'axios';

    export default {

        props: {
            companies: {
                type: Object,
                default: () => []
            }
        },

        data(){
            return {
                startDates: [],
                endDates: [],
                formData: {
                    from: '',
                    to: '',
                    company: ''
                },
                results: []
            }
        },

        computed: {
            orderedCompanies: function () {
                return _.orderBy(this.companies, 'name')
            }
        },

        mounted() {

            for (let i = 1; i < 50; i++) {
                this.startDates.push({
                    'value': this.moment().subtract(i, 'month').startOf('month').format('YYYY-MM-DD'),
                    'label': this.moment().subtract(i, 'month').startOf('month').format('MMMM Do YYYY')
                });
                this.endDates.push({
                    'value': this.moment().subtract(i, 'month').endOf('month').format('YYYY-MM-DD'),
                    'label': this.moment().subtract(i, 'month').endOf('month').format('MMMM Do YYYY')
                });
            }

            console.log('Component mounted.')
        },

        methods: {
            moment() {
                return moment();
            },

            prettyifyDate(dateString){
                return moment(dateString,'YYYYMM').format('MMM YYYY')
            },

            submitForm(){
                let vm = this;

                try {
                    axios.get('/api/v1/build/' + this.formData.company + '/' + this.formData.from + '/' + this.formData.to)
                        .then(response => {
                            vm.results.unshift(response.data.original[0])
                        });

                } catch (err) {
                    console.error(err);

                }
            }
        }
    }

</script>
