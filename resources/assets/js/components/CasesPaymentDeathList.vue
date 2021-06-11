<template>
  <div>
    <filterable v-bind="filterable">
      <thead slot="thead">
      <tr>
        <th width="10px"></th>
        <th>Name</th>
        <th>Age</th>
        <th>Phone</th>
        <th>Address</th>
        <th>Health Condition</th>
        <th>Paid / Free</th>
        <th>Register Date</th>
        <th>Date of Outcome</th>
        <th>Action</th>
      </tr>
      </thead>
      <tr slot-scope="{item}">
        <td>
        </td>
        <td>{{item.name}}</td>
        <td>{{item.age}}</td>
        <td>{{item.phone}}</td>
        <td>{{ item.address }}</td>
        <td>{{ formattedHealthCondition(item.health_condition, item.health_condition_update) }}</td>
        <td>{{ formattedSafeOrFree(item.self_free) }}</td>
        <td>{{ item.register_date_np }}</td>
        <td>{{ item.date_of_outcome }}</td>
        <td>
          <div v-show="checkEditButton()">
            <button v-on:click="editData(item.id)" class="btn btn-primary btn-sm" title="Update Data">
              <i class="fa fa-edit" aria-hidden="true"> Update</i>
            </button>
          </div>
        </td>
        <!-- </div>             -->
      </tr>
      <!--            <span>Selected Ids: {{ item }}</span>-->

    </filterable>
  </div>
</template>

<script type="text/javascript">
import Filterable from './CasesPaymentFilterable.vue'

export default {
  components: {Filterable},
  data() {
    return {
      filterable: {
        url: '/data/api/cases-payment-death',
        orderables: [
          {title: 'Name', name: 'name'},
          {title: 'Age', name: 'age'},
          {title: 'Register Date', name: 'register_date_en'},
          {title: 'Date of Outcome', name: 'date_of_outcome_en'},
          {title: 'Created At', name: 'created_at'},
        ],
        filterGroups: [
          {
            name: 'Case',
            filters: [
              {title: 'Name', name: 'name', type: 'string'},
              {title: 'Age', name: 'age', type: 'numeric'},
              {title: 'Phone Number', name: 'phone', type: 'string'},
              {title: 'Register Date', name: 'register_date_en', type: 'datetime'},
              {title: 'Date of Outcome', name: 'date_of_outcome_en', type: 'datetime'},
              {title: 'Created Date', name: 'created_at', type: 'datetime'},
            ]
          }
        ]
      }
    }
  },
  methods: {
    formattedHealthCondition : function (type, update) {
      if (update !== null){
        var data = JSON.parse(update).slice(-1)[0] ;
        type = parseInt(data.id);
      }
      switch (type) {
        case 1:
          return 'No Symptoms';
        case 2:
          return 'Mild';
        case 3:
          return 'Moderate ( HDU )';
        case 4:
          return 'Severe - ICU';
        case 5:
          return 'Severe - Ventilator';
        default:
          return 'N/A';
      }
    },
    formattedSafeOrFree : function (type) {
      switch (type) {
        case 1:
          return 'Paid';
        case 2:
          return 'Free';
        default:
          return 'N/A';
      }
    },
    editData : function (id){
      window.open(
          '/admin/cases-payment-create?token=' + id,
          '_blank'
      );
      console.log(item);
    },
    checkEditButton(){
      return this.$userRole === 'healthpost' || this.$userRole === 'main' || this.$permissionId === '1';
    }

  }
}
</script>