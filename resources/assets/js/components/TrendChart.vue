<template>
  <div class="container">
    <line-chart 
        class="h-100"
        v-if="loaded" 
        :chartData="chartData" 
        :options="options"
        :width="null" 
        :height="null"
        :styles="styles" />
  </div>
</template>

<script>
import moment from "moment";
import LineChart from "./TrendChartModel.vue";

export default {
  name: "LineChartContainer",
  components: { LineChart },

  props: {
    company: {
      type: Number,
      default: 0
    },
    start: {
      type: String,
      required: true
    },
    end: {
      type: String,
      required: true
    }
  },

  data: () => ({
    loaded: false,
    chartData: null,
    options: {},
    styles: {}
  }),

  async mounted() {
    this.loaded = false;

    try {
      const response = await fetch(
        "/api/v1/trend/" + this.company + "/" + this.start + "/" + this.end
      );
      const data = await response.json();

      let stats = {
          labels: [],
          datasets: [
              {
                  label: 'Sessions',
                  backgroundColor: 'rgba(0,88,120,.6)',
                  borderColor: '#005878',
                  borderWidth: 3,
                  pointBackgroundColor: '#FFF',
                  pointBorderColor: '#005878',
                  pointBorderWidth: 3,
                  data: [],
                  order: 2
              },
              {
                  label: 'Users',
                  backgroundColor: 'rgba(4,53,83,.8)',
                  borderColor: '#043553',
                  borderWidth: 3,
                  pointBackgroundColor: '#FFF',
                  pointBorderColor: '#043553',
                  pointBorderWidth: 2,
                  data: [],
                  order: 1
              },
              {
                  label: 'Page Views',
                  backgroundColor: 'rgba(181,190,52,.4)',
                  borderColor: '#B5BE34',
                  
                  borderWidth: 3,
                  pointBackgroundColor: '#FFF',
                  pointBorderColor: '#B5BE34',
                  pointBorderWidth: 2,
                  data: [],
                  order: 3
              }
          ]
      }

      data.forEach(item => {
        // console.log(moment(item.date, "YYYYMM").format("MMM YYYY"));
        stats.labels.push(moment(item.date, "YYYYMM").format("MMM YYYY"))
        stats.datasets[0].data.push(item.sessions)
        stats.datasets[1].data.push(item.users)
        stats.datasets[2].data.push(item.views)
      });

      this.chartData = stats;
      this.loaded = true;
    } catch (e) {
      console.error(e);
    }
  }
};
</script>