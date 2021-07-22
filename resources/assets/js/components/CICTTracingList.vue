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
      <tr slot-scope="{item, removeItemOnSuccess}">
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
            <button v-on:click="contactList(item.token)" class="btn btn-secondary btn-sm" title="Contact List">
              <i class="fa fa-list" aria-hidden="true"> Contact List</i>
            </button>
            <button v-on:click="deletePatientData(item, removeItemOnSuccess)" class="btn btn-danger btn-sm" title="Delete Data">
              <i class="fa fa-trash" aria-hidden="true"> Delete</i>
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
    contactList : function (id){
      window.open(
          '/admin/cict-tracing/contact-list/' + id,
          '_self'
      );
    },

    deletePatientData: function (item, removeItemOnSuccess) {
      this.$swal({
        title: "Are you sure?",
        text: "Your data will be deleted.",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel please!",
        closeOnConfirm: false,
        closeOnCancel: false
      }).then((result) => {
        if (result.value) {
          axios.delete('/admin/cict-tracing/' + item.token)
              .then((response) => {
                if (response.data.message === 'success') {
                  removeItemOnSuccess(item);
                  this.$swal({
                    title: 'Record Deleted',
                    type: 'success',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                  })
                  this.healthpostSelected = null;
                  
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
          this.$swal("Cancelled", "Data not moved :)", "error");
        }
      })
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