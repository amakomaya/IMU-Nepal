<template>
  <div>
    <filterable v-bind="filterable">
      <thead slot="thead">
      <tr>
        <th width="10px">Parent Case ID</th>
        <th>Name</th>
        <th>Age</th>
        <th>Sex</th>
        <th>District</th>
        <th>Municipality</th>
        <th></th>
      </tr>
      </thead>
      <tr slot-scope="{item}">
        <td>{{ item.case_id}}</td>
        <td>{{item.name}}</td>
        <td>{{item.age}}</td>
        <td>{{sex(item.sex)}}</td>
        <td>{{item.district.district_name}}</td>
        <td>{{item.municipality.municipality_name}}</td>

        <td>
            <button v-on:click="cictUpdateDate(item.case_id)" class="btn btn-primary btn-sm" title="CICT Update Data">
              <i class="fa fa-edit" aria-hidden="true"> CICT Update</i>
            </button>
          <br>
            <button v-on:click="deleteData(item.id)" class="btn btn-secondary btn-sm" title="Delete Data">
              <i class="fa fa-list" aria-hidden="true"> Contact List</i>
            </button>
        </td>
        <!-- </div>             -->
      </tr>
      <!--            <span>Selected Ids: {{ item }}</span>-->

    </filterable>
  </div>
</template>

<script type="text/javascript">
import Filterable from './CasesPaymentFilterable.vue'
import axios from "axios";

export default {
  components: {Filterable},
  data() {
    return {
      filterable: {
        url: '/data/api/cict-tracing',
        orderables: [
          {title: 'Name', name: 'name'},
          {title: 'Age', name: 'age'},
          {title: 'Created At', name: 'created_at'}
        ],
        filterGroups: [
          {
            name: 'Case',
            filters: [
              {title: 'Name', name: 'name', type: 'string'},
              {title: 'Age', name: 'age', type: 'numeric'},
              {title: 'Created At', name: 'created_at', type: 'datetime'}
            ]
          }
        ]
      }
    }
  },
  methods: {
    cictUpdateDate : function (id){
      window.open(
          '/admin/cict-tracing/section-one?case_id=' + id,
          '_self'
      );
    },
    sex(type) {
      switch (type) {
        case '1':
          return 'M';
        case '2':
          return 'F';
        default:
          return 'O';
      }
    },
  }
}
</script>