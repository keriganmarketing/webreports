<template>
    <div class="container">
        <div class="row justify-content-center py-4">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">Run Web Report</div>

                    <div class="card-body">
                        
                        <form :action="'/' + formData.company + '/report/' + formData.date" ref="reportForm">

                            <div class="row">
                                <div class="col-12 col-md-auto mb-2 mb-md-0">
                                    <select id="company" class="custom-select" v-model="formData.company" >
                                        <option value="">Select a Company</option>
                                        <option v-for="company in orderedCompanies" :key="company.index" :value="company.id" >{{ company.name }}</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-auto mb-4 mb-md-0 flex-grow-1">
                                    <select id="month" class="custom-select" v-model="formData.date" >
                                        <option value="">Select a Date</option>
                                        <option v-for="date in dates" :key="date.index" :value="date.year + '/' + date.month" >{{ date.name }}</option>
                                    </select>
                                </div>
                                <div v-if="formData.company != '' && formData.date != ''" class="col-auto">
                                    <button class="btn btn-primary" id="reportSubmit">Run report</button>
                                </div>
                            </div>

                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import _ from 'lodash';

    export default {
        props: {
            companies: {
                type: Object,
                default: () => []
            },
            dates: {
                type: Array,
                default: () => []
            }
        },

        computed: {
            orderedCompanies: function () {
                return _.orderBy(this.companies, 'name')
            }
        },

        data(){
            return {
                formData: {
                    'company' : '',
                    'date'    : ''
                }
            }
        },

        mounted() {
            console.log('Component mounted.')
        },
    }

</script>

