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
        <th title="Latest Health Condition">Health Condition</th>
        <th>Paid / Free</th>
        <th>Register Date</th>
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
        <td>
          <div v-show="checkEditButton()">
            <button v-on:click="editData(item.id)" class="btn btn-primary btn-sm" title="Update Data">
              <i class="fa fa-edit" aria-hidden="true"> Update</i>
            </button>
          </div>
          <div v-show="checkDeleteButton()">
            <button v-on:click="deleteData(item.id)" class="btn btn-danger btn-sm" title="Delete Data">
              <i class="fa fa-trash" aria-hidden="true"> Delete</i>
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
import axios from "axios";

export default {
  components: {Filterable},
  data() {
    return {
      filterable: {
        url: '/data/api/cases-payment',
        orderables: [
          {title: 'Name', name: 'name'},
          {title: 'Age', name: 'age'},
          {title: 'Register Date', name: 'register_date_en'},
          {title: 'Created At', name: 'created_at'}
        ],
        filterGroups: [
          {
            name: 'Case',
            filters: [
              {title: 'Name', name: 'name', type: 'string'},
              {title: 'Age', name: 'age', type: 'numeric'},
              {title: 'Phone Number', name: 'phone', type: 'string'},
              {title: 'Register Date', name: 'register_date_en', type: 'datetime'},
              {title: 'Created At', name: 'created_at', type: 'datetime'}
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
    },
    deleteData : function(id){
      this.$swal({
        title: "Are you sure?",
        text: "You don\'t able to to retrieve this data.",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel please!",
        closeOnConfirm: false,
        closeOnCancel: false
      }).then((result) => {
        if (result.value) {
          axios.post('/api/v1/cases-payment/delete', {'id':id})
              .then((response) => {
                if (response.data.message === 'success') {
                  this.$swal({
                    title: 'Record Deleted',
                    type: 'success',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                  })
                  if (this.item){
                    this.$dlg.closeAll(function(){
                      // do something after all dialog closed
                    })
                  }
                } else {
                  this.$swal({
                    title: 'Oops. No record found.',
                    type: 'error',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                  })
                }
              })
        } else {
          this.$swal("Cancelled", "Your imaginary data is safe :)", "error");
        }
      })
    },
    checkEditButton(){
      var arr = this.$userPermissions.split(',');
      return this.$userRole === 'healthpost' || this.$userRole === 'main'|| this.$provincePermissionId === '1' ||  arr.includes('cases-payment');
    },
    checkDeleteButton() {
      // main super admin token
      return this.$userSessionToken === '5a4425' || this.$provincePermissionId === '1';
    }
  }
}
</script>