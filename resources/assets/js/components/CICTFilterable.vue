<template>
  <div class="filterable">
    <div class="panel">
      <div class="panel-heading">
        <div class="panel-title">
          <span>Case Records match</span>
          <select v-model="query.filter_match">
            <option value="and">All</option>
            <option value="or">Any</option>
          </select>
          <span>of the following:</span>
        </div>
      </div>
      <div class="panel-body">
        <div class="pull-right">
          <span class="label label-primary" style="font-size: 105%;">Total: {{ total }}</span>
        </div>
        <div class="filter">
          <div class="filter-item" v-for="(f, i) in filterCandidates">
            <div class="filter-column">
              <div class="form-group">
                <select class="form-control" @input="selectColumn(f, i, $event)">
                  <option value="">Select a filter</option>
                  <optgroup v-for="group in filterGroups" :label="group.name">
                    <option v-for="x in group.filters"
                            :value="JSON.stringify(x)"
                            :selected="f.column && x.name === f.column.name"
                    >
                      {{x.title}}
                    </option>
                  </optgroup>
                </select>
              </div>
            </div>
            <div class="filter-operator" v-if="f.column">
              <div class="form-group">
                <select class="form-control" @input="selectOperator(f, i, $event)">
                  <option v-for="y in fetchOperators(f)"
                          :value="JSON.stringify(y)"
                          :selected="f.operator && y.name === f.operator.name"
                  >
                    {{y.title}}
                  </option>
                </select>
              </div>
            </div>
            <template v-if="f.column && f.operator">
              <div class="filter-full" v-if="f.operator.component === 'single'">
                <input type="text" class="form-control" v-model="f.query_1">
              </div>
              <template  v-if="f.operator.component === 'double'">
                <div class="filter-query_1">
                  <input type="text" class="form-control" v-model="f.query_1">
                </div>
                <div class="filter-query_2">
                  <input type="text" class="form-control" v-model="f.query_2">
                </div>
              </template>
              <template v-if="f.operator.component === 'datetime_1'">
                <div class="filter-query_1">
                  <input type="text" class="form-control" v-model="f.query_1">
                </div>
                <div class="filter-query_2">
                  <select class="form-control" v-model="f.query_2">
                    <option>hours</option>
                    <option>days</option>
                    <option>months</option>
                    <option>years</option>
                  </select>
                </div>
              </template>
              <template v-if="f.operator.component === 'datetime_2'">
                <div class="filter-query_2">
                  <select class="form-control" v-model="f.query_1">
                    <option value="yesterday">yesterday</option>
                    <option value="today">today</option>
                    <option value="tomorrow">tomorrow</option>
                    <option value="last_month">last month</option>
                    <option value="this_month">this month</option>
                    <option value="next_month">next month</option>
                    <option value="last_year">last year</option>
                    <option value="this_year">this year</option>
                    <option value="next_year">next year</option>
                  </select>
                </div>
              </template>
              <template v-if="f.operator.component === 'datetime_3'">
                <div class="filter-query_1">
                  <v-nepalidatepicker title="Start of the Day" class="form-control" calenderType="Nepali" placeholder="YYYY-MM-DD" format="YYYY-MM-DD" v-model="f.query_1" :yearSelect="true" :monthSelect="true" />
                </div>
                <div class="filter-query_2">
                  <v-nepalidatepicker title="End of the day" class="form-control" calenderType="Nepali" placeholder="YYYY-MM-DD" format="YYYY-MM-DD" v-model="f.query_2" :yearSelect="true" :monthSelect="true" />
                </div>
              </template>
            </template>
            <div class="filter-remove" v-if="f">
              <button @click="removeFilter(f, i)">x</button>
            </div>
          </div>
          <div class="filter-controls">
            <button class="btn" @click="addFilter">+</button>
            <button class="btn btn-secondary" @click="resetFilter"
                    v-if="this.appliedFilters.length > 0"
            >
              Reset
            </button>
            <button class="btn btn-primary" @click="applyFilter">Apply Filter</button>
          </div>
        </div>
      </div>
    </div>
    <div class="filterable-export">
        <div class="btn btn-primary">

          <download-excel
              :fetch   = "exportToExcel"
              :fields = "json_fields"
              :name    = "excelFileName()"
          >
            Download Data
            <i class="fa fa-download" aria-hidden="true"></i>
          </download-excel>
        </div>
      <div>
        <span>Order by:</span>
        <select :disabled="loading" @input="updateOrderColumn">
          <option v-for="column in orderables"
                  :value="column.name"
                  :selected="column && column.name == query.order_column"
          >
            {{column.title}}
          </option>
        </select>
        <strong @click="updateOrderDirection">
          <span v-if="query.order_direction === 'asc'">&uarr;</span>
          <span v-else>&darr;</span>
        </strong>
      </div>
    </div>
    <div class="panel">
      <div class="panel-body">
        <table class="table table-striped sortable">
          <slot name="thead"></slot>
          <tbody>
            <slot v-if="collection.data && collection.data.length"
                v-for="item in collection.data"
                :item="item" :removeItemOnSuccess="removeItemOnSuccess"
            >
            </slot>
          </tbody>
        </table>
      </div>

      <div class="panel-body" v-if="!apiresponce" v-for="index in 5" :key="index">
          <ListLoader :width="100">
            <rect x="0" y="0" rx="3" ry="3" width="250" height="10" />
            <rect x="20" y="20" rx="3" ry="3" width="220" height="10" />
            <rect x="20" y="40" rx="3" ry="3" width="170" height="10" />
            <rect x="0" y="60" rx="3" ry="3" width="250" height="10" />
            <rect x="20" y="80" rx="3" ry="3" width="200" height="10" />
            <rect x="20" y="100" rx="3" ry="3" width="80" height="10" />
          </ListLoader>
      </div>

      <div class="panel-body" v-if="apiresponce === true && collection.data.length === 0">
          There are no records to display ..........
      </div>
      <div class="panel-footer">
        <div>
          <select v-model="query.limit" :disabled="loading" @change="updateLimit">
            <option>100</option>
            <option>250</option>
            <option>500</option>
            <option>1000</option>
          </select>
          <small> Showing {{collection.from}} - {{collection.to}} of {{collection.total}} entries.</small>
        </div>
        <div>
          <button class="btn" :disabled="!collection.prev_page_url || loading"
                  @click="prevPage">&laquo; Prev</button>
          <button class="btn" :disabled="!collection.next_page_url || loading"
                  @click="nextPage">Next &raquo;</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script type="text/javascript">
import Vue from 'vue'
import axios from 'axios'
import DataConverter from "ad-bs-converter";
import { ContentLoader, ListLoader } from 'vue-content-loader'

export default {
  components: {
    ContentLoader, ListLoader
  },
  props: {
    url: String,
    filterGroups: Array,
    orderables: Array,
  },
  data() {
    return {
      total: 0,
      loading: true,
      apiresponce : false,
      appliedFilters: [],
      filterCandidates: [],
      file : '',
      query: {
        order_column: 'created_at',
        order_direction: 'desc',
        filter_match: 'and',
        limit: 100,
        page: 1
      },
      collection: {
        data: []
      },
      json_fields: {
        'S.N': 'serial_number',
        'Case ID' : 'case_id',
        'Name': 'name',
        'Age': 'age',
        'Gender': 'sex',
        'Phone': 'emergency_contact_one',
        'District' : 'district',
        'Municipality' : 'municipality',
        'CICT Initiated Date' : 'cict_initiated_date',
        'Device Used' : 'regdev'
      }
    }
  },
  computed: {
    fetchOperators() {
      return (f) => {
        return this.availableOperators().filter((operator) => {
          if(f.column && operator.parent.includes(f.column.type)) {
            return operator
          }
        })
      }
    },
  },
  mounted() {
    this.fetch()
    this.addFilter()
  },
  methods: {
    ad2bs: function () {
      var dateObject = new Date();

      var dateFormat = dateObject.getFullYear()  + "/" + (dateObject.getMonth()+1) + "/" + dateObject.getDate();

      let dateConverter = DataConverter.ad2bs(dateFormat);

      return dateConverter.en.year + '-' + dateConverter.en.month + '-' + dateConverter.en.day;

    },
    removeItemOnSuccess(item) {
      let removeIndex = [];
      this.collection.data.find((d, index)=>{
        if (d.id == item.id){
          removeIndex.push(index);
        }
      });
      removeIndex.map(index => {
        this.collection.data.splice(index, 1);
      });
    },
    updateOrderDirection() {
      if(this.query.order_direction === 'desc') {
        this.query.order_direction = 'asc'
      } else {
        this.query.order_direction = 'desc'
      }
      this.applyChange()
    },
    updateOrderColumn(e) {
      const value = e.target.value
      Vue.set(this.query, 'order_column', value)
      this.applyChange()
    },
    excelFileName : function(){
      var ext = '.xls';
      return 'IMU Nepal Export Data '+ new Date()+ext;
    },
    exportToExcel() {
      let self = this;
      if (confirm("Do you want to Download all records in excel ! ")) {
        let list=[];
        let role = this.$userRole;
        this.collection.data.map(function(data, key) {
          let exportableData = {};
          let formattedGenderObject = {1:"Male", 2:"Female", 3:"Other"};
          exportableData.serial_number = key +1;
          exportableData.case_id = data.case_id;
          exportableData.name = data.name;
          exportableData.age = data.age;
          exportableData.sex = formattedGenderObject[data.sex];
          exportableData.emergency_contact_one = data.emergency_contact_one;
          exportableData.district = data.municipality.district_name;
          exportableData.municipality = data.municipality ? data.municipality.municipality_name : '';
          exportableData.cict_initiated_date = data.cict_initiated_date;
          exportableData.regdev = data.regdev;
          list.push(exportableData);
        });
        return list;
      }
    },
    resetFilter() {
      this.appliedFilters.splice(0)
      this.filterCandidates.splice(0)
      this.addFilter()
      this.query.page = 1
      this.applyChange()
    },
    applyFilter() {
      Vue.set(this.$data, 'appliedFilters',
          JSON.parse(JSON.stringify(this.filterCandidates))
      )
      this.query.page = 1;
      this.applyChange()
    },
    removeFilter(f, i) {
      this.filterCandidates.splice(i, 1)
    },
    selectOperator(f, i, e) {
      let value = e.target.value
      if(value.length === 0) {
        Vue.set(this.filterCandidates[i], 'operator', value)
        return
      }

      let obj = JSON.parse(value)

      Vue.set(this.filterCandidates[i], 'operator', obj)

      this.filterCandidates[i].query_1 = null
      this.filterCandidates[i].query_2 = null

      // set default query

      switch(obj.name) {
        case 'in_the_past':
        case 'in_the_next':
          this.filterCandidates[i].query_1 = 28
          this.filterCandidates[i].query_2 = 'days'
          break;
        case 'in_the_peroid':
          this.filterCandidates[i].query_1 = 'today'
          break;
        case 'in_the_custom_selected_period':
          this.filterCandidates[i].query_1 = this.ad2bs()
          this.filterCandidates[i].query_2 = this.ad2bs()
                break;
      }
    },
    selectColumn(f, i, e) {
      let value = e.target.value
      if(value.length === 0) {
        Vue.set(this.filterCandidates[i], 'column', value)
        return
      }

      let obj = JSON.parse(value)

      Vue.set(this.filterCandidates[i], 'column', obj)

      // set default operator: todo
      switch(obj.type) {
        case 'numeric':
          this.filterCandidates[i].operator = this.availableOperators()[4]
          this.filterCandidates[i].query_1 = null
          this.filterCandidates[i].query_2 = null
          break;
        case 'string':
          this.filterCandidates[i].operator = this.availableOperators()[6]
          this.filterCandidates[i].query_1 = null
          this.filterCandidates[i].query_2 = null
          break;
        case 'datetime':
          this.filterCandidates[i].operator = this.availableOperators()[9]
          this.filterCandidates[i].query_1 = 28
          this.filterCandidates[i].query_2 = 'days'
          break;
        case 'counter':
          this.filterCandidates[i].operator = this.availableOperators()[14]
          this.filterCandidates[i].query_1 = null
          this.filterCandidates[i].query_2 = null
          break;
      }
    },
    addFilter() {
      this.filterCandidates.push({
        column: '',
        operator: '',
        query_1: null,
        query_2: null
      })
    },
    applyChange() {
      this.fetch()
    },
    updateLimit() {
      this.query.page = 1
      this.applyChange()
    },
    prevPage() {
      if(this.collection.prev_page_url) {
        this.query.page = Number(this.query.page) - 1
        this.applyChange()
      }
    },
    nextPage() {
      if(this.collection.next_page_url) {
        this.query.page = Number(this.query.page) + 1
        this.applyChange()
      }
    },
    checkPermission(value){
      var arr = this.$userPermissions.split(',');
      return arr.includes(value);
    },
    getFilters() {
      const f = {}

      this.appliedFilters.forEach((filter, i) => {
        f[`f[${i}][column]`] = filter.column.name
        f[`f[${i}][operator]`] = filter.operator.name
        f[`f[${i}][query_1]`] = filter.query_1
        f[`f[${i}][query_2]`] = filter.query_2
      })

      return f
    },
    fetch() {
      this.loading = true
      const filters = this.getFilters()

      const params = {
        ...filters,
        ...this.query
      }
      axios.get(this.url, {params: params})
          .then((res) => {
            Vue.set(this.$data, 'collection', res.data.collection)
            this.query.page = res.data.collection.current_page
            this.apiresponce = true
            this.total = res.data.total
          })
          .catch((error) => {

          })
          .finally(() => {
            this.loading = false
          })
    },
    availableOperators() {
      return [
        {title: 'equal to', name: 'equal_to', parent: ['numeric', 'string'], component: 'single'},
        {title: 'not equal to', name: 'not_equal_to', parent: ['numeric', 'string'], component: 'single'},
        {title: 'less than', name: 'less_than', parent: ['numeric'], component: 'single'},
        {title: 'greater than', name: 'greater_than', parent: ['numeric'], component: 'single'},

        {title: 'between', name: 'between', parent: ['numeric'], component: 'double'},
        {title: 'not between', name: 'not_between', parent: ['numeric'], component: 'double'},

        {title: 'contains', name: 'contains', parent: ['string'], component: 'single'},
        {title: 'starts with', name: 'starts_with', parent: ['string'], component: 'single'},
        {title: 'ends with', name: 'ends_with', parent: ['string'], component: 'single'},

        {title: 'in the past', name: 'in_the_past', parent: ['datetime'], component: 'datetime_1'},
        {title: 'in the next', name: 'in_the_next', parent: ['datetime'], component: 'datetime_1'},
        {title: 'in the period', name: 'in_the_peroid', parent: ['datetime'], component: 'datetime_2'},
        {title: 'in the custom selected period', name: 'in_the_custom_selected_period', parent: ['datetime'], component: 'datetime_3'},

        {title: 'equal to', name: 'equal_to_count', parent: ['counter'], component: 'single'},
        {title: 'not equal to', name: 'not_equal_to_count', parent: ['counter'], component: 'single'},
        {title: 'less than', name: 'less_than_count', parent: ['counter'], component: 'single'},
        {title: 'greater than', name: 'greater_than_count', parent: ['counter'], component: 'single'},
      ]
    }
  }
}
</script>
<style lang="scss" scoped>
$color-error: #e75650;
$color-primary: #3aa3e3;
$color-secondary: #2c405a;
$color-success: #5cb85c;
$color-warning: #f0ad4e;
$text-color: #484746;
$bg-color: #fafafa;
$bg-color-light: #fcfcfc;
$border-color: rgba(63, 63, 68, 0.1);

$font-family: Helvetica, sans-serif;

$box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);

html {
  width: 100%;
  height: 100%;
}

body {
  width: 100%;
  height: 100%;
  margin: 0;
  color: $text-color;
  background: $bg-color;
  font-family: $font-family;
  font-size: 13px;
  line-height: 16px;
  -webkit-font-smoothing: antialiased;
}

* {
  box-sizing: border-box;
}

.container {
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
}

.navbar {
  background: #fff;
  height: 45px;
  border-bottom: 2px solid $border-color;
}

.content {
  width: 1000px;
  margin: 0 auto;
  overflow-y: scroll;
  padding: 15px;
}

.panel {
  background: #fff;
  margin-bottom: 16px;
  border-radius: 2px;
  box-shadow: $box-shadow;
  &-heading {
    padding: 8px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid $border-color;
  }
  &-title {
    font-size: 18px;
    line-height: 24px;
  }
  &-body {
    padding: 8px;
  }
  &-footer {
    padding: 8px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-top: 1px solid $border-color;
  }
}

.table {
  width: 100%;
  border-collapse: collapse;
  thead {
    tr {
      th {
        text-align: left;
        border-bottom: 2px solid $border-color;
        padding: 5px;
        font-weight: bold;
      }
    }
  }
  tbody {
    tr {
      border-top: 1px solid $border-color;
      td {
        vertical-align: top;
        padding: 5px;
      }
      &:nth-of-type(odd) {
        background: $bg-color-light;
      }
    }
  }
}

select {
  background: #fafafa;
  border: none;
  border-bottom: 1px solid rgba(0, 0, 0, 0.01);
  border-radius: 1px;
  box-shadow: inset 0 1px 1px 0 rgba(0, 0, 0, 0.1);
}

.form-control {
  color: $text-color;
  line-height: 13px;
  font-size: 13px;
  height: 32px;
  background: #fafafa;
  border: none;
  border-bottom: 1px solid rgba(0, 0, 0, 0.01);
  border-radius: 1px;
  box-shadow: inset 0 1px 1px 0 rgba(0, 0, 0, 0.1);
  padding: 8px;
  width: 100%;
  display: block;
}

.filter {
  &-item {
    margin-bottom: 15px;
    display: flex;
  }
  &-column {
    padding-right: 15px;
    width: 25%;
  }
  &-operator {
    padding-right: 15px;
    width: 25%;
  }
  &-query_1 {
    width: 24%;
    padding-right: 15px;
  }
  &-full {
    width: 48%;
  }
  &-query_2 {
    width: 24%;
  }
  &-remove {
    width: 2%;
    text-align: right;
    button {
      color: $color-error;
      background: transparent;
      border: none;
      font-weight: bold;
    }
  }
}

.btn {
  border: none;
  border-radius: 2px;
  padding: 5px 10px;
  display: inline-block;
  position: relative;
  text-shadow: none;
  cursor: pointer;
  transition: background 0.2s;
  color: $text-color;
  background: #ececec;
  border-bottom: 1px solid darken(#ececec, 10%);
  &-primary {
    background: $color-primary;
    color: #fff;
    border-bottom: 1px solid darken($color-primary, 10%);
  }

  &-secondary {
    background: $color-secondary;
    color: #fff;
    border-bottom: 1px solid darken($color-secondary, 10%);
  }

  &-success {
    background: $color-success;
    color : #fff;
    border-bottom: 1px solid darken($color-success, 10%);
  }

  &-danger {
    background: $color-error;
    color : #fff;
    border-bottom: 1px solid darken($color-error, 10%);
  }

  &-warning {
    background: $color-warning;
    color : #fff;
    border-bottom: 1px solid darken($color-warning, 10%);
  }
}

.filterable {
  &-export {
    margin-bottom: 15px;
    display: flex;
    justify-content: space-between;
  }
}

</style>