<template>
          <div class="text-center">
            <div v-if="!vaccinationStatus"> No records found !!! </div>
            <div v-if="vaccinationStatus" width='100%"'>
                <GChart
                        :settings="{ packages: ['table'] }"
                        type="Table"
                        :data="data"
                        :options="chartOptions"
                        ref="gChart"
                />
            </div>
        </div>
</template>

<script>
    import { GChart } from 'vue-google-charts'

export default {
    components: {GChart},
    props: {
            data: Array,
            vaccinationStatus : Boolean        },
  data() {
    return {
      chartOptions: {
        chart: {
            title: "Vaccination Report",
            subtitle: "Vaccine name, Vaccine period, and Vaccinated Date",
        }
      }
    }
  },
}
</script>